<?php

namespace App\Mapper;

use App\Entity\User;
use App\Repository\ImageRepository;
use App\Request\BaseRequest;

class UserUpdateMapper
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function mapping(User $user, BaseRequest $updateUserRequest): User
    {
        $avatarId = $updateUserRequest->getAvatar() ?? '';
        $avatar = $this->imageRepository->find($avatarId);

        $user->setEmail($updateUserRequest->getEmail() ?? $user->getEmail())
            ->setRoles($updateUserRequest->getRoles() ?? $user->getRoles())
            ->setPhone($updateUserRequest->getPhone() ?? $user->getPhone())
            ->setAddress($updateUserRequest->getAddress() ?? $user->getAddress())
            ->setName($updateUserRequest->getName() ?? $user->getName())
            ->setAvatar($avatar ?? $user->getAvatar())
            ->setUpdatedAt(new \DateTimeImmutable());
        return $user;
    }
}
