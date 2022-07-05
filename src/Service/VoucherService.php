<?php

namespace App\Service;

use App\Entity\Voucher;
use App\Repository\VoucherRepository;
use App\Request\AddVoucherRequest;

class VoucherService
{
    private VoucherRepository $voucherRepository;

    public function __construct(VoucherRepository $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    public function add(AddVoucherRequest $addVoucherRequest):void {
        $voucher = new Voucher();
        $voucher->setCode($addVoucherRequest->getCode());
        $voucher->setDiscount($addVoucherRequest->getPercent());
        $voucher->setRemain($addVoucherRequest->getRemain());
        $this->voucherRepository->add($voucher, true);
    }

    public function getAllDisCount()
    {
        return $this->voucherRepository->findAll();
    }
}
