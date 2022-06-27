<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    public function success(array $data, int $status = Response::HTTP_OK): JsonResponse
    {
        $jsonResponse = new JsonResponse();

        $jsonResponse->setData([
            'status' => 'success',
            'data' => $data
        ]);
        $jsonResponse->setStatusCode($status);
        return $jsonResponse;
    }

    public function errors(array $data, int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $jsonResponse = new JsonResponse();

        $jsonResponse->setData([
            'status' => 'error',
            'errors' => $data
        ]);
        $jsonResponse->setStatusCode($status);
        return $jsonResponse;
    }
}
