<?php /** @noinspection PhpUnused */

namespace Martyd420\SimpleCachedDownloader;


use Martyd420\SimpleCachedDownloader\Exceptions\CacheNotWriteableException;
use Martyd420\SimpleCachedDownloader\Exceptions\ConnectionErrorException;

class SimpleCachedDownloader
{
    private string $cache_dir;
    private $curl;

    private string $user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36';


    public function __construct(string $cache_dir)
    {
        $this->cache_dir = realpath($cache_dir) . DIRECTORY_SEPARATOR;

        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_USERAGENT,         $this->user_agent);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,    true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER,    false);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST,    false);
        curl_setopt($this->curl, CURLOPT_HEADER,            true);
    }


    public function __destruct()
    {
        @curl_close($this->curl);
    }


    /**
     * @param string $url
     * @param int $max_age
     * @param int $sleep
     * @return DownloadResult
     * @throws CacheNotWriteableException
     * @throws ConnectionErrorException
     */
    public function download(string $url, int $max_age = 86400, int $sleep = 0): DownloadResult
    {
        if (!is_dir($this->cache_dir) || !is_writeable($this->cache_dir)) {
            throw new CacheNotWriteableException('Permission denied in ' . $this->cache_dir);
        }

        $cache_file = $this->cache_dir . 'scd_' . md5($url) . '.html';
        $cache_file_headers = $this->cache_dir . 'scd_' . md5($url) . '.html.txt';

        if (file_exists($cache_file) && filemtime($cache_file) > (time() - $max_age)) {
            $header = file_get_contents($cache_file_headers);
            $data = file_get_contents($cache_file);
        } else {
            curl_setopt($this->curl, CURLOPT_URL, $url);
            $response       = curl_exec($this->curl);
            $header_size    = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
            $header         = substr($response, 0, $header_size);
            $data           = substr($response, $header_size);

            if (empty($data) || empty($header)) {
                throw new ConnectionErrorException('Failed to download data');
            }

            file_put_contents($cache_file, $data);
            file_put_contents($cache_file . '.txt', $header);

            sleep($sleep);
        }

        return new DownloadResult($header, $data);
    }


    public function setUserAgent(string $user_agent): void
    {
        $this->user_agent = $user_agent;
    }


}
