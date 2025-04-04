<?php

return [
    App\Providers\App\AppServiceProvider::class,
    App\Providers\Config\ConfigServiceProvider::class,
    App\Providers\Auth\AuthServiceProvider::class,
    App\Providers\Event\EventServiceProvider::class,
    App\Providers\Translator\TranslatorServiceProvider::class,
    App\Providers\Action\ActionServiceProvider::class,
    App\Providers\Bus\BusServiceProvider::class,
    App\Providers\Cache\CacheServiceProvider::class,
    App\Providers\Client\ClientServiceProvider::class,
    App\Providers\Command\CommandServiceProvider::class,
    App\Providers\Filesystem\FilesystemServiceProvider::class,
    App\Providers\Logger\LoggerServiceProvider::class,
    App\Providers\Pipeline\PipelineServiceProvider::class,
    App\Providers\Query\QueryServiceProvider::class,
    App\Providers\Tenant\TenantServiceProvider::class,
    App\Providers\Filament\Filament\FilamentServiceProvider::class,
    App\Providers\Filament\WebPanel\WebPanelServiceProvider::class,
    App\Providers\Filament\AdminPanel\AdminPanelServiceProvider::class,
    App\Providers\Filament\Spotlight\SpotlightServiceProvider::class,
    App\Providers\Spotlight\SpotlightServiceProvider::class,
    App\Providers\LaravelPWA\LaravelPWAServiceProvider::class,
];
