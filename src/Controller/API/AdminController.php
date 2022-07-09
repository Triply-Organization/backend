<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Request\PatchUpdateUserRequest;
use App\Request\UserRequest;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Traits\ResponseTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\CustomerService;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin/manager', name: 'admin_')]
class AdminController extends AbstractController
{
    use ResponseTrait;

    #[isGranted('ROLE_ADMIN')]
    #[Route('/customers', name: 'getCustomers', methods: 'GET')]
    public function listCustomers(
        Request $request,
        ValidatorInterface $validator,
        CustomerService $customerService,
        UserRequest $userRequest,
    ): JsonResponse {
        $query = $request->query->all();
        $userRequest = $userRequest->fromArray($query);
        $errors = $validator->validate($userRequest);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $users = $customerService->getCustomers($userRequest);

        return $this->success($users);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/users', name: 'getUsers', methods: 'GET')]
    public function listUsers(
        Request $request,
        ValidatorInterface $validator,
        UserService $userService,
        UserRequest $userRequest
    ): JsonResponse {
        $query = $request->query->all();
        $userRequest = $userRequest->fromArray($query);
        $errors = $validator->validate($userRequest);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $users = $userService->getUsers($userRequest);

        return $this->success($users);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/users/{id<\d+>}', name: 'editUsers', methods: 'PATCH')]
    public function editUserRole(
        User $user,
        Request $request,
        ValidatorInterface $validator,
        UserService $userService,
        PatchUpdateUserRequest $patchUpdateUserRequest
    ): JsonResponse {
        $dataRequest = $request->toArray();
        $editRoleRequestData = $patchUpdateUserRequest->fromArray($dataRequest);
        $errors = $validator->validate($editRoleRequestData);

        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }

        $userService->update($user, $editRoleRequestData);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/users/{id<\d+>}', name: 'delete_user', methods: 'DELETE')]
    public function deleteUser(User $user, UserService $userService): JsonResponse
    {
        $userService->deleteUser($user);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/undo/users/{id<\d+>}', name: 'undo_delete_user', methods: 'PATCH')]
    public function undoDeleteUser(User $user, UserService $userService): JsonResponse
    {
        $userService->undoDeleteUser($user);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/customers/{id<\d+>}', name: 'delete_customers', methods: 'DELETE')]
    public function deleteCustomer(User $user, CustomerService $customerService): JsonResponse
    {
        if ($customerService->deleteCustomer($user)) {
            return $this->success([], Response::HTTP_NO_CONTENT);
        }

        return $this->errors(['Something wrong']);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/undo/customers/{id<\d+>}', name: 'undo_delete_customer', methods: 'PATCH')]
    public function undoDeleteCustomer(User $user, CustomerService $customerService): JsonResponse
    {
        if ($customerService->undoDeleteCustomer($user)) {
            return $this->success([], Response::HTTP_NO_CONTENT);
        }

        return $this->errors(['Something wrong']);
    }
}
