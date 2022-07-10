<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Review;
use App\Entity\ReviewDetail;
use App\Entity\Tour;
use App\Entity\TypeReview;
use App\Repository\ReviewDetailRepository;
use App\Repository\ReviewRepository;
use App\Repository\TypeReviewRepository;
use App\Request\GetReviewAllRequest;
use App\Request\ReviewRequest;
use App\Transformer\ReviewTransformer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Date;

use function Composer\Autoload\includeFile;

class ReviewService
{
    private Security $security;
    private OrderService $orderService;
    private ReviewRepository $reviewRepository;
    private TypeReviewRepository $typeReviewRepository;
    private ReviewDetailRepository $reviewDetailRepository;
    private ReviewDetailService $reviewDetailService;
    private ReviewTransformer $reviewTransformer;
    private ParameterBagInterface $params;

    public function __construct(
        Security $security,
        OrderService $orderService,
        ReviewRepository $reviewRepository,
        TypeReviewRepository $typeReviewRepository,
        ReviewDetailRepository $reviewDetailRepository,
        ReviewDetailService $reviewDetailService,
        ReviewTransformer $reviewTransformer,
        ParameterBagInterface $params
    ) {
        $this->security = $security;
        $this->orderService = $orderService;
        $this->reviewRepository = $reviewRepository;
        $this->typeReviewRepository = $typeReviewRepository;
        $this->reviewDetailRepository = $reviewDetailRepository;
        $this->reviewDetailService = $reviewDetailService;
        $this->reviewTransformer = $reviewTransformer;
        $this->params = $params;
    }

    public function handleRating(Tour $tour): array
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

    public function getRatingDetail(Tour $tour): array
    {
        $results = [];
        $location = $rooms = $services = $price = $amenities = 0;
        $ratings = $this->handleRating($tour);
        $count = 0;
        foreach ($ratings as $rating) {
            if (count($rating) > 0) {
                $location = $location + (isset($rating['location']) ? $rating['location'] : 0);
                $rooms = $rooms + (isset($rating['rooms']) ? $rating['rooms'] : 0);
                $services = $services + (isset($rating['services']) ? $rating['services'] : 0);
                $price = $price + (isset($rating['price']) ? $rating['price'] : 0);
                $amenities = $amenities + (isset($rating['amenities']) ? $rating['amenities'] : 0);
                $count = $count + 1;
            }
        }
        if ($count > 0) {
            $results['location'] = $location / $count;
            $results['rooms'] = $rooms / $count;
            $results['price'] = $price / $count;
            $results['services'] = $services / $count;
            $results['amenities'] = $amenities / $count;
            $results['avg'] = ($location + $rooms + $services + $price + $amenities) / (5 * $count);
        }

        return $results;
    }

    public function getRatingOverall(Tour $tour): array
    {
        $results = [];
        $location = $rooms = $services = $price = $amenities = 0;
        $ratings = $this->handleRating($tour);
        $count = 0;
        foreach ($ratings as $rating) {
            if (count($rating) > 0) {
                $location = $location + (isset($rating['location']) ? $rating['location'] : 0);
                $rooms = $rooms + (isset($rating['rooms']) ? $rating['rooms'] : 0);
                $services = $services + (isset($rating['services']) ? $rating['services'] : 0);
                $price = $price + (isset($rating['price']) ? $rating['price'] : 0);
                $amenities = $amenities + (isset($rating['amenities']) ? $rating['amenities'] : 0);
                $count = $count + 1;
            }
        }
        if ($count > 0) {
            $results['avg'] = ($location + $rooms + $services + $price) / (5 * $count);
        }

        return $results;
    }

    public function ratingForTour(Tour $tour): float|int
    {
        $avg = 0;
        $location = $rooms = $services = $price = $amenities = 0;
        $ratings = $this->handleRating($tour);
        $count = 0;
        foreach ($ratings as $rating) {
            if (count($rating) > 0) {
                $location = $location + $rating['location'];
                $rooms = $rooms + $rating['rooms'];
                $services = $services + $rating['services'];
                $price = $price + $rating['price'];
                $amenities = $amenities + $rating['amenities'];
                $count = $count + 1;
            }
        }
        if ($count > 0) {
            $avg = ($location + $rooms + $services + $price) / (5 * $count);
        }

        return $avg;
    }

