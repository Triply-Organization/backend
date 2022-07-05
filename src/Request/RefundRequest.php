<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class RefundRequest extends BaseRequest
{
    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $billId;

    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $orderId;


    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $stripeId;

    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $dayRemain;

    /**
     * @return mixed
     */
    public function getStripeId()
    {
        return $this->stripeId;
    }

    /**
     * @param mixed $stripeId
     */
    public function setStripeId($stripeId): void
    {
        $this->stripeId = $stripeId;
    }

    /**
     * @return mixed
     */
    public function getDayRemain()
    {
        return $this->dayRemain;
    }

    /**
     * @param mixed $dayRemain
     */
    public function setDayRemain($dayRemain): void
    {
        $this->dayRemain = $dayRemain;
    }

    /**
     * @return mixed
     */
    public function getBillId()
    {
        return $this->billId;
    }

    /**
     * @param mixed $billId
     */
    public function setBillId($billId): void
    {
        $this->billId = $billId;
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


}
