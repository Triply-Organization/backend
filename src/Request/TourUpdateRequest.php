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

    #[Assert\Type('array')]
    private $tourPlans = self::ARRAY_DEFAULT;

    #[Assert\Type('array')]
    private $services = self::ARRAY_DEFAULT;

    #[Assert\Type('array')]
    private $tourImages = self::ARRAY_DEFAULT;

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
     * @return array
     */
    public function getTourPlans(): array
    {
        return $this->tourPlans;
    }

    /**
     * @param array $tourPlan
     */
    public function setTourPlans(array $tourPlans): void
    {
        $this->tourPlans = $tourPlans;
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * @param array $service
     */
    public function setServices(array $services): void
    {
        $this->services = $services;
    }

    /**
     * @return array
     */
    public function getTourImages(): array
    {
        return $this->tourImages;
    }

    /**
     * @param array $tourImage
     */
    public function setTourImages(array $tourImages): void
    {
        $this->tourImages = $tourImages;
    }
}
