<?php

declare(strict_types=1);

namespace App\View\Metas;

final class Meta implements MetaInterface
{
    public function __construct(
        public string $title,
        public string $description,
        public string $keywords,
        public string $url,
        public ?OpenGraphInterface $openGraph = null
    ) {}
}
