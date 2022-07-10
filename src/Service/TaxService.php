<?php

namespace App\Service;

use App\Entity\Tax;
use App\Mapper\TaxUpdateMapper;
use App\Repository\TaxRepository;
use App\Request\AddTaxRequest;
use App\Request\BaseRequest;
use App\Request\GetTaxRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaxService
{
    private TaxRepository $taxRepository;
    private TaxUpdateMapper $taxUpdateMapper;

    public function __construct(TaxRepository $taxRepository, TaxUpdateMapper $taxUpdateMapper)
    {
        $this->taxRepository = $taxRepository;
        $this->taxUpdateMapper = $taxUpdateMapper;
    }

    public function add(AddTaxRequest $addTaxRequest)
    {
        $tax = new Tax();
        $tax->setCurrency($addTaxRequest->getCurrency());
        $tax->setPercent($addTaxRequest->getPercent());
        $this->taxRepository->add($tax, true);

        return true;
    }

    public function update(Tax $tax, BaseRequest $updateVoucherRequest)
    {
        $voucherUpdated = $this->taxUpdateMapper->mapping($tax, $updateVoucherRequest);
        $this->taxRepository->add($voucherUpdated, true);

        return true;
    }

    public function delete(Tax $tax)
    {
        $this->taxRepository->delete($tax->getId());

        return true;
    }

    public function find(GetTaxRequest $getTaxRequest): Tax
    {
        $tax = $this->taxRepository->findOneBy(['currency' => $getTaxRequest->getCurrency()]);

        if (!$tax) {
            throw new NotFoundHttpException();
        }

        return $tax;
    }
}
