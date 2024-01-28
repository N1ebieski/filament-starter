<?php

declare(strict_types=1);

namespace App\View\Metas\Web\User;

use Illuminate\Support\Collection as Collect;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Translation\Translator;

abstract class MetaFactory
{
    public function __construct(
        protected Config $config,
        protected Translator $translator,
        protected Collect $collect,
        protected UserMetaFactory $userMetaFactory
    ) {
    }
}
