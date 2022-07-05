<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UserRequest extends BaseRequest
{
    public const DEFAULT_PAGE = 1;

    #[Assert\Type('string')]
    private $email;

    #[Assert\Type('numeric')]
    private $page = self::DEFAULT_PAGE;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page): void
    {
        $this->page = (int) $page;
    }
}
