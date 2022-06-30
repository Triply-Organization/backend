<?php

namespace App\Transformer;

use App\Entity\User;
use JetBrains\PhpStorm\ArrayShape;

class UserJsonTransformer extends BaseTransformer
{
    #[ArrayShape(['id' => "int|null", 'name' => "mixed"])]
    public function jsonParse(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName()
        ];
    }
}
