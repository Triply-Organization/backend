<?php

namespace App\Tests\Unit\Request;

use App\Request\AddVoucherRequest;
use PHPUnit\Framework\TestCase;

class AddVoucherRequestTest extends TestCase
{
    public function testAddVoucherRequest()
    {
        $request = new AddVoucherRequest();
        $request->setPercent(20);
        $request->setCode('VIPCODE');
        $request->setRemain(20);

        $this->assertEquals('VIPCODE', $request->getCode());
        $this->assertEquals(20, $request->getPercent());
        $this->assertEquals(20, $request->getRemain());
    }
}
