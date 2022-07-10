<?php

namespace App\Tests\Unit\Service;

use App\Entity\Order;
use App\Entity\User;
use App\Mapper\UserUpdateMapper;
use App\Repository\OrderRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Request\PatchUpdateUserRequest;
use App\Request\UserGetAllOrderRequest;
use App\Request\UserRequest;
use App\Service\UserService;
use App\Transformer\OrderTransformer;
use App\Transformer\UserTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserServiceTest extends TestCase
{
    private $userRepositoryMock;
    private $userTransformerMock;
    private $userUpdateMapperMock;
    private $orderTransformerMock;
    private $reviewRepositoryMock;
    private $orderRepositoryMock;
    private $paramsMock;

    public function setUp(): void
    {
        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $this->userTransformerMock = $this->getMockBuilder(UserTransformer::class)->disableOriginalConstructor()->getMock();
        $this->userUpdateMapperMock = $this->getMockBuilder(UserUpdateMapper::class)->disableOriginalConstructor()->getMock();
        $this->orderTransformerMock = $this->getMockBuilder(OrderTransformer::class)->disableOriginalConstructor()->getMock();
        $this->reviewRepositoryMock = $this->getMockBuilder(ReviewRepository::class)->disableOriginalConstructor()->getMock();
        $this->orderRepositoryMock = $this->getMockBuilder(OrderRepository::class)->disableOriginalConstructor()->getMock();
        $this->paramsMock = $this->getMockBuilder(ParameterBagInterface::class)->getMock();
    }

    public function testGetAllOrder()
    {
        $getAllOrderRequest = new UserGetAllOrderRequest();
        $user = new User();
        $orders = new Order();
        $array['orders'] = $orders;
        $array['totalPages'] = 1;
        $array['page'] = 1;
        $array['totalOrders'] = 1;
        $this->orderRepositoryMock->expects($this->once())->method('getAllOrder')->willReturn($array);
        $userService = new UserService($this->userRepositoryMock, $this->userTransformerMock, $this->userUpdateMapperMock,
            $this->reviewRepositoryMock, $this->orderRepositoryMock, $this->orderTransformerMock,  $this->paramsMock);
        $userGetAllOrder = $userService->getAllOrder($getAllOrderRequest, $user);

        $this->assertIsArray($userGetAllOrder);
    }

    public function testGetUser()
    {
        $userRequest = new UserRequest();
        $user = new User();
        $array['users'] = $user;
        $array['totalPages'] = 1;
        $array['page'] = 1;
        $array['totalUsers'] = 1;
        $this->userRepositoryMock->expects($this->once())->method('getAll')->willReturn($array);
        $userService = new UserService($this->userRepositoryMock, $this->userTransformerMock, $this->userUpdateMapperMock,
            $this->reviewRepositoryMock, $this->orderRepositoryMock, $this->orderTransformerMock,  $this->paramsMock);
        $getUser = $userService->getUsers($userRequest);

        $this->assertIsArray($getUser);
    }

    public function testUndoDeleteUser()
    {
        $userMock = $this->getMockBuilder(User::class)->getMock();
        $userMock->method('getId')->willReturn(1);
        $this->reviewRepositoryMock->expects($this->once())->method('undoDeleteWithRelation')->willReturn(array());
        $this->userRepositoryMock->expects($this->once())->method('undoDelete')->willReturn(array());
        $userService = new UserService($this->userRepositoryMock, $this->userTransformerMock, $this->userUpdateMapperMock,
            $this->reviewRepositoryMock, $this->orderRepositoryMock, $this->orderTransformerMock,  $this->paramsMock);

        $undoDeleteUser = $userService->undoDeleteUser($userMock);
        $this->assertTrue($undoDeleteUser);
    }

    public function testDeleteUser()
    {
        $userMock = $this->getMockBuilder(User::class)->getMock();
        $userMock->method('getId')->willReturn(1);
        $userService = new UserService($this->userRepositoryMock, $this->userTransformerMock, $this->userUpdateMapperMock,
            $this->reviewRepositoryMock, $this->orderRepositoryMock, $this->orderTransformerMock,  $this->paramsMock);
        $this->reviewRepositoryMock->expects($this->once())->method('deleteWithRelation');
        $this->userRepositoryMock->expects($this->once())->method('delete');

        $result = $userService->deleteUser($userMock);
        $this->assertTrue($result);
    }

    public function testUpdateUser()
    {
        $user = new User();
        $patchUpdateUserRequest = new PatchUpdateUserRequest();
        $userService = new UserService($this->userRepositoryMock, $this->userTransformerMock, $this->userUpdateMapperMock,
            $this->reviewRepositoryMock, $this->orderRepositoryMock, $this->orderTransformerMock,  $this->paramsMock);
        $this->userRepositoryMock->expects($this->once())->method('add');

        $result = $userService->update($user, $patchUpdateUserRequest);
        $this->assertTrue($result);
    }
}
