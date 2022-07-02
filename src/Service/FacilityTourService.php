<?php

namespace App\Service;

use App\Entity\Tour;
use App\Repository\ServiceRepository;
use App\Repository\TourServiceRepository;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;

class FacilityTourService
{
    private ServiceRepository $serviceRepository;
    private TourServiceRepository $tourServiceRepository;

    public function __construct(
        ServiceRepository     $serviceRepository,
        TourServiceRepository $tourServiceRepository
    )
    {
        $this->serviceRepository = $serviceRepository;
        $this->tourServiceRepository = $tourServiceRepository;
    }

    public function addServiceToTour(TourRequest $tourRequest, Tour $tour): void
    {
        foreach ($tourRequest->getServices() as $serviceRequest) {
            $service = $this->serviceRepository->find($serviceRequest);
            if (!is_object($service)) {
                continue;
            }
            $tourService = new \App\Entity\TourService();
            $tourService->setService($service);
            $tourService->setTour($tour);
            $this->tourServiceRepository->add($tourService);
        }
    }

    public function updateServiceFromTour(Tour $tour, TourUpdateRequest $tourUpdateRequest): void
    {
        $this->addNewServiceToTour($tour, $tourUpdateRequest);
        $this->deleteServiceFromTour($tour, $tourUpdateRequest);
    }

    private function addNewServiceToTour(Tour $tour, TourUpdateRequest $tourUpdateRequest)
    {
        if ($tourUpdateRequest->getServices()['newServiceToTour']) {
            $newServices = $tourUpdateRequest->getServices()['newServiceToTour'];
            foreach ($newServices as $newService) {
                $service = $this->serviceRepository->find($newService);
                if (!is_object($service)) {
                    continue;
                }
                $tour->addService($service);
            }
        }
    }

    private function deleteServiceFromTour(Tour $tour, TourUpdateRequest $tourUpdateRequest)
    {
        if ($tourUpdateRequest->getServices()['deleteServiceFromTour']) {
            $deleteServices = $tourUpdateRequest->getServices()['deleteServiceFromTour'];
            foreach ($deleteServices as $deleteService) {
                $service = $this->serviceRepository->find($deleteService);
                if (!is_object($deleteServices)) {
                    continue;
                }
                $tour->removeService($service);
            }
        }
    }
}
