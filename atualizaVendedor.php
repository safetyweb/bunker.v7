<?php 

$hashLocal = mt_rand();
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
	$request = md5( implode( $_POST ) );

	if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
	{
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	}
	else
	{
		$_SESSION['last_request']  = $request;

		if(isset($_POST["btnUploadFile"])){


			fnEscreve($_FILES["file"]["tmp_name"]);
		}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != ''){


				//mensagem de retorno
			switch ($opcao)
			{
				case 'CAD':
				$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
				break;
				case 'ALT':
				$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
				break;
				case 'EXC':
				$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
				break;
			}			
			$msgTipo = 'alert-success';

		}  	

	}
}


	//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);	
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)){
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}

}else {
	$cod_empresa = 0;		
		//fnEscreve('entrou else');
}

?>

<div class="push30"></div> 

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php 
				$formBack = "1276";
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


				<div class="push30"></div> 

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Importar Arquivo CSV/Excel</legend>

							<div class="col-md-5">
								<div class="input-group">
									<span class="input-group-btn">
										<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="ANEXO" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
									</span>
									<input type="text" name="ARQUIVO_UP" id="UP_ARQUIVO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100">
								</div>
								<span class="help-block">(Tamanho máximo de 20MB por anexo)</span>
							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		

						<div class="push5"></div> 

					</form>

					<div class="col-md-2">
						<button class="col-md-12 btn btn-default tmplt" name="tmplt"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;&nbsp; Template</button>
						<script>
							$(".tmplt").click(function(e) {
								e.preventDefault();
								location.href = "https://adm.bunker.mk/media/clientes/template_atualiza_vendedor.xlsx";
							});
						</script>
					</div>

					<div class="push20"></div>									

				</div>								

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>					

<div class="push20"></div>



<script type="text/javascript">

	$('.upload').on('click', function (e) {
		var idField = 'arqUpload_' + $(this).attr('idinput');
		var typeFile = $(this).attr('extensao');

		$.dialog({
			title: 'Arquivo',
			content: '' +
			'<form ID="UPLOAD_CSV" method = "POST" enctype = "multipart/form-data">' +
			'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
			'<div class="progress" style="display: none">' +
			'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">'+
			'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
			'</div>' +
			'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
			'</form>'
		});
	});

	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes/');
		formData.append('id', <?php echo $cod_empresa ?>);
		formData.append('usC', <?php echo $cod_usucada ?>);
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
			url: '../uploads/uploadAtualizaVendedor.php?id=<?php echo $cod_empresa; ?>', 
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