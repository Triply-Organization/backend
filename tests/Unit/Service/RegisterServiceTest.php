<?php

namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Mapper\RegisterMapper;
use App\Repository\UserRepository;
use App\Request\RegisterRequest;
use App\Service\RegisterService;
use App\Service\ReviewService;
use PHPUnit\Framework\TestCase;

class RegisterServiceTest extends TestCase
{
    public function testRegisterService()
    {
        $requestData = new RegisterRequest();
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $registerMapperMock = $this->getMockBuilder(RegisterMapper::class)->disableOriginalConstructor()->getMock();
        $registerService = new RegisterService($userRepositoryMock, $registerMapperMock);
        $userRepositoryMock->expects($this->once())->method('add');
        $result = $registerService->register($requestData);
        $this->assertInstanceOf(User::class, $result);
    }
}
