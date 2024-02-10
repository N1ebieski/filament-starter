<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions;

use App\Models\Role\Role;
use App\Commands\CommandBus;
use App\Filament\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Filament\Tables\Actions\DeleteAction;
use App\Commands\Role\Delete\DeleteCommand;

final class DeleteRole extends Action
{
    public function __construct(
        private readonly CommandBus $commandBus
    ) {
    }

    public static function make(): DeleteAction
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->getAction();
    }

    public function getAction(): DeleteAction
    {
        return DeleteAction::make()
            ->modalHeading(fn (Role $record): string => Lang::get('role.pages.delete.title', [
                'name' => $record->name
            ]))
            ->using(function (Role $record): bool {
                return $this->commandBus->execute(new DeleteCommand($record));
            })
            ->successNotificationTitle(fn (Role $record): string => Lang::get('role.messages.delete.success', [
                'name' => $record->name
            ]));
    }
}
