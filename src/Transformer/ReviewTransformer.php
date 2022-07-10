<?php

namespace App\Transformer;

use App\Entity\Review;

class ReviewTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'comment', 'createdAt'];

    public function toArray(Review $review): array
    {
        return [
            'idOrder' => $review->getOrderDetail()->getId(),
            'idReview' => $review->getId()
        ];
    }

    public function toArrayOfAdmin(Review $review): array
    {
        $result = $this->transform($review, static::PARAMS);
        $result['user']['id'] = $review->getUser()->getId();
        $result['user']['name'] = $review->getUser()->getName();
        $result['user']['email'] = $review->getUser()->getEmail();
        $result['order'] = $review->getOrderDetail()->getId();
        $result['tour']['id'] = $review->getTour()->getId();
        $result['tour']['name'] = $review->getTour()->getTitle();
        foreach ($review->getReviewDetails() as $key => $reviewDetail) {
            $reviewArray[$key]['type'] = $reviewDetail->getType()->getName();
            $reviewArray[$key]['rate'] = $reviewDetail->getRate();
        }
        $result['rating'] = $reviewArray;

        return $result;
    }
}
