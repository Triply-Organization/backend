<?php

namespace App\Request;
use Symfony\Component\Validator\Constraints as Assert;

class PatchUpdateVoucherRequest extends BaseRequest
{
    #[Assert\Type('string')]
    private $code;

    #[Assert\Type('numeric')]
    private $percent;

    #[Assert\Type('numeric')]
    private $remain;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
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

    /**
     * @return mixed
     */
    public function getRemain()
    {
        return $this->remain;
    }

    /**
     * @param mixed $remain
     */
    public function setRemain($remain): void
    {
        $this->remain = $remain;
    }
}
