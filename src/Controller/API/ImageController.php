<?php

namespace App\Controller\API;

use App\Request\ImageRequest;
use App\Service\ImageService;
use App\Traits\ResponseTrait;
use App\Transformer\ImageTransformer;
use App\Validator\ImageValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/images', name: 'image_')]
class ImageController extends AbstractController
{
    use ResponseTrait;

    #[isGranted('ROLE_USER')]
    #[Route('/', name: 'add_image', methods: 'POST')]
    public function addImage(
        Request $request,
        ImageService $imageService,
        ImageTransformer $imageTransformer,
        ImageRequest $imageRequest,
        ValidatorInterface $validator,
    ): JsonResponse {
        $filesRequest = $request->files->get('image');
        $results = [];

        foreach ($filesRequest as $fileRequest) {
            $file = $imageRequest->setImage($fileRequest);
            $errors = $validator->validate($file);
            if (count($errors) > 0) {
                return $this->errors(['Something wrong']);
            }
            $image = $imageService->addImage($fileRequest);
            $results[] = $imageTransformer->fromArray($image);
        }

        return $this->success($results);
    }
}
