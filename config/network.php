<?php

return [
    'trusted_proxies' => count($proxies = explode(',', (string) env('TRUSTED_PROXIES', ''))) > 1 ?
        $proxies : $proxies[0],
];
