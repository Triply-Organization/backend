<?php

namespace App\Tests\Unit\Entity;

use App\Entity\PriceList;
use App\Entity\Schedule;
use App\Entity\Tour;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    public function testScheduleCreate(): void
    {
        $schedule = new Schedule();
        $this->assertEquals(Schedule::class, get_class($schedule));
    }

    public function testScheduleProperties(): void
    {
        $schedule = new Schedule();
        $tour = new Tour();
        $priceList = new PriceList();

        $schedule->setCreatedAt(new \DateTimeImmutable());
        $schedule->setUpdatedAt(new \DateTimeImmutable());
        $schedule->setDeletedAt(new \DateTimeImmutable());
        $schedule->setTour($tour);
        $schedule->setStartDate('2022-06-24');
        $schedule->setTicketRemain(100);
        $schedule->addPriceList($priceList);
        $this->assertNull($schedule->getId());
        $this->assertEquals('object', gettype($schedule->getTour()));
        $this->assertEquals('object', gettype($schedule->getPriceLists()));
        $this->assertEquals('object', gettype($schedule->getCreatedAt()));
        $this->assertEquals('object', gettype($schedule->getDeletedAt()));
        $this->assertEquals('object', gettype($schedule->getUpdatedAt()));
        $this->assertEquals('integer', gettype($schedule->getTicketRemain()));
        $this->assertEquals('2022-06-24', $schedule->getStartDate());
        $this->assertEquals(100, $schedule->getTicketRemain());

        $schedule->removePriceList($priceList);
    }
}
