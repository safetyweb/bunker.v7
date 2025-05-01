<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$opcao = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$des_grupotr = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaEmpresa = "";
$qrTemplate = "";
$qrBuscaFases = "";
$qrListaPersonas = "";
$temp = "";


//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_grupotr = fnLimpaCampoZero(@$_REQUEST['COD_GRUPOTR']);
		$des_grupotr = fnLimpaCampo(@$_REQUEST['DES_GRUPOTR']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			//fnMostraForm();

			//echo $sql;

			//mysqli_query($connAdm->connAdm(),trim($sql));				

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


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$sql = "DELETE FROM IMPORT_BLACKLIST WHERE COD_EMPRESA = $cod_empresa";
mysqli_query(connTemp($cod_empresa, ""), trim($sql));

//fnEscreve($cod_empresa);

?>

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script type="text/javascript" src="js/plugins/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode: "textareas",
		setup: function(ed) {
			// set the editor font size
			ed.onInit.add(function(ed) {
				ed.getBody().style.fontSize = '13px';
			});
		},
		language: "pt",
		theme: "advanced",
		plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1: "undo,redo,|,bold,italic,underline,strikethrough,nonbreaking,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,forecolor,backcolor,|,copy,paste,cut,|,pastetext,pasteword,|,search,replace,|,link,unlink,anchor,image,|,hr,removeformat,visualaid,|,cleanup,preview,print,code,fullscreen",
		theme_advanced_buttons2: "",
		theme_advanced_buttons3: "",
		theme_advanced_toolbar_location: "top",
		theme_advanced_toolbar_align: "left",
		theme_advanced_statusbar_location: "bottom",
		theme_advanced_resizing: true,

		// Example content CSS (should be your site CSS)
		//content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url: "lists/template_list.js",
		external_link_list_url: "lists/link_list.js",
		external_image_list_url: "lists/image_list.js",
		media_external_list_url: "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values: {
			username: "Some User",
			staffid: "991234"
		}
	});
