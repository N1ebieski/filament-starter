<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Pages;

use Filament\Tables;
use Filament\Actions;
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
use App\Filters\Role\RoleFilter;
use App\Filters\User\UserFilter;
use App\View\Metas\MetaInterface;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use App\Models\Permission\Permission;
use Filament\Forms\Components\Select;
use App\ValueObjects\Role\DefaultName;
use App\ValueObjects\User\StatusEmail;
use App\Commands\Role\Edit\EditCommand;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Validation\Rules\Exists;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Facades\FilamentView;
use App\Commands\Role\Create\CreateCommand;
use App\Commands\Role\Delete\DeleteCommand;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables\Columns\TextInputColumn;
use App\View\Metas\Admin\User\IndexMetaFactory;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use App\Filament\Resources\Admin\Role\RoleResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View as ViewContract;
use App\Commands\Role\DeleteMulti\DeleteMultiCommand;
use App\Filament\Resources\Admin\User\Actions\CreateUser;
use App\Filament\Pages\MetaInterface as PageMetaInterface;
use App\Commands\User\EditStatusEmail\EditStatusEmailCommand;

class ManageUsers extends ManageRecords implements PageMetaInterface
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
                    ->where('name', new Name(DefaultName::USER->value))
                    ->when(!empty($this->getTableFilterState('roles')['values']), function (Builder $query) {
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
            ->query(function () {
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
                    ->disabled(fn (User $record, Guard $guard) => !$guard->user()->can('toggleStatusEmail', $record))
                    ->getStateUsing(fn (User $record) => !is_null($record->email_verified_at))
                    ->updateStateUsing(function (User $record) {
                        return $this->commandBus->execute(new EditStatusEmailCommand(
                            user: $record,
                            status: $record->status_email->toggle()
                        ));
                    })
            ])
            ->filters([
                SelectFilter::make('status_email')
                    ->label(Lang::get('user.status_email.label'))
                    ->options(StatusEmail::class)
                    ->query(fn (Builder|User $query, array $data) => $query->filterStatusEmail(StatusEmail::tryFrom($data['value'] ?? ''))),

                SelectFilter::make('roles')
                    ->label(Lang::get('user.roles.label'))
                    ->relationship($this->role->getTable(), 'name')
                    ->preload()
                    ->multiple()
                    ->query(fn (Builder|User $query, array $data) => $query->filterRoles($this->role->newQuery()->findMany($data['values'])))
            ])
            // ->actions([
            //     Tables\Actions\EditAction::make()
            //         ->modalHeading(fn (Role $record) => Lang::get('role.pages.edit.title', ['name' => $record->name->value]))
            //         ->mutateRecordDataUsing(function (array $data, Role $record): array {
            //             $data['name'] = $data['name']->value;
            //             $data['permissions'] = $record->permissions->pluck('id')->toArray();

            //             return $data;
            //         })
            //         ->form([
            //             TextInput::make('name')
            //                 ->label(Lang::get('role.name.label'))
            //                 ->required()
            //                 ->disabled(fn (Role $record) => $record->name->isDefault())
            //                 ->string()
            //                 ->minLength(3)
            //                 ->maxLength(255)
            //                 ->unique($this->role->getTable(), 'name', ignoreRecord: true),

            //             Select::make('permissions')
            //                 ->label(Lang::get('role.permissions.label'))
            //                 ->options(fn (Role $record) => $this->getGroupedPermissions($record)->toArray())
            //                 ->searchable()
            //                 ->multiple()
            //                 ->required()
            //                 ->exists($this->permission->getTable(), 'id', function (Exists $rule, Role $record) {
            //                     return $rule->when(
            //                         $record->name->isEqualsDefault(DefaultName::USER),
            //                         function (Exists $rule) {
            //                             return $rule->where(function (Builder $builder) {
            //                                 return $builder->where('name', 'like', 'web.%')
            //                                     ->orWhere('name', 'like', 'api.%');
            //                             });
            //                         }
            //                     )
            //                     ->when(
            //                         $record->name->isEqualsDefault(DefaultName::API),
            //                         function (Exists $rule) {
            //                             return $rule->where(function (Builder $builder) {
            //                                 return $builder->where('name', 'like', 'api.%');
            //                             });
            //                         }
            //                     );
            //                 })
            //         ])
            //         ->stickyModalFooter()
            //         ->closeModalByClickingAway(false)
            //         ->using(function (array $data, Role $record): Role {
            //             return $this->commandBus->execute(new EditCommand(
            //                 role: $record,
            //                 name: $data['name'],
            //                 permissions: $this->permission->newQuery()->findMany($data['permissions'])
            //             ));
            //         })
            //         ->successNotificationTitle(fn (Role $record) => Lang::get('role.messages.edit', ['name' => $record->name])),

            //         Tables\Actions\DeleteAction::make()
            //             ->modalHeading(fn (Role $record) => Lang::get('role.pages.delete.title', ['name' => $record->name]))
            //             ->using(function (Role $record) {
            //                 return $this->commandBus->execute(new DeleteCommand($record));
            //             })
            //             ->successNotificationTitle(fn (Role $record) => Lang::get('role.messages.delete', ['name' => $record->name])),
            // ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make()
            //             ->modalHeading(fn (Collection $records) => Lang::choice('role.pages.delete_multi.title', $records->count(), ['number' => $records->count()]))
            //             ->using(function (Collection $records, Guard $guard) {
            //                 $records = $records->filter(fn (Role $role) => $guard->user()->can('delete', $role));

            //                 return $this->commandBus->execute(new DeleteMultiCommand($records));
            //             })
            //             ->successNotificationTitle(fn (Collection $records) => Lang::choice('role.messages.delete_multi', $records->count(), ['number' => $records->count()])),
            //     ]),
            // ])
            ->recordUrl(null)
            ->recordAction(null)
            ->defaultSort(function (Builder|User $query): Builder {
                return $query->filterOrderBy(new OrderBy('id', Order::DESC));
            });
    }
}
