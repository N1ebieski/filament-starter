<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Actions\Delete;

use App\Commands\CommandBusInterface;
use App\Commands\User\Delete\DeleteCommand;
use App\Filament\Actions\Action;
use App\Models\User\User;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

final class DeleteUserAction extends Action
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
            ->modalHeading(fn (User $record): string => Lang::get('user.pages.delete.title', [
                'name' => $record->name,
            ]))
            ->using(fn (User $record): bool => $this->commandBus->execute(new DeleteCommand($record)))
            ->successNotificationTitle(fn (User $record): string => Lang::get('user.messages.delete.success', [
                'name' => $record->name,
            ]));
    }
}
