<?php

namespace App\Tests\Unit\Service;

use App\Entity\Review;
use App\Entity\Tour;
use App\Repository\ReviewDetailRepository;
use App\Repository\ReviewRepository;
use App\Repository\TypeReviewRepository;
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

        $reviewService = new ReviewService($this->securityMock, $this->orderServiceMock,
            $this->reviewRepositoryMock, $this->typeReviewRepositoryMock,
            $this->reviewDetailRepositoryMock, $this->reviewDetailServiceMock,
            $this->reviewTransformerMock, $this->params);
        $results = $reviewService->handleRating($tour);

        $this->assertIsArray($results);
    }
}
