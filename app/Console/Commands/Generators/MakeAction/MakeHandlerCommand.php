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
    public function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Actions';
    }

    private function getClassAction(): string
    {
        $classAction = Str::afterLast($this->getNameAction(), '/');

        return $classAction;
    }

    private function getNameAction(): string
    {
        $nameAction = Str::before($this->argument('name'), 'Handler').'Action';

        return $nameAction;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     */
    public function replaceClass($stub, $name): string
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace(['DummyClassAction', '{{ class_action }}', '{{class_action}}'], $this->getClassAction(), $stub);
    }
}
