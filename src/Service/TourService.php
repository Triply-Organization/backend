<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Tour;
use App\Entity\TourImage;
use App\Entity\TourPlan;
use App\Mapper\TourRequestToTour;
use App\Mapper\TourUpdateRequestToTour;
use App\Repository\DestinationRepository;
use App\Repository\ImageRepository;
use App\Repository\ServiceRepository;
use App\Repository\TourImageRepository;
use App\Repository\TourPlanRepository;
use App\Repository\TourRepository;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;

class TourService
{
    private TourRequestToTour $tourRequestToTour;
    private TourRepository $tourRepository;
    private ServiceRepository $serviceRepository;
    private DestinationRepository $destinationRepository;
    private TourPlanRepository $tourPlanRepository;
    private ImageRepository $imageRepository;
    private TourImageRepository $tourImageRepository;
    private TourUpdateRequestToTour $tourUpdateRequestToTour;

    public function __construct(
        TourRequestToTour $tourRequestToTour,
        TourRepository $tourRepository,
        ServiceRepository $serviceRepository,
        DestinationRepository $destinationRepository,
        TourPlanRepository $tourPlanRepository,
        ImageRepository $imageRepository,
        TourImageRepository $tourImageRepository,
        TourUpdateRequestToTour $tourUpdateRequestToTour
    ) {
        $this->tourRequestToTour = $tourRequestToTour;
        $this->tourRepository = $tourRepository;
        $this->serviceRepository = $serviceRepository;
        $this->destinationRepository = $destinationRepository;
        $this->tourPlanRepository = $tourPlanRepository;
        $this->imageRepository = $imageRepository;
        $this->tourImageRepository = $tourImageRepository;
        $this->tourUpdateRequestToTour = $tourUpdateRequestToTour;
    }

    public function addTour(TourRequest $tourRequest): Tour
    {
        $tourMapper = $this->tourRequestToTour->mapper($tourRequest);
        $tourImage = $this->addTourImage($tourRequest, $tourMapper);
        $tourService = $this->addServiceToTour($tourRequest, $tourImage);
        $tour = $this->addTourPlan($tourRequest, $tourService);
        $this->tourRepository->add($tour, true);

        return $tour;
    }

    public function updateTour(Tour $tour, TourUpdateRequest $tourUpdateRequest): Tour
    {
        $tourUpdateMapper = $this->tourUpdateRequestToTour->mapper($tour, $tourUpdateRequest);
        $this->updateTourPlan($tourUpdateRequest);
        if ($tourUpdateRequest->getServices()) {
            $this->updateServiceFromTour($tour, $tourUpdateRequest);
        }
        if ($tourUpdateRequest->getTourImages()) {
            $this->updateTourImage($tour, $tourUpdateRequest);
        }
        $this->tourRepository->add($tourUpdateMapper, true);

        return $tour;
    }

    public function delete(Tour $tour): void
    {
        $this->tourPlanRepository->deleteWithRelation('tour', $tour->getId());

        $this->tourImageRepository->deleteWithRelation('tour', $tour->getId());

        $this->tourRepository->delete($tour->getId());
    }

    public function undoDelete(Tour $tour): void
    {
        $this->tourPlanRepository->undoDeleteWithRelation('tour', $tour->getId());

        $this->tourImageRepository->undoDeleteWithRelation('tour', $tour->getId());

        $this->tourRepository->undoDelete($tour->getId());
    }

    private function addServiceToTour(TourRequest $tourRequest, Tour $tour): Tour
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

    private function addTourPlan(TourRequest $tourRequest, Tour $tour): Tour
    {
        foreach ($tourRequest->getTourPlans() as $tourPlanRequest) {
            $destination = $this->destinationRepository->find($tourPlanRequest['destination']);
            if (!is_object($destination)) {
                continue;
            }
            $tourPlan = new TourPlan();
            $tourPlan->setTitle($tourPlanRequest['title']);
            $tourPlan->setDescription($tourPlanRequest['description']);
            $tourPlan->setDay($tourPlanRequest['day']);
            $tourPlan->setDestination($destination);
            $tourPlan->setTour($tour);
            $this->tourPlanRepository->add($tourPlan);
        }

        return $tour;
    }

