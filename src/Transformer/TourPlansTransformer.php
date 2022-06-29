<?php

namespace App\Transformer;

use App\Entity\TourPlan;

class TourPlansTransformer
{

    public function toArray(TourPlan $tourPlan): array
    {
        return [
            'day' => $tourPlan->getDay(),
            'title' => $tourPlan->getTitle(),
            'description' => $tourPlan->getDescription()
        ];
    }

}
