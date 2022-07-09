<?php

namespace App\Tests\Unit\Request;

use App\Request\GetVoucherRequest;
use PHPUnit\Framework\TestCase;

class GetVoucherRequestTest extends TestCase
{
    public function testGetVoucher()
    {
        $request = new GetVoucherRequest();
        $request->setCode('QSZCX');

        $this->assertEquals('QSZCX', $request->getCode());
    }
}
