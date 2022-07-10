<?php

namespace App\Tests\Unit\Service;

use App\Entity\Image;
use App\Manager\UploadImageS3Manager;
use App\Repository\ImageRepository;
use App\Service\ImageService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageServiceTest extends TestCase
{
    public function testAddImage()
    {
        $file = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();
        $imageRepositoryMock = $this->getMockBuilder(ImageRepository::class)->disableOriginalConstructor()->getMock();
        $imageS3ManagerMock = $this->getMockBuilder(UploadImageS3Manager::class)->disableOriginalConstructor()->getMock();
        $imageS3ManagerMock->expects($this->once())->method('upload')->willReturn('string');
        $imageService = new ImageService($imageRepositoryMock, $imageS3ManagerMock);
        $result = $imageService->addImage($file);

        $this->assertInstanceOf(Image::class, $result);
    }
}
