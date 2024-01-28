<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions;

use App\Models\Role\Role;
use App\Commands\CommandBus;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Filament\Tables\Actions\DeleteAction;
use App\Commands\Role\Delete\DeleteCommand;

final class DeleteRole
{
    public function __construct(
        private readonly CommandBus $commandBus
    ) {
    }

    public static function make(): DeleteAction
    {
        /** @var self */
        $static = App::make(static::class);

        return $static->get();
    }

    public function get(): DeleteAction
    {
        return DeleteAction::make()
            ->modalHeading(fn (Role $record) => Lang::get('role.pages.delete.title', ['name' => $record->name]))
            ->using(function (Role $record) {
                return $this->commandBus->execute(new DeleteCommand($record));
            })
            ->successNotificationTitle(fn (Role $record) => Lang::get('role.messages.delete', ['name' => $record->name]));
    }
}
