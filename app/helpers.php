<?php
// Application helper functions

if(! function_exists('config')){
    /**
     * Get config values
     * @param $value
     * @return mixed
     * @throws Exception
     */
    function config($value){

        $file = require config_path('tika.config.php');

        if (! file_exists(config_path('tika.config.php')))
            throw new \Exception('Config file '.basename($file).' not found');

        $explode = explode(':', $value);

        foreach ($explode as $vartmp) {
            if (isset($file[$vartmp]))
                $file = $file[$vartmp];
        }

        return $file;
    }
}

if(! function_exists('_checkPHPVersion')){
    /**
     * Check php version of system
     * @param $min
     * @return bool
     * @throws Exception
     */
    function _checkPHPVersion($min){

        if (! version_compare(phpversion(), $min, '>='))
            throw new \Exception(
                'Your PHP version is too old ! PHP '.$min.' is required.'
            );
        return true;

    }
}

if (! function_exists('json_last_error_msg')) {
    /**
     * @return mixed|string
     */
    function json_last_error_msg(){
        static $errors = [
            JSON_ERROR_NONE            => null,
            JSON_ERROR_DEPTH           => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH  => 'Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR       => 'Unexpected control character found',
            JSON_ERROR_SYNTAX          => 'Syntax error, malformed JSON',
            JSON_ERROR_UTF8            => 'Malformed UTF-8 characters,
            possibly incorrectly encoded'
        ];
        $error = json_last_error();
        return array_key_exists($error, $errors) ? $errors[$error] :
            "Unknown error ({$error})";
    }
}

if(! function_exists('getInterfacesCommand')){
    /**
     * Returns command line for retreive interfaces
     * @param $misc
     * @param $commands
     * @return null|string
     */
    function getInterfacesCommand($commands)
    {
        $ifconfig = whichCommand($commands['ifconfig'], ' | awk -F \'[/  |: ]\' \'{print $1}\' | sed -e \'/^$/d\'');

        if (!empty($ifconfig)) {
            return $ifconfig;
        } else {
            $ip_cmd = whichCommand($commands['ip'], ' -V', false);

            if (!empty($ip_cmd)) {
                return $ip_cmd.' -oneline link show | awk \'{print $2}\' | sed "s/://"';
            } else {
                return null;
            }
        }
    }
}

if(! function_exists('getIpCommand')){
    /**
     * Returns command line for retreive IP address from interface name
     * @param $misc
     * @param $commands
     * @param $interface
     * @return null|string
     */
    function getIpCommand($commands, $interface)
    {
        $ifconfig = whichCommand($commands['ifconfig'], ' '.$interface.' | awk \'/inet / {print $2}\' | cut -d \':\' -f2');

        if (!empty($ifconfig)) {
            return $ifconfig;
        } else {
            $ip_cmd = whichCommand($commands['ip'], ' -V', false);
            if (!empty($ip_cmd)) {
                return 'for family in inet inet6; do '.
                $ip_cmd.' -oneline -family $family addr show '.$interface.' | grep -v fe80 | awk \'{print $4}\' | sed "s/\/.*//"; ' .
                'done';
            } else {
                return null;
            }
        }
    }
}

