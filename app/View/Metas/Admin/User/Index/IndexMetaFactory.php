<?php

declare(strict_types=1);

namespace App\View\Metas\Admin\User\Index;

use App\Overrides\Illuminate\Contracts\Translation\Translator;
use App\View\Metas\Admin\AdminMetaFactory;
use App\View\Metas\Admin\MetaFactory;
use App\View\Metas\MetaInterface;

final class IndexMetaFactory extends MetaFactory
{
    public function __construct(
        private readonly AdminMetaFactory $adminMetaFactory,
        private readonly Translator $translator
    ) {}

    public function makeMeta(?int $page = null): MetaInterface
    {
        return $this->adminMetaFactory->makeMeta(
            title: $this->translator->string('user.pages.index.title'),
            page: $page,
            description: $this->translator->string('user.pages.index.description'),
            keywords: $this->translator->string('user.pages.index.keywords'),
        );
    }
}
