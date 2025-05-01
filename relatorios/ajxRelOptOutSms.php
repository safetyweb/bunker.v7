<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$tipo = "";
$dat_ini = "";
$dat_fim = "";
$log_opttrue = "";
$andEmpresa = "";
$andOptout = "";
$log_optout = "";
$cod_empresa_cli = "";
$cod_cliente = "";
$optout = "";
$log_sms = "";
$sqlOptOutLista = "";
$sqlOptOutCli = "";
$campo = "";
$valor = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = [];
$array = [];
$row = "";
$newRow = "";
$objeto = "";
$arrayColumnsNames = [];
$retorno = "";
$inicio = "";
$qrOptOut = "";
$sqlCli = "";
$qrCli = "";
$checkOptOut = "";


// require_once '../js/plugins/Spout/Autoloader/autoload.php';
// use Box\Spout\Writer\WriterFactory;
// use Box\Spout\Common\Type;	



$opcao = @$_GET['opcao'];
$tipo = fnLimpaCampo(@$_POST['tipo']);

if ($tipo == '') {

	$itens_por_pagina = @$_GET['itens_por_pagina'];
	$pagina = @$_GET['idPage'];
	$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
	$dat_ini = fnDataSql(@$_POST['DAT_INI']);
	$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
	if (empty(@$_REQUEST['LOG_OPTTRUE'])) {
		$log_opttrue = 'N';
	} else {
		$log_opttrue = @$_REQUEST['LOG_OPTTRUE'];
	}

	//fnEscreve($dat_ini);

	if ($cod_empresa != '' && $cod_empresa != 0) {
		$andEmpresa = "AND LO.COD_EMPRESA = $cod_empresa ";
	} else {
		$andEmpresa = "";
	}

	if ($log_opttrue == 'S') {
		$andOptout = "AND LO.DES_OPOUT = 1 ";
	} else {
		$andOptout = "";
	}

	if (isset($_GET['ido'])) {

		$log_optout = fnDecode(@$_GET['ido']);
		$cod_empresa_cli = fnDecode(@$_GET['id']);
		$cod_cliente = fnDecode(@$_GET['idc']);

		if ($log_optout == 1) {
			$optout = 0;
			$log_sms = 'S';
		} else {
			$optout = 1;
			$log_sms = 'N';
		}

		$sqlOptOutLista = "UPDATE LISTA_OPTOUT SET 
							   DES_OPOUT = $optout, 
							   COD_USUCADA = $_SESSION[SYS_COD_USUARIO], 
							   DAT_ALTERAC = NOW() 
							   WHERE COD_EMPRESA = $cod_empresa_cli 
							   AND COD_CLIENTE = $cod_cliente";
		$sqlOptOutCli = "UPDATE CLIENTES SET LOG_SMS = '$log_sms' WHERE COD_EMPRESA = $cod_empresa_cli AND COD_CLIENTE = $cod_cliente";

		mysqli_query($connAdm->connAdm(), $sqlOptOutLista);
		mysqli_query(connTemp($cod_empresa_cli, ''), $sqlOptOutCli);
	}
} else {
	$opcao = $tipo;
}

