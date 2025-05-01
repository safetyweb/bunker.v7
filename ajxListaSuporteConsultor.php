<?php

include '_system/_functionsMain.php';

//echo fnDebug('true');

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];


if (isset($_POST['DAT_INI'])) {
	$dat_ini = fnDataSql($_POST['DAT_INI']);
} else {
	$dat_ini = "";
}

if (isset($_POST['DAT_FIM'])) {
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
} else {
	$dat_ini = "";
}

if (isset($_POST['DAT_INI_ENT'])) {
	$dat_ini_ent = fnDataSql($_POST['DAT_INI_ENT']);
} else {
	$dat_ini_ent = "";
}

if (isset($_POST['DAT_FIM_ENT'])) {
	$dat_fim_ent = fnDataSql($_POST['DAT_FIM_ENT']);
} else {
	$dat_fim_ent = "";
}

if (isset($_POST['COD_EXTERNO'])) {
	$cod_externo = $_POST['COD_EXTERNO'];
} else {
	$cod_externo = "";
}

if (isset($_POST['COD_EMPRESA'])) {
	$cod_empresa = $_POST['COD_EMPRESA'];
} else {
	$cod_empresa = "";
}

if (isset($_POST['NOM_CHAMADO'])) {
	$nom_chamado = $_POST['NOM_CHAMADO'];
} else {
	$nom_chamado = "";
}

if (isset($_POST['COD_CHAMADO'])) {
	$cod_chamado = $_POST['COD_CHAMADO'];
} else {
	$cod_chamado = "";
}

if (isset($_POST['COD_USUARIO'])) {
	$cod_usuario = $_POST['COD_USUARIO'];
} else {
	$cod_usuario = "";
}

if (isset($_POST['COD_TPSOLICITACAO'])) {
	$cod_tpsolicitacao = $_POST['COD_TPSOLICITACAO'];
} else {
	$cod_tpsolicitacao = "";
}

if (isset($_POST['COD_STATUS'])) {
	$cod_status = $_POST['COD_STATUS'];
} else {
	$cod_status = "";
}

if (isset($_POST['COD_INTEGRADORA'])) {
	$cod_integradora = $_POST['COD_INTEGRADORA'];
} else {
	$cod_integradora = "";
}

if (isset($_POST['COD_PLATAFORMA'])) {
	$cod_plataforma = $_POST['COD_PLATAFORMA'];
} else {
	$cod_plataforma = "";
}

if (isset($_POST['COD_VERSAOINTEGRA'])) {
	$cod_versaointegra = $_POST['COD_VERSAOINTEGRA'];
} else {
	$cod_versaointegra = "";
}

if (isset($_POST['COD_PRIORIDADE'])) {
	$cod_prioridade = $_POST['COD_PRIORIDADE'];
} else {
	$cod_prioridade = "";
}

if (isset($_POST['COD_USURES'])) {
	$cod_usures = $_POST['COD_USURES'];
} else {
	$cod_usures = "";
}

if (isset($_POST['COD_STATUS_EXC'])) {
	$Arr_COD_STATUS_EXC = $_POST['COD_STATUS_EXC'];
	$cod_status_exc = "";

	for ($i = 0; $i < count($Arr_COD_STATUS_EXC); $i++) {
		$cod_status_exc = $cod_status_exc . $Arr_COD_STATUS_EXC[$i] . ",";
	}

	$cod_status_exc = rtrim($cod_status_exc, ',');
} else {
	$cod_status_exc = "0";
}

if (isset($_POST['COD_TIPO_EXC'])) {
	$Arr_COD_TIPO_EXC = $_POST['COD_TIPO_EXC'];
	$cod_tipo_exc = "";

	for ($i = 0; $i < count($Arr_COD_TIPO_EXC); $i++) {
		$cod_tipo_exc = $cod_tipo_exc . $Arr_COD_TIPO_EXC[$i] . ",";
	}

	$cod_tipo_exc = rtrim($cod_tipo_exc, ',');
} else {
	$cod_tipo_exc = "0";
}

