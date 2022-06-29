<?php

namespace App\Service;

use App\Request\CheckoutRequest;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentService
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    /**
     * @throws ApiErrorException
     */
    public function stripeCheckout(CheckoutRequest $checkoutRequestData): string
    {
        Stripe::setApiKey($this->params->get('stripe_secret_key'));
        $session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'vnd',
                    'product_data' => [
                        'name' => $checkoutRequestData->getName(),
                    ],
                    'unit_amount' => $checkoutRequestData->getAmount(),
                ],
                'quantity' => $checkoutRequestData->getQuantity(),
            ]],
            'mode' => 'payment',
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
        ]);
        return $session->url;
    }
}
