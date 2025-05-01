<?php
function getUrl($url)
{
    $ch = curl_init(); 
    $timeout = 30; // set to zero for no timeout 
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 	
	//curl_setopt ($ch,CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt ($ch, CURLOPT_URL, $url); 
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_PROXY, "tcp://72.221.196.145"); //your proxy url
    curl_setopt($ch, CURLOPT_PROXYPORT, "4145"); // your proxy port number 
    //curl_setopt($ch, CURLOPT_PROXYUSERPWD, "username:pass"); //username:pass 
    $file_contents = curl_exec($ch); 
    curl_close($ch); 
    return $file_contents;
}
 echo  getUrl("https://totem.bunker.mk/consulta_V2.do?key=qfQ8Qoq6CzUuXhzo2aS66rGOJZp£TH9Ho29awNxU8XEm0xjpwELBBIw¢¢");
?>