switch ($opcao) {

	case 'edit':

		$cod_cliente = fnLimpaCampoZero(@$_POST['pk']);
		$cod_empresa = fnLimpaCampoZero(@$_POST['codempresa']);
		$campo = fnLimpaCampo(@$_POST['name']);
		$valor = fnLimpaCampo(@$_POST['value']);

		if (strpos($valor, ',') !== false) {
			$valor = fnValorSql($valor);
		}

		// fnEscreve($cod_empresa);
		// fnEscreve($campo);
		// fnEscreve($valor);


		$sql = "UPDATE CLIENTES SET $campo='$valor' WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
		fnEscreve($sql);
		fnTestesql(connTemp($cod_empresa, ''), $sql);

		break;

	case 'exportar':

		// $nomeRel = @$_GET['nomeRel'];
		// $arquivo = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

		// $writer = WriterFactory::create(Type::CSV);
		// $writer->setFieldDelimiter(';');
		// $writer->openToFile($arquivo);

		// $sql = "";

		// $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);

		// $array = array();

		// while($row = mysqli_fetch_assoc($arrayQuery)){
		// 	  $newRow = array();

		// 	  $cont = 0;
		// 	  foreach ($row as $objeto) {

		// 		array_push($newRow, $objeto);

		// 		$cont++;
		// 	  }
		// 	$array[] = $newRow;
		// }

		// $arrayColumnsNames = array();
		// while($row = mysqli_fetch_field($arrayQuery))
		// {
		// 	array_push($arrayColumnsNames, $row->name);
		// }			

		// $writer->addRow($arrayColumnsNames);
		// $writer->addRows($array);

		// $writer->close();

		break;

	default:

		$sql = "SELECT LO.ID FROM LISTA_OPTOUT LO
					INNER JOIN EMPRESAS EM ON EM.COD_EMPRESA = LO.COD_EMPRESA
					WHERE LO.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					$andEmpresa";

		//fnEscreve($sql);

		$retorno = mysqli_query($connAdm->connAdm(), $sql);
		$total_itens_por_pagina = mysqli_num_rows($retorno);

		$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT LO.*, EM.NOM_FANTASI, US.NOM_USUARIO FROM LISTA_OPTOUT LO
						INNER JOIN EMPRESAS EM ON EM.COD_EMPRESA = LO.COD_EMPRESA
						LEFT JOIN USUARIOS US ON US.COD_USUARIO = LO.COD_USUCADA
						WHERE LO.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
						$andEmpresa
						ORDER BY DAT_CADASTR DESC
						LIMIT $inicio,$itens_por_pagina";

		// fnEscreve($sql);
		// fnTestesql(connTemp($cod_empresa,''),$sql);

		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

		$count = 0;

		while ($qrOptOut = mysqli_fetch_assoc($arrayQuery)) {

			$sqlCli = "SELECT NUM_CELULAR FROM CLIENTES WHERE COD_EMPRESA = $qrOptOut[COD_EMPRESA] AND COD_CLIENTE = $qrOptOut[COD_CLIENTE]";
			$qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($qrOptOut['COD_EMPRESA'], ''), $sqlCli));
			$count++;

			if ($qrOptOut['DES_OPOUT'] != 0) {
				$checkOptOut  = "checked";
			} else {
				$checkOptOut = "";
			}

?>
			<tr>
				<td><small><?= $qrOptOut['ID'] ?></small></td>
				<td><small><?= $qrOptOut['MSG'] ?></small></td>
				<td class="text-right sp_celphones">
					<a href="#" class="editable"
						data-type='text'
						data-title='Editar celular' data-pk="<?= $qrOptOut['COD_CLIENTE'] ?>"
						data-name="NUM_CELULAR"
						data-tipo="edit"
						data-codempresa="<?= $cod_empresa ?>"><?= $qrCli['NUM_CELULAR'] ?>

					</a>
				</td>
				<td class="text-center"><small><?= fnDataFull($qrOptOut['DAT_CADASTR']) ?></small></td>
				<td><small><?= $qrOptOut['NOM_FANTASI'] ?></small></td>
				<td><small><?= $qrOptOut['NOM_USUARIO'] ?></small></td>
				<td class="text-center"><small><?= fnDataFull($qrOptOut['DAT_ALTERAC']) ?></small></td>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" class="switch" onchange='toggleOptOut("<?= fnEncode($qrOptOut['COD_EMPRESA']) ?>","<?= fnEncode($qrOptOut['COD_CLIENTE']) ?>","<?= fnEncode($qrOptOut['DES_OPOUT']) ?>")' <?= $checkOptOut ?>>
						<span style="height: 25px;"></span>
					</label>
				</td>
			</tr>

			<script type="text/javascript">
				$('.editable').editable({
					emptytext: '(__) _____-____',
					url: './relatorios/ajxRelOptOutSms.php',
					ajaxOptions: {
						type: 'post'
					},
					params: function(params) {
						params.codempresa = $(this).data('codempresa');
						params.tipo = $(this).data('tipo');
						return params;
					},
					success: function(data) {
						reloadPage(current_page);
					}
				});
			</script>


<?php
		}

		break;
}

?>