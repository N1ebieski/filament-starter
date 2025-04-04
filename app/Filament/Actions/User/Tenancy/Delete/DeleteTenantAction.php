<?php

declare(strict_types=1);

namespace App\Filament\Actions\User\Tenancy\Delete;

use App\Commands\CommandBusInterface;
use App\Commands\Tenant\Delete\DeleteCommand;
use App\Filament\Actions\Action as BaseAction;
use App\Models\Tenant\Tenant;
use App\Overrides\Illuminate\Support\Facades\Lang;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

final class DeleteTenantAction extends BaseAction
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public static function make(Tenant $tenant): DeleteAction
    {
        /** @var static */
        $static = App::make(self::class);

        return $static->makeAction($tenant);
    }

    public function makeAction(Tenant $tenant): DeleteAction
    {
        return DeleteAction::make()
            ->record($tenant)
            ->authorize(fn (Tenant $tenant): bool => Gate::allows('userDelete', $tenant))
            ->icon('heroicon-m-trash')
            ->form([
                TextInput::make('name')
                    ->label(Lang::string('tenant.name.label'))
                    ->required()
                    ->string()
                    ->minLength(3)
                    ->maxLength(255),
            ])
            ->modalHeading(fn (Tenant $record): string => Lang::string('tenant.pages.delete.title', [
                'name' => $record->name,
            ]))
            ->action(function (Tenant $record, array $data, Action $action): void {
                if ($data['name'] !== $record->name->value) {
                    Notification::make()
                        ->title(Lang::string('tenant.messages.delete.wrong_name'))
                        ->danger()
                        ->send();

                    $action->halt();
                }

                /** @var bool */
                $result = $this->commandBus->execute(new DeleteCommand($record));

                if (! $result) {
                    $action->failure();

                    return;
                }

                $action->success();
            })
            ->successNotificationTitle(fn (Tenant $record): string => Lang::string('tenant.messages.delete.success', [
                'name' => $record->name,
            ]))
            ->successRedirectUrl(Filament::getCurrentPanel()->getHomeUrl()); // @phpstan-ignore-line
    }
}
