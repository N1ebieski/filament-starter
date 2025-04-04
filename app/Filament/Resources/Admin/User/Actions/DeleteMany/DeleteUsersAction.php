<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Actions\DeleteMany;

use App\Commands\CommandBusInterface;
use App\Commands\User\DeleteMany\DeleteManyCommand;
use App\Filament\Actions\Action;
use App\Models\User\User;
use App\Overrides\Illuminate\Support\Facades\Lang;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

final class DeleteUsersAction extends Action
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
            ->authorize(fn (): bool => Gate::allows('adminDeleteAny', User::class))
            ->modalHeading(fn (Collection $records): string => Lang::choice('user.pages.delete_multi.title', $records->count(), [
                'number' => $records->count(),
            ]))
            ->using(function (Collection $records, Guard $guard): int {
                /** @var User|null */
                $user = $guard->user();

                $records = $records->filter(fn (User $user): bool => Gate::allows('adminDelete', $user));

                return $this->commandBus->execute(new DeleteManyCommand($records));
            })
            ->successNotificationTitle(fn (Collection $records): string => Lang::choice('user.messages.delete_multi.success', $records->count(), [
                'number' => $records->count(),
            ]));
    }
}
