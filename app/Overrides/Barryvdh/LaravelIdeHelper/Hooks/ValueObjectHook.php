<?php

declare(strict_types=1);

namespace App\Overrides\Barryvdh\LaravelIdeHelper\Hooks;

use App\Casts\ValueObject\ValueObjectCast;
use App\ValueObjects\ValueObject;
use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class ValueObjectHook implements ModelHookInterface
{
    public function run(ModelsCommand $command, Model $model): void
    {
        /** @var array<string, string> $casts */
        $casts = $model->getCasts();

        foreach ($casts as $name => $cast) {
            if (Str::startsWith($cast, ValueObjectCast::class)) {
                [$realCast, $valueObject, $nullable] = explode(':', $cast) + [null, null, false];

                $type = '\\'.$valueObject;

                if ((bool) $nullable) {
                    $type .= '|null';
                }

                $command->setProperty($name, $type, true, true);

                continue;
            }

            if (is_subclass_of($cast, ValueObject::class)) {
                $type = '\\'.$cast;

                $command->setProperty($name, $type, true, true);
            }
        }
    }
}
