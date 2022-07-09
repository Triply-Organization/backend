<?php

namespace App\Tests\Unit\Service;

use App\Repository\DestinationRepository;
use App\Repository\TourPlanRepository;
use App\Service\TourPlanService;
use App\Transformer\DestinationTransformer;
use App\Transformer\TourPlansTransformer;
use phpDocumentor\Reflection\Types\Collection;
use PHPUnit\Framework\TestCase;

class TourPlanServiceTest extends TestCase
{
    public function testGetTourPlan()
    {
        $destinationRepositoryMock = $this->getMockBuilder(DestinationRepository::class)->disableOriginalConstructor()->getMock();
        $tourPlanRepositoryMock = $this->getMockBuilder(TourPlanRepository::class)->disableOriginalConstructor()->getMock();
        $tourPlanTransformerMock = $this->getMockBuilder(TourPlansTransformer::class)->getMock();
        $tourPlanTransformerMock->expects($this->once())->method('toArray')->willReturn(array());
        $destinationTransformerMock = $this->getMockBuilder(DestinationTransformer::class)->getMock();
        $plans = \Doctrine\Common\Collections\Collection::class;
        $tourPlanService = new TourPlanService($destinationRepositoryMock, $tourPlanRepositoryMock,
            $tourPlanTransformerMock, $destinationTransformerMock);
        $getTourPlan = $tourPlanService->getTourPlan($plans);
        $this->assertEquals(array(), $getTourPlan);
    }

    public function testGetDestination()
    {
        $destinationRepositoryMock = $this->getMockBuilder(DestinationRepository::class)->disableOriginalConstructor()->getMock();
        $tourPlanRepositoryMock = $this->getMockBuilder(TourPlanRepository::class)->disableOriginalConstructor()->getMock();
        $tourPlanTransformerMock = $this->getMockBuilder(TourPlansTransformer::class)->getMock();
        $destinationTransformerMock = $this->getMockBuilder(DestinationTransformer::class)->getMock();
        $destinationTransformerMock->expects($this->once())->method('toArray')->willReturn(array());
        $destination = \Doctrine\Common\Collections\Collection::class;
        $tourPlanService = new TourPlanService($destinationRepositoryMock, $tourPlanRepositoryMock,
            $tourPlanTransformerMock, $destinationTransformerMock);
        $getDestination = $tourPlanService->getDestination($destination);
        $this->assertEquals(array(), $getDestination);
    }
}
