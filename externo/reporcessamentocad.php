<?php
include '../_system/_functionsMain.php';
//$connadm=$PROCESSAMENTO-> connAdm ();
  $contemp=connTemp(412,'');

/*$sql="SELECT c.NUM_CGCECPF FROM log_canal  cn
INNER JOIN clientes c ON c.COD_CLIENTE=cn.COD_CLIENTE
WHERE cn.cod_tipo=1 AND cn.cod_empresa=415 AND dat_ativ IS null";*/
$sql="SELECT c.NUM_CGCECPF FROM clientes  c WHERE  c.cod_empresa=412 AND NUM_CGCECPF!=0";
  
$rs=mysqli_query($contemp, $sql);
while($dados=mysqli_fetch_assoc($rs))
{ 
    ob_start();
     
	  
    
    $xml1="select * from origemcadastro where num_cgcecpf=$dados[NUM_CGCECPF] ORDER BY COD_ORIGEM asc LIMIT 1;";
    $rsxml= mysqli_fetch_assoc(mysqli_query($contemp, $xml1));
   
    
     $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "http://soap.bunker.mk/?wsdl",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 0,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $rsxml['DES_VENDA'],
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: text/xml"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
             echo "cURL Error #:" . $err;


            } else {         
             
                    $doc = new DOMDocument();
                    libxml_use_internal_errors(true);
                    $doc->loadHTML($response);
                    libxml_clear_errors();
                    $xml1 = $doc->saveXML($doc->documentElement);
                    //$xml = simplexml_load_string($xml);
                      $xml1 = simplexml_load_string($xml1,'SimpleXMLElement',LIBXML_NOCDATA);
                      $json = json_encode($xml1);
                      $array1 = json_decode($json,TRUE);
                      echo '<pre>';
                      print_r($array1);
                      echo '<pre>';
                      sleep(3);
            }
   
} 
