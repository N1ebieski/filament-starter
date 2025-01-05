<?php

declare(strict_types=1);

namespace App\Console\Commands\Generators;

use Illuminate\Console\GeneratorCommand as BaseGeneratorCommand;
use Illuminate\Console\Prohibitable;

abstract class GeneratorCommand extends BaseGeneratorCommand
{
    use Prohibitable;

    public function handle(): ?bool
    {
        if ($this->isProhibited()) {
            return false;
        }

        return parent::handle();
    }
}
