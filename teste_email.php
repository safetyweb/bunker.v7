<?php

include '_system/_functionsMain.php'; 
//require('sendinblue/Mailin.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include 'sendinblue/Mailin.php';


    require('sendinblue/Mailin.php');
    $mailin = new Mailin("https://api.sendinblue.com/v2.0","2BKb0cQDHw84zhZm");
    $data = array( "to" => array("ricardolara.ti@gmail.com"=>"to whom!"),
        "from" => array("riicardolara@gmail.com", "from email!"),
        "subject" => "My subject",
        "html" => "This is the <h1>HTML</h1>"
	);
 
    //var_dump($mailin->send_email($data));
	
	echo '<pre>';
	print_r($mailin->send_email($data));
	echo '</pre>';
?>

