<?php

//echo fnDebug('true');

$hashLocal = mt_rand();
$cod_template = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		if (empty($_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = $_REQUEST['LOG_ATIVO'];
		}

		$nom_template = fnLimpaCampo($_REQUEST['NOM_TEMPLATE']);
		$des_titulo = fnLimpaCampo($_REQUEST['DES_TITULO']);
		$abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
		$des_template = addslashes($_REQUEST['DES_TEMPLATE']);

		// fnEscreve(fnCHRHTML('<>/""~ç[]´'));
		// fnEscreve($des_template);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO TEMPLATE_PUSH(
												COD_EMPRESA,
												LOG_ATIVO,
												NOM_TEMPLATE,
												DES_TITULO,
												ABV_TEMPLATE,
												DES_TEMPLATE,
												COD_USUCADA
								   	  		)VALUES( 
												$cod_empresa,
												'$log_ativo',
												'$nom_template',
												'$des_titulo',
												'$abv_template',
												'$des_template',
												$cod_usucada
											)";
					// fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

					break;

				case 'ALT':

					$sql = "UPDATE TEMPLATE_PUSH SET
										LOG_ATIVO='$log_ativo',
										NOM_TEMPLATE='$nom_template',
										DES_TITULO='$des_titulo',
										ABV_TEMPLATE='$abv_template',
										DES_TEMPLATE='$des_template',
										DAT_ALTERAC=CONVERT_TZ(NOW(),'America/Sao_Paulo','America/Sao_Paulo'),
										COD_ALTERAC=$cod_usucada
								WHERE COD_TEMPLATE=$cod_template";

					// fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$sqlCod = "SELECT COD_TEMPLATE FROM TEMPLATE SMS WHERE COD_EMPRESA = $cod_empresa ORDER BY 1 DESC LIMIT 1";
					$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlCod));
					$cod_template = $qrCod[COD_TEMPLATE];

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

					break;

				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
			}
			//atualiza lista iframe				
?>
			<script>
				try {
					parent.$('#REFRESH_TEMPLATES').val("S");
				} catch (err) {}
				// alert('atualiza parent');
			</script>
<?php
			$msgTipo = 'alert-success';
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_campanha = fnDecode($_GET['idc']);
	$cod_tipo = fnDecode($_GET['tipo']);

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

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idT'])))) {
	$cod_template = fnDecode($_GET['idT']);
}

if ($cod_template != "") {

	//busca dados do convênio
	$sql = "SELECT * FROM TEMPLATE_PUSH WHERE COD_TEMPLATE = " . $cod_template;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
	$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaTemplate)) {
		$cod_template = $qrBuscaTemplate['COD_TEMPLATE'];
		if ($qrBuscaTemplate['LOG_ATIVO'] == 'S') {
			$checkAtivo = "checked";
		} else {
			$checkAtivo = "";
		}
		$nom_template = $qrBuscaTemplate['NOM_TEMPLATE'];
		$des_titulo = $qrBuscaTemplate['DES_TITULO'];
		$abv_template = $qrBuscaTemplate['ABV_TEMPLATE'];
		$des_template = $qrBuscaTemplate['DES_TEMPLATE'];
	}
} else {
	$checkAtivo = "";
	$nom_template = "";
	$des_titulo = "";
	$abv_template = "";
	$des_template = "";
}

$sqlGat = "SELECT TIP_GATILHO FROM GATILHO_PUSH
			   WHERE COD_EMPRESA = $cod_empresa
			   AND COD_CAMPANHA = $cod_campanha
			   LIMIT 1";

$qrGat = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

