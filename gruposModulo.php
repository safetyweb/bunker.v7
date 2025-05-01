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
$cod_grupomodmk = "";
$nom_grupomodmk = "";
$abv_grupomodmk = "";
$des_grupomodmk = "";
$des_icone = "";
$nom_submenus = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$abaModulo = "";
$arrayQuery = [];
$qrBuscaModulos = "";


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

		$cod_grupomodmk = fnLimpaCampoZero(@$_POST['COD_GRUPOMODMK']);
		$nom_grupomodmk = @$_POST['NOM_GRUPOMODMK'];
		$abv_grupomodmk = @$_POST['ABV_GRUPOMODMK'];
		$des_grupomodmk = @$_POST['DES_GRUPOMODMK'];
		$des_icone = @$_POST['DES_ICONE'];

		//fnEscreve($nom_submenus);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_GRUPOMODULOSMARKA (
				 '" . $cod_grupomodmk . "', 
				 '" . $nom_grupomodmk . "', 
				 '" . $abv_grupomodmk . "',
				 '" . $des_grupomodmk . "',
				 '" . $des_icone . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;
			//fnEscreve($sql);

			$arrayProc = mysqli_query($adm, trim($sql));

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
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

//fnMostraForm();

?>


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

				<?php $abaModulo = 1116;
				include "abasModulosMarka.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_GRUPOMODMK" id="COD_GRUPOMODMK" value="">
									</div>
								</div>

								<div class="col-md-5">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome</label>
										<input type="text" class="form-control input-sm" name="NOM_GRUPOMODMK" id="NOM_GRUPOMODMK">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="ABV_GRUPOMODMK" id="ABV_GRUPOMODMK">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Ícone</label><br />
										<button class="btn btn-sm btn-primary btnSearchIcon" id="btnIcon" style="min-height: 33px; margin-top: 1px;" data-icon=""></button>
										<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição</label>
										<input type="text" class="form-control input-sm" name="DES_GRUPOMODMK" id="DES_GRUPOMODMK">
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div id="divId_sub">
						</div>

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover table-sortable">
									<thead>
										<tr>
											<th class="bg-primary" width="40"></th>
											<th class="bg-primary" width="40"></th>
											<th class="bg-primary">Código</th>
											<th class="bg-primary">Nome do Submenu</th>
											<th class="bg-primary">Abreviação</th>
											<th class="bg-primary">Ícone</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT * FROM GRUPOMODULOSMARKA ORDER BY NUM_ORDENAC";

										$arrayQuery = mysqli_query($adm, trim($sql));

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
											<tr>
											  <td align='center'><span class='glyphicon glyphicon-move grabbable' data-id='" . $qrBuscaModulos['COD_GRUPOMODMK'] . "'></span></td>
											  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
											  <td>" . $qrBuscaModulos['COD_GRUPOMODMK'] . "</td>
											  <td>" . $qrBuscaModulos['NOM_GRUPOMODMK'] . "</td>
											  <td>" . $qrBuscaModulos['ABV_GRUPOMODMK'] . "</td>
											  <td align='center'><span class='fa  " . $qrBuscaModulos['DES_ICONE'] . "' ></td>															  
											</tr>
											<input type='hidden' id='ret_COD_GRUPOMODMK_" . $count . "' value='" . $qrBuscaModulos['COD_GRUPOMODMK'] . "'>
											<input type='hidden' id='ret_NOM_GRUPOMODMK_" . $count . "' value='" . $qrBuscaModulos['NOM_GRUPOMODMK'] . "'>
											<input type='hidden' id='ret_ABV_GRUPOMODMK_" . $count . "' value='" . $qrBuscaModulos['ABV_GRUPOMODMK'] . "'>
											<input type='hidden' id='ret_DES_ICONE_" . $count . "' value='" . $qrBuscaModulos['DES_ICONE'] . "'>
											<input type='hidden' id='ret_DES_GRUPOMODMK_" . $count . "' value='" . $qrBuscaModulos['DES_GRUPOMODMK'] . "'>
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

<link rel="stylesheet" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css" />

<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
	$(function() {

		$(".table-sortable tbody").sortable();

		$('.table-sortable tbody').sortable({
			handle: 'span'
		});

		$(".table-sortable tbody").sortable({

			stop: function(event, ui) {

				var Ids = "";
				$('table tr').each(function(index) {
					if (index != 0) {
						Ids = Ids + $(this).children().find('span.glyphicon').attr('data-id') + ",";
					}
				});

				//update ordenação
				//console.log(Ids.substring(0,(Ids.length-1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 6);

				function execOrdenacao(p1, p2) {
					//alert(p2);
					$.ajax({
						type: "GET",
						url: "ajxOrdenacao.php",
						data: {
							ajx1: p1,
							ajx2: p2
						},
						beforeSend: function() {
							//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							//$("#divId_sub").html(data); 
						},
						error: function() {
							$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
						}
					});
				}

			}

		});


		$(".table-sortable tbody").disableSelection();

	});
</script>

<script type="text/javascript">
	$(document).ready(function() {

		//arrastar 
		$('.grabbable').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);
		});

		$(".grabbable").click(function() {
			$(this).parent().addClass('selected').siblings().removeClass('selected');

		});

		//icon picker
		$('.btnSearchIcon').iconpicker({
			cols: 8,
			iconset: 'fontawesome',
			rows: 6,
			searchText: 'Procurar  &iacute;cone'
		});

		$('.btnSearchIcon').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);
		});

	});


	function retornaForm(index) {
		$("#formulario #COD_GRUPOMODMK").val($("#ret_COD_GRUPOMODMK_" + index).val());
		console.log($("#ret_COD_GRUPOMODMK_" + index).val());
		$("#formulario #NOM_GRUPOMODMK").val($("#ret_NOM_GRUPOMODMK_" + index).val());
		$("#formulario #ABV_GRUPOMODMK").val($("#ret_ABV_GRUPOMODMK_" + index).val());
		$("#formulario #DES_GRUPOMODMK").val($("#ret_DES_GRUPOMODMK_" + index).val());
		$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONE_" + index).val());
		$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>