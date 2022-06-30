<?php

namespace App\Service;

use App\Repository\TourImageRepository;
use App\Transformer\TourImageTransformer;
use App\Entity\Tour;

class TourImageService
{
    private TourImageRepository $tourImageRepository;
    private TourImageTransformer $tourImageTransformer;

    public function __construct(TourImageRepository $tourImageRepository, TourImageTransformer $tourImageTransformer)
    {
        $this->tourImageRepository = $tourImageRepository;
        $this->tourImageTransformer = $tourImageTransformer;
    }

    public function getGallary(Tour $tour): array
    {
        $tourImages = $this->tourImageRepository->findBy(['tour' => $tour]);
        $gallery = [];
        foreach ($tourImages as $tourImage) {
            $gallery[] = $this->tourImageTransformer->toArray($tourImage);
        }

        return $gallery;
    }
}
