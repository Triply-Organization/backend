<?php

namespace App\Service;

use App\Entity\Tour;
use App\Repository\TourRepository;
use App\Transformer\ServiceTransformer;
use App\Transformer\TourServicesTransformer;
use App\Repository\ServiceRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FacilityService
{
    private ServiceRepository $serviceRepository;
    private ServiceTransformer $serviceTransformer;
    private TourServicesTransformer $tourServicesTransformer;
    private TourRepository $tourRepository;
    private ReviewService $reviewService;
    private ParameterBagInterface $params;

    public function __construct(
        ServiceTransformer      $serviceTransformer,
        ServiceRepository       $serviceRepository,
        TourServicesTransformer $tourServicesTransformer,
        TourRepository          $tourRepository,
        ReviewService           $reviewService,
        ParameterBagInterface $params,
    )
    {
        $this->serviceTransformer = $serviceTransformer;
        $this->serviceRepository = $serviceRepository;
        $this->tourServicesTransformer = $tourServicesTransformer;
        $this->tourRepository = $tourRepository;
        $this->reviewService = $reviewService;
        $this->params = $params;
    }

    public function getPopularTour()
    {
        $tours = $this->tourRepository->findAll();
        $ratings = ['rating'];
        $tourArray = [];
        $i = 0;
        foreach ($tours as $tour) {
            $ratings['rating'][$tour->getId()] = $this->reviewService->ratingForTour($tour);
        }
        arsort($ratings['rating']);

        foreach ($ratings['rating'] as $key => $rating) {
            if($i < 6) {
                $tourArray[$i] = $this->tourRepository->find($key);
                $i++;
            }
        }
        foreach ($tourArray as $key => $tour) {
            $result[$key]['id'] = $tour->getId();
            $result[$key]['title'] = $tour->getTitle();
            $result[$key]['image'] = $this->getCoverImage($tour);
            $result[$key]['rate'] = $this->reviewService->ratingForTour($tour);
        }
       return $result;
    }


    public function getService($tourServices): array
    {
        $tourServiceList = [];
        foreach ($tourServices as $tourService) {
            $tourServiceList[] = $this->tourServicesTransformer->toArray($tourService);
        }

        return $tourServiceList;
    }

    public function getAllService(): array
    {
        $services = $this->serviceRepository->findAll();
        foreach ($services as $service) {
            $result[] = $this->serviceTransformer->toArray($service);
        }

        return $result;
    }

    public function getCoverImage(Tour $tour): string
    {
        $images = $tour->getTourImages();
        foreach ($images as $image) {
            if ($image->getType() === 'cover') {
                $result = $this->params->get('s3url') . $image->getImage()->getPath();
            }
        }

        return $result;
    }
}
