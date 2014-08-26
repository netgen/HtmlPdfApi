Netgen HtmlPdfApi library Usage Instructions
===========================================

HtmlPdfApi exposes [HTML PDF API](https://htmlpdfapi.com) functionalities: you can generate PDF from URL, generate PDF from HTML, manipulate assets on the server or find out how many credits you have left on your account.

Create HTTP client, validator and HtmlPdfApi
--------------------------------------------

First, create the object. Constructor expects two parameters: HTTP client (which must implement HttpClientInterface) and validator (which must implement ValidatorInterface).
There are two HttpClients implemented, so you can simply instantiate one of them, and pass it on as a parameter.
There is also validator implementation, which you can use in the same way.

```php
$validator = new HtmlPdfApiValidator();
$httpClient = new Guzzle($host, $token, $json_location);
$htmlPdfApi = new HtmlPdfApi($httpClient, $validator);
```
Guzzle client implementation expects the following parameters:
* $host - base url of the api
* $token - security token provided by the [HTML PDF API](https://htmlpdfapi.com)
* $json_location - full path to json file containing service description

Generating PDF
--------------

To generate PDF, you have to define parameters for the request. You can find the list of available options, as well as their description, in HTML PDF API [documentation](https://htmlpdfapi.com/documentation).

###Generating from URL

If you want to generate PDF file directly from url, you must at least provide the 'url' parameter (in that case, 'html' and 'file' parameter must not be set).
Example:
```php
$params = array ('url' => "http://www.netgenlabs.com/")
$pdfFile = $htmlPdfApi->generateFromURL($params);
```

###Generating from HTML

If you want to generate PDF from HTML string, you must at least provide the 'html' parameter (in that case, 'url' and 'file' parameter must not be set).
Example:
```php
$params = array('html' => "<h1>HTML PDF API is cool!</h1>")
$pdfFile = $htmlPdfApi->generateFromHTML($params);
```

###Generating from file

If you want to generate PDF from file (html or compressed file), you must at least provide the 'file' parameter which is a path to the file (in that case, 'html' and 'url' parameter must not be set).
Example:
```php
$params = array('file' => "example.html")
$pdfFile = $htmlPdfApi->generateFromFile($params);
```

###Setting additional options

Example:
```php
$params = array(
    'url' => "http://www.netgenlabs.com",
    'dpi' => 150,
    'margin_top' => 20
);
```
[Full options list](https://htmlpdfapi.com/documentation)

###Note

All three functions return pdf file.

Assets
------

Assets allow you to upload a file and use it as a local file on the server in your templates.
There are several operations you can preform on assets: upload, download, delete, get asset id by name and get list of uploaded assets.

###Create asset
To upload an asset, you have to provide full path to the asset:
```php
$response = $htmlPdfApi->uploadAsset("/path/to/asset");
```

On success returns json with asset information (id, name, mime, size).


###Download asset
To download asset, you can get asset by ID:
```php
$asset = $htmlPdfApi->getAsset($id);
```

On success returns the asset.

###Delete asset
You can delete asset by its ID:
```php
$response = $htmlPdfApi->deleteAsset($id);
```


###Get asset ID by name
To find out which ID your asset has:
```php
$asset_id = $htmlPdfApi->getAssetID($assetName);
```

On success returns asset ID as string.

###Get list of uploaded assets
You can also get the list of uploaded assets:
```php
$assets = $htmlPdfApi->getAssetList();
```

On success returns list of json objects (each containing id, name, mime and size of the asset).

Credits
-------
To find out how many credits you have left on your account:
```php
$credits = $htmlPdfApi->getCredits();
```

On success returns the number of available credits.