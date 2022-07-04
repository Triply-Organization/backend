<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class OrderRequest extends BaseRequest
{
    #[Assert\Type('integer')]
    private $discountId;

    #[Assert\Type('array')]
    private $children;

    #[Assert\Type('array')]
    private $youth;

    #[Assert\Type('array')]
    private $adult;

    /**
     * @return mixed
     */
    public function getDiscountId()
    {
        return $this->discountId;
    }

    /**
     * @param mixed $discountId
     */
    public function setDiscountId($discountId): void
    {
        $this->discountId = $discountId;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children): void
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getYouth()
    {
        return $this->youth;
    }

    /**
     * @param mixed $youth
     */
    public function setYouth($youth): void
    {
        $this->youth = $youth;
    }

    /**
     * @return mixed
     */
    public function getAdult()
    {
        return $this->adult;
    }

    /**
     * @param mixed $adult
     */
    public function setAdult($adult): void
    {
        $this->adult = $adult;
    }
}
