<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Actions;

use App\Models\User\User;
use App\Filament\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use App\Commands\CommandBusInterface;
use Filament\Tables\Actions\DeleteAction;
use App\Commands\User\Delete\DeleteCommand;

final class DeleteUserAction extends Action
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
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
            ->modalHeading(fn (User $record): string => Lang::get('user.pages.delete.title', [
                'name' => $record->name
            ]))
            ->using(function (User $record): bool {
                return $this->commandBus->execute(new DeleteCommand($record));
            })
            ->successNotificationTitle(fn (User $record): string => Lang::get('user.messages.delete.success', [
                'name' => $record->name
            ]));
    }
}
