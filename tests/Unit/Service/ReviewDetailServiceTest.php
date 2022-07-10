<?php

namespace App\Tests\Unit\Service;

use App\Entity\ReviewDetail;
use App\Entity\TypeReview;
use App\Service\ReviewDetailService;
use PHPUnit\Framework\TestCase;

class ReviewDetailServiceTest extends  TestCase
{
    public function testGetTypeRating()
    {
        $typeReview = new TypeReview();
        $reviewDetail = new ReviewDetail();
        $reviewDetail->setRate(2);
        $reviewDetail->setType($typeReview);
        $data = [$reviewDetail];
        $reviewDetailService = new ReviewDetailService();
        $result = $reviewDetailService->getTypeRating($data);
        $this->assertIsArray($result);
    }
}
