<?php

namespace App\Mapper;

use App\Entity\User;
use App\Repository\ImageRepository;
use App\Request\RegisterRequest;

class RegisterMapper
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function mapping(RegisterRequest $registerRequest): User
    {
        $user = new User();
        $imageId = $registerRequest->getImageId() ?? '';
        $image = $this->imageRepository->find($imageId);
        $user->setName($registerRequest->getName())
            ->setEmail($registerRequest->getEmail())
            ->setPassword(password_hash($registerRequest->getPassword(), PASSWORD_DEFAULT))
            ->setRoles($registerRequest->getRoles())
            ->setPhone($registerRequest->getPhone())
            ->setAvatar($image);

        return $user;
    }
}
