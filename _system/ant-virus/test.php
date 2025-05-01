<?php

require 'Clamav.php';
// Directory where test files will be written
$test_dir = 'ant-virus/';


// EICAR is a test string for AV scanners: https://en.wikipedia.org/wiki/EICAR_test_file

$good_test = 'This is a safe string!';

// Tests using the local socket

echo "\nTesting using the local socket option...\n";
$clamav = new Clamav();
if(file_put_contents("$test_dir/clamav_test.txt", $good_test)) {
    echo "Testing a good file...\n";
    if($clamav->scan("$test_dir/clamav_test.txt")) {
        echo "YAY, file is safe!\n";
    } else {
        echo "BOO, file is a virus.  Message: " . $clamav->getMessage() . "\n";
    }
   // unlink("$test_dir/clamav_test.txt");
}
/*
if(file_put_contents("$test_dir/clamav_test.txt", $bad_test)) {
    echo "Testing a bad file...\n";
    if($clamav->scan("$test_dir/clamav_test.txt")) {
        echo "YAY, file is safe!\n";
    } else {
        echo "BOO, file is a virus.  Message: " . $clamav->getMessage() . "\n";
    }
    unlink("$test_dir/clamav_test.txt");
}
*/