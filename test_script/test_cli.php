<?php

use Martyd420\SimpleCachedDownloader\SimpleCachedDownloader;
use Martyd420\SimpleCachedDownloader\Exceptions\ConnectionErrorException;
use Martyd420\SimpleCachedDownloader\Exceptions\CacheNotWriteableException;

$base_path = dirname(__FILE__);

$cache_dir = $base_path . '/cache/';

require_once $base_path . '/../src/Exceptions/CacheNotWriteableException.php';
require_once $base_path . '/../src/Exceptions/ConnectionErrorException.php';
require_once $base_path . '/../src/IDataDownloader.php';
require_once $base_path . '/../src/DataDownloaders/FgcDownloader.php';
require_once $base_path . '/../src/DataDownloaders/CurlDownloader.php';
require_once $base_path . '/../src/DownloadResult.php';
require_once $base_path . '/../src/SimpleCachedDownloader.php';
// or require autoload.php;

$downloader = new SimpleCachedDownloader($cache_dir);

try {
    $result = $downloader->download('https://github.com/martyd420/SimpleCachedDownloader', 0);
} catch (ConnectionErrorException $e) {
    pln('ConnectionErrorException: ' . $e->getMessage());
    $result = null;
} catch (CacheNotWriteableException $e) {
    pln ('CacheNotWriteableException: ') . $e->getMessage();
    $result = null;
}


pln ('Downloaded ' . round(strlen($result) / 1024, 2) . ' KB');

pln('Headers: ');
echo $result->getHeaders();




function pln($s) {
    print date('H:i:s'). ': ' . $s . PHP_EOL;
}