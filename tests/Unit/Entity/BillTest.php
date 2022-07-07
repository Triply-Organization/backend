<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Bill;
use PHPUnit\Framework\TestCase;

class BillTest extends TestCase
{
    public function testBillCreate(): void
    {
        $bill = new Bill();
        $this->assertEquals(Bill::class, get_class($bill));
    }

    public function testBillCheckProperties(): void
    {
        $bill = new Bill();
        $bill->setTotalPrice(500);
        $bill->setCreatedAt(new \DateTimeImmutable());
        $bill->setUpdatedAt(new \DateTimeImmutable());
        $bill->setStripePaymentId(null);
        $bill->setDiscount(50);
        $bill->setCurrency('USD');
        $bill->setTax(20);
        $this->assertNull($bill->getId());

        $this->assertEquals('object', gettype($bill->getCreatedAt()));
        $this->assertEquals('object', gettype($bill->getUpdatedAt()));

        $this->assertEquals('string', gettype($bill->getCurrency()));
        $this->assertEquals('USD', $bill->getCurrency());

        $this->assertEquals('double', gettype($bill->getDiscount()));
        $this->assertEquals(50, $bill->getDiscount());

        $this->assertEquals('double', gettype($bill->getTax()));
        $this->assertEquals(20, $bill->getTax());

        $this->assertEquals('double', gettype($bill->getTotalPrice()));
        $this->assertEquals(500, $bill->getTotalPrice());

        $this->assertNull($bill->getStripePaymentId());
    }
}
