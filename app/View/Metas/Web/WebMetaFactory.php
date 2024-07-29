<?php

declare(strict_types=1);

namespace App\View\Metas\Web;

use App\View\Metas\Meta;
use Illuminate\Http\Request;
use App\View\Metas\MetaInterface;
use App\View\Metas\OpenGraphInterface;
use Illuminate\Support\Collection as Collect;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Config\Repository as Config;

final class WebMetaFactory
{
    public function __construct(
        protected Config $config,
        protected Translator $translator,
        protected Collect $collect,
        protected Request $request
    ) {
    }

    public function getMeta(
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
                $this->translator->get('app.title')
            ])->filter()->implode(' - '),
            description: $this->collect->make([
                $description,
                $page > 1 ? $this->translator->get('pagination.page', ['page' => $page]) : '',
                $this->translator->get('app.description')
            ])->filter()->implode('. '),
            keywords: mb_strtolower($this->collect->make([
                $keywords,
                $this->translator->get('app.keywords')
            ])->filter()->implode(', ')),
            url: $url ?? $this->config->get('app.url') . $this->request->getRequestUri(),
            openGraph: $openGraph
        );
    }
}
