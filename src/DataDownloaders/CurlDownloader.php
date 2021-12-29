<?php

namespace Martyd420\SimpleCachedDownloader\DataDownloaders;

use Martyd420\SimpleCachedDownloader\IDataDownloader;

class CurlDownloader implements IDataDownloader
{

    private $curl;


    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_USERAGENT,         'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,    true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER,    false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST,    false);
        curl_setopt($this->curl, CURLOPT_HEADER,            true);
    }


    public function download(string $url): string
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        return curl_exec($this->curl);
    }


    public function __destruct()
    {
        @curl_close($this->curl);
    }


    public function isWorking(): bool
    {
        return function_exists('curl_init');
    }
}