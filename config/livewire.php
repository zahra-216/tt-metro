<?php

/*
| Only the temporary upload settings are changed here; everything else uses Livewire's own defaults.
| By default Livewire refuses files over 12 MB, which stopped bigger videos, and allows 60 uploads a minute,
| which is too few when many photos are added at once.
*/
return [
    'temporary_file_upload' => [
        'disk' => null,
        'rules' => ['required', 'file', 'max:51200'],   // 50 MB, the same limit as the upload fields
        'directory' => null,
        'middleware' => 'throttle:600,1',
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 20,   // minutes: slow connections and many files
        'cleanup' => true,
    ],
];
