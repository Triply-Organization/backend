<?php

namespace App\Tests\Unit\Service;

use App\Entity\Voucher;
use App\Mapper\VoucherUpdateMapper;
use App\Repository\VoucherRepository;
use App\Request\AddVoucherRequest;
use App\Request\BaseRequest;
use App\Request\GetVoucherRequest;
use App\Service\VoucherService;
use App\Tests\Request\BaseRequestTest;
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

    public function testAddVoucher()
    {
        $addVoucherRequest = new AddVoucherRequest();
        $addVoucherRequest->setCode('STRING');
        $addVoucherRequest->setPercent(12);
        $addVoucherRequest->setRemain(10);

        $voucherRepositoryMock = $this->getMockBuilder(VoucherRepository::class)->disableOriginalConstructor()->getMock();
        $voucherRepositoryMock->expects($this->once())->method('add');
        $voucherUpdateMapperMock = $this->getMockBuilder(VoucherUpdateMapper::class)->getMock();
        $voucherServiceMock = new VoucherService($voucherRepositoryMock, $voucherUpdateMapperMock);

        $result = $voucherServiceMock->add($addVoucherRequest);
        $this->assertTrue($result);
    }

    public function testDeleteVoucher()
    {
        $voucherMock = $this->getMockBuilder(Voucher::class)->getMock();
        $voucherMock->method('getId')->willReturn(1);
        $voucherRepositoryMock = $this->getMockBuilder(VoucherRepository::class)->disableOriginalConstructor()->getMock();
        $voucherRepositoryMock->expects($this->once())->method('delete');
        $voucherUpdateMapperMock = $this->getMockBuilder(VoucherUpdateMapper::class)->getMock();
        $voucherServiceMock = new VoucherService($voucherRepositoryMock, $voucherUpdateMapperMock);

        $result = $voucherServiceMock->delete($voucherMock);
        $this->assertTrue($result);
    }
}
