<?php

namespace App\Service;

use App\Repository\TourServiceRepository;
use App\Transformer\TourServicesTransformer;
use App\Repository\ServiceRepository;

class FacilityService
{
    private TourServicesTransformer $tourServicesTransformer;
    private TourServiceRepository $tourServiceRepository;

    public function __construct(TourServicesTransformer $tourServicesTransformer, TourServiceRepository $tourServiceRepository)
    {
        $this->tourServicesTransformer = $tourServicesTransformer;
        $this->tourServiceRepository = $tourServiceRepository;
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
        $result = [];
        $tourServices = $this->tourServiceRepository->findAll();
        foreach ($tourServices as $tourService) {
            $result[] = $this->tourServicesTransformer->toArray($tourService);
        }

        return $result;
    }
}
