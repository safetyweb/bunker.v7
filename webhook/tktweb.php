<?php
//ngCIgJjewoWOdIdF2QTt36oInXVhyyUwPZj2opCSA3o¢
include '../_system/_functionsMain.php';
include './gettkt/geratkt.php';
/*map do array
    [0] => Usuario
    [1] => senha
    [2] => loja
    [3] => maquina
    [4] => cod_empresa
  fnEncode('ws.farmacia;123456;13;teste;7');
 */
$CPFCARTAO=$_GET['c1'];
$arrayChaveAcesso = explode(";", fnDecode($_GET['key']));

$geturl=geratkt($CPFCARTAO,$arrayChaveAcesso); 

if($geturl['msgerro']=='OK')
{
         $curl = curl_init();
         curl_setopt_array($curl, array(
           CURLOPT_URL => $geturl['urltktmania'],
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => "",
           CURLOPT_HTTPHEADER => array(
             "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
             "cache-control: no-cache"
           ),
         ));

         $response = curl_exec($curl);
         $err = curl_error($curl);

         curl_close($curl);

         if ($err) {
           echo "cURL Error #:" . $err;
         } else {
           echo $response;
         }
}else{
   echo $geturl['msgerro']; 
}   
 /*$enc= fnEncode('ws.farmacia;123456;13;teste;7');
 echo $enc.'<br>';
 $dec=fnDecode($enc);
 $arrayCampos = explode(";", $dec);*/
