<?php

namespace App\Tests\Unit\Request;

use App\Request\ListTourRequest;
use PHPUnit\Framework\TestCase;

class ListTourRequestTest extends TestCase
{
    public function testListTourRequest()
    {
        $request = new ListTourRequest();
        $request->setStartDate('2022-06-22');
        $request->setOrderBy('asc');
        $request->setOrderType('price');
        $request->setDestination('Can Tho');
        $request->setEndPrice(200);
        $request->setGuests(1);
        $request->setLimit(6);
        $request->setOffset(0);
        $request->setPage(1);
        $request->setService(3);
        $request->setStartPrice(100);

        $this->assertEquals('2022-06-22', $request->getStartDate());
        $this->assertEquals(3, $request->getService());
        $this->assertEquals(1, $request->getPage());
        $this->assertEquals('Can Tho', $request->getDestination());
        $this->assertEquals(1, $request->getGuests());
        $this->assertEquals(6, $request->getLimit());
        $this->assertEquals('price', $request->getOrderType());
        $this->assertEquals('asc', $request->getOrderBy());
        $this->assertEquals(200, $request->getEndPrice());
        $this->assertEquals(0, $request->getOffset());
        $this->assertEquals(100, $request->getStartPrice());

    }
}
