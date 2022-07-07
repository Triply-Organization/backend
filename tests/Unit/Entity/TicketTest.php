<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Order;
use App\Entity\PriceList;
use App\Entity\Ticket;
use PHPUnit\Framework\TestCase;

class TicketTest extends TestCase
{
    public function testTicketCreate()
    {
        $ticket = new Ticket();
        $this->assertEquals(Ticket::class, get_class($ticket));
    }

    public function testTicketCheckProperties()
    {
        $ticket = new Ticket();
        $priceList = new PriceList();
        $orderName = new Order();

        $ticket->setTotalPrice(300)->setOrderName($orderName)
            ->setAmount(13)->setPriceList($priceList)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setDeletedAt(new \DateTimeImmutable());

        $this->assertEquals(null, $ticket->getId());
        $this->assertEquals('integer', gettype($ticket->getAmount()));
        $this->assertEquals(13, $ticket->getAmount());
        $this->assertEquals('double', gettype($ticket->getTotalPrice()));
        $this->assertEquals(300, $ticket->getTotalPrice());
        $this->assertEquals('object', gettype($ticket->getOrderName()));
        $this->assertEquals('object', gettype($ticket->getPriceList()));

        $this->assertEquals('object', gettype($ticket->getCreatedAt()));
        $this->assertEquals('object', gettype($ticket->getUpdatedAt()));
        $this->assertEquals('object', gettype($ticket->getDeletedAt()));
    }
}
