<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class AddTaxRequest extends BaseRequest
{
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $currency;

    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $percent;

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
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param mixed $percent
     */
    public function setPercent($percent): void
    {
        $this->percent = $percent;
    }
}
