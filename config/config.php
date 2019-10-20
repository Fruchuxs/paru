<?php
return [
    'settings' => [
        "displayErrorDetails" => true,
        'addContentLengthHeader' => false,
        'error' => [
            'displayErrorDetails' => true,
            'logErrors' => true,
            'logErrorDetails' => true,
        ]
    ],
    'auth' => [
        'realm' => 'Protected',
        'path' => [
            '/backend'
        ],
        'users' => [
            'root' => 'root',
        ]
    ],
];
