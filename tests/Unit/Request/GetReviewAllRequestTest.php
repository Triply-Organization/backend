<?php

namespace App\Tests\Unit\Request;

use App\Request\GetReviewAllRequest;
use PHPUnit\Framework\TestCase;

class GetReviewAllRequestTest extends TestCase
{
    public function testGetAllReview()
    {
        $request = new GetReviewAllRequest();
        $request->setLimit(10);
        $request->setOffset(0);
        $request->setPage(1);

        $this->assertEquals(10, $request->getLimit());
        $this->assertEquals(0, $request->getOffset());
        $this->assertEquals(1, $request->getPage());
    }
}
