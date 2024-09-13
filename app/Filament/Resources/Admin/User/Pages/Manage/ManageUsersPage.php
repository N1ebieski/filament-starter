<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Pages\Manage;

use Override;
use App\Queries\Order;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Models\User\User;
use Filament\Tables\Table;
use App\Filament\Pages\HasMeta;
use App\View\Metas\MetaInterface;
use App\Queries\QueryBusInterface;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;
use App\Commands\CommandBusInterface;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\HasTableSearch;
use App\ValueObjects\Role\Name\DefaultName;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\HasTablePaginate;
use Filament\Tables\Actions\BulkActionGroup;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use App\Filament\Resources\Admin\Role\RoleResource;
use App\View\Metas\Admin\User\Index\IndexMetaFactory;
use App\Filament\Pages\MetaInterface as PageMetaInterface;
use App\Commands\User\EditStatusEmail\EditStatusEmailCommand;
use App\Filament\Resources\Admin\User\Actions\Edit\EditUserAction;
use App\Filament\Resources\Admin\User\Actions\Create\CreateUserAction;
use App\Filament\Resources\Admin\User\Actions\Delete\DeleteUserAction;
use App\Filament\Resources\Admin\User\Actions\DeleteMany\DeleteUsersAction;

final class ManageUsersPage extends ManageRecords implements PageMetaInterface
{
    use HasMeta;
    use HasTableSearch;
    use HasTablePaginate;

    protected static string $resource = RoleResource::class;

    private Role $role;

    private CommandBusInterface $commandBus;

    private QueryBusInterface $queryBus;

    private IndexMetaFactory $metaFactory;

    public function boot(
        Role $role,
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        IndexMetaFactory $metaFactory
    ): void {
        $this->role = $role;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
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

    #[Override]
    public function getMeta(): MetaInterface
    {
        return $this->metaFactory->makeMeta($this->getPage());
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateUserAction::make(
                $this->role->newQuery()
                    ->where('name', DefaultName::User->value)
                    ->when(!empty($this->getTableFilterState('roles')['values']), function (Builder $query): Builder {
                        //@phpstan-ignore-next-line
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
                return $this->queryBus->execute(GetByFilterQuery::from([
                    'select' => ['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at'],
                    'with' => ['roles:name']
                ]));
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
                        /** @var User|null */
                        $user = $guard->user();

                        return !$user?->can('toggleStatusEmail', $record);
                    })
                    ->getStateUsing(fn (User $record): bool => $record->status_email->getAsBool())
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
                    ->getOptionLabelFromRecordUsing(fn (Role $record) => $record->name->value)
                    ->query(function (Builder|User $query, array $data): Builder {
                        return $query->filterRoles($this->role->newQuery()->findMany($data['values']));
                    })
            ])
            ->actions([
                EditUserAction::make(),
                DeleteUserAction::make()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteUsersAction::make()
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(null)
            ->defaultSort(function (Builder|User $query): Builder {
                return $query->filterOrderBy(new OrderBy('id', Order::Desc));
            });
    }
}
