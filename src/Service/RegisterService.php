<?php

namespace App\Service;

use App\Entity\User;
use App\Mapper\RegisterMapper;
use App\Repository\UserRepository;
use App\Request\RegisterRequest;

class RegisterService
{
    private UserRepository $userRepository;
    private RegisterMapper $registerMapper;

    public function __construct(
        UserRepository $userRepository,
        RegisterMapper $registerMapper,
    ) {
        $this->userRepository = $userRepository;
        $this->registerMapper = $registerMapper;
    }

    public function register(RegisterRequest $requestData): User
    {
        $user = $this->registerMapper->mapping($requestData);
        $this->userRepository->add($user, true);

        return $user;
    }
}