$hoje = fnFormatDate(date("Y-m-d"));


// fnEscreve($cod_status_exc);


if ($dat_ini == "") {
	$ANDdatIni = " ";
} else {
	$ANDdatIni = "AND DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";
}

if ($dat_ini_ent == date('Y-m-d')) {
	$ANDdatIniEnt = " ";
} else {
	$ANDdatIniEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') >= '$dat_ini_ent'";
}

if ($dat_fim_ent == "") {
	$ANDdatFimEnt = " ";
} else {
	$ANDdatFimEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') <= '$dat_fim_ent'";
}

if ($cod_externo == "") {
	$ANDcodExterno = " ";
} else {
	$ANDcodExterno = "AND SC.COD_EXTERNO LIKE '%$cod_externo%' ";
}

if ($cod_chamado == "") {
	$ANDcodChamado = " ";
} else {
	$ANDcodChamado = "AND SC.COD_CHAMADO = $cod_chamado ";
}

if ($cod_empresa == "") {
	$ANDcodEmpresa = " ";
} else {
	$ANDcodEmpresa = "AND SC.COD_EMPRESA = $cod_empresa ";
}

if ($nom_chamado == "") {
	$ANDnomChamado = " ";
} else {
	$ANDnomChamado = "AND SC.NOM_CHAMADO LIKE '%$nom_chamado%' ";
}

if ($cod_tpsolicitacao == "") {
	$ANDcodTipo = " ";
} else {
	$ANDcodTipo = "AND SC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";
}

if ($cod_status == "") {
	$ANDcodStatus = "";
} else {
	$ANDcodStatus = "AND SC.COD_STATUS = $cod_status ";
}

if ($cod_status_exc == "0") {
	$ANDcodStatusExc = "";
} else {
	$ANDcodStatusExc = "AND SC.COD_STATUS NOT IN($cod_status_exc) ";
}

if ($cod_tipo_exc == "0") {
	$ANDcodTipoExc = "";
} else {
	$ANDcodTipoExc = "AND SC.COD_TPSOLICITACAO NOT IN($cod_tipo_exc) ";
}

if ($cod_integradora == "") {
	$ANDcodIntegradora = " ";
} else {
	$ANDcodIntegradora = "AND SC.COD_INTEGRADORA = $cod_integradora ";
}

if ($cod_plataforma == "") {
	$ANDcodPlataforma = " ";
} else {
	$ANDcodPlataforma = "AND SC.COD_PLATAFORMA = $cod_plataforma ";
}

if ($cod_versaointegra == "") {
	$ANDcodVersaointegra = " ";
} else {
	$ANDcodStatus = "AND SC.COD_VERSAOINTEGRA = $cod_versaointegra ";
}

if ($cod_prioridade == "") {
	$ANDcodPrioridade = " ";
} else {
	$ANDcodPrioridade = "AND SC.COD_PRIORIDADE = $cod_prioridade ";
}

if ($cod_usuario == "") {
	$ANDcodUsuario = " ";
} else {
	$ANDcodUsuario = "AND SC.COD_USUARIO = $cod_usuario ";
}



if ($cod_usuario != "" && $cod_usures != "" && $cod_usuario == $cod_usures) {
	$ANDcod_usures = "AND (SC.COD_USUARIO = $cod_usuario OR SC.COD_USURES = $cod_usures OR SC.COD_CONSULTORES IN($cod_usuario) OR SC.COD_USUARIOS_ENV IN($cod_usuario)) ";
	$ANDcodUsuario = "";
} else {
	$ANDcod_usures = "";
}




