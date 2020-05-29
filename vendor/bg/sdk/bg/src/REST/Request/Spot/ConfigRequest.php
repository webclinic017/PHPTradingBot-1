<?php
/**
 * Created by PhpStorm.
 * User: qingyu.gou
 * Date: 2020/3/19
 * Time: 11:03 AM
 */

namespace Bg\Sdk\REST\Request\Spot;
use Bg\Sdk\REST\RESTRequest;

class ConfigRequest extends RESTRequest
{

    protected $path = "/spot/config";

    protected $method = "GET";

    protected $isNeedSign = false;

    public function getParams():array
    {
        return [];
    }

}