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

?>