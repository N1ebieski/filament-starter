<?php

declare(strict_types=1);

namespace App\Data\Pipelines\ObjectDefaultsDataPipe;

use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use Spatie\LaravelData\Support\DataClass;
use Spatie\LaravelData\DataPipes\DataPipe;
use Spatie\LaravelData\Support\Creation\CreationContext;

final class ObjectDefaultsDataPipe implements DataPipe
{
    /**
     * @param class-string $className
     */
    private function getConstructorDefaults(string $className): array
    {
        $reflection = new \ReflectionClass($className);
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

    public function handle(mixed $payload, DataClass $class, array $properties, CreationContext $creationContext): array
    {
        /** @var array */
        $interfaces = class_implements($class->name);

        if (is_array($payload) && in_array(ObjectDefaultsInterface::class, $interfaces)) {
            $properties = array_merge(
                $this->getConstructorDefaults($class->name),
                $properties
            );
        }

        return $properties;
    }
}
