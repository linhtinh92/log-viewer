<?php

return [
    'name' => 'Log Viewer',
    'pattern' => env('LOGVIEWER_PATTERN', '*.log'),
    'storage_path' => env('LOGVIEWER_STORAGE_PATH', storage_path('logs')),
];
