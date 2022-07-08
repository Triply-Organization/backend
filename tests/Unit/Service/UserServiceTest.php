<?php

namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Mapper\UserUpdateMapper;
use App\Repository\ImageRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Transformer\OrderTransformer;
use App\Transformer\UserTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class UserServiceTest extends TestCase
{
    public function testGetAllOrder()
    {
        $user = new User();
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $imageRepositoryMock = $this->getMockBuilder(ImageRepository::class)->disableOriginalConstructor()->getMock();
        $userTransformer = new UserTransformer();
        $userEditMapper = new UserUpdateMapper($imageRepositoryMock);
        $reviewRepositoryMock = $this->getMockBuilder(ReviewRepository::class)->disableOriginalConstructor()->getMock();
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $securityMock->expects($this->once())->method('getUser')->willReturn($user);
        $orderTransformerMock = $this->getMockBuilder(OrderTransformer::class)->disableOriginalConstructor()->getMock();

        $userExpected['id'] = $user->getId();
        $userExpected['email'] = $user->getEmail();
        $userExpected['fullname'] = $user->getName();
        $userExpected['avatar'] = $user->getAvatar();

        $userService = new UserService($userRepositoryMock, $userTransformer, $userEditMapper,
            $reviewRepositoryMock, $securityMock, $orderTransformerMock);
        $result = $userService->getAllOrder();
        $this->assertEquals(['user' => $userExpected], $result);
    }
}
