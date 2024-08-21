<?php

declare(strict_types=1);

namespace App\Overrides\Barryvdh\LaravelIdeHelper\Hook;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Casts\ValueObject\ValueObjectCast;
use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;

final class ValueObjectHook implements ModelHookInterface
{
    public function run(ModelsCommand $command, Model $model): void
    {
        $casts = $model->getCasts();

        foreach ($casts as $name => $cast) {
            if (Str::startsWith($cast, ValueObjectCast::class)) {
                [$realCast, $valueObject, $nullable] = explode(':', $cast) + [null, null, false];

                $type = '\\' . $valueObject;

                if ((bool)$nullable) {
                    $type .= '|null';
                }

                $command->setProperty($name, $type, true, true);
            }
        }
    }
}
