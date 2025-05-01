<?php 

	include '_system/_functionsMain.php';

	unset($_SESSION['AUTH_DINAMIZE']);
	unset($_SESSION['COD_LISTA']);

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT TIP_RETORNO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
	$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
	
	if($tip_retorno == 1){
		$casasDec = 0;
	}else{
		$casasDec = 2;
	}

	$ARRAY_UNIDADE1=array(
				   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
				   'cod_empresa'=>$cod_empresa,
				   'conntadm'=>$connAdm->connAdm(),
				   'IN'=>'N',
				   'nomecampo'=>'',
				   'conntemp'=>'',
				   'SQLIN'=> ""   
				   );
	$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

	$sql = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";

	$qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	$des_campanha = preg_replace('/\s+/', '_', fnAcentos($qrCamp['DES_CAMPANHA']));

	$des_campanha = str_replace('/', '.', $des_campanha);

	$connTemp = connTemp($cod_empresa, "");

	mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
	mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
	mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");

	$sql = "SELECT CP.DES_CAMPANHA, 
						   CP.DAT_INI, 
						   CP.HOR_INI,
						   CP.COD_EXT_CAMPANHA, 
						   TE.COD_EXT_TEMPLATE,
						   TE.DES_ASSUNTO,
						   TE.DES_REMET,
						   TE.END_REMET,
						   TE.EMAIL_RESPOSTA,
						   TE.DES_ASSUNTO,
						   MDE.DES_TEMPLATE AS HTML 
					FROM CAMPANHA CP
					INNER JOIN EMAIL_CONTROLE_AUX ECA ON ECA.COD_CAMPANHA = CP.COD_CAMPANHA
					INNER JOIN TEMPLATE_EMAIL TE ON TE.COD_TEMPLATE = ECA.COD_TEMPLATE
					INNER JOIN MODELO_EMAIL MDE ON MDE.COD_TEMPLATE = TE.COD_TEMPLATE
					WHERE CP.COD_EMPRESA = $cod_empresa
					AND CP.COD_CAMPANHA = $cod_campanha";

	// fnEscreve($sql);

	$qrMsg = mysqli_fetch_assoc(mysqli_query($connTemp,$sql));

	mysqli_close($connTemp);

	// fnEscreve($qrMsg['HTML']);

	$tagsPersonaliza=procpalavrasV2($qrMsg['DES_ASSUNTO'].$qrMsg['HTML'],$connAdm->connAdm(),$cod_empresa);

	// echo'<pre>';
	// print_r($tagsPersonaliza);
	// echo'</pre>';

	// fnEscreve('chegou 1');

	// include './_system/ibope/BuscarCampanha.php';
	// include './_system/ibope/FnIbotpe.php';
	// include '_system/ftpIbope.php';

	$nomeRel = $cod_empresa.'_'.date("YmdHis")."_".$des_campanha."_CONTROLE.csv";
	$arquivo = '_system/func_dinamiza/lista_envio/'.$nomeRel;
	$caminhoRelat = '_system/func_dinamiza/lista_envio/';

	$tagsPersonaliza = '{{cmp1}},{{cmp2}},'.$tagsPersonaliza.",{{cmp3}}";

	$tagsPersonaliza = str_replace(',,', ',', $tagsPersonaliza);

	$tags = explode(',',$tagsPersonaliza);

	$selectCliente = "";
	$tagsDinamize = "";		

	for ($i=0; $i < count($tags) ; $i++) {
		// fnEscreve($tags[$i]);

		$sqlExt = "SELECT VD.COD_EXTERNO, VR.KEY_BANCOVAR FROM VARIAVEIS_DINAMIZE VD 
				   INNER JOIN VARIAVEIS VR ON VR.COD_BANCOVAR = VD.COD_BANCOVAR
				   WHERE VD.COD_EMPRESA = $cod_empresa AND VD.DES_EXTERNO = '$tags[$i]'";

		$qrExterno = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlExt));

		$tagsDinamize .= '{"Position":"'.$i.'", "Field":"'.$qrExterno[COD_EXTERNO].'", "Rule":"3"},';

		switch($qrExterno['KEY_BANCOVAR']){

			case '<#NOME>';
				$selectCliente .= "SUBSTRING_INDEX(SUBSTRING_INDEX(concat(Upper(SUBSTR(C.NOM_CLIENTE, 1,1)), lower(SUBSTR(C.NOM_CLIENTE, 2,LENGTH(C.NOM_CLIENTE)))), ' ', 1), ' ', -1) AS NOM_CLIENTE, ";
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
													GROUP BY cred.cod_cliente),0),$casasDec),$casasDec,'pt_BR') AS CREDITO_DISPONIVEL, ";
			break;
			case '<#SALDOEXPIRA>';

				$selectCliente .= "(SELECT  
									FORMAT(TRUNCATE(IFNULL(SUM(B.VAL_SALDO),0),2),2,'pt_BR') VAL_SALDO
									FROM CREDITOSDEBITOS B
									WHERE 
									B.COD_EMPRESA=$cod_empresa AND
									DATE(B.DAT_EXPIRA) between NOW() AND DATE(DATE_ADD(NOW(),INTERVAL 30 DAY)) AND
										B.TIP_CREDITO='C' AND
									B.COD_STATUSCRED='1' 
									AND COD_CLIENTE=C.cod_CLIENTE) AS VAL_SALDOEXPIRA, ";

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
				$selectCliente .= "C.COD_CLIENTE, ";
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
				$selectCliente .= "C.COD_UNIVEND,";
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
				// $selectCliente .= "C.DES_EMAILUS,";
			break;
			
		}
		
	}

	$selectCliente .= "C.DES_EMAILUS,";

	$tagsDinamize = rtrim(trim($tagsDinamize),',');

	$selectCliente = rtrim(trim($selectCliente),',');

	// echo'<pre>';
	// print_r($tags);
	// echo'</pre>';
                       
	$sql = "SELECT $selectCliente
			FROM clientes C 
			INNER JOIN EMAIL_CONTROLE EC ON EC.COD_CLIENTE = C.COD_CLIENTE
			WHERE EC.COD_EMPRESA = $cod_empresa 
			AND EC.COD_CAMPANHA = $cod_campanha";
			// AND EC.COD_CLIENTE IN($cod_clientes)";
			
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	// fnEscreve($sql);
	// exit();

	$array = array();
	$linhas = 0;
	$email = '{"email": "diogo_tank@hotmail.com"},';
         
	while($row = mysqli_fetch_assoc($arrayQuery)){

		$newRow[] = $row;
		$email .= '{"email": "'.$row['DES_EMAILUS'].'"},';
		$linhas++;
	}

	$emailsTeste = rtrim($email, ',');

	while($headers=mysqli_fetch_field($arrayQuery))
	{
	    $headers1[campos][]=$headers->name; 
	}

	// fnescreve($caminhoRelat);
	// fnescreve($nomeRel);

	$arraydebitos=array('quantidadeEmailenvio'=>$linhas,
                        'COD_EMPRESA'=>$cod_empresa,
                        'PERMITENEGATIVO'=>'N',
                        'COD_CANALCOM'=>'1',
                        'CONFIRMACAO'=>'S',
                        'COD_CAMPANHA'=>$cod_campanha,    
                        'LOG_TESTE'=> 'S',
                        'DAT_CADASTR'=> date('Y-m-d H:i:s'),
                        'CONNADM'=>$connAdm->connAdm()
                        ); 

    // $retornoDeb=FnDebitos($arraydebitos);

	// fnEscreve("chegou 2");

	$retornoDeb['cod_msg'] = 1;

    if($retornoDeb['cod_msg'] == 1){

    	include "_system/func_dinamiza/Function_dinamiza.php";
		include "autenticaDinamize.php";
		// retorna $_SESSION[COD_LISTA] E $_SESSION[AUTH_DINAMIZE]

		// fnEscreve("chegou 3");

		gerandorcvs($caminhoRelat, $nomeRel, ';', $newRow, $headers1);

		$retornoContatos = contatos_dinamiza ("$_SESSION[AUTH_DINAMIZE]",
					                        "$arquivo",
											"$tagsDinamize",
											$_SESSION['COD_LISTA']);

		// fnEscreve($tagsDinamize);

		$cod_mailing_ext = $retornoContatos[body][code];

		// echo'<pre>';
		// fnEscreve($_SESSION['AUTH_DINAMIZE']);
		// fnEscreve($arquivo);
		// fnEscreve($tagsDinamize);
		// fnEscreve($_SESSION['COD_LISTA']);
		// print_r($retornoContatos);
		// echo'</pre>';

		// fnEscreve($cod_mailing_ext);

		if($cod_mailing_ext != ""){

			$sqlSeg = "SELECT COD_EXT_SEGMENTO FROM EMAIL_LOTE 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CAMPANHA = $cod_campanha
						AND LOG_TESTE = 'S'
						AND COD_EXT_SEGMENTO IS NOT NULL 
						LIMIT 1";

			// fnEscreve($sqlSeg);

			// fnEscreve("chegou 4");

			$arraySeg = mysqli_query(connTemp($cod_empresa,''),$sqlSeg);

			$qrSeg = mysqli_fetch_assoc($arraySeg);

			if(mysqli_num_rows($arraySeg) == 1 && $qrSeg['COD_EXT_SEGMENTO'] != ""){

				// fnEscreve('if');

				$retornoListaSeg = ListaSegmento("$_SESSION[AUTH_DINAMIZE]", $qrSeg['COD_EXT_SEGMENTO'], $_SESSION[COD_LISTA]);

				// echo'<pre>';
				// fnEscreve($retornoListaSeg[code_detail]);
				// print_r($retornoListaSeg);
				// echo'</pre>';
				// exit();

				if($retornoListaSeg['code_detail'] == 'Sucesso'){

					$retornoSegmento = UpdateSegmento("$_SESSION[AUTH_DINAMIZE]", $cod_mailing_ext, $cod_empresa."_".$cod_campanha."_ENVIO_TESTE" , $qrSeg['COD_EXT_SEGMENTO'], $_SESSION[COD_LISTA]);
					$cod_ext_segmento = $qrSeg['COD_EXT_SEGMENTO'];

				}else{

					$retornoSegmento = FiltroSegmentos("$_SESSION[AUTH_DINAMIZE]", $cod_empresa."_".$cod_campanha."_ENVIO_TESTE" , $cod_mailing_ext, $_SESSION[COD_LISTA]);
					$cod_ext_segmento = $retornoSegmento['body']['code'];

				}


			}else{

				$retornoLista = ListaSegmento2("$_SESSION[AUTH_DINAMIZE]",$cod_empresa."_".$cod_campanha."_".$des_campanha,$_SESSION[COD_LISTA]);

				$cod_ext_segmento = $retornoLista['body']['items'][0]['code'];

				// echo'<pre>';
				// print_r($retornoLista);
				// echo'</pre>';

				if($cod_ext_segmento != ""){

					// fnEscreve('updateSegInAdd');

					$retornoSegmento = UpdateSegmento("$_SESSION[AUTH_DINAMIZE]", $cod_mailing_ext, $cod_empresa."_".$cod_campanha."_".$des_campanha , $cod_ext_segmento, $_SESSION[COD_LISTA]);

				}else{

					$retornoSegmento = FiltroSegmentos("$_SESSION[AUTH_DINAMIZE]", $cod_empresa."_".$cod_campanha."_".$des_campanha , $cod_mailing_ext, $_SESSION[COD_LISTA]);

					$cod_ext_segmento = $retornoSegmento['body']['code'];

				}

			}

			// echo'<pre>';
			// print_r($retornoSegmento);
			// echo'</pre>';
			// fnEscreve($cod_ext_segmento);
			// exit();

			// fnEscreve("chegou 5");



			if($cod_ext_segmento != ""){

				sleep(5);

				$dat_envio = date("Y-m-d H:i:s");

				$retornoEnvio = addenvioTESTE(
						$_SESSION['AUTH_DINAMIZE'],
						$des_campanha,
		                $_SESSION['COD_LISTA'],
						addslashes($qrMsg['DES_ASSUNTO']),
						addslashes($qrMsg['DES_REMET']),
						$qrMsg['END_REMET'],
						$qrMsg['EMAIL_RESPOSTA'],
						$qrMsg['COD_EXT_CAMPANHA'],
						$cod_ext_segmento,
						$qrMsg['COD_EXT_TEMPLATE'],
						2
				);

				$cod_disparo_ext = $retornoEnvio['body']['code'];

				// fnEscreve(remove_emoji(addslashes($qrMsg['DES_ASSUNTO'])));

				$retornoTeste = Inicioteste($_SESSION['AUTH_DINAMIZE'], $emailsTeste, $cod_disparo_ext);

				// echo'<pre>';
				// print_r($des_campanha);
				// print_r($_SESSION[COD_LISTA]);
				// print_r(addslashes($qrMsg['DES_ASSUNTO']));
				// echo'</pre>';

				// echo'<pre>';
				// fnEscreve('retorno addenvioTESTE');
				// print_r($retornoEnvio);
				// fnEscreve('retorno Inicioteste');
				// print_r($retornoTeste);
				// echo'</pre>';

				// exit(); 


				if($cod_disparo_ext != ""){
					$log_envio = 'S';
				}else{
					$log_envio = 'N';
					$cod_disparo_ext = 0;
				}

				$sqlControle = "INSERT INTO EMAIL_LOTE(
												COD_CAMPANHA,
												COD_EMPRESA,
												COD_LOTE,
												QTD_LISTA,
												COD_EXT_SEGMENTO,
												COD_MAILING_EXT,
												COD_DISPARO_EXT,
												COD_EXT_TEMPLATE,
												NOM_ARQUIVO,
												DES_PATHARQ,
												LOG_TESTE,
												LOG_ENVIO,
												DAT_AGENDAMENTO,
												COD_USUCADA
											) VALUES(
												$cod_campanha,
												$cod_empresa,
												0,
												'$linhas',
												'$cod_ext_segmento',
												'$cod_mailing_ext',
												'$cod_disparo_ext',
												'$qrMsg[COD_EXT_TEMPLATE]',
												'$nomeRel',
												'$arquivo',
												'S',
												'$log_envio',
												'$dat_envio',
												$cod_usucada
											)";

				mysqli_query(connTemp($cod_empresa,''),$sqlControle);

				if($log_envio == 'S'){

					$sqlUpdt = "UPDATE EMAIL_CONTROLE SET DAT_ENVIO = NOW()
			 					WHERE COD_EMPRESA = $cod_empresa 
			 					AND COD_CAMPANHA = $cod_campanha";

			 		mysqli_query(connTemp($cod_empresa,''),$sqlUpdt);

					echo fnDataFull($dat_envio); 

				}else{

					echo "Erro de envio. ($retornoEnvio[code_detail]) Por favor, contate o suporte.";

				}


			}

		}

    }else if($retornoDeb['cod_msg'] == 5){
		echo 'Sem saldo';
	}else if($retornoDeb['cod_msg'] == 2){
		echo 'Não foram gerados débitos';
	}

?>