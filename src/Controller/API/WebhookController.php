<?php

namespace App\Controller\API;

use App\Service\StripeService;
use PHPMailer\PHPMailer\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/webhook', name: 'api_')]
class WebhookController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('', name: 'webhook')]
    public function getData(Request $request,LoggerInterface $logger,StripeService $stripeService)
    {
        $event = $request->toArray();
        $data = $event['data']['object'];
        $type = $event['type'];

        $stripeService->eventHandler($data, $type);

        return $this->json(['']);
    }
}
