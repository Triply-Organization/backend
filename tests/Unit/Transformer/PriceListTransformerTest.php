<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\PriceList;
use App\Entity\TicketType;
use App\Transformer\PriceListTransformer;
use PHPUnit\Framework\TestCase;

class PriceListTransformerTest extends TestCase
{
    public function testToArray()
    {
        $priceList = new PriceList();
        $ticketType = new TicketType();
        $ticketType->setName('adult');
        $priceList->setType($ticketType);
        $priceList->setPrice(500);
        $priceListTransformer = new PriceListTransformer();
        $result = $priceListTransformer->toArray($priceList);
        $this->assertEquals([
            'id' => null,
            'type' => 'adult',
            'price' => 500
        ], $result);
    }
}
