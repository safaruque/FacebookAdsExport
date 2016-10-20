<?php
namespace FacebookAdsExporter\Tests;

use FacebookAdsExporter\FacebookAdsExporter;

class FacebookAdsExporterTest extends \PHPUnit_Framework_TestCase
{

    private $app;

    protected function setUp()
    {
        $this->app = FacebookAdsExporter::create();
    }

    /**
     *
     */
    public function testCreate()
    {
        $this->assertInstanceOf('FacebookAdsExporter\FacebookAdsExporter', $this->app);
    }

    public function testFbApp()
    {
        $this->assertInstanceOf('Facebook\FacebookApp', $this->app->fbApp);
    }

    public function testExport(){
        $data = array(
            array('campaign_name_1', 'adset_id_1'),
            array('campaign_name_1', 'adset_id_2')
        );
        $path = "example.csv";
        $this->app->export($data, $path);
        $dataFromCSV = file_get_contents($path);
        $this->assertEquals(preg_match('/campaign_name_1/', $dataFromCSV), 1);

    }
}