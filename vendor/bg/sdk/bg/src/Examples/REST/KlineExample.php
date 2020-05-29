<?php
/**
 * Created by PhpStorm.
 * User: qingyu.gou
 * Date: 2020/3/19
 * Time: 11:44 AM
 */

namespace Bg\Sdk\REST\Examples;

use Bg\Sdk\RESTApplication;
use Bg\Sdk\REST\Request\Spot\KlineRequest;

class KlineExample
{
    public static function sendRequest()
    {
        $timestamp =  (int)(ServerTimeExample::sendRequest()/100);
        error_log($timestamp);
        $request = new KlineRequest('BIP-USDT','m1',$timestamp-60,$timestamp);
        $client = new RESTApplication();

        if($client->getResponse($request)->isError()){
            error_log('Code: '.$client->response->getCode().PHP_EOL.'Message: '.$client->response->getMessage());
        }else{

        }
        return $client->response->getData();
    }
}