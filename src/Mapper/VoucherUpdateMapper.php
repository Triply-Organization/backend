<?php

namespace App\Mapper;

use App\Entity\Voucher;
use App\Request\BaseRequest;
use DateTimeImmutable;

class VoucherUpdateMapper
{
    public function mapping(Voucher $voucher, BaseRequest $updateVoucherRequest): Voucher
    {
        $voucher->setCode($updateVoucherRequest->getCode() ?? $voucher->getCode())
                ->setDiscount($updateVoucherRequest->getPercent() ?? $voucher->getDiscount())
                ->setRemain($updateVoucherRequest->getRemain() ?? $voucher->getRemain())
                ->setUpdatedAt(new \DateTimeImmutable());
        return $voucher;
    }
}
