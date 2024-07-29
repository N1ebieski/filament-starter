<?php

declare(strict_types=1);

namespace App\Data\Data\Payload\OnlyParameters;

use Closure;
use App\Data\Data\Payload\Payload;
use App\Data\Data\Payload\HandlerInterface;

final class OnlyParametersHandler implements HandlerInterface
{
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

    public function handle(Payload $payload, Closure $next): Payload
    {
        $parameters = array_flip($this->getConstructorParameters($payload->classname));

        $payload->payloads = array_intersect_key($payload->payloads, $parameters);

        return $next($payload);
    }
}
