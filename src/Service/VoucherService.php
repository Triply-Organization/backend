<?php

namespace App\Service;

use App\Repository\VoucherRepository;

class VoucherService
{
    private VoucherRepository $voucherRepository;

    public function __construct(VoucherRepository $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    public function getAllDisCount()
    {
        return $this->voucherRepository->findAll();
    }
}