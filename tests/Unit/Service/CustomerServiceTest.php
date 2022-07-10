<?php

namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use App\Request\UserRequest;
use App\Service\CustomerService;
use App\Transformer\UserTransformer;
use PHPUnit\Framework\TestCase;

class CustomerServiceTest extends TestCase
{
    public function testGetCustomers()
    {
        $customerRequest = new UserRequest();
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $userRepositoryMock->expects($this->once())->method('getAll')->willReturn([
            'users' => [],
            'totalPages' => 0,
            'page' => 1,
            'totalUsers' => 0,
        ]);
        $userTransformerMock = $this->getMockBuilder(UserTransformer::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $customerService = new CustomerService($userRepositoryMock, $userTransformerMock, $tourRepositoryMock);
        $result = $customerService->getCustomers($customerRequest);
        $this->assertIsArray($result);
    }

    public function testDeleteCustomer()
    {
        $user = $this->getMockBuilder(User::class)->getMock();
        $user->method('getId')->willReturn(1);
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $userRepositoryMock->expects($this->once())->method('delete')->willReturn([]);
        $userTransformerMock = $this->getMockBuilder(UserTransformer::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock->expects($this->once())->method('deleteWithRelation')->willReturn([]);
        $customerService = new CustomerService($userRepositoryMock, $userTransformerMock, $tourRepositoryMock);
        $result = $customerService->deleteCustomer($user);

        $this->assertTrue($result);
    }

    public function testUndoDeleteCustomer()
    {
        $user = $this->getMockBuilder(User::class)->getMock();
        $user->method('getId')->willReturn(1);
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $userRepositoryMock->expects($this->once())->method('undoDelete')->willReturn([]);
        $userTransformerMock = $this->getMockBuilder(UserTransformer::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock = $this->getMockBuilder(TourRepository::class)->disableOriginalConstructor()->getMock();
        $tourRepositoryMock->expects($this->once())->method('undoDeleteWithRelation')->willReturn([]);
        $customerService = new CustomerService($userRepositoryMock, $userTransformerMock, $tourRepositoryMock);
        $result = $customerService->undoDeleteCustomer($user);

        $this->assertTrue($result);
    }
}
