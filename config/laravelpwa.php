<?php

return [
    'name' => 'LaravelPWA',
    'manifest' => [
        'name' => env('APP_NAME', 'My PWA App'),
        'short_name' => env('APP_SHORT_NAME', 'PWA'),
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation' => 'any',
        'status_bar' => 'black',
        'icons' => [
            [
                'path' => '/images/icons/pwa-64x64.png',
                'sizes' => '64x64',
                'purpose' => 'any'
            ],
            [
                'path' => '/images/icons/pwa-192x192.png',
                'sizes' => '192x192',
                'purpose' => 'any'
            ],
            [
                'path' => '/images/icons/pwa-512x512.png',
                'sizes' => '512x512',
                'purpose' => 'any'
            ],
            [
                'path' => '/images/icons/maskable-icon-512x512.png',
                'sizes' => '512x512',
                'purpose' => 'maskable'
            ],
            [
                'path' => '/images/icons/apple-touch-icon-180x180.png',
                'sizes' => '180x180',
                'purpose' => 'any'
            ]
        ],
        'splash' => [
            '1136x640' => '/images/splash/apple-splash-landscape-1136x640.png',
            '1334x750' => '/images/splash/apple-splash-landscape-1334x750.png',
            '1792x828' => '/images/splash/apple-splash-landscape-1792x828.png',
            '2048x1536' => '/images/splash/apple-splash-landscape-2048x1536.png',
            '2160x1620' => '/images/splash/apple-splash-landscape-2160x1620.png',
            '2208x1242' => '/images/splash/apple-splash-landscape-2208x1242.png',
            '2224x1668' => '/images/splash/apple-splash-landscape-2224x1668.png',
            '2388x1668' => '/images/splash/apple-splash-landscape-2388x1668.png',
            '2436x1125' => '/images/splash/apple-splash-landscape-2436x1125.png',
            '2532x1170' => '/images/splash/apple-splash-landscape-2532x1170.png',
            '2556x1179' => '/images/splash/apple-splash-landscape-2556x1179.png',
            '2688x1242' => '/images/splash/apple-splash-landscape-2688x1242.png',
            '2732x2048' => '/images/splash/apple-splash-landscape-2732x2048.png',
            '2778x1284' => '/images/splash/apple-splash-landscape-2778x1284.png',
            '2796x1290' => '/images/splash/apple-splash-landscape-2796x1290.png',

            '640x1136' => '/images/splash/apple-splash-portrait-640x1136.png',
            '750x1334' => '/images/splash/apple-splash-portrait-750x1334.png',
            '828x1792' => '/images/splash/apple-splash-portrait-828x1792.png',
            '1125x2436' => '/images/splash/apple-splash-portrait-1125x2436.png',
            '1170x2532' => '/images/splash/apple-splash-portrait-1170x2532.png',
            '1179x2556' => '/images/splash/apple-splash-portrait-1179x2556.png',
            '1242x2208' => '/images/splash/apple-splash-portrait-1242x2208.png',
            '1242x2688' => '/images/splash/apple-splash-portrait-1242x2688.png',
            '1284x2778' => '/images/splash/apple-splash-portrait-1284x2778.png',
            '1290x2796' => '/images/splash/apple-splash-portrait-1290x2796.png',
            '1536x2048' => '/images/splash/apple-splash-portrait-1536x2048.png',
            '1620x2160' => '/images/splash/apple-splash-portrait-1620x2160.png',
            '1668x2224' => '/images/splash/apple-splash-portrait-1668x2224.png',
            '1668x2388' => '/images/splash/apple-splash-portrait-1668x2388.png',
            '2048x2732' => '/images/splash/apple-splash-portrait-2048x2732.png',
        ],
        'shortcuts' => [],
        'custom' => [
            "screenshots" => [
                [
                    "src" => "/images/screenshots/screenshot-540x720.png",
                    "type" => "image/png",
                    "sizes" => "540x720",
                    "form_factor" => "narrow"
                ],
                [
                    "src" => "/images/screenshots/screenshot-720x540.png",
                    "type" => "image/png",
                    "sizes" => "720x540",
                    "form_factor" => "wide"
                ]
            ]
        ]
    ]
];
