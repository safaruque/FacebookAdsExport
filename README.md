# FacebookAdsExport

This library exports insights of Facebook Ads daily. The exported file format is CSV

[![Build Status](https://secure.travis-ci.org/KnpLabs/snappy.png?branch=master)](http://travis-ci.org/KnpLabs/snappy)

## Installation using [Composer](http://getcomposer.org/)

```bash
$ composer require faruque/facebook-ads-export 
```

## Usage

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use FacebookAdsExporter\FacebookAdsExporter;

$facebookAdsExporter = FacebookAdsExporter::create();
$adInsights = $facebookAdsExporter->fetch($date);
$facebookAdsExporter->export($adInsights, $path);

$date = "2016-09-18";
$path = 'facebook-ads-insights.csv';
```

## Credits

FacebookAdsExport has been developed by the [Safique Ahmed Faruque](http://pranjol.com) team.
