<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Review;
use App\Entity\Schedule;
use App\Entity\Tour;
use App\Entity\TourImage;
use App\Entity\TourPlan;
use App\Entity\TourService;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class TourTest extends TestCase
{
    public function testTourCreate()
    {
        $tour = new Tour();
        $this->assertEquals(Tour::class, get_class($tour));
    }

    public function testTourCheckProperties()
    {
        $tour = new Tour();
        $tourImage = new TourImage();
        $schedule = new Schedule();
        $tourPlan = new TourPlan();
        $tourService = new TourService();
        $review = new Review();
        $tourServiceArray = new ArrayCollection();

        $tour->setTitle('TITLE')->setStatus('STATUS')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setDeletedAt(new \DateTimeImmutable())
            ->setMaxPeople(50)->setDuration(5)
            ->setOverView('OVERVIEW')->setMinAge(13)
            ->setTourServices($tourServiceArray);

        $tour->addReview($review);
        $tour->getReviews();
        $tour->removeReview($review);

        $tour->addTourPlan($tourPlan);
        $tour->getTourPlans();
        $tour->removeTourPlan($tourPlan);

        $tour->addTourImage($tourImage);
        $tour->getTourImages();
        $tour->removeTourImage($tourImage);

        $tour->addSchedule($schedule);
        $tour->getSchedules();
        $tour->removeSchedule($schedule);

        $tour->addTourService($tourService);
        $tour->removeTourService($tourService);

        $this->assertEquals(null, $tour->getId());
        $this->assertEquals('string', gettype($tour->getTitle()));
        $this->assertEquals('TITLE', $tour->getTitle());
        $this->assertEquals('string', gettype($tour->getStatus()));
        $this->assertEquals('STATUS', $tour->getStatus());
        $this->assertEquals('string', gettype($tour->getOverView()));
        $this->assertEquals('OVERVIEW', $tour->getOverView());
        $this->assertEquals('integer', gettype($tour->getMaxPeople()));
        $this->assertEquals(50, $tour->getMaxPeople());
        $this->assertEquals('integer', gettype($tour->getDuration()));
        $this->assertEquals(5, $tour->getDuration());
        $this->assertEquals('integer', gettype($tour->getMinAge()));
        $this->assertEquals('13', $tour->getMinAge());

        $this->assertEquals('object', gettype($tour->getTourServices()));
        $this->assertEquals('object', gettype($tour->getCreatedAt()));
        $this->assertEquals('object', gettype($tour->getUpdatedAt()));
        $this->assertEquals('object', gettype($tour->getDeletedAt()));
    }
}
