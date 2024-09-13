<?php

declare(strict_types=1);

namespace App\View\Metas\Admin\Home\Index;

use App\View\Metas\MetaInterface;
use App\View\Metas\Admin\MetaFactory;
use App\View\Metas\Admin\AdminMetaFactory;

final class IndexMetaFactory extends MetaFactory
{
    public function __construct(
        private readonly AdminMetaFactory $adminMetaFactory
    ) {
    }

    public function makeMeta(): MetaInterface
    {
        return $this->adminMetaFactory->makeMeta();
    }
}
