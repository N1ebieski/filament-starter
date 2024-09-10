<?php

declare(strict_types=1);

namespace App\Commands;

interface CommandBusInterface
{
    /**
     * @return mixed
     */
    public function execute(Command $command);

    public function dispatch(Command $command): void;

    public function dispatchSync(Command $command): void;
}
