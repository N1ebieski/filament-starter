<?php

declare(strict_types=1);

namespace App\Models\Shared\Attributes;

use Illuminate\Support\Str;
use Override;

trait HasCamelCaseAttributes
{
    #[Override]
    public function getAttribute($key)
    {
        if (
            array_key_exists($key, $this->relations)
            || method_exists($this, $key)
        ) {
            return parent::getAttribute($key);
        } else {
            return parent::getAttribute(Str::snake($key));
        }
    }

    #[Override]
    public function setAttribute($key, $value)
    {
        return parent::setAttribute(Str::snake($key), $value);
    }
}
