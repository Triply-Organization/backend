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
        $this->deleteServiceFromTour($tourUpdateRequest);
    }

    private function addNewServiceToTour(Tour $tour, TourUpdateRequest $tourUpdateRequest): void
    {
        if ($tourUpdateRequest->getServices()['newServiceToTour'] !== null) {
            $newServices = $tourUpdateRequest->getServices()['newServiceToTour'];
            foreach ($newServices as $newService) {
                $service = $this->serviceRepository->find($newService);
                if (!is_object($service)) {

                    continue;
                }
                $newTourService = new \App\Entity\TourService();
                $newTourService->setService($service);
                $newTourService->setTour($tour);
                $newTourService->setUpdatedAt(new \DateTimeImmutable());
                $this->tourServiceRepository->add($newTourService);
            }
        }
    }

    private function deleteServiceFromTour(TourUpdateRequest $tourUpdateRequest): void
    {
        if ($tourUpdateRequest->getServices()['deleteServiceFromTour'] !== null) {
            $deleteServices = $tourUpdateRequest->getServices()['deleteServiceFromTour'];
            foreach ($deleteServices as $deleteService) {
                $tourService = $this->tourServiceRepository->find($deleteService);
                if (!is_object($tourService)) {

                    continue;
                }
                $tourService->setDeletedAt(new \DateTimeImmutable());
                $this->tourServiceRepository->add($tourService);
            }
        }
    }
}
