<?php

namespace App\Service;

use App\Entity\Tour;
use App\Entity\TourPlan;
use App\Repository\DestinationRepository;
use App\Repository\TourPlanRepository;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;
use App\Transformer\TourPlansTransformer;


class TourPlanService
{
    private DestinationRepository $destinationRepository;
    private TourPlanRepository $tourPlanRepository;
    private TourPlansTransformer $tourPlansTransformer;

    public function __construct(
        DestinationRepository $destinationRepository,
        TourPlanRepository    $tourPlanRepository,
        TourPlansTransformer  $tourPlansTransformer
    )
    {
        $this->destinationRepository = $destinationRepository;
        $this->tourPlanRepository = $tourPlanRepository;
        $this->tourPlansTransformer = $tourPlansTransformer;
    }

    public function addTourPlan(TourRequest $tourRequest, Tour $tour): Tour
    {
        foreach ($tourRequest->getTourPlans() as $tourPlanRequest) {
            $destination = $this->destinationRepository->find($tourPlanRequest['destination']);
            if (!is_object($destination)) {
                continue;
            }
            $tourPlan = new TourPlan();
            $tourPlan->setTitle($tourPlanRequest['title']);
            $tourPlan->setDescription($tourPlanRequest['description']);
            $tourPlan->setDay($tourPlanRequest['day']);
            $tourPlan->setDestination($destination);
            $tourPlan->setTour($tour);
            $this->tourPlanRepository->add($tourPlan);
        }

        return $tour;
    }

    public function getTourPlan($plans): array
    {
        $tourPlans = [];
        foreach ($plans as $plan) {
            $tourPlans[] = $this->tourPlansTransformer->toArray($plan);
        }

        return $tourPlans;
    }

    public function updateTourPlan(TourUpdateRequest $tourUpdateRequest): void
    {
        foreach ($tourUpdateRequest->getTourPlans() as $tourPlanRequest) {
            $destination = $this->destinationRepository->find($tourPlanRequest['destination']);
            if (!is_object($destination)) {

                continue;
            }
            $tourPlan = $this->tourPlanRepository->find($tourPlanRequest['id']);
            if (!is_object($tourPlan)) {

                continue;
            }
            $tourPlan->setTitle($tourPlanRequest['title'] ?? $tourPlan->getTitle());
            $tourPlan->setDescription($tourPlanRequest['description'] ?? $tourPlan->getDescription());
            $tourPlan->setDay($tourPlanRequest['day'] ?? $tourPlan->getDay());
            $tourPlan->setDestination($destination ?? $tourPlan->getDestination());
            $tourPlan->setUpdatedAt(new \DateTimeImmutable());
            $this->tourPlanRepository->add($tourPlan);
        }
    }
}