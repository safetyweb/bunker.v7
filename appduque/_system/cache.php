<?php
function getUrl () {
    if (!isset($_SERVER['REQUEST_URI'])) {
    $url = $_SERVER['REQUEST_URI'];
    } else {
    $url = $_SERVER['SCRIPT_NAME'];
    $url .= (!empty($_SERVER['QUERY_STRING']))? '?' . $_SERVER[ 'QUERY_STRING' ] : '';

    }
    return $url;
}

//getUrl gets the queried page with query string
function cache ($buffer) { //page's content is $buffer
    $url = getUrl();
    $filename = md5($url) . '.cache';
    $data = time() . '¦' . $buffer;
    $filew = fopen("cache/" . $filename, 'w');
    fwrite($filew, $data);
    fclose($filew);
    return $buffer;
}

function display () {
    $url = getUrl();
    $filename = md5($url) . '.cache';
    if (!file_exists("cache/" . $filename)) {
    return false;
    }
    $filer = fopen("cache/" . $filename, 'r');
    $data = fread($filer, filesize("cache/" . $filename));
    fclose($filer);
    $content = explode('¦', $data, 2);
    if (count($content)!= 2 OR !is_numeric($content['0'])) {
        return false;
    }
    if (time()-(100) > $content['0']) { // 100 is the cache time here!!!
        return false;
    }
        echo $content['1'];
        die();
}







?>