<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\PriceList;
use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\TaxRepository;
use App\Repository\TicketRepository;
use App\Repository\PriceListRepository;
use App\Repository\VoucherRepository;
use App\Request\OrderRequest;
use App\Traits\ResponseTrait;
use Symfony\Component\Security\Core\Security;

class OrderService
{
    use ResponseTrait;

    const STATUS_DEFAULT = 'unpaid';

    private OrderRepository $orderRepository;
    private Security $security;
    private TicketRepository $ticketRepository;
    private PriceListRepository $priceListRepository;
    private VoucherRepository $voucherRepository;
    private TaxRepository $taxRepository;

    public function __construct(
        OrderRepository     $orderRepository,
        Security            $security,
        TicketRepository    $ticketRepository,
        PriceListRepository $priceListRepository,
        VoucherRepository   $voucherRepository,
        TaxRepository       $taxRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->security = $security;
        $this->ticketRepository = $ticketRepository;
        $this->priceListRepository = $priceListRepository;
        $this->voucherRepository = $voucherRepository;
        $this->taxRepository = $taxRepository;
    }

    public function checkUserOfOrder(Order $order): bool
    {
        $currentUser = $this->security->getUser();
        if ($currentUser->getId() !== $order->getUser()->getId()) {
            return false;
        }

        return true;
    }

    public function order(OrderRequest $orderRequest, User $user): Order
    {
        $tax = $this->taxRepository->findOneBy(['currency' => $orderRequest->getCurrency()]);
        $order = new Order();
        $order->setDiscount(null)
            ->setUser($user)
            ->setTotalPrice(0)
            ->setTax($tax)
            ->setStatus(self::STATUS_DEFAULT);
        $this->orderRepository->add($order, true);
        $totalPrice = $this->addTicket($orderRequest, $order);
        $this->orderRepository->add($order->setTotalPrice($totalPrice), true);

        return $order;
    }

    public function findOneTicketOfOrder(Order $order): ?Ticket
    {
        return $this->ticketRepository->findOneBy(array('orderName' => $order));
    }

    public function findTicketsOfOrder(Order $order): array
    {
        return $this->ticketRepository->findBy(array('orderName' => $order));
    }

    public function addTicket(OrderRequest $orderRequest, Order $order): float|int|null
    {
        $totalpirce = 0;
        if ($orderRequest->getChildren() !== []) {
            $priceTicketChildren = $this->addChildrenTicket($orderRequest, $order);
            $totalpirce = $totalpirce + $priceTicketChildren;
        }
        if ($orderRequest->getYouth() !== []) {
            $priceTicketYouth = $this->addYouthTicket($orderRequest, $order);
            $totalpirce = $totalpirce + $priceTicketYouth;
        }
        if ($orderRequest->getAdult() !== []) {
            $priceTicketAdult = $this->addAdultTicket($orderRequest, $order);
            $totalpirce = $totalpirce + $priceTicketAdult;
        }

        return $totalpirce;
    }

    public function createTicket(PriceList $priceList, $amount): Ticket
    {
        $ticket = new Ticket();
        $ticket->setPriceList($priceList)
            ->setAmount($amount)
            ->setTotalPrice($priceList->getPrice() * $amount);
        return $ticket;
    }

    public function addChildrenTicket(OrderRequest $orderRequest, Order $order): float|int|null
    {
        $price = 0;
        $priceListChildren = $this->priceListRepository->find($orderRequest->getChildren()['priceListId']);
        if ($priceListChildren) {
            $ticket = $this->createTicket($priceListChildren, $orderRequest->getChildren()['amount']);
            $ticket->setOrderName($order);
            $this->ticketRepository->add($ticket, true);
            $price = $ticket->getTotalPrice();
        }
        return $price;
    }

    public function addYouthTicket(OrderRequest $orderRequest, Order $order): float|int|null
    {
        $price = 0;
        $priceListYouth = $this->priceListRepository->find($orderRequest->getYouth()['priceListId']);
        if ($priceListYouth) {
            $ticket = $this->createTicket($priceListYouth, $orderRequest->getYouth()['amount']);
            $ticket->setOrderName($order);
            $this->ticketRepository->add($ticket, true);
            $price = $ticket->getTotalPrice();
        }
        return $price;
    }

    public function addAdultTicket(OrderRequest $orderRequest, Order $order): float|int|null
    {
        $price = 0;
        $priceListAdult = $this->priceListRepository->find($orderRequest->getAdult()['priceListId']);
        if ($priceListAdult) {
            $ticket = $this->createTicket($priceListAdult, $orderRequest->getAdult()['amount']);
            $ticket->setOrderName($order);
            $this->ticketRepository->add($ticket, true);
            $price = $ticket->getTotalPrice();
        }

        return $price;
    }
}
