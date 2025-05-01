<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$primeiroDia = "";
$dataUltimoDia = "";
$msgRetorno = "";
$msgTipo = "";
$cod_sistemas = "";
$cod_segmentos = "";
$sistema = "";
$segmento = "";
$hHabilitado = "";
$hashForm = "";
$hoje = "";
$dias30 = "";
$valorDataPrimeiraVenda = "";
$dat_ini = "";
$dat_fim = "";
$qtd_univendUsu = 0;
$lojas = "";
$lojasAut = "";
$lojasSelecionadas = "";
$arrayQuery = [];
$qrUsu = "";
$TOTAL_SISTEMAS = "";
$andSistema = "";
$andSegment = "";
$sqlBuscaEmpresa = "";
$queryEmpresa = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$ARRAY_SISTEMA1 = [];
$ARRAY_SISTEMA = [];
$qrListaEmpresas = "";
$tem_sistema = "";
$sistemas = "";
$des_sistema = "";
$i = "";
$NOM_ARRAY_SISTEMAS = [];
$TOTAL_EMPRESAS = "";
$TOTAL_SALDO_SMS = "";
$TOTAL_SALDO_EMAIL = "";
$QTD_SISTEMAS = "";


$hashLocal = mt_rand();

//$primeiroDia =fndataSql(date("Y-m-01"));
//$dataUltimoDia = fndataSql(date("t", mktime(0,0,0, date('m'),'01', date('Y'))) . '-' . date('m/Y'));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request'] = $request;

		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);

		//INICIALIZANDO VARIAVEIS
		$cod_sistemas = "";
		$cod_segmentos = "";

		foreach ($_REQUEST['COD_SISTEMA'] as $sistema) {
			//concatenando variaveis separadas por vírgula
			$cod_sistemas .= $sistema . ",";
		}

		// removendo última vírgula da variável
		$cod_sistemas = rtrim($cod_sistemas, ",");

		foreach ($_REQUEST['COD_SEGMENT'] as $segmento) {
			$cod_segmentos .= $segmento . ",";
		}

		$cod_segmentos = rtrim($cod_segmentos, ",");

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {
		}
	}
}


//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate($valorDataPrimeiraVenda['primeira_venda']);
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//fnMostraForm();
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojas);
//fnEscreve($lojasAut);
//fnEscreve($cod_univend);
//fnEscreve($lojasSelecionadas);

?>

