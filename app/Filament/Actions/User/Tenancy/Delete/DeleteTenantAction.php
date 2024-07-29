<?php

declare(strict_types=1);

namespace App\Filament\Actions\User\Tenancy\Delete;

use Filament\Actions\Action;
use App\Models\Tenant\Tenant;
use Filament\Facades\Filament;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;
use App\Commands\CommandBusInterface;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Commands\Tenant\Delete\DeleteCommand;
use App\Filament\Actions\Action as BaseAction;

final class DeleteTenantAction extends BaseAction
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public static function make(Tenant $tenant): DeleteAction
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->getAction($tenant);
    }

    public function getAction(Tenant $tenant): DeleteAction
    {
        return DeleteAction::make()
            ->record($tenant)
            ->icon('heroicon-m-trash')
            ->hidden(function (Tenant $record, Guard $guard) {
                /** @var User|null */
                $user = $guard->user();

                return !$user?->can('delete', $record);
            })
            ->form([
                TextInput::make('name')
                    ->label(Lang::get('tenant.name.label'))
                    ->required()
                    ->string()
                    ->minLength(3)
                    ->maxLength(255),
            ])
            ->modalHeading(fn (Tenant $record): string => Lang::get('tenant.pages.delete.title', [
                'name' => $record->name
            ]))
            ->action(function (Tenant $record, array $data, Action $action): void {
                if ($data['name'] !== $record->name) {
                    Notification::make()
                        ->title(Lang::get('tenant.messages.delete.wrong_name'))
                        ->danger()
                        ->send();

                    $action->halt();
                }

                /** @var bool */
                $result = $this->commandBus->execute(new DeleteCommand($record));

                if (!$result) {
                    $action->failure();

                    return;
                }

                $action->success();
            })
            ->successNotificationTitle(fn (Tenant $record): string => Lang::get('tenant.messages.delete.success', [
                'name' => $record->name
            ]))
            ->successRedirectUrl(Filament::getCurrentPanel()->getHomeUrl());
    }
}
