<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\UserRequest;
use App\Transformer\UserTransformer;

class CustomerService
{
    private UserRepository $userRepository;
    private UserTransformer $userTransformer;
    private UserRequest $userRequest;

    public function __construct(
        UserRepository  $userRepository,
        UserRequest     $userRequest,
        UserTransformer $userTransformer
    )
    {
        $this->userRepository = $userRepository;
        $this->listCustomerRequest = $userRequest;
        $this->userTransformer = $userTransformer;
    }

    public function getCustomers(UserRequest $userRequest)
    {
        $customerRole = ['role' => 'ROLE_CUSTOMER'];
        $data = $this->userRepository->getAll($userRequest, $customerRole);
        $users = $data['users'];
        $results = [];
        foreach ($users as $user) {
            $results['users'][] = $this->userTransformer->fromArray($user);
        }
        $results['totalPages'] = $data['totalPages'];
        $results['page'] = $data['page'];
        $results['totalUsers'] = $data['totalUsers'];

        return $results;
    }
}
