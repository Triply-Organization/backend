<?php

namespace App\Tests\Unit\Service;

use App\Repository\TicketTypeRepository;
use App\Service\TicketTypeService;
use App\Transformer\TicketTransformer;
use PHPUnit\Framework\TestCase;

class TicketTypeServiceTest extends TestCase
{
    public function testGetTicketType()
    {
        $ticketTransformerMock = $this->getMockBuilder(TicketTransformer::class)->disableOriginalConstructor()->getMock();
        $ticketTransformerMock->expects($this->once())->method('toArray')->willReturn(array());
        $ticketTypeRepositoryMock = $this->getMockBuilder(TicketTypeRepository::class)->disableOriginalConstructor()->getMock();

        $ticketTypeService = new TicketTypeService($ticketTransformerMock, $ticketTypeRepositoryMock);
        $result = $ticketTypeService->getTicket();

        $this->assertEquals(array(), $result);
    }
}
