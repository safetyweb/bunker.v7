<?php

include '../_system/_functionsMain.php';
//$connadm=$PROCESSAMENTO-> connAdm ();
  $contemp=connTemp(109,'');

$sql="SELECT DES_VENDA FROM origemvenda o
WHERE o.cod_empresa='109'
and o.COD_PDV NOT IN 
      (SELECT cod_vendapdv FROM vendas 
		 WHERE cod_vendapdv=o.COD_PDV 
		    AND cod_empresa=109)
AND o.dat_cadastr BETWEEN '2020-12-04 00:00:00' AND '2021-01-05 21:35:00'
 ORDER BY o.cod_origem ASC ";

$rs=mysqli_query($contemp, $sql);
while($dados=mysqli_fetch_assoc($rs))
{ ob_start();
      
   $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "http://soap.bunker.mk/?wsdl",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 0,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $dados['DES_VENDA'],
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
          $xml = $doc->saveXML($doc->documentElement);
          //$xml = simplexml_load_string($xml);
        $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        if($array [body][envelope][body][inserevendaresponse][inserevendaresponse][coderro]=='47')
		{
		echo '<pre>';
        print_r($dados['DES_VENDA']);
        echo '<pre>';
			
		}			
        }
        
  ob_end_flush();
ob_flush();
flush();
} 
//header("Refresh:1; url=eprocessamento.php");