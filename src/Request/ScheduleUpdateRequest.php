<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ScheduleUpdateRequest extends BaseRequest
{
    #[Assert\Type('numeric')]
    private $children;

    #[Assert\Type('numeric')]
    private $youth;

    #[Assert\Type('numeric')]
    private $adult;

    #[Assert\Type('string')]
    private $dateStart;

    #[Assert\Type('int')]
    private $remain;

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

    /**
     * @return mixed
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param mixed $dateStart
     */
    public function setDateStart($dateStart): void
    {
        $this->dateStart = $dateStart;
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
