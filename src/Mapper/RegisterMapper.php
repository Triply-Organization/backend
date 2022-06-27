<?php

namespace App\Mapper;

use App\Entity\User;
use App\Request\RegisterRequest;

class RegisterMapper
{
    public function mapping(RegisterRequest $registerRequest): User
    {
        $user = new User();
        $user->setName($registerRequest->getName())
            ->setEmail($registerRequest->getEmail())
            ->setPassword(password_hash($registerRequest->getPassword(), PASSWORD_DEFAULT))
            ->setRoles($registerRequest->getRoles())
            ->setPhone($registerRequest->getPhone());

        return $user;
    }
}
