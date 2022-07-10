<?php

namespace App\Tests\Unit\Entity;

use App\Entity\ReviewDetail;
use App\Entity\TypeReview;
use PHPUnit\Framework\TestCase;

class TypeReviewTest extends TestCase
{
    public function testTypeReviewCreate()
    {
        $typeReview = new TypeReview();
        $this->assertEquals(TypeReview::class, get_class($typeReview));
    }

    public function testTypeReviewCheckProperties()
    {
        $typeReview = new TypeReview();
        $reviewDetail = new ReviewDetail();

        $typeReview->setName('TYPEREVIEW')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setDeletedAt(new \DateTimeImmutable());
        $typeReview->addReviewDetail($reviewDetail);
        $typeReview->getReviewDetails();
        $typeReview->removeReviewDetail($reviewDetail);

        $this->assertEquals(null, $typeReview->getId());
        $this->assertEquals('string', gettype($typeReview->getName()));
        $this->assertEquals('TYPEREVIEW', $typeReview->getName());

        $this->assertEquals('object', gettype($typeReview->getCreatedAt()));
        $this->assertEquals('object', gettype($typeReview->getUpdatedAt()));
        $this->assertEquals('object', gettype($typeReview->getDeletedAt()));
    }
}
