<?php

namespace App\Tests\Unit\Request;

use App\Request\ReviewRequest;
use PHPUnit\Framework\TestCase;

class ReviewRequestTest extends TestCase
{
    public function testReviewRequest()
    {
        $request = new ReviewRequest();
        $request->setComment('Really good tour');
        $request->setRate([5, 5, 5, 4, 3]);


        $this->assertEquals('Really good tour', $request->getComment());
        $this->assertEquals([5, 5, 5, 4, 3], $request->getRate());
    }
}
