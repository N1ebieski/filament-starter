<?php

declare(strict_types=1);

namespace App\Overrides\Spatie\LaravelData;

use Closure;
use App\Models\HasAttributesInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Lazy as BaseLazy;
use Spatie\LaravelData\Support\Lazy\DefaultLazy;
use Spatie\LaravelData\Support\Lazy\RelationalLazy;
use Spatie\LaravelData\Support\Lazy\ConditionalLazy;

abstract class Lazy
{
    public static function whenAttributeLoaded(string $attribute, HasAttributesInterface $model, Closure $value): ConditionalLazy
    {
        return self::when($model->attributeLoaded($attribute), $value);
    }

    public static function whenLoaded(string $item, Model $model, Closure $value): RelationalLazy|ConditionalLazy|DefaultLazy
    {
        if (method_exists($model, $item)) {
            return BaseLazy::whenLoaded($item, $model, $value);
        }

        if ($model instanceof HasAttributesInterface) {
            return self::whenAttributeLoaded($item, $model, $value);
        }

        return BaseLazy::create($value);
    }

    public static function when(Closure|bool $condition, Closure $value): ConditionalLazy
    {
        /** @var Closure */
        $conditionAsClosure = is_bool($condition) ? fn () => $condition : $condition;

        return BaseLazy::when($conditionAsClosure, $value);
    }
}
