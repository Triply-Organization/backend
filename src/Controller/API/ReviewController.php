<?php

namespace App\Controller\API;

use App\Entity\Order;
use App\Entity\Review;
use App\Request\GetReviewAllRequest;
use App\Request\ReviewRequest;
use App\Service\ReviewService;
use App\Traits\ResponseTrait;
use App\Transformer\ReviewTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/reviews', name: 'review_')]
class ReviewController extends AbstractController
{
    use ResponseTrait;

    #[Route('/{id<\d+>}', name: 'add', methods: 'POST')]
    #[IsGranted('ROLE_USER')]
    public function create(
        Order $order,
        Request $request,
        ReviewRequest $reviewRequest,
        ValidatorInterface $validator,
        ReviewService $reviewService,
        ReviewTransformer $reviewTransformer,
    ): JsonResponse {
        $requestData = $request->toArray();
        $reviewData = $reviewRequest->fromArray($requestData);
        $errors = $validator->validate($reviewData);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $reviewData = $reviewService->addReview($reviewRequest, $order);
        if ($reviewData === false) {
            return $this->errors(['Something wrong']);
        }
        $result = $reviewTransformer->toArray($reviewData);

        return $this->success($result);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: 'DELETE')]
    #[isGranted('ROLE_USER')]
    public function deleteReview(Review $review, ReviewService $reviewService): JsonResponse
    {
        $result = $reviewService->deleteReview($review);
        if ($result === false) {
            return $this->errors(['Something wrong']);
        }
        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/', name: 'getAllReview', methods: 'GET')]
    #[isGranted('ROLE_ADMIN')]
    public function getAllReview(
        Request $request,
        ReviewService $reviewService,
        GetReviewAllRequest $getReviewAllRequest,
        ValidatorInterface $validator
    ): JsonResponse {
        $query = $request->query->all();
        $tourRequest = $getReviewAllRequest->fromArray($query);
        $errors = $validator->validate($tourRequest);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $result = $reviewService->adminGetAllReviews($getReviewAllRequest);
        return $this->success($result);
    }
}
