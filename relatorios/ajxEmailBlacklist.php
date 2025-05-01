<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$cod_usuario = "";
$des_email = "";
$dat_ini = "";
$dat_fim = "";
$dias30 = "";
$hoje = "";
$ARRAY_VENDEDOR1 = "";
$ARRAY_VENDEDOR = "";
$andUsuario = "";
$andEmail = "";
$nomeRel = "";
$arquivo = "";
$writer = "";
$arrayQuery = [];
$array = [];
$row = "";
$newRow = "";
$objeto = "";
$NOM_ARRAY_NON_VENDEDOR = "";
$arrayColumnsNames = [];
$retorno = "";
$inicio = "";
$qrRet = "";
$usuario = "";
$sqlCount = "";
$arrayCount = [];
$cod_blklist = "";
$sqlExc = "";



use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

//echo fnDebug('true');

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_usuario = fnLimpaCampoZero(@$_POST['COD_USUARIO']);
$des_email = fnLimpaCampo(@$_POST['DES_EMAIL']);
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

$ARRAY_VENDEDOR1 = array(
	'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
	'cod_empresa' => $cod_empresa,
	'conntadm' => $connAdm->connAdm(),
	'IN' => 'N',
	'nomecampo' => '',
	'conntemp' => '',
	'SQLIN' => ""
);
$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

if ($cod_usuario != 0 && $cod_usuario != '') {
	$andUsuario = "AND COD_USUCADA = $cod_usuario";
} else {
	$andUsuario = "";
}

if ($des_email != '' && $des_email != 0) {
	$andEmail = "AND DES_EMAIL = '$des_email'";
} else {
	$andEmail = "";
}

switch ($opcao) {

	case 'exportar':

		$nomeRel = @$_GET['nomeRel'];
		$arquivo = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

		$writer = WriterFactory::create(Type::CSV);
		$writer->setFieldDelimiter(';');
		$writer->openToFile($arquivo);

		$sql = "SELECT DES_EMAIL AS EMAIL, 
						   COD_USUCADA AS USUARIO, 
						   DAT_CADASTR 
					FROM BLACKLIST_EMAIL
				    WHERE COD_EMPRESA = $cod_empresa
				    AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
				    $andUsuario
			    	$andEmail
			";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$array = array();

		while ($row = mysqli_fetch_assoc($arrayQuery)) {

			$newRow = array();

			$cont = 0;

			foreach ($row as $objeto) {

				if ($cont == 1) {

					$NOM_ARRAY_NON_VENDEDOR = (array_search($objeto, array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

					if ($objeto == 9999) {
						$objeto = "Integração";
					} else {
						$objeto = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'];
					}

					array_push($newRow, $objeto);
				} else {
					array_push($newRow, $objeto);
				}

				$cont++;
			}

			$array[] = $newRow;
		}

		$arrayColumnsNames = array();
		while ($row = mysqli_fetch_field($arrayQuery)) {
			array_push($arrayColumnsNames, $row->name);
		}

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();

		break;

	case 'paginar':

		$sql = "SELECT COD_BLKLIST FROM BLACKLIST_EMAIL
				    WHERE COD_EMPRESA = $cod_empresa
				    AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
				    $andUsuario
			    	$andEmail";

		//fnEscreve($sql);

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$total_itens_por_pagina = mysqli_num_rows($retorno);

		$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		$sql = "SELECT COD_BLKLIST, 
							   DES_EMAIL, 
							   COD_USUCADA, 
							   DAT_CADASTR,
							   (SELECT COUNT(CL.COD_CLIENTE) FROM CLIENTES CL 
							   	WHERE CL.DES_EMAILUS = BE.DES_EMAIL
							   	AND CL.COD_EMPRESA = $cod_empresa) AS QTD_EMAIL
						FROM BLACKLIST_EMAIL BE
					    WHERE COD_EMPRESA = $cod_empresa
					    AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
					    $andUsuario
				    	$andEmail
					    LIMIT $inicio,$itens_por_pagina";

		// fnEscreve($sql);
		//fnTestesql(connTemp($cod_empresa,''),$sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		$count = 0;

		while ($qrRet = mysqli_fetch_assoc($arrayQuery)) {

			$count++;
			$NOM_ARRAY_NON_VENDEDOR = (array_search($qrRet['COD_USUCADA'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

			if ($qrRet['COD_USUCADA'] == 9999) {
				$usuario = "Integração";
			} else {
				$usuario = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'];
			}

			// $sqlCount = "SELECT COD_CLIENTE FROM CLIENTES 
			// 			 WHERE COD_EMPRESA = $cod_empresa 
			// 			 AND DES_EMAILUS = $qrRet['DES_EMAIL']";

			// $arrayCount = mysqli_query(connTemp($cod_empresa,''),$sqlCount);


?>
			<tr>
				<td><small><?= $qrRet['DES_EMAIL'] ?></small></td>
				<td><small><?= $usuario ?></small></td>
				<td><small><?= fnDataShort($qrRet['DAT_CADASTR']) ?></small></td>
				<td><small><?= fnValor($qrRet['QTD_EMAIL'], 0) ?></small></td>
				<td class="text-center">
					<small>
						<div class="btn-group dropdown dropleft">
							<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								ações &nbsp;
								<span class="fas fa-caret-down"></span>
							</button>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
								<li class="text-info"><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1547) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idm=<?php echo fnEncode($qrRet['COD_BLKLIST']) ?>&idp=<?= $pagina ?>&pop=true" data-title="Clientes / <?= $qrRet['DES_EMAIL'] ?>"><i class='fal fa-list'></i> Lista </a></li>
								<?php if ($qrRet['QTD_EMAIL'] == 0) { ?>
									<li class="text-danger"><a href="javascript:void(0)" onclick='excluirEmail("<?= fnEncode($qrRet['COD_BLKLIST']) ?>","<?= $qrRet['DES_EMAIL'] ?>")'><i class='fal fa-trash-alt'></i> Excluir </a></li>
								<?php } ?>
								<!-- <li class="divider"></li> -->
								<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
							</ul>
						</div>
					</small>
				</td>
			</tr>


		<?php
		}
		?>
		<script>
			$("#PAGINA").val("<?= $pagina ?>");
		</script>
<?php

		break;

	case 'exc':

		$cod_blklist = fnDecode(@$_POST['COD_BLKLIST']);

		$sqlExc = "DELETE FROM BLACKLIST_EMAIL WHERE COD_BLKLIST = $cod_blklist";
		fnEscreve($sqlExc);
		fnTestesql(connTemp($cod_empresa, ''), $sqlExc);

		break;
}

?>