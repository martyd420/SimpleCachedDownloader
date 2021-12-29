<?php

namespace Martyd420\SimpleCachedDownloader;

interface IDataDownloader {

    /**
     * must return data with headers
    */
    public function download(string $url): string;

    /**
     * selftest
     * @todo try to download random data, or only check if requied functions exists?
    */
    public function isWorking(): bool;

}