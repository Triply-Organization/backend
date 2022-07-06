<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class EditRoleRequest extends BaseRequest
{
    #[Assert\Type('array')]
    private $role;

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }
}
