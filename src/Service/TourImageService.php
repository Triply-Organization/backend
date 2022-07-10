<?php

namespace App\Service;

use App\Entity\Tour;
use App\Entity\TourImage;
use App\Repository\ImageRepository;
use App\Repository\TourImageRepository;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;
use App\Transformer\TourImageTransformer;

class TourImageService
{
    private ImageRepository $imageRepository;
    private TourImageRepository $tourImageRepository;
    private TourImageTransformer $tourImageTransformer;

    public function __construct(
        ImageRepository $imageRepository,
        TourImageRepository $tourImageRepository,
        TourImageTransformer $tourImageTransformer
    ) {
        $this->imageRepository = $imageRepository;
        $this->tourImageRepository = $tourImageRepository;
        $this->tourImageTransformer = $tourImageTransformer;
    }

    public function getGallery(Tour $tour): array
    {
        $tourImages = $this->tourImageRepository->findBy(['tour' => $tour]);
        $gallery = [];
        foreach ($tourImages as $tourImage) {
            $gallery[] = $this->tourImageTransformer->toArray($tourImage);
        }

        return $gallery;
    }

    public function addTourImage(TourRequest $tourRequest, Tour $tour): void
    {
        foreach ($tourRequest->getTourImages() as $tourImageRequest) {
            $image = $this->imageRepository->find($tourImageRequest['id']);
            if (!$image) {
                continue;
            }
            $tourImage = new TourImage();
            $tourImage->setType($tourImageRequest['type']);
            $tourImage->setTour($tour);
            $tourImage->setImage($image);
            $this->tourImageRepository->add($tourImage);
        }
    }

    public function updateTourImage(Tour $tour, TourUpdateRequest $tourUpdateRequest): Tour
    {
        foreach ($tourUpdateRequest->getTourImages() as $tourImageRequest) {
            if (!$tourImageRequest['delete'] && !$tourImageRequest['type']) {
                continue;
            }
            if ($tourImageRequest['delete'] === true) {
                $this->deleteTourImage($tourImageRequest);

                continue;
            }
            if ($tourImageRequest['type'] === "COVER" && $tourImageRequest['idTourImage']) {
                $this->addTourImageTypeCover($tourImageRequest);

                continue;
            }
            $this->addTourImageTypeGallery($tour, $tourImageRequest);
        }

        return $tour;
    }

    private function deleteTourImage(array $tourImageRequest): void
    {
        $tourImageDelete = $this->tourImageRepository->find($tourImageRequest['idTourImage']);
        if (!$tourImageDelete) {
            return;
        }
        $tourImageDelete->setDeletedAt(new \DateTimeImmutable());
        $this->tourImageRepository->add($tourImageDelete);
    }

    private function addTourImageTypeGallery(Tour $tour, array $tourImageRequest): void
    {
        $image = $this->imageRepository->find($tourImageRequest['id']);
        if (!$image) {
            return;
        }
        $tourImage = new TourImage();
        $tourImage->setType($tourImageRequest['type']);
        $tourImage->setTour($tour);
        $tourImage->setImage($image);
        $tourImage->setUpdatedAt(new \DateTimeImmutable());
        $this->tourImageRepository->add($tourImage);
    }

    private function addTourImageTypeCover(array $tourImageRequest): void
    {
        $tourImage = $this->tourImageRepository->find($tourImageRequest['idTourImage']);
        if (!$tourImage) {
            return;
        }
        $image = $this->imageRepository->find($tourImageRequest['id']);
        if (!$image) {
            return;
        }
        $tourImage->setImage($image);
        $tourImage->setUpdatedAt(new \DateTimeImmutable());
        $this->tourImageRepository->add($tourImage);
    }
}
