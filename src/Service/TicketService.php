<?php

namespace App\Service;

use App\Repository\PriceListRepository;
use App\Repository\TicketRepository;
use App\Repository\TicketTypeRepository;
use App\Transformer\TicketTransformer;

class TicketService
{
    private TicketTransformer $ticketTransformer;
    private TicketTypeRepository $ticketTypeRepository;

    public function __construct(TicketTransformer $ticketTransformer, TicketTypeRepository $ticketTypeRepository)
    {
        $this->ticketTransformer = $ticketTransformer;
        $this->ticketTypeRepository = $ticketTypeRepository;
    }

    public function getTicket(): array
    {
        $ticketTypes = $this->ticketTypeRepository->findAll();
        $result = [];
        foreach ($ticketTypes as $ticketType) {
            $result = $this->ticketTransformer->toArray($ticketType);
        }
        return $result;
    }
}
