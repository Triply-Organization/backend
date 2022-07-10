<?php

namespace App\Service;

use App\Entity\Destination;
use App\Repository\DestinationRepository;
use App\Transformer\DestinationTransformer;

class DestinationService
{
    private DestinationRepository $destinationRepository;
    private DestinationTransformer $destinationTransformer;

    public function __construct(
        DestinationRepository $destinationRepository,
        DestinationTransformer $destinationTransformer
    ) {
        $this->destinationRepository = $destinationRepository;
        $this->destinationTransformer = $destinationTransformer;
    }

    public function getAllDestination(): array
    {
        return $this->getDestination($this->destinationRepository->findAll());
    }

    public function getDestination(array $destinations): array
    {
        $results = [];
        foreach ($destinations as $destination) {
            $results [] = $this->destinationTransformer->toArray($destination);
        }

        return $results;
    }
}
