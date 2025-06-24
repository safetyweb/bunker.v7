<?php

if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$hashLocal = "";
$msgTipo = "";
$msgRetorno = "";
$cod_usucada = "";
$hojeFull = "";
$cod_venda = "";
$cod_orcamento = "";
$cod_cliente = "";
$num_cgcecpf = "";
$cod_univend_cli = "";
$cod_lancamen = "";
$cod_ocorren = "";
$cod_formapa = "";
$tem_prodaux = "";
$des_comenta = "";
$val_totprodu = "";
$val_resgate = "";
$val_desconto = "";
$val_gerencial = "";
$val_totvenda = "";
$val_lancamento = "";
$cod_vendapdv = "";
$cod_cupom = "";
$casasDec = "";
$addpdv = "";
$tip_contabil = "";
$log_pontuar = "";
$log_funciona = "";
$resgateCerto = "";
$hHabilitado = "";
$hashForm = "";
$pontuar = "";
$creditou = "";
$sqlBUSCA = "";
$resultuser = "";
$dadoscliente = "";
$mensagemResg = "";
$retornoValida = "";
$codResg = "";
$msgTipoResg = "";
$hoje = "";
$sql1 = "";
$contempteste = "";
$queryVenda = "";
$sqlVenda = "";
$queryVend = "";
$cadat = "";
$rowclien = "";
$rcod_vendainst = "";
$sqlItem = "";
$arrItem = "";
$countItem = "";
$sqlUpdtItem = "";
$qrItem = "";
$comentariovenda = "";
$sqlitemvenda = "";
$queryexec = "";
$val_venda = "";
$row = "";
$what = "";
$by = "";
$val_descitem = "";
$nom_prod = "";
$vendaitem = "";
$val_liqvenda = "";
$des_tokenres = "";
$des_cupom = "";
$arrayC = [];
$arrayCampos = [];
$id_vendapdv = "";
$arrayVenda = [];
$retornoVenda = "";
$msgVenda = "";
$val_credito = "";
$dat_expira = "";
$rwcredito = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$tp_campanha = "";
$prazo = "";
$rs_prazo = "";
$nom_empresa = "";
$tip_retorno = "";
$classeFormata = "";
$money = "";
$qrBuscaCliente = "";
$nom_cliente = "";
$num_cartao = "";
$qrBuscaOrcamento = "";
$qrBuscaOrcamentoAux = "";
$qrBuscaSaldoResgate = "";
$credito_disponivel = "";
$total_credito = 0;
$dias30 = "";
$dat_ini = "";
$msgRetornoOrc = "";
$msgTipoOrc = "";
$formBack = "";
$abaCli = "";
$qrListaLancamento = "";
$qrListaOcorrencia = "";
$qrListaUnive = "";
$disabled = "";
$qrBuscaPagamento = "";
$valorTotal = "";
$qrBuscaProdutos = "";
$valorTotalProd = "";

