<?php

namespace App\Transformer;

use App\Entity\Review;

class ReviewTransformer extends BaseTransformer
{
    public function toArray(Review $review)
    {
        return [
            'idOrder' => $review->getOrderDetail()->getId(),
            'idReview' => $review->getId()
        ];
    }
}
