<?php

namespace App\Service;

use App\Transformer\TourServicesTransformer;
use App\Repository\ServiceRepository;

class FacilityService
{
    private TourServicesTransformer $tourServicesTransformer;
    private ServiceRepository $serviceRepository;

    public function __construct(TourServicesTransformer $tourServicesTransformer, ServiceRepository $serviceRepository)
    {
        $this->tourServicesTransformer = $tourServicesTransformer;
        $this->serviceRepository = $serviceRepository;
    }

    public function getServices($services): array
    {
        $tourServiceList = [];
        foreach ($services as $service) {
            $tourServiceList[] = $this->tourServicesTransformer->toArray($service);
        }

        return $tourServiceList;
    }

    public function getAllService()
    {
        $services = $this->getServices($this->serviceRepository->findAll());
        return $services;
    }
}
