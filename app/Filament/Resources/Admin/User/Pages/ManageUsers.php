<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Pages;

use App\Queries\Order;
use App\Queries\Search;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Models\User\User;
use App\Queries\QueryBus;
use Filament\Tables\Table;
use App\Commands\CommandBus;
use App\Queries\SearchFactory;
use App\Filament\Pages\HasMeta;
use App\ValueObjects\Role\Name;
use App\Filters\User\UserFilter;
use App\View\Metas\MetaInterface;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;
use App\ValueObjects\Role\DefaultName;
use App\ValueObjects\User\StatusEmail;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables\Actions\BulkActionGroup;
use App\View\Metas\Admin\User\IndexMetaFactory;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use App\Filament\Resources\Admin\Role\RoleResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\Resources\Admin\User\Actions\EditUser;
use App\Filament\Resources\Admin\User\Actions\CreateUser;
use App\Filament\Resources\Admin\User\Actions\DeleteUser;
use App\Filament\Pages\MetaInterface as PageMetaInterface;
use App\Filament\Resources\Admin\User\Actions\DeleteUsers;
use App\Commands\User\EditStatusEmail\EditStatusEmailCommand;

final class ManageUsers extends ManageRecords implements PageMetaInterface
{
    use HasMeta;

    protected static string $resource = RoleResource::class;

    private User $user;

    private Role $role;

    private CommandBus $commandBus;

    private SearchFactory $searchFactory;

    private QueryBus $queryBus;

    private IndexMetaFactory $metaFactory;

    public function boot(
        User $user,
        Role $role,
        CommandBus $commandBus,
        QueryBus $queryBus,
        SearchFactory $searchFactory,
        IndexMetaFactory $metaFactory
    ): void {
        $this->user = $user;
        $this->role = $role;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->searchFactory = $searchFactory;
        $this->metaFactory = $metaFactory;
    }

    public function getTitle(): string
    {
        return Lang::get('user.pages.index.title');
    }

    public static function getNavigationLabel(): string
    {
        return '';
    }

    public function getMeta(): MetaInterface
    {
        return $this->metaFactory->make($this->getPage());
    }

    private function getSearch(?string $search): ?Search
    {
        return !is_null($search) && mb_strlen($search) > 2 ?
            $this->searchFactory->make($search, $this->user) : null;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateUser::make(
                $this->role->newQuery()
                    ->where('name', new Name(DefaultName::User->value))
                    ->when(!empty($this->getTableFilterState('roles')['values']), function (Builder $query): Builder {
                        return $query->orWhereIn('id', $this->getTableFilterState('roles')['values']);
                    })
                    ->get()
            )
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->searchable(true)
            ->query(function (): Builder {
                return $this->queryBus->execute(new GetByFilterQuery(
                    filters: new UserFilter(
                        search: $this->getSearch($this->getTableSearch())
                    )
                ));
            })
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(query: function (Builder|User $query, string $direction): Builder {
                        return $query->filterOrderBy(new OrderBy('id', Order::from($direction)));
                    }),

                TextColumn::make('name')
                    ->label(Lang::get('user.name.label'))
                    ->grow(),

                TextColumn::make('email')
                    ->label(Lang::get('user.email.label')),

                TextColumn::make('roles.name')
                    ->label(Lang::get('user.roles.label')),

                TextColumn::make('email_verified_at')
                    ->label(Lang::get('user.email_verified_at.label'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(query: function (Builder|User $query, string $direction): Builder {
                        return $query->filterOrderBy(new OrderBy('email_verified_at', Order::from($direction)));
                    }),

                TextColumn::make('created_at')
                    ->label(Lang::get('default.created_at.label'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(query: function (Builder|User $query, string $direction): Builder {
                        return $query->filterOrderBy(new OrderBy('created_at', Order::from($direction)));
                    }),

                TextColumn::make('updated_at')
                    ->label(Lang::get('default.updated_at.label'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(query: function (Builder|User $query, string $direction): Builder {
                        return $query->filterOrderBy(new OrderBy('updated_at', Order::from($direction)));
                    }),

                ToggleColumn::make('status_email')
                    ->label(Lang::get('user.status_email.label'))
                    ->disabled(function (User $record, Guard $guard): bool {
                        return !$guard->user()?->can('toggleStatusEmail', $record);
                    })
                    ->getStateUsing(fn (User $record): bool => !is_null($record->email_verified_at))
                    ->updateStateUsing(function (User $record): User {
                        return $this->commandBus->execute(new EditStatusEmailCommand(
                            user: $record,
                            status: $record->status_email->toggle()
                        ));
                    })
                    ->afterStateUpdated(function (User $record, bool $state): void {
                        if (!$state) {
                            return;
                        }

                        Notification::make()
                            ->title(Lang::get('user.messages.toggle_status_email.verified.success', [
                                'email' => $record->email,
                                'name' => $record->name
                            ]))
                            ->success()
                            ->send();
                    })
            ])
            ->filters([
                SelectFilter::make('status_email')
                    ->label(Lang::get('user.status_email.label'))
                    ->options(StatusEmail::class)
                    ->query(function (Builder|User $query, array $data): Builder {
                        return $query->filterStatusEmail(StatusEmail::tryFrom($data['value'] ?? ''));
                    }),

                SelectFilter::make('roles')
                    ->label(Lang::get('user.roles.label'))
                    ->relationship($this->role->getTable(), 'name')
                    ->preload()
                    ->multiple()
                    ->query(function (Builder|User $query, array $data): Builder {
                        return $query->filterRoles($this->role->newQuery()->findMany($data['values']));
                    })
            ])
            ->actions([
                EditUser::make(),
                DeleteUser::make()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteUsers::make()
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(null)
            ->defaultSort(function (Builder|User $query): Builder {
                return $query->filterOrderBy(new OrderBy('id', Order::Desc));
            });
    }
}
