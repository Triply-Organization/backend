<?php

namespace App\Tests\Unit\Service;

use App\Entity\TicketType;
use App\Repository\TicketTypeRepository;
use App\Service\TicketService;
use App\Transformer\TicketTransformer;
use PHPUnit\Framework\TestCase;

class TicketServiceTest extends TestCase
{
    public function testGetTicket()
    {
        $typeTicket = new TicketType();
        $typeTicket->setName('Children');
        $typeTicket->setCreatedAt(new \DateTimeImmutable());
        $ticketTransformerMock = $this->getMockBuilder(TicketTransformer::class)->disableOriginalConstructor()->getMock();
        $ticketTypeRepositoryMock = $this->getMockBuilder(TicketTypeRepository::class)->disableOriginalConstructor()->getMock();
        $ticketTypeRepositoryMock->expects($this->once())->method('findAll')->willReturn(array($typeTicket));

        $ticketService = new TicketService($ticketTransformerMock, $ticketTypeRepositoryMock);
        $result = $ticketService->getTicket();

        $this->assertIsArray($result);
    }
}