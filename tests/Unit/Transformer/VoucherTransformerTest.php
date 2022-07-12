<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\Voucher;
use App\Transformer\VoucherTransformer;
use PHPUnit\Framework\TestCase;

class VoucherTransformerTest extends TestCase
{
    public function testFromArray()
    {
        $voucher = new Voucher();
        $voucher->setCode("STRING")->setDiscount(12)->setRemain(4);
        $voucherTransformer = new VoucherTransformer();
        $result = $voucherTransformer->fromArray($voucher);
        $this->assertIsArray($result);
    }
}
