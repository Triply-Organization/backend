<?php

namespace App\Service;

use App\Repository\TourRepository;
use App\Repository\TourServiceRepository;
use App\Transformer\ServiceTransformer;
use App\Transformer\TourServicesTransformer;
use App\Repository\ServiceRepository;

class FacilityService
{
    private ServiceRepository $serviceRepository;
    private ServiceTransformer $serviceTransformer;
    private TourServicesTransformer $tourServicesTransformer;
    private TourRepository $tourRepository;
    private ReviewService $reviewService;

    public function __construct(
        ServiceTransformer      $serviceTransformer,
        ServiceRepository       $serviceRepository,
        TourServicesTransformer $tourServicesTransformer,
        TourRepository          $tourRepository,
        ReviewService           $reviewService
    )
    {
        $this->serviceTransformer = $serviceTransformer;
        $this->serviceRepository = $serviceRepository;
        $this->tourServicesTransformer = $tourServicesTransformer;
        $this->tourRepository = $tourRepository;
        $this->reviewService = $reviewService;
    }

    public function getPopularTour()
    {
        $tours = $this->tourRepository->findAll();
        $ratings = [];
        foreach ($tours as $key => $tour) {
            $ratings[$tour->getId()] = $this->reviewService->ratingForTour($tour);
        }

        return $ratings;
    }

    public function getService($tourServices): array
    {
        $tourServiceList = [];
        foreach ($tourServices as $tourService) {
            $tourServiceList[] = $this->tourServicesTransformer->toArray($tourService);
        }

        return $tourServiceList;
    }

    public function getAllService()
    {
        $services = $this->serviceRepository->findAll();
        foreach ($services as $service) {
            $result[] = $this->serviceTransformer->toArray($service);
        }

        return $result;
    }
}
