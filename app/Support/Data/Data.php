<?php

declare(strict_types=1);

namespace App\Support\Data;

use Spatie\LaravelData\Data as BaseData;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @method self only(string ...$only)
 */
abstract class Data extends BaseData implements Arrayable
{
    private static function getConstructorDefaults(): array
    {
        $reflection = new \ReflectionClass(static::class);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return [];
        }

        $parameters = $constructor->getParameters();
        $defaults = [];

        foreach ($parameters as $parameter) {
            if ($parameter->isDefaultValueAvailable()) {
                $defaults[$parameter->getName()] = $parameter->getDefaultValue();
            }
        }

        return $defaults;
    }

    /**
     * \Spatie\LaravelData\Data doesn't allow to have objects as constructor's default values.
     * This method overrides spatie's factory to bring this feature.
     */
    public static function from(mixed ...$payloads): static
    {
        if (in_array(ObjectDefaultsInterface::class, class_implements(static::class))) {
            $payloadsWithDefaults = array_merge(static::getConstructorDefaults(), ...$payloads);

            $payloads = [$payloadsWithDefaults];
        }

        return parent::from(...$payloads);
    }
}
