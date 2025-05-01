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

		$des_img1 = fnLimpaCampo($_REQUEST['DES_IMG1']);
		$des_template1 = base64_encode($_REQUEST['DES_TEMPLATE1']);
		$des_img2 = fnLimpaCampo($_REQUEST['DES_IMG2']);
		$des_template2 = base64_encode($_REQUEST['DES_TEMPLATE2']);
		$des_img3 = fnLimpaCampo($_REQUEST['DES_IMG3']);
		$des_template3 = base64_encode($_REQUEST['DES_TEMPLATE3']);
		
		$cod_empresa = 274;

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			switch($opcao){
			
			case 'CAD':

				$sqlCad = "INSERT INTO MENSAGEM_ADORAI(
											COD_EMPRESA,
											DES_IMG1,
											DES_TEMPLATE1,
											DES_IMG2,
											DES_TEMPLATE2,
											DES_IMG3,
											DES_TEMPLATE3,
											COD_USUCADA
										)VALUES(
											$cod_empresa,
											'$des_img1',
											'$des_template1',
											'$des_img2',
											'$des_template2',
											'$des_img3',
											'$des_template3',
											$cod_usucada
										)";

				fnescreve($sqlCad);

				//fnTestesql(connTemp($cod_empresa),$sqlCad);				
				$arrayProc = mysqli_query(conntemp($cod_empresa,''), $sqlCad);

				if (!$arrayProc) {

					$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlCad,$nom_usuario);
				}
				break;
				case 'ALT':	

				
			break;
			case 'ALT':	
				
			break;
			case 'EXC':
				
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
	$cod_empresa = 274;
	//fnEscreve('entrou else');
}

$cod_empresa = 274;

$sql = "SELECT * FROM MENSAGEM_ADORAI 
		WHERE COD_EMPRESA = $cod_empresa
		ORDER BY COD_MENSAGEM DESC LIMIT 1";
$arrayQuery = mysqli_query(conntemp($cod_empresa,''), $sql);


if(isset($arrayQuery)){

	$qrBusca = mysqli_fetch_assoc($arrayQuery);

	$des_img1 = $qrBusca[DES_IMG1];
	$des_template1 = base64_decode($qrBusca[DES_TEMPLATE1]);
	$des_img2 = $qrBusca[DES_IMG2];
	$des_template2 = base64_decode($qrBusca[DES_TEMPLATE2]);
	$des_img3 = $qrBusca[DES_IMG3];
	$des_template3 = base64_decode($qrBusca[DES_TEMPLATE3]);

}else{

	$des_img1 = "";
	$des_template1 = "";
	$des_img2 = "";
	$des_template2 = "";
	$des_img3 = "";
	$des_template3 = "";

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

								<!-- <div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Celular</label>
										<input type="text" class="form-control text-center sp_celphones" placeholder="Celular para envio" name="NUM_CELULAR" id="NUM_CELULAR" value="">
										<div class="help-block with-errors"></div>
									</div>
								</div> -->

								<!-- <div class="col-md-3">
									<div class="form-group">
										<b>@nome</b>, tudo bem? 😃<br><br>Você fez uma pequisa em nosso site procurando <b>chalé para o período de 17/11</b>?<br><br>Achamos que <b>gostaria de saber</b> que <b>vagou de última hora uma diária no chalé 10</b>, aqui no <b>Adorai em Piedade.</b><hr>
										<img src="https://img.bunker.mk/media/clientes/3/mensagemAvulsa2.jpeg" class="img-responsive"><br><hr>
										Para <b>confirmar a reserva e garantir a data</b>, basta me passar <b>seus dados aqui, ou clicar no link</b>: https://roteirosadorai.com.br/?canal=recap<hr>
										<div class="help-block with-errors"></div>
									</div>
								</div> -->

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-6">
									<label for="inputName" class="control-label">Mensagem 1:</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG1" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="DES_IMG1" id="DES_IMG1" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_img1; ?>">
									</div>
								</div>

								<div class="push10"></div>

								<div class="col-lg-12">
									<div class="form-group">
										
										<textarea class="editor form-control input-sm" rows="6" name="DES_TEMPLATE1" id="DES_TEMPLATE1" maxlength="4000"><?php echo $des_template1; ?></textarea>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>	

							<div class="row">

								<div class="col-md-6">
									<label for="inputName" class="control-label">Mensagem 2:</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG2" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="DES_IMG2" id="DES_IMG2" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_img2; ?>">
									</div>
								</div>

								<div class="push10"></div>
								
								<div class="col-lg-12">
									<div class="form-group">
										
										<textarea class="editor form-control input-sm" rows="6" name="DES_TEMPLATE2" id="DES_TEMPLATE2" maxlength="4000"><?php echo $des_template2; ?></textarea>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
							</div>	

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-6">
									<label for="inputName" class="control-label">Mensagem 3:</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG3" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="DES_IMG3" id="DES_IMG3" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_img3; ?>">
									</div>
								</div>

								<div class="push10"></div>
								
								<div class="col-lg-12">
									<div class="form-group">
										
										<textarea class="editor form-control input-sm" rows="6" name="DES_TEMPLATE3" id="DES_TEMPLATE3" maxlength="4000"><?php echo $des_template3; ?></textarea>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
							</div>						

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="ID" id="ID" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

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

	<!-- <link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
	<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>	 -->
	
<script type="text/javascript">

	$(function(){
		var SPMaskBehavior = function(val) {
			return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
			onKeyPress: function(val, e, field, options) {
				field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};

		// TextArea
		// $(".editor").jqte({
		// 	sup: false,
		// 	sub: false,
		// 	outdent: false,
		// 	indent: false,
		// 	left: false,
    	// 	center: false,
    	// 	color: false,
    	// 	right: false,
    	// 	strike: false,
    	// 	source: false,
	    //     link:false,
	    //     unlink: false,		        
	    //     remove: false,
	    // 	rule: false,
	    // 	fsize: false,
	    // 	format: false,
	    // });

		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

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

	});

	function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        $('.progress').show();
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function (evt) {
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
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
                    $(this).remove();
                });
                if (!data.trim()) {
                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
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
	
</script>