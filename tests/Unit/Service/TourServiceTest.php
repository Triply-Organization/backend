<?php

namespace App\Tests\Unit\Service;

use App\Entity\Tour;
use App\Mapper\TourCreateMapper;
use App\Mapper\TourUpdateMapper;
use App\Repository\TourImageRepository;
use App\Repository\TourPlanRepository;
use App\Repository\TourRepository;
use App\Request\ChangeStatusOfTourRequest;
use App\Request\ListTourRequest;
use App\Request\TourRequest;
use App\Service\FacilityTourService;
use App\Service\TourImageService;
use App\Service\TourPlanService;
use App\Service\TourService;
use PHPUnit\Framework\TestCase;

class TourServiceTest extends TestCase
{
    private $tourRepositoryMock;
    private $tourImageRepositoryMock;
    private $tourPlanRepositoryMock;
    private $tourCreateMapperMock;
    private $tourUpdateMapperMock;
    private $tourPlanServiceMock;
    private $tourImageServiceMock;
    private $facilityTourServiceMock;

    public function setUp(): void
    {
        $this->tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $this->tourImageRepositoryMock = $this->getMockBuilder(TourImageRepository::class)->disableOriginalConstructor()->getMock();
        $this->tourPlanRepositoryMock = $this->getMockBuilder(TourPlanRepository::class)->disableOriginalConstructor()->getMock();
        $this->tourCreateMapperMock = $this->getMockBuilder(TourCreateMapper::class)->disableOriginalConstructor()->getMock();
        $this->tourUpdateMapperMock = $this->getMockBuilder(TourUpdateMapper::class)->disableOriginalConstructor()->getMock();
        $this->tourPlanServiceMock = $this->getMockBuilder(TourPlanService::class)->disableOriginalConstructor()->getMock();
        $this->tourImageServiceMock = $this->getMockBuilder(TourImageService::class)->disableOriginalConstructor()->getMock();
        $this->facilityTourServiceMock = $this->getMockBuilder(FacilityTourService::class)->disableOriginalConstructor()->getMock();
    }

    public function testFindAll()
    {
        $this->tourRepositoryMock->expects($this->once())->method('getAll')->willReturn(array());
        $listTourRequest = new ListTourRequest();
        $tourService = new TourService($this->tourRepositoryMock, $this->tourImageRepositoryMock, $this->tourPlanRepositoryMock,
            $this->tourCreateMapperMock, $this->tourUpdateMapperMock, $this->tourPlanServiceMock, $this->tourImageServiceMock, $this->facilityTourServiceMock);
        $result = $tourService->findAll($listTourRequest);

        $this->assertEquals(array(), $result);
    }

    public function testAddTour()
    {
        $this->tourCreateMapperMock->expects($this->once())->method('mapping')->willReturn(new Tour());
        $tourRequest = new TourRequest();
        $tourService = new TourService($this->tourRepositoryMock, $this->tourImageRepositoryMock, $this->tourPlanRepositoryMock,
            $this->tourCreateMapperMock, $this->tourUpdateMapperMock, $this->tourPlanServiceMock, $this->tourImageServiceMock, $this->facilityTourServiceMock);
        $result = $tourService->addTour($tourRequest);

        $this->assertInstanceOf(Tour::class, $result);
    }

    public function testDeleteTourService()
    {
        $tourMock = $this->getMockBuilder(Tour::class)->getMock();
        $tourMock->method('getId')->willReturn(1);
        $this->tourImageRepositoryMock->expects($this->once())->method('deleteWithRelation');
        $this->tourPlanRepositoryMock->expects($this->once())->method('deleteWithRelation');
        $this->tourRepositoryMock->expects($this->once())->method('delete');

        $tourService = new TourService($this->tourRepositoryMock, $this->tourImageRepositoryMock, $this->tourPlanRepositoryMock,
            $this->tourCreateMapperMock, $this->tourUpdateMapperMock, $this->tourPlanServiceMock, $this->tourImageServiceMock, $this->facilityTourServiceMock);
        $result = $tourService->delete($tourMock);
        $this->assertTrue($result);
    }

    public function testUndoDeleteTourService()
    {
        $tourMock = $this->getMockBuilder(Tour::class)->getMock();
        $tourMock->method('getId')->willReturn(1);
        $this->tourImageRepositoryMock->expects($this->once())->method('undoDeleteWithRelation');
        $this->tourPlanRepositoryMock->expects($this->once())->method('undoDeleteWithRelation');
        $this->tourRepositoryMock->expects($this->once())->method('undoDelete');

        $tourService = new TourService($this->tourRepositoryMock, $this->tourImageRepositoryMock, $this->tourPlanRepositoryMock,
            $this->tourCreateMapperMock, $this->tourUpdateMapperMock, $this->tourPlanServiceMock, $this->tourImageServiceMock, $this->facilityTourServiceMock);
        $result = $tourService->undoDelete($tourMock);
        $this->assertTrue($result);
    }

    public function testChangeStatus()
    {
        $tour = new Tour;
        $statusOfTourRequest = new ChangeStatusOfTourRequest;
        $statusOfTourRequest->setStatus('STRING');
        $this->tourRepositoryMock->expects($this->once())->method('find')->willReturn($tour);
        $this->tourRepositoryMock->expects($this->once())->method('add');
        $tourService = new TourService($this->tourRepositoryMock, $this->tourImageRepositoryMock, $this->tourPlanRepositoryMock,
            $this->tourCreateMapperMock, $this->tourUpdateMapperMock, $this->tourPlanServiceMock, $this->tourImageServiceMock, $this->facilityTourServiceMock);

        $result = $tourService->changeStatus($statusOfTourRequest, $tour);
        $this->assertTrue($result);
    }
}
