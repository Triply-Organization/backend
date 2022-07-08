<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Request\PatchUpdateUserRequest;
use App\Service\UserService;
use App\Traits\ResponseTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/users', name: 'user_')]
class UserController extends AbstractController
{
    use ResponseTrait;

    #[Route('/', name: 'getAllOrder', methods: 'GET')]
    #[IsGranted('ROLE_USER')]
    public function getAllOrderOfUser(
        UserService $userService
    ): JsonResponse {
        return  $this->success($userService->getAllOrder());
    }

    #[Route('/{id<\d+>}', name: 'update', methods: 'PATCH')]
    #[IsGranted('ROLE_USER')]
    public function updateUser(
        Request $request,
        User $user,
        PatchUpdateUserRequest $patchUpdateUserRequest,
        ValidatorInterface $validator,
        UserService $userService
    ): JsonResponse {
        $requestData = $request->toArray();
        $patchUpdateUserRequestData = $patchUpdateUserRequest->fromArray($requestData);
        $errors = $validator->validate($patchUpdateUserRequestData);

        if(count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }

        $userService->update($user, $patchUpdateUserRequestData);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