$hashLocal = mt_rand();
$msgTipo = 'alert-success';
$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$hojeFull = date("Y-m-d H:i:s");

		$cod_venda = fnLimpacampoZero(@$_REQUEST['COD_VENDA']);
		$cod_orcamento = fnLimpacampoZero(@$_REQUEST['COD_ORCAMENTO']);
		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_cliente = fnLimpacampoZero(@$_REQUEST['COD_CLIENTE']);
		$num_cgcecpf = fnLimpacampo(fnDecode(@$_REQUEST['NUM_CGCECPF']));
		$cod_univend_cli = fnLimpacampoZero(fnDecode(@$_REQUEST['COD_UNIVEND_CLI']));
		$cod_lancamen = fnLimpacampoZero(@$_REQUEST['COD_LANCAMEN']);
		$cod_ocorren = fnLimpacampoZero(@$_REQUEST['COD_OCORREN']);
		$cod_univend = fnLimpacampoZero(@$_REQUEST['COD_UNIVEND']);
		$cod_formapa = fnLimpacampo(@$_REQUEST['COD_FORMAPA']);
		$tem_prodaux = fnLimpacampoZero(@$_REQUEST['TEM_PRODAUX']);
		$des_comenta = fnLimpaCampo(@$_REQUEST['DES_COMENTA']);
		$val_totprodu = fnLimpacampo(@$_REQUEST['VAL_TOTPRODU']);
		$val_resgate = fnLimpacampo(@$_REQUEST['VAL_RESGATE']);
		$val_desconto = fnLimpacampo(@$_REQUEST['VAL_DESCONTO']);
		if ($_REQUEST['COD_LANCAMEN'] == 4) {
			$val_desconto = fnLimpacampo(@$_REQUEST['VAL_GERENCIAL']);
		}
		$val_gerencial  = fnLimpacampo(@$_REQUEST['VAL_GERENCIAL']);
		$val_totvenda = fnLimpacampo(@$_REQUEST['VAL_TOTVENDA']);
		$val_lancamento = fnLimpacampo(@$_REQUEST['VAL_LANCAMENTO']);
		$cod_vendapdv = fnLimpacampo(@$_REQUEST['COD_VENDAPDV']);
		$cod_cupom = fnLimpacampozero(@$_REQUEST['COD_CUPOM']);
		$casasDec = fnLimpacampo(@$_REQUEST['CASAS_DEC']);
		if (trim($cod_vendapdv) == "") {
			$cod_vendapdv = $cod_univend . "." . trim($hojeFull);
		} else {
			$addpdv = date("YmdHis");
			$cod_vendapdv = $cod_vendapdv . '.' . $addpdv;
		}
		$tip_contabil = fnLimpacampo(@$_REQUEST['TIP_CONTABIL']);
		$log_pontuar = fnLimpacampo(@$_REQUEST['LOG_PONTUAR']);
		$log_funciona = fnLimpacampo(@$_REQUEST['LOG_FUNCIONA']);

		//tipo de contabilizacao para resgate
		if ($tip_contabil == "RESG") {
			$resgateCerto = $val_resgate;
		} else {
			$resgateCerto = 0;
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			$pontuar = "S";
			//se empresa pontua funcionário
			if ($log_pontuar == 'S') {
				$pontuar = "S";
				$creditou = 0;
			} else {
				//se cliente é funcionario
				if ($log_funciona == 'S') {
					$pontuar = "N";
					$creditou = 4;
				} else {
					$pontuar = "S";
					$creditou = 0;
				}
			}

			//se GEF sempre pontua funcionário
			if ($cod_empresa == 119) {
				if ($log_funciona == 'S') {
					$pontuar = "S";
					$creditou = 0;
				}
			}

			$sqlBUSCA = "SELECT COD_USUARIO,
		                LOG_USUARIO,
		                DES_SENHAUS,
		                COD_UNIVEND,
		                COD_EMPRESA
		         FROM usuarios 
		         WHERE cod_empresa=$cod_empresa
		               AND COD_TPUSUARIO=10 AND 
		               COD_EXCLUSA = 0 LIMIT 1";
			$resultuser = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlBUSCA));

			include "_system/_FUNCTION_WS.php";

			$dadoscliente = array(
				'cpf' => $num_cgcecpf,
				'vl_liquido' => $val_totvenda,
				'vl_resgate' => $val_resgate,
				'login' => $resultuser['LOG_USUARIO'],
				'senha' => fnDecode($resultuser['DES_SENHAUS']),
				'idloja' => $cod_univend_cli,
				'empresa' => $cod_empresa
			);

			if ($cod_lancamen == 3 && $pontuar == 'S') {
				$mensagemResg = "";
			} else if ($val_resgate == "") {
				$mensagemResg = "";
			} else {
				$retornoValida = validaDesc($dadoscliente);
				$mensagemResg = $retornoValida['body']['envelope']['body']['validadescontosresponse']['validadescontos']['msgerro'];
				$codResg = $retornoValida['body']['envelope']['body']['validadescontosresponse']['validadescontos']['coderro'];
				$msgTipoResg = "alert-success";

				if ($codResg == 49) {
					$msgTipoResg = "alert-warning";
				}
			}

			//VENDA AVULSA                                            
			if ($cod_lancamen == 4) {

				// $hoje = fnFormatDate(date("Y-m-d"));

				// $sql1 = "CALL SP_INSERE_VENDA_AVULSA(
				// 				" . $cod_venda . ",
				// 				" . $cod_orcamento . ",
				// 				" . $cod_empresa . ",
				// 				" . $cod_cliente . ",
				// 				" . $cod_lancamen . ",
				// 				" . $cod_ocorren . ",
				// 				" . $cod_univend . ",
				// 				" . $cod_formapa . ",
				// 				'" . fnValorSQLEXtrato($val_totprodu, 2) . "',
				// 				'" . $tem_prodaux . "',
				// 				'" . fnValorSQLEXtrato($resgateCerto, 2) . "',
				// 				'" . fnValorSQLEXtrato($val_gerencial, 2) . "',
				// 				'" . fnValorSQLEXtrato($val_totvenda, 2) . "',
				// 				'" . $cod_vendapdv . "',
				// 				" . $cod_usucada . ",
				// 				'" . $tip_contabil . "',   
				// 				'0',   
				// 				'" . fnDataSql($hoje) . "',   
				// 				'1',
				// 				" . $creditou . ",
				// 				'" . $cod_cupom . "'
				// ) ";
				// // fnEscreve($sql1);
				// $contempteste = connTemp($cod_empresa, '');

				// $queryVenda = mysqli_multi_query($contempteste, $sql1);

				// //adicionado por Lucas chamado 6791, se já existir um lançamento para o cupom especifico, não deixar lançar
				// // if ($cod_cupom != '' && $cod_cupom != 0) {

				// // 	$sqlVenda = "SELECT * FROM VENDAS WHERE COD_EMPRESA = $cod_empresa AND COD_CUPOM='$cod_cupom' AND COD_UNIVEND = $cod_univend AND COD_STATUSCRED IN (1,2,3,4,5,7,8,9,10)";

				// // 	$queryVend = mysqli_query($contempteste, $sqlVenda);

				// // 	if ($queryVend->num_rows > 0) {
				// // 		$msgTipoResg = "alert-warning";
				// // 		$mensagemResg = "Já existe lançamento para esse cupom!</strong>";
				// // 	} else {
				// // 		$queryVenda = mysqli_multi_query($contempteste, $sql1);
				// // 	}
				// // } else {

				// // }


				// if ($queryVenda) {
				// 	do {
				// 		// Store first result set
				// 		if ($cadat = mysqli_store_result($contempteste)) {
				// 			while ($rowclien = mysqli_fetch_assoc($cadat)) {
				// 				$rcod_vendainst = $rowclien['v_COD_VENDA'];
				// 			}
				// 			mysqli_free_result($contempteste);
				// 		}
				// 	} while (mysqli_next_result($contempteste));
				// }

				// $sqlItem = "SELECT * FROM ITEMVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_VENDA = $rcod_vendainst";
				// $arrItem = mysqli_query(connTemp($cod_empresa, ''), $sqlItem);
				// $countItem = 0;
				// $sqlUpdtItem = "";
				// while ($qrItem = mysqli_fetch_assoc($arrItem)) {
				// 	$sqlUpdtItem .= "UPDATE ITEMVENDA SET COD_ITEMEXT = $countItem WHERE COD_EMPRESA = $cod_empresa AND COD_ITEMVEN = $qrItem['COD_ITEMVEN'];";
				// 	$countItem++;
				// }
				// if ($sqlUpdtItem != '' && $sqlUpdtItem != 0) {
				// 	mysqli_multi_query(connTemp($cod_empresa, ''), $sqlUpdtItem);
				// }

				// mysqli_close($contempteste);

				// if ($rcod_vendainst != '' && $rcod_vendainst != 0) {
				// 	$comentariovenda = "INSERT INTO venda_info (COD_VENDA,
				// 														 COD_EMPRESA,
				// 														 COD_USUCADA,
				// 														 DES_TIPO,
				// 														 DES_COMENTA) 
				// 														 VALUES 
				// 														 ($rcod_vendainst, 
				// 														  $cod_empresa, 
				// 														  $cod_usucada, 
				// 														  '1',
				// 														  '" . addslashes('Venda Avulsa!') . "');";
				// 	mysqli_query(connTemp($cod_empresa, ''), $comentariovenda);
				// }

				include 'totem/funWS/inserirvenda.php';

				$sqlitemvenda = "select B.COD_EXTERNO,
						B.DES_PRODUTO,
						ROUND(A.VAL_UNITARIO, $casasDec) as VAL_UNITARIO,
						ROUND(A.VAL_DESCONTOUN, $casasDec) as VAL_DESCONTOUN,
						ROUND(A.VAL_LIQUIDO, $casasDec) as VAL_LIQUIDO,
						A.QTD_PRODUTO,
						A.COD_VENDA
						 from AUXVENDA A
						inner join  PRODUTOCLIENTE B on 	A.COD_PRODUTO=B.COD_PRODUTO	
						where A.COD_ORCAMENTO = '$cod_orcamento' and A.COD_ORCAMENTO <> ''  order by A.COD_VENDA";

				// fnEscreve($sqlitemvenda);

				$queryexec = mysqli_query(connTemp($cod_empresa, ''), $sqlitemvenda);

				$val_venda = 0;

				while ($row = mysqli_fetch_assoc($queryexec)) {
					// matriz de entrada
					$what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç');

					// matriz de saída
					$by   = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C');
					$val_descitem = $row['VAL_UNITARIO'] - $row['VAL_DESCONTOUN'];
					// devolver a string
					$nom_prod = str_replace($what, $by, $row['DES_PRODUTO']);
					$vendaitem .= "<vendaitem>
									<id_item>" . $row['COD_VENDA'] . "</id_item>
									<produto>" . $nom_prod . "</produto>
									<codigoproduto>" . $row['COD_EXTERNO'] . "</codigoproduto>
									<quantidade>" . str_replace(".", ",", $row['QTD_PRODUTO']) . "</quantidade>
									<valorbruto>" . str_replace(".", "", fnValor($row['VAL_UNITARIO'], 2)) . "</valorbruto>
									<descontovalor>" . str_replace(".", "", fnValor($val_descitem, 2)) . "</descontovalor>
									<valorliquido>" . str_replace(".", "", fnValor($row['VAL_DESCONTOUN'], 2)) . "</valorliquido>
								</vendaitem>";

					$val_venda = $val_venda + ($row['VAL_DESCONTOUN'] * $row['QTD_PRODUTO']);
				}

				// $val_venda = fnValorSQLEXtrato($val_totvenda, 2);
				$val_liqvenda = $val_venda - $val_desconto;
				$val_resgate = fnValorSQLEXtrato($resgateCerto, 2);
				$des_tokenres = fnLimpaCampo(@$_POST['HID_TKNRESG']);
				$des_cupom = $cod_cupom;

				//pegar um usuario senha e loja para montar a string da chave;
				$sqlBUSCA = "SELECT COD_USUARIO,
							LOG_USUARIO,
							DES_SENHAUS,
							COD_UNIVEND,
							COD_EMPRESA
							FROM usuarios 
							WHERE cod_empresa=$cod_empresa
							AND COD_TPUSUARIO=10 AND 
							COD_EXCLUSA = 0 LIMIT 1";
				$resultuser = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlBUSCA));

				$arrayC = $resultuser['LOG_USUARIO'] . ';' . fnDecode($resultuser['DES_SENHAUS']) . ';' . $cod_univend . ';' . 'Venda Avulsa' . ';' . $resultuser['COD_EMPRESA'];
				$arrayCampos = explode(";", $arrayC);

				$id_vendapdv = $arrayCampos['2'] . $cod_empresa . date("dmYHis");

				$arrayVenda = array(
					'id_vendapdv' => $id_vendapdv,
					'datahora' => date("Y-m-d H:i:s"),
					'cartao' => $num_cgcecpf,
					'valortotalbruto' => str_replace(".", "", fnValor($val_venda, 2)),
					'descontototalvalor' => str_replace(".", "", $val_desconto),
					'valortotalliquido' => str_replace(".", "", fnValor($val_liqvenda, 2)),
					'valor_resgate' => str_replace(".", "", fnValor($val_resgate, 2)),
					// 'cupomfiscal'=>date("dmYHis"),
					'cupomfiscal' => "$des_cupom",
					'formapagamento' => $cod_formapa,
					'pontostotal' => 0,
					'codatendente' => $cod_usucada,
					'codvendedor' => $cod_usucada,
					'token_resgate' => ''
				);

				// echo "<pre>";
				// print_r($vendaitem);
				// echo "</pre>";
				// if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
				// 	echo $cod_formapa;
				// 	echo "<pre>";
				// 	print_r($arrayVenda);
				// 	echo "</pre>";
				// 	exit();
				// }

				$retornoVenda = inserirvenda($arrayVenda, $arrayCampos, $vendaitem);

				// echo "<pre>";
				// print_r($arrayCampos);
				// print_r($retornoVenda);
				// echo "</pre>";
				// exit();

				$msgVenda = json_decode(json_encode($retornoVenda), TRUE);

				// fnEscreve($msgVenda['0']);

				if ($msgVenda['0'] != "Processo de venda concluido!") {
					$msgTipo = 'alert-danger';
					$msgRetorno = $msgVenda['0'] . '<br> Favor entrar em contato com o <strong>suporte</strong>.';
				} else {
					$updtVenda = "UPDATE vendas SET COD_ORCAMENTO = $cod_orcamento WHERE COD_VENDAPDV = $id_vendapdv AND COD_EMPRESA = $cod_empresa";
					mysqli_query(connTemp($cod_empresa, ''), $updtVenda);
				}
			}


			//CRÉDITO AVULSO
			if ($cod_lancamen == 3 && $pontuar == 'S') {

				$val_credito = fnLimpacampo(@$_REQUEST['VAL_CREDITO']);
				$dat_expira = fnLimpacampo(@$_REQUEST['DAT_EXPIRA']);

				$sql1 = "CALL SP_CADASTRA_CREDITO(
								'" . $cod_cliente . "',
								'" . fnValorSql($val_credito) . "',
								'" . fnDataSql($dat_expira) . "',
								'" . $cod_usucada . "',   
								'Crédito Avulso',   
								'13',   
								'" . $cod_univend . "',   
								'" . $cod_empresa . "',
								'" . date('Ymis') . "',    
								'CAD'   
								) ";
				// fnEscreve($sql1);
				$rwcredito = mysqli_fetch_assoc(mysqli_query(connTemp(fnDecode(@$_GET['id']), ''), $sql1));
				//=============================================================
				if ($rwcredito['COD_CREDITO'] != '') {
					$comentariovenda = "INSERT INTO venda_info (COD_VENDA,
																		 COD_EMPRESA,
																		 COD_USUCADA,
																		 DES_TIPO,
																		 DES_COMENTA) 
																		 VALUES 
																		 (" . $rwcredito['COD_CREDITO'] . ", 
																		  $cod_empresa, 
																		  $cod_usucada, 
																		  '2',
																		  '" . addslashes($des_comenta) . "');";
					mysqli_query(connTemp($cod_empresa, ''), $comentariovenda);
				}
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					if ($mensagemResg == "") {
					}
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_cliente = fnDecode(@$_GET['idC']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, NUM_DECIMAIS_B, NUM_DECIMAIS, TIP_RETORNO, TIP_CONTABIL,TIP_CAMPANHA, LOG_PONTUAR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	$tp_campanha = $qrBuscaEmpresa['TIP_CAMPANHA'];

	//pegar o prazo dos creditos
	$prazo = "SELECT max(cr.QTD_VALIDAD) as QTD_VALIDAD 
				FROM CAMPANHARESGATE cr 
				INNER JOIN campanha c ON c.COD_CAMPANHA = cr.COD_CAMPANHA 
				WHERE cr.cod_empresa = $cod_empresa 
				AND c.LOG_REALTIME = 'S' 
				AND c.tip_campanha =  $tp_campanha
				AND c.LOG_ATIVO = 'S'  
				AND c.COD_EXCLUSA =''";

	//fnEscreve($prazo);


	/*"SELECT max(cr.QTD_VALIDAD) as QTD_VALIDAD FROM  CAMPANHARESGATE cr 
			INNER JOIN campanha c ON c.COD_CAMPANHA=cr.COD_CAMPANHA
			WHERE cr.cod_empresa=$cod_empresa AND c.LOG_REALTIME='S' AND c.LOG_ATIVO='S' AND c.tip_campanha='" . $qrBuscaEmpresa['TIP_CAMPANHA'] . "'";*/
	$rs_prazo = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $prazo));
	//fnEscreve($rs_prazo['QTD_VALIDAD']);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$tip_contabil = $qrBuscaEmpresa['TIP_CONTABIL'];
		$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
		$log_pontuar = $qrBuscaEmpresa['LOG_PONTUAR'];

		// fnEscreve($qrBuscaEmpresa['TIP_RETORNO']);

		if ($tip_retorno == 1) {
			$casasDec = 0;
			$classeFormata = "int";
		} else {
			$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
			if ($cod_empresa == 19) {
				$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS'];
			}
			$classeFormata = "money";
		}

		// fnEscreve($casasDec);

	} else {
		$casasDec = 2;
	}
} else {
	$cod_empresa = 0;
	$casasDec = 2;
}

