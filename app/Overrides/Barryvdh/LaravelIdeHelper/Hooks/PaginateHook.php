<?php

declare(strict_types=1);

namespace App\Overrides\Barryvdh\LaravelIdeHelper\Hooks;

use App\Queries\Result\Paginate;
use Illuminate\Database\Eloquent\Model;
use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;

final class PaginateHook implements ModelHookInterface
{
    public function run(ModelsCommand $command, Model $model): void
    {
        $command->unsetMethod('filterPaginate');
        $command->setMethod('filterPaginate', '\\' . LengthAwarePaginator::class, [
            '\\' . Paginate::class . ' $paginate'
        ]);
    }
}
