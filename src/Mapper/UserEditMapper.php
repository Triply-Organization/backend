<?php

namespace App\Mapper;

use App\Entity\Tour;
use App\Entity\User;
use App\Request\EditRoleRequest;

class UserEditMapper
{
    public function mapping(User $user, EditRoleRequest $editRoleRequest)
    {
        $user->setRoles($editRoleRequest->getRole() ?? $user->getRoles())->setUpdatedAt(new \DateTimeImmutable());
        return $user;
    }
}
