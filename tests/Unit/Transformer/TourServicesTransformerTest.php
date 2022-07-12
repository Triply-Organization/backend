<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Service;
use App\Entity\TourService;
use App\Transformer\TourServicesTransformer;
use PHPUnit\Framework\TestCase;

class TourServicesTransformerTest extends TestCase
{
    public function testToArray()
    {
        $service = new Service();
        $service->setName('hotel');
        $tourService = new TourService();
        $tourService->setService($service);
        $tourServiceTransformer = new TourServicesTransformer();
        $result = $tourServiceTransformer->toArray($tourService);
        $this->assertEquals(['id' => null, 'name' => 'hotel'], $result);
    }
}
