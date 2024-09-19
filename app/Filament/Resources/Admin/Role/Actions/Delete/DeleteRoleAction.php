<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions\Delete;

use App\Commands\CommandBusInterface;
use App\Commands\Role\Delete\DeleteCommand;
use App\Filament\Actions\Action;
use App\Models\Role\Role;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

final class DeleteRoleAction extends Action
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public static function make(): DeleteAction
    {
        /** @var static */
        $static = App::make(self::class);

        return $static->makeAction();
    }

    public function makeAction(): DeleteAction
    {
        return DeleteAction::make()
            ->modalHeading(fn (Role $record): string => Lang::get('role.pages.delete.title', [
                'name' => $record->name,
            ]))
            ->using(function (Role $record): bool {
                return $this->commandBus->execute(new DeleteCommand($record));
            })
            ->successNotificationTitle(fn (Role $record): string => Lang::get('role.messages.delete.success', [
                'name' => $record->name,
            ]));
    }
}
