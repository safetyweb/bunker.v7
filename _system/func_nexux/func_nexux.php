<?php

function EnvioSms($Usuario,$senha,$campanha,$cod_campanha,$centro_custo_interno,$json)
{
   /*$teste='{"usuario":"'.$Usuario.'",
							 "senha":"'.$senha.'",
							 "campanha":"'.$campanha.'",
							 "id_campanha":"'.$cod_campanha.'",
							 "centro_custo_interno":"'.$centro_custo_interno.'",
							 "tipo_envio":"short",
							 "mensagens": '.$json.'}';
	return 	 $teste;
	*/
	$curl = curl_init();

	curl_setopt_array($curl, array(
	 CURLOPT_URL => "https://sms.solucoesdigitais.cc/integracao/v2/envio_lote",				 	
	/*	 CURLOPT_URL => "https://sms.solucoesdigitais.cc/integracao/v2/envio_transacional_lote",*/
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 600,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>'{"usuario":"'.$Usuario.'",
							 "senha":"'.$senha.'",
							 "campanha":"'.$campanha.'",
							 "id_campanha":"'.$cod_campanha.'",
							 "centro_custo_interno":"'.$centro_custo_interno.'",
							 "tipo_envio":"short",
							 "mensagens": '.$json.'}',
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8"
	  ),
	));
			$response = curl_exec($curl);
			$err = curl_error($curl);
	       // $teste=curl_getinfo($curl, CURLINFO_HTTP_CODE);		
			curl_close($curl);
			if ($err) {
				$connect= "cURL Error #:" . $err;
			} else {
			  $connect=json_decode ($response,true); 
			}
			  return   $connect;
}
/*
echo'<pre>';
print_r(EnvioSms('markateste',
                 'marka2020',
				 'diogo_teste',
				 '13,',
				 '00',
				 '[{"numero": "5548996243831",
					"mensagem": "teste envio sms 20:02.",
					"serial": "001",
					"data_agendamento": "2020-10-19 20:02:00"
					},
					{
					"numero": "5515988034772",
					"mensagem": "Teste envio sms 20:02",
					"serial": "001",
					"data_agendamento": "2020-10-19 20:02:00"
					}]
                '));
echo'</pre>';
*/
