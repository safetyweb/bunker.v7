<?php

//echo fnDebug('true');

$hashLocal = mt_rand();
$cod_tpmodal = 0;

$conn = connTemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;



		$cod_licitac = fnLimpaCampoZero($_REQUEST['COD_LICITAC']);
		$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$nom_licitac = fnLimpaCampo($_REQUEST['NOM_LICITAC']);
		$num_licitac = fnLimpaCampo($_REQUEST['NUM_LICITAC']);
		$des_licitac = fnLimpaCampo($_REQUEST['DES_LICITAC']);
		$cod_tpmodal = fnLimpaCampoZero($_REQUEST['COD_TPMODAL']);
		$num_adminis = fnLimpaCampo($_REQUEST['NUM_ADMINIS']);
		$dat_habilit = fnLimpaCampo($_REQUEST['DAT_HABILIT']);
		$dat_propost = fnLimpaCampo($_REQUEST['DAT_PROPOST']);
		$dat_edital = fnLimpaCampo($_REQUEST['DAT_EDITAL']);
		$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);

		//fnEscreve($cod_licitac);

		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_LICITACAO (
				 '" . $cod_licitac . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_conveni . "', 
				 '" . $nom_licitac . "',
				 '" . $num_licitac . "',
				 '" . $des_licitac . "',	
				 '" . $cod_tpmodal . "', 
				 '" . $num_adminis . "',
				 '" . fnDataSql($dat_edital) . "',
				 '" . $cod_usucada . "',
				 '" . $opcao . "'    
			        );";

			// fnEscreve($sql);
			$arrayQr = mysqli_query(connTemp($cod_empresa,''), $sql);

			if (!$arrayQr) {

				$cod_erro = Log_error_comand($connAdm->connAdm(),connTemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
			}
			//fnEscreve($cod_erro);

			if ($opcao == 'CAD') {

				$sqlCod = "SELECT MAX(COD_LICITAC) COD_LICITAC FROM LICITACAO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sqlCod);
				if (!$arrayQuery) {
					$cod_erro = Log_error_comand($connAdm->connAdm(),connTemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlCod,$nom_usuario);
				}
				$qrCod = mysqli_fetch_assoc($arrayQuery);
				$cod_licitac = $qrCod[COD_LICITAC];

				$sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
				$arrayCont = mysqli_query(connTemp($cod_empresa,''), $sqlArquivos);

				if (mysqli_num_rows($arrayCont) > 0) {
					$sqlUpd1 = "UPDATE ANEXO_CONVENIO SET COD_LICITAC = $cod_licitac, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
					$queryUpdate1 = mysqli_query(connTemp($cod_empresa,''), $sqlUpd1);

					if (!$queryUpdate1) {
						$cod_erro = Log_error_comand($connAdm->connAdm(),connTemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpd1,$nom_usuario);
					}
				}
			} else {
				$sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_LICITAC = $cod_licitac AND LOG_STATUS = 'N'";
				$queryUpdate = mysqli_query(connTemp($cod_empresa,''), $sqlUpd);
				if (!$queryUpdate) {
					$cod_erro = Log_error_comand($connAdm->connAdm(),connTemp($cod_empresa,''),$cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpd,$nom_usuario);
				}
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}
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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$nom_empresa = "";
}


if (isset($_GET['idC'])) {
	if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))) {

		//busca dados do convênio
		$cod_conveni = fnDecode($_GET['idC']);
		$sql = "SELECT NOM_CONVENI FROM CONVENIO WHERE COD_CONVENI = " . $cod_conveni;

		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);
		$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaTemplate)) {
			$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
		}
	}
}

//busca dados do usuário
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = " . $cod_usucada;

//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaUsuario)) {
	$nom_usuario = $qrBuscaUsuario['NOM_USUARIO'];
}

//fnMostraForm();
//fnEscreve($cod_checkli);

$tp_cont = 'Anexo da Licitação';
$tp_anexo = 'COD_LICITAC';
$cod_tpanexo = 'COD_LICITAC';
$cod_busca = $cod_licitac;

$sqlUpdtCont = "DELETE FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_LICITAC != 0 AND LOG_STATUS = 'N'";
mysqli_query(connTemp($cod_empresa, ''), $sqlUpdtCont);

$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
mysqli_query(connTemp($cod_empresa, ''), $sqlUpdtCont);

