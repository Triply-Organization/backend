<?php

namespace App\Tests\Unit\Request;


use App\Request\ChangeStatusOfTourRequest;
use PHPUnit\Framework\TestCase;

class ChangeStatusOfTourRequestTest extends TestCase
{
    public function testChangeStatusTourRequest()
    {
        $request = new ChangeStatusOfTourRequest();
        $request->setStatus('enable');

        $this->assertEquals('enable', $request->getStatus());
    }
}
