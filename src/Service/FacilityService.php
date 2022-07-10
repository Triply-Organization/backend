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
    private ParameterBagInterface $params;
    private TourPlanService $tourPlanService;
    private ScheduleService $scheduleService;

    public function __construct(
        ServiceTransformer $serviceTransformer,
        ServiceRepository $serviceRepository,
        TourServicesTransformer $tourServicesTransformer,
        TourRepository $tourRepository,
        ParameterBagInterface $params,
        TourPlanService $tourPlanService,
        ScheduleService $scheduleService
    ) {
        $this->serviceTransformer = $serviceTransformer;
        $this->serviceRepository = $serviceRepository;
        $this->tourServicesTransformer = $tourServicesTransformer;
        $this->tourRepository = $tourRepository;
        $this->params = $params;
        $this-> tourPlanService = $tourPlanService;
        $this->scheduleService = $scheduleService;
    }

    public function getPopularTour()
    {
        $result = [];
        $tours = $this->tourRepository->getPopularTour();

        foreach ($tours as $key => $value) {
            $tour = $this->tourRepository->find($value['id']);
            $result[$key]['id'] = $tour->getId();
            $result[$key]['title'] = $tour->getTitle();
            $result[$key]['image'] = is_null($this->getCoverImage($tour)) ? null : $this->getCoverImage($tour);
            $result[$key]['rate'] = $value['rate'];
            $result[$key]['totalReview'] = count($tour->getReviews());
            $result[$key]['maxPeople'] = $tour->getMaxPeople();
            $result[$key]['destinations'] = $this->tourPlanService->getDestination($tour->getTourPlans());
            $result[$key]['schedule'] = $this->scheduleService->getPrice($tour->getSchedules()->toArray());
            $result[$key]['duration'] = $tour->getDuration();
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
        $result = [];
        $services = $this->serviceRepository->findAll();
        foreach ($services as $service) {
            $result[] = $this->serviceTransformer->toArray($service);
        }

        return $result;
    }

    public function getCoverImage(Tour $tour): string
    {
        $result = '';
        $images = $tour->getTourImages();
        foreach ($images as $image) {
            if ($image->getType() === 'cover') {
                $result = is_null($image->getImage()) ? null : $this->params->get('s3url') . $image->getImage()->getPath();
            }
        }

        return $result;
    }
}
