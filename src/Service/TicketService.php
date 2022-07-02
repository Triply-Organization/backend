<?php

namespace App\Service;

use App\Repository\PriceListRepository;
use App\Transformer\TicketTransformer;

class TicketService
{
    private TicketTransformer $ticketTransformer;
    private PriceListRepository $ticketRepository;

    public function __construct(TicketTransformer $ticketTransformer, PriceListRepository $ticketRepository)
    {
        $this->ticketTransformer = $ticketTransformer;
        $this->ticketRepository = $ticketRepository;
    }

    public function getTicket(): array
    {

        $tickets = $this->ticketRepository->findAll();
        $ticketList = [];
        foreach ($tickets as $ticket) {
            $ticketList[] = $this->ticketTransformer->toArray($ticket);
        }

        return $ticketList;
    }
}
