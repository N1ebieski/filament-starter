<?php

declare(strict_types=1);

namespace App\Overrides\Barryvdh\LaravelIdeHelper\Hooks;

use App\Queries\Shared\Result\Drivers\Paginate\Paginate;
use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class PaginateHook implements ModelHookInterface
{
    public function run(ModelsCommand $command, Model $model): void
    {
        $command->unsetMethod('filterPaginate');
        $command->setMethod('filterPaginate', '\\'.LengthAwarePaginator::class, [
            '\\'.Paginate::class.' $paginate',
        ]);
    }
}
