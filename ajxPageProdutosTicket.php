<?php
include '_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$where = "";
$andFiltro = "";
$cod_prodtkt = "";
$cod_produto = "";
$nom_prodtkt = "";
$arrLojasAut = "";
$log_ativotk = "";
$log_prodtkt = "";
$dat_iniptkt = "";
$dat_fimptkt = "";
$pct_desctkt = "";
$val_prodtkt = "";
$val_promtkt = "";
$log_habitkt = "";
$log_ofertas = "";
$cod_persona_tkt = "";
$Arr_COD_PERSONA_TKT = "";
$Arr_COD_MULTEMP = "";
$i = 0;
$cod_univend_aut = "";
$Arr_COD_UNIVEND_AUT = "";
$cod_univend_blk = "";
$Arr_COD_UNIVEND_BLK = "";
$cod_categortkt = "";
$cod_categortkt2 = "";
$cod_desctkt = "";
$des_mensgtkt = "";
$CarregaMaster = "";
$log_tktunivend = "";
$orderBy = "";
$ARRAY_UNIDADE1 = [];
$ARRAY_UNIDADE = [];
$ARRAY_VENDEDOR1 = [];
$ARRAY_VENDEDOR = [];
$arrayQuery = [];
$qrListaPersonas = [];
$persona = [];
$nomeRel = "";
$arquivoCaminho = "";
$selectUnifica = "";
$dat_ini = "";
$dat_fim = "";
$andEmpresa = "";
$lojasSelecionadas = "";
$groupUnifica = "";
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$retorno = "";
$inicio = "";
$teste = "";
$qrBuscaProdutosTkt = "";
$NOM_ARRAY_NON_VENDEDOR = [];
$lojaLoop = "";
$nomeLoja = "";
$NOM_ARRAY_UNIDADE = [];
$mostraLOG_ATIVOTK = "";
$mostraLOG_PRODTKT = "";
$mostraCOD_UNIVEND_AUT = "";
$mostraCOD_UNIVEND_BLK = "";
$mostraDES_IMAGEM = "";
$mostraOFERTAS = "";
$mostraAUTOMATIC = "";
$mostraValidade = "";
$mostraValidadeHora = "";
$mostraInvalidado = "";
$textoDanger = "";
$e = "";
$arrayPersonas = [];
$valores = "";
$iconePersona = "";
$obj = "";
$log_destaque = '';

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnLimpaCampoZero(fnDecode(@$_GET['id']));
$where = fnDecode(@$_POST['WHERE']);
$andFiltro = @$_REQUEST['AND_FILTRO'];

$cod_prodtkt = fnLimpaCampoZero(@$_POST['COD_PRODTKT']);
$cod_produto = fnLimpaCampoZero(@$_POST['COD_PRODUTO']);
$cod_univend = fnLimpaCampoZero(@$_POST['COD_UNIVEND']);
$nom_prodtkt = fnLimpaCampo(@$_POST['NOM_PRODTKT']);
$arrLojasAut = explode(",", $_SESSION["SYS_COD_UNIVEND"]);
if (empty(@$_REQUEST['LOG_ATIVOTK'])) {
	$log_ativotk = 'N';
} else {
	$log_ativotk = @$_REQUEST['LOG_ATIVOTK'];
}
if (empty(@$_REQUEST['LOG_PRODTKT'])) {
	$log_prodtkt = 'N';
} else {
	$log_prodtkt = @$_REQUEST['LOG_PRODTKT'];
}
if (empty(@$_REQUEST['LOG_DESTAQUE'])) {
	$log_destaque = 'N';
} else {
	$log_destaque = $_REQUEST['LOG_DESTAQUE'];
}

$dat_iniptkt = fnDataSql(@$_POST['DAT_INIPTKT']);
if ($dat_iniptkt != '' && $dat_iniptkt != 0) {
	$dat_iniptkt = $dat_iniptkt . " 00:00:00";
}

$dat_fimptkt = fnDataSql(@$_POST['DAT_FIMPTKT']);
if ($dat_fimptkt != '' && $dat_fimptkt != 0) {
	$dat_fimptkt = $dat_fimptkt . " 23:59:59";
}

