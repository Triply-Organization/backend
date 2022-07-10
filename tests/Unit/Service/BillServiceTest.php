<?php

namespace App\Tests\Unit\Service;

use App\Entity\Bill;
use App\Repository\BillRepository;
use App\Service\BillService;
use PHPUnit\Framework\TestCase;

class BillServiceTest extends TestCase
{

    public function testAddBill()
    {
        $billRepositoryMock = $this->getMockBuilder(BillRepository::class)->disableOriginalConstructor()->getMock();
        $metadata = [
            'totalPrice' => 500,
            'taxPrice' => 50,
            'discountPrice' => 50,
        ];
        $data = [
            'payment_intent' => null,
            'currency' => 'USD',
        ];
        $billService = new BillService($billRepositoryMock);
        $result = $billService->add($metadata, $data);

        $this->assertInstanceOf(Bill::class, $result);
    }
}
