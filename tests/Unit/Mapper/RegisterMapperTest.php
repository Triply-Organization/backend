<?php

namespace App\Tests\Unit\Mapper;

use App\Entity\Image;
use App\Entity\User;
use App\Mapper\RegisterMapper;
use App\Mapper\TourCreateMapper;
use App\Repository\ImageRepository;
use App\Request\RegisterRequest;
use PHPUnit\Framework\TestCase;

class RegisterMapperTest extends TestCase
{
    public function testMapping()
    {
        $image = new Image();
        $imageRepositoryMock = $this->getMockBuilder(ImageRepository::class)->disableOriginalConstructor()->getMock();
        $imageRepositoryMock->expects($this->once())->method('find')->willReturn($image);
        $registerRequest = new RegisterRequest();
        $registerRequest->setName('user');
        $registerRequest->setEmail('user@gmail.com');
        $registerRequest->setPassword(123);
        $registerRequest->setRoles(['ROLE_USER']);
        $registerRequest->setPhone('0123456789');
        $registerRequest->setImageId(1);
        $registerMapper= new RegisterMapper($imageRepositoryMock);
        $result = $registerMapper->mapping($registerRequest);

        $this->assertEquals(null, $result->getId());
        $this->assertEquals('user', $result->getName());
        $this->assertEquals('user@gmail.com', $result->getEmail());
        $this->assertEquals($image, $result->getAvatar());
    }
}
