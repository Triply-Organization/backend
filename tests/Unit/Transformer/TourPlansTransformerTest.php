<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Destination;
use App\Entity\TourPlan;
use App\Transformer\TourPlansTransformer;
use PHPUnit\Framework\TestCase;

class TourPlansTransformerTest extends TestCase
{
    public function testToArray()
    {
        $destination = new Destination();
        $destination->setName('Can Tho');
        $tourPlan = new TourPlan();
        $tourPlan->setDestination($destination);
        $tourPlan->setTitle('Travel Can Tho');
        $tourPlan->setDay(1);
        $tourPlan->setDescription('nice tour');
        $tourPlansTransformer = new TourPlansTransformer();
        $result = $tourPlansTransformer->toArray($tourPlan);
        $this->assertEquals([
            'id' => null,
            'destination' => 'Can Tho',
            'day' => 1,
            'title' => 'Travel Can Tho',
            'description' => 'nice tour'
        ], $result);
    }
}
