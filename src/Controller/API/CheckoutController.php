<?php

namespace App\Controller\API;

use App\Request\CheckoutRequest;
use App\Service\StripeService;
use App\Traits\ResponseTrait;
use Psr\Log\LoggerInterface;
use Stripe\Exception\ApiErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/checkout', name: 'api_')]
class CheckoutController
{
    use ResponseTrait;

    /**
     * @throws ApiErrorException
     */
    #[Route('/', name: 'checkout', methods: 'POST')]
    public function checkout(
        Request $request,
        CheckoutRequest $checkoutRequest,
        ValidatorInterface $validator,
        StripeService $stripeService
    ): JsonResponse {
        $requestData = $request->toArray();
        $checkoutRequestData = $checkoutRequest->fromArray($requestData);
        $errors = $validator->validate($checkoutRequestData);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }

        $checkoutSession = $stripeService->checkout($checkoutRequestData);
        return $this->success([[
            'checkoutURL' => $checkoutSession->url,
        ]]);
    }
}
