<?php

namespace App\Tests\Unit\Request;

use App\Request\PatchUpdateUserRequest;
use PHPUnit\Framework\TestCase;

class PatchUpdateUserRequestTest extends TestCase
{
    public function testUpdateUserRequest()
    {
        $request = new PatchUpdateUserRequest();
        $request->setEmail('kha@gmail.com');
        $request->setPhone('0911603179');
        $request->setName('khajackie');
        $request->setPassword('123456');
        $request->setAddress('Can Tho');
        $request->setAvatar(1);
        $request->setRoles(['ROLE_USER']);

        $this->assertEquals('kha@gmail.com', $request->getEmail());
        $this->assertEquals('0911603179', $request->getPhone());
        $this->assertEquals('khajackie', $request->getName());
        $this->assertEquals('123456', $request->getPassword());
        $this->assertEquals('Can Tho', $request->getAddress());
        $this->assertEquals(1, $request->getAvatar());
        $this->assertEquals(['ROLE_USER'], $request->getRoles());
    }
}
