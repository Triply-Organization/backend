<?php

namespace App\Service;

use App\Entity\Ticket;
use App\Entity\Schedule;
use App\Entity\User;
use App\Repository\TicketRepository;
use App\Repository\PriceListRepository;
use App\Request\OrderRequest;
use Symfony\Component\Security\Core\Security;

class OrderService
{
    private TicketRepository $orderRepository;
    private Security $security;
    private PriceListRepository $ticketRepository;

    public function __construct(
        TicketRepository    $orderRepository,
        Security            $security,
        PriceListRepository $ticketRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->security = $security;
        $this->ticketRepository = $ticketRepository;
    }

    public function order(OrderRequest $orderRequest)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->security->getUser();
        $result = [];
        if ($orderRequest->getChildren() !== null) {
            $result['children'] = $this->orderTicketChildren($currentUser, $orderRequest);
        }

        if ($orderRequest->getYouth() !== null) {
            $result['youth'] = $this->orderTicketYouth($currentUser, $orderRequest);
        }

        if ($orderRequest->getAdult() !== null) {
            $result['adult'] = $this->orderTicketAdult($currentUser, $orderRequest);
        }
        return $result;
    }

    private function orderTicketChildren($currentUser, OrderRequest $orderRequest): Ticket
    {
        $order = new Ticket();
        $order->setUser($currentUser);
        $ticket = $this->ticketRepository->find($orderRequest->getChildren()['id']);
        $order->setAmount($orderRequest->getChildren()['amount'])
            ->setTicket($ticket);
        $this->orderRepository->add($order, true);

        return $order;
    }

    private function orderTicketYouth($currentUser, OrderRequest $orderRequest)
    {
        $order = new Ticket();
        $order->setUser($currentUser);
        $ticket = $this->ticketRepository->find($orderRequest->getYouth()['id']);
        $order->setAmount($orderRequest->getYouth()['amount'])
            ->setTicket($ticket);
        $this->orderRepository->add($order, true);

        return $order;
    }

    private function orderTicketAdult($currentUser, OrderRequest $orderRequest)
    {
        $order = new Ticket();
        $order->setUser($currentUser);
        $ticket = $this->ticketRepository->find($orderRequest->getAdult()['id']);
        $order->setAmount($orderRequest->getAdult()['amount'])
            ->setTicket($ticket);
        $this->orderRepository->add($order, true);

        return $order;
    }
}
