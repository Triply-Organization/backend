<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Traits\ResponseTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Request\ListUserRequest;
use App\Service\ListUserService;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    use ResponseTrait;

    #[Route('/users', name: 'get_users', methods: 'GET')]
    public function listUsers(
        Request            $request,
        ValidatorInterface $validator,
        ListUserService    $listUserService,
        ListUserRequest    $listUserRequest,
    ): JsonResponse
    {
        $query = $request->query->all();
        $userRequest = $listUserRequest->fromArray($query);
        $errors = $validator->validate($userRequest);
        if (count($errors) > 0) {
            return $this->errors(['Bad request']);
        }
        $users = $listUserService->getUsers($userRequest);

        return $this->success($users);
    }
}
