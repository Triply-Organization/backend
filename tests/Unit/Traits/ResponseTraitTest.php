<?php

namespace App\Tests\Unit\Traits;

use App\Traits\ResponseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseTraitTest extends TestCase
{
    use ResponseTrait;

    public function testSuccessResponse()
    {
        $data = ['success'];
        $result = $this->success($data);
        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    public function testErrorsResponse()
    {
        $data = ['Something wrong'];
        $result = $this->errors($data);
        $this->assertInstanceOf(JsonResponse::class, $result);
    }
}
