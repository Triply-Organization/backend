<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Review;
use App\Entity\ReviewDetail;
use App\Entity\TypeReview;
use App\Repository\ReviewDetailRepository;
use App\Repository\ReviewRepository;
use App\Repository\TypeReviewRepository;
use App\Request\ReviewRequest;
use Symfony\Component\Security\Core\Security;

class ReviewService
{
    private Security $security;
    private OrderService $orderService;
    private ReviewRepository $reviewRepository;
    private TypeReviewRepository $typeReviewRepository;
    private ReviewDetailRepository $reviewDetailRepository;

    public function __construct(
        Security $security,
        OrderService $orderService,
        ReviewRepository $reviewRepository,
        TypeReviewRepository $typeReviewRepository,
        ReviewDetailRepository $reviewDetailRepository
    ) {
        $this->security = $security;
        $this->orderService = $orderService;
        $this->reviewRepository = $reviewRepository;
        $this->typeReviewRepository = $typeReviewRepository;
        $this->reviewDetailRepository = $reviewDetailRepository;
    }


    public function addReview(
        ReviewRequest $reviewRequest,
        Order $order
    ) {
        $currentUser = $this->security->getUser();
        $orderCommented = $this->reviewRepository->findBy(['orderDetail' => $order->getId()]);
        if ($currentUser->getId() !== $order->getUser()->getId() && $currentUser->getRoles()['role'] === 'ROLE_USER') {
            return false;
        }
        if ($orderCommented !== []) {
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

    public function deleteReview(Review $review)
    {
        $currentUser = $this->security->getUser();
        if ($currentUser->getId() !== $review->getUser()->getId() && $currentUser->getRoles()['role'] === 'ROLE_USER') {
            return false;
        }
        foreach ($review->getReviewDetails() as $reviewDetail) {
            $reviewDetail->setDeletedAt(new \DateTimeImmutable());
            $this->reviewDetailRepository->add($reviewDetail, true);
        }
        $review->setDeletedAt(new \DateTimeImmutable());
        $this->reviewRepository->add($review, true);

        return true;
    }

    private function addRate(ReviewRequest $reviewRequest, Review $review)
    {
        $bool = true;
        foreach ($reviewRequest->getRate() as $rate) {
            $reviewDetail = new ReviewDetail();
            $typeResult = $this->typeReviewRepository->find($rate['id']);
            if ($typeResult === null) {
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
