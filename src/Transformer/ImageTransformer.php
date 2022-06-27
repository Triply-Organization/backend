<?php

namespace App\Transformer;

use App\Entity\Image;

class ImageTransformer extends BaseTransformer
{
    const PARAMS = ['id' ,'path', 'createdAt'];
    public function fromArray(Image $image): array
    {
        return $this->transform($image, static::PARAMS);
    }
}
