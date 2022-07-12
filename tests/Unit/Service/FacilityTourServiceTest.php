<?php

namespace App\Tests\Unit\Service;

use App\Entity\Service;
use App\Entity\Tour;
use App\Repository\ServiceRepository;
use App\Repository\TourServiceRepository;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;
use App\Service\FacilityTourService;
use PHPUnit\Framework\TestCase;

class FacilityTourServiceTest extends TestCase
{
    private $serviceRepositoryMock;
    private $tourServiceRepositoryMock;

    public function setUp(): void
    {
        $this->serviceRepositoryMock = $this->getMockBuilder(ServiceRepository::class)->disableOriginalConstructor()->getMock();
        $this->tourServiceRepositoryMock = $this->getMockBuilder(TourServiceRepository::class)->disableOriginalConstructor()->getMock();
    }

    public function testAddServiceToTour()
    {
        $service = new Service();
        $tourRequest = new TourRequest();
        $tour = new Tour();
        $tourRequest->setServices([$service]);
        $this->serviceRepositoryMock->expects($this->once())->method('find')->willReturn($service);
        $this->tourServiceRepositoryMock->expects($this->once())->method('add');
        $facility = new FacilityTourService($this->serviceRepositoryMock, $this->tourServiceRepositoryMock);
        $serviceToTour = $facility->addServiceToTour($tourRequest, $tour);
        $this->assertTrue($serviceToTour);
    }

    public function testAddServiceToTourFail()
    {
        $service = new Service();
        $tourRequest = new TourRequest();
        $tour = new Tour();
        $tourRequest->setServices([$service]);
        $this->serviceRepositoryMock->expects($this->once())->method('find');
        $this->tourServiceRepositoryMock->method('add');
        $facility = new FacilityTourService($this->serviceRepositoryMock, $this->tourServiceRepositoryMock);
        $serviceToTour = $facility->addServiceToTour($tourRequest, $tour);
        $this->assertTrue($serviceToTour);
    }

    public function testUpdateServiceFormTour()
    {
        $tour = new Tour();
        $tourUpdateRequest = new TourUpdateRequest();
        $facilityTourServiceMock = $this->getMockBuilder(FacilityTourService::class)
            ->onlyMethods(['addNewServiceToTour', 'deleteServiceFromTour'])->disableOriginalConstructor()->getMock();
        $updateServiceFormTour = $facilityTourServiceMock->updateServiceFromTour($tour, $tourUpdateRequest);
        $this->assertTrue($updateServiceFormTour);
    }

    public function testAddNewServiceToTour()
    {
        $service = new Service();
        $tour = new Tour();
        $tourUpdateRequest = new TourUpdateRequest();
        $newService = ['newServiceToTour' => [$service]];
        $tourUpdateRequest->setServices($newService);
        $this->serviceRepositoryMock->expects($this->once())->method('find')->willReturn($service);
        $this->tourServiceRepositoryMock->expects($this->once())->method('add');
        $facilityTourService = new FacilityTourService($this->serviceRepositoryMock, $this->tourServiceRepositoryMock);
        $newServiceToTour = $facilityTourService->addNewServiceToTour($tour, $tourUpdateRequest);
        $this->assertTrue($newServiceToTour);
    }

    public function testAddNewServiceToTourFail()
    {
        $service = new Service();
        $tour = new Tour();
        $tourUpdateRequest = new TourUpdateRequest();
        $newService = ['newServiceToTour' => [$service]];
        $tourUpdateRequest->setServices($newService);
        $this->serviceRepositoryMock->expects($this->once())->method('find');
        $this->tourServiceRepositoryMock->method('add');
        $facilityTourService = new FacilityTourService($this->serviceRepositoryMock, $this->tourServiceRepositoryMock);
        $newServiceToTour = $facilityTourService->addNewServiceToTour($tour, $tourUpdateRequest);
        $this->assertTrue($newServiceToTour);
    }

    public function testDeleteServiceFormTour() :void
    {
        $service = new Service();
        $tourUpdateRequest = new TourUpdateRequest();
        $deleteServices = ['deleteServiceFromTour' => [$service]];
        $tourUpdateRequest->setServices($deleteServices);
        $this->tourServiceRepositoryMock->expects($this->once())->method('find')->willReturn($service);
        $this->tourServiceRepositoryMock->expects($this->once())->method('add');
        $facilityTourService = new FacilityTourService($this->serviceRepositoryMock, $this->tourServiceRepositoryMock);
        $newServiceToTour = $facilityTourService->deleteServiceFromTour($tourUpdateRequest);
        $this->assertTrue($newServiceToTour);
    }
}
