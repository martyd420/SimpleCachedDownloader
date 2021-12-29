<?php /** @noinspection PhpUnused */

namespace Martyd420\SimpleCachedDownloader;

use Martyd420\SimpleCachedDownloader\Exceptions\CacheNotWriteableException;
use Martyd420\SimpleCachedDownloader\Exceptions\ConnectionErrorException;

class SimpleCachedDownloader
{
    private string $cache_dir;
    private ?IDataDownloader $data_downloader = null;


    public function __construct(string $cache_dir)
    {
        $this->cache_dir = realpath($cache_dir) . DIRECTORY_SEPARATOR;

        // force require classes, that implements IDataDownloader (for searching with get_declared_classes())
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DataDownloaders';
        $dir = dir($path);
        while (false !== ($file = $dir->read())) {
            if (substr($file, -4) === '.php') {
                require_once ($path . DIRECTORY_SEPARATOR . $file);
            }
        }

    }


    /**
     * @param string $url
     * @param int $max_age in seconds
     * @param int $sleep in seconds
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
            $body = file_get_contents($cache_file);
        } else {
            $data_downloader = $this->getDataDownloader();
            $data = $data_downloader->download($url);

            $header_size    = strpos($data, "\r\n\r\n");
            $header         = substr($data, 0, $header_size);
            $body           = str_replace($header, '', $data);
            $body           = trim($body);

            if (empty($body) || empty($header)) {
                throw new ConnectionErrorException('Failed to download data');
            }

            file_put_contents($cache_file, $body);
            file_put_contents($cache_file . '.txt', $header);

            sleep($sleep);
        }

        return new DownloadResult($header, $body);
    }


    private function getDataDownloader(): ?IDataDownloader
    {
        if (!is_null($this->data_downloader)) return $this->data_downloader;

        /** @var $dl IDataDownloader */
        foreach (get_declared_classes() as $data_downloader_class_name) {
            if (in_array(IDataDownloader::class, class_implements($data_downloader_class_name))) {
                $dl = new $data_downloader_class_name;
                if ($dl->isWorking()) return $dl;
            }
        }

        return null;
    }

    public function setDataDownloader(IDataDownloader $idownloader)
    {
        $this->data_downloader = $idownloader;
    }

}
