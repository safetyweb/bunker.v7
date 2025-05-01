<?php

//echo fnDebug('true');

$hashLocal = mt_rand();
$log_obrigat = 'N';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_recebim = fnLimpaCampoZero($_REQUEST['COD_RECEBIM']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
		$cod_contrat = fnLimpaCampoZero($_REQUEST['COD_CONTRAT']);
		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
		$cod_medicao = fnLimpaCampoZero($_REQUEST['COD_MEDICAO']);
		$num_medicao = fnLimpaCampo($_REQUEST['NUM_MEDICAO']);
		$dat_medicao = fnLimpaCampo($_REQUEST['DAT_MEDICAO']);
		$val_evolucao = fnValorSql($_REQUEST['VAL_EVOLUCAO']);
		$val_medicao = fnValorSql($_REQUEST['VAL_MEDICAO']);
		$des_nomebem = fnLimpaCampo($_REQUEST['DES_NOMEBEM']);
		$tip_controle = fnLimpaCampo($_REQUEST['TIP_CONTROLE']);
		$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);
		// $val_total = fnLimpaCampo($_REQUEST['VAL_TOTAL']);

		$val_total = $val_evolucao * $val_medicao;

		if (isset($_POST["btnUploadFile"])) {


			fnEscreve($_FILES["file"]["tmp_name"]);
		}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$dat_medicao = date("Y-m-d H:i:s");

					$sql = "INSERT INTO CONTROLE_RECEBIMENTO(
											COD_EMPRESA,
											COD_CONVENI,
											COD_CONTRAT,
											COD_CLIENTE,
											COD_MEDICAO,
											NUM_MEDICAO,
											DAT_MEDICAO,
											VAL_EVOLUCAO,
											VAL_MEDICAO,
											VAL_TOTAL,
											DES_NOMEBEM,
											TIP_CONTROLE,
											COD_USUCADA
											) VALUES(
											$cod_empresa,
											$cod_conveni,
											$cod_contrat,
											$cod_cliente,
											$cod_medicao,
											'$num_medicao',
											'" . fnDataSql($dat_medicao) . "',
											'" . $val_evolucao . "',
											'" . $val_medicao . "',
											'" . $val_total . "',
											'$des_nomebem',
											'$tip_controle',
											$cod_usucada
											)";

					//fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					if ($cod_recebim == 0) {

						$sqlCod = "SELECT MAX(COD_RECEBIM) COD_RECEBIM FROM CONTROLE_RECEBIMENTO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlCod);
						$qrCod = mysqli_fetch_assoc($arrayQuery);
						$cod_recebim = $qrCod[COD_RECEBIM];

						$sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
						$arrayCont = mysqli_query(connTemp($cod_empresa, ''), $sqlArquivos);

						if (mysqli_num_rows($arrayCont) > 0) {
							$sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_RECEBIM = $cod_recebim, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
							mysqli_query(connTemp($cod_empresa, ''), $sqlUpd);
						}
					} else {
						// $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_LICITAC = $cod_licitac AND LOG_STATUS = 'N'";
						// mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
					}

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':

					$sql = "UPDATE CONTROLE_RECEBIMENTO SET
											COD_MEDICAO=$cod_medicao,
											NUM_MEDICAO='$num_medicao',
											DAT_MEDICAO='" . fnDataSql($dat_medicao) . "',
											VAL_EVOLUCAO='" . $val_evolucao . "',
											VAL_MEDICAO='" . $val_medicao . "',
											VAL_TOTAL='" . $val_total . "',
											DES_NOMEBEM='$des_nomebem',
											COD_ALTERAC=$cod_usucada
											WHERE COD_RECEBIM = $cod_recebim
											";

					//fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_RECEBIM = $cod_recebim AND LOG_STATUS = 'N'";
					mysqli_query(connTemp($cod_empresa, ''), $sqlUpd);

					$sql = "SELECT COD_RECEBIM_LOTE COD_RECEBIM FROM CONTROLE_RECEBIMENTO
					WHERE COD_EMPRESA=$cod_empresa AND COD_RECEBIM=0$cod_recebim";
					$rs = mysqli_query(connTemp($cod_empresa, ''), $sql);
					$linha = mysqli_fetch_assoc($rs);
					fnCalculaLote($linha["COD_RECEBIM"],$cod_empresa);
					
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;

				case 'ARQUIVO_UP':

					$filename = "";

					if (file_exists($filename)) {

						$sqlUpload = "LOAD DATA INFILE '$filename' INTO TABLE CONTROLE_RECEBIMENTO";

						//mysqli_query(connTemp($cod_empresa,''),$sqlUpload);

					} else {
						$msgRetorno = "Importação não <strong>concluída!</strong>";
					}
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$nom_empresa = "";
}


