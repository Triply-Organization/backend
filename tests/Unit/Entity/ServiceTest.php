<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Service;
use App\Entity\TourService;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{

    public function testServiceCreate(): void
    {
        $service = new Service();
        $this->assertEquals(Service::class, get_class($service));
    }

    public function testServiceProperties(): void
    {
        $service = new Service();
        $tourService = new TourService();

        $service->setCreatedAt(new \DateTimeImmutable());
        $service->setUpdatedAt(new \DateTimeImmutable());
        $service->setDeletedAt(new \DateTimeImmutable());
        $service->setName('swimming');
        $service->addTourService($tourService);
        $this->assertNull($service->getId());
        $this->assertEquals('object', gettype($service->getUpdatedAt()));
        $this->assertEquals('object', gettype($service->getDeletedAt()));
        $this->assertEquals('object', gettype($service->getCreatedAt()));
        $this->assertEquals('object', gettype($service->getTourServices()));
        $this->assertEquals('swimming', $service->getName());

        $service->removeTourService($tourService);
    }
}
