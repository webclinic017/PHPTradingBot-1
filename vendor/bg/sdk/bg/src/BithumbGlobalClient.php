<?php
/**
 * Created by PhpStorm.
 * User: devacc
 * Date: 26.05.2020
 * Time: 16:21
 */

namespace Bg\Sdk;

use Bg\Sdk\Clients\RESTClient;
use Bg\Sdk\Clients\WSClient;
use Bg\Sdk\Examples\REST\ServerTimeExample;
use Bg\Sdk\REST\Interfaces\RESTClientInterface;
use Bg\Sdk\REST\Interfaces\RESTRequestInterface;
use Bg\Sdk\REST\RESTResponse;
use Bg\Sdk\WS\Interfaces\WSClientInterface;
use Bg\Sdk\WS\WSStream;

class BithumbGlobalClient implements WSClientInterface,RESTClientInterface
{

    /**
     * Rest Client container
     * @var
     */
    protected $RESTClient;

    /**
     * WS Client container
     * @var
     */
    protected $WSClient;

    /**
     * @var RESTResponse
     */
    public $response;

    /**
     * @var RESTRequestInterface
     */
    public $request;

    public function __construct(string $apiKey = '', string $apiSecretKey= '', string $msgNo = '1234567890')
    {
        $BithumbTime = ServerTimeExample::getTimestamp();
        $this->RESTClient = new RESTClient($apiKey,$apiSecretKey,$BithumbTime,$msgNo);
        $this->WSClient = new WSClient($apiKey,$apiSecretKey,$BithumbTime);
    }
    public function subscribe(WSStream $stream)
    {
        return $this->WSClient->subscribe($stream);
    }
    public function unSubscribe(WSStream $stream)
    {
        return $this->WSClient->unSubscribe($stream);
    }
    public function getResponse(RESTRequestInterface $request): RESTResponse
    {
        $this->response = $this->RESTClient->getResponse($request);
        return $this->response;
    }
    public function getRequest(): RESTRequestInterface
    {
        $this->request = $this->RESTClient->getRequest();
        return $this->request;
    }
}