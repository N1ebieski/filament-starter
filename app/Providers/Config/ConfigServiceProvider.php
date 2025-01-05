<?php

declare(strict_types=1);

namespace App\Providers\Config;

use App\Console\Commands\Generators\GeneratorCommand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

/**
 * @property-read \Illuminate\Foundation\Application $app
 */
final class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $url = Config::get('app.url');

        /** @var string */
        $scheme = parse_url($url, PHP_URL_SCHEME);

        URL::forceScheme($scheme);
        URL::forceRootUrl($url);
    }

    public function boot(): void
    {
        Model::shouldBeStrict();
        DB::prohibitDestructiveCommands($this->app->isProduction());

        GeneratorCommand::prohibit($this->app->isProduction());
    }
}
