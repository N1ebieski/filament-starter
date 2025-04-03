<?php

declare(strict_types=1);

namespace App\View\Metas;

interface OpenGraphInterface
{
    public string $title { get;
    }

    public string $description { get;
    }

    public string $type { get;
    }

    public string $image { get;
    }

    public string $url { get;
    }
}
