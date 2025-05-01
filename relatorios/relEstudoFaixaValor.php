<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$cod_persona = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$val_ini = "";
$val_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$Arr_COD_PERSONA = "";
$i = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_conveni = "";
$qrBuscaTemplate = "";
$nom_conveni = "";
$cod_objeto = "";
$popUp = "";
$qrListaPersonas = "";
$lojasSelecionadas = "";
$val_total = 0;
$qrBuscaModulos = "";
$qtdRegistros = 0;
$mediaValcompra = "";
$mediaQtdCompra = "";
$valorTkt = "";


$hashLocal = mt_rand();
$cod_persona = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$dat_ini = fnDataSql(@$_REQUEST['DAT_INI']);
		$dat_fim = fnDataSql(@$_REQUEST['DAT_FIM']);
		$val_ini = fnValorSql(@$_REQUEST['VAL_INI']);
		$val_fim = fnValorSql(@$_REQUEST['VAL_FIM']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if (isset($_POST['COD_PERSONA'])) {
			$cod_persona = "";
			$Arr_COD_PERSONA = @$_POST['COD_PERSONA'];

			for ($i = 0; $i < count($Arr_COD_PERSONA); $i++) {
				$cod_persona = $cod_persona . $Arr_COD_PERSONA[$i] . ",";
			}

			$cod_persona = ltrim(rtrim($cod_persona, ','), ',');
		} else {
			$cod_persona = "0";
		}

		if ($opcao != '' && $opcao != 0) {

			//mensagem de retorno
			switch ($opcao) {
			}
			$msgTipo = 'alert-success';
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$nom_empresa = "";
}

if (isset($_GET['idC'])) {
	if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idC'])))) {

		//busca dados do convênio
		$cod_conveni = fnDecode(@$_GET['idC']);
		//$sql = "SELECT NOM_CONVENI, COD_OBJETO FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
		$sql = "SELECT A.NOM_CONVENI,B.COD_OBJETO FROM CONVENIO A,LICITACAO_OBJETO B
					WHERE A.COD_CONVENI=B.COD_CONVENI AND
						  A.COD_CONVENI = " . $cod_conveni;

		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaTemplate)) {
			$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
			$cod_objeto = $qrBuscaTemplate['COD_OBJETO'];
		}
	}
}
//busca revendas do usuário
include "unidadesAutorizadas.php";
//fnMostraForm();
?>

