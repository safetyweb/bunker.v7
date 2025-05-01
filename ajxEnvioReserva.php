<?php 

	include '_system/_functionsMain.php'; 

	$num_celular = fnLimpaDoc($_POST['NUM_CELULAR']);
	$des_mensagem = $_POST['ARR_MENSAGEM'];
	$cod_canal = $_POST['CANAL'];
	$formato = '.jpg';
	$imgAsSticker = '"imageAsSticker": true,';
	$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];

	// echo "<pre>";
	// print_r($des_mensagem);
	// echo "</pre>";

	if($nom_usuario == ""){
		$nom_usuario == "BUNKER";
	}

	$sql = "SELECT KEY_CANAL FROM CANAL_ADORAI WHERE COD_EMPRESA = 274 AND cod_canal = $cod_canal";

	$arrCanal = mysqli_query(conntemp(274,""), $sql);

	$qrCanal = mysqli_fetch_assoc($arrCanal);

	$chave = $qrCanal['KEY_CANAL'];
	// canal oficial api wacloud
	if($chave == "64c10c20fa1acc9fa2c1846c"){

		if(!is_array($des_mensagem)){

			$des_msg = $des_mensagem;
			$des_mensagem = array("0"=>$des_msg);

		}

		foreach ($des_mensagem as $mensagem) {

			$qrQuarto = json_decode($mensagem,true);

			if($qrQuarto['video'] != ""){

				$media = $qrQuarto['video'];
				$template = "64f8b42476615ff9e0278c5d";
				$tipo = "video";

			}else if($qrQuarto['imagem'] != ""){

				$media = $qrQuarto['imagem'];
				$template = "64f8b42476615ff9e0278c62";
				$tipo = "image";

			}

			$arrEnvio = array(
				"tipo" => $tipo,
				"media" => $media,
				"var1" => "*Período:* ".fnDataShort($qrQuarto['dataMin'])." ".$qrQuarto['semanaIni']." a ".fnDataShort($qrQuarto['dataMax'])." ".$qrQuarto['semanaFim'],
				"var2" => "*Local:* ".$qrQuarto['local'],
				"var3" => "*Diárias:* ".$qrQuarto['nroDiarias']." *Pessoas:* ".$qrQuarto['nroPessoas'],
				"var4" => "*Acomodação:* ".$qrQuarto['chale'],
				"var5" => "*Total:* R$".fnValor($qrQuarto['total'],2),
				"var6" => "2x de R$".fnValor(($qrQuarto['total']/2),2),
				"var7" => "*R$".fnValor(($qrQuarto['total']/6),2)."*",
				"var8" => $qrQuarto['descricao'],
				"var9" => "detalhes.php?datI=".fnDataShort($qrQuarto['dataMin'])."&datF=".fnDataShort($qrQuarto['dataMax'])."&numC=".$num_celular."&idh=".$qrQuarto['idHotel']."&idc=".$qrQuarto['idQuarto']."&iv=".base64_encode($qrQuarto[total])."&cv=".$qrQuarto['codVendedor']
			);

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-template',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS =>'{
			  "forceSend": "true",
			  "templateId": "'.$template.'",
			  "verifyContact": "false",
			  "number": "55'.$num_celular.'",
			  "templateComponents": [
			    {
			      "type": "HEADER",
			      "parameters": [
			        {
			          "type": "'.$arrEnvio[tipo].'",
			          "'.$arrEnvio[tipo].'": {
			              "link" : "'.$arrEnvio[media].'"
			           }
			        }
			      ]
			    },
			    {
			      "type": "BODY",
			      "parameters": [
			        {
			          "type": "text",
			          "text": "'.$arrEnvio[var1].'"
			        },
			        {
			          "type": "text",
			          "text": "'.$arrEnvio[var2].'"
			        },
			        {
			          "type": "text",
			          "text": "'.$arrEnvio[var3].'"
			        },
			        {
			          "type": "text",
			          "text": "'.$arrEnvio[var4].'"
			        },
			        {
			          "type": "text",
			          "text": "'.$arrEnvio[var5].'"
			        },
			        {
			          "type": "text",
			          "text": "'.$arrEnvio[var6].'"
			        },
			        {
			          "type": "text",
			          "text": "'.$arrEnvio[var7].'"
			        },
			        {
			          "type": "text",
			          "text": "'.$arrEnvio[var8].'"
			        },
			      ]
			    },
			    {
			      "type": "button",
			      "sub_type": "url",
			      "index": 0,
			      "parameters": [
			            {
			                "type": "text",
			                "text": "'.$arrEnvio[var9].'"
			            }
			        ]
			    },
			  ]
			}',
			  CURLOPT_HTTPHEADER => array(
			    'access-token: 64c10c20fa1acc9fa2c1846c',
			    'Content-Type: application/json',
			    'Accept: application/json'
			  ),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			echo $response;

			$sqlDesc = "INSERT INTO ACESSOS_ADORAI(
		                                COD_EMPRESA,
		                                DES_ORIGEM,
		                                NUM_CELULAR,
		                                DAT_INI,
		                                DAT_FIM,
		                                COD_HOTEL,
		                                COD_CHALE
		                            ) VALUES(
		                                274,
		                                '$nom_usuario',
		                                '$num_celular',
		                                '".$qrQuarto[dataMin]."',
		                                '".$qrQuarto[dataMax]."',
		                                '".$qrQuarto[idHotel]."',
		                                '".$qrQuarto[idQuarto]."'
		                            )";

		    

		    mysqli_query(connTemp(274,''), $sqlDesc);

			sleep(1);

		}

		
		
	}else{

		$num_celular = '55'.$num_celular;

		include "_system/whatsapp/wstAdorai.php";

		$sql = "SELECT *
                from SENHAS_WHATSAPP
                WHERE COD_EMPRESA = 274
                AND COD_UNIVEND = $cod_canal
                LIMIT 1";

        // fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        $count = 0;
        $qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

		// novo envio 
		if(!is_array($des_mensagem)){

			$des_msg = $des_mensagem;
			$des_mensagem = array("0"=>$des_msg);

		}

		foreach ($des_mensagem as $mensagem) {

			$qrQuarto = json_decode($mensagem,true);

			if($qrQuarto['idHotel'] == ""){
				fnEscreve("sem id - aqui mesmo");
				$qrQuarto = json_decode(json_encode($mensagem,true),true);
			}else{
				fnEscreve("com id");
			}

			echo "<pre>";
			print_r($qrQuarto);
			echo "</pre>";

			if($qrQuarto['imagem'] != ""){

				$media = $qrQuarto['imagem'];
				$extFoto = explode(".", $qrQuarto['imagem']);
				$nom_arquivo = explode("/", $qrQuarto['video']);

				$ext = ".".end($extFoto);
				$nom_arq = end($nom_arquivo);

				// fnEscreve($qrQuarto['imagem']);
				// fnEscreve($qrQuarto['chale']);
				// fnEscreve($ext);

				// $resultcreate=sendMedia($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, 3, 'image', $qrQuarto['chale'], $qrQuarto['chale'], $qrQuarto['imagem']);
				$resultcreate=sendMedia($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, 3, 'image', $nom_arq, ' ', $qrQuarto['imagem']);
				// echo "<pre>";
				// fnEscreve($nom_arq);
				// print_r($resultcreate);
				// echo "</pre>";

			}

			if($qrQuarto['video'] != ""){

				$media = $qrQuarto['video'];
				$extVideo = explode(".", $qrQuarto['video']);
				$nom_arquivo = explode("/", $qrQuarto['video']);

				$ext = ".".end($extVideo);
				$nom_arq = end($nom_arquivo);
				// $ext = ".mp4";

				// $resultcreate=sendMedia($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, 3, 'video', $qrQuarto['chale'], $qrQuarto['chale'], $qrQuarto['video']);
				$resultcreate=sendMedia($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, 3, 'video', $nom_arq, ' ', $qrQuarto['video']);
				// echo "<pre>";
				// fnEscreve($nom_arq);
				// print_r($resultcreate);
				// echo "</pre>";

			}

			// $linkEnvio = "https://motor.roteirosadorai.com.br/search/".$qrQuarto['dataMin']."/".$qrQuarto['dataMax']."/1/".$qrQuarto['idHotel']."/".$qrQuarto['idQuarto']."?canal_id=".$qrQuarto['codVendedor'];
			$linkEnvio = "https://roteirosadorai.com.br/detalhes.php?datI=".fnDataShort($qrQuarto['dataMin'])."&datF=".fnDataShort($qrQuarto['dataMax'])."&idh=".$qrQuarto['idHotel']."&idc=".$qrQuarto['idQuarto']."&infQ=".base64_encode(json_encode($qrQuarto))."&iv=".base64_encode($qrQuarto[total])."&cv=".$qrQuarto['codVendedor'];

			$linkEnvio = file_get_contents("http://tinyurl.com/api-create.php?url=".$linkEnvio);

			$msgEnvio = "*Período:* ".fnDataShort($qrQuarto['dataMin'])." ".$qrQuarto['semanaIni']." a ".fnDataShort($qrQuarto['dataMax'])." ".$qrQuarto['semanaFim']."<br />*Local:*".$qrQuarto['local']."<br />*Diárias:* ".$qrQuarto['nroDiarias']." *Pessoas:* ".$qrQuarto['nroPessoas']."<br /><br />*Acomodação:* ".$qrQuarto['chale']."<br />".$qrQuarto['descricao']."<br /><br />*Check in a partir das 16h e check out até as 12h*<br /><br />*Valores e opções de pgto:*<br />*Total:* R$".fnValor($qrQuarto['total'],2)."<br />*1) PIX* 50% na reserva e 50% até 72hrs antes do check-in, 2x de R$".fnValor(($qrQuarto['total']/2),2)."<br />*2) Cartão* sem juros até *10x* de *R$".fnValor(($qrQuarto['total']/10),2)."*<br /><br /><br />*Ver detalhes e reservar:* Clique no link...<br />$linkEnvio";

			$msgsbtr=nl2br($msgEnvio,true);                                
			$msgsbtr = str_replace('<br />',"\n", $msgsbtr);
			// $msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);

			$retorno = FnsendText($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, $msgsbtr, 3);

			echo "<pre>";
			print_r($retorno);
			echo "</pre>";

			// $sqlDesc = "INSERT INTO ACESSOS_ADORAI(
		    //                             COD_EMPRESA,
		    //                             DES_ORIGEM,
		    //                             NUM_CELULAR,
		    //                             DAT_INI,
		    //                             DAT_FIM,
		    //                             COD_HOTEL,
		    //                             COD_CHALE
		    //                         ) VALUES(
		    //                             274,
		    //                             '$nom_usuario',
		    //                             '$num_celular',
		    //                             '".$qrQuarto[dataMin]."',
		    //                             '".$qrQuarto[dataMax]."',
		    //                             '".$qrQuarto[idHotel]."',
		    //                             '".$qrQuarto[idQuarto]."'
		    //                         )";

		    

		    // mysqli_query(connTemp(274,''), $sqlDesc);

		}


	}

	// else{
	// 	// envio padrão easychat
	// 	if(!is_array($des_mensagem)){

	// 		$des_msg = $des_mensagem;
	// 		$des_mensagem = array("0"=>$des_msg);

	// 	}

	// 	foreach ($des_mensagem as $mensagem) {

	// 		$qrQuarto = json_decode($mensagem,true);

	// 		if($qrQuarto['imagem'] != ""){

	// 			$media = $qrQuarto['imagem'];
	// 			$extFoto = explode(".", $qrQuarto['imagem']);

	// 			$ext = ".".end($extFoto);

	// 			// fnEscreve($qrQuarto['imagem']);
	// 			// fnEscreve($qrQuarto['chale']);
	// 			// fnEscreve($ext);

	// 			$curl = curl_init();

	// 			curl_setopt_array($curl, array(
	// 			  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-media',
	// 			  CURLOPT_RETURNTRANSFER => true,
	// 			  CURLOPT_SSL_VERIFYPEER=> false,
	// 			  CURLOPT_ENCODING => '',
	// 			  CURLOPT_MAXREDIRS => 10,
	// 			  CURLOPT_TIMEOUT => 0,
	// 			  CURLOPT_FOLLOWLOCATION => true,
	// 			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 			  CURLOPT_CUSTOMREQUEST => 'POST',
	// 			  CURLOPT_POSTFIELDS =>'{
	// 			  "extension": "'.$ext.'",
	// 			  "forceSend": true,
	// 			  "number": "55'.$num_celular.'",
	// 			  "verifyContact": true,
	// 			  "linkUrl": "'.$qrQuarto['imagem'].'",
	// 			  "fileName": "'.$qrQuarto['chale'].'",
	// 			  "caption": ""
	// 			}',
	// 			  CURLOPT_HTTPHEADER => array(
	// 			    'access-token: '.$chave.'',
	// 			    'Content-Type: application/json',
	// 			    'Accept: application/json'
	// 			  ),
	// 			));

	// 			$response = curl_exec($curl);

	// 			curl_close($curl);

	// 			sleep(3);

	// 		}

	// 		if($qrQuarto['video'] != ""){

	// 			$media = $qrQuarto['video'];
	// 			$extVideo = explode(".", $qrQuarto['video']);

	// 			$ext = ".".end($extVideo);
	// 			// $ext = ".mp4";

	// 			$curl = curl_init();

	// 			curl_setopt_array($curl, array(
	// 			  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-media',
	// 			  CURLOPT_RETURNTRANSFER => true,
	// 			  CURLOPT_SSL_VERIFYPEER=> false,
	// 			  CURLOPT_ENCODING => '',
	// 			  CURLOPT_MAXREDIRS => 10,
	// 			  CURLOPT_TIMEOUT => 0,
	// 			  CURLOPT_FOLLOWLOCATION => true,
	// 			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 			  CURLOPT_CUSTOMREQUEST => 'POST',
	// 			  CURLOPT_POSTFIELDS =>'{
	// 			  "extension": "'.$ext.'",
	// 			  "forceSend": true,
	// 			  "number": "55'.$num_celular.'",
	// 			  "verifyContact": true,
	// 			  "linkUrl": "'.$qrQuarto['video'].'",
	// 			  "fileName": "'.$qrQuarto['chale'].'",
	// 			  "caption": ""
	// 			}',
	// 			  CURLOPT_HTTPHEADER => array(
	// 			    'access-token: '.$chave.'',
	// 			    'Content-Type: application/json',
	// 			    'Accept: application/json'
	// 			  ),
	// 			));

	// 			$response = curl_exec($curl);

	// 			curl_close($curl);

	// 			sleep(3);

	// 		}

	// 		// $linkEnvio = "https://motor.roteirosadorai.com.br/search/".$qrQuarto['dataMin']."/".$qrQuarto['dataMax']."/1/".$qrQuarto['idHotel']."/".$qrQuarto['idQuarto']."?canal_id=".$qrQuarto['codVendedor'];
	// 		$linkEnvio = "https://roteirosadorai.com.br/detalhes.php?datI=".fnDataShort($qrQuarto['dataMin'])."&datF=".fnDataShort($qrQuarto['dataMax'])."&numC=".$num_celular."&idh=".$qrQuarto['idHotel']."&idc=".$qrQuarto['idQuarto']."&iv=".base64_encode($qrQuarto[total])."&cv=".$qrQuarto['codVendedor'];

	// 		$linkEnvio = file_get_contents("http://tinyurl.com/api-create.php?url=".$linkEnvio);

	// 		$msgEnvio = "*Período:* ".fnDataShort($qrQuarto['dataMin'])." ".$qrQuarto['semanaIni']." a ".fnDataShort($qrQuarto['dataMax'])." ".$qrQuarto['semanaFim']."\n*Local:*".$qrQuarto['local']."\n*Diárias:* ".$qrQuarto['nroDiarias']." *Pessoas:* ".$qrQuarto['nroPessoas']."\n\n*Acomodação:* ".$qrQuarto['chale']."\n".$qrQuarto['descricao']."\n\n*Check in a partir das 16h e check out até as 12h*\n\n*Valores e opções de pgto:*\n*Total:* R$".fnValor($qrQuarto['total'],2)."\n*1) PIX* 50% na reserva e 50% até 72hrs antes do check-in, 2x de R$".fnValor(($qrQuarto['total']/2),2)."\n*2) Cartão* sem juros até *10x* de *R$".fnValor(($qrQuarto['total']/10),2)."*\n\n\n*Ver detalhes e reservar:* Clique no link...\n$linkEnvio";

	// 		$curl = curl_init();

	// 		curl_setopt_array($curl, array(
	// 		  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-text',
	// 		  CURLOPT_RETURNTRANSFER => true,
	// 		  CURLOPT_SSL_VERIFYPEER=> false,
	// 		  CURLOPT_ENCODING => '',
	// 		  CURLOPT_MAXREDIRS => 10,
	// 		  CURLOPT_TIMEOUT => 0,
	// 		  CURLOPT_FOLLOWLOCATION => true,
	// 		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 		  CURLOPT_CUSTOMREQUEST => 'POST',
	// 		  CURLOPT_POSTFIELDS =>'{
	// 		  "forceSend": true,
	// 		  "message": "'.$msgEnvio.'",
	// 		  "number": "55'.$num_celular.'",
	// 		  "verifyContact": true
	// 		}',
	// 		  CURLOPT_HTTPHEADER => array(
	// 		    'access-token: '.$chave.'',
	// 		    'Content-Type: application/json',
	// 		    'Accept: application/json'
	// 		  ),
	// 		));

	// 		$response = curl_exec($curl);

	// 		curl_close($curl);

	// 		$sqlDesc = "INSERT INTO ACESSOS_ADORAI(
	// 	                                COD_EMPRESA,
	// 	                                DES_ORIGEM,
	// 	                                NUM_CELULAR,
	// 	                                DAT_INI,
	// 	                                DAT_FIM,
	// 	                                COD_HOTEL,
	// 	                                COD_CHALE
	// 	                            ) VALUES(
	// 	                                274,
	// 	                                '$nom_usuario',
	// 	                                '$num_celular',
	// 	                                '".$qrQuarto[dataMin]."',
	// 	                                '".$qrQuarto[dataMax]."',
	// 	                                '".$qrQuarto[idHotel]."',
	// 	                                '".$qrQuarto[idQuarto]."'
	// 	                            )";

		    

	// 	    mysqli_query(connTemp(274,''), $sqlDesc);

	// 		sleep(1);

	// 	}


	// }


?>