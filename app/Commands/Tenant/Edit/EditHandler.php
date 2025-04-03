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
        $this->db->beginTransaction();

        try {
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
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        /** @var Tenant */
        return $tenant->fresh();
    }
}
