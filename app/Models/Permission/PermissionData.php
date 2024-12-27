<?php

declare(strict_types=1);

namespace App\Models\Permission;

use App\Data\Data\Data;
use App\ValueObjects\Permission\Name\Name;
use DateTime;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class PermissionData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly Name $name,
        public readonly ?DateTime $createdAt,
        public readonly ?DateTime $updatedAt,
    ) {}
}
