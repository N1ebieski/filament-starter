<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Index;

use App\Http\Requests\Request;
use App\Models\User\User;
use App\Rules\AllowedSelect\AllowedSelectRule;
use App\Rules\AllowedSort\AllowedSortRule;
use App\Rules\AllowedWith\AllowedWithRule;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class IndexRequest extends Request
{
    public readonly int $paginate;

    public function __construct(
        public readonly int $page = 1,
        public readonly ?string $search = null,
        public readonly ?array $select = null,
        public readonly ?array $with = null,
        public readonly ?string $statusEmail = null,
        public readonly ?array $roles = null,
        public readonly ?array $tenants = null,
        public readonly ?string $orderby = null,
        ?int $paginate = null
    ) {
        $this->paginate = $paginate ?? Config::integer('database.paginate');
    }

    public static function rules(User $user): array
    {
        $paginate = Config::integer('database.paginate');

        return [
            'page' => 'bail|integer',
            'search' => 'bail|nullable|string|min:3|max:255',
            'select' => 'bail|nullable|array',
            'select.*' => [
                'bail',
                'string',
                new AllowedSelectRule($user),
            ],
            'with' => 'bail|nullable|array',
            'with.*' => [
                'bail',
                'string',
                new AllowedWithRule($user),
            ],
            'status_email' => [
                'bail',
                'string',
                Rule::enum(StatusEmail::class),
            ],
            'roles' => 'bail|nullable|array',
            'roles.*' => [
                'bail',
                'int',
                Rule::exists('roles', 'id'),
            ],
            'tenants' => 'bail|nullable|array',
            'tenants.*' => [
                'bail',
                'int',
                Rule::exists('tenants', 'id'),
            ],
            'orderby' => [
                'bail',
                'string',
                'nullable',
                new AllowedSortRule($user),
            ],
            'paginate' => [
                'bail',
                'nullable',
                'integer',
                Rule::in([$paginate, ($paginate * 2), ($paginate * 4)]),
            ],
        ];
    }
}
