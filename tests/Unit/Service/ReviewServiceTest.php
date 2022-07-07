<?php

namespace App\Tests\Unit\Service;

use App\Entity\Tour;
use App\Repository\ReviewDetailRepository;
use App\Repository\ReviewRepository;
use App\Repository\TypeReviewRepository;
use App\Service\OrderService;
use App\Service\ReviewDetailService;
use App\Service\ReviewService;
use App\Transformer\ReviewTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class ReviewServiceTest extends TestCase
{
    public function testHandleRating()
    {
        $tour = new Tour();
        $securityMock = $this->getMockBuilder(Security::class)->disableOriginalConstructor()->getMock();
        $orderServiceMock = $this->getMockBuilder(OrderService::class)->disableOriginalConstructor()->getMock();
        $reviewRepositoryMock = $this->getMockBuilder(ReviewRepository::class)->disableOriginalConstructor()->getMock();
        $reviewRepositoryMock->expects($this->once())->method('findBy')->willReturn(array());
        $typeReviewRepositoryMock = $this->getMockBuilder(TypeReviewRepository::class)->disableOriginalConstructor()->getMock();
        $reviewDetailRepositoryMock = $this->getMockBuilder(ReviewDetailRepository::class)->disableOriginalConstructor()->getMock();
        $reviewDetailRepositoryMock->expects($this->atLeastOnce())->method('findBy')->willReturn(array());
        $reviewDetailServiceMock = $this->getMockBuilder(ReviewDetailService::class)->disableOriginalConstructor()->getMock();
        $reviewTransformerMock = $this->getMockBuilder(ReviewTransformer::class)->disableOriginalConstructor()->getMock();
        $reviewService = new ReviewService($securityMock, $orderServiceMock, $reviewRepositoryMock,
            $typeReviewRepositoryMock, $reviewDetailRepositoryMock, $reviewDetailServiceMock, $reviewTransformerMock);
        $result = $reviewService->handleRating($tour);

        $this->assertIsArray($result);
    }
}
