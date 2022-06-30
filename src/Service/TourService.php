<?php

namespace App\Service;

use App\Entity\Service;
use App\Repository\TourRepository;
use App\Entity\Tour;
use App\Request\TourRequest;
use App\Repository\TourImageRepository;
use App\Repository\ImageRepository;
use App\Transformer\TourImageTransformer;
use App\Repository\ServiceRepository;
use App\Repository\TourPlanRepository;
use App\Transformer\TourPlansTransformer;
use App\Transformer\TourServicesTransformer;

class TourService
{
    private TourRepository $tourRepository;
    private TourImageRepository $tourImageRepository;
    private TourImageTransformer $tourImageTransformer;
    private TourServicesTransformer $tourServicesTransformer;
    private TourPlansTransformer $tourPlansTransformer;

    public function __construct(
        TourRepository          $tourRepository,
        TourImageRepository     $tourImageRepository,
        TourImageTransformer    $tourImageTransformer,
        TourPlansTransformer    $tourPlansTransformer,
        TourServicesTransformer $tourServicesTransformer
    )
    {
        $this->tourRepository = $tourRepository;
        $this->tourImageRepository = $tourImageRepository;
        $this->tourImageTransformer = $tourImageTransformer;
        $this->tourServicesTransformer = $tourServicesTransformer;
        $this->tourPlansTransformer = $tourPlansTransformer;
    }

    public function findAll(TourRequest $tourRequest): array
    {
        return $this->tourRepository->getAll($tourRequest);
    }

    public function getCover(Tour $tour): string
    {
        $tourImages = $this->tourImageRepository->findBy(['tour' => $tour]);
        $path = '';
        foreach ($tourImages as $tourImage) {
            if ($tourImage->getType() === 'cover') {
                $path = $tourImage->getImage()->getPath();
            }
        }

        return $path;
    }

    public function getGallary(Tour $tour): array
    {
        $tourImages = $this->tourImageRepository->findBy(['tour' => $tour]);
        $gallery = [];
        foreach ($tourImages as $tourImage) {
            $gallery[] = $this->tourImageTransformer->toArray($tourImage);
        }

        return $gallery;
    }

    public function getTourPlan($plans): array
    {
        $tourPlans = [];
        foreach ($plans as $plan) {
            $tourPlans[] = $this->tourPlansTransformer->toArray($plan);
        }

        return $tourPlans;
    }

    public function getServices($services): array
    {
        $tourServiceList = [];
        foreach ($services as $service) {
            $tourServiceList[] = $this->tourServicesTransformer->toArray($service);
        }

        return $tourServiceList;
    }
}
