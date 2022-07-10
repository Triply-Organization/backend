<?php

namespace App\Controller\API;

use App\Entity\Order;
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

    #[Route('/{id<\d+>}', name: 'details', methods: 'GET')]
    #[IsGranted('ROLE_USER')]
    public function orderDetails(
        Order $order,
        OrderService $orderService,
        OrderTransformer $orderTransformer
    ): JsonResponse {
        $checkUser = $orderService->checkUserOfOrder($order);
        if ($checkUser === false) {
            return $this->errors(['Something wrong']);
        }
        return $this->success($orderTransformer->detailToArray($order));
    }

    #[Route('/', name: 'add', methods: 'POST')]
    #[IsGranted('ROLE_USER')]
    public function orderTour(
        Request $request,
        OrderRequest $orderRequest,
        OrderService $orderService,
        ValidatorInterface $validator,
        OrderTransformer $orderTransformer,
    ): JsonResponse {
        $user = $this->getUser();
        $requestData = $request->toArray();
        $order = $orderRequest->fromArray($requestData);
        $errors = $validator->validate($order);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $orderData = $orderService->order($order, $user);
        $result = $orderTransformer->orderToArray($orderData);

        return $this->success($result);
    }
}
