<?php
/**
 * Created by PhpStorm.
 * User: qingyu.gou
 * Date: 2020/3/19
 * Time: 11:03 AM
 */

namespace Bg\Sdk\WS\Streams;
use Bg\Sdk\WS\WSStream;

class TradeStream extends WSStream
{

    protected $path = "TRADE";
    protected $isNeedSign = false;
    public $callback;

    public function __construct(string $symbol, callable $callback ,bool $private=false)
    {
        $this->path .=':'.$symbol;
        $this->symbol=$symbol;
        $this->isNeedSign=$private;
        $this->callback=$callback;
    }

}