switch ($casasDec) {

	case 0:
		$money = "int";
		break;

	case 3:
		$money = "money3";
		break;

	case 4:
		$money = "money4";
		break;

	case 5:
		$money = "money5";
		break;

	default:
		$money = "money";
		break;
}

//busca dados do cliente
$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE, LOG_FUNCIONA, COD_UNIVEND FROM CLIENTES where COD_CLIENTE = '" . $cod_cliente . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaCliente)) {

	$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
	$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
	$cod_univend_cli = $qrBuscaCliente['COD_UNIVEND'];
	$log_funciona = $qrBuscaCliente['LOG_FUNCIONA'];
} else {

	$nom_cliente = "";
	$cod_cliente = "";
	$num_cartao = "";
	$num_cgcecpf = "";
	$log_funciona = "";
}

//busca dados do orçamento
$sql = "SELECT max(COD_ORCAMENTO)+1 as COD_ORCAMENTO FROM CONTADOR WHERE COD_CONTADOR = 1 ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaOrcamento = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaOrcamento['COD_ORCAMENTO']) && $qrBuscaOrcamento['COD_ORCAMENTO'] != "") {

	$cod_orcamento = $qrBuscaOrcamento['COD_ORCAMENTO'];

	//fnEscreve($qrBuscaOrcamento['COD_ORCAMENTO']);

	//atualiza contador do orçamento
	$sql = "UPDATE CONTADOR SET COD_ORCAMENTO = '" . $cod_orcamento . "' WHERE COD_CONTADOR = 1 ";
	mysqli_query(connTemp($cod_empresa, ''), $sql);
} else {

	$cod_orcamento = 0;
}


