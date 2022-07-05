<?php

namespace App\Service;

use App\Entity\Bill;
use App\Repository\BillRepository;
use App\Repository\OrderRepository;
use App\Repository\ScheduleRepository;
use App\Repository\TourRepository;
use App\Repository\VoucherRepository;
use App\Request\CheckoutRequest;
use PHPMailer\PHPMailer\Exception;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class StripeService
{
    private const CHECK_COMPLETED = 'checkout.session.completed';

    private ParameterBagInterface $params;
    private BillRepository $billRepository;
    private SendMailService $sendMailService;
    private TourRepository $tourRepository;
    private OrderRepository $orderRepository;
    private ScheduleRepository $scheduleRepository;
    private VoucherRepository $voucherRepository;

    public function __construct(
        ParameterBagInterface $params,
        BillRepository $billRepository,
        SendMailService $sendMailService,
        TourRepository $tourRepository,
        OrderRepository $orderRepository,
        ScheduleRepository $scheduleRepository,
        VoucherRepository $voucherRepository
    ) {

        $this->params = $params;
        $this->billRepository = $billRepository;
        $this->sendMailService = $sendMailService;
        $this->tourRepository = $tourRepository;
        $this->orderRepository = $orderRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->voucherRepository = $voucherRepository;
    }

    /**
     * @throws ApiErrorException
     */
    public function checkout(CheckoutRequest $checkoutRequestData): Session
    {
        $stripeSK = $this->params->get('stripe_secret_key');
        Stripe::setApiKey($stripeSK);
        $this->minusVoucher($checkoutRequestData->getVoucherId());
        return Session::create($this->sessionConfig($checkoutRequestData));
    }

    /**
     * @throws Exception
     */
    public function eventHandler(array $data, string $type, array $metadata): void
    {
        $bill = new Bill();
        if ($type === self::CHECK_COMPLETED) {
            $order = $this->orderRepository->find($metadata['orderId']);
            $schedule = $this->scheduleRepository->find($metadata['scheduleId']);

            $bill->setTotalPrice($metadata['totalPrice']);
            $bill->setTax($metadata['taxPrice']);
            $bill->setDiscount($metadata['discountPrice']);
            $bill->setStripePaymentId($data['payment_intent']);
            $bill->setCurrency($data['currency']);

            $this->billRepository->add($bill, true);

            $order->setStatus('paid');
            $order->setBill($bill);
            $this->orderRepository->add($order, true);

            $schedule->setTicketRemain($schedule->getTicketRemain() - 1);
            $schedule->setUpdatedAt(new \DateTimeImmutable());
            $this->scheduleRepository->add($schedule, true);

            $this->sendMailService->sendBillMail($data['customer_details']['email'], 'Thank you', $bill);
        }
    }

    private function sessionConfig(CheckoutRequest $checkoutRequestData): array
    {
        $languages = 'vi';
        $totalPrice = $checkoutRequestData->getTotalPrice() * 1000;
        $tour = $this->tourRepository->find($checkoutRequestData->getTourId());
        $taxRateId = 'txr_1LGNaYBshpup8grmjONnWYWz';
        if ($checkoutRequestData->getCurrency() === 'usd') {
            $languages = 'en';
            $totalPrice = $checkoutRequestData->getTotalPrice() * 100;
            $taxRateId = 'txr_1LGeepBshpup8grmsXrotsGu';
        }
        return [
            'line_items' => [[
                'price_data' => [
                    'currency' => $checkoutRequestData->getCurrency(),
                    'product_data' => [
                        'name' => $checkoutRequestData->getTourName(),
                    ],
                    'unit_amount' => $totalPrice,
                    'tax_behavior' => 'exclusive',
                ],
                'quantity' => 1,
                'tax_rates' => [$taxRateId]
            ]],
            'metadata' => $this->metadata($checkoutRequestData),
            'customer_email' => $checkoutRequestData->getEmail(),
            'locale' => $languages,
            'submit_type' => 'book',
            'mode' => 'payment',
            'success_url' => 'http://localhost:3000/confirmation/1',
            'cancel_url' => 'http://localhost:3000/',
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
        $voucherRemain = $voucher->getRemain();
        $voucher->setRemain($voucherRemain - 1);
        $this->voucherRepository->add($voucher, true);
    }
}
