<?php

namespace App\Tests\Unit\Service;

use App\Entity\Service;
use App\Entity\Tour;
use App\Entity\TourService;
use App\Repository\ServiceRepository;
use App\Repository\TourRepository;
use App\Service\FacilityService;
use App\Service\ScheduleService;
use App\Service\TourPlanService;
use App\Transformer\ServiceTransformer;
use App\Transformer\TourServicesTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FacilityServiceTest extends TestCase
{
    private $serviceRepositoryMock;
    private $serviceTransformerMock;
    private $tourServicesTransformerMock;
    private $tourRepositoryMock;
    private $paramsMock;
    private $tourPlanServiceMock;
    private $scheduleServiceMock;

    public function setUp(): void
    {
        $this->serviceTransformerMock = $this->getMockBuilder(ServiceTransformer::class)->getMock();
        $this->serviceRepositoryMock = $this->getMockBuilder(ServiceRepository::class)->disableOriginalConstructor()->getMock();
        $this->tourServicesTransformerMock = $this->getMockBuilder(TourServicesTransformer::class)->getMock();
        $this->tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $this->paramsMock = $this->getMockBuilder(ParameterBagInterface::class)->disableOriginalConstructor()->getMock();
        $this->tourPlanServiceMock = $this->getMockBuilder(TourPlanService::class)->disableOriginalConstructor()->getMock();
        $this->scheduleServiceMock = $this->getMockBuilder(ScheduleService::class)->disableOriginalConstructor()->getMock();
    }

    public function testGetService()
    {
        $service = new Service();
        $tour = new Tour();
        $tourService = new TourService();
        $tourService->setTour($tour)->setService($service)->setCreatedAt(new \DateTimeImmutable());
        $tourServices = [$tourService];
        $facilityService = new FacilityService($this->serviceTransformerMock, $this->serviceRepositoryMock, $this->tourServicesTransformerMock,
            $this->tourRepositoryMock, $this->paramsMock, $this->tourPlanServiceMock, $this->scheduleServiceMock);
        $result = $facilityService->getService($tourServices);
        $this->assertIsArray($result);
    }

    public function testGetAllService()
    {
        $service = new Service();
        $service->setName('STRING')->setCreatedAt(new \DateTimeImmutable());
        $services = [$service];
        $this->serviceRepositoryMock->expects($this->once())->method('findAll')->willReturn($services);
        $facilityService = new FacilityService($this->serviceTransformerMock, $this->serviceRepositoryMock, $this->tourServicesTransformerMock,
            $this->tourRepositoryMock, $this->paramsMock, $this->tourPlanServiceMock, $this->scheduleServiceMock);
        $result = $facilityService->getAllService();
        $this->assertIsArray($result);

    }
}
