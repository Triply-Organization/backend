<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Destination;
use App\Transformer\DestinationTransformer;
use PHPUnit\Framework\TestCase;

class DestinationTransformerTest extends TestCase
{

    public function testTransform(): void
    {
        $destination = new Destination();
        $destination->setName('Can Tho');
        $destinationTransformer = new DestinationTransformer();
        $destinationTransformer = $destinationTransformer->toArray($destination);
        $expectedArray = [
            "id" => null,
            "name" => "Can Tho",
        ];
        $this->assertEquals($expectedArray, $destinationTransformer);

    }
}
