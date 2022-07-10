<?php

namespace App\Tests\Unit\Service;

use App\Entity\Tax;
use App\Mapper\TaxUpdateMapper;
use App\Repository\TaxRepository;
use App\Request\AddTaxRequest;
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
        $getTaxRequest = new GetTaxRequest();
        $taxRepositoryMock = $this->getMockBuilder(TaxRepository::class)->disableOriginalConstructor()->getMock();
        $taxUpdateMapperMock = $this->getMockBuilder(TaxUpdateMapper::class)->disableOriginalConstructor()->getMock();
        $taxRepositoryMock->expects($this->once())->method('findOneBy')->willReturn(null);
        $taxService = new TaxService($taxRepositoryMock, $taxUpdateMapperMock);
        $this->ExpectException(NotFoundHttpException::class);
        $taxService->find($getTaxRequest);
    }

    public function testAddTaxService()
    {
        $addTaxRequest = new AddTaxRequest;
        $addTaxRequest->setPercent(12);
        $addTaxRequest->setCurrency('STRING');
        $taxRepositoryMock = $this->getMockBuilder(TaxRepository::class)->disableOriginalConstructor()->getMock();
        $taxUpdateMapperMock = $this->getMockBuilder(TaxUpdateMapper::class)->disableOriginalConstructor()->getMock();
        $taxService = new TaxService($taxRepositoryMock, $taxUpdateMapperMock);
        $taxRepositoryMock->expects($this->once())->method('add');
        $result = $taxService->add($addTaxRequest);
        $this->assertTrue($result);
    }

    public function testDeleteTaxService()
    {
        $taxMock = $this->getMockBuilder(Tax::class)->getMock();
        $taxMock->method('getId')->willReturn(1);
        $taxRepositoryMock = $this->getMockBuilder(TaxRepository::class)->disableOriginalConstructor()->getMock();
        $taxUpdateMapperMock = $this->getMockBuilder(TaxUpdateMapper::class)->disableOriginalConstructor()->getMock();
        $taxService = new TaxService($taxRepositoryMock, $taxUpdateMapperMock);
        $taxRepositoryMock->expects($this->once())->method('delete');
        $result = $taxService->delete($taxMock);
        $this->assertTrue($result);
    }
}
