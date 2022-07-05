<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\UserRequest;
use App\Transformer\UserTransformer;

class UserService
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

    public function getUsers(UserRequest $userRequest)
    {
        $userRole = ['role' => 'ROLE_USER'];
        $data = $this->userRepository->getAll($userRequest, $userRole);
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
