<?php

namespace App\Tests\Unit\Request;

use App\Request\CheckoutRequest;
use PHPUnit\Framework\TestCase;

class CheckoutRequestTest extends TestCase
{
    public function testCheckoutRequest()
    {
        $request = new CheckoutRequest();
        $request->setName('kha');
        $request->setCurrency('USD');
        $request->setDate(2022 - 06 - 27);
        $request->setDiscountPrice(20);
        $request->setEmail('kha@gmail.com');
        $request->setOrderId(10);
        $request->setPhone('0911603179');
        $request->setScheduleId(1);
        $request->setTaxPrice(50);
        $request->setTotalPrice(530);
        $request->setTourId(1);
        $request->setTourName('Can Tho Trip');
        $request->setVoucherId(1);

        $this->assertEquals('kha', $request->getName());
        $this->assertEquals('USD', $request->getCurrency());
        $this->assertEquals('kha@gmail.com', $request->getEmail());
        $this->assertEquals('0911603179', $request->getPhone());
        $this->assertEquals('Can Tho Trip', $request->getTourName());
        $this->assertEquals(2022 - 06 - 27, $request->getDate());
        $this->assertEquals(20, $request->getDiscountPrice());
        $this->assertEquals(10, $request->getOrderId());
        $this->assertEquals(1, $request->getScheduleId());
        $this->assertEquals(50, $request->getTaxPrice());
        $this->assertEquals(530, $request->getTotalPrice());
        $this->assertEquals(1, $request->getTourId());
        $this->assertEquals(1, $request->getVoucherId());
    }
}