$sqlCount = "SELECT COUNT(*) AS CONTADOR FROM SAC_CHAMADOS SC 
						WHERE SC.NOM_CHAMADO LIKE '%$nom_chamado%'
		  				$ANDcodUsuario
		  				$ANDcod_usures
		  				$ANDcodExterno
		  				$ANDcodChamado
		  				$ANDcodEmpresa
		  				$ANDnomChamado
		  				$ANDcodStatus
		  				$ANDcodTipo
		  				$ANDcodIntegradora
		  				$ANDcodPlataforma
		  				$ANDcodVersaointegra
		  				$ANDcodPrioridade
		  				$ANDcodStatusExc
		  				$ANDcodTipoExc
		  				$ANDdatIniEnt
		  				$ANDdatFimEnt												  				
						ORDER BY SC.COD_PRIORIDADE ASC
						";
// fnEscreve($sqlCount);

$retorno = mysqli_query($connAdmSAC->connAdm(), $sqlCount);
$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

//variavel para calcular o início da visualização com base na página atual
$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

$sqlSac = "SELECT SC.COD_CHAMADO, SC.COD_EMPRESA, SC.NOM_CHAMADO, SC.COD_EXTERNO, 
						SC.DAT_CADASTR, SC.DAT_CHAMADO, SC.DAT_ENTREGA, SC.DAT_PROXINT, SC.DES_PREVISAO, SC.COD_USUARIO,
						SC.COD_USURES, SC.LOG_ADM, SP.DES_PLATAFORMA, ST.DES_TPSOLICITACAO, 
						SV.DES_VERSAOINTEGRA, SPR.DES_PRIORIDADE, SPR.DES_COR AS COR_PRIORIDADE, SPR.DES_ICONE AS ICO_PRIORIDADE,
						SS.ABV_STATUS, SS.DES_COR AS COR_STATUS, SS.DES_ICONE AS ICO_STATUS,
						(SELECT MAX(SCM.DAT_CADASTRO) FROM SAC_COMENTARIO SCM WHERE SCM.COD_CHAMADO = SC.COD_CHAMADO) AS DAT_INTERAC
						FROM SAC_CHAMADOS SC 
						LEFT JOIN SAC_PLATAFORMA SP ON SP.COD_PLATAFORMA=SC.COD_PLATAFORMA
						LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
						LEFT JOIN SAC_VERSAOINTEGRA SV ON SV.COD_VERSAOINTEGRA=SC.COD_VERSAOINTEGRA
						LEFT JOIN SAC_PRIORIDADE SPR ON SPR.COD_PRIORIDADE=SC.COD_PRIORIDADE
						LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
						WHERE SC.NOM_CHAMADO LIKE '%$nom_chamado%'
						$ANDcodUsuario
						$ANDcod_usures
						$ANDcodExterno
						$ANDcodChamado
						$ANDcodEmpresa
						$ANDnomChamado
						$ANDcodStatus
						$ANDcodTipo
						$ANDcodIntegradora
						$ANDcodPlataforma
						$ANDcodVersaointegra
						$ANDcodPrioridade
						$ANDcodStatusExc
		  				$ANDcodTipoExc
						$ANDdatIniEnt
						$ANDdatFimEnt
						ORDER BY SC.COD_CHAMADO DESC limit $inicio,$itens_por_pagina
						";
// fnEscreve($sqlSac);

$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(), $sqlSac);

