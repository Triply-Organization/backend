<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class UserService
{
    private Security $security;

    public function __construct(
        Security $security
    ) {
        $this->security = $security;
    }

    public function getAllOrder()
    {
        $currentUser = $this->security->getUser();
        $result = [];
        $result['user']['id'] = $currentUser->getId();
        $result['user']['email'] = $currentUser->getEmail();
        $result['user']['fullname'] = $currentUser->getName();
        $result['user']['avatar'] = $currentUser->getAvatar();
        foreach ($currentUser->getOrders() as $key => $order) {
            $result['orders'][$key] = $order;
        }
        return $result;
    }
}
