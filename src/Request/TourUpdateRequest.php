<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class TourUpdateRequest extends BaseRequest
{
    #[Assert\Type('string')]
    private $title = self::STRING_DEFAULT;
    #[Assert\Type('integer')]
    private $duration = self::INT_DEFAULT;

    #[Assert\Type('integer')]
    private $maxPeople = self::INT_DEFAULT;

    #[Assert\Type('integer')]
    private $minAge = self::INT_DEFAULT;

    #[Assert\Type('string')]
    private $overView = self::STRING_DEFAULT;

    #[Assert\Type('numeric')]
    private $price = self::NUMERIC_DEFAULT;

    #[Assert\Type('array')]
    private $tourPlan = self::ARRAY_DEFAULT;

    #[Assert\Type('array')]
    private $service = self::ARRAY_DEFAULT;

    #[Assert\Type('array')]
    private $tourImage = self::ARRAY_DEFAULT;

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param null $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return null
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param null $duration
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return null
     */
    public function getMaxPeople()
    {
        return $this->maxPeople;
    }

    /**
     * @param null $maxPeople
     */
    public function setMaxPeople($maxPeople): void
    {
        $this->maxPeople = $maxPeople;
    }

    /**
     * @return null
     */
    public function getMinAge()
    {
        return $this->minAge;
    }

    /**
     * @param null $minAge
     */
    public function setMinAge($minAge): void
    {
        $this->minAge = $minAge;
    }

    /**
     * @return null
     */
    public function getOverView()
    {
        return $this->overView;
    }

    /**
     * @param null $overView
     */
    public function setOverView($overView): void
    {
        $this->overView = $overView;
    }

    /**
     * @return null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param null $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return array
     */
    public function getTourPlan(): array
    {
        return $this->tourPlan;
    }

    /**
     * @param array $tourPlan
     */
    public function setTourPlan(array $tourPlan): void
    {
        $this->tourPlan = $tourPlan;
    }

    /**
     * @return array
     */
    public function getService(): array
    {
        return $this->service;
    }

    /**
     * @param array $service
     */
    public function setService(array $service): void
    {
        $this->service = $service;
    }

    /**
     * @return array
     */
    public function getTourImage(): array
    {
        return $this->tourImage;
    }

    /**
     * @param array $tourImage
     */
    public function setTourImage(array $tourImage): void
    {
        $this->tourImage = $tourImage;
    }
}