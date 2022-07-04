<?php

namespace App\Service;

use App\Entity\Bill;
use App\Entity\Order;
use App\Entity\PriceList;
use App\Entity\Ticket;
use App\Entity\Schedule;
use App\Entity\User;
use App\Entity\Voucher;
use App\FindException\findTicketException;
use App\Repository\OrderRepository;
use App\Repository\TicketRepository;
use App\Repository\PriceListRepository;
use App\Repository\VoucherRepository;
use App\Request\OrderRequest;
use App\Traits\ResponseTrait;
use Symfony\Component\Security\Core\Security;

use function PHPUnit\Framework\throwException;

class OrderService
{
    use ResponseTrait;

    private OrderRepository $orderRepository;
    private Security $security;
    private TicketRepository $ticketRepository;
    private PriceListRepository $priceListRepository;
    private VoucherRepository $voucherRepository;

    public function __construct(
        OrderRepository $orderRepository,
        Security $security,
        TicketRepository $ticketRepository,
        PriceListRepository $priceListRepository,
        VoucherRepository $voucherRepository,
    ) {
        $this->orderRepository = $orderRepository;
        $this->security = $security;
        $this->ticketRepository = $ticketRepository;
        $this->priceListRepository = $priceListRepository;
        $this->voucherRepository = $voucherRepository;
    }

    public function checkoutUserOfOrder(Order $order)
    {
        $currentUser = $this->security->getUser();
        $roles = $currentUser->getRoles();
        if($roles['role'] === 'ROLE_USER') {
            if($currentUser->getId() !== $order->getUser()->getId()) {
                return false;
            }
        }
        return true;
    }

    public function order(OrderRequest $orderRequest)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->security->getUser();
        $order = new Order();
        if ($orderRequest->getDiscountId() === null) {
            $discount = null;
        } else {
            $discount = $this->voucherRepository->find($orderRequest->getDiscountId());
        }

        $order->setDiscount($discount)
            ->setUser($currentUser)
            ->setTotalPrice(0);
        $this->orderRepository->add($order, true);
        $totalPrice = $this->addTicket($orderRequest, $order);
        $this->orderRepository->add($order->setTotalPrice($totalPrice), true);

        return $order;
    }

    public function findOneTicketOfOrder(Order $order)
    {
        return $this->ticketRepository->findOneBy(array('orderName' => $order));
    }

    public function findTicketsOfOrder(Order $order)
    {
        return $this->ticketRepository->findBy(array('orderName' => $order));
    }

    private function addTicket(OrderRequest $orderRequest, Order $order)
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

    private function createTicket(PriceList $priceList, $amount)
    {
        $ticket = new Ticket();
        $ticket->setPriceList($priceList)
            ->setAmount($amount)
            ->setTotalPrice($priceList->getPrice() * $amount);
        return $ticket;
    }

    private function addChildrenTicket(OrderRequest $orderRequest, Order $order)
    {
        $price = 0;
        $priceListChildren = $this->priceListRepository->find($orderRequest->getChildren()['priceListId']);
        if ($priceListChildren !== null) {
            $ticket = $this->createTicket($priceListChildren, $orderRequest->getChildren()['amount']);
            $ticket->setOrderName($order);
            $this->ticketRepository->add($ticket, true);
            $price = $ticket->getTotalPrice();
        }
        return $price;
    }

    private function addYouthTicket(OrderRequest $orderRequest, Order $order)
    {
        $price = 0;
        $priceListYouth = $this->priceListRepository->find($orderRequest->getYouth()['priceListId']);
        if ($priceListYouth !== null) {
            $ticket = $this->createTicket($priceListYouth, $orderRequest->getYouth()['amount']);
            $ticket->setOrderName($order);
            $this->ticketRepository->add($ticket, true);
            $price = $ticket->getTotalPrice();
        }
        return $price;
    }

    private function addAdultTicket(OrderRequest $orderRequest, Order $order)
    {
        $price = 0;
        $priceListAdult = $this->priceListRepository->find($orderRequest->getAdult()['priceListId']);
        if ($priceListAdult !== null) {
            $ticket = $this->createTicket($priceListAdult, $orderRequest->getAdult()['amount']);
            $ticket->setOrderName($order);
            $this->ticketRepository->add($ticket, true);
            $price = $ticket->getTotalPrice();
        }
        return $price;
    }
}
