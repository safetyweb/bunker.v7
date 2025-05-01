<?php 

	include '_system/_functionsMain.php';

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$TEXTOENVIO = $_POST['DES_TEMPLATE'];
	$cod_template = fnLimpaCampoZero($_POST['COD_TEMPLATE']);
	$num_celular = fnLimpaCampo($_POST['NUM_CELULAR']);
	$mensagensContatos = "";

	// fnEscreve($TEXTOENVIO);
	// fnEscreve($cod_template);
	// fnEscreve($num_celular);

	include "autenticaNexux.php";
	// retorna: $usuario, $senha, $cliente_externo e $parc_cadastrado(0/1)

	if($parc_cadastrado == 0){
		fnEscreve("Parceiro não cadastrado na empresa");
	}

	$dat_envio = date("Y-m-d H:i:s");

	$celulares = explode(';', $num_celular);

	if(count($celulares) > 0){


		for ($i=0; $i < count($celulares) ; $i++) {
                              
			$TEXTOENVIO=str_replace('<#NOME>', "QUICKTEST", $TEXTOENVIO);
			$TEXTOENVIO=str_replace('<#SALDO>', "9,99", $TEXTOENVIO);
			$TEXTOENVIO=str_replace('<#NOMELOJA>',  "Loja Teste", $TEXTOENVIO);
			$TEXTOENVIO=str_replace('<#ANIVERSARIO>', fnDataShort($dat_envio), $TEXTOENVIO); 
			$TEXTOENVIO=str_replace('<#DATAEXPIRA>', fnDataShort($dat_envio), $TEXTOENVIO); 
			$TEXTOENVIO=str_replace('<#EMAIL>', "sms@quicktest.com", $TEXTOENVIO); 
			$msgsbtr=nl2br($TEXTOENVIO,true);  
			fnEscreve($msgsbtr);                              
			$msgsbtr= str_replace('<br />',' \n ', $msgsbtr);

			// $mensagensContatos .= '{"numero": "55'.fnLimpaDoc($celulares[$i]).'",
			// 						"mensagem": "'.$msgsbtr.'",
			// 						"serial": "0",
			// 						"data_agendamento": "'.$dat_envio.'"
			// 						},';

			// $CLIE_SMS_L[]=array("numero"=> fnLimpaDoc($celulares[$i]),
            //                 "mensagem"=>$msgsbtr,                   
            //                 "DataAgendamento"=> "$dat_envio",
            //                 "Codigo_cliente"=>"qck"
            //                  );

			if($cod_parcomu_auth == 17){

				$CLIE_SMS_L[]=array("numero"=> fnLimpaDoc($celulares[$i]),
		                            "mensagem"=>$msgsbtr,                   
		                            "DataAgendamento"=> "$dat_envio",
		                            "Codigo_cliente"=>"quicktest"
		                             );

			}else{

				$CLIE_SMS_L[]=array('Body'=>$msgsbtr,
                                    'From'=>$cliente_externo,
                                    'To'=>'+55'.fnLimpaDoc($celulares[$i]),
                                    'Codigointerno'=> 0,
                                    'COD_CLIENTE'=> 0
		                             );

			}
			// else{

			// 	$CLIE_SMS_L[]=array("from"=>$cliente_externo,
			//                         "to" =>'+55'.fnLimpaDoc($celulares[$i]), 
			//                         "mensagem"=>$msgsbtr,                   
			//                         "DataAgendamento"=> "$dat_envio",
			//                         "Codigointerno"=> "quicktest",
			//                         "codCliente"=>"quicktest",
			//                         "numCelular"=>fnLimpaDoc($celulares[$i])
			//                        );  

			// }

		}

		// $mensagensContatos = rtrim($mensagensContatos,',');
		// fnEscreve($mensagensContatos);

		// include "_system/func_nexux/func_nexux.php";

		// $retornoEnvio = EnvioSms($usuario,
		// 		                 $senha,
		// 						 "QUICKTEST_$cod_empresa",
		// 						 $cod_empresa.',0,'.$cod_template,
		// 						 $cliente_externo,
		// 						 "[".$mensagensContatos."]");

		// fnEscreve($usuario);
		// fnEscreve($cod_empresa);
		// fnEscreve($cod_template);
		// fnEscreve($cliente_externo);
		// fnEscreve($mensagensContatos);


		include './_system/func_nexux/func_transacional.php';
		// ENVIO -------------------------------------------------------------------------------------------------------------------------
        // $=EnvioSms_fast($senha,"qck",json_encode($CLIE_SMS_L),'short');

        if($cod_parcomu_auth == 17){
        	fnEscreve("nexux");

	        $testefast=EnvioSms_fast($senha,$des_campanha,json_encode($CLIE_SMS_L),'short');

	        $cod_erro_nexux=$testefast[Resultado][CodigoResultado];

	        $msgenvio=$testefast[Resultado][Mensagem];
	        $jsonputo=json_encode($testefast);

	    }else{
        	fnEscreve("wavy");

	    	$arrEnvio=array('PROVEDOR'=> $cod_parcomu_auth,
                    'URL'=> $url_api,
                    'METHOD'=>'POST',
                    'Authorization'=> $senha,
                    'Usuario'=> $usuario,
                    'COD_EMPRESA'=> $cod_empresa,    
                    'SEND'=> $CLIE_SMS_L
            );
            $testefast = fnenviosms($arrEnvio);
            $cod_erro_nexux='0';

	    }
	    // else{
	    // 	fnEscreve("twilio");

	    // 	$base64= base64_encode($usuario.':'.$senha);
		//     $testefast=sms_twilo($base64,$CLIE_SMS_L,$usuario,$senha);
		//     $cod_erro_nexux='0';

	    // }

		echo("<pre>");
		fnEscreve($cod_parcomu_auth);
		print_r($arrEnvio);
		print_r($testefast);
		echo("</pre>");

	}


?>