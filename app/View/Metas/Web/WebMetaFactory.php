<?php

declare(strict_types=1);

namespace App\View\Metas\Web;

use App\Overrides\Illuminate\Contracts\Config\Repository as Config;
use App\Overrides\Illuminate\Contracts\Translation\Translator;
use App\View\Metas\Meta;
use App\View\Metas\MetaInterface;
use App\View\Metas\OpenGraphInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

final class WebMetaFactory
{
    public function __construct(
        private readonly Config $config,
        private readonly Translator $translator,
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
            title: Collection::make([
                $title,
                $page > 1 ? $this->translator->string('pagination.page', ['page' => $page]) : '',
                $this->translator->string('app.title'),
            ])->filter()->implode(' - '),
            description: Collection::make([
                $description,
                $page > 1 ? $this->translator->string('pagination.page', ['page' => $page]) : '',
                $this->translator->string('app.description'),
            ])->filter()->implode('. '),
            keywords: mb_strtolower(Collection::make([
                $keywords,
                $this->translator->string('app.keywords'),
            ])->filter()->implode(', ')),
            url: $url ?? $this->config->string('app.url').$this->request->getRequestUri(),
            openGraph: $openGraph
        );
    }
}
