<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Order;
use App\Entity\Review;
use App\Entity\ReviewDetail;
use App\Entity\Tour;
use App\Entity\User;
use PHPUnit\Framework\TestCase;


class ReviewTest extends TestCase
{
    public function testReviewCreate(): void
    {
        $review = new Review();
        $this->assertEquals(Review::class, get_class($review));
    }

    public function testReviewProperties(): void
    {
        $review = new Review();
        $user = new User();
        $reviewDetail = new ReviewDetail();
        $tour = new Tour();
        $orderDetail = new Order();
        $review->setCreatedAt(new \DateTimeImmutable());
        $review->setDeletedAt(new \DateTimeImmutable());
        $review->setUser($user);
        $review->setTour($tour);
        $review->setComment('good');
        $review->addReviewDetail($reviewDetail);
        $review->setOrderDetail($orderDetail);
        $this->assertNull($review->getId());
        $this->assertEquals('object', gettype($review->getUser()));
        $this->assertEquals('object', gettype($review->getDeletedAt()));
        $this->assertEquals('object', gettype($review->getCreatedAt()));
        $this->assertEquals('object', gettype($review->getTour()));
        $this->assertEquals('object', gettype($review->getOrderDetail()));
        $this->assertEquals('object', gettype($review->getReviewDetails()));
        $this->assertEquals('string', gettype($review->getComment()));
        $this->assertEquals('good', $review->getComment());
        $review->removeReviewDetail($reviewDetail);
    }
}
