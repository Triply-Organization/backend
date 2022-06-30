<?php

namespace App\Service;

use App\Transformer\TourPlansTransformer;

class TourPlanService
{

    private TourPlansTransformer $tourPlansTransformer;

    public function __construct(TourPlansTransformer $tourPlansTransformer)
    {
        $this->tourPlansTransformer = $tourPlansTransformer;
    }

    public function getTourPlan($plans): array
    {
        $tourPlans = [];
        foreach ($plans as $plan) {
            $tourPlans[] = $this->tourPlansTransformer->toArray($plan);
        }

        return $tourPlans;
    }
}
