<?php
namespace FacebookAdsExporter\Tests;

use FacebookAdsExporter\FacebookAdsExporter;

class FacebookAdsExportTest extends \PHPUnit_Framework_TestCase {

    /**
     * @requires extension mysqli
     */
    public function testGetActiveAds($date="2016-09-18"){
        $fae = FacebookAdsExporter::create();
        $activeAds = $fae->getActiveAds($date);
        $this->assertTrue(is_array($activeAds) && !empty($activeAds));
    }

    public function testGetAdInsights($date="2016-09-18"){
        $fae = FacebookAdsExporter::create();
        $activeAds = $fae->getActiveAds($date);
        $adInsights = $fae->getAdInsights($activeAds, $date);
        $this->assertTrue(is_array($adInsights) && !empty($adInsights));
    }
}