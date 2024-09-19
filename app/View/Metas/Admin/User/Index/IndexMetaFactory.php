<?php

declare(strict_types=1);

namespace App\View\Metas\Admin\User\Index;

use App\View\Metas\Admin\AdminMetaFactory;
use App\View\Metas\Admin\MetaFactory;
use App\View\Metas\MetaInterface;
use Illuminate\Support\Facades\Lang;

final class IndexMetaFactory extends MetaFactory
{
    public function __construct(
        private readonly AdminMetaFactory $adminMetaFactory
    ) {}

    public function makeMeta(?int $page = null): MetaInterface
    {
        return $this->adminMetaFactory->makeMeta(
            title: Lang::get('user.pages.index.title'),
            page: $page,
            description: Lang::get('user.pages.index.description'),
            keywords: Lang::get('user.pages.index.keywords'),
        );
    }
}
