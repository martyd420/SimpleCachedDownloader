<?php /** @noinspection PhpUnused */

namespace Martyd420\SimpleCachedDownloader;

class DownloadResult
{
    private string $headers;
    private string $body;


    public function __construct(string $headers, string $body)
    {
        $this->headers = $headers;
        $this->body = $body;
    }


    public function getHeaders(): string
    {
        return $this->headers;
    }


    public function getBody(): string
    {
        return $this->body;
    }


    public function __toString(): string
    {
        return $this->getBody();
    }

}