<?php

declare(strict_types=1);

namespace App\View\Metas\Admin;

abstract class MetaFactory
{
    public function __construct(
        protected AdminMetaFactory $adminMetaFactory
    ) {
    }
}
