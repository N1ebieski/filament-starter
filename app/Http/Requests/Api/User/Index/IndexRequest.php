<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Index;

use App\Models\User\User;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use App\Rules\AllowedSort\AllowedSortRule;
use App\Rules\AllowedWith\AllowedWithRule;
use App\Rules\AllowedSelect\AllowedSelectRule;
use App\ValueObjects\User\StatusEmail\StatusEmail;

final class IndexRequest extends Request
{
    public int $page = 1;

    public ?string $search = null;

    public ?array $select = null;

    public ?array $with = null;

    public ?string $status_email = null;

    public ?array $roles = null;

    public ?array $tenants = null;

    public ?string $orderby = null;

    public int $paginate;

    public function __construct()
    {
        $this->paginate = Config::get('database.paginate');
    }

    public static function rules(User $user): array
    {
        $paginate = Config::get('database.paginate');

        return [
            'page' => 'integer',
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
                new AllowedWithRule($user)
            ],
            'status_email' => [
                'bail',
                'string',
                Rule::enum(StatusEmail::class)
            ],
            'roles' => 'bail|nullable|array',
            'roles.*' => [
                'bail',
                'int',
                Rule::exists('roles', 'id')
            ],
            'tenants' => 'bail|nullable|array',
            'tenants.*' => [
                'bail',
                'int',
                Rule::exists('tenants', 'id')
            ],
            'orderby' => [
                'bail',
                'string',
                'nullable',
                new AllowedSortRule($user)
            ],
            'paginate' => [
                'bail',
                'nullable',
                'integer',
                Rule::in([$paginate, ($paginate * 2), ($paginate * 4)])
            ]
        ];
    }
}
