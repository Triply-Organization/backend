<?php

namespace App\Tests\Unit\Request;

use App\Request\OrderRequest;
use PHPUnit\Framework\TestCase;

class OrderRequestTest extends TestCase
{
    public function testGetVoucher()
    {
        $request = new OrderRequest();
        $request->setCurrency('USD');
        $request->setAdult([2]);
        $request->setChildren([1]);
        $request->setYouth([3]);

        $this->assertEquals('USD', $request->getCurrency());
        $this->assertEquals([2], $request->getAdult());
        $this->assertEquals([1], $request->getChildren());
        $this->assertEquals([3], $request->getYouth());
    }
}
