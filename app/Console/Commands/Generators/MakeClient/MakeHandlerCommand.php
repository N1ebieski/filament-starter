<?php

declare(strict_types=1);

namespace App\Console\Commands\Generators\MakeClient;

use App\Console\Commands\Generators\GeneratorCommand;
use Illuminate\Support\Str;

final class MakeHandlerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make:client:handler {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Client Handler file based on a stub template.';

    protected function getStub(): string
    {
        return base_path().'/stubs/client.handler.stub';
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

    private function getClassClient(): string
    {
        return Str::afterLast($this->getNameClient(), '/');
    }

    private function getClassResponse(): string
    {
        return Str::afterLast($this->getNameResponse(), '/');
    }

    private function getNameClient(): string
    {
        return Str::before($this->argument('name'), 'Handler').'Client';
    }

    private function getNameResponse(): string
    {
        return Str::before($this->argument('name'), 'Handler').'Response';
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

        return Str::of($stub)
            ->replace(['DummyClassClient', '{{ class_client }}', '{{class_client}}'], $this->getClassClient())
            ->replace(['DummyClassResponse', '{{ class_response }}', '{{class_response}}'], $this->getClassResponse())
            ->toString();
    }

    /**
     * Execute the console command.
     */
    public function handle(): ?bool
    {
        $baseHandle = parent::handle();

        $this->call(MakeResponseCommand::class, ['name' => $this->getNameResponse()]);

        return $baseHandle;
    }
}
