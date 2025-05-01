<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_propriedade = fnLimpaCampoZero($_REQUEST['COD_PROPRIEDADE']);
		$cod_hotel = fnLimpaCampoZero($_REQUEST['COD_HOTEL']);
		$nom_propriedade = fnLimpaCampo($_REQUEST['NOM_PROPRIEDADE']);
		$tip_propriedade = fnLimpaCampo($_REQUEST['TIP_PROPRIEDADE']);
		$des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);
		$des_propriedade = str_replace('"', "´´", str_replace("'", "´",$_REQUEST['DES_PROPRIEDADE']));
		$cod_empresa = 274;
		$des_contrato = str_replace('"', "´´", str_replace("'", "´", $_REQUEST['DES_CONTRATO']));
		$meta_title = fnLimpaCampo($_REQUEST['META_TITLE']);
		$meta_description = fnLimpaCampo($_REQUEST['META_DESCRIPTION']);
		$h1_propriedade = fnLimpaCampo($_REQUEST['H1_PROPRIEDADE']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			switch ($opcao) {

				case 'CAD':
					$sqlCad = "INSERT INTO ADORAI_PROPRIEDADES(
												COD_EMPRESA,
												COD_HOTEL,
												NOM_PROPRIEDADE,
												TIP_PROPRIEDADE,
												DES_IMAGEM,
												DES_PROPRIEDADE,
												DES_CONTRATO,
												META_TITLE,
												META_DESCRIPTION,
												H1_PROPRIEDADE,
												COD_USUCADA
											)VALUES(
												$cod_empresa,
												$cod_hotel,
												'$nom_propriedade',
												'$tip_propriedade',
												'$des_imagem',
												'$des_propriedade',
												'$des_contrato',
												'$meta_title',
												'$meta_description',
												'$h1_propriedade',
												$cod_usucada
											)";

					//fnescreve($sqlCad);


					//fnTestesql(connTemp($cod_empresa),$sqlCad);				
					$arrayProc = mysqli_query(conntemp($cod_empresa, ''), $sqlCad);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlCad, $nom_usuario);
					}
					break;
				case 'ALT':
					$sqlAlt = "UPDATE ADORAI_PROPRIEDADES SET
													COD_HOTEL = $cod_hotel,
													NOM_PROPRIEDADE = '$nom_propriedade',
													TIP_PROPRIEDADE = '$tip_propriedade',
													DES_IMAGEM = '$des_imagem',
													DES_PROPRIEDADE = '$des_propriedade',
													META_DESCRIPTION = '$meta_description',
													META_TITLE = '$meta_title',
													H1_PROPRIEDADE = '$h1_propriedade',
													DES_CONTRATO = '$des_contrato',
													COD_ALTERAC = $cod_usucada
							WHERE COD_PROPRIEDADE = $cod_propriedade
							AND COD_EMPRESA = $cod_empresa";

					// fnEscreve($sqlAlt);

					$arrayAlt = mysqli_query(conntemp($cod_empresa, ''), $sqlAlt);

					if (!$arrayAlt) {

						$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAlt, $nom_usuario);
					}
					break;
				case 'EXC':
					$sqlExc = "UPDATE ADORAI_PROPRIEDADES SET
													COD_EXCLUSA = $cod_usucada,
													DAT_EXCLUSA = NOW()
							WHERE COD_PROPRIEDADE = $cod_propriedade
							AND COD_EMPRESA = $cod_empresa";
					$arrayExc = mysqli_query(conntemp($cod_empresa, ''), $sqlExc);

					if (!$arrayExc) {

						$cod_erro = Log_error_comand($adm, conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlExc, $nom_usuario);
					}
					break;
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

