<?php
use App\Ticker;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::group(['middleware' => 'auth'], function () {
    /*
     * Ajax/Post Routes
     */
    Route::get('/loadFavorites', 'HomeController@favorites');
    Route::get('/toggleFavorite/{symbol}', 'HomeController@toggleFavorite');
    Route::get('/loadRecentOrders', 'HomeController@recentOrders');



    Route::get('/', 'HomeController@positions');
    Route::get('/signals', 'HomeController@signals')->name('signals');
    Route::get('/system', 'HomeController@system')->name('system');
    Route::get('/system/ctl/{command}/{service}', 'HomeController@systemCtl')->name('systemCtl');
    Route::get('/events', 'HomeController@events')->name('events');
    Route::get('/history/', 'HomeController@history')->name('history');
    Route::get('/history/{column?}/{sort?}', 'HomeController@history')->name('sortHistory');
    Route::get('/positions/{id?}', 'HomeController@positions')->name('positions');
    Route::get('/positions/{id?}/show', 'HomeController@positions')->name('showSymbol');
    Route::get('/positions/toggleTrailing/{id}', 'HomeController@toggleTrailing')->name('toggleTrailing');
    Route::get('/positions/close/{id}', 'HomeController@closePosition')->name('closePosition');
    Route::get('/positions/new/{type}/{market}/{quantity}/{tp?}/{sl?}/{ttp?}/{tsl?}', 'HomeController@newPosition')->name('newPosition');
    Route::get('/positions/table/open', 'HomeController@openTable')->name('openTable');
    Route::post('/positions/edit/{id}', 'HomeController@editPosition')->name('editPosition');
    Route::post('/positions/save', 'HomeController@savePosition')->name('savePosition');
    Route::get('/modules', 'HomeController@modules')->name('modules');
    Route::get('/modules/enable/{id}', 'HomeController@enableModule')->name('enableModule');
    Route::get('/modules/disable/{id}', 'HomeController@disableModule')->name('disableModule');
    Route::get('/modules/install/{name}', 'HomeController@installModule')->name('installModule');
    Route::get('/modules/uninstall/{id}', 'HomeController@uninstallModule')->name('uninstallModule');
    Route::post('/saveSettings', 'HomeController@saveSettings')->name('saveSettings');
    Route::post('/saveOrderDefaults', 'HomeController@saveOrderDefaults')->name('saveOrderDefaults');
    try {
        if (!empty(\App\Modules::getMenus())) {
            foreach (\App\Modules::getMenus() as $menu) {
                Route::match(array('GET', 'POST'), '/modules/page/' . $menu['route'], function (\Illuminate\Http\Request $request) use ($menu) {
                    $module = \App\Modules::init($menu['module']);
                    return view('modulePage', [
                        'module' => $menu['module'],
                        'output' => $module->{$menu['route'] . 'Page'}($request)
                    ]);

                })->name($menu['route']);
            }
        }
    } catch (\Exception $exception) {

    }
});

Route::get('/debug/css', function () {
    return view('css');
});
Route::get('/tickers/{symbol}/history/{fromTime}', function ($symbol,$fromTime) {
    $tickerHistory = Ticker::getTickerHistory($symbol,$fromTime);
    $tickerHistoryConverted = [];
    $prevTickerPrice = 0;
    foreach ($tickerHistory as $ticker){
        if($prevTickerPrice ==0){
            $prevTickerPrice = $ticker->close;
        }
        $tickerHistoryConverted[] = ['time'=>$ticker->created_at->getTimestamp(),'open'=>$prevTickerPrice,'high'=> $ticker->high, 'low'=> $ticker->low, 'close'=> $ticker->close , 'value'=>$ticker->close];
        $prevTickerPrice = $ticker->open;
    }
    echo json_encode($tickerHistoryConverted);
    return ;
});
Route::get('/tickers/{symbol}/price', function ($symbol) {
    echo json_encode(\App\BithumbTradeHelper::getPrice($symbol));
    return ;
});

