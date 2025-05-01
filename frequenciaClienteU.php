<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_frequencia = "";
$qtd_diashist = 0;
$qtd_inativo = 0;
$qtd_mesclass = 0;
$faixa_min_fans = "";
$faixa_max_fans = "";
$faixa_min_fieis = "";
$faixa_max_fieis = "";
$faixa_min_frequentes = "";
$faixa_max_frequentes = "";
$faixa_min_casuais = "";
$faixa_max_casuais = "";
$pct_fans = "";
$pct_fieis = "";
$pct_frequentes = "";
$pct_casuais = "";
$tot_cliente = "";
$txt_fans = "";
$txt_fieis = "";
$txt_frequentes = "";
$txt_casuais = "";
$cod_usucada = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$sqlInsert = "";
$arrayInsert = [];
$cod_erro = "";
$sqlUpdate = "";
$arrayUpdate = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrFreq = "";
$countFreq = "";
$abaEmpresa = "";


$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_frequencia = fnLimpaCampoZero(@$_POST['COD_FREQUENCIA']);
		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$qtd_diashist = fnLimpaCampoZero(@$_POST['QTD_DIASHIST']);
		$qtd_inativo = fnLimpaCampoZero(@$_POST['QTD_INATIVO']);
		$qtd_mesclass = fnLimpaCampoZero(@$_POST['QTD_MESCLASS']);
		$faixa_min_fans = fnLimpaCampo(@$_POST['FAIXA_MIN_FANS']);
		$faixa_max_fans = fnLimpaCampo(@$_POST['FAIXA_MAX_FANS']);
		$faixa_min_fieis = fnLimpaCampo(@$_POST['FAIXA_MIN_FIEIS']);
		$faixa_max_fieis = fnLimpaCampo(@$_POST['FAIXA_MAX_FIEIS']);
		$faixa_min_frequentes = fnLimpaCampo(@$_POST['FAIXA_MIN_FREQUENTES']);
		$faixa_max_frequentes = fnLimpaCampo(@$_POST['FAIXA_MAX_FREQUENTES']);
		$faixa_min_casuais = fnLimpaCampo(@$_POST['FAIXA_MIN_CASUAIS']);
		$faixa_max_casuais = fnLimpaCampo(@$_POST['FAIXA_MAX_CASUAIS']);
		$pct_fans = fnLimpaCampo(@$_POST['PCT_FANS']);
		$pct_fieis = fnLimpaCampo(@$_POST['PCT_FIEIS']);
		$pct_frequentes = fnLimpaCampo(@$_POST['PCT_FREQUENTES']);
		$pct_casuais = fnLimpaCampo(@$_POST['PCT_CASUAIS']);
		$tot_cliente = fnLimpaCampo(@$_POST['TOT_CLIENTE']);
		$txt_fans = fnLimpaCampo(@$_POST['TXT_FANS']);
		$txt_fieis = fnLimpaCampo(@$_POST['TXT_FIEIS']);
		$txt_frequentes = fnLimpaCampo(@$_POST['TXT_FREQUENTES']);
		$txt_casuais = fnLimpaCampo(@$_POST['TXT_CASUAIS']);

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$sqlInsert = "INSERT INTO FREQUENCIA_CLIENTE_U(
									COD_EMPRESA,
									FAIXA_MIN_FANS,
									FAIXA_MAX_FANS,
									FAIXA_MIN_FIEIS,
									FAIXA_MAX_FIEIS,
									FAIXA_MIN_FREQUENTES,
									FAIXA_MAX_FREQUENTES,
									FAIXA_MIN_CASUAIS,
									FAIXA_MAX_CASUAIS,
									PCT_FANS,
									PCT_FIEIS,
									PCT_FREQUENTES,
									PCT_CASUAIS,
									TXT_FANS,
									TXT_FIEIS,
									TXT_FREQUENTES,
									TXT_CASUAIS,
									QTD_DIASHIST,
									QTD_INATIVO,
									QTD_MESCLASS,
									COD_USUCADA
									)VALUES(
									$cod_empresa,
									$faixa_min_fans,
									$faixa_max_fans,
									$faixa_min_fieis,
									$faixa_max_fieis,
									$faixa_min_frequentes,
									$faixa_max_frequentes,
									$faixa_min_casuais,
									$faixa_max_casuais,
									'" . fnValorSql($pct_fans) . "',
									'" . fnValorSql($pct_fieis) . "',
									'" . fnValorSql($pct_frequentes) . "',
									'" . fnValorSql($pct_casuais) . "',
									'$txt_fans',
									'$txt_fieis',
									'$txt_frequentes',
									'$txt_casuais',
									$qtd_diashist,
									$qtd_inativo,
									$qtd_mesclass,
									$cod_usucada
									);";

					//echo $sql;
					// fnEscreve($sql);								
					$arrayInsert = mysqli_query(conntemp($cod_empresa, ''), $sqlInsert);

					if (!$arrayInsert) {

						$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
					}
					//fnTestesql(connTemp($cod_empresa,''),$sql);

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;

				case 'ALT':
					$sqlUpdate = "UPDATE FREQUENCIA_CLIENTE_U SET							
								FAIXA_MIN_FANS=$faixa_min_fans,
								FAIXA_MAX_FANS=$faixa_max_fans,
								FAIXA_MIN_FIEIS=$faixa_min_fieis,
								FAIXA_MAX_FIEIS=$faixa_max_fieis,
								FAIXA_MIN_FREQUENTES=$faixa_min_frequentes,
								FAIXA_MAX_FREQUENTES=$faixa_max_frequentes,
								FAIXA_MIN_CASUAIS=$faixa_min_casuais,
								FAIXA_MAX_CASUAIS=$faixa_max_casuais,
								PCT_FANS = '" . fnValorSql($pct_fans) . "',
								PCT_FIEIS = '" . fnValorSql($pct_fieis) . "',
								PCT_FREQUENTES = '" . fnValorSql($pct_frequentes) . "',
								PCT_CASUAIS = '" . fnValorSql($pct_casuais) . "',
								TXT_FANS='$txt_fans',
								TXT_FIEIS='$txt_fieis',
								TXT_FREQUENTES='$txt_frequentes',
								TXT_CASUAIS='$txt_casuais',
								QTD_DIASHIST=$qtd_diashist,
								QTD_INATIVO=$qtd_inativo,
								QTD_MESCLASS=$qtd_mesclass
								WHERE COD_FREQUENCIA = $cod_frequencia;";

					//fnEscreve($sql);	
					$arrayUpdate = mysqli_query(conntemp($cod_empresa, ''), $sqlUpdate);

					if (!$arrayUpdate) {

						$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;

				case 'EXC':
					$sql = "DELETE FROM FREQUENCIA_CLIENTE_U WHERE COD_FREQUENCIA = $cod_frequencia;";
					// mysqli_query($connAdm->connAdm(),trim($sql));
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$sql = "SELECT * FROM FREQUENCIA_CLIENTE_U WHERE COD_EMPRESA = $cod_empresa";
$arrayQuery = mysqli_query($conn, $sql);
$qrFreq = mysqli_fetch_assoc($arrayQuery);
$countFreq = mysqli_num_rows($arrayQuery);

if ($countFreq > 0) {
	$cod_frequencia = $qrFreq['COD_FREQUENCIA'];
	$faixa_min_fans = $qrFreq['FAIXA_MIN_FANS'];
	$faixa_max_fans = $qrFreq['FAIXA_MAX_FANS'];
	$faixa_min_fieis = $qrFreq['FAIXA_MIN_FIEIS'];
	$faixa_max_fieis = $qrFreq['FAIXA_MAX_FIEIS'];
	$faixa_min_frequentes = $qrFreq['FAIXA_MIN_FREQUENTES'];
	$faixa_max_frequentes = $qrFreq['FAIXA_MAX_FREQUENTES'];
	$faixa_min_casuais = $qrFreq['FAIXA_MIN_CASUAIS'];
	$faixa_max_casuais = $qrFreq['FAIXA_MAX_CASUAIS'];
	$pct_fans = $qrFreq['PCT_FANS'];
	$pct_fieis = $qrFreq['PCT_FIEIS'];
	$pct_frequentes = $qrFreq['PCT_FREQUENTES'];
	$pct_casuais = $qrFreq['PCT_CASUAIS'];
	$txt_fans = $qrFreq['TXT_FANS'];
	$txt_fieis = $qrFreq['TXT_FIEIS'];
	$txt_frequentes = $qrFreq['TXT_FREQUENTES'];
	$txt_casuais = $qrFreq['TXT_CASUAIS'];
	$qtd_diashist = $qrFreq['QTD_DIASHIST'];
	$qtd_inativo = $qrFreq['QTD_INATIVO'];
	$qtd_mesclass = $qrFreq['QTD_MESCLASS'];
} else {
	$cod_frequencia = 0;
	$faixa_min_fans = 0;
	$faixa_max_fans = 0;
	$faixa_min_fieis = 0;
	$faixa_max_fieis = 0;
	$faixa_min_frequentes = 0;
	$faixa_max_frequentes = 0;
	$faixa_min_casuais = 0;
	$faixa_max_casuais = 0;
	$pct_fans = 0;
	$pct_fieis = 0;
	$pct_frequentes = 0;
	$pct_casuais = 0;
	$txt_fans = "Fã";
	$txt_fieis = "Fiel";
	$txt_frequentes = "Frequente";
	$txt_casuais = "Casual";
	$qtd_diashist = "";
	$qtd_inativo = "";
	$qtd_mesclass = "";
}


//fnMostraForm();

?>
<style>
	.table-icons button {
		background: #fff;
		color: #3c3c3c;
	}

	.table-icons button:hover {
		background: #2c3e50;
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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>
				<?php include "atalhosPortlet.php"; ?>
			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php $abaEmpresa = 1621;
				include "abasEmpresaConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Parâmetros de Atualização</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Ciclo de Recompra<small> (em dias)</small></label>
										<input type="text" class="form-control text-center input-sm int" name="QTD_DIASHIST" id="QTD_DIASHIST" maxlength="3" value="<?php echo $qtd_diashist ?>">
										<div class="help-block with-errors">Histórico para Classificação</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Momento da Classificação</label>
										<select data-placeholder="Selecione a periodicidade de reclassificação" name="QTD_MESCLASS" id="QTD_MESCLASS" class="chosen-select-deselect">
											<option value=""></option>
											<option value="12" disabled>Anual</option>
											<option value="6" disabled>Semestral</option>
											<option value="4" disabled>Quadrimestral</option>
											<option value="3" disabled>Trimestral</option>
											<option value="2" disabled>Bimestral</option>
											<option value="1">Mensal</option>
										</select>
										<script>
											$("#formulario #QTD_MESCLASS").val("<?php echo $qtd_mesclass; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors">início em 01/jan</div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<fieldset>
							<legend>Configuração Geral</legend>

							<div class="row">

								<div class="col-md-3" style="padding-left: 0; padding-right: 0;">

									<div class="col-md-5">
										<div class="form-group">
											<label for="inputName" class="control-label">De</label>
											<input type="text" class="form-control input-sm text-center int compara" data-compara="7" name="FAIXA_MIN_CASUAIS" id="FAIXA_MIN_CASUAIS" maxlength="6" value="<?= $faixa_min_casuais ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2 text-center">
										<div class="push30"></div>
										a
									</div>

									<div class="col-md-5">
										<div class="form-group">
											<label for="inputName" class="control-label">Até</label>
											<input type="text" class="form-control input-sm text-center int compara" data-compara="8" name="FAIXA_MAX_CASUAIS" id="FAIXA_MAX_CASUAIS" maxlength="6" value="<?= $faixa_max_casuais ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="col-md-3" style="padding-left: 0; padding-right: 0;">

									<div class="col-md-5">
										<div class="form-group">
											<label for="inputName" class="control-label">De</label>
											<input type="text" class="form-control input-sm text-center int compara" data-compara="5" name="FAIXA_MIN_FREQUENTES" id="FAIXA_MIN_FREQUENTES" maxlength="6" value="<?= $faixa_min_frequentes ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2 text-center">
										<div class="push30"></div>
										a
									</div>

									<div class="col-md-5">
										<div class="form-group">
											<label for="inputName" class="control-label">Até</label>
											<input type="text" class="form-control input-sm text-center int compara" data-compara="6" name="FAIXA_MAX_FREQUENTES" id="FAIXA_MAX_FREQUENTES" maxlength="6" value="<?= $faixa_max_frequentes ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="col-md-3" style="padding-left: 0; padding-right: 0;">

									<div class="col-md-5">
										<div class="form-group">
											<label for="inputName" class="control-label">De</label>
											<input type="text" class="form-control input-sm text-center int compara" data-compara="3" name="FAIXA_MIN_FIEIS" id="FAIXA_MIN_FIEIS" maxlength="6" value="<?= $faixa_min_fieis ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2 text-center">
										<div class="push30"></div>
										a
									</div>

									<div class="col-md-5">
										<div class="form-group">
											<label for="inputName" class="control-label">Até</label>
											<input type="text" class="form-control input-sm text-center int compara" data-compara="4" name="FAIXA_MAX_FIEIS" id="FAIXA_MAX_FIEIS" maxlength="6" value="<?= $faixa_max_fieis ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="col-md-3" style="padding-left: 0; padding-right: 0;">

									<div class="col-md-5">
										<div class="form-group">
											<label for="inputName" class="control-label">De</label>
											<input type="text" class="form-control input-sm text-center int compara" data-compara="1" name="FAIXA_MIN_FANS" id="FAIXA_MIN_FANS" maxlength="6" value="<?= $faixa_min_fans ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2 text-center">
										<div class="push30"></div>
										a
									</div>

									<div class="col-md-5">
										<div class="form-group">
											<label for="inputName" class="control-label">Até</label>
											<input type="text" class="form-control input-sm text-center int compara" data-compara="2" name="FAIXA_MAX_FANS" id="FAIXA_MAX_FANS" maxlength="6" value="<?= $faixa_max_fans ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<input type="text" class="form-control input-sm text-center" name="TXT_CASUAIS" id="TXT_CASUAIS" value="<?= $txt_casuais ?>">
										<div class="help-block with-errors">Casual (1)</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<input type="text" class="form-control input-sm text-center" name="TXT_FREQUENTES" id="TXT_FREQUENTES" value="<?= $txt_frequentes ?>">
										<div class="help-block with-errors">Frequente (2)</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<input type="text" class="form-control input-sm text-center" name="TXT_FIEIS" id="TXT_FIEIS" value="<?= $txt_fieis ?>">
										<div class="help-block with-errors">Fiel (3)</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<input type="text" class="form-control input-sm text-center" name="TXT_FANS" id="TXT_FANS" value="<?= $txt_fans ?>">
										<div class="help-block with-errors">Fã (4)</div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<?php if ($_SESSION["SYS_COD_EMPRESA"] == 2) { ?>
								<a href="javascript:void(0)" class="btn btn-danger pull-left addBox" data-url="action.php?mod=<?php echo fnEncode(1541) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Processamento de Cargas - <?= $nom_empresa ?>"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Processar Manualmente</a>
								<!--  CALL SP_RELAT_LUCRO_FREQUENCIA_UNIVEND ('95890,96311,95932,95898,95892,95939,95902,95868,95907,95887,95903,95962,95909,95900,95901,95959,95908,95866,95925,95956,95872,95957,95896,95948,95899,95961,95869,95867,95881,95891,95970,95888,95871,95883,95949,95922,95877,95921,95874,95928,95952,95906,95953,95893,95914,95916,95960,95904,95941,95965,95966,95973,95967,95942,95875,95920,95917,95946,95936,95935,95944,95884,95964,95955,95878,95886,95905,95918,95947,95889,95919,95865,95937,95897,95910,95873,95954,95912,95924,95879,95938,95923,95880,95915,95870,95943,95958,95882,95951,95876,95968,95913,95894,95926,95950,95940,95927,95895,95911,95945,95972,95971,95974,96316,96318,96414,96415,95864',39,0,'2019-12') -->
							<?php } ?>

							<?php if ($cod_frequencia == 0) { ?>

								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

							<?php } else { ?>

								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>

							<?php } ?>


						</div>

						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="COD_FREQUENCIA" id="COD_FREQUENCIA" value="<?= $cod_frequencia ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="QTD_INATIVO" id="QTD_INATIVO" maxlength="3" value="<?php echo $qtd_inativo ?>">


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

<div class="push20"></div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 90%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">
	$(document).ready(function() {

		//      	$('#PCT_FANS,#PCT_FIEIS,#PCT_FREQUENTES,#PCT_CASUAIS').change(function(){
		// 	$('#TOT_CLIENTE').unmask();
		// 	var campo = $(this);

		// 	pct_fans = limpaValorZero('#PCT_FANS');
		// 	pct_fieis = limpaValorZero('#PCT_FIEIS');
		// 	pct_frequentes = limpaValorZero('#PCT_FREQUENTES');
		// 	pct_casuais = limpaValorZero('#PCT_CASUAIS');

		// 	total = (pct_fans+pct_fieis+pct_frequentes+pct_casuais).toFixed(2);

		// 	$('#TOT_CLIENTE').val(total).toString();

		// });

		// $("#CAD,#ALT").click(function(e){

		// 	e.preventDefault();
		// 	alert('teste');

		// 	total = parseFloat($("#TOT_CLIENTE").val().replace('.','').replace(',','.'));

		// 	if(total != 100.00){
		// 		$.alert({
		//                   title: "Valor Incorreto",
		//                   content: "A soma dos valores não pode ser diferente de 100"
		//               });
		// 	}else{
		// 		$("#formulario").submit();
		// 	}
		// });

		$(".compara").change(function() {

			let numCompara = Number($(this).attr("data-compara")),
				idCompara1 = "#" + $(this).attr("id"),
				idZera = "",
				baseCompara = "",
				idCompara2 = "",
				numCompara2 = 0,
				mensagem = false;

			if (numCompara % 2 == 1) {
				numCompara2 = numCompara + 1;
				baseCompara = "min";
			} else {
				numCompara2 = numCompara - 1;
				baseCompara = "max";
			}

			idCompara2 = "#" + $("[data-compara=" + numCompara2 + "]").attr("id");

			// console.log("campo 1: " + idCompara1 + " - " + $(idCompara1).val());
			// console.log("campo 2: " + idCompara2 + " - " + $(idCompara2).val());
			// console.log("BASE: " + baseCompara);

			if ($(idCompara2).val() != "") {
				if (baseCompara == "min") {
					if (Number($(idCompara1).val()) >= Number($(idCompara2).val())) {
						idZera = idCompara2;
						mensagem = true;
					}
				} else {
					if (Number($(idCompara2).val()) >= Number($(idCompara1).val())) {
						// console.log("se "+$(idCompara2).val()+" for maior que "+$(idCompara1).val());
						idZera = idCompara1;
						mensagem = true;
					}
				}
			}

			if (mensagem) {
				$(idZera).val("");
				$.alert({
					title: "Valor Incorreto",
					content: "O valor mínimo não pode ser maior ou igual que o máximo"
				});
			}

		});

		$(".addBox").click(function() {
			$('#popModal').find('.modal-content').css({
				'width': '100vw',
				'height': '99.5vh',
				'marginLeft': 'auto',
				'marginRight': 'auto'

			});
			$('#popModal').find('.modal-dialog').css({
				'margin': '0'
			});
		});

		function limpaValorZero(id) {
			if ($(id).val() != '') {
				val = parseFloat($(id).val().replace('.', '').replace(',', '.'));
			} else {
				val = 0;
			}
			return val;
		}

	});
</script>