$pct_desctkt = fnLimpaCampo(@$_POST['PCT_DESCTKT']);
$val_prodtkt = fnLimpaCampo(@$_POST['VAL_PRODTKT']);
$val_promtkt = fnLimpaCampo(@$_POST['VAL_PROMTKT']);
$log_habitkt = fnLimpaCampo(@$_POST['LOG_HABITKT']);
if (empty(@$_REQUEST['LOG_HABITKT'])) {
	$log_habitkt = 'N';
} else {
	$log_habitkt = @$_REQUEST['LOG_HABITKT'];
}
if (empty(@$_REQUEST['LOG_OFERTAS'])) {
	$log_ofertas = 'N';
} else {
	$log_ofertas = @$_REQUEST['LOG_OFERTAS'];
}

//$cod_persona_tkt = fnLimpaCampo(@$_POST['COD_PERSONA_TKT']);
//array das personas
if (isset($_POST['COD_PERSONA_TKT'])) {
	$Arr_COD_PERSONA_TKT = @$_POST['COD_PERSONA_TKT'];
	//print_r($Arr_COD_MULTEMP);			 
	for ($i = 0; $i < count($Arr_COD_PERSONA_TKT); $i++) {
		$cod_persona_tkt = $cod_persona_tkt . $Arr_COD_PERSONA_TKT[$i] . ",";
	}
	$cod_persona_tkt = substr($cod_persona_tkt, 0, -1);
} else {
	$cod_persona_tkt = "0";
}

//$cod_univend_aut = fnLimpaCampo(@$_POST['COD_UNIVEND_AUT']);
//array das lojas
if (isset($_POST['COD_UNIVEND_AUT'])) {
	$Arr_COD_UNIVEND_AUT = @$_POST['COD_UNIVEND_AUT'];
	//print_r($Arr_COD_MULTEMP);			 
	for ($i = 0; $i < count($Arr_COD_UNIVEND_AUT); $i++) {
		$cod_univend_aut = $cod_univend_aut . $Arr_COD_UNIVEND_AUT[$i] . ",";
	}
	$cod_univend_aut = substr($cod_univend_aut, 0, -1);
} else {
	$cod_univend_aut = "0";
}

//$cod_univend_blk = fnLimpaCampo(@$_POST['COD_UNIVEND_BLK']);			
//array das lojas
if (isset($_POST['COD_UNIVEND_BLK'])) {
	$Arr_COD_UNIVEND_BLK = @$_POST['COD_UNIVEND_BLK'];
	//print_r($Arr_COD_MULTEMP);			 
	for ($i = 0; $i < count($Arr_COD_UNIVEND_BLK); $i++) {
		$cod_univend_blk = $cod_univend_blk . $Arr_COD_UNIVEND_BLK[$i] . ",";
	}
	$cod_univend_blk = substr($cod_univend_blk, 0, -1);
} else {
	$cod_univend_blk = "0";
}

$cod_categortkt = fnLimpaCampo(@$_POST['COD_CATEGORTKT']);
$cod_categortkt2 = fnLimpaCampo(@$_POST['COD_CATEGORTKT2']);
$cod_desctkt = fnLimpaCampo(@$_REQUEST['COD_DESCTKT']);
if (empty($cod_desctkt)) {
	$cod_desctkt = 0;
}

$des_mensgtkt = fnLimpaCampo(@$_POST['DES_MENSGTKT']);

$CarregaMaster = '1';

if ($log_tktunivend == "S" && $CarregaMaster == '0') {

	if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {
		$CarregaMaster = '1';
	} else {
		$CarregaMaster = '0';
	}
}

$andDestaque = "";
if (!empty($_POST["LOG_DESTAQUE"]) && $_POST["LOG_DESTAQUE"] == "S") {
	$andDestaque = " AND PRODUTOTKT.LOG_OFERTAS = 'S'";
}

if (@$_POST["DES_ORDENAC"] <> "") {

	switch (@$_POST["DES_ORDENAC"]) {
		case 'alfa-asc':
			$orderBy = "ORDER BY NOM_PRODTKT ASC";
			break;

		case 'alfa-desc':
			$orderBy = "ORDER BY NOM_PRODTKT DESC";
			break;

		case 'data-asc':
			$orderBy = "ORDER BY DAT_FIMPTKT ASC";
			break;

		case 'data-desc':
			$orderBy = "ORDER BY DAT_FIMPTKT DESC";
			break;

		case 'cat-asc':
			$orderBy = "ORDER BY DES_CATEGOR ASC";
			break;

		case 'cat-desc':
			$orderBy = "ORDER BY DES_CATEGOR DESC";
			break;

		default:
			$orderBy = "order by DES_CATEGOR, NOM_PRODTKT";
			break;
	}
}


