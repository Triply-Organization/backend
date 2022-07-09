<?php

namespace App\Tests\Unit\Request;

use App\Request\RefundRequest;
use PHPUnit\Framework\TestCase;

class RefundRequestTest extends TestCase
{
    public function testRefundRequest()
    {
        $request = new RefundRequest();
        $request->setBillId(3);
        $request->setDayRemain(5);
        $request->setStripeId('5');
        $request->setOrderId(5);
        $request->setCurrency('USD');


        $this->assertEquals(3, $request->getBillId());
        $this->assertEquals(5, $request->getDayRemain());
        $this->assertEquals(5, $request->getOrderId());
        $this->assertEquals('5', $request->getStripeId());
        $this->assertEquals('USD', $request->getCurrency());
    }
}