    private function addTourImage(TourRequest $tourRequest, Tour $tour): Tour
    {
        foreach ($tourRequest->getTourImages() as $tourImageRequest) {
            $image = $this->imageRepository->find($tourImageRequest['id']);
            if (!is_object($image)) {
                continue;
            }
            $tourImage = new TourImage();
            $tourImage->setType($tourImageRequest['type']);
            $tourImage->setTour($tour);
            $tourImage->setImage($image);
            $this->tourImageRepository->add($tourImage);
        }

        return $tour;
    }

    private function updateTourPlan(TourUpdateRequest $tourUpdateRequest): void
    {
        foreach ($tourUpdateRequest->getTourPlans() as $tourPlanRequest) {
            $destination = $this->destinationRepository->find($tourPlanRequest['destination']);
            if (!is_object($destination)) {
                continue;
            }
            $tourPlan = $this->tourPlanRepository->find($tourPlanRequest['id']);
            if (!is_object($tourPlan)) {
                continue;
            }
            $tourPlan->setTitle($tourPlanRequest['title'] ?? $tourPlan->getTitle());
            $tourPlan->setDescription($tourPlanRequest['description'] ?? $tourPlan->getDescription());
            $tourPlan->setDay($tourPlanRequest['day'] ?? $tourPlan->getDay());
            $tourPlan->setDestination($destination ?? $tourPlan->getDestination());
            $tourPlan->setUpdatedAt(new \DateTimeImmutable());
            $this->tourPlanRepository->add($tourPlan);
        }
    }

    private function updateServiceFromTour(Tour $tour, TourUpdateRequest $tourUpdateRequest): void
    {
        $newServices = $tourUpdateRequest->getServices()['newServiceToTour'];
        foreach ($newServices as $newService) {
            $service = $this->serviceRepository->find($newService);
            if (!is_object($service)) {
                continue;
            }
            $tour->addService($service);
        }
        $deleteServices = $tourUpdateRequest->getServices()['deleteServiceFromTour'];
        foreach ($deleteServices as $deleteService) {
            $service = $this->serviceRepository->find($deleteService);
            if (!is_object($deleteServices)) {
                continue;
            }
            $tour->removeService($service);
        }
    }

    private function updateTourImage(Tour $tour, TourUpdateRequest $tourUpdateRequest)
    {
        foreach ($tourUpdateRequest->getTourImages() as $tourImageRequest) {
            if (!$tourImageRequest['delete'] && !$tourImageRequest['type']) {
                continue;
            }
            if ($tourImageRequest['delete'] === true) {
                $this->deleteTourIamge($tourImageRequest);

                continue;
            }
            if ($tourImageRequest['type'] === "COVER") {
                $this->addTourImageTypeCover($tourImageRequest);

                continue;
            }
            $this->addTourImageTypeGallery($tour, $tourImageRequest);
        }

        return $tour;
    }

    private function deleteTourIamge(array $tourImageRequest)
    {
        $tourImageDelete = $this->tourImageRepository->find($tourImageRequest['idTourImage']);
        if (!is_object($tourImageDelete)) {
            return;
        }
        $tourImageDelete->setDeletedAt(new \DateTimeImmutable());
        $this->tourImageRepository->add($tourImageDelete);
    }

    private function addTourImageTypeGallery(Tour $tour, array $tourImageRequest)
    {
        $image = $this->imageRepository->find($tourImageRequest['id']);
        if (!is_object($image)) {
            return $tour;
        }
        $tourImage = new TourImage();
        $tourImage->setType($tourImageRequest['type']);
        $tourImage->setTour($tour);
        $tourImage->setImage($image);
        $tourImage->setUpdatedAt(new \DateTimeImmutable());
        $this->tourImageRepository->add($tourImage);

        return $tour;
    }

    private function addTourImageTypeCover(array $tourImageRequest): void
    {
        $tourImage = $this->tourImageRepository->find($tourImageRequest['idTourImage']);
        if (!is_object($tourImage)) {
            return;
        }
        $image = $this->imageRepository->find($tourImageRequest['id']);
        if (!is_object($image)) {
            return;
        }
        $tourImage->setImage($image);
        $tourImage->setUpdatedAt(new \DateTimeImmutable());
        $this->tourImageRepository->add($tourImage);
    }
}
