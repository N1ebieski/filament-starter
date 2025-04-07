<?php

namespace App\Providers\App;

use App\Console\Commands\Generators\GeneratorCommand;
use App\Providers\ServiceProvider;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

/**
 * @property-read \Illuminate\Foundation\Application $app
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(DeferrableServiceProvider::class);

        $this->forceAppUrl();
    }

    private function forceAppUrl(): void
    {
        $url = Config::string('app.url');

        /** @var string $scheme */
        $scheme = parse_url((string) $url, PHP_URL_SCHEME);

        URL::forceScheme($scheme);
        URL::forceRootUrl($url);
    }

    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());
        DB::prohibitDestructiveCommands($this->app->isProduction());
        Date::use(CarbonImmutable::class);

        GeneratorCommand::prohibit($this->app->isProduction());
    }
}
