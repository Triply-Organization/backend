<?php

namespace App\Tests\Unit\Service;

use App\Repository\BillRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use App\Service\StatisticalService;
use PHPUnit\Framework\TestCase;

class StatisticalServiceTest extends TestCase
{
    public function testStatisticalTotalRevenue()
    {
        $billRepositoryMock = $this->getMockBuilder(BillRepository::class)->disableOriginalConstructor()->getMock();
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $billRepositoryMock->expects($this->once())->method('statisticalTotalRevenue')->willReturn(array());
        $year = 2020;
        $statisticalService = new StatisticalService($billRepositoryMock, $userRepositoryMock, $tourRepositoryMock);
        $result = $statisticalService->statisticalTotalRevenue($year);

        $this->assertEquals(array(), $result);
    }

    public function testStatisticalBooking()
    {
        $billRepositoryMock = $this->getMockBuilder(BillRepository::class)->disableOriginalConstructor()->getMock();
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $billRepositoryMock->expects($this->once())->method('statisticalBooking')->willReturn(array());
        $year = 2020;
        $statisticalService = new StatisticalService($billRepositoryMock, $userRepositoryMock, $tourRepositoryMock);
        $result = $statisticalService->statisticalBooking($year);

        $this->assertEquals(array(), $result);
    }

    public function testStatisticalTotal()
    {
        $billRepositoryMock = $this->getMockBuilder(BillRepository::class)->disableOriginalConstructor()->getMock();
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $billRepositoryMock->expects($this->once())->method('findAll')->willReturn(array());
        $userRepositoryMock->expects($this->once())->method('findBy')->willReturn(array());
        $tourRepositoryMock->expects($this->once())->method('findBy')->willReturn(array());
        $statisticalService = new StatisticalService($billRepositoryMock, $userRepositoryMock, $tourRepositoryMock);
        $result = $statisticalService->statisticalTotal();

        $this->assertIsArray($result);
    }
}
