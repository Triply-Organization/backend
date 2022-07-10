<?php

namespace App\Transformer;

use App\Entity\Review;
use App\Entity\Tour;
use App\Service\FacilityService;
use App\Service\RelatedTourService;
use App\Service\ScheduleService;
use App\Service\TicketService;
use App\Service\TourImageService;
use App\Service\TourPlanService;
use App\Service\TourService;
use App\Service\ReviewService;

class TourDetailTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'price'];
    private FacilityService $facilityService;
    private TourImageService $tourImageService;
    private TourPlanService $tourPlanService;
    private ScheduleService $scheduleService;
    private RelatedTourService $relatedTourService;
    private ReviewService $reviewService;


    public function __construct(
        FacilityService $facilityService,
        TourImageService $tourImageService,
        TourPlanService $tourPlanService,
        ScheduleService $scheduleService,
        RelatedTourService $relatedTourService,
        ReviewService $reviewService,
    ) {
        $this->facilityService = $facilityService;
        $this->tourImageService = $tourImageService;
        $this->tourPlanService = $tourPlanService;
        $this->scheduleService = $scheduleService;
        $this->relatedTourService = $relatedTourService;
        $this->reviewService = $reviewService;
    }

    public function toArray(Tour $tour): array
    {
        $result = $this->transform($tour, static::PARAMS);
        $result['schedule'] = $this->scheduleService->getPrice($tour->getSchedules()->toArray());
        $result['createdUser'] = $tour->getCreatedUser()->getEmail();
        $result['tourImages'] = $this->tourImageService->getGallery($tour);
        $result['services'] = $this->facilityService->getService($tour->getTourServices());
        $result['relatedTour'] = $this->relatedTourService->getRelatedTour(
            $tour->getTourPlans()[0]->getDestination()->getName(),
            $tour->getId()
        );
        $result['tourPlans'] = $this->tourPlanService->getTourPlan($tour->getTourPlans());
        $result['rating'] = $this->reviewService->getRatingDetail($tour);
        $result['totalReview'] = count($tour->getReviews());
        $result['reviews'] = $this->reviewService->getAllReviews($tour);

        return $result;
    }
}
