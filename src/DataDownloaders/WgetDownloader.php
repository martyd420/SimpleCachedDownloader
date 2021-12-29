<?php

namespace Martyd420\SimpleCachedDownloader\DataDownloaders;

use Martyd420\SimpleCachedDownloader\IDataDownloader;

class WgetDownloader implements IDataDownloader
{

    public function download(string $url): string
    {
        $user_agent = 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36';

        $cmd = 'wget --save-headers --output-document - ' . $url;
        $result = shell_exec($cmd);

        return  $result ?: '';
    }


    public function isWorking(): bool
    {

        return false; /** :) */

        if (function_exists('shell_exec')) {
            $data = shell_exec('wget --version');
            if (strpos($data, 'GNU Wget') !== false) return true;
        }

        return false;
    }

}