<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<div class="push30"></div>

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-sm-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Persona</label>

										<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA[]" id="COD_PERSONA" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1" required>
											<option value=""></option>
											<?php

											$sql = "SELECT * from persona where cod_empresa = $cod_empresa and LOG_ATIVO = 'S' AND COD_EXCLUSA = 0 order by DES_PERSONA  ";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

												echo "
														<option value='" . $qrListaPersonas['COD_PERSONA'] . "'>" . ucfirst($qrListaPersonas['DES_PERSONA']) . "</option> 
													";
											}

											?>
										</select>

									</div>

								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-2">

									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Inicial</label>
										<div class="input-group">
											<input type="text" class="form-control input-sm money" name="VAL_INI" id="VAL_INI" value="<?= $val_ini ?>" data-mask="##0" data-mask-reverse="true" maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Final</label>
										<div class="input-group">
											<input type="text" class="form-control input-sm money" name="VAL_FIM" id="VAL_FIM" value="<?= $val_fim ?>" data-mask="##0" data-mask-reverse="true" maxlength="11">
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" style="width:auto" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>
							</div>

						</fieldset>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
					</form>


				</div>
				</div>
			</div>
			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="col-lg-12">

							<div class="no-more-tables table-responsive">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover tablesorter">
										<thead>
											<tr>
												<!--<th class="{sorter:false}" width="40"></th>-->
												<th>Unidade</th>
												<th>Período</th>
												<th class="text-center{sorter:false}">Faturamento</th>
												<th class="text-center{sorter:false}">Qt.Transações</th>
												<th class="text-center{sorter:false}">Ticket Médio</th>
											</tr>
										</thead>

										<tbody>

											<?php

											include "filtroGrupoLojas.php";

											$sql = "SELECT vendas.COD_UNIVEND, 
													   unidadevenda.NOM_FANTASI,								 
												DATEDIFF ('$dat_fim', '$dat_ini') periodo, 
												COUNT(*) qtd_compras, SUM(val_totprodu) val_compras, SUM(val_totprodu)/ COUNT(*) tkt_medio
												FROM vendas, clientes, unidadevenda
												WHERE vendas.COD_CLIENTE=clientes.COD_CLIENTE AND vendas.cod_cliente IN(
												SELECT cod_cliente
												FROM personaclassifica
												WHERE COD_PERSONA= $cod_persona AND cod_empresa=$cod_empresa) AND dat_cadastr_ws BETWEEN '$dat_ini' AND '$dat_fim' AND 
												vendas.COD_UNIVEND IN($lojasSelecionadas) AND 
												unidadevenda.COD_UNIVEND=vendas.COD_UNIVEND AND
												val_totprodu >=$val_ini AND val_totprodu <= $val_fim
												GROUP BY vendas.COD_UNIVEND											
												ORDER BY SUM(val_totprodu)";

											//fnEscreve($sql);


											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											$val_total = 0;
											while (@$qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<tr>
													<td><?= $qrBuscaModulos['NOM_FANTASI'] ?></td>
													<td><?= $qrBuscaModulos['periodo'] ?>&nbsp;Dias</td>
													<td width="10%"><b><small>R$ </small>
															<div style="display: inline;" id="total_col4a_<?php echo $qrBuscaModulos['val_compras']; ?>"><?php echo fnValor($qrBuscaModulos['val_compras'], 2); ?>
														</b>
							</div>
							</th>
							<td width="19%"><b><small></small>
									<div style="display: inline;" id="total_col4a_<?php echo $qrBuscaModulos['qtd_compras']; ?>"><?php echo $qrBuscaModulos['qtd_compras']; ?>
								</b>
						</div>
						</th>
						<td width="19%"><b><small>R$ </small>
								<div style="display: inline;" id="total_col6a_<?php echo $qrBuscaModulos['tkt_medio']; ?>"><?php echo fnValor($qrBuscaModulos['tkt_medio'], 2); ?>
							</b>
					</div>
					</th>
					</tr>
				<?php

												$qtdRegistros = mysqli_num_rows($arrayQuery);
												$mediaValcompra += $qrBuscaModulos['val_compras'];
												$mediaQtdCompra += $qrBuscaModulos['qtd_compras'];
												$valorTkt = $mediaQtdCompra != 0 ? ($mediaValcompra / $mediaQtdCompra) : 0;
												//fnEscreve($qtdRegistros);
											}
				?>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<?php
						if ($qtdRegistros != 0 && $qtdRegistros != '') {
						?>
							<td><b><small>R$ </small><?php echo fnValor($mediaValcompra, 2); ?></b></td>
							<td><b><small></small><?php echo $mediaQtdCompra; ?></b></td>
							<td><b><small>R$ </small><?php echo fnValor($valorTkt, 2); ?></b></td>
						<?php
						}
						?>
					</tr>
				</tfoot>

				</table>
				<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
				</form>

				</div>

			</div>

			<div class="push"></div>

	</div>

</div>
</div>
<!-- fim Portlet -->
</div>

</div>

<div class="push20"></div>
<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	$(document).ready(function() {

		var persona = '<?php echo $cod_persona; ?>';
		if (persona != 0 && persona != "") {
			//retorno combo multiplo - USUARIOS_ENV
			$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_persona; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_PERSONA option[value=" + Number(sistemasUniArr[i]).toString() + "]").prop("selected", "true");
			}
			$("#formulario #COD_PERSONA").trigger("chosen:updated");
		}

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			//maxDate : 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$("#DAT_INI_GRP").data("DateTimePicker").defaultDate(false);
		$('#DAT_FIM_GRP').data("DateTimePicker").defaultDate(false);

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date).date(null);
		});

	});

	function retornaForm(index) {
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>