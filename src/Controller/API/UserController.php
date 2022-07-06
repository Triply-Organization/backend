<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Service\UserService;
use App\Traits\ResponseTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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
}
