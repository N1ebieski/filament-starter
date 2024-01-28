<?php

declare(strict_types=1);

namespace App\View\Metas\Admin;

use App\View\Metas\Meta;
use App\View\Metas\MetaInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use App\View\Metas\OpenGraphInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class AdminMetaFactory
{
    public function make(
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
                $page > 1 ? Lang::get('pagination.page', ['page' => $page]) : '',
                Lang::get('admin.pages.panel.title'),
                Lang::get('app.pages.panel.title')
            ])->filter()->implode(' - '),
            description: Collection::make([
                $description,
                $page > 1 ? Lang::get('pagination.page', ['page' => $page]) : '',
                Lang::get('admin.pages.panel.description'),
                Lang::get('app.pages.panel.description')
            ])->filter()->implode('. '),
            keywords: mb_strtolower(Collection::make([
                $keywords,
                Lang::get('admin.pages.panel.keywords'),
                Lang::get('app.pages.panel.keywords')
            ])->filter()->implode(', ')),
            url: $url ?? Config::get('app.url') . Request::getRequestUri(),
            openGraph: $openGraph
        );
    }
}
