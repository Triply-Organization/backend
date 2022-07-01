<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Schedule;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Request\OrderRequest;
use Symfony\Component\Security\Core\Security;

class OrderService
{
    private OrderRepository $orderRepository;
    private Security $security;

    public function __construct(
        OrderRepository $orderRepository,
        Security        $security
    )
    {
        $this->orderRepository = $orderRepository;
        $this->security = $security;
    }

    public function order(Schedule $schedule, OrderRequest $orderRequest)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->security->getUser();
        $order = new Order();
        if ($orderRequest['children'] === null) {
            $order->setUser($currentUser)
                ->setSchedule($schedule)
                ->setAmount($orderRequest['children']);
        }

    }
}