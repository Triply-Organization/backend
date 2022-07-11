<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CheckoutRequest extends BaseRequest
{
    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $tourId;

    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $orderId;

    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $scheduleId;

    #[Assert\Type('numeric')]
    private $voucherId;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $tourName;

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


    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $currency;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $totalPrice;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $discountPrice;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $taxPrice;

    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $numberOfTickets;

    /**
     * @return mixed
     */
    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    /**
     * @param mixed $discountPrice
     */
    public function setDiscountPrice($discountPrice): void
    {
        $this->discountPrice = $discountPrice;
    }

    /**
     * @return mixed
     */
    public function getTaxPrice()
    {
        return $this->taxPrice;
    }

    /**
     * @param mixed $taxPrice
     */
    public function setTaxPrice($taxPrice): void
    {
        $this->taxPrice = $taxPrice;
    }


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
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId): void
    {
        $this->orderId = $orderId;
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

    /**
     * @return mixed
     */
    public function getTourName()
    {
        return $this->tourName;
    }

    /**
     * @param mixed $tourName
     */
    public function setTourName($tourName): void
    {
        $this->tourName = $tourName;
    }

    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice): void
    {
        $this->totalPrice = $totalPrice;
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

    /**
     * @return mixed
     */
    public function getScheduleId()
    {
        return $this->scheduleId;
    }

    /**
     * @param mixed $scheduleId
     */
    public function setScheduleId($scheduleId): void
    {
        $this->scheduleId = $scheduleId;
    }

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
    public function getVoucherId()
    {
        return $this->voucherId;
    }

    /**
     * @param mixed $voucherId
     */
    public function setVoucherId($voucherId): void
    {
        $this->voucherId = $voucherId;
    }

    /**
     * @return mixed
     */
    public function getNumberOfTickets()
    {
        return $this->numberOfTickets;
    }

    /**
     * @param mixed $numberOfTickets
     */
    public function setNumberOfTickets($numberOfTickets): void
    {
        $this->numberOfTickets = $numberOfTickets;
    }
}
