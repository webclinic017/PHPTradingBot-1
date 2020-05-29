<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/28/18
 * Time: 17:45
 */

namespace App;



use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Bg\Sdk\Examples\REST\ServerTimeExample;
class Ticker extends Model
{
    protected $table = 'ticker';
    protected $fillable = ['eventType', 'eventTime', 'symbol', 'priceChange', 'percentChange', 'averagePrice', 'prevClose', 'close', 'closeQty', 'bestBid', 'bestBidQty', 'bestAsk', 'bestAskQty', 'open', 'high', 'low', 'volume', 'quoteVolume', 'openTime', 'closeTime', 'firstTradeId', 'lastTradeId', 'numTrades'];

    public static function getTickerHistory($symbol,$fromTime=-1)
    {
        $since = Carbon::createFromTimestamp($fromTime)->toDateTimeString();
        $tickerHistory = self::where('created_at', '>', $since)
            ->where('symbol', $symbol)
            ->orderBy('created_at', 'desc')
            ->get();
            return $tickerHistory;
    }
    public static function getTickerInitDataTV($symbol)
    {
//        $since = Carbon::now()->subDays(30);
        $tickerHistory =self::getTickerHistory($symbol);

//        [{ time: '2018-10-19', open: 180.34, high: 180.99, low: 178.57, close: 179.85 },]
        $tickerHistoryConverted = [];
        $prevTickerPrice = 0;

        foreach ($tickerHistory as $ticker){
            if($prevTickerPrice ==0){
                $prevTickerPrice = $ticker->close;
            }
            $tickerHistoryConverted[] = ['time'=>$ticker->created_at->getTimestamp(),'open'=>$prevTickerPrice,'high'=> $ticker->high, 'low'=> $ticker->low, 'close'=> $ticker->close , 'value'=>$ticker->close];
            $prevTickerPrice = $ticker->open;
//            error_log(print_r($ticker->close,1));
        }
            return $tickerHistoryConverted;
    }
}