<?php


$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
		
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_pagamento =  fnLimpaCampoZero($_REQUEST['COD_FORMAPAG']);
		$cod_propriedade =  fnLimpaCampoZero($_REQUEST['COD_PROPRIEDADE']);
		$des_formapag = fnLimpaCampo($_REQUEST['DES_FORMAPAG']);
		$abv_formapag= fnLimpaCampo($_REQUEST['ABV_FORMAPAG']);
		$des_pagamento = fnLimpaCampo($_REQUEST['DES_PAGAMENTO']);
		$des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);

		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {			

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
				$sql = "INSERT INTO ADORAI_FORMAPAG(
					COD_EMPRESA,
					COD_PROPRIEDADE,
					COD_USUCADA,
					DES_FORMAPAG,
					ABV_FORMAPAG,
					DES_IMAGEM
					)
				VALUES(
					$cod_empresa,
					$cod_propriedade,
					$cod_usucada,
					'$des_formapag',
					'$abv_formapag',
					'$des_imagem'
					)
				";

				$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);

				if (!$arrayProc){
					$cod_error = Log_error_comand($connAdm->connAdm(),$connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
				}
				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
				}
				break;
				case 'ALT':
				$sql = "UPDATE ADORAI_FORMAPAG SET 
				COD_ALTERAC = $cod_usucada,
				COD_PROPRIEDADE = $cod_propriedade,
				DES_FORMAPAG = '$des_formapag',
				ABV_FORMAPAG = '$abv_formapag',
				DES_IMAGEM = '$des_imagem',
				DAT_ALTERAC = NOW()
				WHERE 
				COD_FORMAPAG = $cod_pagamento AND COD_EMPRESA = $cod_empresa
				";

				fnEscreve($sql);
				$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);
				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
				}
				break;
				case 'EXC':
				$sql = "UPDATE ADORAI_FORMAPAG SET 
				COD_EXCLUSA = $cod_usucada,
				DAT_EXCLUSA = NOW()
				WHERE 
				COD_FORMAPAG = $cod_pagamento AND COD_EMPRESA = $cod_empresa
				";
				$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sql);
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
	$cod_empresa = 274;
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
}

$conn = conntemp($cod_empresa,"");



?>

<style>
	.hiddenRow {
		padding: 0 !important;
	}
	tr{
		border-bottom: none!important;
	}
	#blocker
	{
		display:none; 
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
	}

	#blocker div
	{
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)</div>
</div>

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
				$abaAdorai = 2015;
				include "abasAdorai.php"; 

				$abaManutencaoAdorai = fnDecode($_GET['mod']);
					//echo $abaUsuario;

					//se não for sistema de campanhas

				echo ('<div class="push20"></div>');
				include "abasSistemaAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_FORMAPAG" id="COD_FORMAPAG" value="">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Forma de pagamento</label>
										<input type="text" class="form-control input-sm" name="DES_FORMAPAG" id="DES_FORMAPAG" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Propriedades</label>
										<select data-placeholder="Selecione os hotéis" name="COD_PROPRIEDADE" id="COD_PROPRIEDADE" class="chosen-select-deselect" required>
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
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Abreviação</label>
										<input type="text" class="form-control input-sm" name="ABV_FORMAPAG" id="ABV_FORMAPAG" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<label for="inputName" class="control-label required">Imagem</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="text" name="DES_IMAGEM" id="DES_IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_imagem; ?>">
									</div>																
									<span class="help-block">(.png 300px X 80px)</span>
								</div>	

							</div>

						</fieldset>

						<div class="push10"></div>

						<div class="form-group text-right col-lg-12">
							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<div class="push5"></div>

					</form>

					<div class="push50"></div>



					<div class="no-more-tables">

						<form name="formLista">

							<table class="table table-bordered table-hover table-sortable tablesorter">
								<thead>
									<tr>
										<th class='{ sorter: false } text-center'></th>
										<th>Código</th>
										<th>Propriedade</th>
										<th>Descrição</th>
										<th>Abreviação</th>
										<th>Imagem</th>
									</tr>
								</thead>
								<tbody>

									<?php
									$sql = "SELECT a.*,
									UNI.NOM_FANTASI
									FROM ADORAI_FORMAPAG AS a
									LEFT JOIN unidadevenda AS UNI ON UNI.COD_EXTERNO = A.COD_PROPRIEDADE
									WHERE A.COD_EXCLUSA IS NULL AND A.COD_EMPRESA = $cod_empresa order by a.COD_FORMAPAG";
													//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$count = 0;
									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										echo "
										<tr>
										<td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></td>
										<td>".$qrBusca['COD_FORMAPAG']."</td>";

										if($qrBusca['COD_PROPRIEDADE'] == 9999){
											echo "<td>Todas Propriedades</td>";
										}else{
											echo "<td>".$qrBusca['NOM_FANTASI']."</td>";
										}

										echo "
										<td>".$qrBusca['DES_FORMAPAG']."</td>
										<td>".$qrBusca['ABV_FORMAPAG']."</td>
										<td ><img src='/media/clientes/".$cod_empresa."/".$qrBusca['DES_IMAGEM']."' alt='Descrição da Imagem' width='100' height='40'></td>

														
										</tr>
										<input type='hidden' id='ret_COD_FORMAPAG_" . $count . "' value='" . $qrBusca['COD_FORMAPAG'] . "'>
										<input type='hidden' id='ret_NOM_FANTASI_" . $count . "' value='" . $qrBusca['NOM_FANTASI'] . "'>
										<input type='hidden' id='ret_COD_PROPRIEDADE_" . $count . "' value='" . $qrBusca['COD_PROPRIEDADE'] . "'>
										<input type='hidden' id='ret_DES_FORMAPAG_" . $count . "' value='" . $qrBusca['DES_FORMAPAG'] . "'>
										<input type='hidden' id='ret_ABV_FORMAPAG_" . $count . "' value='" . $qrBusca['ABV_FORMAPAG'] . "'>
										<input type='hidden' id='ret_DES_PAGAMENTO_" . $count . "' value='" . $qrBusca['DES_PAGAMENTO'] . "'>
										<input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . $qrBusca['DES_IMAGEM'] . "'>
										";
									}
									?>

								</tbody>
							</table>
						</form>

					</div>

					<div class="push20"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/daterangepicker-master/daterangepicker.js"></script>
<link rel="stylesheet" href="js/daterangepicker-master/daterangepicker.css" />

<script type="text/javascript">

	function retornaForm(index) {
		$("#formulario #COD_FORMAPAG").val($("#ret_COD_FORMAPAG_" + index).val());
		$("#formulario #COD_PROPRIEDADE").val($("#ret_COD_PROPRIEDADE_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_USUCADA").val($("#ret_COD_USUCADA_" + index).val());
		$("#formulario #DES_FORMAPAG").val($("#ret_DES_FORMAPAG_" + index).val());
		$("#formulario #ABV_FORMAPAG").val($("#ret_ABV_FORMAPAG_" + index).val());
		$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
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
                	$('.jconfirm-open').fadeOut(300, function() {
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
	}

</script>