<?php

function fcConfig($key)
{
    $config = [
        // Extra headers for CORS
        'headers' => [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => ['GET', 'POST'],
        ]
    ];

    return $config[$key];
}
