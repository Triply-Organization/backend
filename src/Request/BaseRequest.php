<?php

namespace App\Request;

abstract class BaseRequest
{
    const LIMIT_DEFAULT = 10;
    const VALUE_DEFAULT = null;
    const STRING_DEFAULT = null;
    const INT_DEFAULT = null;
    const NUMERIC_DEFAULT = null;
    const ARRAY_DEFAULT = [];

    public function fromArray(?array $requests): self
    {
        foreach ($requests as $key => $request) {
            $action = 'set' . ucfirst($key);
            if (!method_exists($this, $action)) {
                continue;
            }
            $this->{$action}($request);
        }

        return $this;
    }
}
