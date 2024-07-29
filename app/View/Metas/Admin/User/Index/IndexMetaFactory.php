<?php

declare(strict_types=1);

namespace App\View\Metas\Admin\User\Index;

use App\View\Metas\MetaInterface;
use Illuminate\Support\Facades\Lang;
use App\View\Metas\Admin\MetaFactory;

final class IndexMetaFactory extends MetaFactory
{
    public function getMeta(?int $page = null): MetaInterface
    {
        return $this->adminMetaFactory->getMeta(
            title: Lang::get('user.pages.index.title'),
            page: $page,
            description: Lang::get('user.pages.index.description'),
            keywords: Lang::get('user.pages.index.keywords'),
        );
    }
}
