<?php

namespace App\Transformer;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'name', 'email', 'phone', 'address', 'roles'];

    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function fromArray(User $user): array
    {
        $result = $this->transform($user, static::PARAMS);
        $result['avatar'] = $user->getAvatar()
            ? $this->params->get('s3url') . $user->getAvatar()->getPath()
            : null;

        return $result;
    }
}
