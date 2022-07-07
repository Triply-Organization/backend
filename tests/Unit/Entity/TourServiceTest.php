<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Service;
use App\Entity\Tour;
use App\Entity\TourService;
use PHPUnit\Framework\TestCase;

class TourServiceTest extends TestCase
{
    public function testTourServiceCreate()
    {
        $tourService = new TourService();
        $this->assertEquals(TourService::class, get_class($tourService));
    }

    public function testTourServiceCheckEntity()
    {
        $tourService = new TourService();
        $tour = new Tour();
        $service = new Service();

        $tourService->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setDeletedAt(new \DateTimeImmutable())
            ->setTour($tour)->setService($service);

        $this->assertEquals(null, $tourService->getId());
        $this->assertEquals('object', gettype($tourService->getCreatedAt()));
        $this->assertEquals('object', gettype($tourService->getUpdatedAt()));
        $this->assertEquals('object', gettype($tourService->getDeletedAt()));
        $this->assertEquals('object', gettype($tourService->getTour()));
        $this->assertEquals('object', gettype($tourService->getService()));
    }
}
