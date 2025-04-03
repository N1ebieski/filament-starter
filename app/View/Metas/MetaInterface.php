<?php

declare(strict_types=1);

namespace App\View\Metas;

interface MetaInterface
{
    public string $title { get;
    }

    public string $description { get;
    }

    public string $keywords { get;
    }

    public string $url { get;
    }

    public ?OpenGraphInterface $openGraph { get;
    }
}
