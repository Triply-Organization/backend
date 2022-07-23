<?php

namespace App\Tests\Unit\Service;

use App\Entity\Destination;
use App\Entity\Tour;
use App\Entity\TourPlan;
use App\Repository\DestinationRepository;
use App\Repository\TourPlanRepository;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;
use App\Service\TourPlanService;
use App\Transformer\DestinationTransformer;
use App\Transformer\TourPlansTransformer;
use phpDocumentor\Reflection\Types\Collection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Date;

class TourPlanServiceTest extends TestCase
{
    private $destinationRepositoryMock;
    private $tourPlanRepositoryMock;
    private $tourPlanTransformerMock;
    private $destinationTransformerMock;

    public function setUp(): void
    {
        $this->destinationRepositoryMock = $this->getMockBuilder(DestinationRepository::class)->disableOriginalConstructor()->getMock();
        $this->tourPlanRepositoryMock = $this->getMockBuilder(TourPlanRepository::class)->disableOriginalConstructor()->getMock();
        $this->tourPlanTransformerMock = $this->getMockBuilder(TourPlansTransformer::class)->getMock();
        $this->destinationTransformerMock = $this->getMockBuilder(DestinationTransformer::class)->getMock();
    }

    public function testGetTourPlan()
    {
        $plans = [];
        $tourPlanService = new TourPlanService(
            $this->destinationRepositoryMock,
            $this->tourPlanRepositoryMock,
            $this->tourPlanTransformerMock,
            $this->destinationTransformerMock
        );
        $getTourPlan = $tourPlanService->getTourPlan($plans);
        $this->assertIsArray($getTourPlan);
    }

    public function testGetDestination()
    {
        $destination = [];
        $tourPlanService = new TourPlanService(
            $this->destinationRepositoryMock,
            $this->tourPlanRepositoryMock,
            $this->tourPlanTransformerMock,
            $this->destinationTransformerMock
        );
        $getDestination = $tourPlanService->getDestination($destination);
        $this->assertIsArray($getDestination);
    }

    public function testAddTourPlan()
    {
        $tourPlanRequest['title'] = 'string';
        $tourPlanRequest['destination'] = 1;
        $tourPlanRequest['description'] = 'string';
        $tourPlanRequest['day'] =  1;
        $tour = new Tour();
        $destination = new Destination();
        $tourRequest = new TourRequest();
        $tourRequest->setTourPlans([$tourPlanRequest]);
        $this->destinationRepositoryMock->expects($this->once())->method('find')->willReturn($destination);
        $this->tourPlanRepositoryMock->expects($this->once())->method('add');
        $tourPlanService = new TourPlanService(
            $this->destinationRepositoryMock,
            $this->tourPlanRepositoryMock,
            $this->tourPlanTransformerMock,
            $this->destinationTransformerMock
        );
        $result = $tourPlanService->addTourPlan($tourRequest, $tour);
        $this->assertTrue($result);
    }

    public function testUpdateTourPlan()
    {
        $tourPlanRequest['title'] = 'string';
        $tourPlanRequest['destination'] = 1;
        $tourPlanRequest['description'] = 'string';
        $tourPlanRequest['day'] =  1;
        $tourPlanRequest['id'] =  1;
        $tourPlan = new TourPlan();
        $destination = new Destination();
        $tourUpdateRequest = new TourUpdateRequest();
        $tourUpdateRequest->setTourPlans([$tourPlanRequest]);
        $this->destinationRepositoryMock->expects($this->once())->method('find')->willReturn($destination);
        $this->tourPlanRepositoryMock->expects($this->once())->method('add');
        $this->tourPlanRepositoryMock->expects($this->once())->method('find')->willReturn($tourPlan);
        $tourPlanService = new TourPlanService(
            $this->destinationRepositoryMock,
            $this->tourPlanRepositoryMock,
            $this->tourPlanTransformerMock,
            $this->destinationTransformerMock
        );
        $result = $tourPlanService->updateTourPlan($tourUpdateRequest);
        $this->assertTrue($result);
    }
}
