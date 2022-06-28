<?php

namespace App\Transformer;

use App\Entity\User;
use JetBrains\PhpStorm\ArrayShape;

class UserTransformer
{
    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'email' => "null|string", 'phone' => "null|string", 'address' => "null|string", 'roles' => "array|string[]"])]
    public function transform(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'address' => $user->getAddress(),
            'roles' => $user->getRoles(),
        ];
    }
}
