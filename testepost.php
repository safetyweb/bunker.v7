<?php
//PHP_INT_SIZE
@ini_set('output_buffering','4092');
@ini_set('memory_limit', '1024M');
@ini_set('post_max_size', '2048');
@ini_set('max_input_vars', '3000');
@ini_set('zlib.output_compression', 'ON'); 


//ini_set('max_execution_time', '3600');
//ini_set('max_input_vars', '30000');
@ini_set('innodb_lock_wait_timeout', '120');
@ini_set('upload_max_filesize', '1024M');

include './_system/_functionsMain.php';
echo fnDebug('true');
echo 'display_errors = ' . ini_get('display_errors') . "\n";
echo 'register_globals = ' . ini_get('register_globals') . "\n";
echo 'post_max_size = ' . ini_get('post_max_size') . "\n";
echo 'post_max_size+1 = ' . (ini_get('post_max_size')+1) . "\n";
echo 'post_max_size in bytes = ' . return_bytes(ini_get('post_max_size'));

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}
     phpinfo();