<?php

declare(strict_types=1);

namespace App\View\Metas;

final class Meta implements MetaInterface
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $keywords,
        public readonly string $url,
        public readonly ?OpenGraphInterface $openGraph = null
    ) {}
}
