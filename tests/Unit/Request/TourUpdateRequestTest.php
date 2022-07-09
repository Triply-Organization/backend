<?php

namespace App\Tests\Unit\Request;

use App\Request\TourUpdateRequest;
use PHPUnit\Framework\TestCase;

class TourUpdateRequestTest extends TestCase
{
    public function testTourUpdateRequest()
    {
        $request = new TourUpdateRequest();
        $request->setDuration(3);
        $request->setMaxPeople(5);
        $request->setMinAge(20);
        $request->setOverView('Good tour');
        $request->setServices([1, 2, 3]);
        $request->setTitle('abc');
        $request->setTourImages([1, 2, 3]);
        $request->setTourPlans([1, 2, 3]);

        $this->assertEquals([1, 2, 3], $request->getTourPlans());
        $this->assertEquals([1, 2, 3], $request->getServices());
        $this->assertEquals(3, $request->getDuration());
        $this->assertEquals([1, 2, 3], $request->getTourImages());
        $this->assertEquals(5, $request->getMaxPeople());
        $this->assertEquals(20, $request->getMinAge());
        $this->assertEquals('Good tour', $request->getOverView());
        $this->assertEquals('abc', $request->getTitle());
    }
}