if (isset($_GET['idCT'])) {
	if (is_numeric(fnLimpacampo(fnDecode($_GET['idCT'])))) {

		//busca dados do convênio
		$cod_contrat = fnDecode($_GET['idCT']);

		$sql = "SELECT CTT.*, 
					(SELECT SUM(VAL_EVOLUCAO) FROM CONTROLE_RECEBIMENTO CR  WHERE CR.COD_EMPRESA=CTT.COD_EMPRESA AND CR.COD_CONTRAT=CTT.COD_CONTRAT AND CR.COD_CONVENI=CTT.COD_CONVENI) AS TOTAL_ITEM,
					(SELECT SUM(VAL_TOTAL) FROM CONTROLE_RECEBIMENTO CR  WHERE CR.COD_EMPRESA=CTT.COD_EMPRESA AND CR.COD_CONTRAT=CTT.COD_CONTRAT AND CR.COD_CONVENI=CTT.COD_CONVENI) AS VAL_TOTAL_RECEBI,
					CL.COD_CLIENTE, 
					CL.NOM_CLIENTE,
					CON.NOM_CONVENI					
					FROM CONTRATO CTT 
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = CTT.COD_CLIENTE
					LEFT JOIN CONVENIO CON ON CON.COD_CONVENI = CTT.COD_CONVENI
					WHERE CTT.COD_CONTRAT = $cod_contrat AND CTT.COD_EMPRESA = $cod_empresa
					";

		//fnEscreve($sql);
		$arrayQuery =  mysqli_query(connTemp($cod_empresa, ''), $sql);
		$qrContrat = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrContrat)) {
			$cod_contrat = $qrContrat['COD_CONTRAT'];
			$cod_conveni = $qrContrat['COD_CONVENI'];
			$nom_conveni = $qrContrat['NOM_CONVENI'];
			$cod_cliente = $qrContrat['COD_CLIENTE'];
			$nro_contrat = $qrContrat['NRO_CONTRAT'];
			$val_valor = $qrContrat['VAL_VALOR'];
			$total_item = $qrContrat['TOTAL_ITEM'];
			$val_total_recebi = $qrContrat['VAL_TOTAL_RECEBI'];
			$nom_empContrat = $qrContrat['NOM_CLIENTE'];
		}
	}
}

$sqlAcumula = "SELECT SUM(VAL_MEDICAO) AS VAL_MEDAC, SUM(VAL_EVOLUCAO) AS VAL_EVOFIS 
	FROM CONTROLE_RECEBIMENTO WHERE COD_CONTRAT = $cod_contrat AND COD_EMPRESA = $cod_empresa";
$arrayAcumula =  mysqli_query(connTemp($cod_empresa, ''), $sqlAcumula);
$qrAcumula = mysqli_fetch_assoc($arrayAcumula);

if (isset($qrAcumula)) {

	$val_medac = $qrAcumula['VAL_MEDAC'];
	$val_evofis = $qrAcumula['VAL_EVOFIS'];
} else {

	$val_medac = 0;
	$val_evofis = 0;
}

//fnMostraForm();
//fnEscreve($cod_empresa);

$tp_cont = 'Anexo do Recebimento';
$tp_anexo = 'COD_RECEBIM';
$cod_tpanexo = 'COD_RECEBIM';
$cod_busca = $cod_recebim;

$sqlUpdtCont = "DELETE FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_RECEBIM != 0 AND LOG_STATUS = 'N'";
mysqli_query(connTemp($cod_empresa, ''), $sqlUpdtCont);

