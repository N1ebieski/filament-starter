<?php

declare(strict_types=1);

namespace App\Mixins\Collection\Data;

use App\Data\Data\Data;
use App\Models\Shared\Data\DataInterface;
use Closure;
use Illuminate\Support\Collection;

/**
 * @mixin Collection
 */
final class DataMixin
{
    public function toData(): Closure
    {
        return function () {
            return $this->map(function (mixed $value): Data {
                if (! is_object($value) || ! $value instanceof DataInterface) {
                    throw new \InvalidArgumentException(
                        'Only objects that implement '.DataInterface::class.' can be converted to '.Data::class
                    );
                }

                return $value->data;
            });
        };
    }
}
