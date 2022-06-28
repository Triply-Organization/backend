<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Image;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testUserCreate(): void
    {
        $user = new User();
        $this->assertEquals(User::class, get_class($user));
    }

    public function testUserCheckProperties(): void
    {
        $user = new User();
        $image = new Image();
        $user->setName('user');
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());
        $user->setDeletedAt(new \DateTimeImmutable());
        $user->setEmail('user@gmail.com');
        $user->setPassword('123');
        $user->setPhone('0987095457');
        $user->setAddress('Can Tho');
        $user->setImage($image);

        $this->assertNull($user->getId());

        $this->assertEquals('object', gettype($user->getImage()));

        $this->assertEquals('string', gettype($user->getName()));
        $this->assertEquals('user', $user->getName());

        $this->assertEquals('array', gettype($user->getRoles()));
        $this->assertEquals(['ROLE_USER'], $user->getRoles());

        $this->assertEquals('string', gettype($user->getEmail()));
        $this->assertEquals('user@gmail.com', $user->getEmail());

        $this->assertEquals('string', gettype($user->getUserIdentifier()));
        $this->assertEquals('user@gmail.com', $user->getUserIdentifier());

        $this->assertEquals('string', gettype($user->getPassword()));
        $this->assertEquals('123', $user->getPassword());

        $this->assertEquals('string', gettype($user->getPhone()));
        $this->assertEquals('0987095457', $user->getPhone());

        $this->assertEquals('string', gettype($user->getAddress()));
        $this->assertEquals('Can Tho', $user->getAddress());

        $this->assertEquals('object', gettype($user->getCreatedAt()));
        $this->assertEquals('object', gettype($user->getUpdatedAt()));
        $this->assertEquals('object', gettype($user->getDeletedAt()));
    }
}
