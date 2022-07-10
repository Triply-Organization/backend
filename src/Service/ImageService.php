<?php

namespace App\Service;

use App\Entity\Image;
use App\Manager\UploadImageS3Manager;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    private ImageRepository $imageRepository;
    private UploadImageS3Manager $imageS3Manager;

    public function __construct(ImageRepository $imageRepository, UploadImageS3Manager $imageS3Manager)
    {
        $this->imageRepository = $imageRepository;
        $this->imageS3Manager = $imageS3Manager;
    }

    public function addImage(UploadedFile $file): Image
    {
        $image = new Image();
        $imagePath = $this->imageS3Manager->upload($file);
        $image->setPath($imagePath);
        $this->imageRepository->add($image, true);

        return $image;
    }
}
