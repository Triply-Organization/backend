<?php

namespace App\Service;

use App\Entity\Bill;
use App\Repository\BillRepository;

class BillService
{
    private BillRepository $billRepository;

    public function __construct(BillRepository $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    public function add(array $metadata, array $data): Bill
    {
        $bill = new Bill();

        $bill->setTotalPrice($metadata['totalPrice']);
        $bill->setTax($metadata['taxPrice']);
        $bill->setDiscount($metadata['discountPrice']);
        $bill->setStripePaymentId($data['payment_intent']);
        $bill->setCurrency($data['currency']);

        $this->billRepository->add($bill, true);

        return $bill;
    }
}
