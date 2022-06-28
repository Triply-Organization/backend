<?php

namespace App\EventListener;

use App\Traits\ResponseTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationFailureListener
{
    use ResponseTrait;

    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event): void
    {
        $response = $this->errors(['Unauthorized'], Response::HTTP_UNAUTHORIZED);
        $event->setResponse($response);
    }
}
