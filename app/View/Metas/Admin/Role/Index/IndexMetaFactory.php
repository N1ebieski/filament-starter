<?php

declare(strict_types=1);

namespace App\View\Metas\Admin\Role\Index;

use App\View\Metas\MetaInterface;
use Illuminate\Support\Facades\Lang;
use App\View\Metas\Admin\MetaFactory;

final class IndexMetaFactory extends MetaFactory
{
    public function make(?int $page = null): MetaInterface
    {
        return $this->adminMetaFactory->make(
            title: Lang::get('role.pages.index.title'),
            page: $page,
            description: Lang::get('role.pages.index.description'),
            keywords: Lang::get('role.pages.index.keywords'),
        );
    }
}
