<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Bill;
use App\Entity\Order;
use App\Entity\Tax;
use App\Entity\Voucher;
use App\Service\OrderService;
use App\Service\VoucherService;
use App\Transformer\OrderTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OrderTransformerTest extends TestCase
{
    private $orderServiceMock;
    private $voucherServiceMock;
    private $paramsMock;

    public function setUp(): void
    {
        $this->orderServiceMock = $this->getMockBuilder(OrderService::class)->disableOriginalConstructor()->getMock();
        $this->voucherServiceMock = $this->getMockBuilder(VoucherService::class)->disableOriginalConstructor()->getMock();
        $this->paramsMock = $this->getMockBuilder(ParameterBagInterface::class)->disableOriginalConstructor()->getMock();
    }

    public function testOrderToArray()
    {
        $orderMock = $this->getMockBuilder(Order::class)->getMock();
        $orderMock->method('getId')->willReturn(1);
        $taxMock = $this->getMockBuilder(Tax::class)->getMock();
        $taxMock->method('getId')->willReturn(1);
        $taxMock->setCurrency('string')->setPercent(10);
        $orderMock->method('getTax')->willReturn($taxMock);
        $voucherMock = $this->getMockBuilder(Voucher::class)->getMock();
        $voucherMock->method('getId')->willReturn(1);
        $voucherMock->setCode('string')->setDiscount(10)->setRemain(10);
        $this->voucherServiceMock->expects($this->once())->method('getAllDisCount')->willReturn([$voucherMock]);
        $orderTransformerMock = $this->getMockBuilder(OrderTransformer::class)->onlyMethods(['toArray'])
        ->setConstructorArgs([$this->paramsMock, $this->orderServiceMock, $this->voucherServiceMock])->getMock();
        $result = $orderTransformerMock->orderToArray($orderMock);
        $this->assertIsArray($result);
    }

    public function testDetailToArray()
    {
        $orderMock = $this->getMockBuilder(Order::class)->getMock();
        $orderMock->method('getId')->willReturn(1);
        $billMock = $this->getMockBuilder(Bill::class)->getMock();
        $billMock->method('getId')->willReturn(1);
        $billMock->setTotalPrice(5.0)->setCurrency('string')->setDiscount(5.0)
        ->setStripePaymentId('string')->setTax(5.0);
        $orderMock->method('getBill')->willReturn($billMock);
        $orderTransformerMock = $this->getMockBuilder(OrderTransformer::class)->onlyMethods(['toArray'])
            ->setConstructorArgs([$this->paramsMock, $this->orderServiceMock, $this->voucherServiceMock])->getMock();
        $result = $orderTransformerMock->detailToArray($orderMock);
        $this->assertIsArray($result);
    }

    public function testTicketInfoReturnWithChildren()
    {
        $ticketInfo['typeTicket'] = 'children';
        $ticketInfo['idTicket'] = 1;
        $ticketInfo['amount'] = 10;
        $ticketInfo['priceTick'] = 10;
        $ticketInfos[] = $ticketInfo;

        $orderTransformer = new OrderTransformer($this->paramsMock, $this->orderServiceMock, $this->voucherServiceMock);
        $result = $orderTransformer->ticketInfoReturn($ticketInfos);
        $this->assertIsArray($result);
    }

    public function testTicketInfoReturnWithYouth()
    {
        $ticketInfo['typeTicket'] = 'youth';
        $ticketInfo['idTicket'] = 1;
        $ticketInfo['amount'] = 10;
        $ticketInfo['priceTick'] = 10;
        $ticketInfos[] = $ticketInfo;

        $orderTransformer = new OrderTransformer($this->paramsMock, $this->orderServiceMock, $this->voucherServiceMock);
        $result = $orderTransformer->ticketInfoReturn($ticketInfos);
        $this->assertIsArray($result);
    }

    public function testTicketInfoReturnWithAdult()
    {
        $ticketInfo['typeTicket'] = 'adult';
        $ticketInfo['idTicket'] = 1;
        $ticketInfo['amount'] = 10;
        $ticketInfo['priceTick'] = 10;
        $ticketInfos[] = $ticketInfo;

        $orderTransformer = new OrderTransformer($this->paramsMock, $this->orderServiceMock, $this->voucherServiceMock);
        $result = $orderTransformer->ticketInfoReturn($ticketInfos);
        $this->assertIsArray($result);
    }
}
