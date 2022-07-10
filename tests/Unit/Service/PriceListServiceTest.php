<?php

namespace App\Tests\Unit\Service;

use App\Entity\PriceList;
use App\Entity\TicketType;
use App\Repository\PriceListRepository;
use App\Repository\TicketTypeRepository;
use App\Service\PriceListService;
use App\Transformer\PriceListTransformer;
use PHPUnit\Framework\TestCase;

class PriceListServiceTest extends TestCase
{
    private $priceListRepositoryMock;
    private $priceListTransformerMock;
    private $ticketTypeRepositoryMock;

    public function setUp(): void
    {
        $this->priceListRepositoryMock = $this->getMockBuilder(PriceListRepository::class)->disableOriginalConstructor()->getMock();
        $this->priceListTransformerMock = $this->getMockBuilder(PriceListTransformer::class)->getMock();
        $this->ticketTypeRepositoryMock = $this->getMockBuilder(TicketTypeRepository::class)->disableOriginalConstructor()->getMock();

    }

    public function testGetTicketType()
    {
        $priceList = new PriceList();
        $priceLists = [$priceList];
        $priceListService = new PriceListService($this->priceListRepositoryMock, $this->priceListTransformerMock, $this->ticketTypeRepositoryMock);
        $result = $priceListService->getTicketType($priceLists);
        $this->assertIsArray($result);
    }

    public function testGetTicketPrice()
    {
        $ticketType = new TicketType();
        $ticketPrice = new PriceList();
        $ticketPrice->setPrice(5);
        $priceListService = new PriceListService($this->priceListRepositoryMock, $this->priceListTransformerMock, $this->ticketTypeRepositoryMock);
        $this->priceListRepositoryMock->expects($this->once())->method('find')->willReturn($ticketPrice);
        $result = $priceListService->getTicketPrice($ticketType);
        $this->assertIsFloat($result);
    }
}
