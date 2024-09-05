<?php

declare(strict_types=1);

namespace App\Support\Query\Columns;

use Illuminate\Support\Collection;

final class ColumnsHelper
{
    public static function getColumnsWithTablePrefix(array $columns, string $table): array
    {
        return (new Collection($columns))
            ->map(function (string $column) use ($table): string {
                return "{$table}.{$column}";
            })
            ->toArray();
    }

    public static function getColumnsAsString(array $columns): string
    {
        return (new Collection($columns))
            ->map(function (string $column) {
                return self::getColumnWithTicks($column);
            })
            ->implode(',');
    }

    public static function getColumnWithTicks(string $column): string
    {
        $names = explode('.', $column);

        return (new Collection($names))
            ->map(function (string $name) {
                return '`' . $name . '`';
            })
            ->implode('.');
    }

    public static function getColumnWithSnakes(string $column): string
    {
        return self::getColumnWithTicks(str_replace('.', '_', $column));
    }
}
