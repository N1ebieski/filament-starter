<?php

declare(strict_types=1);

namespace App\View\Metas\Admin\User;

use App\View\Metas\MetaInterface;
use Illuminate\Support\Facades\Lang;
use App\View\Metas\Admin\MetaFactory;

final class IndexMetaFactory extends MetaFactory
{
    public function make(?int $page = null): MetaInterface
    {
        return $this->adminMetaFactory->make(
            title: Lang::get('user.pages.index.title'),
            page: $page,
            description: Lang::get('user.pages.index.description'),
            keywords: Lang::get('user.pages.index.keywords'),
        );
    }
}
