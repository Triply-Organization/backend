<?php

namespace App\Tests\Unit\Request;

use App\Request\UserGetAllOrderRequest;
use PHPUnit\Framework\TestCase;

class UserGetAllOrderRequestTest extends TestCase
{
    public function testGetAllOrderRequest()
    {
        $request = new UserGetAllOrderRequest();
        $request->setPage(1);
        $request->setOffset(0);
        $request->setLimit(6);

        $this->assertEquals(1, $request->getPage());
        $this->assertEquals(0, $request->getOffset());
        $this->assertEquals(6, $request->getLimit());
    }
}
