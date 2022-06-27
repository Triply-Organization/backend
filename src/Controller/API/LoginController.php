<?php

namespace App\Controller\API;

use App\Traits\ResponseTrait;
use App\Transformer\UserTransformer;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    use ResponseTrait;

    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function login(JWTTokenManagerInterface $tokenManager, UserTransformer $userTransformer): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            $message = ['Unauthorized',Response::HTTP_UNAUTHORIZED];
            return $this->errors($message);
        }
        $token = $tokenManager->create($user);
        $userData = $userTransformer->transform($user);
        $data = [
            'data' => $userData,
            'token' => $token
        ];
        return $this->success($data);
    }
}
