<?php

namespace App\Tests\Request;

use App\Request\BaseRequest;
use App\Request\GetReviewAllRequest;
use PHPUnit\Framework\TestCase;

class BaseRequestTest extends TestCase
{
    public function testGetSetBaseRequest()
    {
        $request = new GetReviewAllRequest();
        $allow = ['limit' => 10, 'offset' => 1, 'page' => 1];
        $result = $request->fromArray($allow);
        $this->assertEquals(1, $result->getPage());
        $this->assertEquals(10, $result->getLimit());
        $this->assertEquals(1, $result->getOffset());
    }
}
