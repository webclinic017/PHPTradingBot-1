<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/24/18
 * Time: 17:42
 */

namespace App\Http\Controllers;


use App\Order;
use App\Ticker;
class ApiController extends Controller
{
    public function positions()
    {
        $openPositions = Order::getOpenPositions(true);
        $positions = [];
        foreach ($openPositions as $open) {
            $positions[] = [
                'id' => $open->id,
                'symbol' => $open->symbol,
                'pl' => round($open->getPL(),2),
                'qty' => $open->origQty,
            ];
        }
        return $positions;
    }
//    public function getTickerHistory($symbol,$fromTime=null){
//
//        $tickerHistory = Ticker::getTickerHistory($symbol,$fromTime);
//        $tickerHistoryConverted = [];
//        $prevTickerPrice = 0;
//        foreach ($tickerHistory as $ticker){
//            if($prevTickerPrice ==0){
//                $prevTickerPrice = $ticker->close;
//            }
//            $tickerHistoryConverted[] = ['time'=>$ticker->created_at->getTimestamp(),'open'=>$prevTickerPrice,'high'=> $ticker->high, 'low'=> $ticker->low, 'close'=> $ticker->close , 'value'=>$ticker->close];
//            $prevTickerPrice = $ticker->open;
//        }
//        return $tickerHistoryConverted;
//    }
}