$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlCont));
$num_contador = $qrCont['NUM_CONTADOR'];

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

					<?php
					//menu superior - licitação
					$abaProposta = 1089;
					include "abasLicitacao.php";
					?>

					<div class="push30"></div>


					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_LICITAC" id="COD_LICITAC" value="">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Convênio</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni ?>" required>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Núm. Processo Administrativo</label>
										<input type="text" class="form-control input-sm" name="NUM_ADMINIS" id="NUM_ADMINIS" value="" maxlength="40">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data de Publicação do Edital</label>
										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="DAT_EDITAL" id="DAT_EDITAL" value="" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>


							</div>

							<div class="row">

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label">Objeto da Licitação</label>
										<input type="text" class="form-control input-sm" name="NOM_LICITAC" id="NOM_LICITAC" value="" maxlength="40" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Núm. da Modalidade </label>
										<input type="text" class="form-control input-sm" name="NUM_LICITAC" id="NUM_LICITAC" value="" maxlength="40">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Modalidade</label>
										<select data-placeholder="Selecione" name="COD_TPMODAL" id="COD_TPMODAL" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "select * from TIPOMODALIDADE order by COD_TPMODAL ";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

											while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
												echo "
													<option value='" . $qrListaTipoEntidade['COD_TPMODAL'] . "'>" . $qrListaTipoEntidade['DES_TPMODAL'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição</label>
										<textarea type="text" class="form-control input-sm" rows="3" name="DES_LICITAC" id="DES_LICITAC" value="" maxlength="250"></textarea>
									</div>
									<div class="help-block with-errors"></div>
								</div>

							</div>

							<div class="push10"></div>

							<?php include "uploadConvenio.php"; ?>

							<div class="push10"></div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> 
							   DAT_HABILIT DAT_PROPOST
							  -->

						</div>

						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?= $cod_conveni ?>">
						<input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
						<input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>
				</div>

				</div>

			</div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover tablesorter buscavel">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Código</th>
												<th>Número</th>
												<th>Objeto</th>
												<th>Número do Processo</th>
											</tr>
										</thead>
										<tbody>

											<?php
											$sql = "SELECT * FROM LICITACAO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												echo "
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
											  <td>" . $qrBuscaModulos['COD_LICITAC'] . "</td>											
											  <td>" . $qrBuscaModulos['NUM_LICITAC'] . "</td>
											  <td>" . $qrBuscaModulos['NOM_LICITAC'] . "</td>
											  <td>" . $qrBuscaModulos['NUM_ADMINIS'] . "</td>
											</tr>
											
											<input type='hidden' id='ret_COD_LICITAC_" . $count . "' value='" . $qrBuscaModulos['COD_LICITAC'] . "'>
											<input type='hidden' id='ret_COD_CONVENI_" . $count . "' value='" . $qrBuscaModulos['COD_CONVENI'] . "'>
											<input type='hidden' id='ret_NUM_LICITAC_" . $count . "' value='" . $qrBuscaModulos['NUM_LICITAC'] . "'>
											<input type='hidden' id='ret_NOM_LICITAC_" . $count . "' value='" . $qrBuscaModulos['NOM_LICITAC'] . "'>
											<input type='hidden' id='ret_DES_LICITAC_" . $count . "' value='" . $qrBuscaModulos['DES_LICITAC'] . "'>
											<input type='hidden' id='ret_COD_TPMODAL_" . $count . "' value='" . $qrBuscaModulos['COD_TPMODAL'] . "'>
											<input type='hidden' id='ret_NUM_ADMINIS_" . $count . "' value='" . $qrBuscaModulos['NUM_ADMINIS'] . "'>
											<input type='hidden' id='ret_DAT_EDITAL_" . $count . "' value='" . date_time($qrBuscaModulos['DAT_EDITAL']) . "'>
											";
											}
											?>

										</tbody>
									</table>

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

		$('#btnBusca').prop('disabled', true);

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

	});

	function retornaForm(index) {
		$("#formulario #COD_LICITAC").val($("#ret_COD_LICITAC_" + index).val());
		$("#formulario #COD_OBJETOANEXO").val($("#ret_COD_LICITAC_" + index).val());
		$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_" + index).val());
		$("#formulario #NUM_LICITAC").val($("#ret_NUM_LICITAC_" + index).val());
		$("#formulario #NOM_LICITAC").val($("#ret_NOM_LICITAC_" + index).val());
		$("#formulario #DES_LICITAC").val($("#ret_DES_LICITAC_" + index).val());
		$("#formulario #COD_TPMODAL").val($("#ret_COD_TPMODAL_" + index).val()).trigger("chosen:updated");
		$("#formulario #NUM_ADMINIS").val($("#ret_NUM_ADMINIS_" + index).val());
		$("#formulario #DAT_EDITAL").unmask().val($("#ret_DAT_EDITAL_" + index).val());
		$('.upload').prop('disabled', false).removeAttr('disabled');

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');

		refreshUpload();
	}
</script>

<?php include 'jsUploadConvenio.php'; ?>