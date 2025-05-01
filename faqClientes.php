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
$cod_pergunta = "";
$des_pergunta = "";
$des_resposta = "";
$num_ordenac = "";
$nom_submenus = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$abaEmpresa = "";
$abaAdorai = "";
$abaManutencaoAdorai = "";
$des_regras = "";
$qrBuscaFAQ = "";


$hashLocal = mt_rand();

$cod_empresa = 274;

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_pergunta = fnLimpaCampoZero(@$_REQUEST['COD_PERGUNTA']);
		$des_pergunta = @$_REQUEST['DES_PERGUNTA'];
		$des_resposta = addslashes(htmlentities(@$_REQUEST['DES_RESPOSTA']));
		$num_ordenac = @$_REQUEST['NUM_ORDENAC'];
		$cod_empresa = @$_REQUEST['COD_EMPRESA'];

		//fnEscreve($nom_submenus);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_PERGUNTAS (
				 '" . $cod_pergunta . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_pergunta . "', 
				 '" . $des_resposta . "', 
				 '" . $opcao . "'    
				) ";

			// fnEscreve($sql);				
			$arrayProc = mysqli_query(conntemp($cod_empresa, ""), $sql);

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
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
	$cod_empresa = 274;
	//fnEscreve('entrou else');
}

//fnMostraForm();

?>


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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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

				<?php
				if (fndecode(@$_GET['mod']) == 1167) {
					$abaEmpresa = 1167;
					include "abasEmpresaConfig.php";
				}

				//faq - isolado adorai
				if (fndecode(@$_GET['mod']) == 1853) {
					$abaAdorai = 1833;
					include "abasAdorai.php";

					$abaManutencaoAdorai = 1853;
					echo ('<div class="push20"></div>');
					include "abasManutencaoAdorai.php";
				}

				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PERGUNTA" id="COD_PERGUNTA" value="">
									</div>
								</div>

								<div class="col-md-7">
									<div class="form-group">
										<label for="inputName" class="control-label required">Pergunta</label>
										<input type="text" class="form-control input-sm" name="DES_PERGUNTA" id="DES_PERGUNTA" maxlength="250" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>

						<fieldset>
							<legend>Resposta</legend>

							<div class="row">

								<div class="col-md-12">

									<textarea name="DES_RESPOSTA" id="DES_RESPOSTA" style="width: 100%; height: 240px;"><?php echo $des_regras; ?></textarea>

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
						<input type="hidden" name="DES_TIPOFAQ" id="DES_TIPOFAQ" value="EXT">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div id="divId_sub">
						</div>

						<div class="no-more-tables">

							<form name="formLista" id="formLista">

								<table class="table table-bordered table-sortable table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th class="{ sorter: false }" width="40"></th>
											<th class="{ sorter: false }" width="40"></th>
											<th>Código</th>
											<th>Pergunta</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT * FROM PERGUNTAS WHERE COD_EMPRESA = $cod_empresa order by NUM_ORDENAC";
										$arrayQuery = mysqli_query(conntemp($cod_empresa, ""), $sql);

										$count = 0;
										while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
													<tr>
														<td class='text-center'><span class='ordernacao glyphicon glyphicon-move grabbable' data-id='" . $qrBuscaFAQ['COD_PERGUNTA'] . "'></span></td>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaFAQ['COD_PERGUNTA'] . "</td>
														<td>" . $qrBuscaFAQ['DES_PERGUNTA'] . "</td>
													</tr>
													<input type='hidden' id='ret_COD_PERGUNTA_" . $count . "' value='" . $qrBuscaFAQ['COD_PERGUNTA'] . "'>
													<input type='hidden' id='ret_DES_PERGUNTA_" . $count . "' value='" . $qrBuscaFAQ['DES_PERGUNTA'] . "'>
													<input type='hidden' id='ret_DES_RESPOSTA_" . $count . "' value='" . $qrBuscaFAQ['DES_RESPOSTA'] . "'>
													<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBuscaFAQ['NUM_ORDENAC'] . "'>
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
				$('.ordernacao').each(function(index) {
					Ids = Ids + $(this).attr('data-id') + ",";
				});

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				execOrdenacao(arrayOrdem, 4);

				function execOrdenacao(p1, p2) {
					//alert(p1);
					$.ajax({
						type: "GET",
						url: "ajxOrdenacaoEmp.php",
						data: {
							ajx1: p1,
							ajx2: p2,
							ajx3: <?php echo $cod_empresa ?>
						},
						beforeSend: function() {
							//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							$("#divId_sub").html(data);
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

	});


	function retornaForm(index) {
		$("#formulario #COD_PERGUNTA").val($("#ret_COD_PERGUNTA_" + index).val());
		$("#formulario #DES_PERGUNTA").val($("#ret_DES_PERGUNTA_" + index).val());
		tinyMCE.getInstanceById('DES_RESPOSTA').execCommand('mceSetContent', false, eval('document.getElementById("formLista").ret_DES_RESPOSTA_' + index + '.value'));
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>