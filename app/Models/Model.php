<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Shared\Attributes\HasAttributes;
use App\Models\Shared\Attributes\HasCamelCaseAttributes;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use HasAttributes;
    use HasCamelCaseAttributes;
}
