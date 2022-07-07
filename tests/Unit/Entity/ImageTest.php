<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testImageCreate(): void
    {
        $image = new Image();
        $this->assertEquals(Image::class, get_class($image));
    }

    public function testImageCheckProperties(): void
    {
        $image = new Image();
        $image->setPath('img/tourThaiLand.jpg');
        $image->setCreatedAt(new \DateTimeImmutable());
        $image->setUpdatedAt(new \DateTimeImmutable());
        $image->setDeletedAt(new \DateTimeImmutable());

        $this->assertNull($image->getId());
        $this->assertEquals('string', gettype($image->getPath()));
        $this->assertEquals('img/tourThaiLand.jpg', $image->getPath());
        $this->assertEquals('object', gettype($image->getCreatedAt()));
        $this->assertEquals('object', gettype($image->getUpdatedAt()));
        $this->assertEquals('object', gettype($image->getDeletedAt()));
    }
}
