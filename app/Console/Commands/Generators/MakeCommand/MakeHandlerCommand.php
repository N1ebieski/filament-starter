<?php

declare(strict_types=1);

namespace App\Console\Commands\Generators\MakeCommand;

use App\Console\Commands\Generators\GeneratorCommand;
use Illuminate\Support\Str;

final class MakeHandlerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make:command:handler {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Command Handler file based on a stub template.';

    protected function getStub(): string
    {
        return base_path().'/stubs/command.handler.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    public function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Commands';
    }

    private function getClassCommand(): string
    {
        return Str::afterLast($this->getNameCommand(), '/');
    }

    private function getNameCommand(): string
    {
        return Str::before($this->argument('name'), 'Handler').'Command';
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

        return str_replace(['DummyClassCommand', '{{ class_command }}', '{{class_command}}'], $this->getClassCommand(), $stub);
    }
}
