<?php

namespace App\Tests\Unit\Request;

use App\Request\PutUpdateVoucherRequest;
use PHPUnit\Framework\TestCase;

class PutUpdateVoucherRequestTest extends TestCase
{
    public function testUpdateVoucherRequest()
    {
        $request = new PutUpdateVoucherRequest();
        $request->setPercent(20);
        $request->setCode('ABC123');
        $request->setRemain(10);

        $this->assertEquals(20, $request->getPercent());
        $this->assertEquals('ABC123', $request->getCode());
        $this->assertEquals(10, $request->getRemain());
    }
}
