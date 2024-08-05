<?php

declare(strict_types=1);

namespace App\View\Metas\Web\User\Account\Index;

use App\View\Metas\MetaInterface;
use App\View\Metas\Web\User\MetaFactory;

final class IndexMetaFactory extends MetaFactory
{
    public function makeMeta(): MetaInterface
    {
        return $this->userMetaFactory->makeMeta(
            title: $this->translator->get('account.pages.index.title'),
            description: $this->translator->get('account.pages.index.description'),
            keywords: $this->translator->get('account.pages.index.keywords'),
        );
    }
}
