<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Bill;
use App\Entity\Order;
use App\Entity\Review;
use App\Entity\Tax;
use App\Entity\Ticket;
use App\Entity\User;
use App\Entity\Voucher;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testOderCreate(): void
    {
        $order = new Order();
        $this->assertEquals(Order::class, get_class($order));
    }

    public function testOrderCheckProperties(): void
    {
        $order = new Order();
        $voucher = new Voucher();
        $bill = new Bill();
        $tax = new Tax();
        $review = new Review();
        $user = new User();
        $ticket = new Ticket();
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setUpdatedAt(new \DateTimeImmutable());
        $order->setDeletedAt(new \DateTimeImmutable());
        $order->setTotalPrice(500);
        $order->setTax($tax);
        $order->setDiscount($voucher);
        $order->setUser($user);
        $order->setReview($review);
        $order->setBill($bill);
        $order->addTicket($ticket);
        $order->setStatus('processing');
        $this->assertNull($order->getId());
        $this->assertEquals('object', gettype($order->getCreatedAt()));
        $this->assertEquals('object', gettype($order->getUpdatedAt()));
        $this->assertEquals('object', gettype($order->getDeletedAt()));
        $this->assertEquals('object', gettype($order->getTax()));
        $this->assertEquals('object', gettype($order->getTickets()));
        $this->assertEquals('object', gettype($order->getUser()));
        $this->assertEquals('object', gettype($order->getBill()));
        $this->assertEquals('object', gettype($order->getReview()));
        $this->assertEquals('object', gettype($order->getDiscount()));
        $this->assertEquals('string', gettype($order->getStatus()));
        $this->assertEquals('processing', $order->getStatus());
        $this->assertEquals('double', gettype($order->getTotalPrice()));
        $this->assertEquals(500, $order->getTotalPrice());
        $order->removeTicket($ticket);
    }
}