//verifica orçamento aux
$sql = "select count(COD_ORCAMENTO) as TEM_PRODAUX from AUXVENDA WHERE COD_ORCAMENTO = '" . $cod_orcamento . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaOrcamentoAux = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaOrcamentoAux)) {
	$tem_prodaux = $qrBuscaOrcamentoAux['TEM_PRODAUX'];
}

//busca saldos de resgate
$sql = "CALL SP_CONSULTA_SALDO_CLIENTE('" . $cod_cliente . "') ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaSaldoResgate = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSaldoResgate)) {
	$credito_disponivel = $qrBuscaSaldoResgate['CREDITO_DISPONIVEL'];
	$total_credito = $qrBuscaSaldoResgate['TOTAL_CREDITO'];
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

$dias30 = fnFormatDate(date('Y-m-d', strtotime('+ ' . $rs_prazo['QTD_VALIDAD'] . ' days')));
$dat_ini = fnFormatDate(date("Y-m-d"));

if ($cod_orcamento == 0) {
	$msgRetornoOrc = "Controle de venda avulsa/crédito extra não liberado. <br>Favor entrar em contato com o suporte.";
	$msgTipoOrc = 'alert-danger';
} else {
	$msgRetornoOrc = "";
}
// fnEscreve($money);
// fnEscreve($casasDec);
// fnEscreve($tip_retorno);

