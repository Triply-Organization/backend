<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class PatchUpdateTaxRequest extends \App\Request\BaseRequest
{
    #[Assert\Type('string')]
    private $currency;

    #[Assert\Type('numeric')]
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