$count = 0;
$adm = "";
$entrega = "";
while ($qrSac = mysqli_fetch_assoc($arrayQuerySac)) {

	if ($qrSac['LOG_ADM'] == 'S') {
		$adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
	} else {
		$adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
	}

	$count++;

	$sqlEmpresa = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $qrSac[COD_EMPRESA]";
	$qrNomEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmpresa));

	$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USUARIO]) AS NOM_SOLICITANTE,
										(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
	$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));
	//fnEscreve($sqlUsuarios);										  

	if ($qrSac['DAT_ENTREGA'] == "1969-12-31") {
		$entrega = "";
	} else {
		$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
		if (fnDatasql($entrega) < fnDatasql($hoje)) {
			$entrega = "<span class='text-danger'><b>" . fnDataShort($qrSac['DAT_ENTREGA']) . "</b></span>";
		}
	}

	if ($qrSac['DAT_PROXINT'] == "1969-12-31") {
		$proxInt = "";
	} else {
		$proxInt = fnDataShort($qrSac['DAT_PROXINT']);
		if (fnDatasql($proxInt) < fnDatasql($hoje)) {
			$proxInt = "<span class='text-danger'><b>" . fnDataShort($qrSac['DAT_PROXINT']) . "</b></span>";
		}
	}

	if ($qrSac['DAT_INTERAC'] != "") {
		if (fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)) {
			$atualizado = "<b>Hoje</b>";
			$f = "f17";
		} else if (fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))) {
			$atualizado = "<b>Ontem</b>";
			$f = "f17";
		} else {
			$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
			$f = "f14";
		}
	} else {
		$atualizado = "";
	}

	if (isset($qrSac['COD_STATUS']) && $qrSac['COD_STATUS'] == 12) {

		$difference = fnValor((abs(strtotime(date("Y-m-d H:i:s")) - strtotime($qrSac['DAT_CADASTR'])) / 3600), 0);

		if ($difference <= 12) {
			$corDiff = "label-success";
		} else if ($difference > 12 && $difference <= 24) {
			$corDiff = "label-warning";
		} else {
			$corDiff = "label-danger";
		}

		$badgeDias = "<span class='label-as-badge text-center " . $corDiff . "'><span class='txtBadge'>" . $difference . "</span></span>";
	} else {
		$badgeDias = "";
	}

	//$diff_dias = fnDateDif($qrSac['DAT_CADASTR'],Date("Y-m-d"));
	// fnEscreve(fnDatasql($qrSac['DAT_INTERAC']));
?>

	<tr>
		<td class="text-center">
			<small>
				<a href="action.php?mod=<?= fnEncode(1462); ?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']); ?>&idC=<?php echo fnEncode($qrSac['COD_CHAMADO']); ?>" target="_blank"><?= $qrSac['COD_CHAMADO'] ?>&nbsp;
					<span class="fa fa-external-link-square"></span>
				</a>
			</small>
		</td>
		<td><small><?= $adm ?> &nbsp; <?= $qrNomEmp['NOM_FANTASI'] ?></small></td>
		<td><small><?= $qrSac['NOM_CHAMADO'] ?></small></td>
		<td><small><?= $qrNomUsu['NOM_SOLICITANTE'] ?></small></td>
		<td><small><?= fnDataShort($qrSac['DAT_CADASTR']) ?></small></td>
		<td><small><?= $qrSac['DES_TPSOLICITACAO'] ?></small></td>
		<td><small><?= $qrNomUsu['NOM_RESPONSAVEL'] ?></small></td>

		<td class="text-center">
			<small>
				<p class="label" style="background-color: <?php echo $qrSac['COR_PRIORIDADE'] ?>">
					<span class="<?php echo $qrSac['ICO_PRIORIDADE']; ?>" style="color: #FFF;"></span>
					<!-- &nbsp; <?php echo $qrSac['DES_PRIORIDADE']; ?> -->
				</p>
			</small>
		</td>

		<td class="text-center">
			<small>
				<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>">
					<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
					&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
				</p>
				&nbsp;
				<?= $badgeDias ?>
			</small>

			<!-- <div><?= $badgeDias ?></div> -->
		</td>

		<td class="text-center f14"><small><?= $proxInt ?></small></td>
		<td class="text-center <?= $f ?>"><small><?= $atualizado ?></small></td>
		<td class="text-center f14"><small><?= $entrega ?></small></td>

	</tr>
<?php
}


?>
<script type="text/javascript">
	$(function() {
		$('[data-toggle="tooltip"]').tooltip();
	});
</script>