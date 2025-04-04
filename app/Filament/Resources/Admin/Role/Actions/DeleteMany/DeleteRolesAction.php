<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions\DeleteMany;

use App\Commands\CommandBusInterface;
use App\Commands\Role\DeleteMany\DeleteManyCommand;
use App\Filament\Actions\Action;
use App\Models\Role\Role;
use App\Overrides\Illuminate\Support\Facades\Lang;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

final class DeleteRolesAction extends Action
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public static function make(): DeleteBulkAction
    {
        /** @var static */
        $static = App::make(self::class);

        return $static->makeAction();
    }

    public function makeAction(): DeleteBulkAction
    {
        return DeleteBulkAction::make()
            ->authorize(fn (): bool => Gate::allows('adminDeleteAny', Role::class))
            ->modalHeading(fn (Collection $records): string => Lang::choice('role.pages.delete_multi.title', $records->count(), [
                'number' => $records->count(),
            ]))
            ->using(function (Collection $records): int {
                $records = $records->filter(fn (Role $role): bool => Gate::allows('adminDelete', $role));

                return $this->commandBus->execute(new DeleteManyCommand($records));
            })
            ->successNotificationTitle(fn (Collection $records): string => Lang::choice('role.messages.delete_multi.success', $records->count(), [
                'number' => $records->count(),
            ]));
    }
}
