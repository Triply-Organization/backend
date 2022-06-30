<?php

namespace App\Service;

use App\Entity\Tour;
use App\Repository\ServiceRepository;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;

class TourServiceService
{

    private ServiceRepository $serviceRepository;

    public function __construct(
        ServiceRepository $serviceRepository
    )
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function addServiceToTour(TourRequest $tourRequest, Tour $tour): Tour
    {
        foreach ($tourRequest->getServices() as $serviceRequest) {
            $service = $this->serviceRepository->find($serviceRequest);
            if (!is_object($service)) {
                continue;
            }
            $tour->addService($service);
        }

        return $tour;
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
