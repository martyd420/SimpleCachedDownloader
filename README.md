### Simple cached downloader 
This is a simple library for downloading files/web pages with caching of downloaded data.
It can downloads via multiple methods defined by IDataDownloader interface. 
By default, SimpleDownloader select first functional DataDownloader, but can be 
force set by **`$simpleDownlaoder->setDataDownloader()`** 
###
##### Installation:
`composer require martyd420/simple-downloader`

###
##### Usage:
```
require 'vendor/autoload.php';  // or require all classes manually

$downloader = new SimpleCachedDownloader('/temp');
$result = $downloader->download('https://github.com');

echo $result->getHeaders();
echo $result; // or echo ->getBody();
```


###### Optional args:
_$max_age_ of cache file \
_$sleep_ seconds before next request in this instance 
```
$downloader->download(string $url, int $max_age = 86400, int $sleep = 0):
```
