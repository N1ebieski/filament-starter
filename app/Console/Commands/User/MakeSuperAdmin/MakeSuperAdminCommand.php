<?php

declare(strict_types=1);

namespace App\Console\Commands\User\MakeSuperAdmin;

use App\Commands\CommandBusInterface;
use App\Commands\User\Create\CreateCommand;
use App\Models\Role\Role;
use App\Models\User\User;
use App\ValueObjects\Role\Name\DefaultName;
use Filament\Commands\MakeUserCommand;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Override;

final class MakeSuperAdminCommand extends MakeUserCommand
{
    protected $description = 'Create a new Filament superadmin';

    protected $signature = 'app:user:make:superadmin
                            {--name= : The name of the user}
                            {--email= : A valid and unique email address}
                            {--password= : The password for the user (min. 8 characters)}';

    private User $user;

    private Role $role;

    private CommandBusInterface $commandBus;

    public function __invoke(
        User $user,
        Role $role,
        CommandBusInterface $commandBus
    ): int {
        $this->user = $user;
        $this->role = $role;
        $this->commandBus = $commandBus;

        if (! $this->authorize()) {
            $this->components->error(Lang::get('superadmin.exist'));

            return self::INVALID;
        }

        return parent::handle();
    }

    private function authorize(): bool
    {
        return $this->user->newQuery()
            ->whereHas('roles', function (Builder $query) {
                return $query->where('name', DefaultName::SuperAdmin->value);
            })->count() === 0;
    }

    #[Override]
    protected function createUser(): Authenticatable
    {
        return $this->commandBus->execute(CreateCommand::from([
            ...$this->getUserData(),
            'roles' => $this->role->all(),
        ]));
    }

    #[Override]
    public function handle(): int
    {
        /** @var int */
        return App::call([$this, '__invoke']);
    }

    /**
     * @param  User  $user
     */
    protected function sendSuccessMessage(Authenticatable $user): void
    {
        $loginUrl = Filament::getLoginUrl();

        $this->components->info(Lang::get('superadmin.messages.create.success', [
            'user' => ($user->getAttribute('email') ?? $user->getAttribute('username')),
            'login_url' => $loginUrl,
        ]));
    }
}
