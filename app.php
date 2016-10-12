<?php
require_once __DIR__ . '/vendor/autoload.php';

use FacebookAdsExporter\FacebookAdsExporter;

$date = "2016-09-18";
$path = 'abc.csv';
$facebookAdsExporter = FacebookAdsExporter::create();
$adInsights = $facebookAdsExporter->fetch($date);
$facebookAdsExporter->export($adInsights, $path);
