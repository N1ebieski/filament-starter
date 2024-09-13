<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Index;

use App\Models\User\User;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use App\Rules\ResourceWith\ResourceWithRule;
use Spatie\LaravelData\Attributes\MapInputName;
use App\Rules\ResourceSelect\ResourceSelectRule;

final class IndexRequest extends Request
{
    public int $page = 1;

    public ?string $search = null;

    public ?array $select = null;

    public ?array $with = null;

    #[MapInputName('paginate')]
    public int $result;

    public function __construct()
    {
        $this->result = Config::get('database.paginate');
    }

    public static function rules(): array
    {
        $paginate = Config::get('database.paginate');

        return [
            'page' => 'integer',
            'search' => 'bail|nullable|string|min:3|max:255',
            'select' => 'bail|nullable|array',
            'select.*' => [
                'bail',
                'string',
                new ResourceSelectRule(User::class),
            ],
            'with' => 'bail|nullable|array',
            'with.*' => [
                'bail',
                'string',
                new ResourceWithRule(User::class)
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
