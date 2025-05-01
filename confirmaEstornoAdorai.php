<?php
	//echo fnDebug('true');

$hashLocal = mt_rand();	

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

		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);
		$tip_tarifa = fnLimpaCampoZero($_REQUEST['TIP_TARIFA']);
		$val_tarifa = fnLimpaCampoZero(fnValorSql($_REQUEST['VAL_TARIFA']));
		$dat_devolucao = fnLimpaCampo(fnDataSql($_REQUEST['DAT_DEVOLUCAO']));
		$cod_devolucao = fnLimpaCampoZero($_REQUEST['COD_DEVOLUCAO']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usudevol = $_SESSION["SYS_COD_USUARIO"];

			//fnEscreve($cod_empresa);

		if ($opcao != ''){	

				//mensagem de retorno
			switch ($opcao)
			{
				case 'ALT':

				$sql = "UPDATE ADORAI_DEVOLUCOES SET
				DES_IMAGEM = '$des_imagem',
				COD_USUDEVOL = $cod_usudevol,
				DAT_DEVOLUCAO = '$dat_devolucao',
				COD_STATUS = 1
				WHERE COD_DEVOLUCAO = $cod_devolucao AND COD_EMPRESA = $cod_empresa";

				//fnEscreve($sql);
				$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
				$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				$msgTipo = 'alert-success';					

				break;
			}			
			
		}  	

	}
}

	//defaul - perfil

	//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);	
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);                     

}else {
	$cod_empresa = 274;
}

if(is_numeric(fnLimpacampo(fnDecode($_GET['dev'])))){

	$cod_devolucao = fnLimpaCampo(fnDecode($_GET['dev']));

	$sql = "SELECT * FROM ADORAI_DEVOLUCOES
	WHERE COD_EMPRESA = $cod_empresa
	AND COD_DEVOLUCAO = $cod_devolucao";

	$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBusca = mysqli_fetch_assoc($query);

	$cod_devolucao = $qrBusca['COD_DEVOLUCAO'];
	$tip_pagamen = $qrBusca['TIP_PAGAMEN'];
	$dat_limite = $qrBusca['DAT_LIMITE'];
	$cod_reserva = $qrBusca['COD_PEDIDO'];
	$val_devolucao = $qrBusca['VAL_DEVOLUCAO'];
	$dat_devolucao = $qrBusca['DAT_DEVOLUCAO'];
	$des_imagem = $qrBusca['DES_IMAGEM'];
	$tip_tarifa = $qrBusca['TIP_TARIFA'];
	$val_tarifa = $qrBusca['VAL_TARIFA'];
}else{
	$cod_devolucao = "";	
	$tip_pagamen = "";
	$dat_limite = "";	
	$cod_reserva = "";	
	$val_devolucao = 0;	
	$tip_tarifa = "";	
	$val_tarifa = 0;	
}

?>

<?php if ($popUp != "true"){ ?>
	<div class="push30"></div> 
<?php } ?>

<div class="row">				

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true"){  ?>							
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;" >
				<?php } ?>

				<?php if ($popUp != "true"){  ?>
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

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend> 

								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código Devolução</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_DEVOLUCAO" id="COD_DEVOLUCAO" value="<?php echo $cod_devolucao; ?>">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código Reserva</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_RESERVA" id="COD_RESERVA" value="<?php echo $cod_reserva; ?>">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Forma de devolução</label>
											<div class="push5"></div>
											<input type="text" class="form-control input-sm" readonly="readonly" name="TIP_PAGAMEN" id="TIP_PAGAMEN" value="<?php echo $tip_pagamen; ?>" maxlenght="100">
										</div>														
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Data Limite</label>
											<div class="push5"></div>
											<input type="text" class="form-control input-sm" readonly="readonly" name="DAT_LIMITE" id="DAT_LIMITE" value="<?php echo fnDataShort($dat_limite); ?>" maxlenght="100">
										</div>														
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor</label>
											<div class="push5"></div>
											<input type="text" class="form-control input-sm" readonly="readonly" name="DAT_LIMITE" id="DAT_LIMITE" value="<?php echo fnDataShort($dat_limite); ?>" maxlenght="100">
										</div>														
									</div>

								</div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data do Estorno</label>

											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" <?php echo $dat_devolucao ? 'readonly="readonly"' : '' ;?> name="DAT_DEVOLUCAO" id="DAT_DEVOLUCAO" value="<?=fnDataShort($dat_devolucao)?>" required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<label for="inputName" class="control-label">Comprovante de Estorno</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG_G" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="text" name="IMAGEM" id="IMAGEM" class="form-control input-sm" <?php echo $des_imagem ? 'readonly="readonly"' : '' ;?>style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($des_imagem); ?>">
											<input type="hidden" name="DES_IMAGEM" id="DES_IMAGEM" value="<?php echo $des_imagem; ?>">
										</div>
										<span class="help-block">(Se houver)</span>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Descontos</label>
											<select data-placeholder="Selecione a tarifa" name="TIP_TARIFA" id="TIP_TARIFA" class="chosen-select-deselect" disabled>
												<option value="" >&nbsp;</option>
												<?php
												$sql = "SELECT * FROM ADORAI_TARIFA";
												
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
												while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery)) {
													echo "
													<option value='" . $qrListaEstCivil['tip_tarifa'] . "'>" . $qrListaEstCivil['des_tarifa'] . "</option> 
													";
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
											<script>
												$("#TIP_TARIFA").val('<?=$tip_tarifa?>').trigger("chosen:updated");
											</script>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor de Desconto</label>
											<div class="push5"></div>
											<input type="text" class="form-control input-sm"readonly="readonly" name="VAL_TARIFA" id="VAL_TARIFA" value="<?php echo $val_tarifa; ?>" maxlenght="100">
										</div>														
									</div>
									
								</div>

								<div class="push10"></div>		

							</fieldset>										

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-md-12">
								<?php if (!$dat_devolucao){ ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Confirmar Pagamento</button>
								<?php } ?>
							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">	

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

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	
	<script type="text/javascript">
		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			defaultDate: moment()
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

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
			var formData = new FormData();
			var nomeArquivo = $('#' + idField)[0].files[0]['name'];

			formData.append('arquivo', $('#' + idField)[0].files[0]);
			formData.append('diretorio', '../media/clientes');
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
				processData: false,
				contentType: false,
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

	</script>	