<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Models\Role\Role;
use App\Models\User\User;
use App\Commands\CommandBus;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Lang;
use App\ValueObjects\Role\DefaultName;
use Filament\Commands\MakeUserCommand;
use App\Commands\User\Create\CreateCommand;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class MakeSuperAdminCommand extends MakeUserCommand
{
    protected $description = 'Create a new Filament superadmin';

    protected $signature = 'app:user:make:superadmin
                            {--name= : The name of the user}
                            {--email= : A valid and unique email address}
                            {--password= : The password for the user (min. 8 characters)}';

    public function __construct(
        private readonly User $user,
        private readonly Role $role,
        private readonly CommandBus $commandBus
    ) {
        parent::__construct();
    }

    private function authorize(): bool
    {
        return $this->user->newQuery()
            ->whereHas('roles', function (Builder $query) {
                return $query->where('name', DefaultName::SuperAdmin);
            })->count() === 0;
    }

    protected function createUser(): Authenticatable
    {
        return $this->commandBus->execute(new CreateCommand(
            ...$this->getUserData(),
            roles: $this->role->all()
        ));
    }

    public function handle(): int
    {
        if (!$this->authorize()) {
            $this->components->error(Lang::get('superadmin.exist'));

            exit;
        }

        return parent::handle();
    }

    protected function sendSuccessMessage(Authenticatable $user): void
    {
        $loginUrl = Filament::getLoginUrl();

        $this->components->info(Lang::get('superadmin.messages.create.success', [
            'user' => ($user->getAttribute('email') ?? $user->getAttribute('username')),
            'login_url' => $loginUrl
        ]));
    }
}
