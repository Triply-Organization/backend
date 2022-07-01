<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CheckoutRequest extends BaseRequest
{
    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $tourId;

    #[Assert\Date]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $date;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $orderDetails;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $name;

    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $email;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $phone;

    /**
     * @return mixed
     */
    public function getTourId()
    {
        return $this->tourId;
    }

    /**
     * @param mixed $tourId
     */
    public function setTourId($tourId): void
    {
        $this->tourId = $tourId;
    }

    /**
     * @return mixed
     */
    public function getOrderDetails()
    {
        return $this->orderDetails;
    }

    /**
     * @param mixed $orderDetails
     */
    public function setOrderDetails($orderDetails): void
    {
        $this->orderDetails = $orderDetails;
    }

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
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $currency;

    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $amount;


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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }
}
