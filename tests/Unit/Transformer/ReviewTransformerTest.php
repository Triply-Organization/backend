<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Review;
use App\Entity\ReviewDetail;
use App\Entity\Tour;
use App\Entity\TypeReview;
use App\Entity\User;
use App\Transformer\ReviewTransformer;
use PHPUnit\Framework\TestCase;
use App\Entity\Order;

class ReviewTransformerTest extends TestCase
{
    public function testToArray()
    {
        $review = new Review();
        $orderDetail = new Order();
        $review->setOrderDetail($orderDetail);
        $reviewTransformer = new ReviewTransformer();
        $result = $reviewTransformer->toArray($review);
        $this->assertEquals(['idOrder' => null, 'idReview' => null], $result
        );
    }

    public function testToArrayAdmin()
    {
        $review = new Review();
        $review->setComment('nice');
        $review->setCreatedAt(new \DateTimeImmutable());
        $user = new User();
        $user->setName('user');
        $user->setEmail('user@gmail.com');
        $tour = new Tour();
        $order = new Order();
        $tour->setTitle('Da Nang Trip');
        $reviewDetail = new ReviewDetail();
        $typeReview = new TypeReview();
        $typeReview->setName('location');
        $reviewDetail->setType($typeReview);
        $reviewDetail->setRate(5);
        $typeReview->setName('location');
        $review->addReviewDetail($reviewDetail);
        $review->setUser($user);
        $review->setOrderDetail($order);
        $review->setTour($tour);
        $reviewTransformer = new ReviewTransformer();
        $result = $reviewTransformer->toArrayOfAdmin($review);
        $this->assertIsArray($result);
    }
}
