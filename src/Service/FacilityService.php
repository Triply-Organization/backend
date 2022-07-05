<?php

namespace App\Service;

use App\Repository\TourServiceRepository;
use App\Transformer\ServiceTransformer;
use App\Transformer\TourServicesTransformer;
use App\Repository\ServiceRepository;

class FacilityService
{
    private ServiceRepository $serviceRepository;
    private ServiceTransformer $serviceTransformer;
    private TourServicesTransformer $tourServicesTransformer;

    public function __construct(
        ServiceTransformer $serviceTransformer,
        ServiceRepository $serviceRepository,
        TourServicesTransformer $tourServicesTransformer
    ) {
        $this->serviceTransformer = $serviceTransformer;
        $this->serviceRepository = $serviceRepository;
        $this->tourServicesTransformer = $tourServicesTransformer;
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
