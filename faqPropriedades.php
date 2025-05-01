<!-- CREATE TABLE PERGUNTAS_ADORAI (
    COD_PERGUNTA INT PRIMARY KEY AUTO_INCREMENT,
    COD_EMPRESA INT NOT NULL DEFAULT '0',
    COD_PROPRIEDADE INT NOT NULL DEFAULT '0',
    COD_ACOMODACAO INT NOT NULL DEFAULT '0',
    DES_PERGUNTA VARCHAR(500),
    DES_RESPOSTA TEXT,
    NUM_ORDENAC INT NOT NULL DEFAULT '0',
    LOG_ATIVO CHAR(1) NOT NULL DEFAULT 'S',
    COD_USUCADA INT,
    DAT_CADASTR TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    COD_ALTERAC INT,
    DAT_ALTERAC DATETIME,
    COD_EXCLUSA INT,
    DAT_EXCLUSA DATETIME
); -->

<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$cod_empresa = 274;

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		
		$log_ativo = 'S';

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usuario = $_SESSION["SYS_COD_USUARIO"];

		$cod_pergunta = fnLimpaCampoZero($_REQUEST['COD_PERGUNTA']);
		$des_pergunta = $_REQUEST['DES_PERGUNTA'];
		$des_resposta = addslashes(htmlentities($_REQUEST['DES_RESPOSTA']));
		$num_ordenac = $_REQUEST['NUM_ORDENAC'];
		$cod_empresa = $_REQUEST['COD_EMPRESA'];
		$cod_propriedade = $_REQUEST['COD_PROPRIEDADE'];
		$cod_acomodacao = $_REQUEST['COD_ACOMODACAO'];
		$des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);
		// fnEscreve($nom_submenus);
		// fnEscreve($num_ordenac);
		// fnEscreve($num_usuario);

		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			// $sql = "CALL SP_ALTERA_PERGUNTAS (
			// 	 '" . $cod_pergunta . "', 
			// 	 '" . $cod_empresa . "', 
			// 	 '" . $des_pergunta . "', 
			// 	 '" . $des_resposta . "', 
			// 	 '" . $opcao . "'    
			// 	) ";

			// // fnEscreve($sql);				
			// $arrayProc = mysqli_query(conntemp($cod_empresa,""), $sql);


			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$sqlPerguntas = "SELECT MAX(NUM_ORDENAC) AS NUM_ORDENAC FROM PERGUNTAS_ADORAI WHERE COD_PROPRIEDADE = $cod_propriedade AND COD_ACOMODACAO = $cod_acomodacao";
					$perguntaquery = mysqli_query(connTemp($cod_empresa, ''), $sqlPerguntas);
					
					$qrPergunta = mysqli_fetch_assoc($perguntaquery);
					$num_ordenac = $qrPergunta['NUM_ORDENAC'] + 1;
					
					$sql = "INSERT INTO PERGUNTAS_ADORAI (
						COD_EMPRESA,
						COD_PROPRIEDADE,
						COD_ACOMODACAO,
						DES_PERGUNTA,
						DES_RESPOSTA,
						NUM_ORDENAC,
						LOG_ATIVO,
						DES_IMAGEM,
						COD_USUCADA,
						DAT_CADASTR
					)
					VALUES (
						$cod_empresa,
						$cod_propriedade,
						0,
						'$des_pergunta',
						'$des_resposta',
						$num_ordenac,
						'$log_ativo',
						'$des_imagem',
						$cod_usuario,
						NOW()
						)
						";
			// fnEscreve($sql);

			$arrayProc = mysqli_query(conntemp($cod_empresa,""), $sql);
			
			// if (!$arrayProc) {

			// 	$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
			// }

