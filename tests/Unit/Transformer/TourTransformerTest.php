<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Review;
use App\Entity\Tour;
use App\Entity\User;
use App\Service\ReviewService;
use App\Service\ScheduleService;
use App\Service\TourPlanService;
use App\Service\TourService;
use App\Transformer\TourTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TourTransformerTest extends TestCase
{
    public function testToArray(): void
    {
        $review = new Review();
        $tour = new Tour();
        $user = new User();
        $user->setEmail('kha@gmail.com');
        $tour->addReview($review);
        $tour->setMinAge(15);
        $tour->setDuration(3);
        $tour->setMaxPeople(15);
        $tour->setOverView('good tour');
        $tour->setStatus('enable');
        $tour->setTitle('tour can tho');
        $tour->setCreatedUser($user);
        $tourServiceMock = $this->getMockBuilder(TourService::class)->disableOriginalConstructor()->getMock();
        $tourServiceMock->expects($this->once())->method('getCover')->willReturn('img/tour.jpg');
        $tourPlanServiceMock = $this->getMockBuilder(TourPlanService::class)->disableOriginalConstructor()->getMock();
        $tourPlanServiceMock->expects($this->once())->method('getDestination')->willReturn(array());
        $scheduleServiceMock = $this->getMockBuilder(ScheduleService::class)->disableOriginalConstructor()->getMock();
        $scheduleServiceMock->expects($this->once())->method('getPrice')->willReturn(array());
        $paramsMock = $this->getMockBuilder(ParameterBagInterface::class)->disableOriginalConstructor()->getMock();
        $paramsMock->expects($this->once())->method('get')->willReturn('s3/');
        $reviewService = $this->getMockBuilder(ReviewService::class)->disableOriginalConstructor()->getMock();
        $reviewService->expects($this->once())->method('getRatingOverall')->willReturn(array());
        $tourtransformer = new TourTransformer($tourServiceMock, $scheduleServiceMock, $paramsMock, $tourPlanServiceMock, $reviewService);
        $result = $tourtransformer->toArray($tour);
        $this->assertIsArray($result);

    }
}
