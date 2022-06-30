<?php

namespace App\Mapper;

use App\Entity\Tour;
use App\Entity\User;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;
use Symfony\Component\Security\Core\Security;

class TourUpdateRequestToTour
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function mapper(Tour $tour, TourUpdateRequest $tourUpdateRequest): Tour
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->security->getUser();
        $tour->setTitle($tourUpdateRequest->getTitle() ?? $tour->getTitle())
            ->setDuration($tourUpdateRequest->getDuration() ?? $tour->getDuration())
            ->setMaxPeople($tourUpdateRequest->getMaxPeople() ?? $tour->getMaxPeople())
            ->setMinAge($tourUpdateRequest->getMinAge() ?? $tour->getMinAge())
            ->setOverView($tourUpdateRequest->getOverView() ?? $tour->getOverView())
            ->setPrice($tourUpdateRequest->getPrice() ?? $tour->getPrice())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setCreatedUser($currentUser);
        return $tour;
    }
}