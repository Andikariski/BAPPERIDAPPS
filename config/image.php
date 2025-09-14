<?php

// File: config/image.php
return [
    'driver' => env('IMAGE_DRIVER', 'gd'), // 'gd' atau 'imagick'

    'quality' => [
        'default' => 90,
        'thumbnail' => 80,
        'resize' => 85,
    ],

    'watermark' => [
        'text' => env('APP_NAME', 'BAPPERIDA PPS'),
        'font_size' => 20,
        'font_path' => null, // null untuk menggunakan font default
        'color' => 'ffffff', // tanpa # untuk v3
        'position' => [
            'x_offset' => 10,
            'y_offset' => 10,
            'align' => 'right',
            'valign' => 'bottom',
        ],
        'angle' => 0,
    ],

    'thumbnail' => [
        'width' => 300,
        'height' => 200,
        'method' => 'cover', // 'cover', 'contain', 'scaleDown'
    ],

    'resize' => [
        'max_width' => 1920,
        'max_height' => 1080,
    ],

    'paths' => [
        'fonts' => public_path('fonts'),
        'storage' => 'app/public',
    ],
];
