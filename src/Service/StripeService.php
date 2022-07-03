<?php

namespace App\Service;

use App\Entity\Bill;
use App\Repository\BillRepository;
use App\Request\CheckoutRequest;
use PHPMailer\PHPMailer\Exception;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\TaxRate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class StripeService
{
    private const CHECK_COMPLETED = 'checkout.session.completed';

    private ParameterBagInterface $params;
    private StripeClient $stripe;
    private BillRepository $billRepository;
    private SendMailService $sendMailService;

    public function __construct(
        ParameterBagInterface $params,
        BillRepository $billRepository,
        SendMailService $sendMailService
    ) {
        $this->params = $params;
        $this->stripe = new StripeClient($this->params->get('stripe_secret_key'));
        $this->billRepository = $billRepository;
        $this->sendMailService = $sendMailService;
    }

    /**
     * @throws ApiErrorException
     */
    public function checkout(CheckoutRequest $checkoutRequestData): Session
    {
        $stripeSK = $this->params->get('stripe_secret_key');
        Stripe::setApiKey($stripeSK);

        $taxRateId = 'txr_1LGNaYBshpup8grmjONnWYWz';

        if ($checkoutRequestData->getCurrency() === 'usd')
        {
            $taxRateId = 'txr_1LGeepBshpup8grmsXrotsGu';
        }

        return Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => $checkoutRequestData->getCurrency(),
                    'product_data' => [
                        'name' => $checkoutRequestData->getName(),
                    ],
                    'unit_amount' => $checkoutRequestData->getAmount(),
                    'tax_behavior' => 'exclusive',
                ],
                'quantity' => 1,
                'tax_rates' => [$taxRateId]
            ]],
            'mode' => 'payment',
            'allow_promotion_codes' => true,
            'success_url' => 'http://localhost:3000/confirmation/1',
            'cancel_url' => 'https://example.com/cancel',
        ]);
    }

    /**
     * @throws Exception
     */
    public function eventHandler(array $data, string $type): void
    {
        $bill = new Bill;
        if ($type === self::CHECK_COMPLETED) {
            $bill->setTotalPrice($data['amount_total']);
            $bill->setTax($data['total_details']['amount_tax']);
            $bill->setDiscount($data['total_details']['amount_discount']);
            $this->billRepository->add($bill, true);

            $this->sendMailService->sendBillMail($data['customer_details']['email'], 'Thank you', $bill);
        }
    }

    /**
     * @throws ApiErrorException
     */
    public function createPromoCode(int $percent, int $durationInMonth, string $code): void
    {

        $coupons = $this->stripe->coupons->create(
            [
                'percent_off' => $percent,
                'duration' => 'repeating',
                'duration_in_months' => $durationInMonth,
            ]
        );

        $this->stripe->promotionCodes->create(
            ['coupon' => $coupons->id, 'code' => $code]
        );
    }

    /**
     * @throws ApiErrorException
     */
    public function createTax(string $taxName, string $taxOfCountry, int $taxPercentage): TaxRate
    {
        return $this->stripe->taxRates->create([
            'display_name' => $taxName,
            'description' => $taxName . ' ', $taxOfCountry,
            'jurisdiction' => 'DE',
            'percentage' => $taxPercentage,
            'inclusive' => false,
        ]);
    }
}
