<?php

declare(strict_types=1);

namespace App\View\Metas\User\Account\Index;

use App\Overrides\Illuminate\Contracts\Translation\Translator;
use App\View\Metas\MetaInterface;
use App\View\Metas\User\MetaFactory;
use App\View\Metas\User\UserMetaFactory;

final class IndexMetaFactory extends MetaFactory
{
    public function __construct(
        private readonly UserMetaFactory $userMetaFactory,
        private readonly Translator $translator
    ) {}

    public function makeMeta(): MetaInterface
    {
        return $this->userMetaFactory->makeMeta(
            title: $this->translator->string('account.pages.index.title'),
            description: $this->translator->string('account.pages.index.description'),
            keywords: $this->translator->string('account.pages.index.keywords'),
        );
    }
}
