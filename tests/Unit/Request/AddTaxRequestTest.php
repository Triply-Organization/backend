<?php

namespace App\Tests\Unit\Request;

use App\Request\AddTaxRequest;
use PHPUnit\Framework\TestCase;

class AddTaxRequestTest extends TestCase
{
    public function testAddTaxRequest()
    {
        $request = new AddTaxRequest();
        $request->setCurrency('USD');
        $request->setPercent(20);

        $this->assertEquals('USD', $request->getCurrency());
        $this->assertEquals(20, $request->getPercent());
    }
}
