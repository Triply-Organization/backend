<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Image;
use App\Entity\Tour;
use App\Entity\TourImage;
use PHPUnit\Framework\TestCase;

class TourImageTest extends TestCase
{
    public function testTourImageCreate()
    {
        $tourImage = new TourImage();
        $this->assertEquals(TourImage::class, get_class($tourImage));
    }

    public function testTourImageCheckProperties()
    {
        $tourImage = new TourImage();
        $tour = new Tour();
        $image = new Image();

        $tourImage->setImage($image)->setType('TYPE')
            ->setTour($tour)->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setDeletedAt(new \DateTimeImmutable());

        $this->assertEquals(null, $tourImage->getId());

        $this->assertEquals('string', gettype($tourImage->getType()));
        $this->assertEquals('TYPE', $tourImage->getType());

        $this->assertEquals('object', gettype($tourImage->getTour()));
        $this->assertEquals('object', gettype($tourImage->getImage()));

        $this->assertEquals('object', gettype($tourImage->getCreatedAt()));
        $this->assertEquals('object', gettype($tourImage->getUpdatedAt()));
        $this->assertEquals('object', gettype($tourImage->getDeletedAt()));
    }
}
