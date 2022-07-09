<?php

namespace App\Tests\Unit\Service;

use App\Entity\Tour;
use App\Entity\User;
use App\Repository\PriceListRepository;
use App\Repository\ScheduleRepository;
use App\Repository\TourRepository;
use App\Service\PriceListService;
use App\Service\ScheduleService;
use App\Transformer\ScheduleTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class ScheduleServiceTest extends TestCase
{
    public function testGetFunction()
    {
        $scheduleTransformerMock = $this->getMockBuilder(ScheduleTransformer::class)->disableOriginalConstructor()->getMock();
        $scheduleTransformerMock->expects($this->once())->method('toArray')->willReturn(array());
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $scheduleRepositoryMock = $this->getMockBuilder(ScheduleRepository::class)->disableOriginalConstructor()->getMock();
        $scheduleRepositoryMock->expects($this->once())->method('findBy')->willReturn(array());
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $priceListServiceMock = $this->getMockBuilder(PriceListService::class)->disableOriginalConstructor()->getMock();
        $priceListRepositoryMock = $this->getMockBuilder(PriceListRepository::class)->disableOriginalConstructor()->getMock();
        $scheduleService = new ScheduleService($scheduleTransformerMock, $tourRepositoryMock, $scheduleRepositoryMock,
        $securityMock, $priceListRepositoryMock, $priceListServiceMock);
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
        $scheduleTransformerMock = $this->getMockBuilder(ScheduleTransformer::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock->expects($this->once())->method('find')->willReturn($tour);
        $scheduleRepositoryMock = $this->getMockBuilder(ScheduleRepository::class)->disableOriginalConstructor()->getMock();
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $securityMock->expects($this->once())->method('getUser')->willReturn($user);
        $priceListServiceMock = $this->getMockBuilder(PriceListService::class)->disableOriginalConstructor()->getMock();
        $priceListRepositoryMock = $this->getMockBuilder(PriceListRepository::class)->disableOriginalConstructor()->getMock();
        $scheduleService = new ScheduleService($scheduleTransformerMock, $tourRepositoryMock, $scheduleRepositoryMock,
            $securityMock, $priceListRepositoryMock, $priceListServiceMock);

        $result = $scheduleService->checkTour($tour);
        $this->assertTrue($result);
    }

    public function testCheckTourWithoutTour()
    {
        $tour = new Tour();
        $scheduleTransformerMock = $this->getMockBuilder(ScheduleTransformer::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock->expects($this->once())->method('find')->willReturn(null);
        $scheduleRepositoryMock = $this->getMockBuilder(ScheduleRepository::class)->disableOriginalConstructor()->getMock();
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $priceListServiceMock = $this->getMockBuilder(PriceListService::class)->disableOriginalConstructor()->getMock();
        $priceListRepositoryMock = $this->getMockBuilder(PriceListRepository::class)->disableOriginalConstructor()->getMock();
        $scheduleService = new ScheduleService($scheduleTransformerMock, $tourRepositoryMock, $scheduleRepositoryMock,
            $securityMock, $priceListRepositoryMock, $priceListServiceMock);

        $result = $scheduleService->checkTour($tour);
        $this->assertFalse($result);
    }

    public function testCheckTourWithoutRightUser()
    {
        $tour = new Tour();
        $scheduleTransformerMock = $this->getMockBuilder(ScheduleTransformer::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock->expects($this->once())->method('find')->willReturn($tour);
        $scheduleRepositoryMock = $this->getMockBuilder(ScheduleRepository::class)->disableOriginalConstructor()->getMock();
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $priceListServiceMock = $this->getMockBuilder(PriceListService::class)->disableOriginalConstructor()->getMock();
        $priceListRepositoryMock = $this->getMockBuilder(PriceListRepository::class)->disableOriginalConstructor()->getMock();
        $scheduleService = new ScheduleService($scheduleTransformerMock, $tourRepositoryMock, $scheduleRepositoryMock,
            $securityMock, $priceListRepositoryMock, $priceListServiceMock);

        $result = $scheduleService->checkTour($tour);
        $this->assertFalse($result);
    }
}
