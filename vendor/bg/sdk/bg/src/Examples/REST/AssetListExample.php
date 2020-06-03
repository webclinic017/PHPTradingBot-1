<?php
/**
 * Created by PhpStorm.
 * User: qingyu.gou
 * Date: 2020/3/19
 * Time: 11:44 AM
 */

namespace Bg\Sdk\REST\Examples;

use Bg\Sdk\Clients\RESTClient;
use Bg\Sdk\REST\Request\Spot\AssetListRequest;

class AssetListExample
{
    public static function getAllAssets()
    {
        $client = new RESTClient();
        if($client->getResponse(new AssetListRequest("ALL","spot"))->isError()){
            error_log('Code: '.$client->response->getCode().PHP_EOL.'Message: '.$client->response->getMessage());
            return false;
        }else{
            return $client->response->getData();
        }
    }
}