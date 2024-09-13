<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Index;

use App\Models\User\User;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use App\Support\Query\Sorts\SortsHelper;
use App\Rules\ResourceWith\ResourceWithRule;
use Spatie\LaravelData\Attributes\MapInputName;
use App\Rules\ResourceSelect\ResourceSelectRule;

final class IndexRequest extends Request
{
    public int $page = 1;

    #[MapInputName('search')]
    public ?string $searchBy = null;

    public ?array $select = null;

    public ?array $with = null;

    public ?array $roles = null;

    public ?array $tenants = null;

    #[MapInputName('orderby')]
    public ?string $orderBy = null;

    #[MapInputName('paginate')]
    public int $result;

    public function __construct()
    {
        $this->result = Config::get('database.paginate');
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
                new ResourceSelectRule($user),
            ],
            'with' => 'bail|nullable|array',
            'with.*' => [
                'bail',
                'string',
                new ResourceWithRule($user)
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
                Rule::in(SortsHelper::getAttributesWithOrder($user->getSortable()))
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