<style>
	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>
				<?php
				include "backReport.php";
				include "atalhosPortlet.php";
				?>
			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<div class="push30"></div>

				<div class="login-form">

					<form name="formulario" id="formulario" method="POST" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Segmento</label>
										<select data-placeholder="Selecione o Segmento" name="COD_SEGMENT[]" id="COD_SEGMENT" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
											<?php
											$sql = "SELECT COD_SEGMENT,NOM_SEGMENT FROM SEGMENTOMARKA";
											fnEscreve($sql);
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrUsu = mysqli_fetch_assoc($arrayQuery)) {
												echo "
													<option value='" . $qrUsu['COD_SEGMENT'] . "'>" . $qrUsu['NOM_SEGMENT'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
										<?php //fnEscreve($arrayQuery); 
										?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Sistema</label>
										<select data-placeholder="Selecione o Sistema" name="COD_SISTEMA[]" id="COD_SISTEMA" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
											<?php

											$sql = "SELECT COD_SISTEMA,DES_SISTEMA FROM SISTEMAS";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrUsu = mysqli_fetch_assoc($arrayQuery)) {
												echo "
													<option value='" . $qrUsu['COD_SISTEMA'] . "'>" . $qrUsu['DES_SISTEMA'] . "</option> 
												";

												//$TOTAL_SISTEMAS = mysqli_num_rows($arrayQuery);
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
										<?php //fnEscreve($arrayQuery); 
										?>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>
							</div>

						</fieldset>

						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>
				</div>
			</div>
		</div>

		<div class="push20"></div>

		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="login-form">

					<div class="push30"></div>

					<div class="no-more-tables">

						<div class="form-group text-center col-lg-12">

							<table class="table table-bordered table-striped table-hover tableSorter buscavel">
								<thead>
									<tr>
										<th>Código</th>
										<th>Empresa</th>
										<th>Servidor</th>
										<th>Segmento</th>
										<th>Sistema</th>
										<th class="text-center">Saldo SMS</th>
										<th class="text-center">Saldo e-Mail</th>
									</tr>
								</thead>
								<tbody id="relatorioEmpresas">

									<?php

									//Filtros

									if ($cod_sistemas != '' && $cod_sistemas != 0) {
										$andSistema = "AND A.COD_SISTEMAS IN($cod_sistemas)";
									} else {
										$andSistema = "";
									}
									if ($cod_segmentos != '' && $cod_segmentos != 0) {
										$andSegment = "AND S.COD_SEGMENT IN($cod_segmentos)";
									} else {
										$andSegment = "";
									}

									//busca dados da empresa		
									//$sqlBuscaEmpresa = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas";
									//fnEscreve($sqlBuscaEmpresa);
									//$queryEmpresa = mysqli_query($connAdm->connAdm(), $sqlBuscaEmpresa);

									/*while ($qrBuscaEmpresa = mysqli_fetch_assoc($queryEmpresa)) {

										$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
										$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
										$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
										*/
									//echo "<pre>";
									if ($_SESSION["SYS_COD_MASTER"] == "2") {
										$sql = "SELECT 
														SUM(case when PM.TIP_LANCAMENTO ='C' AND CC.COD_CANALCOM=13 then PM.QTD_PRODUTO ELSE 0 END) - SUM(case when PM.TIP_LANCAMENTO ='D'  AND CC.COD_CANALCOM=13 then PM.QTD_PRODUTO ELSE 0 END) QTD_PRODUTO_EMAIL,
														SUM(case when PM.TIP_LANCAMENTO ='C' AND CC.COD_CANALCOM=21 then PM.QTD_PRODUTO ELSE 0 END) - SUM(case when PM.TIP_LANCAMENTO ='D'  AND CC.COD_CANALCOM=21 then PM.QTD_PRODUTO ELSE 0 END) QTD_PRODUTO_SMS,
														SUM(case when PM.TIP_LANCAMENTO ='C' AND CC.COD_CANALCOM=20 then PM.QTD_PRODUTO ELSE 0 END) - SUM(case when PM.TIP_LANCAMENTO ='D'  AND CC.COD_CANALCOM=20 then PM.QTD_PRODUTO ELSE 0 END) QTD_PRODUTO_WHATSAPP,
														GROUP_CONCAT( distinct PM.TIP_LANCAMENTO) TIP_LANCAMENTO, 
														GROUP_CONCAT( distinct CC.DES_CANALCOM) DES_CANALCOM,
														GROUP_CONCAT( distinct CC.COD_CANALCOM) COD_CANALCOM,
														S.NOM_SEGMENT,
														COUNT(DISTINCT A.COD_SISTEMAS) QTD_SISTEMAS,
														A.COD_SISTEMAS,
														A.COD_EMPRESA, 
														A.NOM_FANTASI,
														right (DT.IP, 3) IP
														FROM PEDIDO_MARKA PM
														INNER JOIN PRODUTO_MARKA PRM ON PRM.COD_PRODUTO = PM.COD_PRODUTO
														INNER JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = PRM.COD_CANALCOM
														INNER JOIN EMPRESAS A ON A.COD_EMPRESA = PM.COD_EMPRESA
														INNER JOIN SEGMENTOMARKA S ON S.COD_SEGMENT = A.COD_SEGMENT
														INNER JOIN tab_database DT ON DT.COD_EMPRESA=PM.COD_EMPRESA														
														WHERE PM.COD_ORCAMENTO > 0
														-- AND PM.COD_EMPRESA = $cod_empresa
														$andSistema
														$andSegment
														GROUP BY PM.COD_EMPRESA";
									} else {
										$sql = "SELECT 
														SUM(case when PM.TIP_LANCAMENTO ='C' AND CC.COD_CANALCOM=13 then PM.QTD_PRODUTO ELSE 0 END) - SUM(case when PM.TIP_LANCAMENTO ='D'  AND CC.COD_CANALCOM=13 then PM.QTD_PRODUTO ELSE 0 END) QTD_PRODUTO_EMAIL,
														SUM(case when PM.TIP_LANCAMENTO ='C' AND CC.COD_CANALCOM=21 then PM.QTD_PRODUTO ELSE 0 END) - SUM(case when PM.TIP_LANCAMENTO ='D'  AND CC.COD_CANALCOM=21 then PM.QTD_PRODUTO ELSE 0 END) QTD_PRODUTO_SMS,
														SUM(case when PM.TIP_LANCAMENTO ='C' AND CC.COD_CANALCOM=20 then PM.QTD_PRODUTO ELSE 0 END) - SUM(case when PM.TIP_LANCAMENTO ='D'  AND CC.COD_CANALCOM=20 then PM.QTD_PRODUTO ELSE 0 END) QTD_PRODUTO_WHATSAPP,
														GROUP_CONCAT( distinct PM.TIP_LANCAMENTO) TIP_LANCAMENTO, 
														GROUP_CONCAT( distinct CC.DES_CANALCOM) DES_CANALCOM,
														GROUP_CONCAT( distinct CC.COD_CANALCOM) COD_CANALCOM,
														S.NOM_SEGMENT,
														COUNT(DISTINCT A.COD_SISTEMAS) QTD_SISTEMAS,
														A.COD_SISTEMAS,
														A.COD_EMPRESA, 
														A.NOM_FANTASI,
														right (DT.IP, 3) IP
														FROM PEDIDO_MARKA PM
														INNER JOIN PRODUTO_MARKA PRM ON PRM.COD_PRODUTO = PM.COD_PRODUTO
														INNER JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = PRM.COD_CANALCOM
														INNER JOIN EMPRESAS A ON A.COD_EMPRESA = PM.COD_EMPRESA
														INNER JOIN SEGMENTOMARKA S ON S.COD_SEGMENT = A.COD_SEGMENT
														INNER JOIN tab_database DT ON DT.COD_EMPRESA=PM.COD_EMPRESA														
														WHERE PM.COD_ORCAMENTO > 0
														AND PM.COD_EMPRESA IN (" . $_SESSION["SYS_COD_MULTEMP"] . ")
														$andSistema
														$andSegment
														GROUP BY PM.COD_EMPRESA";
									}

									//echo($_SESSION['SYS_COD_MASTER']);
									//fnescreve($sql) ;
									//fnescreve($_SESSION["SYS_COD_MULTEMP"]);

									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
									//fnTestesql($connAdm->connAdm(),$sql);

									$ARRAY_SISTEMA1 = array(
										'sql' => "SELECT COD_SISTEMA,DES_SISTEMA FROM SISTEMAS",
										'cod_empresa' => 0,
										'conntadm' => $connAdm->connAdm(),
										'IN' => 'N',
										'nomecampo' => '',
										'conntemp' => '',
										'SQLIN' => ""
									);
									$ARRAY_SISTEMA = fnUnivend($ARRAY_SISTEMA1);
									$count = 0;

									while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

										$qtdSegmento[$qrListaEmpresas['NOM_SEGMENT']] = $qrListaEmpresas['NOM_SEGMENT'];


										if (!empty($qrListaEmpresas['COD_SISTEMAS'])) {

											$tem_sistema = "tem";

											$sistemas = explode(',', $qrListaEmpresas['COD_SISTEMAS']);

											$des_sistema = "";

											for ($i = 0; $i < count($sistemas); $i++) {

												$NOM_ARRAY_SISTEMAS = (array_search($sistemas[$i], array_column($ARRAY_SISTEMA, 'COD_SISTEMA')));
												$des_sistema .= $ARRAY_SISTEMA[$NOM_ARRAY_SISTEMAS]['DES_SISTEMA'] . ", ";
											}

											$des_sistema = rtrim(trim($des_sistema), ",");
										} else {
											$tem_sistema = "nao";
										}

										//echo "<pre>";
										//print_r($qrListaEmpresas);
										//echo "</pre>";
									?>

										<tr>
											<td><?= $qrListaEmpresas['COD_EMPRESA'] ?></td>
											<td><a href="action.do?mod=<?php echo fnEncode(1503); ?>&id=<?php echo fnEncode($qrListaEmpresas['COD_EMPRESA']); ?>" target="_blank"><?= $qrListaEmpresas['NOM_FANTASI'] ?></a></td>
											<td><?= $qrListaEmpresas['IP'] ?></td>
											<td><?= $qrListaEmpresas['NOM_SEGMENT'] ?></td>
											<td><?= $des_sistema ?></td>
											<td class="text-center"><?= $qrListaEmpresas['QTD_PRODUTO_SMS'] ?></td>
											<td class="text-center"><?= $qrListaEmpresas['QTD_PRODUTO_EMAIL'] ?></td>
										</tr>

									<?php
										if ($count == mysqli_num_rows($arrayQuery) - 1) {
											$TOTAL_SEGMENT = count($qtdSegmento);
										}
										$count++;

										$TOTAL_EMPRESAS = mysqli_num_rows($arrayQuery);
										$TOTAL_SALDO_SMS += $qrListaEmpresas['QTD_PRODUTO_SMS'];
										$TOTAL_SALDO_EMAIL += $qrListaEmpresas['QTD_PRODUTO_EMAIL'];
										$QTD_SISTEMAS = $qrListaEmpresas['QTD_SISTEMAS'];
									}


									?>
									<tr>
										<td></td>
										<td><b><small><?= $TOTAL_EMPRESAS ?></small></b></td>
										<td></td>
										<td><b><small><?= $TOTAL_SEGMENT ?></small></b></td>
										<td><b><small></small></b></td>
										<td class="text-center"><b><small><?= $TOTAL_SALDO_SMS ?></small></b></td>
										<td class="text-center"><b><small><?= $TOTAL_SALDO_EMAIL ?></small></b></td>
									</tr>
								</tbody>

							</table>

						</div>

					</div>
				</div>

				<div class="push50"></div>

			</div>
		</div>
	</div>
</div>

<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	$(function() {

		carregaComboMulti("formulario", "COD_SISTEMA", "<?= $cod_sistemas ?>");
		carregaComboMulti("formulario", "COD_SEGMENT", "<?= $cod_segmentos ?>");

	});

	function carregaComboMulti(idForm, idCombo, cod) {

		var sistemasUni = cod;

		if (cod != "") {

			$("#" + idForm + " #" + idCombo).val('').trigger("chosen:updated");

			// explode a variavel e transforma em json
			var sistemasUniArr = sistemasUni.split(',');

			// looping no json pra pegar cada cod individualmente
			for (var i = 0; i < sistemasUniArr.length; i++) {
				//atribui cada codigo à combo
				$("#" + idForm + " #" + idCombo + " option[value=" + Number(sistemasUniArr[i]) + "]").prop("selected", "true");
			}

			//ATUALIZA O PLUGIN - CHOSEN
			$("#" + idForm + " #" + idCombo).trigger("chosen:updated");

		} else {

			$("#" + idForm + " #" + idCombo).val('').trigger("chosen:updated");

		}

	}
</script>