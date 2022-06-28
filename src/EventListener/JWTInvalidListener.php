<?php

namespace App\EventListener;

use App\Traits\ResponseTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\Response;

class JWTInvalidListener
{
    use ResponseTrait;

    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $response = $this->errors(['Unauthorized'], Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }

    public function onJWTNotFound(JWTNotFoundEvent $event): void
    {
        $response = $this->errors(['Unauthorized'], Response::HTTP_UNAUTHORIZED);
        $event->setResponse($response);
    }
}
