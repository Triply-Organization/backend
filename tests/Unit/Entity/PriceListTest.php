<?php

namespace App\Tests\Unit\Entity;

use App\Entity\PriceList;
use App\Entity\Schedule;
use App\Entity\Ticket;
use App\Entity\TicketType;
use PHPUnit\Framework\TestCase;

class PriceListTest extends TestCase
{
    public function testPriceListCreate(): void
    {
        $priceList = new PriceList();
        $this->assertEquals(PriceList::class, get_class($priceList));
    }

    public function testOrderCheckProperties(): void
    {
        $priceList = new PriceList();
        $schedule = new Schedule();
        $ticket = new Ticket();
        $ticketType = new TicketType();
        $priceList->setCreatedAt(new \DateTimeImmutable());
        $priceList->setUpdatedAt(new \DateTimeImmutable());
        $priceList->setDeletedAt(new \DateTimeImmutable());
        $priceList->setCurrency('USD');
        $priceList->setPrice('500');
        $priceList->setSchedule($schedule);
        $priceList->setType($ticketType);
        $priceList->addTicket($ticket);
        $this->assertNull($priceList->getId());
        $this->assertEquals('object', gettype($priceList->getCreatedAt()));
        $this->assertEquals('object', gettype($priceList->getUpdatedAt()));
        $this->assertEquals('object', gettype($priceList->getDeletedAt()));
        $this->assertEquals('object', gettype($priceList->getSchedule()));
        $this->assertEquals('object', gettype($priceList->getTickets()));
        $this->assertEquals('object', gettype($priceList->getType()));
        $this->assertEquals('double', gettype($priceList->getPrice()));
        $this->assertEquals(500, $priceList->getPrice());
        $this->assertEquals('string', gettype($priceList->getCurrency()));
        $this->assertEquals('USD', $priceList->getCurrency());
        $priceList->removeTicket($ticket);
    }
}
