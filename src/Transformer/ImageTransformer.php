<?php

namespace App\Transformer;

use App\Entity\Image;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'path'];
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function fromArray(Image $image): array
    {
        $imageData = $this->transform($image, static::PARAMS);
        $imageData['path'] = $this->params->get('s3url') . $image->getPath();

        return $imageData;
    }
}
