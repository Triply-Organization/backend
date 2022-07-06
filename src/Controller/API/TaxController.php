<?php

namespace App\Controller\API;

use App\Entity\Tax;
use App\Request\AddTaxRequest;
use App\Request\BaseRequest;
use App\Request\GetTaxRequest;
use App\Request\PatchUpdateTaxRequest;
use App\Request\PutUpdateTaxRequest;
use App\Service\TaxService;
use App\Traits\ResponseTrait;
use App\Transformer\TaxTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/taxes', name: 'tax_')]
class TaxController extends AbstractController
{
    use ResponseTrait;

    #[Route('/', name: 'add', methods: 'POST')]
    public function addTax(
        Request $request,
        AddTaxRequest $addTaxRequest,
        ValidatorInterface $validator,
        TaxService $taxService,
    ): JsonResponse {
        $requestData = $request->toArray();
        $addTaxRequestData = $addTaxRequest->fromArray($requestData);
        $errors = $validator->validate($addTaxRequestData);

        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }

        $taxService->add($addTaxRequestData);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/{id<\d+>}', name: 'put_update', methods: 'PUT')]
    public function putUpdateTax(
        Request $request,
        Tax $tax,
        PutUpdateTaxRequest $updateTaxRequest,
        ValidatorInterface $validator,
        TaxService $taxService
    ): JsonResponse {
        return $this->updateTax($request, $tax, $updateTaxRequest, $validator, $taxService);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/{id<\d+>}', name: 'patch_update', methods: 'PATCH')]
    public function patchUpdateTax(
        Request $request,
        Tax $tax,
        PatchUpdateTaxRequest $updateTaxRequest,
        ValidatorInterface $validator,
        TaxService $taxService
    ): JsonResponse {
        return $this->updateTax($request, $tax, $updateTaxRequest, $validator, $taxService);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/{id<\d+>}', name: 'delete', methods: 'DELETE')]
    public function deleteTax(
        Tax $tax,
        TaxService $taxService
    ): JsonResponse {
        $taxService->delete($tax);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[isGranted('ROLE_USER')]
    #[Route('/getinfo', name: 'getinfo', methods: 'GET')]
    public function getTax(
        Request $request,
        GetTaxRequest $getTaxRequest,
        ValidatorInterface $validator,
        TaxService $taxService,
        TaxTransformer $taxTransformer
    ): JsonResponse {
        $requestData = $request->query->all();
        $getTaxRequestData = $getTaxRequest->fromArray($requestData);
        $errors = $validator->validate($getTaxRequestData);

        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }

        $tax = $taxService->find($getTaxRequestData);
        $taxData = $taxTransformer->fromArray($tax);

        return $this->success($taxData);
    }


    private function updateTax(
        Request $request,
        Tax $tax,
        BaseRequest $updateTaxRequest,
        ValidatorInterface $validator,
        TaxService $taxService
    ): JsonResponse {
        $requestData = $request->toArray();
        $updateTaxRequestData = $updateTaxRequest->fromArray($requestData);
        $errors = $validator->validate($updateTaxRequestData);

        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }

        $taxService->update($tax, $updateTaxRequestData);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
