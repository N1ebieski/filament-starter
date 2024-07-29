<?php

declare(strict_types=1);

namespace App\Data\Data\Payload\ObjectDefaults;

use Closure;
use App\Data\Data\Payload\Payload;
use App\Data\ObjectDefaultsInterface;
use App\Data\Data\Payload\HandlerInterface;

final class ObjectDefaultsHandler implements HandlerInterface
{
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

    public function handle(Payload $payload, Closure $next): Payload
    {
        if (in_array(ObjectDefaultsInterface::class, class_implements($payload->className))) {
            $payload->payloads = array_merge(
                $this->getConstructorDefaults($payload->className),
                $payload->payloads
            );
        }

        return $next($payload);
    }
}
