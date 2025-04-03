<?php

declare(strict_types=1);

namespace App\View\Metas\Admin;

use App\View\Metas\Meta;
use App\View\Metas\MetaInterface;
use App\View\Metas\OpenGraphInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use App\Overrides\Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Request;

class AdminMetaFactory
{
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
                $page > 1 ? Lang::string('pagination.page', ['page' => $page]) : '',
                Lang::string('admin.pages.panel.title'),
                Lang::string('app.pages.panel.title'),
            ])->filter()->implode(' - '),
            description: Collection::make([
                $description,
                $page > 1 ? Lang::string('pagination.page', ['page' => $page]) : '',
                Lang::string('admin.pages.panel.description'),
                Lang::string('app.pages.panel.description'),
            ])->filter()->implode('. '),
            keywords: mb_strtolower(Collection::make([
                $keywords,
                Lang::string('admin.pages.panel.keywords'),
                Lang::string('app.pages.panel.keywords'),
            ])->filter()->implode(', ')),
            url: $url ?? Config::string('app.url').Request::getRequestUri(),
            openGraph: $openGraph
        );
    }
}
