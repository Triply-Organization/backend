<?php

namespace App\Tests\Unit\Entity;

use App\Entity\PriceList;
use App\Entity\Ticket;
use App\Entity\TicketType;
use PHPUnit\Framework\TestCase;

class TicketTypeTest extends TestCase
{
    public function testTicketTypeCreate()
    {
        $ticketType = new TicketType();
        $this->assertEquals(TicketType::class, get_class($ticketType));
    }

    public function testTicketTypeCheckProperties()
    {
        $ticketType = new TicketType();
        $ticket = new PriceList();

        $ticketType->setName('TICKETTYPE')->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setDeletedAt(new \DateTimeImmutable());
        $ticketType->addTicket($ticket);
        $ticketType->getTickets();
        $ticketType->removeTicket($ticket);

        $this->assertEquals(null, $ticketType->getId());
        $this->assertEquals('string', gettype($ticketType->getName()));
        $this->assertEquals('TICKETTYPE', $ticketType->getName());

        $this->assertEquals('object', gettype($ticketType->getCreatedAt()));
        $this->assertEquals('object', gettype($ticketType->getUpdatedAt()));
        $this->assertEquals('object', gettype($ticketType->getDeletedAt()));
    }
}
