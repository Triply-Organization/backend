<?php

namespace App\Tests\Unit\Service;

use App\Entity\Destination;
use App\Repository\DestinationRepository;
use App\Service\DestinationService;
use App\Transformer\DestinationTransformer;
use PHPUnit\Framework\TestCase;

class DestinationServiceTest extends TestCase
{
    private $destinationRepositoryMock;
    private $destinationTransformerMock;

    public function setUp(): void
    {
        $this->destinationRepositoryMock = $this->getMockBuilder(DestinationRepository::class)->disableOriginalConstructor()->getMock();
        $this->destinationTransformerMock = $this->getMockBuilder(DestinationTransformer::class)->getMock();
    }

    public function testGetAllDestination()
    {
        $destination = new Destination();
        $destinations = [$destination];
        $this->destinationRepositoryMock->expects($this->once())->method('findAll')->willReturn($destinations);
        $destinationService = new DestinationService($this->destinationRepositoryMock, $this->destinationTransformerMock);
        $result = $destinationService->getAllDestination();
        $this->assertIsArray($result);
    }

    public function testGetDestination()
    {
        $destination = new Destination();
        $destinations = [$destination];
        $destinationService = new DestinationService($this->destinationRepositoryMock, $this->destinationTransformerMock);
        $result = $destinationService->getDestination($destinations);
        $this->assertIsArray($result);
    }
}
