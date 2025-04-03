<?php

declare(strict_types=1);

namespace App\View\Metas\Admin\Role\Index;

use App\Overrides\Illuminate\Support\Facades\Lang;
use App\View\Metas\Admin\AdminMetaFactory;
use App\View\Metas\Admin\MetaFactory;
use App\View\Metas\MetaInterface;

final readonly class IndexMetaFactory extends MetaFactory
{
    public function __construct(
        private AdminMetaFactory $adminMetaFactory
    ) {}

    public function makeMeta(?int $page = null): MetaInterface
    {
        return $this->adminMetaFactory->makeMeta(
            title: Lang::string('role.pages.index.title'),
            page: $page,
            description: Lang::string('role.pages.index.description'),
            keywords: Lang::string('role.pages.index.keywords'),
        );
    }
}
