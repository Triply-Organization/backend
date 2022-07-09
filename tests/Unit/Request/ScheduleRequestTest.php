<?php

namespace App\Tests\Request;

use App\Request\ScheduleRequest;
use PHPUnit\Framework\TestCase;

class ScheduleRequestTest extends TestCase
{
    public function testScheduleRequest()
    {
        $request = new ScheduleRequest();
        $request->setRemain(50);
        $request->setYouth(1);
        $request->setChildren(3);
        $request->setAdult(1);
        $request->setDateStart(2022 - 06 - 22);


        $this->assertEquals(50, $request->getRemain());
        $this->assertEquals(1, $request->getYouth());
        $this->assertEquals(3, $request->getChildren());
        $this->assertEquals(1, $request->getAdult());
        $this->assertEquals(2022 - 06 - 22, $request->getDateStart());
    }
}
