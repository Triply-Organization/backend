<?php

namespace App\Tests\Unit\Request;

use App\Request\PatchUpdateVoucherRequest;
use PHPUnit\Framework\TestCase;

class PatchUpdateVoucherRequestTest extends TestCase
{
    public function testUpdateUserRequest()
    {
        $request = new PatchUpdateVoucherRequest();
        $request->setPercent(20);
        $request->setCode('ABC123');
        $request->setRemain(10);

        $this->assertEquals(20, $request->getPercent());
        $this->assertEquals('ABC123', $request->getCode());
        $this->assertEquals(10, $request->getRemain());
    }
}
