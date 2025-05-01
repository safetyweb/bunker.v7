<?php 

	include '_system/_functionsMain.php';

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	// $qtd_clientes = fnLimpaCampoZero($_REQUEST['QTD_CLIENTES']);
	// $cod_clientes = "";

	// for ($i=1; $i <= $qtd_clientes; $i++) { 
	// 	if($_REQUEST["check_$i"] != ""){
	// 		$cod_clientes = $cod_clientes.$_REQUEST["check_$i"].',';
	// 	}
	// }

	// $cod_clientes = ltrim(rtrim($cod_clientes,','),',');

	// if($cod_clientes == ""){
	// 	$cod_clientes = 0;
	// }

	// fnEscreve($cod_clientes);

	// fnMostraForm();

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

	$sql = "SELECT CP.DES_CAMPANHA, 
						   CP.DAT_INI, 
						   CP.HOR_INI,
						   CP.COD_EXT_CAMPANHA, 
						   TE.COD_EXT_TEMPLATE,
						   TE.DES_ASSUNTO,
						   MDE.DES_TEMPLATE AS HTML 
					FROM CAMPANHA CP
					INNER JOIN EMAIL_CONTROLE_AUX ECA ON ECA.COD_CAMPANHA = CP.COD_CAMPANHA
					INNER JOIN TEMPLATE_EMAIL TE ON TE.COD_TEMPLATE = ECA.COD_TEMPLATE
					INNER JOIN MODELO_EMAIL MDE ON MDE.COD_TEMPLATE = TE.COD_TEMPLATE
					WHERE CP.COD_EMPRESA = $cod_empresa
					AND CP.COD_CAMPANHA = $cod_campanha
					-- AND ME.LOG_PRINCIPAL = 'S'";
	// fnEscreve($sql);
	$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	// fnEscreve($qrMsg['HTML']);

	$tagsPersonaliza=procpalavras($qrMsg['DES_ASSUNTO'].$qrMsg['HTML'],$connAdm->connAdm());

	// echo'<pre>';
	// print_r($tagsPersonaliza);
	// echo'</pre>';

	// fnEscreve('chegou 1');

	include './_system/ibope/BuscarCampanha.php';
	include './_system/ibope/FnIbotpe.php';
	include '_system/ftpIbope.php';

	$nomeRel = $cod_empresa.'_'.date("YmdHis")."_".$des_campanha."_CONTROLE.csv";
	$arquivo = '_system/ibope/listas_envio/'.$nomeRel;
	$caminhoRelat = '_system/ibope/listas_envio/';

	$tagsPersonaliza = '<#EMAIL>,'.strtoupper($tagsPersonaliza);

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
				$selectCliente .= "(SELECT 
									IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos f 
									WHERE f.cod_cliente = cred.cod_cliente AND 
											f.tip_credito = 'C' AND 
											f.cod_statuscred = 1 AND 
											f.tip_campanha = cred.tip_campanha AND 
											(( f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR ( f.log_expira = 'N' ) )),0)+
									IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos_bkp g
									WHERE g.cod_cliente = cred.cod_cliente AND 
											g.tip_credito = 'C' AND 
											g.cod_statuscred = 1 AND 
											g.tip_campanha = cred.tip_campanha AND 
											((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR (g.log_expira = 'N' ) )),0) AS CREDITO_DISPONIVEL
											FROM creditosdebitos cred 
											WHERE cred.cod_cliente=C.cod_CLIENTE
											GROUP BY cred.cod_cliente ) AS CREDITO_DISPONIVEL,";
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
				$selectCliente .= "";
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
				$selectCliente .= "C.DES_EMAILUS,";
			break;
			
		}
		
	}

	$selectCliente = rtrim($selectCliente,',');

	// echo'<pre>';
	// print_r($tags);
	// echo'</pre>';
                       
	$sql = "SELECT $selectCliente
			FROM clientes C 
			INNER JOIN EMAIL_CONTROLE EC ON EC.COD_CLIENTE = C.COD_CLIENTE
			WHERE EC.COD_EMPRESA = $cod_empresa 
			AND EC.COD_CAMPANHA = $cod_campanha
			-- AND EC.COD_CLIENTE IN($cod_clientes)";
			
	// fnEscreve($sql);
	// fnEscreve($arquivo);
			
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

	// fnEscreve('chegou 2');

	$array = array();
	$linhas = 0;
         
	while($row = mysqli_fetch_assoc($arrayQuery)){

		$linha = "";

		for ($i=0; $i < count($tags) ; $i++) {
			// fnEscreve($tags[$i]);
			switch($tags[$i]){

				case '<#NOME>';
					$nome = explode(' ', $row['NOM_CLIENTE']);
					$itemLinha = fnAcentos(ucfirst(strtolower($nome[0])));
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
					$itemLinha = fnValor($row['CREDITO_DISPONIVEL'],2);
					// fnEscreve(fnValor($row['CREDITO_DISPONIVEL'],2));
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
					$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
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
					$NOM_ARRAY_UNIDADE=(array_search($row['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
					$itemLinha = fnAcentos($ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
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

		// fnEscreve($linha);

		$newRow[] = rtrim($linha,';');
		$linhas++;
	}

	// fnescreve($caminhoRelat);
	// fnescreve($nomeRel);

	fngravacvs($newRow,$caminhoRelat,$nomeRel);

	// echo '<pre>';
	// print_r($newRow);
	// echo '</pre>';

	$dadosarquivo = array(
							'arqlocal'=> $arquivo,
	                        'nomearq'=> $nomeRel
	                	); 

	$retorno = ibopeftp($dadosarquivo);

	// fnEscreve('chegou 3');

	// print_r($retorno);

	$sqlControle = "INSERT INTO EMAIL_LOTE(
										COD_CAMPANHA,
										COD_EMPRESA,
										COD_LOTE,
										QTD_LISTA,
										COD_STATUSUP,
										NOM_ARQUIVO,
										DES_PATHARQ,
										LOG_TESTE,
										COD_USUCADA
									) VALUES(
										$cod_campanha,
										$cod_empresa,
										0,
										'$linhas',
										'$retorno[uploadcod]',
										'$nomeRel',
										'$arquivo',
										'S',
										$cod_usucada
									)";
	// fnEscreve($sqlControle);

	mysqli_query(connTemp($cod_empresa,''),$sqlControle);

	// fnEscreve('chegou 4');

	// $linhas = 30000;

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

    $retornoDeb=FnDebitos($arraydebitos);

 //    echo '<pre>';
	// print_r($retornoDeb);
	// echo '</pre>';

	// $tagsPersonaliza = str_replace(',', ';', $tagsPersonaliza);
   
	if($qrMsg['COD_EXT_CAMPANHA'] && $qrMsg['COD_EXT_TEMPLATE'] && $retorno['uploadcod'] == 3 && $retornoDeb['cod_msg'] == 1){

		$dat_ini = date("d/m/Y H:i",strtotime("+5 minutes"));

		$dadosProcessaMailing=array(
                            'txtNome'=>$des_campanha,
                            'flgUploadArquivo'=> 1,
                            'txtSeparadorDadoArquivo'=>';',
                            'flgAtivo'=>1,      
                            'txtPersonalizacao'=> $tagsPersonaliza,    
                            'txtNomeArquivo'=>$nomeRel,
                            'idConfig'=>$qrMsg['COD_EXT_TEMPLATE'],
                            'idCampanha'=>$qrMsg['COD_EXT_CAMPANHA'],   
                            'datAgendamento'=>$dat_ini
                            );

		// echo '<pre>';
		// print_r($dadosProcessaMailing);
		// echo '</pre>';

		$retornoCad = cadastraEProcessaMailingV2 ($Userv2,$dadosProcessaMailing);

		// fnEscreve('chegou 5');


		// echo '<pre>';
		// print_r($retornoCad);
		// echo '</pre>';

		$status = $retornoCad['body']['envelope']['body']['cadastraeprocessamailingv2response']['cadastraeprocessamailingv2result']['status'];
		$cod_mailing = $retornoCad['body']['envelope']['body']['cadastraeprocessamailingv2response']['cadastraeprocessamailingv2result']['mailingid'];

		// $cod_disparo_ext = BuscariDdisparo ($Userv2,$cod_mailing);

		if($status == 'true'){

			$sqlUpdt = "UPDATE EMAIL_LOTE SET 
						LOG_ENVIO = 'S',
						COD_MAILING_EXT = $cod_mailing
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CAMPANHA = $cod_campanha 
						AND NOM_ARQUIVO = '$nomeRel';

						UPDATE EMAIL_CONTROLE SET DAT_ENVIO = NOW()
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CAMPANHA = $cod_campanha;";

			// fnEscreve($sqlUpdt);

			mysqli_multi_query(connTemp($cod_empresa,''),$sqlUpdt);

			echo date("d/m/Y H:i:s");

		}else{
			echo 'erro';
		}

	}else if($retornoDeb['cod_msg'] == 5){
		echo 'Sem saldo';
	}else if($retornoDeb['cod_msg'] == 2){
		echo 'Não foram gerados débitos';
	}else{
		if($retorno['uploadcod'] != 3){
			echo 'Erro no upload da lista';
		}else{
			echo 'erro ao enviar';
		}
	}

?>