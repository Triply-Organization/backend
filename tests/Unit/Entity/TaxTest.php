<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Order;
use App\Entity\Tax;
use PHPUnit\Framework\TestCase;

class TaxTest extends TestCase
{
    public function testTaxCreate()
    {
        $tax = new Tax();
        $this->assertEquals(Tax::class, get_class($tax));
    }

    public function testTaxProperties()
    {
        $tax = new Tax();
        $orderDetail = new Order();

        $tax->setPercent(19)->setCurrency('VN')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setDeletedAt(new \DateTimeImmutable());

        $tax->addOrderDetail($orderDetail);
        $tax->getOrderDetail();
        $tax->removeOrderDetail($orderDetail);

        $this->assertEquals(null, $tax->getId());

        $this->assertEquals('integer', gettype($tax->getPercent()));
        $this->assertEquals(19, $tax->getPercent());

        $this->assertEquals('string', gettype($tax->getCurrency()));
        $this->assertEquals('VN', $tax->getCurrency());

        $this->assertEquals('object', gettype($tax->getCreatedAt()));
        $this->assertEquals('object', gettype($tax->getUpdatedAt()));
        $this->assertEquals('object', gettype($tax->getDeletedAt()));
    }
}
