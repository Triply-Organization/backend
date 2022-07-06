<?php

namespace App\Transformer;

use App\Entity\User;
use JetBrains\PhpStorm\ArrayShape;

class UserTransformer extends BaseTransformer
{
    private const PARAMS = ['id' ,'name', 'email', 'phone','address', 'roles'];

    public function fromArray(User $user): array
    {
        $result = $this->transform($user, static::PARAMS);
        $result['avatar'] = is_null($user->getAvatar()) ? null : $user->getAvatar()->getPath();

        return $result;
    }
}
