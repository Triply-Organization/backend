<?php

namespace App\Controller\API;

use App\Entity\Schedule;
use App\Request\OrderRequest;
use App\Service\OrderService;
use App\Traits\ResponseTrait;
use App\Transformer\OrderTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/orders', name: 'order_')]
class OrderController extends AbstractController
{
    use ResponseTrait;

    #[Route('/{id}', name: 'add', methods: 'POST')]
    #[IsGranted('ROLE_USER')]
    public function orderTour(
        Request            $request,
        OrderRequest       $orderRequest,
        OrderService       $orderService,
        ValidatorInterface $validator,
        OrderTransformer   $orderTransformer,
    ): JsonResponse
    {
        $requestData = $request->toArray();
        $order = $orderRequest->fromArray($requestData);
        $errors = $validator->validate($order);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }

        $orderService = $orderService->order($order);
        $result = $orderTransformer->toArray($orderService);

        return $this->success($result);
    }
}
