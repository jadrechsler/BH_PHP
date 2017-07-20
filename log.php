<?php

$logDirectory = 'logs';

if (!file_exists($logDirectory) && !is_dir($logDirectory)) {
    mkdir($logDirectory);         
} 


function logAppend($data) {
    global $logDirectory;

    $today = date('d_n_Y');

    $hour = date('G')+1;
    $minute = date('i');
    $second = date('s');

    $time = $hour.':'.$minute.':'.$second;
    $prefix = $time.' ---- ';

    $filename = $logDirectory.'/'.$today.'.log';

    if (file_exists($filename)) {
        $file = fopen($filename, 'a');

        fwrite($file, $prefix.$data."\r\n");

        fclose($file);
    } else {
        $file = fopen($filename, 'w');

        fwrite($file, $prefix.$data."\r\n");

        fclose($file);
    }
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function getLogList() {
    global $logDirectory;
    $list = array();

    foreach (glob($logDirectory.'/*.log') as $log) {
        $name = get_string_between($log, '/', '.');
        array_push($list, $name);
    }

    return $list;
}

function getLogAsHTML($name) {
    global $logDirectory;

    $location = $logDirectory.'/'.$name.'.log';

    if (!file_exists($location))
        return '';

    $log = fopen($location, 'r');

    $fullFile = '';

    while ($line = fgets($log)) {
        $fullFile .= $line;
    }

    fclose($log);

    return $fullFile;
}
?>