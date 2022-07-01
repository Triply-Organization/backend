<?php

namespace App\Service;

use App\Repository\TicketRepository;
use App\Transformer\TicketTransformer;

class TicketService
{
    private TicketTransformer $ticketTransformer;
    private TicketRepository $ticketRepository;

    public function __construct(TicketTransformer $ticketTransformer, TicketRepository $ticketRepository)
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
