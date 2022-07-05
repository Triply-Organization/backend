<?php

namespace App\Service;

use App\Entity\User;
use App\Mapper\UserEditMapper;
use App\Repository\UserRepository;
use App\Request\EditRoleRequest;
use App\Request\UserRequest;
use App\Transformer\UserTransformer;

class UserService
{
    private UserRepository $userRepository;
    private UserTransformer $userTransformer;
    private UserRequest $userRequest;
    private UserEditMapper $userEditMapper;


    public function __construct(
        UserRepository  $userRepository,
        UserRequest     $userRequest,
        UserTransformer $userTransformer,
        UserEditMapper  $userEditMapper
    )
    {
        $this->userRepository = $userRepository;
        $this->listCustomerRequest = $userRequest;
        $this->userTransformer = $userTransformer;
        $this->userEditMapper = $userEditMapper;
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

    public function editRole(User $user, EditRoleRequest $editRoleRequest)
    {

        $editUserMapper = $this->userEditMapper->mapping($user, $editRoleRequest);
        $this->userRepository->add($editUserMapper);
        $result = $this->userTransformer->fromArray($user);
        return $result;
    }
}
