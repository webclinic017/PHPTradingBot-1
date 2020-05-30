<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/25/18
 * Time: 01:26
 */

namespace App\Modules;


use App\Modules;
use App\Signal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Waller extends Modules
{
    public static $description = 'Waller module create automatic walls on exchange pair';


    public function menus()
    {
        return [
            [
                'route' => 'Waller',
                'text' => 'Waller',
                'module' => 'Waller'
            ],
        ];
    }

//    public static function getConfig(){
//        return $this->config;
//    }

    public function WallerPage(Request $request)
    {
        if ($request->isMethod('post')) {
//            if ($request->get('exchange') == null) {
//                return redirect()->back()->withErrors('at least one exchange should be selected');
//            }
//            $exchange = array_keys($request->get('exchange'));
            $buyCovering = $request->get('buyCovering');
            $sellCovering = $request->get('sellCovering');
            $spread = $request->get('spread');
            $buyOrderAmount = $request->get('buyOrderAmount');
            $pair = $request->get('pair');

            $this->setConfig([
                'buyCovering' => $buyCovering,
                'sellCovering' => $sellCovering,
                'spread' => $spread,
                'buyOrderAmount' => $buyOrderAmount,
                'pair' => $pair,
            ]);

            return redirect()->back();

        } else {
            view()->addNamespace('Waller', app_path('Modules/Waller/view'));
            return view('Waller::setting', [
                'config' => $this->getConfig(),
                'wall' => false
            ]);
        }
    }

//    public function signalLoop()
//    {
//        $this->_getRiskLevels();
//        $this->_getSignals();
//
//        $config = $this->getConfig();
//        if (!$config)
//            return false;
//
//
//        if (!empty($signals = $this->getSignals())) {
//            foreach ($signals as $signal) {
//                if ($config['volume'] > $signal['basevolume'])
//                    continue;
//
//                if (!in_array($signal['exchange'], $config['exchange'] ?? []))
//                    continue;
//
//                $signal['module'] = 'MiningHamster';
//                Signal::firstOrCreate([
//                    'market' => $signal['market'],
//                    'lastprice' => $signal['lastprice'],
//                    'signalmode' => $signal['signalmode']
//                ], (array)$signal);
//            }
//        }
//    }

    public function getAllBricksOrderId()
    {
//        $signals = json_decode(Cache::get('signal'), true) ?? null;
//        $riskLevels = Cache::get('riskLevels') ?? null;
//        $signalsWithRiskLevels = [];
//        if ($signals) {
//            foreach ($signals as $i => $signal) {
//                $signalsWithRiskLevels[$i] = $signal;
//                if (isset($riskLevels[$signal['exchange'] . '-' . $signal['market']])) {
//                    $signalsWithRiskLevels[$i]['rl'] = $riskLevels[$signal['exchange'] . '-' . $signal['market']]->risklevel;
//                } else {
//                    $signalsWithRiskLevels[$i]['rl'] = 0;
//                }
//            }
//        }
        return false;
    }


//    protected function _getSignals()
//    {
//        $config = $this->getConfig();
//        $apikey = $config['apiKey'] ?? null;
//        $uri = "https://www.mininghamster.com/api/v2/" . $apikey;
//        $sign = hash_hmac('sha512', $uri, $apikey);
//        $ch = curl_init($uri);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:' . $sign));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $execResult = curl_exec($ch);
//        $obj = json_decode($execResult);
//        if ($obj) {
//            Cache::put('signal', json_encode($obj), Carbon::now()->addSeconds(10));
//        }
//        return $obj;
//    }
//
//    protected function _getRiskLevels()
//    {
//        $urlRL = "https://www.mininghamster.com/api/v2/risklevel/ticker";
//        $riskLevelRawContent = file_get_contents($urlRL);
//        $riskLevels = json_decode($riskLevelRawContent);
//        $riskLevelsAssoc = [];
//        foreach ($riskLevels->risklevel as $riskLevel) {
//            $riskLevelsAssoc[$riskLevel->exchange . '-' . $riskLevel->market] = $riskLevel;
//        }
//        Cache::put('riskLevels', $riskLevelsAssoc, Carbon::now()->addMinutes(5));
//        return $riskLevels;
//    }
}