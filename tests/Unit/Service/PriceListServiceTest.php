<?php

namespace App\Tests\Unit\Service;

use App\Entity\PriceList;
use App\Entity\Schedule;
use App\Entity\TicketType;
use App\Repository\PriceListRepository;
use App\Repository\TicketTypeRepository;
use App\Request\ScheduleRequest;
use App\Request\ScheduleUpdateRequest;
use App\Service\PriceListService;
use App\Transformer\PriceListTransformer;
use PHPUnit\Framework\TestCase;

class PriceListServiceTest extends TestCase
{
    private $priceListRepositoryMock;
    private $priceListTransformerMock;
    private $ticketTypeRepositoryMock;

    public function setUp(): void
    {
        $this->priceListRepositoryMock = $this->getMockBuilder(PriceListRepository::class)->disableOriginalConstructor()->getMock();
        $this->priceListTransformerMock = $this->getMockBuilder(PriceListTransformer::class)->getMock();
        $this->ticketTypeRepositoryMock = $this->getMockBuilder(TicketTypeRepository::class)->disableOriginalConstructor()->getMock();
    }

    public function testGetTicketType()
    {
        $priceList = new PriceList();
        $priceLists = [$priceList];
        $priceListService = new PriceListService($this->priceListRepositoryMock, $this->priceListTransformerMock, $this->ticketTypeRepositoryMock);
        $result = $priceListService->getTicketType($priceLists);
        $this->assertIsArray($result);
    }

    public function testGetTicketPrice()
    {
        $ticketType = new TicketType();
        $ticketPrice = new PriceList();
        $ticketPrice->setPrice(5);
        $priceListService = new PriceListService($this->priceListRepositoryMock, $this->priceListTransformerMock, $this->ticketTypeRepositoryMock);
        $this->priceListRepositoryMock->expects($this->once())->method('find')->willReturn($ticketPrice);
        $result = $priceListService->getTicketPrice($ticketType);
        $this->assertIsFloat($result);
    }
    public function testAddListPrice()
    {
        $scheduleRequest = new ScheduleRequest();
        $scheduleRequest->setAdult(5);
        $scheduleRequest->setChildren(5);
        $scheduleRequest->setYouth(5);
        $schedule = new Schedule();
        $priceListServiceMock = $this->getMockBuilder(PriceListService::class)->onlyMethods([
            'addPriceListTypeAdult', 'addPriceListTypeYouth', 'addPriceListTypeChildren'
        ])->setConstructorArgs([
            $this->priceListRepositoryMock, $this->priceListTransformerMock, $this->ticketTypeRepositoryMock
        ])->getMock();
        $priceListServiceMock->expects($this->once())->method('addPriceListTypeAdult')->willReturn(true);
        $priceListServiceMock->expects($this->once())->method('addPriceListTypeYouth')->willReturn(true);
        $priceListServiceMock->expects($this->once())->method('addPriceListTypeChildren')->willReturn(true);
        $result = $priceListServiceMock->addListPrice($scheduleRequest, $schedule);
        $this->assertTrue($result);
    }

    public function testUpdateListPrice()
    {
        $scheduleUpdateRequest = new ScheduleUpdateRequest();
        $scheduleUpdateRequest->setAdult(5);
        $scheduleUpdateRequest->setChildren(5);
        $scheduleUpdateRequest->setYouth(5);
        $schedule = new Schedule();
        $priceListServiceMock = $this->getMockBuilder(PriceListService::class)->onlyMethods([
            'addPriceListTypeAdult', 'addPriceListTypeYouth', 'addPriceListTypeChildren'
        ])->setConstructorArgs([
            $this->priceListRepositoryMock, $this->priceListTransformerMock, $this->ticketTypeRepositoryMock
        ])->getMock();
        $priceListServiceMock->expects($this->once())->method('addPriceListTypeAdult')->willReturn(true);
        $priceListServiceMock->expects($this->once())->method('addPriceListTypeYouth')->willReturn(true);
        $priceListServiceMock->expects($this->once())->method('addPriceListTypeChildren')->willReturn(true);
        $result = $priceListServiceMock->updateListPrice($scheduleUpdateRequest, $schedule);
        $this->assertTrue($result);
    }

    public function testAddPriceListTypeChildren()
    {
        $scheduleRequest = new ScheduleRequest();
        $scheduleRequest->setAdult(5);
        $scheduleRequest->setChildren(5);
        $scheduleRequest->setYouth(5);
        $schedule = new Schedule();
        $ticketType = new TicketType();
        $this->ticketTypeRepositoryMock->expects($this->once())->method('findOneBy')->willReturn($ticketType);
        $this->priceListRepositoryMock->expects($this->once())->method('add');
        $priceListService = new PriceListService($this->priceListRepositoryMock, $this->priceListTransformerMock, $this->ticketTypeRepositoryMock);
        $result = $priceListService->addPriceListTypeChildren($scheduleRequest, $schedule);
        $this->assertTrue($result);
    }

    public function testAddPriceListTypeYouth()
    {
        $scheduleRequest = new ScheduleRequest();
        $scheduleRequest->setAdult(5);
        $scheduleRequest->setChildren(5);
        $scheduleRequest->setYouth(5);
        $schedule = new Schedule();
        $ticketType = new TicketType();
        $this->ticketTypeRepositoryMock->expects($this->once())->method('findOneBy')->willReturn($ticketType);
        $this->priceListRepositoryMock->expects($this->once())->method('add');
        $priceListService = new PriceListService($this->priceListRepositoryMock, $this->priceListTransformerMock, $this->ticketTypeRepositoryMock);
        $result = $priceListService->addPriceListTypeYouth($scheduleRequest, $schedule);
        $this->assertTrue($result);
    }

    public function testAddPriceListTypeAdult()
    {
        $scheduleRequest = new ScheduleRequest();
        $scheduleRequest->setAdult(5);
        $scheduleRequest->setChildren(5);
        $scheduleRequest->setYouth(5);
        $schedule = new Schedule();
        $ticketType = new TicketType();
        $this->ticketTypeRepositoryMock->expects($this->once())->method('findOneBy')->willReturn($ticketType);
        $this->priceListRepositoryMock->expects($this->once())->method('add');
        $priceListService = new PriceListService($this->priceListRepositoryMock, $this->priceListTransformerMock, $this->ticketTypeRepositoryMock);
        $result = $priceListService->addPriceListTypeAdult($scheduleRequest, $schedule);
        $this->assertTrue($result);
    }
}
