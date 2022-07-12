<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Destination;
use App\Entity\TourPlan;
use App\Transformer\DestinationTransformer;
use PHPUnit\Framework\TestCase;

class DestinationTransformerTest extends TestCase
{

    public function testToArray(): void
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

    public function testTransform()
    {
        $destination = new Destination();
        $destination->setName('Can Tho');
        $tourPlan = new TourPlan();
        $tourPlan->setDestination($destination);
        $destinationTransformer = new DestinationTransformer();
        $result = $destinationTransformer->listToArray($tourPlan);
        $this->assertEquals([
            'id' => null,
            'destination' => 'Can Tho'
        ], $result);
    }


}
