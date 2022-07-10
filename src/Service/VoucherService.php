<?php

namespace App\Service;

use App\Entity\Voucher;
use App\Mapper\VoucherUpdateMapper;
use App\Repository\VoucherRepository;
use App\Request\AddVoucherRequest;
use App\Request\BaseRequest;
use App\Request\GetVoucherRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VoucherService
{
    private VoucherRepository $voucherRepository;
    private VoucherUpdateMapper $voucherUpdateMapper;

    public function __construct(VoucherRepository $voucherRepository, VoucherUpdateMapper $voucherUpdateMapper)
    {
        $this->voucherRepository = $voucherRepository;
        $this->voucherUpdateMapper = $voucherUpdateMapper;
    }

    public function add(AddVoucherRequest $addVoucherRequest)
    {
        $voucher = new Voucher();
        $voucher->setCode($addVoucherRequest->getCode());
        $voucher->setDiscount($addVoucherRequest->getPercent());
        $voucher->setRemain($addVoucherRequest->getRemain());
        $this->voucherRepository->add($voucher, true);

        return true;
    }

    public function update(Voucher $voucher, BaseRequest $updateVoucherRequest)
    {
        $voucherUpdated = $this->voucherUpdateMapper->mapping($voucher, $updateVoucherRequest);
        $this->voucherRepository->add($voucherUpdated, true);
        return true;
    }

    public function delete(Voucher $voucher)
    {
        $this->voucherRepository->delete($voucher->getId());
        return true;
    }

    public function find(GetVoucherRequest $getVoucherRequest): ?Voucher
    {
        $voucher = $this->voucherRepository->findOneBy(['code' => $getVoucherRequest->getCode()]);
        if (!$voucher) {
            throw new NotFoundHttpException();
        }

        return $voucher;
    }

    public function getAllDisCount(): array
    {
        return $this->voucherRepository->findAll();
    }
}
