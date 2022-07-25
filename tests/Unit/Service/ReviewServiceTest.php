<?php

namespace App\Tests\Unit\Service;

use App\Entity\Review;
use App\Entity\ReviewDetail;
use App\Entity\Tour;
use App\Entity\TypeReview;
use App\Entity\User;
use App\Repository\ReviewDetailRepository;
use App\Repository\ReviewRepository;
use App\Repository\TypeReviewRepository;
use App\Request\GetReviewAllRequest;
use App\Request\ReviewRequest;
use App\Service\OrderService;
use App\Service\ReviewDetailService;
use App\Service\ReviewService;
use App\Transformer\ReviewTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Security;

class ReviewServiceTest extends TestCase
{
    private $securityMock;
    private $orderServiceMock;
    private $reviewRepositoryMock;
    private $typeReviewRepositoryMock;
    private $reviewDetailRepositoryMock;
    private $reviewDetailServiceMock;
    private $reviewTransformerMock;
    private $params;

    protected function setUp(): void
    {
        $this->securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $this->orderServiceMock = $this->getMockBuilder(OrderService::class)->disableOriginalConstructor()->getMock();
        $this->reviewRepositoryMock = $this->getMockBuilder(ReviewRepository::class)->disableOriginalConstructor()->getMock();
        $this->typeReviewRepositoryMock = $this->getMockBuilder(TypeReviewRepository::class)->disableOriginalConstructor()->getMock();
        $this->reviewDetailRepositoryMock = $this->getMockBuilder(ReviewDetailRepository::class)->disableOriginalConstructor()->getMock();
        $this->reviewDetailServiceMock = $this->getMockBuilder(ReviewDetailService::class)->disableOriginalConstructor()->getMock();
        $this->reviewTransformerMock = $this->getMockBuilder(ReviewTransformer::class)->getMock();
        $this->params = $this->getMockBuilder(ParameterBagInterface::class)->getMock();
    }

    public function testHandleRating()
    {
        $tour = new Tour();
        $review = new Review();
        $this->reviewRepositoryMock->expects($this->once())->method('findBy')->willReturn([$tour]);
        $this->reviewDetailRepositoryMock->expects($this->once())->method('findBy')->willReturn([$review]);

        $reviewService = new ReviewService(
            $this->securityMock,
            $this->orderServiceMock,
            $this->reviewRepositoryMock,
            $this->typeReviewRepositoryMock,
            $this->reviewDetailRepositoryMock,
            $this->reviewDetailServiceMock,
            $this->reviewTransformerMock,
            $this->params
        );
        $results = $reviewService->handleRating($tour);

        $this->assertIsArray($results);
    }

    public function testGetRatingDetail()
    {
        $tour = new Tour();
        $data = [
            '1' => [
                'location' => 5,
                'rooms' => 5,
                'services' => 5,
                'price' => 5,
                'amenities' => 5
            ]
        ];
        $reviewServiceMock = $this->getMockBuilder(ReviewService::class)->onlyMethods(['handleRating'])->disableOriginalConstructor()->getMock();
        $reviewServiceMock->expects($this->once())->method('handleRating')->willReturn($data);
        $getRatingDetail = $reviewServiceMock->getRatingDetail($tour);
        $this->assertIsArray($getRatingDetail);
    }

    public function testGetRatingOverall()
    {
        $tour = new Tour();
        $data = [
            '1' => [
                'location' => 5,
                'rooms' => 5,
                'services' => 5,
                'price' => 5,
                'amenities' => 5
            ]
        ];
        $reviewServiceMock = $this->getMockBuilder(ReviewService::class)->onlyMethods(['handleRating'])->disableOriginalConstructor()->getMock();
        $reviewServiceMock->expects($this->once())->method('handleRating')->willReturn($data);
        $getRatingOverall = $reviewServiceMock->getRatingOverall($tour);
        $this->assertIsArray($getRatingOverall);
    }

    public function testRatingForTour()
    {
        $tour = new Tour();
        $data = [
            '1' => [
                'location' => 5,
                'rooms' => 5,
                'services' => 5,
                'price' => 5,
                'amenities' => 5
            ]
        ];
        $reviewServiceMock = $this->getMockBuilder(ReviewService::class)->onlyMethods(['handleRating'])->disableOriginalConstructor()->getMock();
        $reviewServiceMock->expects($this->once())->method('handleRating')->willReturn($data);
        $ratingForTour = $reviewServiceMock->ratingForTour($tour);
        $this->assertIsNumeric($ratingForTour);
    }

