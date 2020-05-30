<?php

namespace App\Console\Commands;

use App\BithumbTradeHelper;
use App\Brick;
use App\Modules;
use App\Price;
use App\Setting;
use App\Ticker as TickerModel;
use App\Wall;
use Bg\Sdk\Examples\REST\ServerTimeExample;
use Bg\Sdk\REST\Request\Spot\CancelOrdersRequest;
use Bg\Sdk\REST\Request\Spot\OrderDetailRequest;
use Bg\Sdk\REST\Request\Spot\PlaceOrderRequest;
use Bg\Sdk\REST\Request\Spot\SingleOrderRequest;
use Bg\Sdk\WS\Streams\OrderStream;
use Bg\Sdk\WS\WSResponse;
use Bg\Sdk\WS\Streams\TickerStream;
use Bg\Sdk\BithumbGlobalClient;
use Bg\Sdk\WS\Interfaces\WSClientInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use \Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;

class Waller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daemon:waller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listens to ordersChange web socket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    private function createWall(){

    }
    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
                    $this->info('Daemon start');

        while(true) {

            try{

            $bithumb = BithumbTradeHelper::getBithumb();
            //waller settings

            $enabledModules = Modules::getActiveModules();
            $eligibleModules = [];
            if ($enabledModules) {
                foreach ($enabledModules as $module) {
                    $_module = $module->getFactory();
                    if (method_exists($_module, 'signalLoop')) {
                        $eligibleModules[] = $_module;
                    }
                }
            }
//Rest$waller = new Modules\Waller();
//        $config =

            $waller = Modules::init('Waller');
//            $this->info(print_r($waller->config, 1));
            $buyCovering = $waller->getConfig('buyCovering');
            $sellCovering = $waller->getConfig('sellCovering');
            $spread = $waller->getConfig('spread');
            $buyOrderAmount = $waller->getConfig('buyOrderAmount');
            $pair = $waller->getConfig('pair');
            $symbolConfig = BithumbTradeHelper::getNotions($pair);
            $openBithumbOrdersArray = BithumbTradeHelper::getOpenOrdersId($bithumb);
            $openWallerOrdersArray = Brick::getAllBricksOrderId($pair);        //create wall if not exis
            $currentPrice = BithumbTradeHelper::getPrice($pair);

//            $this->info('openWallerOrders' . print_r($openWallerOrdersArray, 1));
            //close orders
            $closedOrders = array_diff($openWallerOrdersArray, $openBithumbOrdersArray);
//            $this->info('$closedOrders  ' . print_r($closedOrders, 1));


            if (empty($openWallerOrdersArray)) {
                //create wall from scratch
                $countSellWall = $sellCovering / $spread;
                $countBuyWall = $buyCovering / $spread;
                $spread = $spread / 100;
                $timestamp = (string)ServerTimeExample::getTimestamp();
                $pricebrick = 0;
                $bricks = [];
                for ($i = 1; $i <= $countSellWall; $i++) {
                    $brick = new Brick();

                    //    protected $fillable = ['side', 'symbol', 'price', 'quantity', 'orderId', 'createTime','tradedNum'];
                    $brick->side = 'sell';
                    $brick->type = 'limit';
                    $brick->symbol = $pair;
                    //get price first brick if price empty
                    if ($pricebrick == 0) {
                        $pricebrick = $currentPrice;
                    } else {
                        $pricebrick = ($pricebrick * $spread) + $pricebrick;
                    }
                    $brick->price = number_format($pricebrick, $symbolConfig->accuracy[0], '.', '');
                    $_quantity = $buyOrderAmount / $pricebrick;
                    $brick->quantity = number_format($_quantity * $spread + $_quantity, $symbolConfig->accuracy[1], '.', '');
                    // new order and subscribe to

                    if ($bithumb->getResponse(new PlaceOrderRequest(
                        $brick->symbol, $brick->type, $brick->side, $brick->price, $brick->quantity, $timestamp
                    ))->isError()) {
                        $this->info('Daemon Waller error ' . $bithumb->response->getCode() . $bithumb->response->getMessage());
//                        $this->info('Daemon Waller error ' . print_r($brick ,1));

                        $this->info(number_format($brick->quantity, $symbolConfig->accuracy[1], '.', '') . 'Params : ' . print_r(number_format($brick->price, $symbolConfig->accuracy[0], '.', ''), 1).$brick->side);

                    } else {
                        $brick->orderId = $bithumb->response->getData()->orderId;
                        $brick->save();
                    }
                }
                $pricebrick = 0;

                for ($i = 1; $i <= $countBuyWall; $i++) {
                    $brick = new Brick();
                    //    protected $fillable = ['side', 'symbol', 'price', 'quantity', 'orderId', 'createTime','tradedNum'];
                    $brick->side = 'buy';
                    $brick->symbol = $pair;
                    $brick->type = 'limit';
                    //get price first brick if price empty
                    if ($pricebrick == 0) {
                        $pricebrick = $currentPrice - ($currentPrice * $spread);
                    } else {
                        $pricebrick = $pricebrick - ($pricebrick * $spread);
                    }
                    $brick->price = number_format($pricebrick, $symbolConfig->accuracy[0], '.', '');
                    $_quantity = $buyOrderAmount/$brick->price;
                    $brick->quantity = number_format($_quantity, $symbolConfig->accuracy[1], '.', '');
                    // new order and subscribe to
                    if ($bithumb->getResponse(new PlaceOrderRequest(
                        $brick->symbol, $brick->type, $brick->side, $brick->price, $brick->quantity, $timestamp
                    ))->isError()) {
                        if($bithumb->response->getCode()==20003){
//                            $brick->save();
                        }
                        $this->info('Daemon Waller error ' . $bithumb->response->getCode() . $bithumb->response->getMessage());
//                        $this->info('Daemon Waller error ' . print_r($brick ,1));

                        $this->info('Daemon $brick->price ' . $brick->price . ' $brick->quantity' . $brick->quantity .$brick->side);
                        $this->info('Daemon $symbolConfig->accuracy ' . print_r($symbolConfig->accuracy, 1) . ' $brick->quantity' . $brick->quantity);

                    } else {
                        $brick->orderId = $bithumb->response->getData()->orderId;
                        $brick->save();
                    }
                }

            }
            if (count($closedOrders) > 0) {
                $spread = $spread/100;
//            protected $fillable = ['side', 'symbol', 'price', 'quantity', 'orderId', 'createTime','tradedNum','type'];

                // recreate walls
                foreach ($closedOrders as $orderId) {
                    //if closed by user just destroy
                    if(!$bithumb->getResponse(new SingleOrderRequest($orderId,$pair))->isError()){
                        if($bithumb->response->getData()->status == 'cancel'){
                            //just remove brick
                            Brick::destroyBrickByOrderId($orderId);
                            continue;
                        }else{
                            $this->info( print_r($bithumb->response->getData(),1));
                            $this->info('Daemon Waller error ' . $bithumb->response->getCode() . $bithumb->response->getMessage());

                        }
                    }
                    $otherWallBrick = new Brick();
                    $oldBrick = Brick::where('orderId', $orderId)->get()[0];
//                    $this->info('$oldBrick  ' . print_r($oldBrick->getOriginal(), 1));

                    $otherWallBrick->symbol = $pair;
                    $otherWallBrick->type = 'limit';
                    if ($oldBrick->side == 'sell') {

                        $otherWallBrick->side = 'buy';
                        $percent = $oldBrick->price * $spread;
                        $otherWallBrick->price = number_format($oldBrick->price - $percent, $symbolConfig->accuracy[0], '.', '');
                        $otherWallBrick->quantity = number_format($buyOrderAmount/$otherWallBrick->price, $symbolConfig->accuracy[1], '.', '');

                    } else {
                        //                    $brick->quantity = number_format($_quantity*$spread + $_quantity , $symbolConfig->accuracy[1],'.','');

                        $otherWallBrick->side = 'sell';
                        $otherWallBrick->quantity = $oldBrick->quantity;
                        $otherWallBrick->price = number_format(($oldBrick->price * $spread) + $oldBrick->price, $symbolConfig->accuracy[0], '.', '');

                    }
                    if ($bithumb->getResponse(new PlaceOrderRequest(
                        $otherWallBrick->symbol, $otherWallBrick->type, $otherWallBrick->side, $otherWallBrick->price, $otherWallBrick->quantity, (string)ServerTimeExample::getTimestamp()
                    ))->isError()) {
                        $this->info('Daemon Waller error ' . $bithumb->response->getCode() . $bithumb->response->getMessage());
                        $this->info($otherWallBrick->quantity . 'Params : ' . print_r($otherWallBrick->price, 1).$otherWallBrick->side);
//                        $this->info('Daemon Waller error ' . print_r($otherWallBrick ,1));


                    } else {
                        $otherWallBrick->orderId = $bithumb->response->getData()->orderId;
                        $otherWallBrick->save();
                    }
                    Brick::destroyBrickByOrderId($orderId);
                }

//                if(!empty($openWallerOrdersArray)){
//                   if( $bithumb->getResponse(new CancelOrdersRequest($openWallerOrdersArray,$pair))->isError()){
//                       $this->info('Daemon Waller CancelOrdersRequest error '.$bithumb->response->getCode().$bithumb->response->getMessage());
//                   }
//
//
//                }
//            Modules\Waller::createWalls();
                //create bricks
                //create first sell wall


            }
//price trailing
                $greenBricks = Brick::getGreenBricks($pair);
                $redBricks = Brick::getRedBricks($pair);
//
                $this->info('Daemon Waller error ' . print_r($redBricks[0]->getOriginal(),1));

                if(empty($greenBricks)){
                    //recreate walls if current price goes much down
                    $priceMin = 0;
                    foreach ($redBricks as $redBrick){
                        if($priceMin > (float)$redBrick->price){
                            $priceMin = (float)$redBrick->price;
                        }
                    }
                    $floatPrice = $priceMin- ($priceMin*$spread);
                    if($floatPrice < (float)$currentPrice){
                        //create wall from current price
                        if(!$bithumb->getResponse(new CancelOrdersRequest($openWallerOrdersArray,$pair))->isError()){
                            foreach ($openWallerOrdersArray as $orderId){
                                Brick::destroyBrickByOrderId($orderId);
                            }
                        }
                    }
                }elseif(empty($redBricks)){
                    //get current price
                    $priceMax = 0;
                    foreach ($greenBricks as $greenBrick){
                        if($priceMax < (float)$greenBrick->price){
                            $priceMax = (float)$greenBrick->price;
                        }
                    }
                    $floatPrice = $priceMax+ ($priceMax*$spread);
                    if($floatPrice < (float)$currentPrice){
                        //create wall from current price
                        if(!$bithumb->getResponse(new CancelOrdersRequest($openWallerOrdersArray,$pair))->isError()){
                            foreach ($openWallerOrdersArray as $orderId){
                                Brick::destroyBrickByOrderId($orderId);
                            }
                        }
                    }
                }
            //check orders from rest api

            // get limit orders Waller getBricks and try to get from rest api


//        $bithumb->subscribe(new OrderStream(
//            'BIP-USDT',
//            function (WSClientInterface $client,OrderStream $stream ,WSResponse $response) use ($bithumb ,$spread, $eligibleModules,$symbolConfig) {
//                if ($response->isError()) {
//                    error_log(print_r($response,1));
//                    $client->subscribe($stream); // reconnect
//                }
//                if ($response->isNormal()) {
////                    error_log(print_r($response->getData(), 1));
//                    try {
//                        //convert ticker data to store
//                        if ($response === 'close') {
//                            $this->info('Daemon ticker restart');
//                            return Artisan::call("daemon:waller", []);
//                        }
//                        //if limit check order in base and delete to create new
//                        if($response->getData()->type == 'limit'&&$response->getData()->status == 'fullDealt'){
//                            $newBrick = new Brick();
//
//                            if(Brick::destroyBrickByOrderId($response->getData()->oId)){
//                             if($response->getData()->side == 'buy'){
//                                 $newBrick->side = 'sell';
//                                 $newBrick->type ='limit' ;
//                                 $newBrick->symbol =$response->getData()->symbol ;
//                                 //get price first brick if price empty
//                                 $newBrick->price =number_format(($response->getData()->price*$spread) + $response->getData()->price, $symbolConfig->accuracy[0],'.','');
//                                 $_quantity = $response->getData()->quantity/$newBrick->price;
//                                 $newBrick->quantity = number_format($_quantity*$spread + $_quantity , $symbolConfig->accuracy[1],'.','') ;
//                             }elseif($response->getData()->side == 'sell'){
//                                 $newBrick->side = 'buy';
//                                 $newBrick->type ='limit' ;
//                                 $newBrick->symbol =$response->getData()->symbol ;
//                                 //get price first brick if price empty
//                                 $newBrick->price = number_format($response->getData()->price - ($response->getData()->price*$spread), $symbolConfig->accuracy[0],'.','') ;
//                                 $_quantity = $response->getData()->quantity;
//                                 $newBrick->quantity = number_format($_quantity*$spread - $_quantity, $symbolConfig->accuracy[1],'.','') ;
//                             }
//                         }
//                            //create antogonist order
//                            if($bithumb->getResponse(new PlaceOrderRequest(
//                                $newBrick->symbol,$newBrick->type,$newBrick->side,$newBrick->price,$newBrick->quantity,$response->getData()->time
//                            ))->isError()){
//                                $this->info('Daemon Waller error in subscribe '.$bithumb->response->getCode().$bithumb->response->getMessage());
//                            }else{
//                                $newBrick->orderId = $bithumb->response->getData()->orderId;
//                                $newBrick->save();
//                            }
//                        }
////                        oId	order id		String
////price	order price	if type is "market", the value is "-1"	String
////quantity	order quantity		String
////side		buy or sell	String
////symbol			String
////type		limit or market	String
////status		created，partDealt，fullDealt，canceled	String
////dealPrice	Last executed price	if status = canceled, the value is "0"	String
////dealQuantity	Last executed quantity	if status = canceled, the value is "0"	String
////dealVolume	Last executed volume	if status = canceled, the value is "0"	String
////fee		if status = canceled, the value is "0"	String
////feeType		if status = canceled, the value is ""	String
////cancelQuantity		if status is not "canceled", the value is "0"	String
////time	order update time		Long
//
//
                    } catch (\Exception $exception) {
                        $this->alert($exception->getMessage());
                        return Artisan::call("daemon:waller", []);

            }
//
////                    Cache::forever('lastTick', time());
//                }
//            }));

//        } else {
//            $this->info('WS : Mini Ticker');
//            $bithumb->miniTicker(function ($api, $ticker) use ($saveTicker,$eligibleModules) {
//                try {
//                    if ($saveTicker)
//                        \App\Ticker::create($ticker);
//                    foreach ($ticker as $tick) {
//                        Cache::put($tick['symbol'], $tick, now()->addHour(1));
//                        $this->onTickEvent($tick,$eligibleModules);
//                    }
//                } catch (\Exception $exception) {
//                    $this->alert($exception->getMessage());
//                }
//
//                Cache::forever('lastTick', time());
//            });
//        }

            unset($bithumb);
//            $this->info('Stop cycle return 0');
//            $this->info('Daemon Waller restart');
            sleep(1);
        }
//        return 0;
        //restart
    }


}
