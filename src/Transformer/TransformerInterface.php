<?php

namespace App\Transformer;

interface TransformerInterface
{
    public function toArray($entity): array;
}