    public function addReview(ReviewRequest $reviewRequest, Order $order): bool|Review
    {
        $currentUser = $this->security->getUser();
        $orderCommented = $this->reviewRepository->findBy(['orderDetail' => $order->getId()]);
        if ($currentUser->getId() !== $order->getUser()->getId() && $currentUser->getRoles()['role'] === 'ROLE_USER') {
            return false;
        }
        if ($orderCommented) {
            return false;
        }
        $firstTicket = $this->orderService->findOneTicketOfOrder($order);
        $review = new Review();
        $review->setUser($currentUser)
            ->setTour($firstTicket->getPriceList()->getSchedule()->getTour())
            ->setOrderDetail($order)
            ->setComment($reviewRequest->getComment());
        $this->reviewRepository->add($review, true);
        $addReviewDetail = $this->addRate($reviewRequest, $review);
        if ($addReviewDetail === false) {
            return false;
        }

        return $review;
    }

    public function deleteReview(Review $review): bool
    {
        $currentUser = $this->security->getUser();
        if ($currentUser->getId() === $review->getUser()->getId() || $currentUser->getRoles() == ["ROLE_ADMIN"]) {
            foreach ($review->getReviewDetails() as $reviewDetail) {
                $reviewDetail->setDeletedAt(new \DateTimeImmutable());
                $this->reviewDetailRepository->add($reviewDetail, true);
            }
            $review->setDeletedAt(new \DateTimeImmutable());
            $this->reviewRepository->add($review, true);

            return true;
        }
        return false;
    }

    public function adminGetAllReviews(GetReviewAllRequest $getReviewAllRequest): array
    {
        $result = [];
        $data = $this->reviewRepository->getAllReviewAdmin($getReviewAllRequest);
        $reviews = $data['reviews'];
        foreach ($reviews as $key => $review) {
            $result ['reviews'][$key] = $this->reviewTransformer->toArrayOfAdmin($review);
        }
        $result['totalPages'] = $data['totalPages'];
        $result['page'] = $data['page'];
        $result['totalReviews'] = $data['totalReviews'];

        return $result;
    }

    public function getAllReviews(Tour $tour): array
    {
        $reviews = $this->reviewRepository->findBy(['tour' => $tour]);
        $results = [];
        foreach ($reviews as $key => $review) {
            if ($review->getDeletedAt() === null) {
                $reviewDetails = $this->reviewDetailRepository->findBy(['review' => $review]);
                $typeRatings = $this->reviewDetailService->getTypeRating($reviewDetails);
                $results[$key]['id'] = $review->getId();
                $results[$key]['name'] = $review->getUser()->getEmail();
                $results[$key]['createdAt'] = $review->getCreatedAt()->format('Y-m-d');
                $results[$key]['tourName'] = $review->getTour()->getTitle();
                $results[$key]['rating'] = $this->handleRatingUser($typeRatings);
                $results[$key]['avatar'] = $review->getUser()->getAvatar()
                    ? $this->params->get('s3url') . $review->getUser()->getAvatar()->getPath()
                    : null;
                $results[$key]['comment'] = $review->getComment();
            }
        }

        return $results;
    }

    public function handleRatingUser(array $typeRatings): array
    {
        $results = [];
        if (count($typeRatings) > 0) {
            foreach ($typeRatings as $key => $typeRating) {
                $results[$key] = $typeRating;
            }
        }

        return $results;
    }

    private function addRate(ReviewRequest $reviewRequest, Review $review): bool
    {
        $bool = true;
        foreach ($reviewRequest->getRate() as $rate) {
            $reviewDetail = new ReviewDetail();
            $typeResult = $this->typeReviewRepository->find($rate['id']);
            if (!$typeResult) {
                $bool = false;
            }
            $reviewDetail->setRate($rate['rate'])
                ->setType($typeResult)
                ->setReview($review);
            $this->reviewDetailRepository->add($reviewDetail, true);
        }

        return $bool;
    }
}