</script>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg . " - " . $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1019";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php $abaEmpresa = 1025; ?>

				<div class="push30"></div>

				<style>
					.leitura2 {
						border: none transparent !important;
						outline: none !important;
						background: #fff !important;
						font-size: 18px;
						padding: 0;
					}

					.container-fluid .passo:not(:first-of-type) {
						display: none;
					}

					.wizard .col-md-2 {
						padding: 0;
					}

					.btn-circle {
						background-color: #DDD;
						opacity: 1 !important;
						border: 2px solid #efefef;
						height: 55px;
						width: 55px;
						margin-top: -23px;
						padding-top: 11px;
						border-radius: 50%;
						-moz-border-radius: 50%;
						-webkit-border-radius: 50%;
						color: #fff;
						font-size: 20px;
					}

					.fa-2x {
						font-size: 19px;
						margin-top: 5px;
					}

					.collapse-chevron .fa {
						transition: .3s transform ease-in-out;
					}

					.collapse-chevron .collapsed .fa {
						transform: rotate(-90deg);
					}

					.pull-right,
					.pull-left {
						margin-top: 3.5px;
					}

					.fundo {
						background: #D3D3D3;
						height: 10px;
						width: 100%;
					}

					.fundoAtivo {
						background: #2ed4e0;
					}

					.inicio {
						background: #2ed4e0;
						border-bottom-left-radius: 10px 7px;
						border-top-left-radius: 10px 7px;
					}

					.final {
						border-bottom-right-radius: 10px 7px;
						border-top-right-radius: 10px 7px;
					}

					.chosen-container {
						width: 100% !important;
					}
				</style>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="row text-center wizard setup-panel">
							<div class="col-md-2"></div>
							<div class="col-md-2" id="step1">
								<div class="fundo inicio">
									<a type="button" class="btn btn-circle fundoAtivo disabled" id="btn1"><span>1</span></a>
								</div><br><br>
								<p>Template</p>
							</div>
							<div class="col-md-2" id="step2">
								<div class="fundo">
									<a type="button" class="btn btn-circle disabled"><span>2</span></a>
								</div><br><br>
								<p>Persona</p>
							</div>
							<div class="col-md-2" id="step3">
								<div class="fundo">
									<a type="button" class="btn btn-circle disabled"><span>3</span></a>
								</div><br><br>
								<p>Confirmação</p>
							</div>
							<div class="col-md-2" id="step4">
								<div class="fundo final">
									<a type="button" class="btn btn-circle disabled"><span class="fas fa-check fa-2x"></span></a>
								</div><br><br>
								<p>Concluído</p>
							</div>
							<div class="col-md-2"></div>
						</div>

						<div class="container-fluid">

							<div class="passo" id="passo1">

								<div class="push30"></div>

								<div class="row">

									<div class="col-md-8 col-md-offset-2">

										<fieldset>
											<legend>Templates Existentes</legend>

											<div class="row">

												<div class="col-md-4 col-md-offset-4">

													<div class="form-group">
														<label for="inputName" class="control-label">Template</label>

														<select data-placeholder="Selecione a template desejada" name="COD_TEMPLATE" id="COD_TEMPLATE" class="chosen-select-deselect" tabindex="1" onchange="carregaInfo('template',this);">
															<option value=""></option>
															<?php

															$sql = "SELECT COD_TEMPLATE, NOM_TEMPLATE FROM TEMPLATE_EMAIL WHERE COD_EMPRESA = $cod_empresa";
															$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
															while ($qrTemplate = mysqli_fetch_assoc($arrayQuery)) {

																echo "
																								  <option value='" . $qrTemplate['COD_TEMPLATE'] . "'>" . $qrTemplate['NOM_TEMPLATE'] . "</option> 
																								";
															}

															?>
														</select>

													</div>

												</div>

											</div>

										</fieldset>

										<div class="push10"></div>

										<fieldset>
											<legend>Banco de Variáveis <small>(<b>Clique e arraste</b> a tag desejada ou <b>copie<b>na área desejada</b>)</small> </legend>

											<div class="row">

												<div class="col-md-12">
													<?php
													if ($cod_empresa == 39) {
														$sql = "select * from VARIAVEIS where COD_BANCOVAR in (3,33) order by NUM_ORDENAC";
													} else {
														$sql = "select * from VARIAVEIS order by NUM_ORDENAC";
													}
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

													$count = 0;
													while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
														$count++;
														echo "
																						<button class='btn btn-info btn-xs dragTag' draggable='true' style='margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;' dragTagName='" . $qrBuscaFases['KEY_BANCOVAR'] . "' onclick='quickCopy('" . $qrBuscaFases['ABV_BANCOVAR'] . "');'>" . $qrBuscaFases['ABV_BANCOVAR'] . "</button>
																						";
													}

													?>
												</div>

											</div>

										</fieldset>

										<div class="push10"></div>

										<fieldset>
											<legend>Editar Template</legend>

											<div class="row" id="templateConteudo">

												<div class="col-md-6">
													<div class="form-group">
														<label for="inputName" class="control-label required">Assunto (subject)</label>
														<input type="text" class="form-control input-sm" name="DES_ASSUNTO" id="DES_ASSUNTO" maxlength="100" value="" required>
													</div>
													<div class="help-block with-errors"></div>
												</div>

												<div class="col-md-6">
													<div class="form-group">
														<label for="inputName" class="control-label required">Remetente do e-Mail (from name)</label>
														<input type="text" class="form-control input-sm" name="DES_REMET" id="DES_REMET" maxlength="100" value="" required>
													</div>
													<div class="help-block with-errors"></div>
												</div>

												<div class="push30"></div>

												<div class="col-md-12">

													<textarea name="DES_TEMPLATE" id="DES_TEMPLATE" style="width: 100%; height: 90vh;"></textarea>

												</div>

											</div>

										</fieldset>

									</div>

								</div>

								<div class="push100"></div>

								<hr>

								<div class="col-md-10"></div>
								<div class="col-md-2">
									<button class="col-md-12 btn btn-primary next next1" name="next">Próximo<i class="fas fa-arrow-right pull-right"></i></button>
								</div>

								<div class="push10"></div>

							</div>

							<div class="passo" id="passo2" style="display: none;">

								<div class="push30"></div>

								<div class="row">

									<div class="col-md-4 col-md-offset-2">

										<div class="form-group">
											<label for="inputName" class="control-label required">Personas participantes</label>

											<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA_TKT" id="COD_PERSONA_TKT" class="chosen-select-deselect requiredChk" tabindex="1" required onchange="carregaInfo('relatorio',this);">
												<option value=""></option>
												<?php

												$sql = "select * from persona where cod_empresa = " . $cod_empresa . " and LOG_ATIVO = 'S' order by DES_PERSONA  ";
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

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Emails Extras</label>
											<input type="text" class="form-control input-sm" name="DES_EMAILEX" id="DES_EMAILEX" maxlength="500" value="">
										</div>
										<div class="help-block with-errors">Separar múltiplos emails por ";"</div>
									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-12">
										<div id="relatorioConteudo"></div>
									</div>

								</div>

								<div class="push50"></div>

								<hr>

								<div class="col-md-10"></div>
								<div class="col-md-2">
									<button class="col-md-12 btn btn-primary next2">Próximo<i class="fas fa-arrow-right pull-right"></i></button>
								</div>

								<div class="push10"></div>

							</div>

							<div class="passo" id="passo3">

								<div class="push50"></div>

								<div class="row">

									<div class="col-md-4"></div>

									<div class="col-md-4 text-center">
										<h4>Deseja Confirmar a operação?</h4>
									</div>

								</div>



								<div class="push100"></div>

								<hr>

								<div class="col-md-2">
									<a href="http://adm.bunker.mk/action.do?mod=<?= fnEncode(1457) ?>&id=<?= fnEncode($cod_empresa) ?>" class="col-md-12 btn btn-primary prev2"><i class="fas fa-repeat pull-left"></i>Reiniciar operação</a>
								</div>

								<div class="col-md-8"></div>

								<div class="col-md-2">
									<button class="col-md-12 btn btn-primary next3">Confirmar<i class="fas fa-check pull-right"></i></button>
								</div>


								<div class="push10"></div>

							</div>

							<div class="passo" id="passo4">

								<div class="push50"></div>

								<div class="row">

									<div class="col-md-4"></div>

									<div class="col-md-4 text-center">
										<h4>Operação executada com sucesso!</h4>
									</div>

								</div>

								<div class="push100"></div>

								<hr>

							</div>


							<div class="push10"></div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="QTD_EMAILOK" id="QTD_EMAILOK" value="">
							<input type="hidden" name="QTD_EMAILNOK" id="QTD_EMAILNOK" value="">
							<input type="hidden" name="QTD_CLIENTE" id="QTD_CLIENTE" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

					</form>

					<div class="push50"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	$('.dragTag').on('dragstart', function(event) {
		var tag = $(this).attr('dragTagName');
		event.originalEvent.dataTransfer.setData("text", ' ' + tag + ' ');
		event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
	});


	$('.dragTag').on('click', function(event) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val(" @" + $(this).text() + " ").select();
		document.execCommand("copy");
		$temp.remove();
	});




	function quickCopy(tag) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val("@" + tag + " ").select();
		document.execCommand("copy");
		$temp.remove();
	}

	function retornaForm(index) {
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$(document).ready(function() {

		$('.next1').click(function() {

			$.confirm({
				title: 'Confirmação',
				animation: 'opacity',
				closeAnimation: 'opacity',
				content: 'Criar template?',
				buttons: {
					confirmar: function() {

						$('#passo1').hide();
						$('#passo2').show();
						$("#step2 div.fundo, #step2 a.btn").addClass('fundoAtivo');

					},
					revisar: function() {

					},
				}
			});

		});

		$('.next2').click(function() {

			$('#passo2').hide();
			$('#passo3').show();
			$("#step3 div.fundo, #step3 a.btn").addClass('fundoAtivo');

		});

		$('.next3').click(function() {

			$.ajax({
				method: 'POST',
				url: 'ajxEnvioSimplesEmail.php',
				data: $('#formulario').serialize(),
				beforeSend: function() {
					$('#passo3').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$('#passo3').hide();
					$('#passo4').show();
					$("#step4 div.fundo, #step4 a.btn").addClass('fundoAtivo');
					console.log(data);
				}
			});

		});

	});

	function carregaInfo(tipo, input) {
		cod_acao = $(input).val();
		$.ajax({
			method: 'POST',
			url: "ajxClientesPersonaEmail.php?opcao=" + tipo,
			data: {
				COD_EMPRESA: '<?= $cod_empresa ?>',
				COD_ACAO: cod_acao
			},
			beforeSend: function() {
				$('#' + tipo + 'Conteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$('#' + tipo + 'Conteudo').html(data);
			}
		});
	}
</script>