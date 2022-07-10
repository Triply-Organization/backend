<?php

namespace App\Transformer;

use App\Entity\Tour;
use App\Service\ReviewService;
use App\Service\ScheduleService;
use App\Service\TourPlanService;
use App\Service\TourService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TourTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'price' , 'status'];
    private const ADMIN_PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'status', 'createdAt'];
    private const CUSTOMER_PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge','createdAt', 'status'];
    private TourService $tourService;
    private ScheduleService $scheduleService;
    private ParameterBagInterface $params;
    private TourPlanService $tourPlanService;
    private ReviewService $reviewService;

    public function __construct(
        TourService $tourService,
        ScheduleService $scheduleService,
        ParameterBagInterface $params,
        TourPlanService $tourPlanService,
        ReviewService $reviewService
    ) {
        $this->tourService = $tourService;
        $this->scheduleService = $scheduleService;
        $this->params = $params;
        $this->tourPlanService = $tourPlanService;
        $this->reviewService = $reviewService;
    }

    public function toArray(Tour $tour): array
    {
        $result = $this->transform($tour, static::PARAMS);
        $result['createdUser'] = $tour->getCreatedUser()->getEmail();
        $result['tourImages'] = $this->params->get('s3url') . $this->tourService->getCover($tour);
        $result['schedule'] = $this->scheduleService->getPrice($tour->getSchedules()->toArray());
        $result['destination'] = $this->tourPlanService->getDestination($tour->getTourPlans());
        $result['rating'] = $this->reviewService->getRatingOverall($tour);
        $result['totalReview'] = count($tour->getReviews());

        return $result;
    }

    public function toArrayOfAdmin(Tour $tour): array
    {
        $result = $this->transform($tour, static::ADMIN_PARAMS);
        $result['schedule'] = $this->scheduleService->getPrice($tour->getSchedules()->toArray());
        if(!$tour->getSchedules()->getValues()) {
            $result['schedule'] = null;
        }
        $result['createdUser'] = $tour->getCreatedUser()->getEmail();

        return $result;
    }

    public function toArrayOfCustomer(Tour $tour): array
    {
        $result = $this->transform($tour, static::CUSTOMER_PARAMS);
        $result['schedule'] = count($tour->getSchedules());
        foreach ($tour->getTourPlans() as $key => $destination) {
            $result['destination'][$key] = $destination->getDestination()->getName();
        }

        return $result;
    }
}