    public function testAdminGetAllReviews()
    {
        $getReviewAllRequest = new GetReviewAllRequest();
        $review = new Review();
        $array['reviews'] = $review;
        $array['totalPages'] = 1;
        $array['page'] = 1;
        $array['totalReviews'] = 1;
        $this->reviewRepositoryMock->expects($this->once())->method('getAllReviewAdmin')->willReturn($array);
        $reviewService = new ReviewService(
            $this->securityMock,
            $this->orderServiceMock,
            $this->reviewRepositoryMock,
            $this->typeReviewRepositoryMock,
            $this->reviewDetailRepositoryMock,
            $this->reviewDetailServiceMock,
            $this->reviewTransformerMock,
            $this->params
        );
        $result = $reviewService->adminGetAllReviews($getReviewAllRequest);

        $this->assertIsArray($result);
    }

    public function testGetAllReviews()
    {
        $tour = new Tour();
        $user = new user();
        $review = new Review();
        $review->setUser($user)->setTour($tour);
        $reviews = [$review];
        $this->reviewRepositoryMock->expects($this->once())->method('findBy')->willReturn($reviews);
        $this->reviewDetailRepositoryMock->expects($this->once())->method('findBy')->willReturn($reviews);
        $reviewService = new ReviewService(
            $this->securityMock,
            $this->orderServiceMock,
            $this->reviewRepositoryMock,
            $this->typeReviewRepositoryMock,
            $this->reviewDetailRepositoryMock,
            $this->reviewDetailServiceMock,
            $this->reviewTransformerMock,
            $this->params
        );
        $results = $reviewService->getAllReviews($tour);

        $this->assertIsArray($results);
    }

    public function testHandleRatingUser()
    {
        $data = [
            'location' => 5,
            'rooms' => 5,
            'services' => 5,
            'price' => 5,
            'amenities' => 5
        ];
        $reviewService = new ReviewService(
            $this->securityMock,
            $this->orderServiceMock,
            $this->reviewRepositoryMock,
            $this->typeReviewRepositoryMock,
            $this->reviewDetailRepositoryMock,
            $this->reviewDetailServiceMock,
            $this->reviewTransformerMock,
            $this->params
        );
        $result = $reviewService->handleRatingUser($data);
        $this->assertIsArray($result);
    }

    public function testDeleteReviewWithTrueRequest()
    {
        $userMock = $this->getMockBuilder(User::class)->getMock();
        $userMock->method('getId')->willReturn(1);
        $userMock->setRoles(["ROLE_ADMIN"]);
        $reviewDetail = new ReviewDetail();
        $review = new Review();
        $review->setUser($userMock)->addReviewDetail($reviewDetail);
        $this->securityMock->expects($this->once())->method('getUser')->willReturn($userMock);
        $this->reviewDetailRepositoryMock->expects($this->once())->method('add');
        $this->reviewRepositoryMock->expects($this->once())->method('add');
        $reviewService = new ReviewService(
            $this->securityMock,
            $this->orderServiceMock,
            $this->reviewRepositoryMock,
            $this->typeReviewRepositoryMock,
            $this->reviewDetailRepositoryMock,
            $this->reviewDetailServiceMock,
            $this->reviewTransformerMock,
            $this->params
        );
        $result = $reviewService->deleteReview($review);
        $this->assertTrue($result);
    }

    public function testAddRate()
    {
        $review = new Review();
        $rate['id'] = 1;
        $rate['rate'] = 5;
        $reviewRequest = new ReviewRequest();
        $reviewRequest->setRate([$rate]);
        $typeReview = new TypeReview();
        $this->typeReviewRepositoryMock->expects($this->once())->method('find')->willReturn($typeReview);
        $this->reviewDetailRepositoryMock->expects($this->once())->method('add');
        $reviewService = new ReviewService(
            $this->securityMock,
            $this->orderServiceMock,
            $this->reviewRepositoryMock,
            $this->typeReviewRepositoryMock,
            $this->reviewDetailRepositoryMock,
            $this->reviewDetailServiceMock,
            $this->reviewTransformerMock,
            $this->params
        );
        $result = $reviewService->addRate($reviewRequest, $review);
        $this->assertTrue($result);
    }
}
