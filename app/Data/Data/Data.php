<?php

declare(strict_types=1);

namespace App\Data\Data;

use App\Data\Data\Payload\Payload;
use Illuminate\Support\Facades\App;
use Spatie\LaravelData\Data as BaseData;
use Illuminate\Contracts\Support\Arrayable;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline;
use App\Overrides\Illuminate\Contracts\Container\Container;
use App\Data\Data\Payload\ObjectDefaults\ObjectDefaultsHandler;
use App\Data\Data\Payload\OnlyParameters\OnlyParametersHandler;
use App\Data\Data\Payload\EnsureIsFlatArray\EnsureIsFlatArrayHandler;

/**
 * @method self only(string ...$only)
 */
abstract class Data extends BaseData implements Arrayable
{
    /**
     * \Spatie\LaravelData\Data doesn't allow to have objects as constructor's default values.
     * This method overrides spatie's factory to bring this feature.
     */
    public static function from(mixed ...$payloads): static
    {
        /** @var Pipeline */
        $pipeline = App::make(Pipeline::class);

        /** @var Container */
        $container = App::make(Container::class);

        /** @var array<HandlerInterface> */
        $handlers = $container->makeMany([
            EnsureIsFlatArrayHandler::class,
            OnlyParametersHandler::class,
            ObjectDefaultsHandler::class
        ]);

        /** @var Payload */
        $payload = $pipeline->through(...$handlers)->process(new Payload(
            payloads: $payloads,
            className: static::class
        ));

        return parent::from(...[$payload->payloads]);
    }
}
