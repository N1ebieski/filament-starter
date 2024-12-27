<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Shared\Attributes\AttributesInterface;
use App\Models\Shared\Attributes\HasAttributes;
use App\Models\Shared\Attributes\HasCamelCaseAttributes;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel implements AttributesInterface
{
    use HasAttributes;
    use HasCamelCaseAttributes;
}
