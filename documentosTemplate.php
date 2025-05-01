	<?php

	if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
		echo fnDebug('true');
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

	$log_ativo = "";
	$cod_documen = "";
	$hashLocal = "";
	$msgRetorno = "";
	$msgTipo = "";
	$nom_documen = "";
	$cod_tipodoc = "";
	$nom_tipodoc = "";
	$abv_documento = "";
	$des_descricao = "";
	$hHabilitado = "";
	$hashForm = "";
	$cod_usucada = "";
	$cod_template = "";
	$nom_template = "";
	$abv_template = "";
	$des_template = "";
	$arrayQuery = [];
	$qrBuscaEmpresa = "";
	$nom_empresa = "";
	$cod_checkli = "";
	$qrBuscaModulos = [];

	$log_ativo = 'N';

	$cod_documen = "";

	$hashLocal = mt_rand();

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$request = md5(serialize($_POST));

		if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		} else {
			$_SESSION['last_request']  = $request;

			$cod_documen = fnLimpaCampoZero(@$_REQUEST['COD_DOCUMEN']);
			$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
			$nom_documen = fnLimpaCampo(@$_REQUEST['NOM_DOCUMEN']);
			$cod_tipodoc = fnLimpaCampo(@$_REQUEST['COD_TIPODOC']);
			$nom_tipodoc = fnLimpaCampo(@$_REQUEST['NOM_TIPODOC']);
			$abv_documento = fnLimpaCampo(@$_REQUEST['DES_ABREVIA']);
			$des_descricao = fnLimpaCampo(@$_REQUEST['DES_DESCRICAO']);
			// if (empty(@$_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=@$_REQUEST['LOG_ATIVO'];}


			$opcao = @$_REQUEST['opcao'];
			$hHabilitado = @$_REQUEST['hHabilitado'];
			$hashForm = @$_REQUEST['hashForm'];

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			if ($opcao != '') {

				$sql = "CALL SP_ALTERA_TEMPLATE (
				 '" . $cod_template . "', 
				 '" . $cod_empresa . "',
				 '" . $log_ativo . "', 
				 '" . $nom_template . "', 
				 '" . $abv_template . "',
				 '" . $des_template . "',
				 '" . $cod_usucada . "',
				 '" . $opcao . "'    
			        );";

				//fnEscreve($sql);
				// mysqli_query(connTemp($cod_empresa,''),$sql);				

				//mensagem de retorno
				switch ($opcao) {
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
						break;
						break;
				}
				$msgTipo = 'alert-success';
			}
		}
	}

	// fnEscreve($opcao);

	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {

		//busca dados da empresa
		$cod_empresa = fnDecode(@$_GET['id']);
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

	//fnEscreve($cod_checkli);

	?>
	<link rel="stylesheet" href="css/widgets.css" />
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
					<?php include "atalhosPortlet.php"; ?>
				</div>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<div class="push50"></div>

					<style>
						.change-icon .fa+.fa,
						.change-icon:hover .fa:not(.fa-edit) {
							display: none;
						}

						.change-icon:hover .fa+.fa:not(.fa-edit) {
							display: inherit;
						}

						.fa-edit:hover {
							color: #18bc9c;
							cursor: pointer;
						}

						.item {
							padding-top: 0;
						}
					</style>

					<div class="col-md-2">

						<div class="panelBox borda">

							<div class="addBox" data-url="action.php?mod=<?php echo fnEncode(1891) ?>&id=<?php echo fnEncode($cod_empresa) ?>&tipo=<?php echo fnEncode('CAD') ?>&pop=true" data-title="Documento">
								<i class="fal fa-plus fa-2x" aria-hidden="true" style="margin: 55px 0 60px 0;"></i>
							</div>
						</div>

					</div>
					<div id="listaDocumentos">
						<?php
						$sql = "SELECT * FROM DOCUMENTOS WHERE cod_empresa = $cod_empresa AND COD_EXCLUSA = 0 ORDER BY NOM_DOCUMEN";

						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

						// Se houver erro na consulta, exibe mensagem de erro
						if (!$arrayQuery || mysqli_num_rows($arrayQuery) === 0) {
							echo "<p style='text-align: center; color: red; font-size: 16px; margin-top: 20px;'>Não há documentos ou ocorreu um erro na busca.</p>";
						} else {

							while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
						?>
								<div class="col-md-2">
									<div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
										<a data-url="action.php?mod=<?php echo fnEncode(1891) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idD=<?php echo fnEncode($qrBuscaModulos['COD_DOCUMEN']); ?>&tipo=<?php echo fnEncode('ALT') ?>&pop=true" data-title="Documento" class="informer informer-default addBox" style="color: #2c3e50;">
											<span class="fal fa-edit"></span>
										</a>
										<a href='action.php?mod=<?php echo fnEncode(1892) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idD=<?php echo fnEncode($qrBuscaModulos['COD_DOCUMEN']) ?>' style='color: #2c3e50; border: none; text-decoration: none;'>
											<div class="push30"></div>
											<i class="fal fa-file-alt fa-lg" style="font-size: 40px"></i>
											<div class="push20"></div>
											<p class="folder"><?php echo $qrBuscaModulos['NOM_DOCUMEN']; ?></p>
										</a>
									</div>
								</div>
						<?php
							}
						}
						?>
					</div>


					<input type="hidden" class="input-sm" name="REFRESH_DOCUMENTOS" id="REFRESH_DOCUMENTOS" value="N">
					<input type="hidden" name="COD_TIPODOC" id="COD_TIPODOC" value="">

					<!-- modal -->
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

					<script type="text/javascript">
						$(document).ready(function() {

							//modal close
							$('.modal').on('hidden.bs.modal', function() {
								console.log('entrou');
								if ($('#REFRESH_DOCUMENTOS').val() == "N") {
									// alert("atualiza");
									RefreshDocumentos(<?php echo $cod_empresa; ?>, <?php echo $cod_documen; ?>);
									$('#REFRESH_DOCUMENTOS').val("N");
								}
							});

						});

						function RefreshDocumentos(idEmp) {
							$.ajax({
								type: "GET",
								url: "ajxRefreshDocumentos.php",
								data: {
									ajx1: idEmp
								},
								beforeSend: function() {
									$('#listaDocumentos').html('<div class="loading" style="width: 100%;"></div>');
								},
								success: function(data) {
									$("#listaDocumentos").html(data);
								},
								error: function() {
									$('#listaDocumentos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
								}
							});
						}

						function retornaForm(index) {
							// $("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
							// $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
							// $("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val()).trigger("chosen:updated");
							// $("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_"+index).val());
							// $("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_"+index).val());
							// $("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_"+index).val());
							// $("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_"+index).val());
							// $("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_"+index).val());
							// $("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_"+index).val());
							// $("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_"+index).val());
							// $("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_"+index).val());
							// $("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_"+index).val());
							// $("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_"+index).val());
							// $('#formulario').validator('validate');			
							// $("#formulario #hHabilitado").val('S');			
						}
					</script>