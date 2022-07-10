<?php

namespace App\Tests\Unit\Service;

use App\Entity\TicketType;
use App\Repository\TicketTypeRepository;
use App\Service\TicketTypeService;
use App\Transformer\TicketTransformer;
use PHPUnit\Framework\TestCase;

class TicketTypeServiceTest extends TestCase
{
    public function testGetTicketType()
    {
        $typeTicket = new TicketType();
        $typeTicket->setName('Children');
        $typeTicket->setCreatedAt(new \DateTimeImmutable());
        $ticketTransformerMock = $this->getMockBuilder(TicketTransformer::class)->disableOriginalConstructor()->getMock();
        $ticketTypeRepositoryMock = $this->getMockBuilder(TicketTypeRepository::class)->disableOriginalConstructor()->getMock();
        $ticketTypeRepositoryMock->expects($this->once())->method('findAll')->willReturn(array($typeTicket));
        $ticketTypeService = new TicketTypeService($ticketTransformerMock, $ticketTypeRepositoryMock);
        $result = $ticketTypeService->getTicketType();

        $this->assertIsArray($result);
    }
}
