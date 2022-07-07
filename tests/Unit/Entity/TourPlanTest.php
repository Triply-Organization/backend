<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Destination;
use App\Entity\Tour;
use App\Entity\TourPlan;
use PHPUnit\Framework\TestCase;

class TourPlanTest extends TestCase
{
    public function testTourPlanCreate()
    {
        $tourPlan = new TourPlan();
        $this->assertEquals(TourPlan::class, get_class($tourPlan));
    }

    public function testTourPlanCheckProperties()
    {
        $tourPlan = new TourPlan();
        $destination = new Destination();
        $tour = new Tour();

        $tourPlan->setTitle('TITLE')->setTour($tour)
            ->setDay(5)->setDescription('DESCRIPTION')
            ->setDestination($destination)->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())->setDeletedAt(new \DateTimeImmutable());

        $this->assertEquals(null, $tourPlan->getId());

        $this->assertEquals('string', gettype($tourPlan->getDescription()));
        $this->assertEquals('DESCRIPTION', $tourPlan->getDescription());
        $this->assertEquals('string', gettype($tourPlan->getTitle()));
        $this->assertEquals('TITLE', $tourPlan->getTitle());
        $this->assertEquals('integer', gettype($tourPlan->getDay()));

        $this->assertEquals('object', gettype($tourPlan->getTour()));
        $this->assertEquals('object', gettype($tourPlan->getDestination()));

        $this->assertEquals('object', gettype($tourPlan->getCreatedAt()));
        $this->assertEquals('object', gettype($tourPlan->getUpdatedAt()));
        $this->assertEquals('object', gettype($tourPlan->getDeletedAt()));
    }
}
