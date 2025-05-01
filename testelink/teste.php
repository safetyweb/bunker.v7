<?php
$url = "http://webservices.dnamais.com.br/online/WSIntegracaoDNAOnline.asmx?wsdl";


for ($x = 0; $x <= 5; $x++) {
$start_time = time();
$headers = get_headers($url);
echo $headers[0]."<br>";
$end_time = time();
echo $end_time - $start_time." Seg<br>";


  
$start_time = time();
$response = file_get_contents($url);
echo $http_response_header[0]."<br>";
$end_time = time();
echo $end_time - $start_time." Seg<br>";

$start_time = time();
$handle = curl_init($url);
curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($handle, CURLOPT_NOBODY, 1); // and *only* get the header 
/* Get the HTML or whatever is linked in $url. */
$response = curl_exec($handle);
/* Check for 404 (file not found). */
$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
// if($httpCode == 404) {
    // /* Handle 404 here. */
// }
echo $httpCode."<br>";
curl_close($handle);
$end_time = time();
echo $end_time - $start_time." Seg<br>";

echo '</br>';




}

//print str_replace(array("\n"," "),array("<br>","&nbsp;"), var_export(print_r(print_r(get_headers($url))),true))."<br>"; 
//5 print str_replace(array("\n"," "),array("<br>","&nbsp;"), var_export(print_r(get_headers($url, 1)),true))."<br>"; 

?>

