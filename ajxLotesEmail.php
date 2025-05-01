<?php

include '_system/_functionsMain.php';

$opcao = fnLimpaCampo($_GET['opcao']);
$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
$cod_campanha = fnLimpaCampo($_REQUEST['COD_CAMPANHA']);

switch ($opcao) {
	case 'exc':

		$cod_geracao = fnLimpaCampoZero(fnDecode($_GET['idg']));

		$sqlExc .= "DELETE FROM EMAIL_LOTE 
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_CAMPANHA = $cod_campanha 
					AND COD_GERACAO = $cod_geracao";

		mysqli_query(connTemp($cod_empresa,''),$sqlExc);

	break;
	
	default:

		$sql = "UPDATE EMAIL_LISTA SET COD_LOTE = NULL
				   WHERE COD_EMPRESA = $cod_empresa 
				   AND COD_CAMPANHA = $cod_campanha; ";

		$sql .= "UPDATE EMAIL_LOTE SET LOG_ENVIO = 'C' 
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_CAMPANHA = $cod_campanha 
				AND COD_MAILING_EXT IS NULL; ";

		mysqli_multi_query(connTemp($cod_empresa,''),$sql);

		$qtd_lote = fnLimpaCampo($_REQUEST['QTD_LOTE']);
		$des_interval = fnLimpaCampoZero($_REQUEST['DES_INTERVAL']);
		$dat_iniagendamento = fnDataSql($_REQUEST['DAT_INIAGENDAMENTO']);

		// fnEscreve($qtd_lote);

		$horas = explode(" ",$_REQUEST['DAT_INIAGENDAMENTO']);

		$dat_iniagendamento = $dat_iniagendamento." ".$horas[1];

		if (isset($_POST['COD_PERSONA'])){
			$Arr_COD_PERSONAS = $_POST['COD_PERSONA'];			 
			 
			   for ($i=0;$i<count($Arr_COD_PERSONAS);$i++) 
			   { 
				$cod_personas = $cod_personas.$Arr_COD_PERSONAS[$i].",";
			   } 
			   
			   $cod_personas = rtrim($cod_personas,",");
			   $cod_personas = ltrim($cod_personas,",");
				
		}else{$cod_personas = "0";}

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
					
		$periodo_hrs = 0;
		$cod_loteref = "";
		$lista = "";

		$sql = "CALL SP_RELAT_EMAIL_LOTE($cod_empresa, $cod_campanha, $qtd_lote)";
		// fnEscreve($sql);
		mysqli_query(connTemp($cod_empresa,''),$sql);

		$sqlLote = "SELECT CP.DES_CAMPANHA, MAX(EL.COD_LOTE) AS NRO_LOTES 
				FROM EMAIL_LISTA EL
				LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = EL.COD_CAMPANHA
				WHERE EL.COD_EMPRESA = $cod_empresa 
				AND EL.COD_CAMPANHA = $cod_campanha";

		$qrLote = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlLote));
		$nro_lotes = $qrLote['NRO_LOTES'];
		$des_campanha = preg_replace('/\s+/', '_', fnAcentos($qrLote['DES_CAMPANHA']));

		$des_campanha = str_replace('/', '.', $des_campanha);

		// fnEscreve($nro_lotes);
		// fnEscreve($qrLote['DES_CAMPANHA']);

		$sql = "SELECT  TE.DES_ASSUNTO,
					MDE.DES_TEMPLATE AS HTML 
					FROM CAMPANHA CP
					INNER JOIN MENSAGEM_EMAIL ME ON ME.COD_CAMPANHA = CP.COD_CAMPANHA
					INNER JOIN TEMPLATE_EMAIL TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_EMAIL
					INNER JOIN MODELO_EMAIL MDE ON MDE.COD_TEMPLATE = TE.COD_TEMPLATE
					WHERE CP.COD_EMPRESA = $cod_empresa
					AND CP.COD_CAMPANHA = $cod_campanha
					AND ME.LOG_PRINCIPAL = 'S'";
		// fnEscreve($sql);
		$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

		// fnEscreve($qrMsg['HTML']);

		$tagsPersonaliza=procpalavras($qrMsg['DES_ASSUNTO'].$qrMsg['HTML'],$connAdm->connAdm());

		$tagsPersonaliza = '<#EMAIL>,'.$tagsPersonaliza;

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

		include './_system/ibope/BuscarCampanha.php';
		include '_system/ftpIbope.php';

		$sql = "SELECT COD_PERSONAS, COD_LISTA
			FROM EMAIL_PARAMETROS
			WHERE COD_LISTA = (
							 	SELECT MAX(COD_LISTA) FROM EMAIL_PARAMETROS
							 	WHERE COD_EMPRESA = $cod_empresa 
							 	AND COD_CAMPANHA = $cod_campanha
							  )";

		$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

		$lista = $qrMsg['COD_LISTA'];
		$personas = $qrMsg['COD_PERSONAS'];

		for ($i=1; $i <= $nro_lotes ; $i++) {

		unset($newRow);

		$nomeRel = $cod_empresa.'_'.date("YmdHis")."_".$des_campanha."_".$i.'.csv';
		$arquivo = '_system/ibope/listas_envio/'.$nomeRel;
		$caminhoRelat = './_system/ibope/listas_envio/';
		                   
		$sql = "SELECT $selectCliente
				FROM clientes C 
				INNER JOIN EMAIL_LISTA EC ON EC.COD_CLIENTE = C.COD_CLIENTE
				WHERE EC.COD_EMPRESA = $cod_empresa 
				AND EC.COD_CAMPANHA = $cod_campanha
				AND EC.COD_LOTE = $i";
				
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

		$array = array();
		$linhas = 0;
		     
		while($row = mysqli_fetch_assoc($arrayQuery)){

			$linha = "";

			for ($j=0; $j < count($tags) ; $j++) {
				// fnEscreve($tags[$i]);
				switch($tags[$j]){

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
						$itemLinha = 'R$'.fnValor($row['CREDITO_DISPONIVEL'],2);
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

		$dadosarquivo = array(
				'arqlocal'=> $arquivo,
		        'nomearq'=> $nomeRel
			);

		$retorno = ibopeftp($dadosarquivo);

		// fnescreve('data inicio: '.$dat_iniagendamento);
		$dat_agendamento = date("Y-m-d H:i:s",strtotime($dat_iniagendamento." + ".$periodo_hrs." hours"));
		// fnescreve('data agendamen.: '.$dat_agendamento);

		$sqlControle = "INSERT INTO EMAIL_LOTE(
											COD_CAMPANHA,
											COD_EMPRESA,
											COD_LOTE,
											QTD_LISTA,
											COD_LISTA,
											COD_PERSONAS,
											COD_STATUSUP,
											DAT_AGENDAMENTO,
											NOM_ARQUIVO,
											DES_PATHARQ,
											COD_USUCADA
										) VALUES(
											$cod_campanha,
											$cod_empresa,
											$i,
											$linhas,
											'$lista',
											'$personas',
											'$retorno[uploadcod]',
											'$dat_agendamento',
											'$nomeRel',
											'$arquivo',
											$cod_usucada
										)";
		// fnEscreve($sqlControle);

		mysqli_query(connTemp($cod_empresa,''),$sqlControle);

		// exit();

		$sqlCod = "SELECT MAX(COD_CONTROLE) AS COD_LOTEREF FROM EMAIL_LOTE
				   WHERE COD_EMPRESA = $cod_empresa 
				   AND COD_CAMPANHA = $cod_campanha";

		$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCod));

		$cod_loteref = $cod_loteref.$qrCod['COD_LOTEREF'].',';

		$periodo_hrs += $des_interval;

		// fnescreve('intervalo: '.$des_interval);
		// fnescreve('periodo acumulado'.$periodo_hrs);

		}

		$cod_loteref = ltrim(rtrim($cod_loteref,','),',');

		$sqlGeracao = "INSERT INTO CONTROLE_LOTE(
										COD_CAMPANHA,
										COD_EMPRESA,
										COD_LOTEREF,
										COD_LISTAREF,
										COD_PERSONAS,
										COD_USUCADA
									) VALUES(
										$cod_campanha,
										$cod_empresa,
										'$cod_loteref',
										'$lista',
										'$personas',
										$cod_usucada
									)";
		// fnEscreve($sqlGeracao);

		mysqli_query(connTemp($cod_empresa,''),$sqlGeracao);

		$sqlUpdate = "UPDATE EMAIL_LOTE SET COD_GERACAO = ( SELECT MAX(COD_GERACAO) 
														FROM CONTROLE_LOTE 
														WHERE COD_EMPRESA = $cod_empresa 
				   										AND COD_CAMPANHA = $cod_campanha )
				  WHERE COD_EMPRESA = $cod_empresa 
				  AND COD_CAMPANHA = $cod_campanha
				  AND COD_CONTROLE IN($cod_loteref)";

		// fnEscreve($sqlUpdate);

		mysqli_query(connTemp($cod_empresa,''),$sqlUpdate);

	break;
}

?>