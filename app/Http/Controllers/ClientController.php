<?php

/**
 * @package App\Http\Controllers
 * @name ClientController
 * @created_at 2016/09/23 11:33 AM
 * This file was generated with lemax command-line
 */

namespace App\Http\Controllers;
use Lemax\Foundation\BaseController;
use GuzzleHttp\Client;

class ClientController extends BaseController {

    /**
     * ClientController constructor.
     */
    public function __construct(){

        _checkPHPVersion(5.4);

        $this->header('application','json');
    }

    /**
     * Get cpu details
     */
    public function cpu()
    {
        // Number of cores
        $num_cores = getCpuCoresNumber();

        // CPU info
        $model      = 'N.A';
        $frequency  = 'N.A';
        $cache      = 'N.A';
        $bogomips   = 'N.A';
        $temp       = 'N.A';

        if ($cpuinfo = shell_exec('cat /proc/cpuinfo')) {

            $processors = preg_split('/\s?\n\s?\n/', trim($cpuinfo));

            foreach ($processors as $processor) {

                $details = preg_split('/\n/', $processor, -1, PREG_SPLIT_NO_EMPTY);

                foreach ($details as $detail) {

                    list($key, $value) = preg_split('/\s*:\s*/', trim($detail));

                    switch (strtolower($key)) {
                        case 'model name':
                        case 'cpu model':
                        case 'cpu':
                        case 'processor':
                            $model = $value;
                            break;

                        case 'cpu mhz':
                        case 'clock':
                            $frequency = $value.' MHz';
                            break;

                        case 'cache size':
                        case 'l2 cache':
                            $cache = $value;
                            break;

                        case 'bogomips':
                            $bogomips = $value;
                            break;
                    }
                }
            }
        }

        if ($frequency == 'N.A') {
            if ($f = shell_exec(
                'cat /sys/devices/system/cpu/cpu0/cpufreq/cpuinfo_max_freq')
            ) {
                $f = $f / 1000;
                $frequency = $f.' MHz';
            }
        }

        // CPU Temp
        if ($this->get('cpu:enable_temperature')) {

            if( exec(
                '/usr/bin/sensors | grep -E "^(CPU Temp|Core 0)" | cut -d \'+\' -f2 |
                cut -d \'.\' -f1', $t
            )) {

                if (isset($t[0]))
                    $temp = $t[0].' °C';

            } else {

                if (exec('cat /sys/class/thermal/thermal_zone0/temp', $t))
                    $temp = round($t[0] / 1000).' °C';

            }
        }

        echo json_encode([
            'model'      => $model,
            'num_cores'  => $num_cores,
            'frequency'  => $frequency,
            'cache'      => $cache,
            'bogomips'   => $bogomips,
            'temp'       => $temp,
        ]);

    }

    /**
     * Get Gateway ip Address
     * @return string
     */
    public function gatewayIpAddr()
    {
        $datas = 'N.A';

        if(exec('ip route | awk \'/a/ { print $3 }\'',$output))
            $datas = $output[0];

        return $datas;
    }

    /**
     * get Trace Route Host
     */
    public function traceRouteHost()
    {
        $datas = [];

        $hosts = config('traceroute_hosts');

        if(empty($hosts))
            $hosts = [ 'google.com' ];

        foreach ($hosts as $key => $host){
            exec('traceroute ' . $host . ' | awk \'/a/ { print ($all) }\'' , $output);

            array_push($datas, [
                'traceroute_to' => $host,
                'hosts' => []
            ]);

            foreach($output as $value){

                preg_match_all("'\((.*?)\)'",$value,$ip);

                array_push($datas[$key]['hosts'], $this->ipDetails($ip[1][0]));

            }
        }

        echo json_encode($datas);
    }

    /**
     * Get ip-address details
     * @param $ip
     * @return array
     */
    public function ipDetails($ip)
    {
        $res = (new Client())->request('GET', 'http://api.db-ip.com/v2/d747cd1166d5c4793a3b8678edb58eaaf219c45a/' . $ip);

        $datas = json_decode($res->getBody(),true);

        return [
            'ipAddress' => $datas['ipAddress'],
            'country' => $datas['countryName'],
            'city' => $datas['city']
        ];
    }

    /**
     * Check nameserver
     */
    public function nameServer()
    {
        echo json_encode(getDNShost());
    }

    /**
     * check Pppoe Status
     */
    public function PppoeStatus()
    {
        $datas = 'N.A';

        if(exec('pppoe-status | awk \'/^pppoe-status/ {print $4}\'' , $output)){
            $datas = $output;
        }

        echo json_encode($datas);
    }

