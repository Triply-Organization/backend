<?php

namespace App\Tests\Unit\Service;

use App\Entity\PriceList;
use App\Entity\Schedule;
use App\Entity\Tour;
use App\Entity\User;
use App\Repository\PriceListRepository;
use App\Repository\ScheduleRepository;
use App\Repository\TourRepository;
use App\Request\ScheduleRequest;
use App\Request\ScheduleUpdateRequest;
use App\Service\PriceListService;
use App\Service\ScheduleService;
use App\Transformer\ScheduleTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class ScheduleServiceTest extends TestCase
{
    private $scheduleTransformerMock;
    private $tourRepositoryMock;
    private $scheduleRepositoryMock;
    private $securityMock;
    private $priceListServiceMock;
    private $priceListRepositoryMock;

    public function setUp(): void
    {
        $this->scheduleTransformerMock = $this->getMockBuilder(ScheduleTransformer::class)->disableOriginalConstructor()->getMock();
        $this->tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $this->scheduleRepositoryMock = $this->getMockBuilder(ScheduleRepository::class)->disableOriginalConstructor()->getMock();
        $this->securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $this->priceListServiceMock = $this->getMockBuilder(PriceListService::class)->disableOriginalConstructor()->getMock();
        $this->priceListRepositoryMock = $this->getMockBuilder(PriceListRepository::class)->disableOriginalConstructor()->getMock();
    }

    public function testGetFunction()
    {
        $this->scheduleRepositoryMock->expects($this->once())->method('findBy')->willReturn(array());
        $scheduleService = new ScheduleService($this->scheduleTransformerMock, $this->tourRepositoryMock, $this->scheduleRepositoryMock,
        $this->securityMock, $this->priceListRepositoryMock, $this->priceListServiceMock);
        $tour = new Tour();
        $schedules = [];
        $resultOne = $scheduleService->getAllScheduleOfCustomer($tour);
        $resultTwo = $scheduleService->getPrice($schedules);

        $this->assertEquals(array(), $resultOne);
        $this->assertEquals(array(), $resultTwo);
    }

    public function testCheckTour()
    {
        $user = new User();
        $tour = new Tour();
        $tour->setCreatedUser($user);
        $this->tourRepositoryMock->expects($this->once())->method('find')->willReturn($tour);
        $this->securityMock->expects($this->once())->method('getUser')->willReturn($user);
        $scheduleService = new ScheduleService($this->scheduleTransformerMock, $this->tourRepositoryMock, $this->scheduleRepositoryMock,
            $this->securityMock, $this->priceListRepositoryMock, $this->priceListServiceMock);

        $result = $scheduleService->checkTour($tour);
        $this->assertTrue($result);
    }

    public function testCheckTourWithoutTour()
    {
        $tour = new Tour();
        $this->tourRepositoryMock->expects($this->once())->method('find')->willReturn(null);
        $scheduleService = new ScheduleService($this->scheduleTransformerMock, $this->tourRepositoryMock, $this->scheduleRepositoryMock,
            $this->securityMock, $this->priceListRepositoryMock, $this->priceListServiceMock);

        $result = $scheduleService->checkTour($tour);
        $this->assertFalse($result);
    }

    public function testAddSchedule()
    {
        $tour = new Tour();
        $scheduleRequest = new ScheduleRequest();
        $scheduleRequest->setDateStart('2022-05-01');
        $scheduleRequest->setRemain(5);
        $this->scheduleRepositoryMock->expects($this->once())->method('add');
        $this->priceListServiceMock->expects($this->once())->method('addListPrice');
        $scheduleService = new ScheduleService($this->scheduleTransformerMock, $this->tourRepositoryMock, $this->scheduleRepositoryMock,
            $this->securityMock, $this->priceListRepositoryMock, $this->priceListServiceMock);
        $result = $scheduleService->addSchedule($scheduleRequest, $tour);
        $this->assertTrue($result);
    }

    public function testUpdateSchedule()
    {
        $schedule = new Schedule();
        $priceList = new PriceList();
        $schedule->addPriceList($priceList);
        $scheduleUpdateRequest = new ScheduleUpdateRequest();
        $scheduleUpdateRequest->setDateStart('2022-05-01');
        $scheduleUpdateRequest->setRemain(5);
        $this->priceListServiceMock->expects($this->once())->method('updateListPrice');
        $this->scheduleRepositoryMock->expects($this->any())->method('add');
        $this->priceListRepositoryMock->expects($this->once())->method('add');
        $scheduleService = new ScheduleService($this->scheduleTransformerMock, $this->tourRepositoryMock, $this->scheduleRepositoryMock,
            $this->securityMock, $this->priceListRepositoryMock, $this->priceListServiceMock);
        $result = $scheduleService->updateSchedule($scheduleUpdateRequest, $schedule);
        $this->assertTrue($result);
    }
}
