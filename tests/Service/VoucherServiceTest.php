<?php

namespace App\Tests\Service;

use App\Entity\Voucher;
use App\Mapper\VoucherUpdateMapper;
use App\Repository\VoucherRepository;
use App\Request\GetVoucherRequest;
use App\Service\VoucherService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VoucherServiceTest extends TestCase
{
    public function testFind()
    {
        $voucherExpected = new Voucher();
        $voucherRepositoryMock = $this->getMockBuilder(VoucherRepository::class)->disableOriginalConstructor()->getMock();
        $voucherRepositoryMock->expects($this->once())->method('findOneBy')->willReturn($voucherExpected);
        $voucherUpdateMapperMock = $this->getMockBuilder(VoucherUpdateMapper::class)->getMock();
        $getVoucherRequestMock = $this->getMockBuilder(GetVoucherRequest::class)->getMock();


        $voucherServiceMock = new VoucherService($voucherRepositoryMock, $voucherUpdateMapperMock);
        $voucher = $voucherServiceMock->find($getVoucherRequestMock);

        $this->assertEquals($voucherExpected, $voucher);
    }

    public function testFindWithFailReturn()
    {
        $voucherRepositoryMock = $this->getMockBuilder(VoucherRepository::class)->disableOriginalConstructor()->getMock();
        $voucherRepositoryMock->expects($this->once())->method('findOneBy')->willReturn(null);
        $voucherUpdateMapperMock = $this->getMockBuilder(VoucherUpdateMapper::class)->getMock();
        $getVoucherRequestMock = $this->getMockBuilder(GetVoucherRequest::class)->getMock();


        $voucherServiceMock = new VoucherService($voucherRepositoryMock, $voucherUpdateMapperMock);
        $this->ExpectException(NotFoundHttpException::class);
        $voucherServiceMock->find($getVoucherRequestMock);
    }

    public function testGetAllDiscount()
    {
        $voucherRepositoryMock = $this->getMockBuilder(VoucherRepository::class)->disableOriginalConstructor()->getMock();
        $voucherRepositoryMock->expects($this->once())->method('findAll')->willReturn(array());
        $voucherUpdateMapperMock = $this->getMockBuilder(VoucherUpdateMapper::class)->getMock();


        $voucherServiceMock = new VoucherService($voucherRepositoryMock, $voucherUpdateMapperMock);
        $voucher = $voucherServiceMock->getAllDisCount();

        $this->assertEquals(array(), $voucher);
    }
}