    /**
     * Get memory details
     */
    public function memory()
    {
        $free = 0;

        if (shell_exec('cat /proc/meminfo')) {
            $free    = shell_exec('grep MemFree /proc/meminfo | awk \'{print $2}\'');
            $buffers = shell_exec('grep Buffers /proc/meminfo | awk \'{print $2}\'');
            $cached  = shell_exec('grep Cached /proc/meminfo | awk \'{print $2}\'');

            $free = (int)$free + (int)$buffers + (int)$cached;
        }

        // Total
        if (! $total = shell_exec('grep MemTotal /proc/meminfo | awk \'{print $2}\''))
            $total = 0;

        // Used
        $used = $total - $free;

        // Percent used
        $percent_used = 0;

        if ($total > 0)
            $percent_used = 100 - (round($free / $total * 100));

        echo json_encode([
            'used'          => getSize($used * 1024),
            'free'          => getSize($free * 1024),
            'total'         => getSize($total * 1024),
            'percent_used'  => $percent_used,
        ]);

    }

    /**
     * Get disk details
     */
    public function disk()
    {
        $datas = [];

        if (! exec('/bin/df -T -P | awk -v c=`/bin/df -T | grep -bo "Type" | awk -F: \'{print $2}\'` \'{print substr($0,c);}\' | tail -n +2 | awk \'{print $1","$2","$3","$4","$5","$6","$7}\'', $df)) {

            $datas[] = [
                'total'         => 'N.A',
                'used'          => 'N.A',
                'free'          => 'N.A',
                'mount'         => 'N.A',
                'percent_used'  => 0,
            ];

        } else {

            $mounted_points = [];

            foreach ($df as $mounted) {

                list(
                    $filesystem, $type, $total, $used,
                    $free, $percent, $mount
                    ) = explode(',', $mounted);

                if (strpos($type, 'tmpfs') !== false &&
                    config('disk:show_tmpfs') === false)
                    continue;

                if (!in_array($mount, $mounted_points)) {

                    $mounted_points = trim($mount);

                    $datas[] = [
                        'total'         => getSize($total * 1024),
                        'used'          => getSize($used * 1024),
                        'free'          => getSize($free * 1024),
                        'mount'         => $mount,
                        'percent_used'  => trim($percent, '%'),
                    ];
                }
            }

        }

        echo json_encode($datas);
    }

    /**
     * Get network details
     */
    public function network(){

        $datas    = [];

        $network  = [];

        // Possible commands for ifconfig and ip
        $commands = [
            'ifconfig' => [
                'ifconfig',
                '/sbin/ifconfig',
                '/usr/bin/ifconfig',
                '/usr/sbin/ifconfig'
            ],
            'ip' => [
                'ip',
                '/bin/ip',
                '/sbin/ip',
                '/usr/bin/ip',
                '/usr/sbin/ip'
            ]
        ];

        $getInterfaces_cmd = getInterfacesCommand($commands);

        if (
            is_null($getInterfaces_cmd) ||
            ! exec($getInterfaces_cmd, $getInterfaces)
        ) {
            $datas[] = [
                'interface' => 'N.A',
                'ip' => 'N.A'
            ];
        } else {

            foreach ($getInterfaces as $name) {

                $ip = null;

                $getIp_cmd = getIpCommand($commands, $name);

                if (is_null($getIp_cmd) || !(exec($getIp_cmd, $ip))) {
                    $network[] = [
                        'name' => $name,
                        'ip'   => 'N.A',
                    ];
                } else {
                    if (!isset($ip[0]))
                        $ip[0] = '';

                    $network[] = [
                        'name' => $name,
                        'ip'   => $ip[0],
                    ];
                }
            }

            foreach ($network as $interface) {

                // Get transmit and receive datas by interface
                exec(
                    'cat /sys/class/net/'.$interface['name'].'/statistics/tx_bytes',
                    $getBandwidth_tx
                );

                exec(
                    'cat /sys/class/net/'.$interface['name'].'/statistics/rx_bytes',
                    $getBandwidth_rx
                );

                $datas[] = [
                    'interface' => $interface['name'],
                    'ip'        => $interface['ip'],
                    'transmit'  => (getSize($getBandwidth_tx[0]) ? getSize($getBandwidth_tx[0]) : 'N.A'),
                    'receive'   => (getSize($getBandwidth_rx[0]) ? getSize($getBandwidth_rx[0]) : 'N.A'),
                ];

                unset($getBandwidth_tx, $getBandwidth_rx);
            }
        }

        array_push($datas,[
            'interface' => 'gateway',
            'ip'        => $this->gatewayIpAddr(),
            'transmit'  => 'N.A',
            'receive'   => 'N.A',
        ]);

        echo json_encode($datas);
    }

    /**
     * Get ping details
     */
    public function ping()
    {
        $datas = [];

        $hosts = config('ping_hosts');

        if (empty($hosts))
            $hosts = [ 'google.com' ];

        foreach ($hosts as $key => $host) {
            exec(
                '/bin/ping -qc 1 ' . $host['host'] . ' | awk -F/ \'/^rtt/ { print $5 }\'', $result );

            if (!isset($result[0])) {
                $result[0] = 0;
            }

            $datas = [
                'host' => $host['host'],
                'ping' => $result[0],
            ];

            unset($result);
        }

        echo json_encode($datas);
    }

