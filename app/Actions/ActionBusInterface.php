<?php

declare(strict_types=1);

namespace App\Actions;

interface ActionBusInterface
{
    public function execute(Action $action): mixed;
}
