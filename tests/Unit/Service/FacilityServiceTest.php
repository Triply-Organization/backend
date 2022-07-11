<?php

namespace App\Tests\Unit\Service;

use App\Entity\Review;
use App\Entity\Schedule;
use App\Entity\Tour;
use App\Entity\TourPlan;
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
        $this->serviceRepositoryMock = $this->getMockBuilder(ServiceRepository::class)->disableOriginalConstructor()->getMock();
        $this->serviceTransformerMock = $this->getMockBuilder(ServiceTransformer::class)->disableOriginalConstructor()->getMock();
        $this->tourServicesTransformerMock = $this->getMockBuilder(TourServicesTransformer::class)->disableOriginalConstructor()->getMock();
        $this->tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $this->paramsMock = $this->getMockBuilder(ParameterBagInterface::class)->disableOriginalConstructor()->getMock();
        $this->tourPlanServiceMock = $this->getMockBuilder(TourPlanService::class)->disableOriginalConstructor()->getMock();
        $this->scheduleServiceMock = $this->getMockBuilder(ScheduleService::class)->disableOriginalConstructor()->getMock();
    }

    public function testGetPopularTour()
    {
        $review = new Review();
        $tourPlan = new TourPlan();
        $schedule = new Schedule();
        $tours = ['id' => 1, 'rate' => 2];
        $facilityServiceMock = $this->getMockBuilder(FacilityService::class)
            ->setConstructorArgs([
                'serviceTransformer' => $this->serviceTransformerMock,
                'serviceRepository' => $this->serviceRepositoryMock,
                'tourServicesTransformer' => $this->tourServicesTransformerMock,
                'tourRepository' => $this->tourRepositoryMock,
                'params' => $this->paramsMock,
                'tourPlanService' => $this->tourPlanServiceMock,
                'scheduleService' => $this->scheduleServiceMock])
            ->onlyMethods(['getCoverImage'])->getMock();
        $tourMock = $this->getMockBuilder(Tour::class)->getMock();
        $tourMock->method('getId')->willReturn(1);
        $tourMock->setTitle('String')->addReview($review)->setMaxPeople(5)
            ->addTourPlan($tourPlan)->addSchedule($schedule)->setDuration(5);
        $facilityServiceMock->expects($this->any())->method('getCoverImage')->willReturn('string');
        $this->tourRepositoryMock->expects($this->once())->method('find')->willReturn($tourMock);
        $this->tourPlanServiceMock->expects($this->once())->method('getDestination')->willReturn(array());
        $this->scheduleServiceMock->expects($this->once())->method('getPrice')->willReturn([array()]);
        $this->tourRepositoryMock->expects($this->once())->method('getPopularTour')->willReturn([$tours]);
        $getPopularTour = $facilityServiceMock->getPopularTour();
        $this->assertIsArray($getPopularTour);
    }
}
