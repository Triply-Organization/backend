<?php

namespace App\Service;

use App\Repository\TourRepository;
use App\Entity\Tour;
use App\Request\TourRequest;
use App\Repository\TourImageRepository;
use App\Repository\ImageRepository;
use App\Transformer\TourImageGallaryTransformer;
use App\Repository\ServiceRepository;
use App\Repository\TourPlanRepository;
use App\Transformer\TourPlansTransformer;
use App\Transformer\TourServicesTransformer;

class TourService
{
    private TourRepository $tourRepository;
    private TourImageRepository $tourImageRepository;
    private TourImageGallaryTransformer $tourImageGallaryTransformer;
    private TourPlanRepository $tourPlanRepository;
    private ServiceRepository $serviceRepository;
    private TourPlansTransformer $tourPlansTransformer;

    public function __construct(
        TourRepository              $tourRepository,
        TourImageRepository         $tourImageRepository,
        TourImageGallaryTransformer $tourImageGallaryTransformer,
        TourPlanRepository          $tourPlanRepository,
        ServiceRepository           $serviceRepository,
        TourPlansTransformer        $tourPlansTransformer,

    )
    {
        $this->tourRepository = $tourRepository;
        $this->tourImageRepository = $tourImageRepository;
        $this->tourImageGallaryTransformer = $tourImageGallaryTransformer;
        $this->tourPlanRepository = $tourPlanRepository;
        $this->serviceRepository = $serviceRepository;
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
            $gallery[] = $this->tourImageGallaryTransformer->toArray($tourImage);
        }

        return $gallery;
    }

    public function getTourPlan(Tour $tour): array
    {
        $tourPlans = $this->tourPlanRepository->findBy(['tour' => $tour]);
        $plans = [];
        foreach ($tourPlans as $tourPlan) {
            $plans[] = $this->tourPlansTransformer->toArray($tourPlan);
        }

        return $plans;
    }

    public function getServices(Tour $tour): array
    {
        $tourServices = $this->serviceRepository->getServices($tour->getId());
        return $tourServices;
    }
}
