<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\TourImage;
use App\Transformer\TourImageTransformer;
use PHPUnit\Framework\TestCase;
use App\Entity\Image;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TourImageTransformerTest extends TestCase
{

    public function testToArray()
    {
        $image = new Image();
        $image->setPath('img/tour.jpg');
        $paramsMock = $this->getMockBuilder(ParameterBagInterface::class)->disableOriginalConstructor()->getMock();
        $paramsMock->expects($this->once())->method('get')->willReturn('https://khajackie2206.s3.ap-southeast-1.amazonaws.com/');
        $tourImage = new TourImage();
        $tourImage->setType('cover');
        $tourImage->setImage($image);
        $tourImageTransformer = new TourImageTransformer($paramsMock);
        $result = $tourImageTransformer->toArray($tourImage);
        $this->assertEquals([
            'id' => null,
            'path' => 'https://khajackie2206.s3.ap-southeast-1.amazonaws.com/img/tour.jpg',
            'type' => 'cover'
        ], $result);
    }
}
