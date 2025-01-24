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
        $classClient = Str::afterLast($this->getNameClient(), '/');

        return $classClient;
    }

    private function getClassResponse(): string
    {
        $classResponse = Str::afterLast($this->getNameResponse(), '/');

        return $classResponse;
    }

    private function getNameClient(): string
    {
        $nameClient = Str::before($this->argument('name'), 'Handler').'Client';

        return $nameClient;
    }

    private function getNameResponse(): string
    {
        $nameResponse = Str::before($this->argument('name'), 'Handler').'Response';

        return $nameResponse;
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

        $stub = Str::of($stub)
            ->replace(['DummyClassClient', '{{ class_client }}', '{{class_client}}'], $this->getClassClient())
            ->replace(['DummyClassResponse', '{{ class_response }}', '{{class_response}}'], $this->getClassResponse())
            ->toString();

        return $stub;
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
