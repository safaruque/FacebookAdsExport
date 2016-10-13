<?php

namespace FacebookAdsExporter;

use Facebook\Facebook;
use Facebook\FacebookApp;
use Facebook\FacebookRequest;
use Dotenv;

class FacebookAdsExporter
{

    protected $appId;
    protected $appSecret;
    protected $appAccessToken;
    protected $accountId;

    public function __construct()
    {
        $dotenv = new Dotenv\Dotenv(dirname('.'));
        $dotenv->load();
        $this->setConfigParams();

        $this->fb = new Facebook([
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
        ]);

        $this->fbApp = new FacebookApp($this->appId, $this->appSecret);
    }

    public function setConfigParams(){
        $this->setAppId(getenv('APP_ID'));
        $this->setAppSecret(getenv('APP_SECRET'));
        //It won't be invalidated unless remove the app from list
        $this->setAppAccessToken(getenv('ACCESS_TOKEN'));
        $this->setAccountId(getenv('ACCOUNT_ID'));
    }

    public function setAppId($appId){
        $this->appId = $appId;
    }

    public function setAppSecret($appSecret){
        $this->appSecret = $appSecret;
    }

    public function setAppAccessToken($appAccessToken){
        $this->appAccessToken = $appAccessToken;
    }

    public function setAccountId($accountId){
        $this->accountId = $accountId;
    }

    public function getAppId(){
        return $this->appId;
    }

    public function getAppSecret(){
        return $this->appSecret;
    }

    public function getAppAccessToken(){
        return $this->appAccessToken;
    }

    public function getAccountId(){
        return $this->accountId;
    }


    public function getActiveAds($date){
        $adList = array();

        $params = array(
            'fields' => 'adset_id, effective_status, status, impression',
            'limit' => 500,
            'summary' => '1',
            'filtering' => "[{'field':'impressions','operator':'GREATER_THAN','value':0}]"
        );

        if(isset($date) && !empty($date)){
            $params['time_range'] = array(
                'since' => $date,
                'until' => $date
            );
        } else {
            $params['date_preset'] = 'yesterday';
        }

        $request = new FacebookRequest(
            $this->fbApp,
            $this->appAccessToken,
            'GET',
            '/' . $this->accountId . '/ads',
            $params
        );

        $response = $this->fb->getClient()->sendRequest($request);
        $adList = $response->getDecodedBody();
        return $adList;
    }

    public function getAdInsights($adList, $date){
        $adInsightList = array();
        $i = 0;

        $adParams = array(
            'breakdowns' => ' hourly_stats_aggregated_by_advertiser_time_zone',
            'fields' =>
                'date_start,date_stop,account_id,account_name,ad_id,ad_name
                    ,campaign_id,campaign_name,adset_id,adset_name,objective,total_actions
                    ,total_unique_actions,action_values,total_action_value,impressions,social_impressions,clicks
                    ,social_clicks,unique_impressions,unique_social_impressions,unique_clicks,spend'
        );

        foreach($adList['data'] as $adInfo){
            $i++;
            $adId = $adInfo['id'];
            sleep(2);



            if(isset($date) && !empty($date)){
                $adParams['time_range'] = array(
                    'since' => $date,
                    'until' => $date
                );
            } else {
                $adParams['date_preset'] = 'yesterday';
            }

            $requestAdInsights = new FacebookRequest(
                $this->fbApp,
                $this->appAccessToken,
                'GET',
                '/' . $adId . '/insights',
                $adParams
            );

            /* handle the result */
            try{
                $responseAdInsights = $this->fb->getClient()->sendRequest($requestAdInsights);
            } catch(Exception $e){
                //Check d error type
                var_dump($e);
                print time();
                sleep(600);

            }

            $responseAdInsightsBody = $responseAdInsights->getDecodedBody();

            if(!empty($responseAdInsightsBody['data'])){
                foreach($responseAdInsightsBody['data'] as $adInsight){
                    $adInsightList[] = $adInsight;
                }


            }

            echo PHP_EOL . $i . PHP_EOL;

        }
        return $adInsightList;
    }

    public function fetch($date){
        $adList = $this->getActiveAds($date);
        return $this->getAdInsights($adList, $date);
    }

    /**
     * Exports data to .csv file
     *
     * @param $data
     * @param $pathToExport Absolute path including filename
     */
    public function export($data, $pathToExport){
        $fp = fopen($pathToExport, 'w');

        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }

    /**
     * Creates a new FacebookAdsExporter.
     *
     * @return Finder A new FacebookAdsExporter instance
     */
    public static function create()
    {
        return new static();
    }
}
