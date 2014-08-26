HtmlPdfApi changelog
====================

0.2 (29.08.2014.)
-----------------

* Guzzle HTTP client now doesn't require `root` parameter (root kernel directory)
* `json_location` parameter in Guzzle HTTP client now has to be full path to .json containing service description
* .json service description file can now have any name
* Fixed Guzzle HTTP client to return only response body (as string)
* Defined `HttpClientInterface` return type as string
* Added error handling in Curl HTTP client
* Various bug fixes