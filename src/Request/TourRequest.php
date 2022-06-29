<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class TourRequest extends BaseRequest
{
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $title;

    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $duration;

    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $maxPeople;

    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $minAge;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $overView;

    #[Assert\Type('float')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $price;

    #[Assert\Type('array')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $service;

    #[Assert\Type('array')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $tourPlan;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $tourImage;


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration): void
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getMaxPeople()
    {
        return $this->maxPeople;
    }

    /**
     * @param mixed $maxPeople
     */
    public function setMaxPeople($maxPeople): void
    {
        $this->maxPeople = $maxPeople;
    }

    /**
     * @return mixed
     */
    public function getMinAge()
    {
        return $this->minAge;
    }

    /**
     * @param mixed $minAge
     */
    public function setMinAge($minAge): void
    {
        $this->minAge = $minAge;
    }

    /**
     * @return mixed
     */
    public function getOverView()
    {
        return $this->overView;
    }

    /**
     * @param mixed $overView
     */
    public function setOverView($overView): void
    {
        $this->overView = $overView;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service): void
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getTourPlan()
    {
        return $this->tourPlan;
    }

    /**
     * @param mixed $tourPlan
     */
    public function setTourPlan($tourPlan): void
    {
        $this->tourPlan = $tourPlan;
    }

    /**
     * @return mixed
     */
    public function getTourImage()
    {
        return $this->tourImage;
    }

    /**
     * @param mixed $tourImage
     */
    public function setTourImage($tourImage): void
    {
        $this->tourImage = $tourImage;
    }
}
