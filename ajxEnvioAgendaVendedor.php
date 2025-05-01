<?php 

	include '_system/_functionsMain.php';

	$opcao = fnLimpaCampo($_GET['opcao']);
	$tipo = fnLimpaCampo($_GET['tipo']);

	$sugestoes = array_filter(explode("||", trim(rtrim(base64_decode($_GET['SUGESTOES']), "||"))));
	$sugestoes = array_map(function($val) {
	    return explode('=>', $val);
	}, $sugestoes);

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$des_template = $_REQUEST['DES_TEMPLATE'];
	$cod_univendURL = fnLimpaCampoZero(fnDecode($_REQUEST['idUv']));
	$cod_desafio = fnLimpaCampoZero(fnDecode($_GET['idD']));
	$cod_responsavel = fnLimpaCampoZero(fnDecode($_GET['idR']));
	$nro_template = fnLimpaCampoZero(fnDecode($_GET['nroT']));
	$cod_clientes = explode(',', $_GET['idC']);
	$cod_cliente_envio = "";
	$clientes_enviados = "";

	// print_r($_REQUEST);
	// fnEscreve($_GET['nroT']);
	// fnEscreve($des_template);
	// exit();

	$connTemp = connTemp($cod_empresa,'');

	mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
    mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
    mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");  

	switch ($nro_template) {
		case 1:
			$nroTemplate = "TS.DES_TEMPLATE2";
		break;

		case 2:
			$nroTemplate = "TS.DES_TEMPLATE3";
		break;

		case 3:
			$nroTemplate = "TS.DES_TEMPLATE4";
		break;

		case 4:
			$nroTemplate = "TS.DES_TEMPLATE5";
		break;
		
		default:
			$nroTemplate = "TS.DES_TEMPLATE";
		break;
	}

	// echo "<pre>";
	// print_r($sugestoes);
	// echo "</pre>";

	// exit();

	foreach ($cod_clientes as $cliente) {
		$cod_cliente_envio .= fnLimpaCampoZero(fnDecode($cliente)).",";
	}
	$cod_cliente_envio = rtrim($cod_cliente_envio,',');

	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	$log_ok = 'S';

	$sqlCamp = "SELECT COD_CAMPANHA FROM CAMPANHA 
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_DESAFIO = $cod_desafio";

	$arrCamp = mysqli_query($connTemp,$sqlCamp);
	$qrCamp = mysqli_fetch_assoc($arrCamp);
	$cod_campanha = $qrCamp[COD_CAMPANHA];

	$agendamento = "";

	$andUnivend = "";
	$nom_unidade_variavel = "";
	if($cod_univendURL != 0){
		$andUnivend = "AND COD_UNIVEND = $cod_univendURL";

		$sqlNomUnivend = "SELECT NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND = $cod_univendURL";
		$qrUniv = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlNomUnivend));
		$nom_unidade_variavel = $qrUniv['NOM_FANTASI'];

		// fnEscreve($sqlNomUnivend);
		// fnEscreve($nom_unidade_variavel);
	}

	$sql = "SELECT *
            from SENHAS_WHATSAPP
            WHERE COD_EMPRESA = $cod_empresa
            $andUnivend
            AND LOG_ATIVO = 'S'
            LIMIT 1";

