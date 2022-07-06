<?php

namespace App\Mapper;

use App\Entity\Tour;
use App\Entity\User;
use App\Repository\ImageRepository;
use App\Repository\ServiceRepository;
use App\Request\TourRequest;
use Symfony\Component\Security\Core\Security;

class TourCreateMapper
{
    private Security $security;
    private const PENDING = 'pending';

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function mapping(TourRequest $tourRequest): Tour
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->security->getUser();
        $tour = new Tour();
        $tour->setTitle($tourRequest->getTitle())
            ->setDuration($tourRequest->getDuration())
            ->setMaxPeople($tourRequest->getMaxPeople())
            ->setMinAge($tourRequest->getMinAge())
            ->setOverView($tourRequest->getOverView())
            ->setCreatedUser($currentUser)
            ->setStatus(self::PENDING);
        return $tour;
    }
}
