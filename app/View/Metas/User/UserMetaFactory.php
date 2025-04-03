<?php

declare(strict_types=1);

namespace App\View\Metas\User;

use App\View\Metas\Meta;
use App\View\Metas\MetaInterface;
use App\View\Metas\OpenGraphInterface;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as Collect;

final class UserMetaFactory
{
    public function __construct(
        private readonly Config $config,
        private readonly Translator $translator,
        private readonly Collect $collect,
        private readonly Request $request
    ) {}

    public function makeMeta(
        ?string $title = null,
        ?int $page = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $url = null,
        ?OpenGraphInterface $openGraph = null
    ): MetaInterface {
        return new Meta(
            title: $this->collect->make([
                $title,
                $page > 1 ? $this->translator->get('pagination.page', ['page' => $page]) : '',
                $this->translator->get('user.title'),
                $this->translator->get('app.title'),
            ])->filter()->implode(' - '),
            description: $this->collect->make([
                $description,
                $page > 1 ? $this->translator->get('pagination.page', ['page' => $page]) : '',
                $this->translator->get('user.title'),
                $this->translator->get('app.description'),
            ])->filter()->implode('. '),
            keywords: mb_strtolower($this->collect->make([
                $keywords,
                $this->translator->get('user.title'),
                $this->translator->get('app.keywords'),
            ])->filter()->implode(', ')),
            url: $url ?? $this->config->get('app.url').$this->request->getRequestUri(),
            openGraph: $openGraph
        );
    }
}
