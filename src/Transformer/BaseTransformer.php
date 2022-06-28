<?php

namespace App\Transformer;

use App\Entity\AbstractEntity;

abstract class BaseTransformer
{
    public function transform(AbstractEntity $entity, array $params): array
    {
        $result = [];
        foreach ($params as $value) {
            $getProperty = 'get' . ucfirst($value);
            if (!method_exists($entity, $getProperty)) {
                continue;
            }
            $result[$value] = $entity->{$getProperty}();
        }

        return $result;
    }
}
