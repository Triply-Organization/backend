<?php

namespace App\Transformer;

use App\Entity\Ticket;
use App\Entity\TicketType;
use App\Service\PriceListService;

class TicketTransformer
{
    private PriceListService $priceListService;

    public function __construct(PriceListService $priceListService)
    {
        $this->priceListService = $priceListService;
    }

    public function toArray(TicketType $ticketType): array
    {
        return [
            'id'  => $ticketType->getId(),
            'type' => $ticketType->getName(),
            'price' => $this->priceListService->getTicketPrice($ticketType)
        ];
    }
}
