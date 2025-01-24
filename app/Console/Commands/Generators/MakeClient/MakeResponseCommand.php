<?php

declare(strict_types=1);

namespace App\Console\Commands\Generators\MakeClient;

use App\Console\Commands\Generators\GeneratorCommand;

final class MakeResponseCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make:client:response {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Client Response file based on a stub template.';

    protected function getStub(): string
    {
        return base_path().'/stubs/client.response.stub';
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
}
