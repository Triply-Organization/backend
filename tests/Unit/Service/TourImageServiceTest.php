<?php

namespace App\Tests\Unit\Service;

use App\Entity\Image;
use App\Entity\Tour;
use App\Repository\ImageRepository;
use App\Repository\TourImageRepository;
use App\Request\TourRequest;
use App\Service\TourImageService;
use App\Transformer\TourImageTransformer;
use PHPUnit\Framework\TestCase;

class TourImageServiceTest extends TestCase
{
    public function testGetGallery()
    {
        $tour = new Tour();
        $imageRepositoryMock = $this->getMockBuilder(ImageRepository::class)->disableOriginalConstructor()->getMock();
        $tourImageRepositoryMock = $this->getMockBuilder(TourImageRepository::class)->disableOriginalConstructor()->getMock();
        $tourImageTransformerMock = $this->getMockBuilder(TourImageTransformer::class)->disableOriginalConstructor()->getMock();
        $tourImageService = new TourImageService($imageRepositoryMock, $tourImageRepositoryMock, $tourImageTransformerMock);
        $tourImageRepositoryMock->expects($this->once())->method('findBy')->willReturn(array());

        $getGallery = $tourImageService->getGallery($tour);
        $this->assertIsArray($getGallery);
    }

    public function testAddTourImage()
    {
        $image = new Image();
        $tourImageRequest['id'] = 1;
        $tourImageRequest['type'] = 'string';
        $tourRequest = new TourRequest();
        $tourRequest->setTourImages([$tourImageRequest]);
        $tour = new Tour();
        $imageRepositoryMock = $this->getMockBuilder(ImageRepository::class)->disableOriginalConstructor()->getMock();
        $imageRepositoryMock->expects($this->once())->method('find')->willReturn($image);
        $tourImageRepositoryMock = $this->getMockBuilder(TourImageRepository::class)->disableOriginalConstructor()->getMock();
        $tourImageTransformerMock = $this->getMockBuilder(TourImageTransformer::class)->disableOriginalConstructor()->getMock();
        $tourImageRepositoryMock->expects($this->once())->method('add');
        $tourImageService = new TourImageService($imageRepositoryMock, $tourImageRepositoryMock, $tourImageTransformerMock);
        $result = $tourImageService->addTourImage($tourRequest, $tour);
        $this->assertTrue($result);
    }
}
