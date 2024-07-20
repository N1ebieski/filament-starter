<?php

declare(strict_types=1);

namespace App\Support\Data;

use Spatie\LaravelData\Data as BaseData;

/**
 * @method self only(string ...$only)
 */
abstract class Data extends BaseData
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
        $payloads = array_merge(static::getConstructorDefaults(), ...$payloads);

        return parent::from($payloads);
    }
}
