<?php
include_once '../../_system/_functionsMain.php';


$sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='22' AND apar.LOG_ATIVO='S'";

$rwempresa = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlempresa));
function sms_twilo($base64,$dadosenvio,$senha)
{
   
    $url = 'https://api.twilio.com/2010-04-01/Accounts/AC6c9f8d8df52bd49f9b8f632ae8c6b371/Messages.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    
    foreach ($dadosenvio as  $value){
         $data = array(
                        'To' => '+5548996243831',
                        'From' => '+14027519610',
                        'Body' => 'ola voce tem saldo',
                        'StatusCallback' => 'http://externo.bunker.mk/twilo/twl/'
                        );
        $username = 'AC6c9f8d8df52bd49f9b8f632ae8c6b371';
        $password = '16d80836b999619e6b879ce19428f46d';
        
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
    
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $ret[]= json_decode($response,true);
    }
      curl_close($curl);
      return $ret;
    
    
    
}

$sqldadoscli="SELECT * FROM clientes WHERE num_cgcecpf IN ('01734200014','39648555885')";
$rwdados=mysqli_query(connTemp(7,''), $sqldadoscli);

while ($rowdados = mysqli_fetch_assoc($rwdados)) {
  $CLIE_SMS_L[]=array("from"=> '+14027519610',
                      "to" =>  '+55'.$rowdados[NUM_CELULAR], 
                      "mensagem"=>'ola voce tem saldo',                   
                      "DataAgendamento"=> "data",
                      "Codigointerno"=> base64_encode("3||7||849525")
                     );  
}

$base64= base64_encode($rwempresa[DES_USUARIO].':'.$rwempresa[DES_AUTHKEY]);
$response=sms_twilo($base64,$CLIE_SMS_L,$rwempresa[DES_USUARIO]);
 echo '<pre>';
 print_r($response);
echo '</pre>';

   ?>