$cod_empresa = 274;

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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1019";
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

				<?php
				$abaAdorai = 1833;
				include "abasAdorai.php";

				$abaManutencaoAdorai = fnDecode($_GET['mod']);
				//echo $abaUsuario;

				//se não for sistema de campanhas

				echo ('<div class="push20"></div>');
				include "abasManutencaoAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Hotel</label>
										<select data-placeholder="Selecione um Hotel" name="COD_HOTEL" id="COD_HOTEL" class="chosen-select-deselect" style="width:100%;" required>
											<option value=""></option>
											<?php $sql = "SELECT NOM_UNIVEND,COD_EXTERNO FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa";

											$arrayHotel = mysqli_query(conntemp($cod_empresa, ''), $sql);

											while ($qrHoteis = mysqli_fetch_assoc($arrayHotel)) {
												echo "<option value='" . $qrHoteis['COD_EXTERNO'] . "'>" . $qrHoteis['NOM_UNIVEND'] . "</option>";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo de propriedade</label>
										<select data-placeholder="Selecione uma opção" name="TIP_PROPRIEDADE" id="TIP_PROPRIEDADE" class="chosen-select-deselect" style="width:100%;" required>
											<option value=""></option>
											<option value="1">Campo</option>
											<option value="2">Serra</option>
											<option value="3">Praia</option>
											<option value="4">Montanha</option>
										</select>
										<script>
											$("#formulario #TIP_PROPRIEDADE").val("<?php echo $tip_propriedade; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome da Propriedade</label>
										<input type="text" class="form-control input-sm" name="NOM_PROPRIEDADE" id="NOM_PROPRIEDADE" maxlength="60" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Imagem</label>
										<input type="text" class="form-control input-sm" name="DES_IMAGEM" id="DES_IMAGEM" maxlength="250">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">H1 Página de Propriedade</label>
										<input type="text" class="form-control input-sm" name="H1_PROPRIEDADE" id="H1_PROPRIEDADE" maxlength="150">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label">Meta Titulo</label>
										<input type="text" class="form-control input-sm" name="META_TITLE" id="META_TITLE" maxlength="200">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-5">
									<div class="form-group">
										<label for="inputName" class="control-label">Meta Descrição</label>
										<input type="text" class="form-control input-sm" name="META_DESCRIPTION" id="META_DESCRIPTION" maxlength="200">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição:</label>
										<textarea class=" form-control input-sm" rows="4" name="DES_PROPRIEDADE" id="DES_PROPRIEDADE" maxlength="1000"></textarea>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Contrato:</label>
										<textarea name="DES_CONTRATO" id="DES_CONTRATO" style="width: 100%; height: 240px;"><?php echo $des_regras; ?></textarea>
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
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_PROPRIEDADE" id="COD_PROPRIEDADE" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th class="{ sorter: false }" width="40"></th>
											<th>Código</th>
											<th>Hotel</th>
											<th>Propriedade</th>
											<th>H1 Propriedade</th>
											<th class="text-center { sorter: false }">Imagem</th>
											<!-- <th class="{ sorter: false }" width="40"></th> -->
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT AC.*, UV.NOM_FANTASI, UV.COD_EXTERNO AS ID_HOTEL FROM ADORAI_PROPRIEDADES AC
												LEFT JOIN UNIDADEVENDA UV ON UV.COD_EXTERNO = AC.COD_HOTEL
												WHERE AC.COD_EMPRESA = $cod_empresa
												AND AC.COD_EXCLUSA = 0 
												ORDER BY AC.COD_HOTEL, NOM_PROPRIEDADE";
										$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

										//fnEscreve($sql);

										$count = 0;
										while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

											$count++;
											$imagem = "";

											if (trim($qrLista['DES_IMAGEM']) != "") {
												$imagem = "<span class='fal fa-check text-success'></span>";
											}

										?>
											<tr>
												<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(<?= $count ?>)'></th>
												<td><?= $qrLista['COD_PROPRIEDADE'] ?></td>
												<td><?= $qrLista['NOM_FANTASI'] ?></td>
												<td><?= $qrLista['NOM_PROPRIEDADE'] ?></td>
												<td><?= $qrLista['H1_PROPRIEDADE'] ?></td>
												<td class="text-center"><?= $imagem ?></td>
											</tr>
											<div id="AREACODE_OFF_<?php echo $count; ?>" style="display: none;">
												<textarea id="AREACODE_<?php echo $count; ?>" rows="1" style="width: 100%;">https://reservas.roteirosadorai.com.br/detalhes.php?idh=<?= $qrLista['ID_HOTEL'] ?>&idc=<?= $qrLista['COD_EXTERNO'] ?></textarea>
												<input type='hidden' id='ret_COD_PROPRIEDADE_<?= $count ?>' value='<?= $qrLista['COD_PROPRIEDADE'] ?>'>
												<input type='hidden' id='ret_COD_HOTEL_<?= $count ?>' value='<?= $qrLista['COD_HOTEL'] ?>'>
												<input type='hidden' id='ret_NOM_PROPRIEDADE_<?= $count ?>' value='<?= $qrLista['NOM_PROPRIEDADE'] ?>'>
												<input type='hidden' id='ret_TIP_PROPRIEDADE_<?= $count ?>' value='<?= $qrLista['TIP_PROPRIEDADE'] ?>'>
												<input type='hidden' id='ret_DES_IMAGEM_<?= $count ?>' value='<?= $qrLista['DES_IMAGEM'] ?>'>
												<input type='hidden' id='ret_DES_PROPRIEDADE_<?= $count ?>' value='<?= $qrLista['DES_PROPRIEDADE'] ?>'>
												<input type='hidden' id='ret_DES_CONTRATO_<?= $count ?>' value='<?= $qrLista['DES_CONTRATO'] ?>'>
												<input type='hidden' id='ret_META_DESCRIPTION_<?= $count ?>' value='<?= $qrLista['META_DESCRIPTION'] ?>'>
												<input type='hidden' id='ret_H1_PROPRIEDADE_<?= $count ?>' value='<?= $qrLista['H1_PROPRIEDADE'] ?>'>
												<input type='hidden' id='ret_META_TITLE_<?= $count ?>' value='<?= $qrLista['META_TITLE'] ?>'>
											<?php
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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>


<script type="text/javascript">
	function copiaLink(index) {
		$("#AREACODE_OFF_" + index).show();
		$("#AREACODE_" + index).select();
		document.execCommand('copy');
		$('.bt' + index).fadeOut(function() {
			// $('.bt'+index).css('background','#2C3E50');
			$('.bt' + index).text('Link copiado');
			$('.bt' + index).fadeIn(200);
		});

		$("#AREACODE_OFF_" + index).hide();
	}
	// $(document).ready(function(){
	// 	// TextArea
	// 		$(".editor").jqte(
	// 			{sup: false,
	// 			sub: false,
	// 			outdent: false,
	// 			indent: false,
	// 			left: false,
	//     		center: false,
	//     		color: false,
	//     		right: false,
	//     		strike: false,
	//     		source: false,
	// 	        link:false,
	// 	        unlink: false,		        
	// 	        remove: false,
	// 	    	rule: false,
	// 	    	fsize: false,
	// 	    	format: false,
	// 	    	});
	// });

	function retornaForm(index) {
		$("#formulario #COD_PROPRIEDADE").val($("#ret_COD_PROPRIEDADE_" + index).val());
		$("#formulario #COD_HOTEL").val($("#ret_COD_HOTEL_" + index).val()).trigger("chosen:updated");
		$("#formulario #NOM_PROPRIEDADE").val($("#ret_NOM_PROPRIEDADE_" + index).val());
		$("#formulario #TIP_PROPRIEDADE").val($("#ret_TIP_PROPRIEDADE_" + index).val()).trigger("chosen:updated");
		$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
		$("#formulario #META_DESCRIPTION").val($("#ret_META_DESCRIPTION_" + index).val());
		$("#formulario #H1_PROPRIEDADE").val($("#ret_H1_PROPRIEDADE_" + index).val());
		$("#formulario #META_TITLE").val($("#ret_META_TITLE_" + index).val());
		tinyMCE.get('DES_PROPRIEDADE').setContent($("#ret_DES_PROPRIEDADE_" + index).val());
		tinyMCE.get('DES_CONTRATO').setContent($("#ret_DES_CONTRATO_" + index).val());
		// $(".editor").jqteVal($("#ret_DES_CONTRATO_" + index).val());

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>