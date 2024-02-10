<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Actions;

use App\Models\User\User;
use App\Commands\CommandBus;
use App\Filament\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Commands\User\DeleteMulti\DeleteMultiCommand;

final class DeleteUsers extends Action
{
    public function __construct(
        private readonly CommandBus $commandBus
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
                return Lang::choice('user.pages.delete_multi.title', $records->count(), [
                    'number' => $records->count()
                ]);
            })
            ->using(function (Collection $records, Guard $guard): int {
                $records = $records->filter(fn (User $user): bool => $guard->user()?->can('delete', $user));

                return $this->commandBus->execute(new DeleteMultiCommand($records));
            })
            ->successNotificationTitle(function (Collection $records): string {
                return Lang::choice('user.messages.delete_multi.success', $records->count(), [
                    'number' => $records->count()
                ]);
            });
    }
}