$tip_gatilho = fnlimpaCampo($qrGat['TIP_GATILHO']);

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

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome Template</label>
										<input type="text" class="form-control input-sm" name="NOM_TEMPLATE" id="NOM_TEMPLATE" value="<?php echo $nom_template ?>" maxlength="50">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação Template</label>
										<input type="text" class="form-control input-sm" name="ABV_TEMPLATE" id="ABV_TEMPLATE" value="<?php echo $abv_template ?>" maxlength="20">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Título da Template</label>
										<input type="text" class="form-control input-sm" name="DES_TITULO" id="DES_TITULO" value="<?php echo $des_titulo ?>" maxlength="199">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Ativo</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?= $checkAtivo ?>>
											<span></span>
										</label>
									</div>
								</div>

								<?php if ($cod_template != "" && $cod_template != 0) { ?>

									<div class="col-md-2">
										<div class="form-group">
											<div class="push20"></div>
											<a href="javascript:void(0)" class="btn btn btn-info" id="enviarTesteSimples" data-toggle='tooltip' data-placement='top' data-original-title='quick test'><span class="fal fa-paper-plane"></span></a>
										</div>
									</div>

								<?php } ?>

							</div>

							<div class="push10"></div>

							<?php if ($tip_gatilho != "individualB") { ?>

								<div class="row">

									<div class="col-md-12">
										<fieldset>
											<legend>Banco de Variáveis <small>(<b>Clique e arraste</b> a tag na área desejada ou <b>clique na tag para copiar</b>)</small> </legend>
											<?php

											//fnEscreve($cod_campanha);

											//busca dados da campanha
											$cod_campanha = fnDecode($_GET['idc']);
											$sql = "SELECT TIP_CAMPANHA FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
											$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

											if (isset($qrBuscaCampanha)) {
												$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
											}
											//fnEscreve($tip_campanha);
											//fnEscreve(1);

											// $sql = "select * from VARIAVEIS where COD_BANCOVAR in (3,23,39,41,44,45) order by NUM_ORDENAC";
											$sql = "select * from VARIAVEIS where LOG_SMS = 'S' order by NUM_ORDENAC";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

											while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
													dragTagName="<?= $qrBuscaFases[KEY_BANCOVAR] ?>"
													tamanho="<?= $qrBuscaFases["NUM_TAMSMS"] ?>"
													onclick="$(function(){quickCopy('<?= $qrBuscaFases[KEY_BANCOVAR] ?>')});">
													<span><?= $qrBuscaFases['ABV_BANCOVAR'] ?></span>
												</a>

												<?php
											}

											if ($tip_campanha == 20) {

												$sql2 = "select * from VARIAVEIS where COD_BANCOVAR in (33,34) order by NUM_ORDENAC";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql2) or die(mysqli_error());
												while ($qrBuscaFasesCupom = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
														dragTagName="<?= $qrBuscaFasesCupom[KEY_BANCOVAR] ?>"
														tamanho="<?= $qrBuscaFasesCupom["NUM_TAMSMS"] ?>"
														onclick="$(function(){quickCopy('<?= $qrBuscaFasesCupom[KEY_BANCOVAR] ?>')});">
														<span><?= $qrBuscaFasesCupom['ABV_BANCOVAR'] ?></span>
													</a>

											<?php
												}
											}


											?>
										</fieldset>
									</div>

								</div>

							<?php } ?>

							<div class="row">

								<div class="col-md-10">
									<div class="form-group">
										<label for="inputName" class="control-label">Mensagem</label>
										<textarea type="text" class="form-control input-sm" rows="3" name="DES_TEMPLATE" id="DES_TEMPLATE" value="" required><?php echo $des_template; ?></textarea>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<div class="form-group">
										<label for="inputName" class="control-label">Caracteres</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="nType" id="nType" value="130">
									</div>
								</div>

							</div>

							<div class="push10"></div>

						</fieldset>


						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
							<?php
							if ($cod_tipo == 'CAD') {
							?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<?php
							} else {
							?>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<?php
							}
							?>

							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?php echo $cod_template ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

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
<div class="modal fade" id="popModalEnvio" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form id="envioTeste" action="">
					<fieldset>
						<legend>Dados do envio</legend>

						<div class="row">

							<div class="col-md-10">
								<div class="form-group">
									<label for="inputName" class="control-label">Celulares (com DDD)</label>
									<input type="text" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="400">
									<div class="help-block with-errors">Separar múltiplos celulares com ";"</div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="push10"></div>
								<div class="push5"></div>
								<a href="javascript:void(0)" id="dispararTeste" class="btn btn-primary btn-sm btn-block getBtn" style="margin-top: 2px;"><i class="fal fa-paper-plane" aria-hidden="true"></i>&nbsp; Envio de teste</a>
							</div>

							<input type="hidden" name="COD_TEMPLATE_ENVIO" id="COD_TEMPLATE_ENVIO" value="<?= $cod_template ?>">
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						</div>

					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(function() {

		$('.dragTag').on('dragstart', function(event) {
			var tag = $(this).attr('dragTagName');
			event.originalEvent.dataTransfer.setData("text", ' ' + tag + ' ');
			event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
		});

		$('.dragTag').on('dragend', function(event) {
			updateCount($('#DES_TEMPLATE'));
		});

		$('.dragTag').on('click', function(event) {
			var $temp = $("<input>");
			$("#tosave").append($temp);
			$temp.val($(this).text()).select();
			document.execCommand("copy");
			$temp.remove();
		});

		$("#enviarTesteSimples").click(function() {

			$("#popModalEnvio").modal();

		});

		$("#dispararTeste").click(function() {
			$("#envioTeste #DES_TEMPLATE_ENVIO").val($("#DES_TEMPLATE").val());
			if ($("#NUM_CELULAR").val().trim() != "") {

				envioTeste();

			} else {

				$.alert({
					title: "Aviso",
					content: "O campo de celulares não pode ser vazio",
					type: 'orange',
					buttons: {
						"OK": {
							btnClass: 'btn-blue',
							action: function() {

							}
						}
					},
					backgroundDismiss: true
				});

			}
		});


	});

	updateCount($('#DES_TEMPLATE'));
	$('#DES_TEMPLATE').keyup(function() {
		updateCount(this)
	});
	$('#DES_TEMPLATE').keydown(function() {
		updateCount(this)
	});
	$('#DES_TEMPLATE').change(function() {
		updateCount(this)
	});

	function envioTeste() {
		$.ajax({
			method: 'POST',
			url: 'ajxEnvioTesteSimplesSms.do?id=<?= fnEncode($cod_empresa) ?>',
			data: {
				DES_TEMPLATE: $("#DES_TEMPLATE").val(),
				COD_TEMPLATE: $("#COD_TEMPLATE").val(),
				NUM_CELULAR: $("#NUM_CELULAR").val()
			},
			beforeSend: function() {
				$("#dispararTeste").html("<center><div class='loading' style='width:50%'></div></center>");
			},
			success: function(data) {

				$("#dispararTeste").html("<span class='fas fa-check'></span>&nbsp;Teste enviado")
					.removeClass("btn-primary")
					.addClass("btn-success")
					.attr('disabled', true)
					.attr('id', 'disparadoTeste');

				setInterval(function() {
					$("#disparadoTeste").fadeOut('fast')
						.html("<span class='fal fa-paper-plane'></span>&nbsp;Envio de teste")
						.removeClass("btn-success")
						.addClass("btn-primary")
						.attr('disabled', false)
						.attr('id', 'dispararTeste')
						.fadeIn('fast');
				}, 15000);

				$.alert({
					title: "Sucesso",
					content: "O seu teste foi enviado! Verifique seu email (essa operação pode levar alguns minutos).",
					type: 'green',
					buttons: {
						"OK": {
							btnClass: 'btn-blue',
							action: function() {

							}
						}
					},
					backgroundDismiss: true
				});

				console.log(data);

			},
			error: function() {

				console.log("erro 500");

			}
		});
	}

	function updateCount(id) {
		var max = 130;
		var cs = $(id).val().length;
		var cr = $(id).val().length;

		var tags = $("#DES_TEMPLATE").val().match(new RegExp('(\<(/?[^\>]+)\>)', 'gim'));
		if (tags != null && tags != undefined) {
			$.each(tags, function(index, value) {
				if ($("a[dragtagname='" + value + "']").length) {
					var tam = $("a[dragtagname='" + value + "']").attr("tamanho");
					cs = parseInt(cs) - parseInt(value.length) + parseInt(tam);
				}
			});
		}

		console.log('max: ' + max, 'tamanho: ' + cs, 'restante: ' + (max - cs));
		//$("#DES_TEMPLATE").attr("maxlength",max - cs + cr);
		$('#nType').val(max - cs);
		if (max < cs) {
			$('#nType').addClass("text-danger");
			$("#CAD").attr("disabled", true);
			$("#CAD").attr("title", "O texto ultrapassou o limite de caracteres!");
			$("#ALT").attr("disabled", true);
			$("#ALT").attr("title", "O texto ultrapassou o limite de caracteres!");
		} else {
			$('#nType').removeClass("text-danger");
			$("#CAD").attr("disabled", false);
			$("#CAD").removeAttr("title");
			$("#ALT").attr("disabled", false);
			$("#ALT").removeAttr("title");
		}
	}

	function quickCopy(tag) {
		var dummyContent = tag;
		var dummy = $('<input>').val(dummyContent).appendTo('body');
		dummy.select();
		document.execCommand('copy');
		dummy.remove();
	}
</script>