<?php

declare(strict_types=1);

namespace App\Data\Pipelines\OnlyParametersDataPipe;

use App\Data\Pipelines\DataPipe as BaseDataPipe;
use Spatie\LaravelData\DataPipes\DataPipe;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataClass;

final class OnlyParametersDataPipe extends BaseDataPipe implements DataPipe
{
    /**
     * @param  class-string  $className
     */
    private function getConstructorParameters(string $className): array
    {
        $reflector = new \ReflectionClass($className);
        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return [];
        }

        $parameters = $constructor->getParameters();

        $names = [];

        foreach ($parameters as $parameter) {
            $names[] = $parameter->getName();
        }

        return $names;
    }

    public function handle(mixed $payload, DataClass $class, array $properties, CreationContext $creationContext): array
    {
        if (is_array($payload)) {
            $constructorParameters = array_flip($this->getConstructorParameters($class->name));

            $properties = array_intersect_key($properties, $constructorParameters);
        }

        return $properties;
    }
}
