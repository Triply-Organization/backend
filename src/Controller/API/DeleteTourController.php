<?php

namespace App\Controller\API;

use App\Traits\ResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/', name: 'api_tour_')]
class DeleteTourController extends AbstractController
{
    use ResponseTrait;
}
