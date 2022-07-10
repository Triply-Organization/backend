<?php

namespace App\Tests\Unit\Service;

use App\Entity\Order;
use App\Entity\PriceList;
use App\Entity\Ticket;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\PriceListRepository;
use App\Repository\TaxRepository;
use App\Repository\TicketRepository;
use App\Repository\VoucherRepository;
use App\Request\OrderRequest;
use App\Service\OrderService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class OrderServiceTest extends TestCase
{
    private $orderRepository;
    private $security;
    private $ticketRepository;
    private $priceListRepository;
    private $voucherRepository;
    private $taxRepository;

    public function setUp(): void
    {
        $this->orderRepositoryMock = $this->getMockBuilder(OrderRepository::class)->disableOriginalConstructor()->getMock();
        $this->securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $this->ticketRepositoryMock = $this->getMockBuilder(TicketRepository::class)->disableOriginalConstructor()->getMock();
        $this->priceListRepositoryMock = $this->getMockBuilder(PriceListRepository::class)->disableOriginalConstructor()->getMock();
        $this->voucherRepositoryMock = $this->getMockBuilder(VoucherRepository::class)->disableOriginalConstructor()->getMock();
        $this->taxRepositoryMock = $this->getMockBuilder(TaxRepository::class)->disableOriginalConstructor()->getMock();
    }

    public function testCheckUserOfOrderWithRight()
    {
        $order = new Order();
        $userMock = $this->getMockBuilder(User::class)->getMock();
        $userMock->method('getId')->willReturn(1);
        $order->setUser($userMock);
        $this->securityMock->expects($this->once())->method('getUser')->willReturn($userMock);
        $orderService = new OrderService($this->orderRepositoryMock, $this->securityMock, $this->ticketRepositoryMock,
            $this->priceListRepositoryMock, $this->voucherRepositoryMock, $this->taxRepositoryMock);
        $result = $orderService->checkUserOfOrder($order);
        $this->assertTrue($result);
    }

    public function testCheckUserOfOrderWithFail()
    {
        $order = new Order();
        $userMock = $this->getMockBuilder(User::class)->getMock();
        $userMock->method('getId')->willReturn(1);
        $userFailMock = $this->getMockBuilder(User::class)->getMock();
        $userFailMock->method('getId')->willReturn(2);
        $order->setUser($userFailMock);
        $this->securityMock->expects($this->once())->method('getUser')->willReturn($userMock);
        $orderService = new OrderService($this->orderRepositoryMock, $this->securityMock, $this->ticketRepositoryMock,
            $this->priceListRepositoryMock, $this->voucherRepositoryMock, $this->taxRepositoryMock);
        $result = $orderService->checkUserOfOrder($order);
        $this->assertFalse($result);
    }

    public function testFindOneTicketOfOrder()
    {
        $order = new Order();
        $ticket = new Ticket();
        $this->ticketRepositoryMock->expects($this->once())->method('findOneBy')->willReturn($ticket);
        $orderService = new OrderService($this->orderRepositoryMock, $this->securityMock, $this->ticketRepositoryMock,
            $this->priceListRepositoryMock, $this->voucherRepositoryMock, $this->taxRepositoryMock);
        $result = $orderService->findOneTicketOfOrder($order);
        $this->assertInstanceOf(Ticket::class, $result);
    }

    public function testFindTicketOfOrder()
    {
        $order = new Order();
        $ticket = new Ticket();
        $tickets = [$ticket];
        $this->ticketRepositoryMock->expects($this->once())->method('findBy')->willReturn($tickets);
        $orderService = new OrderService($this->orderRepositoryMock, $this->securityMock, $this->ticketRepositoryMock,
            $this->priceListRepositoryMock, $this->voucherRepositoryMock, $this->taxRepositoryMock);
        $result = $orderService->findTicketsOfOrder($order);
        $this->assertIsArray($result);
    }

    public function testAddTicket()
    {
        $orderRequest = new OrderRequest();
        $order = new Order();
        $orderServiceMock = $this->getMockBuilder(OrderService::class)
            ->onlyMethods(['addChildrenTicket', 'addYouthTicket', 'addAdultTicket'])
            ->disableOriginalConstructor()->getMock();
        $orderServiceMock->expects($this->once())->method('addChildrenTicket')->willReturn(5.2);
        $orderServiceMock->expects($this->once())->method('addYouthTicket')->willReturn(5);
        $orderServiceMock->expects($this->once())->method('addAdultTicket')->willReturn(5);
        $result = $orderServiceMock->addTicket($orderRequest, $order);
        $this->assertIsFloat($result);
    }

    public function testCreateTicket()
    {
        $priceList = new PriceList();
        $amount = 50;
        $orderService = new OrderService($this->orderRepositoryMock, $this->securityMock, $this->ticketRepositoryMock,
            $this->priceListRepositoryMock, $this->voucherRepositoryMock, $this->taxRepositoryMock);
        $result = $orderService->createTicket($priceList, $amount);
        $this->assertInstanceOf(Ticket::class, $result);
    }
}
