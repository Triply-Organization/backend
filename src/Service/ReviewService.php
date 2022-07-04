<?php

namespace App\Service;

use App\Entity\Tour;
use App\Repository\ReviewRepository;
use App\Repository\ReviewDetailRepository;
use Symfony\Component\Validator\Constraints\Date;
use function Composer\Autoload\includeFile;

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
            $location = $location + $rating['location'];
            $rooms = $rooms + $rating['rooms'];
            $services = $services + $rating['services'];
            $price = $price + $rating['price'];
            $amenities = $amenities + $rating['amenities'];
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
            $location = $location + $rating['location'];
            $rooms = $rooms + $rating['rooms'];
            $services = $services + $rating['services'];
            $price = $price + $rating['price'];
            $amenities = $amenities + $rating['amenities'];
        }
        if (count($ratings) > 0) {
            $results['avg'] = ($location + $rooms + $services + $price) / (5 * count($ratings));
        }

        return $results;
    }

    public function getAllReviews(Tour $tour)
    {
        $reviews = $this->reviewRepository->findBy(['tour' => $tour]);
        $results = [];
        foreach ($reviews as $key => $review) {
            $reviewDetails = $this->reviewDetailRepository->findBy(['review' => $review]);
            $typeRatings = $this->reviewDetailService->getTypeRating($reviewDetails);
            $results[$key]['id'] = $review->getId();
            $results[$key]['name'] = $review->getUser()->getEmail();
            $results[$key]['createdAt'] = $review->getCreatedAt()->format('Y-m-d');
            $results[$key]['tourName'] = $review->getTour()->getTitle();
            $results[$key]['rating'] = $this->handleRatingUser($typeRatings);
            $results[$key]['avatar'] = $review->getUser()->getAvatar()->getPath();
            $results[$key]['comment'] = $review->getComment();
        }

        return $results;
    }

    public function handleRatingUser(array $typeRatings)
    {
        $avg = 0;
        foreach ($typeRatings as $typeRating) {
            $avg = $avg + $typeRating;
        }
        if (count($typeRatings) > 0) {
            return $avg / count($typeRatings);
        }

        return '' ;
    }
}
