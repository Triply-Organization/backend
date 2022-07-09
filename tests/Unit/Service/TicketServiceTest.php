<?php

namespace App\Tests\Unit\Service;

use App\Repository\TicketTypeRepository;
use App\Service\TicketService;
use App\Transformer\TicketTransformer;
use PHPUnit\Framework\TestCase;

class TicketServiceTest extends TestCase
{
    public function testGetTicket()
    {
        $ticketTransformerMock = $this->getMockBuilder(TicketTransformer::class)->disableOriginalConstructor()->getMock();
        $ticketTransformerMock->expects($this->once())->method('toArray')->willReturn(array());
        $ticketTypeRepositoryMock = $this->getMockBuilder(TicketTypeRepository::class)->disableOriginalConstructor()->getMock();

        $ticketService = new TicketService($ticketTransformerMock, $ticketTypeRepositoryMock);
        $result = $ticketService->getTicket();

        $this->assertEquals(array(), $result);
    }
}