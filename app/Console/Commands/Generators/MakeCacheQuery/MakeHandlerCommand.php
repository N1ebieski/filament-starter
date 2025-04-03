<?php

declare(strict_types=1);

namespace App\Console\Commands\Generators\MakeCacheQuery;

use App\Console\Commands\Generators\GeneratorCommand;
use Illuminate\Support\Str;

final class MakeHandlerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make:cachequery:handler {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new CacheQuery Handler file based on a stub template.';

    protected function getStub(): string
    {
        return base_path().'/stubs/cachequery.handler.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\CacheQueries';
    }

    private function getClassCacheQuery(): string
    {
        return Str::afterLast($this->getNameCacheQuery(), '/');
    }

    private function getNameCacheQuery(): string
    {
        return Str::before($this->argument('name'), 'Handler').'CacheCacheQuery';
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

        return str_replace(['DummyClassCacheQuery', '{{ class_cachequery }}', '{{class_cachequery}}'], $this->getClassCacheQuery(), $stub);
    }
}
