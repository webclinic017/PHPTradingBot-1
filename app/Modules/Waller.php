<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/25/18
 * Time: 01:26
 */

namespace App\Modules;


use App\Brick;
use App\Modules;
use App\Signal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;

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
            // in progress
            //
            $lastCache = Cache::get('lastWallUpdate') - time();
            if($lastCache > 10){
                $statusWaller = false;
            }else{
                $statusWaller = true;

            }
            return view('Waller::setting', [
                'config' => $this->getConfig(),
                'wall' => false,
                'statusWaller'=> $statusWaller
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
    /**
     * @param $command
     * @param $service
     * @param bool $background
     * @return bool
     * @throws \Exception
     */
    public static function systemctl($service, $command )
    {
//        $process = new Process(['/path/to/php', '--define', 'memory_limit=1024M', '/path/to/script.php']);
//$process->setWorkingDirectory();
//        if ($service == 'all') {
//            self::systemctl('ticker', $command);
//            self::systemctl('orders', $command);
//            self::systemctl('signal', $command);
//            sleep(2);
//            return true;
//        }


//        $phpBinary = exec("which php");
        $process_which_php = Process::fromShellCommandline("which php");
        $process_which_php->run(null);
        $phpBinary = $process_which_php->getOutput();
        if (!$phpBinary) {
            throw new \Exception('cannot find php binary');
        }

//        $cmd = 'cd ' . base_path() . '; ';
//        error_log(print_r($cmd,1));
        switch ($command) {
            case 'status':

                $process = new Process(['/bin/bash','./bash/service_status.sh',$service]);
                $process->setWorkingDirectory(base_path());

//                $process->setWorkingDirectory(base_path().'/bash');
                $process->run();
                // executes after the command finishes
                if (!$process->isSuccessful()) {
//                    error_log('Exception ./service_status.sh\''.$service.$process->getOutput());
                    throw new ProcessFailedException($process);
                }
                if ($process->getOutput() != 0) {
//                    error_log('TRUE ./service_status.sh\''.$service.$process->getOutput());

                    return true;
                }
//                error_log('false ./service_status.sh\''.$service.$process->getOutput());
                return false;
            case 'restart':
                $stop = self::systemctl($service, 'stop');
                $start = self::systemctl($service, 'start');
                if ($start && $stop) {
//                    error_log('true ./service_restart.sh\'');
                    return true;
                }

//                error_log('false ./service_restart.sh\'');

                return false;
            case 'start':
//                $process = new Process(['php','artisan','daemon:'.$service,'&>/dev/null','&']);
                $process = new Process(['/bin/bash','./bash/service_start.sh',$service]);
                $process->setWorkingDirectory(base_path());
                $process->run();
                if (!$process->isSuccessful()) {
                    error_log('false ./service_start.sh\''.$service.$process->getErrorOutput());
                    throw new ProcessFailedException($process);
                }
//                error_log('here ./service_start.sh\''.$service.$process->getOutput());
                return $process->isSuccessful();
                break;
            case 'stop':
//                $cmd .= "kill $(ps aux | grep 'daemon:$service' | grep -v grep | awk '{print $2}')";
                $process = new Process(['/bin/bash','./bash/service_stop.sh',$service]);
                $process->setWorkingDirectory(base_path());
                $process->run();
                if (!$process->isSuccessful()) {
//                                        error_log('false ./service_stop.sh\''.$service.$process->getOutput());

                    throw new ProcessFailedException($process);
                }
//                                error_log('here ./service_stop.sh\''.$service.$process->getOutput());

                return $process->isSuccessful();

        }

//        error_log(print_r($cmd,1));

//        $process = Process::fromShellCommandline($cmd);
//        if (!$process->isSuccessful()) {
//            throw new ProcessFailedException($process);
//        }

//        error_log('END Process'.print_r($process->getOutput(),1));
//        $process->wait();
//        error_log($process->getOutput());

        // child process
//            $result = exec($cmd, $output, $return);
        return self::systemctl($service, 'status');



    }
}