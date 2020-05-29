<?php
/**
 * Created by PhpStorm.
 * User: qingyu.gou
 * Date: 2020/3/19
 * Time: 11:03 AM
 */

namespace Bg\Sdk\REST\Request;

use Bg\Sdk\REST\RESTRequest;

class ServerTimeRequest extends RESTRequest
{

    protected $path = "/serverTime";

    protected $method = "GET";

    protected $isNeedSign = false;

    public function getParams():array
    {
        return [];
    }

}