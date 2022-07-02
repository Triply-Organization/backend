<?php

namespace App\Transformer;

use App\Entity\PriceList;

class TicketTransformer
{
    public function toArray(PriceList $ticket): array
    {
        return [
            $ticket->getType()->getName() => $ticket->getPrice(),
        ];
    }
}
