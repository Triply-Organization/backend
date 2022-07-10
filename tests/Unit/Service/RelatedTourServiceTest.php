<?php

namespace App\Tests\Unit\Service;

use App\Entity\Tour;
use App\Repository\TourRepository;
use App\Service\RelatedTourService;
use App\Transformer\TourTransformer;
use PHPUnit\Framework\TestCase;

class RelatedTourServiceTest extends TestCase
{
    public function testGetRelatedTour()
    {
        $destinationName = 'destination';
        $tourId = 1;
        $tour = new Tour();
        $tours = [$tour];
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $tourTransformerMock = $this->getMockBuilder(TourTransformer::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock->expects($this->once())->method('getTourWithDestination')->willReturn($tours);
        $relatedTourService = new RelatedTourService($tourRepositoryMock, $tourTransformerMock);
        $result = $relatedTourService->getRelatedTour($destinationName, $tourId);
        $this->assertIsArray($result);
    }
}
