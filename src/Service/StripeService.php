<?php

namespace App\Service;

use App\Entity\Bill;
use App\Repository\BillRepository;
use App\Repository\OrderRepository;
use App\Repository\ScheduleRepository;
use App\Repository\VoucherRepository;
use App\Request\CheckoutRequest;
use App\Request\RefundRequest;
use PHPMailer\PHPMailer\Exception;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StripeService
{
    private const USD_CURRENCY = 'usd';
    private const CHECK_COMPLETED = 'checkout.session.completed';
    private const DAYS_REMAIN_FIFTEEN = 15;
    private const DAYS_REMAIN_SEVEN = 7;
    private const DAYS_REMAIN_THREE = 3;
    private const PERCENT_MINUS_INIT = 1;
    private const PERCENT_MINUS_FIFTEEN_DAYS = 0.3;
    private const PERCENT_MINUS_SEVEN_DAYS = 0.5;
    private const USD_CONVERT = 100;
    private const VND_CONVERT = 1000;

    private ParameterBagInterface $params;
    private BillRepository $billRepository;
    private SendMailService $sendMailService;
    private OrderRepository $orderRepository;
    private ScheduleRepository $scheduleRepository;
    private BillService $billService;
    private VoucherRepository $voucherRepository;

    public function __construct(
        ParameterBagInterface $params,
        BillRepository $billRepository,
        SendMailService $sendMailService,
        OrderRepository $orderRepository,
        ScheduleRepository $scheduleRepository,
        VoucherRepository $voucherRepository,
        BillService $billService
    ) {
        $this->params = $params;
        $this->billRepository = $billRepository;
        $this->sendMailService = $sendMailService;
        $this->orderRepository = $orderRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->voucherRepository = $voucherRepository;
        $this->billService = $billService;
        $this->stripe = new StripeClient($this->params->get('stripe_secret_key'));
    }

    /**
     * @throws ApiErrorException
     */
    public function checkout(CheckoutRequest $checkoutRequestData): Session
    {
        $stripeSK = $this->params->get('stripe_secret_key');
        Stripe::setApiKey($stripeSK);
        if ($checkoutRequestData->getVoucherId()) {
            $this->minusVoucher($checkoutRequestData->getVoucherId());
        }
        return Session::create($this->sessionConfig($checkoutRequestData));
    }

    /**
     * @throws ApiErrorException
     */
    public function refund(RefundRequest $refundRequestData): void
    {
        $bill = $this->billRepository->find($refundRequestData->getBillId());

        if (!$bill) {
            throw new NotFoundHttpException();
        }

        $refundAmount = $this->getAmountRefund($bill, $refundRequestData);

        $this->stripe->refunds->create([
            'payment_intent' => $refundRequestData->getStripeId(),
            'amount' => $refundAmount
        ]);

        $this->completeRefund($refundRequestData);
    }

    /**
     * @throws Exception
     * @throws ApiErrorException
     */
    public function eventHandler(array $data, string $type, array $metadata): void
    {
        if ($type === self::CHECK_COMPLETED) {
            $order = $this->orderRepository->find($metadata['orderId']);
            $schedule = $this->scheduleRepository->find($metadata['scheduleId']);
            if (!$order || !$schedule) {
                throw new NotFoundHttpException();
            }
            $bill = $this->billService->add($metadata, $data);

            $order->setStatus('paid');
            $order->setBill($bill);
            $this->orderRepository->add($order, true);

            $schedule->setTicketRemain($schedule->getTicketRemain() - 1);
            $schedule->setUpdatedAt(new \DateTimeImmutable());
            $this->scheduleRepository->add($schedule, true);
            $this->sendMailService->sendBillMail($data['customer_details']['email'], 'Thank you', $bill);

            $this->stripe->checkout->sessions->expire(
                $data['id'],
                []
            );
        }
    }

    private function sessionConfig(CheckoutRequest $checkoutRequestData): array
    {
        $locale = 'vi';
        $totalPrice = $checkoutRequestData->getTotalPrice() * self::VND_CONVERT;
        if ($checkoutRequestData->getCurrency() === self::USD_CURRENCY) {
            $locale = 'en';
            $totalPrice = $checkoutRequestData->getTotalPrice() * self::USD_CONVERT;
        }
        return [
            'line_items' => [[
                'price_data' => [
                    'currency' => $checkoutRequestData->getCurrency(),
                    'product_data' => [
                        'name' => $checkoutRequestData->getTourName(),
                    ],
                    'unit_amount' => $totalPrice,
                ],
                'quantity' => 1,
            ]],
            'metadata' => $this->metadata($checkoutRequestData),
            'customer_email' => $checkoutRequestData->getEmail(),
            'locale' => $locale,
            'submit_type' => 'book',
            'mode' => 'payment',
            'success_url' => $this->params->get('stripe_payment_success_url') . $checkoutRequestData->getOrderId(),
            'cancel_url' => $this->params->get('stripe_payment_cancel_url') . $checkoutRequestData->getOrderId(),
        ];
    }

    private function metadata(CheckoutRequest $checkoutRequestData): array
    {
        return [
            'tourId' => $checkoutRequestData->getTourId(),
            'orderId' => $checkoutRequestData->getOrderId(),
            'scheduleId' => $checkoutRequestData->getScheduleId(),
            'totalPrice' => $checkoutRequestData->getTotalPrice(),
            'discountPrice' => $checkoutRequestData->getDiscountPrice(),
            'taxPrice' => $checkoutRequestData->getTaxPrice(),
        ];
    }

    private function minusVoucher(int $voucherId): void
    {
        $voucher = $this->voucherRepository->find($voucherId);
        if (!$voucher) {
            throw new NotFoundHttpException();
        }
        $voucherRemain = $voucher->getRemain();
        $voucher->setRemain($voucherRemain - 1);
        $this->voucherRepository->add($voucher, true);
    }


    private function getAmountRefund(Bill $bill, RefundRequest $refundRequestData): float
    {
        $daysRemain = $refundRequestData->getDayRemain();
        $totalPrice = $bill->getTotalPrice() * self::VND_CONVERT;
        $percentMinus = self::PERCENT_MINUS_INIT;

        if ($refundRequestData->getCurrency() === self::USD_CURRENCY) {
            $totalPrice = $bill->getTotalPrice() * self::USD_CONVERT;
        }

        if ($daysRemain <= self::DAYS_REMAIN_FIFTEEN) {
            $percentMinus = self::PERCENT_MINUS_FIFTEEN_DAYS;
        }
        if ($daysRemain >= self::DAYS_REMAIN_THREE && $daysRemain <= self::DAYS_REMAIN_SEVEN) {
            $percentMinus = self::PERCENT_MINUS_SEVEN_DAYS;
        }

        return $totalPrice - ($totalPrice * $percentMinus);
    }

    private function completeRefund(RefundRequest $refundRequestData): void
    {
        $order = $this->orderRepository->find($refundRequestData->getOrderId());
        if (!$order) {
            throw new NotFoundHttpException();
        }
        $order->setStatus('refund');
        $this->orderRepository->add($order, true);
    }
}
