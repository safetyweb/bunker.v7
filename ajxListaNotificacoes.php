<?php

include '_system/_functionsMain.php';

$tip_origem = fnLimpaCampo(fnDecode($_REQUEST['TIP_ORIGEM']));
$cod_identificacao = fnLimpaCampoZero(fnDecode($_REQUEST['COD_IDENTIFICACAO']));
$inicio = $_REQUEST['INICIO'];

if ($tip_origem != "") {

	if ($tip_origem != "ALL") {
		$andOrigem = "AND TIP_ORIGEM = '$tip_origem' 
						  AND COD_IDENTIFICACAO = $cod_identificacao";
	} else {
		$andOrigem = "";
	}

	$sqlLeitura = "UPDATE NOTIFICACOES SET
					   DAT_LEITURA = NOW()
					   WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]
					   $andOrigem
					   AND DAT_LEITURA IS NULL";

	// fnescreve($sqlLeitura);

	mysqli_query($connAdm->connAdm(), $sqlLeitura);
}

$sql = "SELECT N1.* FROM NOTIFICACOES N1
			JOIN (
				  SELECT MAX(COD_NOTIFICACAO) AS COD_NOTIFICACAO 
				  FROM NOTIFICACOES 
				  WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO] 
				  GROUP BY TIP_ORIGEM, COD_IDENTIFICACAO) N2
			ON N1.COD_NOTIFICACAO = N2.COD_NOTIFICACAO
			ORDER BY DAT_LEITURA IS NOT NULL, DAT_LEITURA DESC
			LIMIT $inicio, 10
			";

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

$count = 0;
while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

	$count++;

	if ($qrBuscaModulos['DAT_LEITURA'] != '') {
		$status = "";
	} else {
		$status = "<span class='fas fa-circle text-danger'></span>&nbsp;";
	}

	switch ($qrBuscaModulos['TIP_ORIGEM']) {

		case 'SAC':

			$sqlPerfil = "SELECT COD_EMPRESA FROM USUARIOS WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
			$qrPerfil = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlPerfil));

			if ($qrPerfil['COD_EMPRESA'] == 2) {
				$mod = 1285;
			} else if ($qrPerfil['COD_EMPRESA'] == 3) {
				$mod = 1462;
			} else {
				$mod = 1288;
			}

			$pref = "idC";

			break;

		default:

			break;
	}

?>
	<tr>
		<td class="text-center"><small><small><?= $status ?></small></small></td>
		<td><small><?= $qrBuscaModulos['DES_NOTIFICA'] ?></small></td>
		<td><small><?= $qrBuscaModulos['DES_MOTIVO'] ?></small></td>
		<td><small><?= $qrBuscaModulos['TIP_ORIGEM'] ?></small></td>
		<td class="text-center"><small><?= fnDataFull($qrBuscaModulos['DAT_CADASTR']) ?></small></td>
		<td class="text-center">
			<small>
				<div class="btn-group dropdown dropleft">
					<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						ações &nbsp;
						<span class="fas fa-caret-down"></span>
					</button>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
						<li><a href='javascript:void(0)' onclick='leituraNotifica("<?= fnEncode($qrBuscaModulos["TIP_ORIGEM"]) ?>","<?= fnEncode($qrBuscaModulos["COD_IDENTIFICACAO"]) ?>",0)'>Marcar como lida </a></li>
						<li><a onclick='leituraNotifica("<?= fnEncode($qrBuscaModulos["TIP_ORIGEM"]) ?>","<?= fnEncode($qrBuscaModulos["COD_IDENTIFICACAO"]) ?>",0)' href="action.php?mod=<?= fnEncode($mod) ?>&id=<?= fnEncode($qrBuscaModulos["COD_EMPRESA"]) ?>&<?= $pref ?>=<?= fnEncode($qrBuscaModulos["COD_IDENTIFICACAO"]) ?>" target="_blank">Acessar </a></li>
					</ul>
				</div>
			</small>
		</td>
	</tr>
<?php

}

?>