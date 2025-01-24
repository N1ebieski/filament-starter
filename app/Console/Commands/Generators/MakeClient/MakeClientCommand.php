<?php

declare(strict_types=1);

namespace App\Console\Commands\Generators\MakeClient;

use App\Console\Commands\Generators\GeneratorCommand;
use Illuminate\Support\Str;

final class MakeClientCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make:client {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Client file based on a stub template.';

    protected function getStub(): string
    {
        return base_path().'/stubs/client.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    public function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Http\Clients';
    }

    private function getClassHandler(): string
    {
        $classHandler = Str::afterLast($this->getNameHandler(), '/');

        return $classHandler;
    }

    private function getNameHandler(): string
    {
        $nameHandler = Str::before($this->argument('name'), 'Client').'Handler';

        return $nameHandler;
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
