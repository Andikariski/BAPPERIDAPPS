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
        'text' => 'BAPPERIDA PPS',
        'font_size' => 40,
        'font_path' => './fonts/Thrive.ttf', // null untuk menggunakan font default
        'color' => 'fff', // tanpa # untuk v3
        'stroke' => '000',
        'position' => [
            'x_offset' => 40,
            'y_offset' => 40,
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
        'max_width' => 1280,
        'max_height' => 720,
    ],

    'paths' => [
        'fonts' => public_path('fonts'),
        'storage' => 'app/public',
    ],
];
