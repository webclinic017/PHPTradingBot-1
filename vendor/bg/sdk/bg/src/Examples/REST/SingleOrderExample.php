<?php
/**
 * Created by PhpStorm.
 * User: qingyu.gou
 * Date: 2020/3/19
 * Time: 11:44 AM
 */

namespace Bg\Sdk\REST\Examples;

use Bg\Sdk\Clients\RESTClient;
use Bg\Sdk\REST\Request\Spot\SingleOrderRequest;

class SingleOrderExample
{
    public static function sendRequest()
    {
        $timestamp = ServerTimeExample::getTimestamp();
        $apiKey = 'Your API Key';
        $secretKey = 'Your API SEcret';
        $msgNo = '1234567890';

        $request = new SingleOrderRequest("12300993210","BIP-USDT");
        $client = new RESTApplication($apiKey, $secretKey, $msgNo, $timestamp);

        if($client->getResponse($request)->isError()){
            error_log('Code: '.$client->response->getCode().PHP_EOL.'Message: '.$client->response->getMessage());
            return false;
        }else{
            return $client->response->getData();
        }

    }
}