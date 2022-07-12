<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Destination;
use App\Entity\Review;
use App\Entity\Schedule;
use App\Entity\Tour;
use App\Entity\TourPlan;
use App\Entity\User;
use App\Service\FacilityService;
use App\Service\RelatedTourService;
use App\Service\ReviewService;
use App\Service\ScheduleService;
use App\Service\TourImageService;
use App\Service\TourPlanService;
use App\Transformer\TourDetailTransformer;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class TourDetailTransformerTest extends TestCase
{
    private $facilityServiceMock;
    private $tourImageServiceMock;
    private $tourPlanServiceMock;
    private $scheduleServiceMock;
    private $relatedTourServiceMock;
    private $reviewServiceMock;

    public function setUp(): void
    {
        $this->facilityServiceMock = $this->getMockBuilder(FacilityService::class)->disableOriginalConstructor()->getMock();
        $this->tourImageServiceMock = $this->getMockBuilder(TourImageService::class)->disableOriginalConstructor()->getMock();
        $this->tourPlanServiceMock = $this->getMockBuilder(TourPlanService::class)->disableOriginalConstructor()->getMock();
        $this->scheduleServiceMock = $this->getMockBuilder(ScheduleService::class)->disableOriginalConstructor()->getMock();
        $this->relatedTourServiceMock = $this->getMockBuilder(RelatedTourService::class)->disableOriginalConstructor()->getMock();
        $this->reviewServiceMock = $this->getMockBuilder(ReviewService::class)->disableOriginalConstructor()->getMock();
    }

    public function testToArray()
    {
        $user = new User();
        $user->setEmail('EMAIL');
        $scheduleMock = $this->getMockBuilder(Schedule::class)->onlyMethods(['getId'])->getMock();
        $scheduleMock->setTicketRemain(5);
        $review = new Review();
        $destination = new Destination();
        $destination->setName('STRING');
        $tourPlan = new TourPlan();
        $tourPlan->setDestination($destination);
        $tourMock = $this->getMockBuilder(Tour::class)->onlyMethods(['getId'])->getMock();
        $tourMock->method('getId')->willReturn(1);
        $tourMock->addReview($review)->setCreatedUser($user)->addTourPlan($tourPlan)->addSchedule($scheduleMock);
        $this->scheduleServiceMock->expects($this->once())->method('getPrice')->willReturn(array());
        $this->tourImageServiceMock->expects($this->once())->method('getGallery')->willReturn(array());
        $this->facilityServiceMock->expects($this->once())->method('getService')->willReturn(array());
        $this->relatedTourServiceMock->expects($this->once())->method('getRelatedTour')->willReturn(array());
        $this->tourPlanServiceMock->expects($this->once())->method('getTourPlan')->willReturn(array());
        $this->reviewServiceMock->expects($this->once())->method('getRatingDetail')->willReturn(array());
        $this->reviewServiceMock->expects($this->once())->method('getAllReviews')->willReturn(array());
        $tourDetailTransformer = new TourDetailTransformer($this->facilityServiceMock, $this->tourImageServiceMock, $this->tourPlanServiceMock,
        $this->scheduleServiceMock, $this->relatedTourServiceMock, $this->reviewServiceMock);
        $result = $tourDetailTransformer->toArray($tourMock);
        $this->assertIsArray($result);
    }
}
