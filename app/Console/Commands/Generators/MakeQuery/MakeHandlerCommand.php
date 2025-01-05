<?php

declare(strict_types=1);

namespace App\Console\Commands\Generators\MakeQuery;

use App\Console\Commands\Generators\GeneratorCommand;
use Illuminate\Support\Str;

final class MakeHandlerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make:query:handler {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Query Handler file based on a stub template.';

    protected function getStub(): string
    {
        return base_path().'/stubs/query.handler.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    public function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Queries';
    }

    private function getClassQuery(): string
    {
        $classQuery = Str::afterLast($this->getNameQuery(), '/');

        return $classQuery;
    }

    private function getNameQuery(): string
    {
        $nameQuery = Str::before($this->argument('name'), 'Handler').'Query';

        return $nameQuery;
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

        return str_replace(['DummyClassQuery', '{{ class_query }}', '{{class_query}}'], $this->getClassQuery(), $stub);
    }
}
