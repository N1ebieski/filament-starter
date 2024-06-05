<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions;

use App\Models\Role\Role;
use App\Commands\CommandBusInterface;
use App\Filament\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Commands\Role\DeleteMany\DeleteManyCommand;

final class DeleteRolesAction extends Action
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public static function make(): DeleteBulkAction
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->getAction();
    }

    public function getAction(): DeleteBulkAction
    {
        return DeleteBulkAction::make()
            ->modalHeading(function (Collection $records): string {
                return Lang::choice('role.pages.delete_multi.title', $records->count(), [
                    'number' => $records->count()
                ]);
            })
            ->using(function (Collection $records, Guard $guard): int {
                $records = $records->filter(fn (Role $role): bool => $guard->user()?->can('delete', $role));

                return $this->commandBus->execute(new DeleteManyCommand($records));
            })
            ->successNotificationTitle(function (Collection $records): string {
                return Lang::choice('role.messages.delete_multi.success', $records->count(), [
                    'number' => $records->count()
                ]);
            });
    }
}