$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
mysqli_query(connTemp($cod_empresa, ''), $sqlUpdtCont);

$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlCont));
$num_contador = $qrCont['NUM_CONTADOR'];

//fnEscreve($val_total);

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
							<i class="glyphicon glyphicon-calendar"></i>
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

					<div class="tabbable-line">
						<ul class="nav nav-tabs" style="text-decoration: none;">
							<li>
								<a href="action.do?mod=<?php echo fnEncode(1348) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_conveni); ?>" style="text-decoration: none;">
									<span class="fal fa-arrow-circle-left fa-2x"></span></a>
							</li>
						</ul>
					</div>

					<!-- <h4>upload de arquivo</h4> -->
					<!-- <h4>outra tela de controle de recebimento</h4> -->
					<!-- <h4>campo de comentário</h4> -->

					<div class="push20"></div>

					<form role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_RECEBIM" id="COD_RECEBIM" value="<?= $cod_recebim ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Convênio</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni ?>" required>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa Contratada</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empContrat ?>" required>
										<input type="hidden" class="form-control input-sm" name="COD_LICITAC" id="COD_LICITAC" value="<?php echo $cod_licitac ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Contrato</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NRO_CONTRAT" id="NRO_CONTRAT" value="<?php echo $nro_contrat ?>" required>
									</div>
								</div>

							</div>

							<div class="push20"></div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor do Contrato</label>
										<input type="text" class="form-control input-sm money leitura" name="VAL_CONTRAT" id="VAL_CONTRAT" value="<?= fnValor($val_valor, 2) ?>" readonly>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor Total de Itens Recebidos</label>
										<input type="text" class="form-control input-sm money leituraOff" name="VAL_TOTAL_RECEBI" id="VAL_TOTAL_RECEBI" value="<?= fnValor($val_total_recebi, 2) ?>" readonly>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Quantidade Total de Itens Recebidos</label>
										<input type="text" class="form-control input-sm money leituraOff" name="TOTAL_ITEM" id="TOTAL_ITEM" value="<?= fnValor($total_item, 2) ?>" readonly>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Externo</label>
										<input type="text" class="form-control input-sm " name="COD_MEDICAO" id="COD_MEDICAO" value="" maxlength="11">
									</div>
									<div class="help-block with-errors"></div>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<fieldset>
							<legend>Dados da Medição</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Número do Recebimento</label>
										<input type="text" class="form-control input-sm" name="NUM_MEDICAO" id="NUM_MEDICAO" value="" maxlength="11" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data do Recebimento</label>
										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="DAT_MEDICAO" id="DAT_MEDICAO" value="" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Quantidade do Bem</label>
										<input type="text" class="form-control input-sm money" name="VAL_EVOLUCAO" id="VAL_EVOLUCAO" value="" data-mask="##0" data-mask-reverse="true" maxlength="11">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-1 text-center">
									<div class="push20"></div>
									<span class="f21">x</span>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor do Bem</label>
										<input type="text" class="form-control input-sm money" name="VAL_MEDICAO" id="VAL_MEDICAO" value="" data-mask="##0" data-mask-reverse="true" maxlength="11">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-1 text-center">
									<div class="push20"></div>
									<span class="f21">=</span>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor</label>
										<input type="text" class="form-control input-sm money leituraOff" name="VAL_TOTAL" id="VAL_TOTAL" value="" readonly data-mask="##0" data-mask-reverse="true" maxlength="11" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição do Bem</label>
										<input type="text" class="form-control input-sm" name="DES_NOMEBEM" id="DES_NOMEBEM" value="" maxlength="150" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

							</div>

							<div class="push10"></div>

							<?php include "uploadConvenio.php"; ?>

							<div class="push10"></div>

						</fieldset>

						<div class="push20"></div>

						<hr>
	
						<div class="form-group text-right col-lg-12">
						<div class="col-md-1">
						<span class="input-group-btn">
							<a type="button" name="btnBusca" id="btnBusca" style="border-radius:5px;" class="btn btn-info addBox" data-url="action.php?mod=<?= fnEncode(1807) ?>&id=<?= fnEncode($cod_empresa) ?>&idCT=<?=fnEncode($cod_contrat)?>&idC=<?= fnEncode($cod_conveni)?>&pop=true" data-title="Importação de Produtos"><i class="fal fa-Upload" aria-hidden="true">&nbsp;Importar Produtos</i></a>
						</span>	
						</div>

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="TIP_CONTROLE" id="TIP_CONTROLE" value="RCB">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?= $cod_conveni ?>">
						<input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
						<input type="hidden" name="COD_CONTRAT" id="COD_CONTRAT" value="<?= $cod_contrat ?>">
						<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?= $cod_cliente ?>">
						<input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="LOG_IMPORTOU" id="LOG_IMPORTOU" value="N">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista" id="tb_lista"></form>

						</div>

					</div>

					<div class="push"></div>

				</div>

				</div>
			</div>
			<!-- fim Portlet -->
	</div>

