<?php

namespace App\Tests\Unit\Service;

use App\Entity\Tour;
use App\Repository\ServiceRepository;
use App\Repository\TourRepository;
use App\Service\ReviewService;
use App\Transformer\ServiceTransformer;
use App\Transformer\TourServicesTransformer;
use PHPUnit\Framework\TestCase;
use App\Service\FacilityService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FacilityServiceTest extends TestCase
{

    public function testGetCoverImageFalse()
    {
        $tour = new Tour();
        $serviceTransformerMock = $this->getMockBuilder(ServiceTransformer::class)->disableOriginalConstructor()->getMock();
        $serviceRepositoryMock = $this->getMockBuilder(ServiceRepository::class)->disableOriginalConstructor()->getMock();
        $tourServiceTransformerMock = $this->getMockBuilder(TourServicesTransformer::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $reviewServiceMock = $this->getMockBuilder(ReviewService::class)->disableOriginalConstructor()->getMock();
        $paramMock = $this->getMockBuilder(ParameterBagInterface::class)->disableOriginalConstructor()->getMock();
        $facilityService = new FacilityService($serviceTransformerMock, $serviceRepositoryMock, $tourServiceTransformerMock,
            $tourRepositoryMock, $reviewServiceMock, $paramMock);
        $result = $facilityService->getCoverImage($tour);
        $this->assertEquals('', $result);
    }

}
