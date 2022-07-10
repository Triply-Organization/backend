<?php

namespace App\Controller\API;

use App\Request\RegisterRequest;
use App\Service\RegisterService;
use App\Traits\ResponseTrait;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class RegisterController extends AbstractController
{
    use ResponseTrait;

    /**
     * @throws Exception
     */
    #[Route('/register', name: 'register', methods: 'POST')]
    public function register(
        Request $request,
        ValidatorInterface $validator,
        RegisterRequest $registerRequest,
        RegisterService $registerService,
    ): JsonResponse {
        $requestData = $request->toArray();
        $requestData = $registerRequest->fromArray($requestData);
        $errors = $validator->validate($requestData);

        if (count($errors) > 0) {
            return $this->errors(['errors' => 'Something wrong']);
        }

        $registerService->register($requestData);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