Route::get('/debug', function () {
//    $bithumb = \App\BithumbTradeHelper::getBithumb();
//    if(!$bithumb->getResponse(new ConfigRequest())->isError()){
//        foreach ($bithumb->response->getData()->spotConfig as $symbol) {
//            $notions[$symbol->symbol] = $symbol;
//        }
//    }

//    dd( $notions);
//
//    $bithumb = \App\BithumbTradeHelper::getBithumb();
//
//   echo '<pre> '.var_dump(\App\BithumbTradeHelper::getTick('BIP-USDT')).'</pre>';
//    $bithumb->ticker(false, function ($api, $symbol, $tick) {
//        try {
//
//            error_log('!!!!!!HEEEEEEEY!!!'.print_r($tick,1));
////            $this->onTickEvent($tick,$eligibleModules);
//        } catch (\Exception $exception) {
//            error_log('!!!!!!HEEEEEEEY!!!'.$exception->getMessage());
//
//            $this->alert($exception->getMessage());
//        }
//
//    });
////    $request = new TickerRequest();
////    $request->symbol = 'BIP-USDT';
////    $price = $bithumb->execute($request);
//    $time1 = now();
//    $price = json_decode(\App\BithumbTradeHelper::getTick('BIP-USDT'));
//    for ($i=0;$i<= 4;$i++){
//        $_ = $binance->marketBuyTest('BTCUSDT',40);
//        $_ = $binance->marketBuyTest('ETHUSDT',40);
//        dump($_);
//    }
//
//    $_ = $bithumb->marketBuyTest('BTCUSDT',40);
//    $_ = $bithumb->marketBuyTest('ETHUSDT',40);
//    $_ = $bithumb->marketBuyTest('BTCUSDT',40);
//    $_ = $bithumb->marketBuyTest('ETHUSDT',40);
//    $_ = $bithumb->marketBuyTest('BTCUSDT',40);
//    $_ = $bithumb->marketBuyTest('ETHUSDT',40);
//    $_ = $bithumb->marketBuyTest('BTCUSDT',40);
//    $_ = $bithumb->marketBuyTest('ETHUSDT',40);
//    $_ = $bithumb->marketBuyTest('BTCUSDT',40);
//    $_ = $bithumb->marketBuyTest('ETHUSDT',40);

//    $bithumb = \App\BithumbTradeHelper::getBithumb();
//        dd( $bithumb);

//print_r($bithumb->execute($request),1);

    $time2 = now();

    dd($time2->diffForHumans($time1));
//dd(print_r($jsondata,1));


//    dd(\App\TradeHelper::getPrice('CMTETH'));
//    dd(\App\TradeHelper::getTick('CMTETH'));
//dd(\Illuminate\Support\Facades\Cache::get('CMTETH'));

//    print_r(\App\Setting::all()->toArray());
//    \App\Setting::query()->truncate();

//    $amount = 11; // usdt
//    $symbol = 'BTCUSDT';
//    $notions = \App\TradeHelper::getNotions($symbol);
//    $stepSize = \App\TradeHelper::getStepSize($symbol);
//    $baseAsset = $notions['baseAsset'];
//    $binance = \App\TradeHelper::getBinance();
//    $quantity = \App\TradeHelper::calcUSDT($amount, $baseAsset);
//    $quantity = $binance->roundStep($quantity,$stepSize);
//    $res = $binance->marketBuyTest($symbol, $quantity);
//    dd($res);


//    $amount = 11; // usdt
//    $symbol = 'BTCUSDT';
//    $notions = \App\TradeHelper::getNotions($symbol);
//    $stepSize = \App\TradeHelper::getStepSize($symbol);
//    $baseAsset = $notions['baseAsset'];
//    $binance = \App\TradeHelper::getBinance();
//    $quantity = \App\TradeHelper::calcUSDT($amount, $baseAsset);
//    $quantity = $binance->roundStep($quantity, $stepSize);
//    $res = $binance->marketSellTest($symbol, $quantity);
//    dd($res);
});


Route::get('/home', 'HomeController@index')->name('home');
