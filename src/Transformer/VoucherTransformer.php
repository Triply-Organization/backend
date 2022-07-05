<?php

namespace App\Transformer;

use App\Entity\Voucher;

class VoucherTransformer extends BaseTransformer
{
    private const PARAMS = ['id' ,'code', 'discount'];

    public function fromArray(Voucher $voucher): array
    {
        return $this->transform($voucher, static::PARAMS);
    }
}
