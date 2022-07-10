<?php

namespace App\Tests\Unit\Request;

use App\Request\PutUpdateTaxRequest;
use PHPUnit\Framework\TestCase;

class PutUpdateTaxRequestTest extends TestCase
{
    public function testPutUpdateTaxRequest()
    {
        $request = new PutUpdateTaxRequest();
        $request->setPercent(20);
        $request->setCurrency('VND');

        $this->assertEquals(20, $request->getPercent());
        $this->assertEquals('VND', $request->getCurrency());
    }
}
