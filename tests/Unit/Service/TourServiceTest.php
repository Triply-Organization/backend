<?php

namespace App\Tests\Unit\Service;

use App\Entity\Tour;
use App\Mapper\TourCreateMapper;
use App\Mapper\TourUpdateMapper;
use App\Repository\TourImageRepository;
use App\Repository\TourPlanRepository;
use App\Repository\TourRepository;
use App\Request\ListTourRequest;
use App\Request\TourRequest;
use App\Service\FacilityTourService;
use App\Service\TourImageService;
use App\Service\TourPlanService;
use App\Service\TourService;
use PHPUnit\Framework\TestCase;

class TourServiceTest extends TestCase
{
    public function testFindAll()
    {
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock->expects($this->once())->method('getAll')->willReturn(array());
        $tourImageRepositoryMock = $this->getMockBuilder(TourImageRepository::class)->disableOriginalConstructor()->getMock();
        $tourPlanRepositoryMock = $this->getMockBuilder(TourPlanRepository::class)->disableOriginalConstructor()->getMock();
        $tourCreateMapperMock = $this->getMockBuilder(TourCreateMapper::class)->disableOriginalConstructor()->getMock();
        $tourUpdateMapperMock = $this->getMockBuilder(TourUpdateMapper::class)->disableOriginalConstructor()->getMock();
        $tourPlanServiceMock = $this->getMockBuilder(TourPlanService::class)->disableOriginalConstructor()->getMock();
        $tourImageServiceMock = $this->getMockBuilder(TourImageService::class)->disableOriginalConstructor()->getMock();
        $facilityTourServiceMock = $this->getMockBuilder(FacilityTourService::class)->disableOriginalConstructor()->getMock();
        $listTourRequest = new ListTourRequest();
        $tourService = new TourService($tourRepositoryMock, $tourImageRepositoryMock, $tourPlanRepositoryMock,
            $tourCreateMapperMock, $tourUpdateMapperMock, $tourPlanServiceMock, $tourImageServiceMock, $facilityTourServiceMock);
        $result = $tourService->findAll($listTourRequest);

        $this->assertEquals(array(), $result);
    }

    public function testAddTour()
    {
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $tourImageRepositoryMock = $this->getMockBuilder(TourImageRepository::class)->disableOriginalConstructor()->getMock();
        $tourPlanRepositoryMock = $this->getMockBuilder(TourPlanRepository::class)->disableOriginalConstructor()->getMock();
        $tourCreateMapperMock = $this->getMockBuilder(TourCreateMapper::class)->disableOriginalConstructor()->getMock();
        $tourCreateMapperMock->expects($this->once())->method('mapping')->willReturn(new Tour());
        $tourUpdateMapperMock = $this->getMockBuilder(TourUpdateMapper::class)->disableOriginalConstructor()->getMock();
        $tourPlanServiceMock = $this->getMockBuilder(TourPlanService::class)->disableOriginalConstructor()->getMock();
        $tourImageServiceMock = $this->getMockBuilder(TourImageService::class)->disableOriginalConstructor()->getMock();
        $facilityTourServiceMock = $this->getMockBuilder(FacilityTourService::class)->disableOriginalConstructor()->getMock();
        $tourRequest = new TourRequest();
        $tourService = new TourService($tourRepositoryMock, $tourImageRepositoryMock, $tourPlanRepositoryMock,
            $tourCreateMapperMock, $tourUpdateMapperMock, $tourPlanServiceMock, $tourImageServiceMock, $facilityTourServiceMock);
        $result = $tourService->addTour($tourRequest);

        $this->assertInstanceOf(Tour::class, $result);
    }


}
