<?php

namespace App\Service;

use App\Transformer\TicketTransformer;

class TicketService
{
    private TicketTransformer $ticketTransformer;

    public function __construct(TicketTransformer $ticketTransformer)
    {
        $this->$ticketTransformer = $ticketTransformer;
    }

    public function getTicket($tickets): array
    {
        $ticketList = [];
        foreach ($tickets as $ticket) {
            $ticketList[] = $this->ticketTransformer->toArray($ticket);
        }

        return $ticketList;
    }
}