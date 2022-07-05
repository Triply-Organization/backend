<?php

namespace App\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Traits\ResponseTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Request\ListCustomerRequest;
use App\Service\ListCustomerService;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin/manager', name: 'admin_')]
class AdminController extends AbstractController
{
    use ResponseTrait;

    #[isGranted('ROLE_ADMIN')]
    #[Route('/customers', name: 'get_customers', methods: 'GET')]
    public function listCustomers(
        Request             $request,
        ValidatorInterface  $validator,
        ListCustomerService $listCustomerService,
        ListCustomerRequest $listCustomerRequest,
    ): JsonResponse
    {
        $query = $request->query->all();
        $userRequest = $listCustomerRequest->fromArray($query);
        $errors = $validator->validate($userRequest);
        if (count($errors) > 0) {
            return $this->errors(['Bad request']);
        }
        $users = $listCustomerService->getUsers($userRequest);

        return $this->success($users);
    }

}
