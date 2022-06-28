<?php

namespace App\Transformer;

use App\Entity\User;
use JetBrains\PhpStorm\ArrayShape;

class UserTransformer extends BaseTransformer
{
    private const PARAMS = ['id' ,'name', 'email', 'phone','address', 'roles'];

    public function fromArray(User $user): array
    {
        return $this->transform($user, static::PARAMS);
    }
}
