<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Destination;
use App\Entity\Order;
use App\Entity\Ticket;
use App\Entity\TourPlan;
use PHPUnit\Framework\TestCase;
use App\Entity\User;

class DestinationTest extends TestCase
{
    public function testDestinationCreate(): void
    {
        $destination = new Destination();
        $this->assertEquals(Destination::class, get_class($destination));
    }

    public function testDestinationCheckProperties(): void
    {
        $destination = new Destination();
        $tourPlans = new TourPlan();
        $destination->setCreatedAt(new \DateTimeImmutable());
        $destination->setUpdatedAt(new \DateTimeImmutable());
        $destination->setDeletedAt(new \DateTimeImmutable());
        $destination->setName('Can Tho');
        $destination->addTourPlan($tourPlans);
        $this->assertNull($destination->getId());
        $this->assertEquals('object', gettype($destination->getCreatedAt()));
        $this->assertEquals('object', gettype($destination->getUpdatedAt()));
        $this->assertEquals('object', gettype($destination->getDeletedAt()));
        $this->assertNotNull($destination->getTourPlans());
        $this->assertEquals('string', gettype($destination->getName()));
        $this->assertEquals('Can Tho', $destination->getName());
        $destination->removeTourPlan($tourPlans);
    }
}
