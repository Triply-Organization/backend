<?php

namespace App\Controller\Api;

use App\Service\ImageService;
use App\Traits\ResponseTrait;
use App\Request\ImageRequest;
use App\Transformer\ImageTransformer;
use App\Validator\ImageValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/images', name: 'image_')]
class ImageController extends AbstractController
{
    use ResponseTrait;

    #[Route('/', name: 'add_image', methods: 'POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function addImage(
        Request $request,
        ImageService $imageService,
        ImageTransformer $imageTransformer,
        ImageRequest $imageRequest,
        ImageValidator $imageValidator
    ): JsonResponse {
        $fileRequest = $request->files->get('image');
        $file = $imageRequest->setImage($fileRequest);
        $errors = $imageValidator->validatorImageRequest($file);
        if (!empty($errors)) {
            return $this->errors($errors);
        }
        $image = $imageService->addImage($fileRequest);
        $results = $imageTransformer->fromArray($image);

        return $this->success($results);
    }
}
