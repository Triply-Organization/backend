<?php

namespace App\Mapper;

use App\Entity\Tax;
use App\Request\BaseRequest;
use DateTimeImmutable;

class TaxUpdateMapper
{
    public function mapping(Tax $tax, BaseRequest $updateTaxRequest): Tax
    {
        $tax->setCurrency($updateTaxRequest->getCurrency() ?? $tax->getCurrency())
            ->setPercent($updateTaxRequest->getPercent() ?? $tax->getPercent())
            ->setUpdatedAt(new \DateTimeImmutable());
        return $tax;
    }
}