//fnMostraForm();
//fnEscreve($cod_orcamento);
//fnEscreve($log_pontuar);
//fnEscreve($log_funciona);
// fnEscreve('subiu');
?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1015";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetornoOrc <> '') { ?>
					<div class="alert <?php echo $msgTipoOrc; ?> top30 bottom30" role="alert" id="msgRetornoOrc">
						<?php echo $msgRetornoOrc; ?>
					</div>
				<?php } ?>

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php if ($mensagemResg <> '') { ?>
					<div class="alert <?php echo $msgTipoResg; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $mensagemResg; ?>
					</div>
				<?php } ?>

				<?php
				//menu superior - cliente
				$abaCli = 1067;
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 14: //rede duque
						include "abasClienteDuque.php";
						break;
					case 13: //sh manager
						include "abasIntegradoraCli.php";
						break;
					case 18: //mais cash
						include "abasMaisCashCli.php";
						break;
					default;
						include "abasClienteConfig.php";
						break;
				}
				?>

				<div class="push30"></div>


				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código do Lançamento</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_ORCAMENTO" id="COD_ORCAMENTO" value="<?php echo $cod_orcamento; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>" required>
									</div>
								</div>

								<div class="col-md-4">
									<label for="inputName" class="control-label required">Nome do Usuário</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Venda Avulsa - Busca Clientes">
												<i class="fal fa-search" aria-hidden="true" style="padding-top: 3px;"></i>
											</a>
										</span>
										<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leituraOff" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente; ?>">
										<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>" required>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Número do Cartão</label>
										<input type="text" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo de Lançamento </label>
										<select data-placeholder="Selecione o tipo de lançamento" name="COD_LANCAMEN" id="COD_LANCAMEN" class="chosen-select-deselect requiredChk" required>
											<option value=""></option>
											<?php
											$sql = "SELECT * FROM  TIPOLANCAMENTOMARKA WHERE TIP_LANCAMEN = 'A' order by DES_LANCAMEN ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaLancamento = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																  <option value='" . $qrListaLancamento['COD_LANCAMEN'] . "'>" . $qrListaLancamento['DES_LANCAMEN'] . "</option> 
																";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo da Ocorrência </label>
										<select data-placeholder="Selecione o tipo de lançamento" name="COD_OCORREN" id="COD_OCORREN" class="chosen-select-deselect requiredChk" required>
											<option value=""></option>
											<?php
											$sql = "SELECT * FROM OCORRENCIAMARKA WHERE LOG_OCORREN = 'A' order by DES_OCORREN ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaOcorrencia = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																  <option value='" . $qrListaOcorrencia['COD_OCORREN'] . "'>" . $qrListaOcorrencia['DES_OCORREN'] . "</option> 
																";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<style>
									.chosen-container {
										font-size: 16px;
									}

									.chosen-container-single .chosen-single {
										height: 45px;
									}

									.chosen-container-single .chosen-single span {
										margin-top: 5px;
									}
								</style>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>

										<select data-placeholder="Selecione uma unidade para acesso" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" style="width:100%;" tabindex="1" required>
											<option value="">&nbsp;</option>
											<?php
											$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_FANTASI ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
											while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
												if ($qrListaUnive['LOG_ESTATUS'] == 'N') {
													$disabled = "disabled";
												} else {
													$disabled = " ";
												}
												echo "
															  <option value='" . $qrListaUnive['COD_UNIVEND'] . "'" . $disabled . ">" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
															";
											}
											?>
										</select>
										<?php //fnEscreve($sql); 
										?>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Forma de Pagamento </label>
										<select data-placeholder="Selecione a forma de pagamento" name="COD_FORMAPA" id="COD_FORMAPA" class="chosen-select-deselect requiredChk" required>
											<option value=""></option>
											<?php
											$sql = "select DISTINCT * from FORMAPAGAMENTO where COD_EMPRESA = $cod_empresa GROUP BY DES_FORMAPA order by DES_FORMAPA";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrBuscaPagamento = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																  <option value='" . $qrBuscaPagamento['DES_FORMAPA'] . "'>" . $qrBuscaPagamento['DES_FORMAPA'] . "</option> 
																";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-5">
									<div class="form-group">
										<label for="inputName" class="control-label">Justificativa</label>
										<input type="text" class="form-control input-sm" name="DES_COMENTA" id="DES_COMENTA" value="" maxlength="250">
										<div class="help-block with-errors">máx 250 caracteres</div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-2">
								<a name="addProdutos" class="btn btn-info btn-lg addBox" data-url="action.php?mod=<?php echo fnEncode(1070) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idO=<?php echo fnEncode($cod_orcamento) ?>&pop=true" data-title="Lançamento Avulso - Busca Produtos"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Adicionar Produtos</a>
							</div>

							<div class="push20"></div>

							<div class="col-md-12" id="div_Produtos">

								<?php if ($tem_prodaux > 0) { ?>

									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<th width="40" class="text-center"><i class='fal fa-trash' aria-hidden='true'></i></th>
												<th>Código</th>
												<th>Nome do Produto </th>
												<th class="text-center">Qtd.</th>
												<th class="text-right">Valor Unitário</th>
												<th class="text-right">Valor Desconto</th>
												<th class="text-right">Valor Total</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "select B.DES_PRODUTO,A.* from AUXVENDA A,PRODUTOCLIENTE B
											where 
											A.COD_PRODUTO=B.COD_PRODUTO AND
											A.COD_EMPRESA = $cod_empresa
											A.COD_ORCAMENTO = '" . $cod_orcamento . "' and A.COD_ORCAMENTO <> ''  order by A.COD_VENDA	";

											//fnEscreve($sql);

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											$valorTotal = 0;

											while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												$valorTotalProd = ($qrBuscaProdutos['QTD_PRODUTO'] * $qrBuscaProdutos['VAL_UNITARIO']) - $qrBuscaProdutos['VAL_DESCONTOUN'];

												$valorTotal = $valorTotal + $valorTotalProd;

												// fnEscreve($qrBuscaProdutos['QTD_PRODUTO']);
												// fnEscreve($qrBuscaProdutos['QTD_PRODUTO']);
												// fnEscreve(fnValor($qrBuscaProdutos['QTD_PRODUTO'],$casasDec));


												echo "
											<tr>
											  <td class='text-center'><a href='javascript:void(0);' onclick='deleteProd(" . $cod_orcamento . "," . $qrBuscaProdutos['COD_VENDA'] . ")'><i class='fal fa-trash-alt text-danger' aria-hidden='true'></i></a></td>
											  <td>" . $qrBuscaProdutos['COD_PRODUTO'] . "</td>
											  <td>" . $qrBuscaProdutos['DES_PRODUTO'] . "</td>												
											  <td class='text-center'>" . fnValor($qrBuscaProdutos['QTD_PRODUTO'], $casasDec) . "</td>
											  <td class='text-right'>" . fnValor($qrBuscaProdutos['VAL_UNITARIO'], $casasDec) . "</td>
											  <td class='text-right'>" . fnValor($qrBuscaProdutos['VAL_DESCONTOUN'], $casasDec) . "</td>
											  <td class='text-right'>" . fnValor($valorTotalProd, 2) . "</td>
											</tr>
											<input type='hidden' id='COD_PRODUTO' value='" . $qrBuscaProdutos['COD_PRODUTO'] . "'>
											";
											}

											?>

										</tbody>
									</table>

									<div class="row">

										<div class="col-md-2 pull-right">
											<div class="form-group">
												<label for="inputName" class="control-label">Total de Produtos <span class="extSmall">(A)</span></label>
												<input type="text" class="form-control input-sm text-right calcula leituraOff money" readonly="readonly" name="VAL_TOTPRODU" id="VAL_TOTPRODU" value="<?php echo fnValor($valorTotal, $casasDec); ?>">
												<div class="help-block with-errors">Valor líquido dos itens</div>
											</div>
										</div>

									</div>

									<input type="hidden" name="TEM_PRODAUX" id="TEM_PRODAUX" value="<?php echo $tem_prodaux; ?>">

								<?php } else { ?>

									<div class="row">
										<div class="col-md-2 pull-right">
											<div class="form-group">
												<label for="inputName" class="control-label required">Total de Produtos / Venda <span class="extSmall">(A)</span></label>
												<input type="text" class="form-control input-sm text-right calcula money" tabindex="1" name="VAL_TOTPRODU" id="VAL_TOTPRODU" value="" required>
											</div>
										</div>
									</div>

									<input type="hidden" name="TEM_PRODAUX" id="TEM_PRODAUX" value="<?php echo $tem_prodaux; ?>">

								<?php }  ?>

							</div>

						</div>

						<div class="push10"></div>

						<div class="alert alert-danger ?> alert-dismissible top30 bottom30" role="alert" id="msgRetornoSaldo" style="display: none;">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							Valor de Resgate não pode ser maior que o saldo disponivel.
						</div>

						<fieldset class="dadosLancamento">
							<legend>Dados do Lançamento</legend>

							<div class="row">

								<div class="col-md-2 pull-right">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor/Qtd. do Crédito <span class="extSmall">(B)</span></label>
										<input type="text" class="form-control input-sm text-right calcula <?= $money ?>" name="VAL_CREDITO" id="VAL_CREDITO" tabindex="1" value="">
									</div>
								</div>

								<!--<div class="col-md-2 pull-right">
									<div class="form-group">
										<label for="inputName" class="control-label">Data de Validade</label>
										<input type="text" class="form-control input-sm" name="DAT_EXPIRA" id="DAT_EXPIRA" readonly value="<?php echo $dias30; ?>">
									</div>
								</div>-->

								<div id="div_dat_expira" class="col-md-2 pull-right">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data de Validade</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_EXPIRA" id="DAT_EXPIRA" value="<?php echo $dias30; ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<!--<div class="col-md-2 pull-right">
									<div class="form-group">
										<label for="inputName" class="control-label required">Dias para Expirar</label>
										<input type="text" class="form-control input-sm" name="DIAS_EXPIRAR" id="DIAS_EXPIRAR" value="" required>
									</div>
								</div>-->

							</div>

							<div class="row">

								<div class="col-md-2 pull-right" id="FID_RESGATE">
									<div class="form-group">
										<label for="inputName" id="VAL_RESGATE_LABEL" class="control-label">Valor/Qtd. do Resgate <span class="extSmall">(B)</span></label>
										<input type="text" class="form-control input-sm text-right calcula <?= $money ?>" name="VAL_RESGATE" id="VAL_RESGATE" tabindex="2" value="">
										<span class="help-block extSmall" id="VAL_RESGATE_HELP">Fidelidade como Resgate</span>
									</div>
								</div>

								<div class="col-md-2 pull-right" id="FID_DESCONTO">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor/Qtd. do Resgate <span class="extSmall">(C)</span></label>
										<input type="text" class="form-control input-sm text-right calcula <?= $money ?>" name="VAL_DESCONTO" id="VAL_DESCONTO" tabindex="2" value="">
										<span class="help-block extSmall">Fidelidade como Desconto</span>
									</div>
								</div>

								<div class="col-md-2 pull-right">
									<div class="form-group">
										<label for="inputName" class="control-label">Saldo Disponível Resgate</label>
										<input type="text" class="form-control input-sm text-right leituraOff" readonly="readonly" name="VAL_RESGATE_DISP" id="VAL_RESGATE_DISP" value="<?php echo fnValor($credito_disponivel, 2); ?>">
									</div>
								</div>

								<div class="col-md-2 pull-right">
									<div class="form-group">
										<label for="inputName" class="control-label">Saldo Total Resgate</label>
										<input type="text" class="form-control input-sm text-right leituraOff" readonly="readonly" name="VAL_RESGATE_SALDO" id="VAL_RESGATE_SALDO" value="<?php echo fnValor($total_credito, 2); ?>">
									</div>
								</div>

							</div>

							<div class="row">



							</div>

							<div class="row">

								<div class="col-md-2 pull-right">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor do Desconto <span class="extSmall">(C)</span></label>
										<input type="text" class="form-control input-sm text-right calcula <?= $money ?>" name="VAL_GERENCIAL" id="VAL_GERENCIAL" tabindex="2" value="">
										<span class="help-block extSmall">Gerencial (Sobre a venda)</span>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2 pull-right">
									<div class="form-group">
										<label for="inputName" class="control-label required">Confirmação Valor <span class="extSmall">(D)</span></label>
										<input type="text" class="form-control input-sm text-right money" name="VAL_LANCAMENTO" id="VAL_LANCAMENTO" tabindex="4" value="" required>
									</div>
								</div>

								<!--<input type="hidden" name="DAT_AUXILIAR" id="DAT_AUXILIAR" value="<?php echo $dat_ini; ?>">-->

								<div class="col-md-2 pull-right">
									<div class="form-group">
										<label for="inputName" class="control-label">Cupom Fiscal</label>
										<input type="text" class="form-control input-sm" name="COD_CUPOM" id="COD_CUPOM" tabindex="3" value="">
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2 pull-right">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Total / Lançamento</label>
										<input type="text" class="form-control text-right input-sm leituraOff money" name="VAL_TOTVENDA" id="VAL_TOTVENDA" readonly value="<?php echo fnValor($valorTotal, $casasDec); ?>" required>
										<span class="help-block extSmall">A - B - C (auditoria automática)</span>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn" tabindex="5"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar Lançamento</button>

						</div>

						<input type="hidden" name="TIP_CONTABIL" id="TIP_CONTABIL" value="<?php echo $tip_contabil; ?>">
						<input type="hidden" name="LOG_PONTUAR" id="LOG_PONTUAR" value="<?php echo $log_pontuar; ?>">
						<input type="hidden" name="LOG_FUNCIONA" id="LOG_FUNCIONA" value="<?php echo $log_funciona; ?>">
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?php echo $casasDec; ?>">
						<input type="hidden" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo fnencode($num_cgcecpf); ?>">
						<input type="hidden" name="COD_UNIVEND_CLI" id="COD_UNIVEND_CLI" value="<?php echo fnencode($cod_univend_cli); ?>">
						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="REFRESH_PRODUTOS" id="REFRESH_PRODUTOS" value="N">
						<input type="hidden" name="TEM_PRODUTOS" id="TEM_PRODUTOS" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<div class="push20"></div>

