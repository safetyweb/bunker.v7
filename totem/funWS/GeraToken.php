<?php      
function GeraTokenFull($dadosenvio,$dadoslogin)
{		

      
	$curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://soap.bunker.mk/api/Geratoken.do',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "utf-8",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 120,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
                                    "tipoGeracao": "'.$dadosenvio[tipoGeracao].'",
                                    "nome": "'.$dadosenvio[nome].'",
                                    "cpf": "'.$dadosenvio[cpf].'",
                                    "celular": "'.$dadosenvio[celular].'",
                                    "email": "'.$dadosenvio[email].'",
                                    "whatsapp": "'.$dadosenvio[whatsapp].'"
                                }',
          CURLOPT_HTTPHEADER => array(
            'authorizationCode: '.$dadoslogin.'',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_encode($response,true);								
} 
function GeraToken($dadosenvio,$dadoslogin)
{		

									
					
     $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "utf-8",
          CURLOPT_MAXREDIRS => 1000,
          CURLOPT_TIMEOUT => 300,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
									   <soapenv:Header/>
									   <soapenv:Body>
										  <fid:Geratoken>
											 <tipoGeracao>'.$dadosenvio[tipoGeracao].'</tipoGeracao>
											 <nome>'.$dadosenvio[nome].'</nome>
											 <cpf>'.$dadosenvio[cpf].'</cpf>
											 <celular>'.$dadosenvio[celular].'</celular>
											 <email>'.$dadosenvio[email].'</email>
											  <dadoslogin>
												<login>'.$dadoslogin['0'].'</login>
												<senha>'.$dadoslogin['1'].'</senha>
												<idloja>'.$dadoslogin['2'].'</idloja>
												<idmaquina>'.$dadoslogin['3'].'</idmaquina>
												<idcliente>'.$dadoslogin['4'].'</idcliente>
											  </dadoslogin>
										  </fid:Geratoken>
									   </soapenv:Body>
									</soapenv:Envelope>
		 ',
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml; charset=utf-8",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
          ),
        ));
                                                     
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
			$msg= "cURL Error #:" . $err;
			$arraycpf= array('msg' => $msg);
                            
        } else {
			$doc = new DOMDocument();
			libxml_use_internal_errors(true);
			$doc->loadHTML($response);
			libxml_clear_errors();
			$xml = $doc->saveXML($doc->documentElement);
			$xml = simplexml_load_string($xml);             
			$array = json_decode(json_encode($xml), TRUE);

			return $array;
        }
} 
function ValidaToken($dadosenvio,$dadoslogin)
{		
     $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade">
								   <soapenv:Header/>
								   <soapenv:Body>
									  <fid:validaToken>
										 <tipoGeracao>'.$dadosenvio['tipoGeracao'].'</tipoGeracao>
										 <token>'.$dadosenvio['token'].'</token>
										 <celular>'.$dadosenvio['celular'].'</celular>
										 <cpf>'.$dadosenvio['cpf'].'</cpf>
											  <dadoslogin>
												<login>'.$dadoslogin['0'].'</login>
												<senha>'.$dadoslogin['1'].'</senha>
												<idloja>'.$dadoslogin['2'].'</idloja>
												<idmaquina>'.$dadoslogin['3'].'</idmaquina>
												<idcliente>'.$dadoslogin['4'].'</idcliente>
											  </dadoslogin>
									  </fid:validaToken>
								   </soapenv:Body>
								</soapenv:Envelope>',
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml; charset=utf-8",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
          ),
        ));
                                                     
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          $msg= "cURL Error #:" . $err;
          $arraycpf= array('msg' => $msg);
                            
        } else {
			 $doc = new DOMDocument();
			  libxml_use_internal_errors(true);
			  $doc->loadHTML($response);
			  libxml_clear_errors();
			  $xml = $doc->saveXML($doc->documentElement);
			  $xml = simplexml_load_string($xml);             
 			  $array = json_decode(json_encode($xml), TRUE);
              return $array;
        }
} 

?>