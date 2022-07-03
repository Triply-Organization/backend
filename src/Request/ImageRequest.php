<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class ImageRequest extends BaseRequest
{
    #[Assert\Image(
        maxSize: '10M',
        mimeTypes: [
            'image/jpg',
            'image/png',
            'image/jpeg'
            ],
        maxSizeMessage: 'Please upload a valid size image',
        mimeTypesMessage: 'Please upload a valid image',
    )]
    private File $image;

    /**
     * @return File
     */
    public function getImage(): File
    {
        return $this->image;
    }

    /**
     * @param File $image
     */
    public function setImage(File $image): self
    {
        $this->image = $image;
        return $this;
    }
}
