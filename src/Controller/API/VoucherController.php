<?php

namespace App\Controller\API;

use App\Request\AddVoucherRequest;
use App\Request\GetVoucherRequest;
use App\Service\VoucherService;
use App\Traits\ResponseTrait;
use App\Transformer\VoucherTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/vouchers', name: 'voucher_')]
class VoucherController extends AbstractController
{
    use ResponseTrait;

    #[isGranted('ROLE_ADMIN')]
    #[Route('/', name: 'add', methods: 'POST')]
    public function addVoucher(
        Request $request,
        AddVoucherRequest $addVoucherRequest,
        ValidatorInterface $validator,
        VoucherService $voucherService
    ):JsonResponse {
        $requestData = $request->toArray();
        $addVoucherRequestData = $addVoucherRequest->fromArray($requestData);
        $errors = $validator->validate($addVoucherRequestData);

        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }

        $voucherService->add($addVoucherRequestData);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/getinfo', name: '', methods: 'POST')]
    public function findVoucher(
        Request $request,
        GetVoucherRequest $getVoucherRequest,
        ValidatorInterface $validator,
        VoucherService $voucherService,
        VoucherTransformer $voucherTransformer
    ):JsonResponse {
        $requestData = $request->toArray();
        $getVoucherRequestData = $getVoucherRequest->fromArray($requestData);
        $errors = $validator->validate($getVoucherRequestData);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $voucher = $voucherService->find($getVoucherRequestData);
        $voucherData = $voucherTransformer->fromArray($voucher);
        return $this->success($voucherData);
    }

}