// COD_PERGUNTA INT PRIMARY KEY AUTO_INCREMENT,
// COD_EMPRESA INT NOT NULL DEFAULT '0',
// COD_PROPRIEDADE INT NOT NULL DEFAULT '0',
// COD_ACOMODACAO INT NOT NULL DEFAULT '0',
// DES_PERGUNTA VARCHAR(500),
// DES_RESPOSTA TEXT,
// NUM_ORDENAC INT NOT NULL DEFAULT '0',
// LOG_ATIVO CHAR(1) NOT NULL DEFAULT 'S',
// COD_USUCADA INT,
// DAT_CADASTR TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
// COD_ALTERAC INT,
// DAT_ALTERAC DATETIME,
// COD_EXCLUSA INT,
// DAT_EXCLUSA DATETIME

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':

					$sql = "UPDATE PERGUNTAS_ADORAI SET
						COD_EMPRESA = $cod_empresa,
						COD_PROPRIEDADE = $cod_propriedade,
						COD_ACOMODACAO = 0,
						DES_PERGUNTA = '$des_pergunta',
						DES_RESPOSTA = '$des_resposta',
						NUM_ORDENAC = $num_ordenac,
						LOG_ATIVO = '$log_ativo',
						COD_ALTERAC = $cod_usuario,
						DES_IMAGEM = '$des_imagem',
						DAT_ALTERAC = NOW()
						WHERE COD_EMPRESA = $cod_empresa AND COD_PERGUNTA = $cod_pergunta
						";
					// fnEscreve($sql);

					$alteraProc = mysqli_query(conntemp($cod_empresa,""), $sql);
			
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					$sql = "UPDATE PERGUNTAS_ADORAI SET
						COD_EXCLUSA = $cod_usucada,
						DAT_EXCLUSA = NOW()
					 WHERE = COD_EMPRESA = $cod_empresa AND COD_PERGUNTA = $cod_pergunta";
					
					$ExcluiProc = mysqli_query(conntemp($cod_empresa,""), $sql);
					
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
	
				//faq - isolado adorai
				if (fndecode($_GET['mod']) == 2028){
					$abaAdorai = 1833;
					include "abasAdorai.php";
					
					$abaManutencaoAdorai = 2028;
					echo ('<div class="push20"></div>');
					include "abasManutencaoAdorai.php";
				}
				
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PERGUNTA" id="COD_PERGUNTA" value="">
									</div>
								</div>

								<div class="col-xs-3">
									<div class="form-group">
										<label for="inputName" class="control-label ">Propriedades</label>
										<select data-placeholder="Selecione os hotéis" name="COD_PROPRIEDADE" id="COD_PROPRIEDADE" class="chosen-select-deselect" >
											<option value="9999">Todas</option>
											<?php
											$sqlHotel = "SELECT COD_EXTERNO, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
											$arrayHotel = mysqli_query(connTemp($cod_empresa,''), $sqlHotel);

											while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
												?>
												<option value="<?=$qrHotel[COD_EXTERNO]?>"><?=$qrHotel[NOM_FANTASI]?></option>
												<?php 
											}
											?>
										</select>
										<script>
											$("#COD_PROPRIEDADE").val("<?php echo $cod_propriedade; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<!-- <div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Chalés</label>
										<div id="divId_sub">
											<select data-placeholder="Selecione o sub grupo" name="COD_ACOMODACAO" id="COD_ACOMODACAO" class="chosen-select-deselect COD_ACOMODACAO">
												<option value="">&nbsp;</option>
											</select>
										</div>
										<script>
											
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div> -->

								<div class="col-md-5">
									<div class="form-group">
										<label for="inputName" class="control-label required">Pergunta</label>
										<input type="text" class="form-control input-sm" name="DES_PERGUNTA" id="DES_PERGUNTA" maxlength="250" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-3">
									<label for="inputName" class="control-label ">Imagem</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="IMAGEM" id="IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="" >
										<input type="hidden" name="DES_IMAGEM" id="DES_IMAGEM" class="form-control input-sm" maxlength="100" value="" >
									</div>																
									<span class="help-block">(.png 300px X 80px)</span>
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
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

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

								<table class="table table-bordered table-sortable  table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th class="{ sorter: false }" width="40"></th>
											<th class="{ sorter: false }" width="40"></th>
											<th>Código</th>
											<th>Propriedades</th>
											<!-- <th>Acomodações</th> -->
											<th>Pergunta</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "
										SELECT 
										PA.*,
										UNIV.NOM_FANTASI
										FROM PERGUNTAS_ADORAI AS PA 
										INNER JOIN UNIDADEVENDA AS UNIV ON UNIV.COD_EXTERNO = PA.COD_PROPRIEDADE 
										WHERE PA.COD_EMPRESA = $cod_empresa AND PA.COD_EXCLUSA IS NULL order by PA.NUM_ORDENAC
										";
										$arrayQuery = mysqli_query(conntemp($cod_empresa,""), $sql);

										$count = 0;
										while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
													<tr>
														<td align='center'><span class='fal fa-equals grabbable' data-id='" . $qrBuscaFAQ['COD_PERGUNTA'] . "'></span></td>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
														<td>" . $qrBuscaFAQ['COD_PERGUNTA'] . "</td>
														<td>" . $qrBuscaFAQ['NOM_FANTASI'] . "</td>
														<!--<td>" . $qrBuscaFAQ['NOM_QUARTO'] . "</td> -->
														<td>" . $qrBuscaFAQ['DES_PERGUNTA'] . "</td>

													</tr>
													<input type='hidden' id='ret_COD_PERGUNTA_" . $count . "' value='" . $qrBuscaFAQ['COD_PERGUNTA'] . "'>
													<input type='hidden' id='ret_COD_PROPRIEDADE_" . $count . "' value='" . $qrBuscaFAQ['COD_PROPRIEDADE'] . "'>
													<input type='hidden' id='ret_COD_ACOMODACAO_" . $count . "' value='" . $qrBuscaFAQ['COD_ACOMODACAO'] . "'>
													<input type='hidden' id='ret_DES_PERGUNTA_" . $count . "' value='" . $qrBuscaFAQ['DES_PERGUNTA'] . "'>
													<input type='hidden' id='ret_DES_RESPOSTA_" . $count . "' value='" . $qrBuscaFAQ['DES_RESPOSTA'] . "'>
													<input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . $qrBuscaFAQ['DES_IMAGEM'] . "'>
													<input type='hidden' id='ret_IMAGEM_" . $count . "' value='" . fnBase64DecodeImg($qrBuscaFAQ['DES_IMAGEM']) . "'>
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


<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
	$(function() {

$(".table-sortable tbody").sortable();

$('.table-sortable tbody').sortable({
	handle: 'span'
});

$(".table-sortable tbody").sortable({

	stop: function(event, ui) {	

		var Ids = "";
		$('.table-sortable  tr').each(function(index) {
			if (index != 0) {
				console.log(index);
				// if (index !== undefined){
					Ids = Ids + $(this).children().find('span.fa-equals').attr('data-id') + ",";
				// }
			}
		});

		//update ordenação
		//console.log(Ids.substring(0,(Ids.length-1)));

		var arrayOrdem = Ids.substring(0, (Ids.length - 1));
		//alert(arrayOrdem);
		execOrdenacao(arrayOrdem, 13, '<?= $cod_empresa ?>');

		function execOrdenacao(p1, p2, p3) {
			//alert(p2);
			$.ajax({
				type: "GET",
				url: "ajxOrdenacaoEmp.php",
				data: {
					ajx1: p1,
					ajx2: p2,
					ajx3: p3
				},
				beforeSend: function() {
					//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					// $("#divId_sub").html(data); 
				},
				error: function() {
					$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
				}
			});
		}

	}

});

	$(".table-sortable tbody").disableSelection();

	//arrastar 
	$('.grabbable').on('change', function(e) {
		//console.log(e.icon);
		$("#COD_PERGUNTA").val(e);
		console.log( $("#COD_PERGUNTA").val(e));
	});

	$(".grabbable").click(function() {
		$(this).parent().addClass('selected').siblings().removeClass('selected');
	});

});


	
	$(document).ready( function() {
			
			//arrastar 
			$('.grabbable').on('change', function(e) { 
				//console.log(e.icon);
				$("#COD_PERGUNTA").val(e.n);		
			});	

			$(".grabbable").click(function() {
				$(this).parent().addClass('selected').siblings().removeClass('selected');

			});
			
        });

</script>

<script>
	
	$("#COD_PROPRIEDADE").change(function() {
		var codBusca = $("#COD_PROPRIEDADE").val();
		var codBusca3 = $("#COD_EMPRESA").val();
		buscaSubCat(codBusca, codBusca3);
	});

	function retornaForm(index) {
		$("#formulario #COD_PROPRIEDADE").val($("#ret_COD_PROPRIEDADE_" + index).val()).trigger("chosen:updated");
		// $("#formulario #COD_ACOMODACAO").val($("#ret_COD_ACOMODACAO_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_PERGUNTA").val($("#ret_COD_PERGUNTA_" + index).val());
		$("#formulario #DES_PERGUNTA").val($("#ret_DES_PERGUNTA_" + index).val());
		tinyMCE.getInstanceById('DES_RESPOSTA').execCommand('mceSetContent', false, eval('document.getElementById("formLista").ret_DES_RESPOSTA_' + index + '.value'));
		$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
		$("#formulario #IMAGEM").val($("#ret_IMAGEM_" + index).val());

		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	function buscaSubCat(codprop, idEmp) 
	{
		$.ajax({
			type: "GET",
			url: "ajxAcomodacoesSubCat.do?opcao=SubBusca",
			data: {
				COD_PROPRIEDADE: codprop,
				COD_EMPRESA: idEmp
				
			},

			beforeSend: function() {
				$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_sub").html(data);

			},
			error: function() {
				$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}
	/*****************************
	* ESCRIPT PARA UPLOAD IMAGEM 
	******************************/
	$('.upload').on('click', function(e) {
        var idField = 'arqUpload_' + $(this).attr('idinput');
        var typeFile = $(this).attr('extensao');

        $.dialog({
            title: 'Arquivo',
            content: '' +
            '<form method = "POST" enctype = "multipart/form-data">' +
            '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
			'<div class="progress" style="display: none">' +
            	'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
           		'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
			'</div>' +
            	'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
            '</form>'
        });
    });

	function uploadFile(idField, typeFile) {

        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        if(nomeArquivo.indexOf(' ') > 0){
            $.alert({
                title: "Erro ao efetuar o upload",
                content: "O nome do arquivo não pode conter espaços, renomeie o arquivo e faça o upload novamente",
                type: 'red'
            });
        }else{
            
            var formData = new FormData();

            formData.append('arquivo', $('#' + idField)[0].files[0]);
            formData.append('diretorio', '../media/clientes');
            formData.append('diretorioAdicional', 'faqPropriedades');
            formData.append('id', <?php echo $cod_empresa ?>);
            formData.append('typeFile', typeFile);

            $('.progress').show();
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    $('#btnUploadFile').addClass('disabled');
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            if (percentComplete !== 100) {
                                $('.progress-bar').css('width', percentComplete + "%");
                                $('.progress-bar > span').html(percentComplete + "%");
                            }
                        }
                    }, false);
                    return xhr;
                },
                url: '../uploads/uploaddoc.php',
                type: 'POST',
                data: formData,
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType
                success: function(data) {

                	var data = JSON.parse(data);

                    $('.jconfirm-open').fadeOut(300, function() {
                        $(this).remove();
                    });
                    if (data.success) {
                    	$('#IMAGEM').val(nomeArquivo);
						$('#DES_IMAGEM').val(data.nome_arquivo);
                        $.alert({
                            title: "Mensagem",
                            content: "Upload feito com sucesso",
                            type: 'green'
                        });

                    } else {
                        $.alert({
                            title: "Erro ao efetuar o upload",
                            content: data,
                            type: 'red'
                        });
                    }
                }
            });
        }
    }
</script>