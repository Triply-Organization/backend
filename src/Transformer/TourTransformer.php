<?php

namespace App\Transformer;

use App\Entity\Tour;

class TourTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'price'];
    private UserJsonTransformer $userJsonTransformer;

    public function __construct(UserJsonTransformer $userJsonTransformer)
    {
        $this->userJsonTransformer = $userJsonTransformer;
    }

    public function toArray(Tour $tour): array
    {
        $tourData = $this->transform($tour, static::PARAMS);
        $tourData['createUser'] = $this->userJsonTransformer->jsonParse($tour->getCreatedUser());
        return $tourData;
    }
}
