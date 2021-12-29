<?php

namespace Martyd420\SimpleCachedDownloader\DataDownloaders;

use Martyd420\SimpleCachedDownloader\IDataDownloader;

class FgcDownloader implements IDataDownloader
{

    public function download(string $url): string
    {
        $headers = [
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36',
            'Connection: close',
        ];

        $context = stream_context_create([
            "http" => [
                "method"        => 'GET',
                "header"        => implode("\r\n", $headers),
                "ignore_errors" => true,
            ],
        ]);

        $response = file_get_contents($url, false, $context);
        if (!$response) return false;

        $headers = join($http_response_header, "\r\n");

        return $headers . "\r\n\r\n" . $response;
    }


    public function isWorking(): bool
    {
        if (!function_exists('file_get_contents') ||
            !function_exists('stream_context_create') ||
            !get_cfg_var('allow_url_fopen')) {
            return false;
        } else {
            return true;
        }

    }

}