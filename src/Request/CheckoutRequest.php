<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CheckoutRequest extends BaseRequest
{
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $name;

    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $amount;


    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $quantity;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }
}
