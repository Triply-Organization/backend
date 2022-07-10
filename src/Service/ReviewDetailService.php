<?php

namespace App\Service;

class ReviewDetailService
{
    public function getTypeRating(array $reviewDetails): array
    {
        $results = [];
        foreach ($reviewDetails as $reviewDetail) {
            $results[$reviewDetail->getType()->getName()] = $reviewDetail->getRate();
        }

        return $results;
    }
}
