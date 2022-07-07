<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Review;
use App\Entity\ReviewDetail;
use App\Entity\TypeReview;
use PHPUnit\Framework\TestCase;

class ReviewDetailTest extends TestCase
{
    public function testReviewDetailCreate(): void
    {
        $reviewDetail = new ReviewDetail();
        $this->assertEquals(ReviewDetail::class, get_class($reviewDetail));
    }

    public function testReviewDetailProperties(): void
    {
        $typeReview = new TypeReview();
        $review = new Review();
        $reviewDetail = new ReviewDetail();

        $reviewDetail->setCreatedAt(new \DateTimeImmutable());
        $reviewDetail->setDeletedAt(new \DateTimeImmutable());
        $reviewDetail->setType($typeReview);
        $reviewDetail->setReview($review);
        $reviewDetail->setRate(4.2);
        $this->assertNull($reviewDetail->getId());
        $this->assertEquals('object', gettype($reviewDetail->getReview()));
        $this->assertEquals('object', gettype($reviewDetail->getDeletedAt()));
        $this->assertEquals('object', gettype($reviewDetail->getCreatedAt()));
        $this->assertEquals('object', gettype($reviewDetail->getType()));
        $this->assertEquals('double', gettype($reviewDetail->getRate()));
        $this->assertEquals(4.2, $reviewDetail->getRate());

        $review->removeReviewDetail($reviewDetail);
    }
}
