<?php

use Martyd420\SimpleCachedDownloader\SimpleCachedDownloader;
use Martyd420\SimpleCachedDownloader\Exceptions\ConnectionErrorException;
use Martyd420\SimpleCachedDownloader\Exceptions\CacheNotWriteableException;

$base_path = dirname(__FILE__);

$cache_dir = $base_path . '/cache/';

require_once $base_path . '/../src/SimpleCachedDownloader.php';
require_once $base_path . '/../src/Exceptions/CacheNotWriteableException.php';
require_once $base_path . '/../src/Exceptions/ConnectionErrorException.php';


$downloader = new SimpleCachedDownloader($cache_dir);

try {
    $data = $downloader->download('https://github.com/martyd420/SimpleCachedDownloader');
} catch (ConnectionErrorException $e) {
    pln('ConnectionErrorException: ' . $e->getMessage());
    $data = null;
} catch (CacheNotWriteableException $e) {
    pln ('CacheNotWriteableException: ') . $e->getMessage();
    $data = null;
}


pln ('Downloaded ' . round(strlen($data) / 1024, 1) . ' KB');



function pln($s) {
    print date('H:i:s'). ': ' . $s . PHP_EOL;
}