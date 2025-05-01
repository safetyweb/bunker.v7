<?php
include '../_system/_functionsMain.php';
set_time_limit(1800);
$tempcom= connTemp('19', '');

$sql='Select * from origemvenda where  dat_cadastr >= "2019-07-01 23:59:00"';
$rs=mysqli_query($tempcom, $sql);
while ($xml= mysqli_fetch_assoc($rs))
{
  
   
   $curlatualiza = curl_init();
                curl_setopt($curlatualiza, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt_array($curlatualiza, array(
                  CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
                  CURLOPT_RETURNTRANSFER => true,
                 // CURLOPT_ENCODING => "utf-8",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS =>  "".$xml['DES_VENDA']."",
                  CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: text/xml",
                    "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
                  ),
                ));

                $responseAtualiza = curl_exec($curlatualiza);
                $err = curl_error($curlatualiza);
                curl_close($curlatualiza);
                if ($err) 
                {
                  echo "cURL Error #:" . $err;
                } else {
                    
                    echo $responseAtualiza.'<br><br><br>';
                }
}        

