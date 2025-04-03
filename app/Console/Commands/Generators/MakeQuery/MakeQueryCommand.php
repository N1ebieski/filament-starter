<?php

declare(strict_types=1);

namespace App\Console\Commands\Generators\MakeQuery;

use App\Console\Commands\Generators\GeneratorCommand;
use Illuminate\Support\Str;

final class MakeQueryCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make:query {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Query file based on a stub template.';

    protected function getStub(): string
    {
        return base_path().'/stubs/query.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Queries';
    }

    private function getClassHandler(): string
    {
        return Str::afterLast($this->getNameHandler(), '/');
    }

    private function getNameHandler(): string
    {
        return Str::before($this->argument('name'), 'Query').'Handler';
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

        return str_replace(['DummyClassHandler', '{{ class_handler }}', '{{class_handler}}'], $this->getClassHandler(), $stub);
    }

    /**
     * Execute the console command.
     */
    public function handle(): ?bool
    {
        $baseHandle = parent::handle();

        $this->call(MakeHandlerCommand::class, ['name' => $this->getNameHandler()]);

        return $baseHandle;
    }
}
