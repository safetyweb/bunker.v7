<?php 

	include '_system/_functionsMain.php';

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_desafio = fnLimpaCampoZero(fnDecode($_GET['idD']));
	$TEXTOENVIO = $_POST['DES_TEMPLATE'];
	$cod_template = fnLimpaCampoZero($_POST['COD_TEMPLATE']);
	$cod_parceiro = fnLimpaCampoZero($_POST['COD_PARCEIRO']);
	$num_celular = fnLimpaCampo($_POST['NUM_CELULAR']);
	$mensagensContatos = "";
	$cod_univend = 0;

	$connTemp = connTemp($cod_empresa,'');

	mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
    mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
    mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");

    if($cod_desafio != 0){
    	$sqlUnivend = "SELECT COD_UNIVEND FROM DESAFIO_V2 WHERE COD_EMPRESA = $cod_empresa AND COD_DESAFIO = $cod_desafio";
    	$qrUnivend = mysqli_fetch_assoc(mysqli_query($connTemp,$sqlUnivend));
    	$cod_univends = $qrUnivend['COD_UNIVEND'];

    	//fnEscreve($sqlUnivend);
    	$arrUnivend = explode(",", $cod_univends);
    	$k = array_rand($arrUnivend);
    	$cod_univend = fnLimpaCampoZero($arrUnivend[$k]);

    }

	$sql = "SELECT 
			TE.COD_TEMPLATE,
			TE.DES_IMAGEM,
			TE.NOM_TEMPLATE AS TITULO_MSG,
			DES_TEMPLATE AS HTML,
			DES_TEMPLATE2 AS HTML2,
			DES_TEMPLATE3 AS HTML3, 
			DES_TEMPLATE4 AS HTML4, 
			DES_TEMPLATE5 AS HTML5 
			FROM TEMPLATE_WHATSAPP TE
			WHERE TE.COD_EMPRESA = $cod_empresa
			AND TE.COD_TEMPLATE = $cod_template";
		// fnEscreve($sql);
	$qrMsg = mysqli_fetch_assoc(mysqli_query($connTemp,$sql));
	$des_imagem = $qrMsg['DES_IMAGEM'];
	$des_titulo = $qrMsg['DES_TITULO'];

	$andParceiro = "AND LOG_QUICKTEST = 'S'";
	if($cod_parceiro != 0){
		$andParceiro = "AND COD_SENHAPARC = $cod_parceiro";
	}

	if($cod_univend != 0){
		$andUnivend = "AND COD_UNIVEND IN ($cod_univends)";
	}

	$sql = "SELECT SENHAS_WHATSAPP.*
			from SENHAS_WHATSAPP
			WHERE COD_EMPRESA = $cod_empresa
			AND LOG_ATIVO = 'S'
			$andParceiro
			$andUnivend
			LIMIT 1";

	    //fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$tem_parc = mysqli_num_rows($arrayQuery);

	if($tem_parc == 0){

		$andParceiro = "";
		if($cod_parceiro != 0){
			$andParceiro = "AND COD_SENHAPARC = $cod_parceiro";
		}

		$sql = "SELECT SENHAS_WHATSAPP.*
				from SENHAS_WHATSAPP
				WHERE COD_EMPRESA = $cod_empresa
				AND LOG_ATIVO = 'S'
				$andParceiro
				$andUnivend
				LIMIT 1";

		    //fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

	}


	$count = 0;
	$qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

	$session = $qrBuscaModulos['NOM_SESSAO'];
	$des_token = $qrBuscaModulos[DES_TOKEN];
	$des_authkey = $qrBuscaModulos[DES_AUTHKEY];
	$log_login = $qrBuscaModulos[LOG_LOGIN];
	$port = $qrBuscaModulos[PORT_SERVICAO];
	$dat_envio = date("Y-m-d H:i:s");

	$celulares = explode(';', $num_celular);

	if(count($celulares) > 0){

		for ($i=0; $i < count($celulares) ; $i++) {

			$rand = rand(1,5);

			if($rand == 1){
				$rand = "";
			}

			switch ($rand) {

				case 2:
				$templateNro = "HTML2";
				break;

				case 3:
				$templateNro = "HTML3";
				break;

				case 4:
				$templateNro = "HTML4";
				break;

				case 5:
				$templateNro = "HTML5";
				break;

				default:
				$templateNro = "HTML";
				break;
			}

			$newRow[] = rtrim($linha,';');
			$linhas++;

			if($qrMsg[$templateNro] != ""){
				$msgCli = $qrMsg[$templateNro];
			}else{
				$msgCli = $TEXTOENVIO;
			}
                              
			$TEXTOENVIO=str_replace('<#NOME>', "QUICKTEST", $msgCli);
			$TEXTOENVIO=str_replace('<#SALDO>', "9,99", $TEXTOENVIO);
			$TEXTOENVIO=str_replace('<#NOMELOJA>',  "Loja Teste", $TEXTOENVIO);
			$TEXTOENVIO=str_replace('<#ANIVERSARIO>', fnDataShort($dat_envio), $TEXTOENVIO); 
			$TEXTOENVIO=str_replace('<#DATAEXPIRA>', fnDataShort($dat_envio), $TEXTOENVIO); 
			$TEXTOENVIO=str_replace('<#EMAIL>', "sms@quicktest.com", $TEXTOENVIO); 
			$msgsbtr=nl2br($TEXTOENVIO,true);                                
			$msgsbtr = str_replace('<br />',"\n", $msgsbtr);

			
			$CLIE_WHATSAPP_L[]=array(
								 "message"=> "$msgsbtr",               
								 "number"=> fnLimpaDoc($celulares[$i])
		                        );

			 

		}

        include_once '_system/whatsapp/wstAdorai.php';

       	foreach ($CLIE_WHATSAPP_L as $key => $dadosArray) {
			$tempo_aleatorio = mt_rand(3, 20);


			if(empty($des_imagem)){
				// FNeSCREVE("IF");
				$retorno = FnsendText($session,$des_authkey,'55'.$dadosArray[number],$dadosArray[message],$tempo_aleatorio,$port);
			}else{
				$ext = explode(".", $des_imagem);
				$ext = end($ext);
				$type = "";
				if($ext == "jpg" || $ext == "jpeg" || $ext == "png"){
					$type = 'image';
				}else{
					$type = 'video';
				}
				// FNeSCREVE($type);
				// FNeSCREVE("https://img.bunker.mk/media/clientes/$cod_empresa/wpp/$des_imagem");

				$retorno=sendMedia($session,$des_authkey,'55'.$dadosArray[number],$tempo_aleatorio,$type,$des_imagem,$dadosArray[message],"https://img.bunker.mk/media/clientes/$cod_empresa/wpp/$des_imagem",$port);
				
			}

			// fnescreve($session);
			// fnescreve($des_authkey);
			// fnescreve($dadosArray[number]);
			// fnescreve($dadosArray[message]);
			// fnescreve($tempo_aleatorio);
			// fnescreve("https://img.bunker.mk/media/clientes/$cod_empresa/wpp/$des_imagem");
			echo "<pre>";
			// print_r($CLIE_WHATSAPP_L);
			// echo "Retorno";
			print_r($retorno);
			echo "</pre>";
		}

	}

?>