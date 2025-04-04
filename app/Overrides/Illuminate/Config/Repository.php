<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Config;

use App\Overrides\Illuminate\Contracts\Config\Repository as RepositoryContract;
use Closure;
use Illuminate\Config\Repository as BaseRepository;

final class Repository implements RepositoryContract
{
    public function __construct(private readonly BaseRepository $baseRepository) {}

    /**
     * @param  Closure(): string|null|string|null  $default
     */
    public function string(string $key, $default = null): string
    {
        return $this->baseRepository->string($key, $default);
    }

    /**
     * @param  Closure(): int|null|int|null  $default
     */
    public function integer(string $key, $default = null): int
    {
        return $this->baseRepository->integer($key, $default);
    }

    /**
     * @param  Closure(): float|null|float|null  $default
     */
    public function float(string $key, $default = null): float
    {
        return $this->baseRepository->float($key, $default);
    }

    /**
     * @param  Closure(): bool|null|bool|null  $default
     */
    public function boolean(string $key, $default = null): bool
    {
        return $this->baseRepository->boolean($key, $default);
    }

    /**
     * @param  Closure(): array<string|int, mixed>|null|array<string|int, mixed>|null  $default
     * @return array<string|int, mixed>
     */
    public function array(string $key, $default = null): array
    {
        return $this->baseRepository->array($key, $default);
    }
}
