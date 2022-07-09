<?php

namespace App\Tests\Unit\Request;

use App\Request\GetTaxRequest;
use PHPUnit\Framework\TestCase;

class GetTaxRequestTest extends TestCase
{
    public function testGetTaxRequest()
    {
        $request = new GetTaxRequest();
        $request->setCurrency('USD');

        $this->assertEquals('USD', $request->getCurrency());
    }
}
