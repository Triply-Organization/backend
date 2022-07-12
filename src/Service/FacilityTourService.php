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

    public function addServiceToTour(TourRequest $tourRequest, Tour $tour): bool
    {
        foreach ($tourRequest->getServices() as $serviceRequest) {
            $service = $this->serviceRepository->find($serviceRequest);
            if (!$service) {
                continue;
            }
            $tourService = new \App\Entity\TourService();
            $tourService->setService($service);
            $tourService->setTour($tour);
            $this->tourServiceRepository->add($tourService);
        }

        return true;
    }

    public function updateServiceFromTour(Tour $tour, TourUpdateRequest $tourUpdateRequest): bool
    {
        $this->addNewServiceToTour($tour, $tourUpdateRequest);
        $this->deleteServiceFromTour($tourUpdateRequest);

        return true;
    }

    public function addNewServiceToTour(Tour $tour, TourUpdateRequest $tourUpdateRequest): bool
    {
        if ($tourUpdateRequest->getServices()['newServiceToTour'] !== null) {
            $newServices = $tourUpdateRequest->getServices()['newServiceToTour'];
            foreach ($newServices as $newService) {
                $service = $this->serviceRepository->find($newService);
                if (!$service) {
                    continue;
                }
                $newTourService = new \App\Entity\TourService();
                $newTourService->setService($service);
                $newTourService->setTour($tour);
                $newTourService->setUpdatedAt(new \DateTimeImmutable());
                $this->tourServiceRepository->add($newTourService);
            }
        }

        return true;
    }

    public function deleteServiceFromTour(TourUpdateRequest $tourUpdateRequest): bool
    {
        if ($tourUpdateRequest->getServices()['deleteServiceFromTour'] !== null) {
            $deleteServices = $tourUpdateRequest->getServices()['deleteServiceFromTour'];
            foreach ($deleteServices as $deleteService) {
                $tourService = $this->tourServiceRepository->find($deleteService);
                if (!$tourService) {
                    continue;
                }
                $tourService->setDeletedAt(new \DateTimeImmutable());
                $this->tourServiceRepository->add($tourService);
            }
        }

        return true;
    }
}
