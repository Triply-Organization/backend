<?php

namespace App\Transformer;

use App\Entity\Tax;

class TaxTransformer extends BaseTransformer
{
    private const PARAMS = ['id' ,'currency', 'percent'];

    public function fromArray(Tax $tax): array
    {
        return $this->transform($tax, static::PARAMS);
    }
}