    /**
     * las login info
     */
    public function last_login()
    {
        $datas = [];

        if (config('last_login:enable'))
        {
            if (!(exec('/usr/bin/lastlog --time 365 | /usr/bin/awk -F\' \' \'{ print $1";"$5, $4, $8, $6}\'', $users)))
            {
                $datas = [
                    'user' => 'N.A',
                    'date' => 'N.A',
                ];
            }
            else
            {
                $max = config('last_login:max');

                for ($i = 1; $i < count($users) && $i <= $max; $i++)
                {
                    list($user, $date) = explode(';', $users[$i]);

                    $datas = [
                        'user' => $user,
                        'date' => $date,
                    ];
                }
            }
        }
        echo json_encode($datas);
    }

    /**
     * Check services status
     */
    public function services()
    {
        $datas = [];

        $available_protocols = [
            'tcp', 'udp'
        ];

        $show_port = config('services:show_port');

        if (count(config('services:list')) > 0) {
            foreach (config('services:list') as $key => $service) {
                $host     = $service['host'];
                $name     = $service['name'];
                $protocol = isset($service['protocol']) && in_array($service['protocol'], $available_protocols) ? $service['protocol'] : 'tcp';

                $status = 0;

                if (scanPort($host, $service['port'], $protocol))
                    $status = 1;

                $datas[$key] = [
                    'name'      => $name,
                    'status'    => $status,
                    'port' => $show_port === true ? $service['port'] : 'd',
                ];

                if($show_port !== true)
                    unset($datas[$key]['port']);

            }
        }

        echo json_encode($datas);
    }

    /**
     * Swap information
     */
    public function swap()
    {
        // Free
        if (!($free = shell_exec('grep SwapFree /proc/meminfo | awk \'{print $2}\'')))
        {
            $free = 0;
        }

        // Total
        if (!($total = shell_exec('grep SwapTotal /proc/meminfo | awk \'{print $2}\'')))
        {
            $total = 0;
        }

        // Used
        $used = $total - $free;

        // Percent used
        $percent_used = 0;
        if ($total > 0)
            $percent_used = 100 - (round($free / $total * 100));


        $datas = [
            'used'          => getSize($used * 1024),
            'free'          => getSize($free * 1024),
            'total'         => getSize($total * 1024),
            'percent_used'  => $percent_used,
        ];

        echo json_encode($datas);
    }

    /**
     * System info
     */
    public function system()
    {
        date_default_timezone_set(@date_default_timezone_get());

        // Hostname
        $hostname = php_uname('n');

        // OS
        if (!($os = shell_exec('/usr/bin/lsb_release -ds | cut -d= -f2 | tr -d \'"\'')))
        {
            if (!($os = shell_exec('cat /etc/system-release | cut -d= -f2 | tr -d \'"\'')))
            {
                if (!($os = shell_exec('cat /etc/os-release | grep PRETTY_NAME | tail -n 1 | cut -d= -f2 | tr -d \'"\'')))
                {
                    if (!($os = shell_exec('find /etc/*-release -type f -exec cat {} \; | grep PRETTY_NAME | tail -n 1 | cut -d= -f2 | tr -d \'"\'')))
                    {
                        $os = 'N.A';
                    }
                }
            }
        }
        $os = trim($os, '"');
        $os = str_replace("\n", '', $os);

        // Kernel
        if (!($kernel = shell_exec('/bin/uname -r')))
        {
            $kernel = 'N.A';
        }

        // Uptime
        if (!($totalSeconds = shell_exec('/usr/bin/cut -d. -f1 /proc/uptime')))
        {
            $uptime = 'N.A';
        }
        else
        {
            $uptime = getHumanTime($totalSeconds);
        }

        // Last boot
        if (!($upt_tmp = shell_exec('cat /proc/uptime')))
        {
            $last_boot = 'N.A';
        }
        else
        {
            $upt = explode(' ', $upt_tmp);
            $last_boot = date('Y-m-d H:i:s', time() - intval($upt[0]));
        }

        // Current users
        if (! $current_users = exec('who -u | awk \'{ print $1 }\' ', $res) ) {
            $current_users = 'N.A';
        } else {
            $current_users = $res[0];
        }

        // Server datetime
        if (! $server_date = shell_exec('/bin/date') ) {
            $server_date = date('Y-m-d H:i:s');
        }


        $datas = [
            'hostname'      => $hostname,
            'os'            => $os,
            'kernel'        => $kernel,
            'uptime'        => $uptime,
            'last_boot'     => $last_boot,
            'current_users' => $current_users,
            'server_date'   => $server_date,
        ];

        echo json_encode($datas);
    }

    /**
     * get packet_loss of ip-s
     * @param $ips
     */
    public function packet_loss($ips)
    {

        $command = 'ping -c %d %s';

        $datas = [
            'status' => 100,
            'message' => 'Nothing to do',
            'datas' => []
        ];

        foreach ($ips as $address){

            $output = shell_exec(sprintf($command, 1, $address));

            if (preg_match(
                '/([0-9]*\.?[0-9]+)%(?:\s+packet)?\s+loss/',
                $output,
                $match
            )) {
                $datas['status'] = 200;
                $datas['message'] = "Everything's OK";
                array_push($datas['datas'],[
                    'label' => $address,
                    'value' => trim((float)$match[1])."%"
                ]);
            }
        }

        echo json_encode($datas);
    }

}