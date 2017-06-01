<?php

namespace FCExportHandler\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\ConnectException;

class EmbedderService
{
    protected $imageLinkRegex = '/([a-z\-_0-9\/\:\.]*\.(jpg|jpeg|png|gif))/i';

    public function serve($stream)
    {
        preg_match_all($this->imageLinkRegex, $stream, $matches);

        $matches = $matches[0];

        if (empty($matches)) {
            return $stream;
        }

        $imageToBase64ConversionLiteral = array_map(function($match) {
            return [
              'url' => $match
            ];
        }, $matches);

        $imageToBase64ConversionLiteral = $this->squash($imageToBase64ConversionLiteral);

        $imageToBase64ConversionLiteral = $this->downloadImages($imageToBase64ConversionLiteral);

        $imageToBase64ConversionLiteral = $this->base64Embed($imageToBase64ConversionLiteral);

        $processedStream = $this->streamReplace($imageToBase64ConversionLiteral, $stream);

        return $processedStream;
    }

    public function squash($inputArray)
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

    public function downloadImages($literals)
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

    public function base64Embed($literals)
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

    public function streamReplace($literals, $stream)
    {
        $processedStream = $stream;

        foreach ($literals as $literal) {
            $processedStream = str_replace($literal['url'], $literal['embed'], $processedStream);
        }

        return $processedStream;
    }
}
