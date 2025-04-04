<?php

declare(strict_types=1);

namespace App\Commands\Tenant\Edit;

use App\Commands\Handler;
use App\Models\Tenant\Tenant;
use Illuminate\Database\ConnectionInterface as DB;
use Spatie\LaravelData\Optional;

final class EditHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
    ) {}

    public function handle(EditCommand $command): Tenant
    {
        /** @var Tenant $tenant */
        $tenant = $this->db->transaction(function () use ($command) {
            $tenant = $command->tenant->fill(
                $command->only(...$command->tenant->getFillable())->toArray()
            );

            if (! ($command->user instanceof Optional)) {
                $tenant->user()->associate($command->user);
            }

            $tenant->save();

            if (! ($command->users instanceof Optional)) {
                $tenant->users()->sync($command->users);
            }

            return $tenant;
        });

        $tenant->refresh();

        return $tenant;
    }
}