if($cod_empresa == "219"){
	// fnEscreve($sql);
}

    // echo("<br/>");
    // echo($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

    $count = 0;
    $qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

    $tem_numero = mysqli_num_rows($arrayQuery);

    $port = $qrBuscaModulos[PORT_SERVICAO];


	if($tem_numero > 0){

		if($cod_campanha != ""){

			if($log_ok == 'S'){

				$sqlGat = "SELECT COD_GATILHO, TIP_GATILHO FROM GATILHO_WHATSAPP WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";
				$qrGat = mysqli_fetch_assoc(mysqli_query($connTemp,$sqlGat));

				$sql = "SELECT TE.COD_TEMPLATE
						FROM MENSAGEM_WHATSAPP ME
						INNER JOIN TEMPLATE_WHATSAPP TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_WHATSAPP
						WHERE ME.COD_EMPRESA = $cod_empresa 
						AND ME.COD_CAMPANHA = $cod_campanha";

				// fnEscreve($sql);

				$qrMsg = mysqli_fetch_assoc(mysqli_query($connTemp,$sql));

				if($qrGat['TIP_GATILHO'] == 'individualD'){

					$sqlDec = "SELECT TIP_RETORNO, NUM_DECIMAIS_B FROM empresas where COD_EMPRESA = $cod_empresa";
					//fnEscreve($sql);
					$arrayQuery = mysqli_query($connAdm->connAdm(),$sqlDec);
					$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
					
					if (isset($arrayQuery)){

						$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
						$num_decimais_b = $qrBuscaEmpresa['NUM_DECIMAIS_B'];

						if($tip_retorno == 1){
							$casasDec = 0;
						}else{
							$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
						}
					}

					$sql = "SELECT CP.DES_CAMPANHA, 
							   CP.DAT_INI, 
							   CP.HOR_INI,
							   CP.COD_CAMPANHA, 
							   CP.COD_EMPRESA, 
							   CP.COD_EXT_CAMPANHA, 
							   SPA.COD_PERSONAS,
							   TS.COD_TEMPLATE,
							   TS.DES_IMAGEM,
							   TS.LOG_INDENTIFICA,
							   TS.USUARIO_MSG,
							   TS.DES_SAUDACAO,
							   DES_TEMPLATE AS HTML,
							   DES_TEMPLATE2 AS HTML2,
							   DES_TEMPLATE3 AS HTML3, 
							   DES_TEMPLATE4 AS HTML4, 
							   DES_TEMPLATE5 AS HTML5 
							FROM CAMPANHA CP
							INNER JOIN WHATSAPP_PARAMETROS SPA ON SPA.COD_CAMPANHA = CP.COD_CAMPANHA
							INNER JOIN MENSAGEM_WHATSAPP MS ON MS.COD_CAMPANHA = SPA.COD_CAMPANHA
							INNER JOIN TEMPLATE_WHATSAPP TS ON TS.COD_TEMPLATE = MS.COD_TEMPLATE_WHATSAPP
							WHERE CP.COD_EMPRESA = $cod_empresa
							AND CP.COD_CAMPANHA = $cod_campanha
							ORDER BY 1 DESC LIMIT 1";

					// fnEscreve($sql);
					$qrMsg = mysqli_fetch_assoc(mysqli_query($connTemp,$sql));

					// COMENTADO 07/10/2024 COMO PEDIDO NO CHAMADO 6965
					// $mensagemEnvio = "Oieee, aqui é $nom_usuario! Tudo bem?\n\n";
					if($qrMsg['LOG_INDENTIFICA'] == 'S'){

						if($qrMsg['USUARIO_MSG'] != 'UNI'){

							if($qrMsg['USUARIO_MSG'] == 'USU'){
								
								$cod_responsavel = $cod_usucada;
								
							}

							$sqlNomUsu = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $cod_responsavel";
							// echo($sqlNomUsu);
							$arrUsu = mysqli_query($connAdm->connAdm(), $sqlNomUsu);
							$qrNomUsuario = mysqli_fetch_assoc($arrUsu);

							$nom_usuario = explode(" ", $qrNomUsuario['NOM_USUARIO']);
							$nom_usuario = ucfirst(strtolower($nom_usuario[0]));

						}else{

							$nom_usuario = $nom_unidade_variavel;

						}

						$mensagemEnvio = str_replace('[variavel_usuario]', $nom_usuario, $qrMsg['DES_SAUDACAO']);
						$mensagemEnvio .= "\n\n";

					}else{
						
						$mensagemEnvio = "";
						
					}

					$des_campanha = preg_replace('/\s+/', '_', fnAcentos($qrMsg['DES_CAMPANHA']));

					$des_campanha = str_replace('/', '.', $des_campanha);

					// fnEscreve($qrMsg['HTML']);

					$tagsPersonaliza=procpalavras($qrMsg['HTML'],$connAdm->connAdm());

					$tagsPersonaliza = '<#EMAIL>,<#CODIGO>,'.$tagsPersonaliza;

					$tags = explode(',',$tagsPersonaliza);

					$selectCliente = "";	

					for ($i=0; $i < count($tags) ; $i++) {
						// fnEscreve($tags[$i]);
						switch($tags[$i]){

							case '<#NOME>';
								$selectCliente .= "C.NOM_CLIENTE,";
							break;
							case '<#CARTAO>';
								$selectCliente .= "";
							break;
							case '<#ESTADOCIVIL>';
								$selectCliente .= "";
							break;
							case '<#SEXO>';
								$selectCliente .= "";
							break;
							case '<#PROFISSAO>';
								$selectCliente .= "";
							break;
							case '<#NASCIMENTO>';
								$selectCliente .= "";
							break;
							case '<#ENDERECO>';
								$selectCliente .= "";
							break;
							case '<#NUMERO>';
								$selectCliente .= "";
							break;
							case '<#BAIRRO>';
								$selectCliente .= "";
							break;
							case '<#CIDADE>';
								$selectCliente .= "";
							break;
							case '<#ESTADO>';
								$selectCliente .= "";
							break;
							case '<#CEP>';
								$selectCliente .= "";
							break;
							case '<#COMPLEMENTO>';
								$selectCliente .= "";
							break;
							case '<#TELEFONE>';
								$selectCliente .= "";
							break;
							case '<#CELULAR>';
								$selectCliente .= "";
							break;
							case '<#SALDO>';
								$selectCliente .= "FORMAT(TRUNCATE(IFNULL((
													SELECT IFNULL((
													SELECT  SUM(val_saldo)
													FROM creditosdebitos f
													WHERE f.cod_cliente = cred.cod_cliente AND 
													f.tip_credito = 'C' AND 
													f.cod_statuscred = 1 AND 
													f.tip_campanha = cred.tip_campanha AND 
													((f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (f.log_expira = 'N'))),0)+ IFNULL((
													SELECT SUM(val_saldo)
													FROM creditosdebitos_bkp g
													WHERE g.cod_cliente = cred.cod_cliente AND g.tip_credito = 'C' AND g.cod_statuscred = 1 AND g.tip_campanha = cred.tip_campanha AND ((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (g.log_expira = 'N'))),0)
													FROM creditosdebitos cred
													WHERE cred.cod_cliente=C.cod_CLIENTE
													GROUP BY cred.cod_cliente),0),$casasDec),$casasDec,'pt_BR') AS CREDITO_DISPONIVEL,";
							break;
							case '<#PRIMEIRACOMPRA>';
								$selectCliente .= "";
							break;
							case '<#ULTIMACOMPRA>';
								$selectCliente .= "";
							break;
							case '<#TOTALCOMPRAS>';
								$selectCliente .= "";
							break;
							case '<#CODIGO>';
								$selectCliente .= "C.COD_CLIENTE,";
							break;
							case '<#CUPOMSORTEIO>';
								$selectCliente .= "";
							break;
							case '<#CUPOM_INDICACAO>';
								$selectCliente .= "";
							break;
							case '<#NUMEROLOJA>';
								$selectCliente .= "";
							break;
							case '<#BAIRROLOJA>';
								$selectCliente .= "";
							break;
							case '<#NOMELOJA>';
								if($nom_unidade_variavel != ""){
									$selectCliente .= "'".$nom_unidade_variavel."' AS NOME_LOJA,";
								}else{
									$selectCliente .= "C.COD_UNIVEND AS NOME_LOJA,";
								}
							break;
							case '<#ENDERECOLOJA>';
								$selectCliente .= "";
							break;
							case '<#TELEFONELOJA>';
								$selectCliente .= "";
							break;
							case '<#ANIVERSARIO>';
								$selectCliente .= "C.DAT_NASCIME,";
							break;
							case '<#DATAEXPIRA>';
								$selectCliente .= "(SELECT 
												        MIN(DAT_EXPIRA) AS DAT_EXPIRA
											        	FROM creditosdebitos 
													    WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DAT_EXPIRA,";
							break;
							default:
								$selectCliente .= "C.NUM_CELULAR, C.COD_UNIVEND,";
							break;
							
						}
						
					}

					$selectCliente = rtrim($selectCliente,',');

					$sql = "SELECT $selectCliente
							FROM clientes C
							INNER JOIN DESAFIO_CONTROLE_V2 DC2 ON DC2.COD_CLIENTE = C.COD_CLIENTE
							WHERE C.COD_EMPRESA = $cod_empresa 
							AND C.COD_CLIENTE IN($cod_cliente_envio)
							AND DC2.LOG_CONCLUIDO = 'N'
							AND DC2.COD_DESAFIO = $cod_desafio";
							// AND EC.COD_CLIENTE IN($cod_clientes)";
					if($_GET['dev'] == 'true'){
						fnEscreve($sql);
					}
					// fnEscreve($arquivo);
							
					$arrayQuery = mysqli_query($connTemp,$sql);
					$qtd_clientes_total = mysqli_num_rows($arrayQuery);

					// fnEscreve('chegou 2');

					$array = array();
					$linhas = 0;
					$sqlInsert = "";

					$dat_envio = date("Y-m-d H:i:s", strtotime("+ 10 seconds"));
					$dat_envio = date("Y-m-d H:i:s", strtotime("- 1 hour"));
				         
					while($row = mysqli_fetch_assoc($arrayQuery)){

						$linha = "";

						for ($i=0; $i < count($tags) ; $i++) {
							// fnEscreve($tags[$i]);
							switch($tags[$i]){

								case '<#NOME>';
									$nome = explode(' ', $row['NOM_CLIENTE']);
									$itemLinha = ucfirst(strtolower($nome[0]));
								break;
								case '<#CARTAO>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#ESTADOCIVIL>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#SEXO>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#PROFISSAO>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#NASCIMENTO>';
									$itemLinha = $row['DAT_NASCIME'];
								break;
								case '<#ENDERECO>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#NUMERO>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#BAIRRO>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#CIDADE>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#ESTADO>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#CEP>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#COMPLEMENTO>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#TELEFONE>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#CELULAR>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#SALDO>';
									$itemLinha = fnValor($row['CREDITO_DISPONIVEL'],$casasDec);
								break;
								case '<#PRIMEIRACOMPRA>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#ULTIMACOMPRA>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#TOTALCOMPRAS>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#CODIGO>';
									$itemLinha = $row['COD_CLIENTE'];
								break;
								case '<#CUPOMSORTEIO>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#CUPOM_INDICACAO>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#NUMEROLOJA>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#BAIRROLOJA>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#NOMELOJA>';
									if($nom_unidade_variavel != ""){
										$itemLinha = $nom_unidade_variavel;
									}else{
										$NOM_ARRAY_UNIDADE=(array_search($row['NOME_LOJA'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
										$itemLinha = fnAcentos($ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
									}
								break;
								case '<#ENDERECOLOJA>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#TELEFONELOJA>';
									$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
								break;
								case '<#ANIVERSARIO>';
									$itemLinha = substr($row['DAT_NASCIME'], 0,-5);
								break;
								case '<#DATAEXPIRA>';
									$itemLinha = fnDataShort($row['DAT_EXPIRA']);
								break;
								default:
									$itemLinha = $row['DES_EMAILUS'];
								break;
								
							}
							$linha .= $itemLinha.";";
						}

						$newRow[] = rtrim($linha,';');
						$linhas++;

						$rand = rand(1,5);

						if($rand == 1){
							$rand = "";
						}

						switch ($rand) {
							case 1:
								$templateNro = "HTML";
							break;

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

						if($_GET['nroT'] != 99){

							$msgCli = $mensagemEnvio.$qrMsg[$templateNro];

						}else{

							$msgCli = $mensagemEnvio.$des_template;

						}

						$NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($row['NOM_CLIENTE']))));                                
						$TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $msgCli);
						$TEXTOENVIO=str_replace('<#SALDO>', $row['CREDITO_DISPONIVEL'], $TEXTOENVIO);
						if($nom_unidade_variavel != ""){
							$TEXTOENVIO=str_replace('<#NOMELOJA>', $nom_unidade_variavel, $TEXTOENVIO);
						}else{
							if($row['NOME_LOJA'] != ""){
							$NOM_ARRAY_UNIDADE=(array_search($row['NOME_LOJA'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
							$loja = fnAcentos($ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
							$TEXTOENVIO=str_replace('<#NOMELOJA>',  $loja, $TEXTOENVIO);
						}
						}
						$TEXTOENVIO=str_replace('<#ANIVERSARIO>', $row['DAT_NASCIME'], $TEXTOENVIO); 
						$TEXTOENVIO=str_replace('<#DATAEXPIRA>', fnDataShort($row['DAT_EXPIRA']), $TEXTOENVIO); 
						$TEXTOENVIO=str_replace('<#EMAIL>', $row['DES_EMAILUS'], $TEXTOENVIO); 

						if(count($sugestoes)>0){
							$TEXTOENVIO .= "\n\nConfira nossas ofertas especias para você:";
						}

						foreach ($sugestoes as $sugestao => $produto) {

							$TEXTOENVIO .= "\n\n*".trim($produto[0])."*\n".trim($produto[1]);
						}
						
						$msgsbtr=nl2br($TEXTOENVIO,true); 
						$msgsbtr = str_replace('<br />',"\n", $msgsbtr);

						$msgCli = "";                            

						$celular = $row['NUM_CELULAR'];
						// $celular = "11997269261";
						if($_GET['dev'] == 'true'){
							$celular = "15981146246";
						}
						
						$univend = fnLimpaCampoZero($row['COD_UNIVEND']);

						if($cod_univendURL != 0){
							$univend = $cod_univendURL;
						}

						$CLIE_WHATSAPP_L[]=array(
											"message"=> "$msgsbtr",               
											"imagem"=> "$qrMsg[DES_IMAGEM]",               
											"number"=> fnLimpaDoc($celular),
											"cod_cliente"=> $row['COD_CLIENTE'],
											"nom_cliente"=> $row['NOM_CLIENTE'],
											"univend"=> $univend
										);

					}

					if($qtd_clientes_total > 0){
						
						fngravacvs($newRow,$caminhoRelat,$nomeRel);

						include "_system/whatsapp/wstAdorai.php";

						foreach ($CLIE_WHATSAPP_L as $clientes) {

							$tempo_aleatorio = mt_rand(3, 20);

							if($clientes['imagem'] != ""){

								$ext = explode(".", $clientes['imagem']);
								$ext = end($ext);
								$type = "";

								if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif"){

									$type = 'image';

								}else{

									$type = 'video';

								}

								$retorno = sendMedia($qrBuscaModulos['NOM_SESSAO'],$qrBuscaModulos['DES_AUTHKEY'],'55'.$clientes['number'],$tempo_aleatorio,$type,$clientes['imagem'],$clientes['message'],"https://img.bunker.mk/media/clientes/$cod_empresa/wpp/$clientes[imagem]",$port);
								$tipoMsg = "sendMedia";
							}else{

								$retorno = FnsendText($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], "55".$clientes['number'],$clientes['message'],$tempo_aleatorio,$port);
								$tipoMsg = "sendText";
							}

							$ID_DISPARO = date('Ymd');

							if($_GET['dev'] == 'true'){
								echo "<pre>";
								fnEscreve($tipoMsg);
								print_r($retorno);
								echo "</pre>";
								exit();
							}

							// echo "<pre>";
							// print_r($retorno);
							// echo "</pre>";

							if (isset($retorno['key']['id']) && !empty($retorno['key']['id'])) {

								$CHAVE_GERAL = $retorno['key']['id'];
								$CHAVE_CLIENTE = $retorno['key']['id'];
								$des_status = 'S';
								$leitura = 1;
								$confirmacao = 1;
								$bounce = 0;

								$insertListaFollow .= "(
														$cod_empresa,
														$cod_desafio,
														$clientes[cod_cliente],
														'WhatsApp',
														'$msgsbtr',
														1,
														0,
														$cod_usucada
														),";

								$clientes_enviados .= $clientes['cod_cliente'].",";

							}else{

								$CHAVE_GERAL = "";
								$CHAVE_CLIENTE = "";
								$des_status = 'N';
								$leitura = 0;
								$confirmacao = 0;
								$bounce = 1;

							}

							$insertListaRet .= "(
								$cod_empresa,
								$cod_campanha,       
								'$clientes[nom_cliente]',       
								$clientes[univend],
								$clientes[cod_cliente],
								'$clientes[number]',
								'$clientes[message]',
								'$ID_DISPARO',
								'$CHAVE_GERAL',
								'$CHAVE_CLIENTE',
								'$qrBuscaModulos[NOM_SESSAO]',
								$leitura,
								$confirmacao,
								$bounce,
								'$des_status',
								NOW(),
								'N'  
							),";

						}

						$clientes_enviados = rtrim($clientes_enviados,',');
						$insertListaRet = rtrim($insertListaRet,',');
						$insertListaFollow = rtrim($insertListaFollow,',');

						if($cod_empresa == "219"){
							// exit();
						}

						if($clientes_enviados != ""){

							$sqlUpdt = "UPDATE DESAFIO_CONTROLE_V2 
												SET LOG_CONCLUIDO = 'S',
													COD_USUCADA = $cod_usucada
										WHERE COD_EMPRESA = $cod_empresa 
										AND COD_CLIENTE IN($clientes_enviados)
										AND COD_DESAFIO = $cod_desafio";

							// fnEscreve($sqlUpdt);

							mysqli_query($connTemp,$sqlUpdt);

						}

						if($insertListaRet != ""){
							
							$sqlInsertRel= "INSERT INTO WHATSAPP_LISTA_RET(
														COD_EMPRESA,
														COD_CAMPANHA,                                                                               
														NOM_CLIENTE,
														COD_UNIVEND,
														COD_CLIENTE,
														NUM_CELULAR,
														DES_MSG_ENVIADA,
														ID_DISPARO,
														CHAVE_GERAL,
														CHAVE_CLIENTE,
														ID_EXT_CELULAR,
														COD_CCONFIRMACAO,
														COD_LEITURA,
														BOUNCE,
														DES_STATUS,
														DAT_CADASTR,
														LOG_TESTE
														) VALUES $insertListaRet";
							// fnEscreve($sqlInsertRel);
							mysqli_query($connTemp, $sqlInsertRel);

						}
							
						if($insertListaFollow != ""){

							$sqlInsertFollow = "INSERT INTO FOLLOW_CLIENTE(
																COD_EMPRESA,
																COD_DESAFIO,
																COD_CLIENTE,
																NOM_FOLLOW,
																DES_COMENT,
																TIP_FOLLOW,
																COD_SAC,
																COD_USUCADA
																) VALUES $insertListaFollow";

							mysqli_query($connTemp, $sqlInsertFollow);

						}

						$arrCliEnvio = explode(',', $clientes_enviados);

						echo count($arrCliEnvio);

					}else{
						echo 0;
					}

				}

			}else{

				echo "Necessária aprovação para o envio da lista";
				
			}

		}else{

			echo "A campanha não foi gerada/configurada.";

		}

	}else{

		echo "Não há número conectado para esta unidade.";

	}

?> 