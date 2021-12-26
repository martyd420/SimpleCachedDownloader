<?php

use Martyd420\SimpleCachedDownloader\SimpleCachedDownloader;
use Martyd420\SimpleCachedDownloader\Exceptions\ConnectionErrorException;
use Martyd420\SimpleCachedDownloader\Exceptions\CacheNotWriteableException;

$base_path = dirname(__FILE__);

$cache_dir = $base_path . '/cache/';

require_once $base_path . '/../src/SimpleCachedDownloader.php';
require_once $base_path . '/../src/DownloadResult.php';
require_once $base_path . '/../src/Exceptions/CacheNotWriteableException.php';
require_once $base_path . '/../src/Exceptions/ConnectionErrorException.php';


$downloader = new SimpleCachedDownloader($cache_dir);

try {
    $result = $downloader->download('https://github.com/martyd420/SimpleCachedDownloader');
} catch (ConnectionErrorException $e) {
    pln('ConnectionErrorException: ' . $e->getMessage());
    $result = null;
} catch (CacheNotWriteableException $e) {
    pln ('CacheNotWriteableException: ') . $e->getMessage();
    $result = null;
}


pln ('Downloaded ' . round(strlen($result) / 1024, 1) . ' KB');

pln('Headers: ');
print_r($result->getHeaders());




function pln($s) {
    print date('H:i:s'). ': ' . $s . PHP_EOL;
}