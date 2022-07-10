<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Tax;
use App\Transformer\TaxTransformer;
use PHPUnit\Framework\TestCase;

class TaxTransformerTest extends TestCase
{
    public function testToArray(): void
    {
        $tax = new Tax();
        $tax->setCurrency('USD');
        $tax->setPercent(10);
        $taxTransformer = new TaxTransformer();
        $result = $taxTransformer->fromArray($tax);
        $this->assertEquals([
            'id' => null,
            'currency' => 'USD',
            'percent' => 10
        ], $result);
    }
}
