<?php

namespace App\Tests\Unit\Service;

use App\Entity\Tax;
use App\Mapper\TaxUpdateMapper;
use App\Repository\TaxRepository;
use App\Request\GetTaxRequest;
use App\Service\TaxService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaxServiceTest extends TestCase
{
    public function testFind()
    {
        $tax = new Tax();
        $getTaxRequest = new GetTaxRequest();
        $taxRepositoryMock = $this->getMockBuilder(TaxRepository::class)->disableOriginalConstructor()->getMock();
        $taxUpdateMapperMock = $this->getMockBuilder(TaxUpdateMapper::class)->disableOriginalConstructor()->getMock();
        $taxRepositoryMock->expects($this->once())->method('findOneBy')->willReturn($tax);
        $taxService = new TaxService($taxRepositoryMock, $taxUpdateMapperMock);
        $result = $taxService->find($getTaxRequest);
        $this->assertEquals($tax, $result);
    }

    public function testFindWithFail()
    {
        $tax = new Tax();
        $getTaxRequest = new GetTaxRequest();
        $taxRepositoryMock = $this->getMockBuilder(TaxRepository::class)->disableOriginalConstructor()->getMock();
        $taxUpdateMapperMock = $this->getMockBuilder(TaxUpdateMapper::class)->disableOriginalConstructor()->getMock();
        $taxRepositoryMock->expects($this->once())->method('findOneBy')->willReturn(null);
        $taxService = new TaxService($taxRepositoryMock, $taxUpdateMapperMock);
        $this->ExpectException(NotFoundHttpException::class);
        $taxService->find($getTaxRequest);
    }
}
