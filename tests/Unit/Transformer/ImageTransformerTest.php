<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Image;
use App\Transformer\ImageTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageTransformerTest extends TestCase
{

    public function testFromArray()
    {
        $image = new Image();
        $image->setPath('tour.jpg');
        $paramsMock = $this->getMockBuilder(ParameterBagInterface::class)->disableOriginalConstructor()->getMock();
        $imageTransformer = new ImageTransformer($paramsMock);
        $result = $imageTransformer->fromArray($image);
        $this->assertEquals([
            'id' => null,
            'path' => 'tour.jpg'
        ], $result);
    }
}