</div>

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

<div class="push20"></div>
<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	$(document).ready(function() {

		refresh_grid();

		//modal close
		$('.modal').on('hidden.bs.modal', function() {
			if ($("#LOG_IMPORTOU").val() == 'S') {
				location.reload();
			}
		});


		$('.upload').prop('disabled', true);

		// $("#formulario #VAL_MEDAC").val($("#ret_VAL_TOTAL").val());

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$('#VAL_EVOLUCAO,#VAL_MEDICAO').change(function() {
			$('#VAL_TOTAL').unmask();
			if ($('#VAL_EVOLUCAO').val() != '') {
				val_evolucao = parseFloat($('#VAL_EVOLUCAO').val().replace('.', '').replace(',', '.'));
			} else {
				val_evolucao = 0;
			}
			if ($('#VAL_MEDICAO').val() != '') {
				val_medicao = parseFloat($('#VAL_MEDICAO').val().replace('.', '').replace(',', '.'));
			} else {
				val_medicao = 0;
			}
			total = (val_evolucao * val_medicao).toFixed(2);
			$('#VAL_TOTAL').val(total).toString();
		});

	});

	function retornaForm(index) {
		$("#formulario #COD_RECEBIM").val($("#ret_COD_RECEBIM_" + index).val());
		$("#formulario #COD_OBJETOANEXO").val($("#ret_COD_RECEBIM_" + index).val());
		$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_" + index).val());
		$("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_" + index).val());
		$("#formulario #COD_MEDICAO").val($("#ret_COD_MEDICAO_" + index).val());
		$("#formulario #NUM_MEDICAO").val($("#ret_NUM_MEDICAO_" + index).val());
		$("#formulario #DAT_MEDICAO").val($("#ret_DAT_MEDICAO_" + index).val());
		$("#formulario #VAL_EVOLUCAO").val($("#ret_VAL_EVOLUCAO_" + index).val());
		$("#formulario #VAL_MEDICAO").val($("#ret_VAL_MEDICAO_" + index).val());
		$("#formulario #VAL_TOTAL").val($("#ret_VAL_TOTAL_" + index).val());
		$("#formulario #DES_NOMEBEM").val($("#ret_DES_NOMEBEM_" + index).val());
		$('.upload').prop('disabled', false).removeAttr('disabled');

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');

		refreshUpload();
	}

	function refresh_grid(){
		url = "ajxRecebimentoLista.do?id=<?=fnEncode($cod_empresa)?>&idCT=<?=fnEncode($cod_contrat)?>&idCN=<?=fnEncode($cod_conveni)?>&idC=<?=fnEncode($cod_cliente)?>";
		console.log(url);
		$.ajax({
			type: "POST",                
			url: url,
			data: {},
			beforeSend:function(){
				if ($.trim($("#tb_lista").html()) == ""){
					$("#tb_lista").html('<div class="loading" style="width: 100%;"></div>');
				}
				$("#tb_lista").addClass("loading_data");
			},
			success: function(data) {
				$("#tb_lista").html(data);
				$("#tb_lista").removeClass("loading_data");				
			}
		});
	}
</script>

<style>
	.loading_data *{
		color:#AAA;
		pointer-events:none;
	}
</style>

<?php include 'jsUploadConvenio.php'; ?>