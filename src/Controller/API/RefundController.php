<?php

namespace App\Controller\API;

use App\Request\RefundRequest;
use App\Service\StripeService;
use App\Traits\ResponseTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/refund', name: 'refund_')]
class RefundController extends AbstractController
{
    use ResponseTrait;

    #[isGranted('ROLE_USER')]
    #[Route('/', name: 'stripe', methods: 'POST')]
    public function refund(
        Request $request,
        RefundRequest $refundRequest,
        ValidatorInterface $validator,
        StripeService $stripeService
    ): JsonResponse {
        $requestData = $request->toArray();
        $refundRequestData = $refundRequest->fromArray($requestData);
        $errors = $validator->validate($refundRequestData);

        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }

        $stripeService->refund($refundRequestData);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