$ARRAY_UNIDADE1 = array(
	'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
	'cod_empresa' => $cod_empresa,
	'conntadm' => $connAdm->connAdm(),
	'IN' => 'N',
	'nomecampo' => '',
	'conntemp' => '',
	'SQLIN' => ""
);
$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

$ARRAY_VENDEDOR1 = array(
	'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa in($cod_empresa,3)",
	'cod_empresa' => $cod_empresa,
	'conntadm' => $connAdm->connAdm(),
	'IN' => 'N',
	'nomecampo' => '',
	'conntemp' => '',
	'SQLIN' => ""
);
$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

$sql = "SELECT * FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' AND COD_EXCLUSA = 0 ORDER BY DES_PERSONA ";

$arrayQuery = mysqli_query(conntemp($cod_empresa, ""), $sql);

while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

	$persona[$qrListaPersonas['COD_PERSONA']] = array(
		'DES_COR' => $qrListaPersonas['DES_COR'],
		'DES_ICONE' => $qrListaPersonas['DES_ICONE'],
		'DES_PERSONA' => $qrListaPersonas['DES_PERSONA'],
	);
}

switch ($opcao) {

	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivoCaminho = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		// $sql = "SELECT UV.NOM_FANTASI AS LOJA, 
		// 			   LA.NOM_USUARIO AS USUARIO,
		// 			   TU.DES_TPUSUARIO AS TIP_USUARIO,
		// 			   $selectUnifica
		// 			   LA.DATA_ACESSO,
		// 			   LA.IP_ACESSO,
		// 			   LA.PORTA_ACESSO
		// 		FROM LOG_ACESSO LA
		// 		INNER JOIN USUARIOS US ON US.COD_USUARIO = LA.COD_USUARIO
		// 		INNER JOIN TIPOUSUARIO TU ON TU.COD_TPUSUARIO = US.COD_TPUSUARIO
		// 		LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = US.COD_UNIVEND
		// 		LEFT JOIN EMPRESAS EM ON EM.COD_EMPRESA = LA.COD_EMPRESA
		// 		WHERE
		// 		LA.DATA_ACESSO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
		// 		$andEmpresa
		// 		AND US.COD_UNIVEND IN($lojasSelecionadas)
		// 		$groupUnifica
		// 		ORDER BY LA.DATA_ACESSO DESC";

		// $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);

		// $arquivo = fopen($arquivoCaminho, 'w',0);

		// while($headers=mysqli_fetch_field($arrayQuery)){
		// 	$CABECHALHO[]=$headers->name;
		// }
		// fputcsv ($arquivo,$CABECHALHO,';','"','\n');

		// while ($row=mysqli_fetch_assoc($arrayQuery)){ 


		// 	//$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
		// 	//$textolimpo = json_decode($limpandostring, true);
		// 	$array = array_map("utf8_decode", $row);
		// 	fputcsv($arquivo, $array, ';', '"');

		// 	//echo "<pre>";
		// 	//print_r($row);
		// 	//echo "<pre>";
		// }
		// fclose($arquivo);

		break;

	default:

		$sql = "SELECT PRODUTOTKT.COD_PRODUTO
						FROM PRODUTOTKT
						inner join PRODUTOCLIENTE on PRODUTOCLIENTE.COD_PRODUTO = PRODUTOTKT.COD_PRODUTO
						WHERE PRODUTOTKT.COD_PRODUTO = PRODUTOCLIENTE.COD_PRODUTO 
						AND PRODUTOTKT.COD_EMPRESA = $cod_empresa 
						-- AND PRODUTOCLIENTE.COD_EXCLUSA = 0
                        AND  case when PRODUTOCLIENTE.COD_EXCLUSA = 0 then 0 ELSE 1 end IN (0,1)
						$where
						$andFiltro
						$andDestaque";

		//fnEscreve($sql);

		$retorno = mysqli_query(conntemp($cod_empresa, ""), $sql);
		$total_itens_por_pagina = mysqli_num_rows($retorno);

		$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = " SELECT PRODUTOCLIENTE.DES_PRODUTO,
							      PRODUTOCLIENTE.COD_EXTERNO,
							      PRODUTOCLIENTE.COD_PRODUTO AS PRODUTO,
							      DESCONTOTKT.ABV_DESCTKT,												
							   IF( PRODUTOCLIENTE.DES_IMAGEM <> '','S','N') AS TEM_IMAGEM,
							   PRODUTOTKT.COD_USUCADA USUARIOCAD,
							   PRODUTOTKT.*,
							   categoriatkt.*
							FROM PRODUTOTKT 
							left join categoriatkt on categoriatkt.COD_CATEGORTKT = PRODUTOTKT.COD_CATEGORTKT 
							left join DESCONTOTKT on DESCONTOTKT.COD_DESCTKT = PRODUTOTKT.COD_DESCTKT 
							inner join PRODUTOCLIENTE on PRODUTOCLIENTE.COD_PRODUTO = PRODUTOTKT.COD_PRODUTO
							WHERE PRODUTOTKT.COD_PRODUTO = PRODUTOCLIENTE.COD_PRODUTO 
							AND PRODUTOTKT.COD_EMPRESA = $cod_empresa 
							-- AND PRODUTOCLIENTE.COD_EXCLUSA = 0
                            AND  case when PRODUTOCLIENTE.COD_EXCLUSA = 0 then 0 ELSE 1 end IN (0,1)
							$where
							$andFiltro
							$andDestaque
							$orderBy
							LIMIT $inicio, $itens_por_pagina";
		// fnConsole($sql);
		//fnTestesql(connTemp($cod_empresa,''),$sql);

		$arrayQuery = mysqli_query(conntemp($cod_empresa, ""), $sql);
		$teste = mysqli_num_rows($arrayQuery);

		// fnEscreve($sql);

		$count = 0;
		//constroi array persona
		while ($qrBuscaProdutosTkt = mysqli_fetch_assoc($arrayQuery)) {

			// if($CarregaMaster == '0'){
			// 	if(recursive_array_search($qrBuscaProdutosTkt['COD_UNIVEND'],$arrLojasAut) === false){
			// 		continue;
			// 	}
			// }

			$NOM_ARRAY_NON_VENDEDOR = "";

			if ($qrBuscaProdutosTkt['USUARIOCAD'] != 0) {

				$NOM_ARRAY_NON_VENDEDOR = (array_search($qrBuscaProdutosTkt['USUARIOCAD'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
			}

			$lojaLoop = $qrBuscaProdutosTkt['COD_UNIVEND'];
			if ($lojaLoop == 9999) {
				$nomeLoja = "Todas";
			} else {
				$NOM_ARRAY_UNIDADE = (array_search($qrBuscaProdutosTkt['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
				$nomeLoja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
			}

			$count++;

			if ($qrBuscaProdutosTkt['LOG_ATIVOTK'] == "S") {
				$mostraLOG_ATIVOTK = '<i class="fal fa-check" aria-hidden="true"></i>';
			} else {
				$mostraLOG_ATIVOTK = '';
			}

			if ($qrBuscaProdutosTkt['LOG_PRODTKT'] == "S") {
				$mostraLOG_PRODTKT = '<i class="fal fa-check" aria-hidden="true"></i>';
			} else {
				$mostraLOG_PRODTKT = '';
			}

			if ($qrBuscaProdutosTkt['COD_UNIVEND_AUT'] != "0") {
				$mostraCOD_UNIVEND_AUT = '<i class="fal fa-check" aria-hidden="true"></i>';
			} else {
				$mostraCOD_UNIVEND_AUT = '';
			}

			if ($qrBuscaProdutosTkt['COD_UNIVEND_BLK'] != "0") {
				$mostraCOD_UNIVEND_BLK = '<i class="fal fa-check" aria-hidden="true"></i>';
			} else {
				$mostraCOD_UNIVEND_BLK = '';
			}

			if ($qrBuscaProdutosTkt['TEM_IMAGEM'] == "S") {
				$mostraDES_IMAGEM = '<i class="fal fa-check" aria-hidden="true"></i>';
			} else {
				$mostraDES_IMAGEM = '';
			}

			if ($qrBuscaProdutosTkt['LOG_OFERTAS'] == "S") {
				$mostraOFERTAS = '<i class="fal fa-check" aria-hidden="true"></i>';
			} else {
				$mostraOFERTAS = '';
			}

			if ($qrBuscaProdutosTkt['LOG_AUTOMATIC'] == "S") {
				$mostraAUTOMATIC = '<i class="faL fa-check" aria-hidden="true"></i>';
			} else {
				$mostraAUTOMATIC = '';
			}


			//fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']
			//se validade está vencida 
			if ($qrBuscaProdutosTkt['DAT_FIMPTKT'] != "") {

				$mostraValidade = '';
				$mostraValidadeHora = '';
				$mostraInvalidado = '';
				$textoDanger = '';
				if (date('Y-m-d h:i:s') > $qrBuscaProdutosTkt['DAT_FIMPTKT']) {
					//$mostraValidade = '<i class="fa fa-check-o" aria-hidden="true"></i>';	
					//$mostraValidade = ''.fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']);
					$mostraValidade = '' . fnFormatDate($qrBuscaProdutosTkt['DAT_FIMPTKT']);
					$mostraValidadeHora = fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']);
					$textoDanger = "text-danger";
				} else {
					//$mostraValidade = fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']); 
					$mostraValidade = fnFormatDate($qrBuscaProdutosTkt['DAT_FIMPTKT']);
					$mostraValidadeHora = fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']);
					if ($qrBuscaProdutosTkt['LOG_ATIVOTK'] == "N") {
						$mostraInvalidado = '<i class="fal fa-times text-danger" aria-hidden="true"></i>';
					}
					$textoDanger = "text-success";
				}
				$e = explode(" ", $mostraValidadeHora);
				$mostraValidadeHora = @$e['1'];
			} else {
				$mostraValidade = '';
				$mostraValidadeHora = '';
			}

			//fnEscreve($qrBuscaProdutosTkt['TEM_IMAGEM']);
			//fnEscreve($qrBuscaProdutosTkt['DAT_INIPTKT']);

			echo "
							<tr data-id='" . $qrBuscaProdutosTkt['COD_PRODTKT'] . "'>
							  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
							  <td>" . $qrBuscaProdutosTkt['COD_PRODTKT'] . "</td>
							  <td>" . $qrBuscaProdutosTkt['COD_EXTERNO'] . "</td>
							  <td><a href='action.do?mod=" . fnEncode(1046) . "&id=" . fnEncode($cod_empresa) . "&idP=" . fnEncode($qrBuscaProdutosTkt['COD_PRODUTO']) . "'>" . $qrBuscaProdutosTkt['NOM_PRODTKT'] . "</a></td>
							  <td><small>" . $nomeLoja . "</small></td>
							  <td><small>" . $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'] . "</small></td>
							  <td>" . $qrBuscaProdutosTkt['DES_CATEGOR'] . "</td>
							  <td align='center'><input type='checkbox' name='check_data' value=" . $qrBuscaProdutosTkt['COD_PRODTKT'] . "></th>
							  <td class='" . $textoDanger . " dt-validade'>
							    <small>
								<a href='#' class='editable editable-click " . $textoDanger . "' data-type='date' data-format='dd/mm/yyyy' data-clear='false' data-empresa='$cod_empresa' data-pk='" . $qrBuscaProdutosTkt['COD_PRODTKT'] . "' data-title='Editar'>$mostraValidade</a> $mostraValidadeHora
								</small>
							  </td>
							  <td class='text-center'><small>" . $qrBuscaProdutosTkt['ABV_DESCTKT'] . "</small></td>
							  <td class='text-center'>
							  ";

			//personas
			//<td class='text-center'><a class='btn btn-circle-long btn-success' data-toggle='tooltip' data-placement='top' data-original-title='em estoque' > 00</a></td>

			$arrayPersonas = explode(',', $qrBuscaProdutosTkt['COD_PERSONA_TKT']);
			foreach ($arrayPersonas as $valores) {

				if (substr($persona[$valores]['DES_ICONE'], 0, 3) == 'fa-') {
					$iconePersona = 'fas ' . $persona[$valores]['DES_ICONE'];
				} else {
					$iconePersona = $persona[$valores]['DES_ICONE'];
				}

				echo "<a class='btn btn-circle-long' style='color: #fff; background-color: #" . $persona[$valores]['DES_COR'] . "; border-color: #" . $persona[$valores]['DES_COR'] . ";' data-toggle='tooltip' data-placement='top' data-original-title='" . $persona[$valores]['DES_PERSONA'] . "' ><i class='" . $iconePersona . "' aria-hidden='true'></i></a>&nbsp;";
			}


			echo "
							  </td>
							  <td class='text-center'>" . $mostraLOG_ATIVOTK . $mostraInvalidado . "</td>
							  <td class='text-center'>" . $mostraAUTOMATIC . "</td> 
							  <td class='text-center'>" . $mostraLOG_PRODTKT . "</td>
							  <td class='text-center'>" . $mostraOFERTAS . "</td>
							  <td class='text-center'>" . $mostraCOD_UNIVEND_AUT . "</td>
							  <td class='text-center'>" . $mostraCOD_UNIVEND_BLK . "</td>
							  <td class='text-center'>" . $mostraDES_IMAGEM . "</td>
							</tr>
							<input type='hidden' id='ret_COD_PRODTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_PRODTKT'] . "'>
							<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_PRODUTO'] . "'>
							<input type='hidden' id='ret_NOM_PRODTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['NOM_PRODTKT'] . "'>
							<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrBuscaProdutosTkt['DES_PRODUTO'] . "'>
							<input type='hidden' id='ret_LOG_PRODTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['LOG_PRODTKT'] . "'>
							<input type='hidden' id='ret_LOG_HABITKT_" . $count . "' value='" . $qrBuscaProdutosTkt['LOG_HABITKT'] . "'>
							<input type='hidden' id='ret_DAT_INIPTKT_" . $count . "' value='" . fnFormatDateTime($qrBuscaProdutosTkt['DAT_INIPTKT']) . "'>
							<input type='hidden' id='ret_DAT_FIMPTKT_" . $count . "' value='" . fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']) . "'>
							<input type='hidden' id='ret_PCT_DESCTKT_" . $count . "' value='" . number_format($qrBuscaProdutosTkt['PCT_DESCTKT'], 2, ",", ".") . "'>
							<input type='hidden' id='ret_VAL_PRODTKT_" . $count . "' value='" . number_format($qrBuscaProdutosTkt['VAL_PRODTKT'], 2, ",", ".") . "'>
							<input type='hidden' id='ret_VAL_PROMTKT_" . $count . "' value='" . number_format($qrBuscaProdutosTkt['VAL_PROMTKT'], 2, ",", ".") . "'>
							<input type='hidden' id='ret_COD_PERSONA_TKT_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_PERSONA_TKT'] . "'>
							<input type='hidden' id='ret_COD_UNIVEND_AUT_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_UNIVEND_AUT'] . "'>
							<input type='hidden' id='ret_COD_UNIVEND_BLK_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_UNIVEND_BLK'] . "'>
							<input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_UNIVEND'] . "'>
							<input type='hidden' id='ret_COD_CATEGORTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_CATEGORTKT'] . "'>
							<input type='hidden' id='ret_LOG_OFERTAS_" . $count . "' value='" . $qrBuscaProdutosTkt['LOG_OFERTAS'] . "'>
							<input type='hidden' id='ret_LOG_ATIVOTK_" . $count . "' value='" . $qrBuscaProdutosTkt['LOG_ATIVOTK'] . "'>
							<input type='hidden' id='ret_COD_DESCTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_DESCTKT'] . "'>
							<input type='hidden' id='ret_DES_MENSGTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['DES_MENSGTKT'] . "'>
							";
		}

?>

		<script type="text/javascript">
			$('.editable').editable({
				ajaxOptions: {
					type: 'post'
				},
				success: function(data) {
					var $obj = $(this);
					var ids = $(this).data("pk");
					$('input[name="check_data"]:checked').each(function() {
						ids += "," + $(this).val();
					});
					var data = "empresa=" + $(this).data("empresa") + "&pk=" + $(this).data("pk") + "&ids=" + ids;
					setTimeout(function() {
						data += "&data=" + ($("tr[data-id=" + $obj.data("pk") + "]").find("td.dt-validade a").html());

						$.ajax({
							method: 'POST',
							url: 'ajxProdutosTicket.php',
							data: data,
							success: function(data) {
								console.log(data);
								$.each(data.ids, function(index, value) {
									$("tr[data-id=" + value + "]").find("td.dt-validade").removeClass("text-danger").removeClass("text-success").addClass(data.class);
									$("tr[data-id=" + value + "]").find("td.dt-validade a").removeClass("text-danger").removeClass("text-success").addClass(data.class);
									$("tr[data-id=" + value + "]").find("td.dt-validade a").html(data.data);
								});
							}
						});
						console.log($obj.data());
					}, 100);
				}
			});
		</script>

<?php

		break;
}

?>