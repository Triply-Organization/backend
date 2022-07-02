<?php

namespace App\Service;

use App\Entity\TicketType;
use App\Repository\PriceListRepository;
use App\Entity\Schedule;
use App\Transformer\PriceListTransformer;

class PriceListService
{
    private PriceListRepository $priceListRepository;
    private PriceListTransformer $priceListTransformer;

    public function __construct(PriceListRepository $priceListRepository, PriceListTransformer $priceListTransformer)
    {
        $this->priceListRepository = $priceListRepository;
        $this->priceListTransformer = $priceListTransformer;
    }

    public function getTicketType($priceLists)
    {
        $ticketType = [];
        foreach ($priceLists as $priceList) {
            $ticketType[] = $this->priceListTransformer->toArray($priceList);
        }
        return $ticketType;
    }

    public function getTicketPrice(TicketType $ticketType): ?float
    {
        $ticketPrice = $this->priceListRepository->find($ticketType);
        return $ticketPrice->getPrice();
    }
}
