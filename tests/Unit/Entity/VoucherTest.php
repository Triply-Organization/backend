<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Order;
use App\Entity\Voucher;
use PHPUnit\Framework\TestCase;

class VoucherTest extends TestCase
{
    public function testVoucherCreate()
    {
        $voucher = new Voucher();
        $this->assertEquals(Voucher::class, get_class($voucher));
    }

    public function testVoucherCheckProperties()
    {
        $voucher = new Voucher();
        $order = new Order();
        $voucher->setRemain(50)->setDiscount(50)
            ->setCode('CHECKCODE')->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())->setDeletedAt(new \DateTimeImmutable());
        $voucher->addOrder($order);
        $voucher->getOrders();
        $voucher->removeOrder($order);

        $this->assertEquals(null, $voucher->getId());

        $this->assertEquals('integer', gettype($voucher->getRemain()));
        $this->assertEquals(50, $voucher->getRemain());

        $this->assertEquals('integer', gettype($voucher->getDiscount()));
        $this->assertEquals(50, $voucher->getDiscount());

        $this->assertEquals('string', gettype($voucher->getCode()));
        $this->assertEquals('CHECKCODE', $voucher->getCode());

        $this->assertEquals('object', gettype($voucher->getCreatedAt()));
        $this->assertEquals('object', gettype($voucher->getUpdatedAt()));
        $this->assertEquals('object', gettype($voucher->getDeletedAt()));
    }
}
