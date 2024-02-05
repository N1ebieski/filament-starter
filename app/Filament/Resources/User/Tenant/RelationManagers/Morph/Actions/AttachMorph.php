<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Morph\Actions;

use App\Models\User\User;
use App\Commands\CommandBus;
use App\Models\Tenant\Tenant;
use App\Filament\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use App\Commands\Tenant\Edit\EditCommand;
use Filament\Tables\Actions\AttachAction;

final class AttachMorph extends Action
{
    public function __construct(
        private readonly User $user,
        private readonly CommandBus $commandBus
    ) {
    }

    public static function make(Tenant $tenant): AttachAction
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->getAction($tenant);
    }

    public function getAction(Tenant $tenant): AttachAction
    {
        return AttachAction::make()
            ->icon('heroicon-o-plus-circle')
            ->modalHeading(Lang::get('tenant.pages.morphs.attach.title'))
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->using(function (array $data) use ($tenant): Tenant {
                return $this->commandBus->execute(new EditCommand(
                    name: $tenant->name,
                    user: $tenant->user,
                    tenant: $tenant,
                    morphs: $tenant->morphs->push($this->user->find($data['recordId']))
                ));
            })
            ->successNotificationTitle(function (User $record): string {
                return Lang::get('tenant.messages.morphs.attach.success', [
                    'name' => $record->name
                ]);
            });
    }
}
