<?php

namespace App\Controller\API;

use App\Service\StripeService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/webhook', name: 'api_')]
class WebhookController extends AbstractController
{
    #[Route('', name: 'webhook')]
    public function getData(Request $request, LoggerInterface $logger, StripeService $stripeService)
    {
        $event = $request->toArray();
        $payment = $stripeService->eventHandler($event);
        $logger->debug($request->getContent());

        return $this->json(['']);
    }
}
