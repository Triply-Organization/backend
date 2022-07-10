<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Service;
use App\Transformer\ServiceTransformer;
use PHPUnit\Framework\TestCase;

class ServiceTransformerTest extends TestCase
{
    public function testToArray(): void
    {
        $service = new Service();
        $service->setName('Go to pool');
        $serviceTransformer = new ServiceTransformer();
        $result = $serviceTransformer->toArray($service);
        $this->assertEquals([
            'id' => null,
            'name' => 'Go to pool'
        ], $result);
    }
}
