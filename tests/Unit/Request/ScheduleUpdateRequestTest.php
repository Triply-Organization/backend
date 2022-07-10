<?php

namespace App\Tests\Unit\Request;

use App\Request\ScheduleUpdateRequest;
use PHPUnit\Framework\TestCase;

class ScheduleUpdateRequestTest extends TestCase
{
    public function testScheduleUpdateRequest()
    {
        $request = new ScheduleUpdateRequest();
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
