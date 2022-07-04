<?php

namespace App\Service;

use App\Entity\Tour;
use App\Repository\ReviewDetailRepository;
use App\Repository\ReviewRepository;

class ReviewService
{
    private ReviewRepository $reviewRepository;
    private ReviewDetailRepository $reviewDetailRepository;
    private ReviewDetailService $reviewDetailService;

    public function __construct(ReviewRepository $reviewRepository, ReviewDetailRepository $reviewDetailRepository, ReviewDetailService $reviewDetailService)
    {
        $this->reviewRepository = $reviewRepository;
        $this->reviewDetailRepository = $reviewDetailRepository;
        $this->reviewDetailService = $reviewDetailService;
    }

    public function handleRating(Tour $tour)
    {
        $reviews = $this->reviewRepository->findBy(['tour' => $tour]);
        $results = [];
        foreach ($reviews as $review) {
            $reviewDetails = $this->reviewDetailRepository->findBy(['review' => $review]);
            $typeRating = $this->reviewDetailService->getTypeRating($reviewDetails);
            $results[] = $typeRating;
        }

        return $results;
    }

    public function getRatingDetail(Tour $tour)
    {
        $results = [];
        $location = $rooms = $services = $price = $amenities = 0;
        $ratings = $this->handleRating($tour);
        foreach ($ratings as $rating) {
            $location += $rating['location'];
            $rooms += $rating['rooms'];
            $services += $rating['services'];
            $price += $rating['price'];
            $amenities += $rating['amenities'];
        }
        if (count($ratings) > 0) {
            $results['location'] = $location / count($ratings);
            $results['rooms'] = $rooms / count($ratings);
            $results['price'] = $price / count($ratings);
            $results['services'] = $services / count($ratings);
            $results['amenities'] = $amenities / count($ratings);
            $results['avg'] = ($location + $rooms + $services + $price) / (5 * count($ratings));
        }
        return $results;
    }

    public function getRatingOverrall(Tour $tour)
    {
        $results = [];
        $location = $rooms = $services = $price = $amenities = 0;
        $ratings = $this->handleRating($tour);
        foreach ($ratings as $rating) {
            $location += $rating['location'];
            $rooms += $rating['rooms'];
            $services = $services + $rating['services'];
            $price = $price + $rating['price'];
            $amenities = $amenities + $rating['amenities'];
        }
        if (count($ratings) > 0) {
            $results['avg'] = ($location + $rooms + $services + $price) / (5 * count($ratings));
        }

        return $results;
    }
}
