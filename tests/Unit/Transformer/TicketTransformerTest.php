<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\TicketType;
use App\Service\PriceListService;
use App\Transformer\TicketTransformer;
use PHPUnit\Framework\TestCase;

class TicketTransformerTest extends TestCase
{

    public function testToArray()
    {

        $ticketType = new TicketType();
        $ticketType->setName('adult');
        $priceListService = $this->getMockBuilder(PriceListService::class)->disableOriginalConstructor()->getMock();
        $priceListService->expects($this->once())->method('getTicketPrice')->willReturn(100.0);
        $ticketTransformer = new TicketTransformer($priceListService);
        $result = $ticketTransformer->toArray($ticketType);
        $this->assertEquals([
            'id' => null,
            'type' => 'adult',
            'price' => 100.0
        ], $result);
    }
}
