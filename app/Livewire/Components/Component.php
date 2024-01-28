<?php

declare(strict_types=1);

namespace App\Livewire\Components;

use Livewire\Component as BaseComponent;
use App\Extends\Illuminate\Contracts\Auth\Guard;
use App\Extends\Illuminate\Contracts\Auth\Access\Gate;

/**
 * @property-read Guard $guard
 * @property-read Gate $gate
 * @property-read string $alias
 */
abstract class Component extends BaseComponent
{
}
