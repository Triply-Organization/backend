<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Request\ListUserRequest;
use App\Transformer\UserTransformer;

class ListUserService
{
    private UserRepository $userRepository;
    private UserTransformer $userTransformer;

    public function __construct(
        UserRepository  $userRepository,
        ListUserRequest $listUserRequest,
        UserTransformer $userTransformer
    )
    {
        $this->userRepository = $userRepository;
        $this->listUserRequest = $listUserRequest;
        $this->userTransformer = $userTransformer;
    }

    public function getUsers(ListUserRequest $listUserRequest)
    {
        $data = $this->userRepository->getAll($listUserRequest);
        $users = $data['users'];
        $results = [];
        foreach ($users as $user) {
            $results['users'][] = $this->userTransformer->fromArray($user);
        }

        return $results;
    }

}