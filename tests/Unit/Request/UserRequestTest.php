<?php

namespace App\Tests\Unit\Request;

use App\Request\UserRequest;
use PHPUnit\Framework\TestCase;

class UserRequestTest extends TestCase
{
    public function testUserRequest()
    {
        $request = new UserRequest();
        $request->setPage(1);
        $request->setEmail('kha@gmail.com');

        $this->assertEquals(1, $request->getPage());
        $this->assertEquals('kha@gmail.com', $request->getEmail());
    }
}