<script type="text/javascript">
	$(document).ready(function() {

		// $('#VAL_TOTPRODU').on('input', function() {
		// 	$('#VAL_TOTVENDA').val($(this).val());
		// 	$('#VAL_LANCAMENTO').val($(this).val());
		// })


		$('#CAD').click(function(e) {
			var valResgate = parseFloat($('#VAL_RESGATE').val());
			var valResgateDisp = parseFloat($('#VAL_RESGATE_DISP').val());

			if (valResgate > valResgateDisp) {
				e.preventDefault(); // Impede o envio do formulário
				$('#msgRetornoSaldo').show();
				$('#VAL_RESGATE_LABEL').addClass('text-danger');
				$('#VAL_RESGATE_HELP')
					.addClass('text-danger') // Adiciona a classe text-danger
					.text('Valor de Resgate não pode ser maior que o saldo disponível.');
			} else {
				$('#msgRetorno').hide(); // Oculta o alert se a validação passar
			}
		});

		/*$('#DIAS_EXPIRAR').change(function() {
		    var dias = parseInt($('#DIAS_EXPIRAR').val(), 10); // Converter para número
		    var hoje = $('#DAT_AUXILIAR').val();
			if (!isNaN(dias) && parseInt(dias) > 0) {
						    // Dividir a data em dia, mês e ano
				var partesData = hoje.split('/');
				var dia = parseInt(partesData[0], 10);
			    var mes = parseInt(partesData[1], 10) - 1; // Mês começa em 0 (janeiro é 0)
			    var ano = parseInt(partesData[2], 10);

			    // Criar objeto Date com a data brasileira
			    var data = new Date(ano, mes, dia);

			    // Adicionar os dias
			    data.setDate(data.getDate() + dias);

			    // Agora 'data' contém a nova data após adicionar os dias

			    $('#DAT_EXPIRA').val(data.toLocaleDateString('pt-BR'));
			}
		});*/

		$('.money3').mask("#.##0,000", {
			reverse: true
		});
		$('.money4').mask("#.##0,0000", {
			reverse: true
		});
		$('.money5').mask("#.##0,00000", {
			reverse: true
		});

		$('#DAT_INI_GRP').datetimepicker({
			format: 'DD/MM/YYYY'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$('#COD_LANCAMEN').change(function() {
			if ($(this).val() == 3) {
				$('#DAT_EXPIRA').prop('required', true);
			} else {
				$('#DAT_EXPIRA').prop('required', false);
			}

		});

		// Oculta campos
		$('a[name=addProdutos]').parent().hide();
		$('input[name=VAL_TOTPRODU]').parent().hide();
		$('input[name=VAL_CREDITO]').parent().hide();
		$('input[name=DAT_EXPIRA]').parent().hide();
		$('#div_dat_expira').hide();
		$('input[name=DIAS_EXPIRAR]').parent().hide();
		$('input[name=VAL_RESGATE_SALDO]').parent().hide();
		$('input[name=VAL_RESGATE_DISP]').parent().hide();
		$('input[name=VAL_RESGATE]').parent().hide();
		$('input[name=VAL_DESCONTO]').parent().hide();
		$('input[name=VAL_GERENCIAL]').parent().hide();
		$('input[name=COD_VENDAPDV]').parent().hide();
		$('input[name=VAL_LANCAMENTO]').parent().hide();
		$('input[name=VAL_TOTVENDA]').parent().hide();
		$('.dadosLancamento').hide();
		$('input[name=DES_COMENTA]').parent().hide();

		$(".calcula").change(function() {
			recalcula();
		});

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//modal close
		$('.modal').on('hidden.bs.modal', function() {

			if ($('#REFRESH_PRODUTOS').val() == "S") {
				//alert("atualiza");
				RefreshProdutos(<?php echo $cod_empresa; ?>, <?php echo $cod_orcamento; ?>, "VAL");
				$('#REFRESH_PRODUTOS').val("N");
			}

			if ($('#REFRESH_CLIENTE').val() == "S") {
				var newCli = $('#NOVO_CLIENTE').val();
				window.location.href = "action.php?mod=<?php echo fnEncode(1067); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=" + newCli + " ";
				$('#REFRESH_PRODUTOS').val("N");
			}

		});

		$("#COD_LANCAMEN").change(function() {
			var valor = $('#COD_LANCAMEN').val();

			// Tipo Lançamento = Crédito Extra
			if (valor == 3) {
				$('input[name=DES_COMENTA]').parent().show();
				$('input[name=VAL_TOTPRODU]').parent().hide();
				$('#VAL_TOTPRODU').prop('required', false);
				$('input[name=VAL_CREDITO]').parent().show();
				//$('input[name=DIAS_EXPIRAR]').parent().show();
				$('input[name=DAT_EXPIRA]').parent().show();
				$('#div_dat_expira').show();
				//$('input[name=DIAS_EXPIRAR]').prop('required', true);
				$('input[name=VAL_RESGATE_SALDO]').parent().hide();
				$('input[name=VAL_RESGATE_DISP]').parent().hide();
				$('input[name=VAL_RESGATE]').parent().hide();
				$('input[name=VAL_DESCONTO]').parent().hide();
				$('input[name=VAL_GERENCIAL]').parent().hide();
				$('input[name=COD_VENDAPDV]').parent().hide();
				$('a[name=addProdutos]').parent().hide();
				$('input[name=VAL_LANCAMENTO]').parent().show();
				$('input[name=VAL_TOTVENDA]').parent().show();
				$('.dadosLancamento').show();
				$('#formulario').validator('update');

				// Tipo Lançamento = Venda Avulsa
			} else if (valor == 4) {
				$('input[name=DES_COMENTA]').parent().hide();
				$('input[name=VAL_TOTPRODU]').parent().show();
				$('#VAL_TOTPRODU').prop('required', true);
				$('input[name=VAL_CREDITO]').parent().hide();
				$('input[name=DAT_EXPIRA]').parent().hide();
				$('#div_dat_expira').hide();
				//$('input[name=DIAS_EXPIRAR]').parent().hide();
				//$('input[name=DIAS_EXPIRAR]').prop('required', false);
				$('input[name=VAL_RESGATE_SALDO]').parent().show();
				$('input[name=VAL_RESGATE_DISP]').parent().show();
				$('input[name=VAL_RESGATE]').parent().show();
				$('input[name=VAL_DESCONTO]').parent().show();
				$('input[name=VAL_GERENCIAL]').parent().show();
				$('input[name=COD_VENDAPDV]').parent().show();
				$('a[name=addProdutos]').parent().show();
				$('input[name=VAL_LANCAMENTO]').parent().show();
				$('input[name=VAL_TOTVENDA]').parent().show();
				$('.dadosLancamento').show();
				$('#formulario').validator('update');

				var tip_contabil = '<?php echo $tip_contabil ?>';

				if (tip_contabil == 'RESG') {
					$('#FID_DESCONTO').hide();
				}

				if (tip_contabil == 'DESC') {
					$('#FID_RESGATE').hide();
				}
			}
		});

		$("#formulario").submit(function(e) {
			var valor = $('#COD_LANCAMEN').val();

			if (valor == 3) {
				var valorCred = limpaValor($('#VAL_CREDITO').val());
				var valorLanca = limpaValor($('#VAL_LANCAMENTO').val());

				console.log(valorTotal);
				console.log(valorLanca);

				if (valorCred != valorLanca) {
					$.confirm({
						title: 'Atenção',
						animation: 'opacity',
						closeAnimation: 'opacity',
						icon: 'fa fa-warning',
						type: 'red',
						content: 'Valor da confirmação diferente do valor total',
						buttons: {
							ok: function() {

							},
						}
					});

					e.preventDefault();
				}

			} else if (valor == 4) {

				var valorTotal = limpaValor($('#VAL_TOTVENDA').val());
				var valorLanca = limpaValor($('#VAL_LANCAMENTO').val());

				console.log(valorTotal);
				console.log(valorLanca);

				if (valorTotal != valorLanca) {
					$.confirm({
						title: 'Atenção',
						animation: 'opacity',
						closeAnimation: 'opacity',
						icon: 'fa fa-warning',
						type: 'red',
						content: 'Valor da confirmação diferente do valor do crédito',
						buttons: {
							ok: function() {

							},
						}
					});

					e.preventDefault();
				}

				if ($('#TEM_PRODUTOS').val() == "N") {
					$.confirm({
						title: 'Atenção',
						animation: 'opacity',
						closeAnimation: 'opacity',
						icon: 'fa fa-warning',
						type: 'red',
						content: 'Sua venda não contém produtos.',
						buttons: {
							ok: function() {

							},
						}
					});

					e.preventDefault();
				}

			}
		});
	});

	function recalcula() {

		var valTotal = 0;
		var tip_contabil = '<?php echo $tip_contabil ?>';

		$('.calcula').each(function(index, item) {
			if ($(item).val() != "") {

				if ($(item).attr('id') == "VAL_RESGATE" || $(item).attr('id') == "VAL_DESCONTO") {
					// if (tip_contabil == 'RESG') {
					valTotal = valTotal - limpaValor($(item).val());
					// }
				} else if ($(item).attr('id') == "VAL_GERENCIAL") {
					valTotal = valTotal - limpaValor($(item).val());
				} else {
					console.log(limpaValor($(item).val()));
					valTotal = valTotal + limpaValor($(item).val());
				}
			}
		});
		$('#VAL_TOTVENDA').val();
		$('#VAL_TOTVENDA').unmask();
		$('#VAL_TOTVENDA').val(valTotal.toFixed(2));
		// $('#VAL_TOTVENDA').mask("#.##0,00", {reverse: true});

	}

	function deleteProd(idOrc, idItem) {
		RefreshProdutosExc(<?php echo $cod_empresa; ?>, idOrc, 'EXC_MANUAL', idItem);
	}

	function RefreshProdutos(idEmp, idOrc, tipo) {
		$.ajax({
			type: "GET",
			url: "ajxListaOrcamento.php",
			data: {
				ajx1: idEmp,
				ajx2: idOrc,
				ajx3: tipo,
				CASAS_DEC: <?= $casasDec ?>
			},
			beforeSend: function() {
				$('#div_Produtos').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_Produtos").html(data);
				$("#TEM_PRODUTOS").val("S");
			},
			error: function() {
				$('#div_Produtos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function RefreshProdutosExc(idEmp, idOrc, tipo, idItem) {
		$.ajax({
			type: "GET",
			url: "ajxListaOrcamento.php",
			data: {
				ajx1: idEmp,
				ajx2: idOrc,
				ajx3: tipo,
				ajx4: idItem,
				CASAS_DEC: <?= $casasDec ?>
			},
			beforeSend: function() {
				$('#div_Produtos').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_Produtos").html(data);
				//recalcula();					
			},
			error: function() {
				$('#div_Produtos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {

	}
</script>