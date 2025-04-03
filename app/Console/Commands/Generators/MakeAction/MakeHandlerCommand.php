<?php

declare(strict_types=1);

namespace App\Console\Commands\Generators\MakeAction;

use App\Console\Commands\Generators\GeneratorCommand;
use Illuminate\Support\Str;

final class MakeHandlerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make:action:handler {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Action Handler file based on a stub template.';

    protected function getStub(): string
    {
        return base_path().'/stubs/action.handler.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Actions';
    }

    private function getClassAction(): string
    {
        return Str::afterLast($this->getNameAction(), '/');
    }

    private function getNameAction(): string
    {
        return Str::before($this->argument('name'), 'Handler').'Action';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     */
    protected function replaceClass($stub, $name): string
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace(['DummyClassAction', '{{ class_action }}', '{{class_action}}'], $this->getClassAction(), $stub);
    }
}
