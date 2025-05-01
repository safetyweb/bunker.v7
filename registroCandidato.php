<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_candidato = fnLimpaCampoZero($_REQUEST['COD_CANDIDATO']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_USUARIO']);
		$num_candidato = fnLimpaCampo($_REQUEST['NUM_CANDIDATO']);
		$des_partido = fnLimpaCampo($_REQUEST['DES_PARTIDO']);
		$des_cargo = fnLimpaCampo($_REQUEST['DES_CARGO']);
		$nom_cliente_env = fnLimpaCampo($_REQUEST['NOM_CLIENTE_ENV']);
		$cod_cliente_env = fnLimpaCampoZero($_REQUEST['COD_CLIENTE_ENV']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$conn = conntemp($cod_empresa,"");
		$adm = $connAdm->connAdm();

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {


			//mensagem de retorno
			switch ($opcao) {

				case 'CAD':

					$sql = "INSERT INTO CANDIDATO(
											COD_EMPRESA,
											COD_UNIVEND,
											COD_CLIENTE,
											NUM_CANDIDATO,
											DES_PARTIDO,
											DES_CARGO,
											NOM_ADMIN,
											COD_CLIENTE_ADM,
											COD_USUCADA
										) VALUES(
											$cod_empresa,
											$cod_univend,
											$cod_cliente,
											'$num_candidato',
											'$des_partido',
											'$des_cargo',
											'$nom_cliente_env',
											$cod_cliente_env,
											$cod_usucada	
										)";

					// fnEscreve($sql);

					$arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}

				break;

				case 'ALT':

					$sql = "UPDATE CANDIDATO SET
											NUM_CANDIDATO = '$num_candidato',
											COD_CLIENTE = $cod_cliente,
											DES_PARTIDO = '$des_partido',
											DES_CARGO = '$des_cargo',
											NOM_ADMIN = '$nom_cliente_env',
											COD_CLIENTE_ADM = $cod_cliente_env,
											COD_ALTERAC = $cod_usucada,
											DAT_ALTERAC = NOW()
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_CANDIDATO = $cod_candidato";

					// fnEscreve($sql);

					$arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}

				break;

				case 'EXC':

					$sql = "UPDATE CANDIDATO SET
											COD_EXCLUSA = $cod_usucada,
											DAT_EXCLUSA = NOW()
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_CANDIDATO = $cod_candidato";

					//echo $sql;

					$arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

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
	$cod_univend = fnDecode($_GET['idu']);
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

$sqlCand = "SELECT CDT.*, CL.NOM_CLIENTE FROM CANDIDATO CDT
			INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = CDT.COD_CLIENTE
			WHERE CDT.COD_UNIVEND = $cod_univend 
			AND CDT.COD_EMPRESA = $cod_empresa";
$arrayCandidato = mysqli_query(connTemp($cod_empresa,''), $sqlCand);
$qrCandidato = mysqli_fetch_assoc($arrayCandidato);

if(isset($qrCandidato)){
	$cod_candidato = $qrCandidato[COD_CANDIDATO];
	$num_candidato = $qrCandidato[NUM_CANDIDATO];
	$des_cargo = $qrCandidato[DES_CARGO];
	$des_partido = $qrCandidato[DES_PARTIDO];
	$nom_cliente_env = $qrCandidato[NOM_ADMIN];
	$cod_cliente_env = $qrCandidato[COD_CLIENTE_ADM];
	$cod_cliente = $qrCandidato[COD_CLIENTE];
	$cod_cliente = $qrCandidato[COD_CLIENTE];
	$nom_usuario = $qrCandidato[NOM_CLIENTE];
}

//fnMostraForm();

?>

<div class="push30"></div>

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
					<span class="text-primary"><?php echo $NomePg; ?></span>
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

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">N° Candidato</label>
										<input type="tel" class="form-control input-sm int" name="NUM_CANDIDATO" id="NUM_CANDIDATO" value="<?=$num_candidato?>" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-xs-4">
									<label for="inputName" class="control-label required">Nome do Candidato</label>
									<div class="input-group">
									<span class="input-group-btn">
									<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&idu=<?php echo fnEncode($cod_univend)?>&pop=true" data-title="Busca Candidato"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
									</span>
									<input type="text" name="NOM_USUARIO" id="NOM_USUARIO" value="<?php echo $nom_usuario; ?>" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required readonly>
									<input type="hidden" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_cliente; ?>"  required>
									</div>
									<div class="help-block with-errors"></div>														
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Partido</label>
										<input type="tel" class="form-control input-sm" name="DES_PARTIDO" id="DES_PARTIDO" value="<?=$des_partido?>" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-xs-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Cargo</label>
											<select data-placeholder="Selecione o cargo" name="DES_CARGO" id="DES_CARGO" class="chosen-select-deselect" required>
												<option value="pref">Prefeito</option>
												<option value="ver">Vereador</option>
												<option value="depE">Deputado Estadual</option>
												<option value="depF">Deputado Federal</option>
												<option value="sen">Senador</option>
												<option value="pres">Presidente</option>
											</select>
										<div class="help-block with-errors"></div>
										<script type="text/javascript">$("#formulario #DES_CARGO").val("<?=$des_cargo?>").trigger("chosen:updated");</script>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-xs-4">
									<label for="inputName" class="control-label">Administrador Financeiro</label>
									<div class="input-group">
									<span class="input-group-btn">
									<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&idu=<?php echo fnEncode($cod_univend)?>&op=AGE&pop=true" data-title="Busca Administrador"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
									</span>
									<input type="text" name="NOM_CLIENTE_ENV" id="NOM_CLIENTE_ENV" value="<?php echo $nom_cliente_env; ?>" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" readonly>
									<input type="hidden" name="COD_CLIENTE_ENV" id="COD_CLIENTE_ENV" value="<?php echo $cod_cliente_env; ?>">
									</div>
									<div class="help-block with-errors"></div>														
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<?php if($cod_candidato != 0){ ?>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
							<?php }else{ ?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<?php } ?>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="<?php echo $cod_univend ?>">
						<input type="hidden" name="COD_CANDIDATO" id="COD_CANDIDATO" value="<?=$cod_candidato?>">

						<div class="push5"></div>

					</form>

					

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

	</div>
</div>

<div class="push20"></div>

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

<script type="text/javascript">

	$(function(){

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

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

	function retornaForm(index) {
		$("#formulario #COD_ANEXO").val($("#ret_COD_ANEXO_" + index).val());
		$("#formulario #DES_DOC").val($("#ret_DES_DOC_" + index).val());
		$("#formulario #TIP_DOC").val($("#ret_TIP_DOC_" + index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>