if(! function_exists('getSize')){
    /**
     * Returns human size
     *
     * @param  float $filesize   File size
     * @param  int   $precision  Number of decimals
     * @return string            Human size
     */
    function getSize($filesize, $precision = 2){

        $units = ['', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'];

        foreach ($units as $idUnit => $unit) {
            if ($filesize > 1024)
                $filesize /= 1024;
            else
                break;
            return round($filesize, $precision).' '.$units[$idUnit].'B';
        }

        return null;
    }
}

if(! function_exists('getDNShost')){
    /**
     * Get dnf nameserver-s
     * @return array
     */
    function getDNShost(){
        $datas = 'N.A';

        if(exec('cat /etc/resolv.conf | awk \'/a/ { print $all }\' ' , $output))
            $datas = $output[1];

        return explode(' ',$datas);
    }
}

if(! function_exists('getHostname')){
    /**
     * @return string
     */
    function getHostname(){
        return php_uname('n');
    }
}

if(! function_exists('getCpuCoresNumber')){
    /**
     * Returns CPU cores number
     * @return int | Number of cores
     */
    function getCpuCoresNumber(){
        if (!($num_cores = shell_exec('/bin/grep -c ^processor /proc/cpuinfo'))) {
            if (!($num_cores = trim(shell_exec('/usr/bin/nproc')))) {
                $num_cores = 1;
            }
        }

        if ((int)$num_cores <= 0)
            $num_cores = 1;

        return (int)$num_cores;
    }
}

if(! function_exists('getLanIp')){
    /**
     * Returns server IP
     *
     * @return string Server local IP
     */
    function getLanIp(){
        return $_SERVER['SERVER_ADDR'];
    }
}

if(! function_exists('getHumanTime')){
    /**
     * Seconds to human readable text
     * Eg: for 36545627 seconds => 1 year, 57 days, 23 hours and 33 minutes
     *
     * @return string Text
     */
    function getHumanTime($seconds)
    {
        $units = [
            'year'   => 365*86400,
            'day'    => 86400,
            'hour'   => 3600,
            'minute' => 60,
            // 'second' => 1,
        ];

        $parts = [];

        foreach ($units as $name => $divisor)
        {
            $div = floor($seconds / $divisor);

            if ($div == 0)
                continue;
            else
                if ($div == 1)
                    $parts[] = $div.' '.$name;
                else
                    $parts[] = $div.' '.$name.'s';
            $seconds %= $divisor;
        }

        $last = array_pop($parts);

        if (empty($parts))
            return $last;
        else
            return join(', ', $parts).' and '.$last;
    }
}

if(! function_exists('whichCommand')){
    /**
     * Returns a command that exists in the system among $cmds
     *
     * @param  array  $cmds             List of commands
     * @param  string $args             List of arguments (optional)
     * @param  bool   $returnWithArgs   If true, returns command with the arguments
     * @return string                   Command
     */
    function whichCommand($cmds, $args = '', $returnWithArgs = true)
    {
        $return = '';

        foreach ($cmds as $cmd)
        {
            if (trim(shell_exec($cmd.$args)) != '')
            {
                $return = $cmd;

                if ($returnWithArgs)
                    $return .= $args;

                break;
            }
        }

        return $return;
    }
}

if(! function_exists('pluralize')){
    /**
     * Allows to pluralize a word based on a number
     * Ex : echo 'mot'.Misc::pluralize(5); ==> prints mots
     * Ex : echo 'cheva'.Misc::pluralize(5, 'ux', 'l'); ==> prints chevaux
     * Ex : echo 'cheva'.Misc::pluralize(1, 'ux', 'l'); ==> prints cheval
     *
     * @param  int       $nb         Number
     * @param  string    $plural     String for plural word
     * @param  string    $singular   String for singular word
     * @return string                String pluralized
     */
    function pluralize($nb, $plural = 's', $singular = '')
    {
        return $nb > 1 ? $plural : $singular;
    }
}

if(! function_exists('scanPort')){
    /**
     * Checks if a port is open (TCP or UPD)
     *
     * @param  string   $host       Host to check
     * @param  int      $port       Port number
     * @param  string   $protocol   tcp or udp
     * @param  integer  $timeout    Timeout
     * @return bool                 True if the port is open else false
     */
    function scanPort($host, $port, $protocol = 'tcp', $timeout = 3)
    {
        if ($protocol == 'tcp') {
            $handle = @fsockopen($host, $port, $errno, $errstr, $timeout);

            if (!$handle) {
                return false;
            } else {
                fclose($handle);
                return true;
            }
        } elseif ($protocol == 'udp') {
            $handle = @fsockopen('udp://'.$host, $port, $errno, $errstr, $timeout);

            socket_set_timeout($handle, $timeout);

            $write = fwrite($handle, 'x00');

            $startTime = time();

            $header = fread($handle, 1);

            $endTime = time();

            $timeDiff = $endTime - $startTime;

            fclose($handle);

            if ($timeDiff >= $timeout)
                return true;
            else
                return false;
        }

        return false;
    }
}