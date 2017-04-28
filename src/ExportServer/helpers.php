<?php

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\ConnectException;

function squash($inputArray)
{
    $outputArray = [];
    foreach ($inputArray as $input) {
        if (array_search($input, $outputArray) !== false) {
            continue;
        }

        $outputArray[] = $input;
    }

    return $outputArray;
}

function downloadImages($literals)
{
    $client = new Client([
        'timeout' => 10,
    ]);

    $processedLiterals = [];

    foreach ($literals as $literal) {
        $promises[$literal['url']] = $client->getAsync($literal['url']);
    }

    try {
        $results = Promise\settle($promises)->wait();
    } catch (ConnectException $e) {
        raise_error(404);
    }

    foreach ($literals as $literal) {
        if (!isset($results[$literal['url']]['value'])) {
            continue;
        }

        $response = $results[$literal['url']]['value'];

        $literal['stream'] = $response->getBody();
        $literal['mime_type'] = $response->getHeader('Content-Type')[0];

        $processedLiterals[] = $literal;
    }

    return $processedLiterals;
}

function base64Embed($literals)
{
    $processedLiterals = [];

    foreach ($literals as $literal) {
        $mimeType = $literal['mime_type'];
        $base64 = base64_encode((string) $literal['stream']);

        $literal['embed'] = 'data:' . $mimeType . ';base64,' . $base64;

        $processedLiterals[] = $literal;
    }

    return $processedLiterals;
}

function streamReplace($literals, $stream)
{
    $processedStream = $stream;

    foreach ($literals as $literal) {
        $processedStream = str_replace($literal['url'], $literal['embed'], $processedStream);
    }

    return $processedStream;
}
