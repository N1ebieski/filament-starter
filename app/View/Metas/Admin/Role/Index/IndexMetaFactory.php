<?php

declare(strict_types=1);

namespace App\View\Metas\Admin\Role\Index;

use App\View\Metas\MetaInterface;
use Illuminate\Support\Facades\Lang;
use App\View\Metas\Admin\MetaFactory;
use App\View\Metas\Admin\AdminMetaFactory;

final class IndexMetaFactory extends MetaFactory
{
    public function __construct(
        private readonly AdminMetaFactory $adminMetaFactory
    ) {
    }

    public function makeMeta(?int $page = null): MetaInterface
    {
        return $this->adminMetaFactory->makeMeta(
            title: Lang::get('role.pages.index.title'),
            page: $page,
            description: Lang::get('role.pages.index.description'),
            keywords: Lang::get('role.pages.index.keywords'),
        );
    }
}
