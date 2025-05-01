<?php

//echo "<h5>_".$opcao."</h5>"; 


$hashLocal = mt_rand();
$mod = fnDecode($_GET['mod']);
$cod_atendimento = fnLimpaCampoZero(fnDecode($_GET['idC']));
$dat_cadastro = date("Y-m-d H:i:s");

// fnEscreve($mod);
// fnEscreve($cod_atendimento);

// $sql = "SELECT COD_STATUS FROM ATENDIMENTO_CHAMADOS WHERE COD_ATENDIMENTO = $cod_atendimento";
// $sqlStatus = mysqli_query(connTemp($cod_empresa,''),$sql);
// $qrCodStatus = mysqli_fetch_assoc($sqlStatus);
// $cod_status_chamado = $qrCodStatus['COD_STATUS'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$des_comentario = addslashes(htmlentities($_REQUEST['DES_COMENTARIO']));
		$cod_empresa = fnLimpacampo($_REQUEST['COD_EMPRESA']);
		$cod_usures_old = fnLimpacampo($_REQUEST['COD_USURES_OLD']);
		$cod_status_old = fnLimpacampo($_REQUEST['COD_STATUS_OLD']);
		$dat_entrega_old = fnLimpacampo($_REQUEST['DAT_ENTREGA_OLD']);
		$cod_usures = fnLimpacampo($_REQUEST['COD_USURES']);
		$cod_status = fnLimpacampo($_REQUEST['COD_STATUS']);
		$dat_entrega = fnLimpacampo($_REQUEST['DAT_ENTREGA']);
		// if (empty($_REQUEST['LOG_AGENDA'])) {$log_agenda='N';}else{$log_agenda=$_REQUEST['LOG_AGENDA'];}
		$cod_refdown = fnLimpacampo($_REQUEST['COD_REFDOWN']);
		$primeiroUp = fnLimpaCampo($_REQUEST['PRIMEIRO_UP']);
		// $log_analise = fnLimpaCampo($_REQUEST['LOG_ANALISE']);

		if ($mod == 1441) {
			$tp_comentario = fnLimpacampo($_REQUEST['TP_COMENTARIO']);
			$cor = "2";
			if ($tp_comentario == 1) {
				$log_interac = 'S';
			} else {
				$log_interac = 'N';
			}
		} else {
			$tp_comentario = 1;
			$cor = "";
			$log_interac = 'N';
		}

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($cod_usures_old != $cod_usures) {
			$upUsures = ", COD_USURES = $cod_usures";
			$msgUsuRes = "'\r\nAtribuiu <i>',(SELECT NOM_USUARIO FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO = $cod_usures),'</i> como responsável<br/>',";
		} else {
			$upUsures = "";
			$msgUsuRes = "";
		}

		if ($cod_status_old != $cod_status) {
			$upStatus = ", COD_STATUS = $cod_status";
			$msgStatus = "'\r\nAlterou o status de: <i>',(SELECT DES_STATUS FROM ATENDIMENTO_STATUS WHERE COD_STATUS = $cod_status_old), 
					 			'</i> para: <i>',(SELECT DES_STATUS FROM ATENDIMENTO_STATUS WHERE COD_STATUS = $cod_status),'</i><br/>'";
		} else {
			$upStatus = "";
			$msgStatus = "";
		}

		if ($dat_entrega_old != $dat_entrega) {
			if ($dat_entrega == "") {
				$upDatEnt = "";
				$msgDatEnt = "";
			} else {
				$upDatEnt = ", DAT_ENTREGA = '" . fnDataSql($dat_entrega) . "'";
				if ($log_analise == 'S') {
					$msgDatEnt = "'\r\nAlterou a data de análise para <i>" . fnDataShort($dat_entrega) . "</i> <br/>',";
				} else {
					$msgDatEnt = "'\r\nAlterou a data de entrega para <i>" . fnDataShort($dat_entrega) . "</i> <br/>',";
				}
			}
		} else {
			$upDatEnt = "";
			$msgDatEnt = "";
		}

		if ($cod_usures_old != $cod_usures || $cod_status_old != $cod_status || $dat_entrega_old != $dat_entrega) {
			$msgFeed = ",(
					'$cod_atendimento',
					CONCAT(
					$msgUsuRes $msgStatus $msgDatEnt
					'em <i>" . date('d/m/Y H:i:s') . "</i>'),
					 1,
					'$cod_empresa',
					$cod_usucada,
					'$dat_cadastro',
					'$cor',
					'$cod_status'
					 )";
		}



		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$sql = "";

		if ($opcao != '') {

			if ($opcao == 'CAD') {

				$sql .= "INSERT INTO ATENDIMENTO_COMENTARIO(
									COD_ATENDIMENTO,
									DES_COMENTARIO,
									TP_COMENTARIO,
									COD_EMPRESA,
									COD_USUARIO,
									DAT_CADASTRO,
									COD_COR,
									COD_STATUS
									) VALUES(
									'$cod_atendimento',
									'$des_comentario',
									'$tp_comentario',
									'$cod_empresa',
									$cod_usucada,
									'$dat_cadastro',
									'$cor',
									'$cod_status'
									) $msgFeed;";


				$sql .= "UPDATE ATENDIMENTO_CHAMADOS SET 
					LOG_INTERAC = 'S'
					$upUsures
					$upStatus
					$upDatEnt
					WHERE COD_ATENDIMENTO = $cod_atendimento";

				// fnEscreve($sql);

				//fnTestesql($connAdmSAC->connAdm(), $sql);
				mysqli_multi_query(connTemp($cod_empresa, ''), $sql);
				//fnMostraForm('#formulario');
?>
				<script>
					try {
						parent.$('#REFRESH_COMENTARIO').val("S");
					} catch (err) {}
				</script>
<?php

				if ($primeiroUp == "N") {

					$sql = "SELECT MAX(COD_COMENTARIO) AS COD_COMENTARIO FROM ATENDIMENTO_COMENTARIO WHERE COD_ATENDIMENTO = $cod_atendimento";
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
					$qrComent = mysqli_fetch_assoc($arrayQuery);

					$sql = "UPDATE ATENDIMENTO_ANEXO SET 
									   COD_ATENDIMENTO = $cod_atendimento,
									   COD_EMPRESA = $cod_empresa,
									   COD_COMENTARIO = " . $qrComent['COD_COMENTARIO'] . "
									   WHERE 
									   COD_REFDOWN = $cod_refdown
									   ";
					// fnEscreve($sql);

					mysqli_query(connTemp($cod_empresa, ''), $sql);
				}
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id']))) && fnDecode($_GET['id']) != 0) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 7;
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}

	//fnEscreve('entrou else');
}

$sql = "SELECT COD_USURES, COD_STATUS, DAT_ENTREGA 
			FROM ATENDIMENTO_CHAMADOS WHERE COD_ATENDIMENTO = $cod_atendimento";
// fnEscreve($sql);
$qrChmd = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

if (fnDataShort($qrChmd['DAT_ENTREGA']) == "31/12/1969") {
	$dat_entrega = "";
} else {
	$dat_entrega = fnDataShort($qrChmd['DAT_ENTREGA']);
}

if ($qrChmd['LOG_ANALISE'] == "S") {
	$checkAnalise = "checked";
	$disableAnalise = "";
} else {
	$checkAnalise = "";
	$disableAnalise = "onclick='this.checked=false;'";
}

$sql = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE COD_CONTADOR = 2";
mysqli_query(connTemp($cod_empresa, ''), $sql);

$sql = "SELECT NUM_CONTADOR FROM CONTADOR WHERE COD_CONTADOR = 2";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrCont = mysqli_fetch_assoc($arrayQuery);

$conta = $qrCont['NUM_CONTADOR'];
$primeiroUp = "S";



// fnEscreve($qrChmd['COD_STATUS']);

?>

<div class="push30"></div>

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

					<style>
						li {
							list-style: none;
						}

						.chec-radio .radio-inline .clab {
							cursor: pointer;
							background: #e7e7e7;
							padding: 7px 20px;
							text-align: center;
							text-transform: uppercase;
							color: #2c3e50;
							position: relative;
							height: 34px;
							float: left;
							margin: 0;
							margin-bottom: 5px;
						}

						.chec-radio label.radio-inline input[type="radio"] {
							display: none;
						}

						.chec-radio label.radio-inline input[type="radio"]:checked+div {
							color: #fff;
							background-color: #2c3e50;
						}

						.chec-radio label.radio-inline input[type="radio"]:checked+div:before {
							content: "\e013";
							margin-right: 5px;
							font-family: 'Glyphicons Halflings';
						}

						.collapse-chevron .fa {
							transition: .3s transform ease-in-out;
						}

						.collapse-chevron .collapsed .fa {
							transform: rotate(-90deg);
						}

						.collapse-plus .fas {
							transition: .2s transform ease-in-out;
						}

						.collapse-plus .collapsed .fas {
							transform: rotate(45deg);
						}

						.area {
							width: 100%;
							padding: 7px;
						}

						#dropZone {
							display: block;
							border: 2px dashed #bbb;
							-webkit-border-radius: 5px;
							border-radius: 5px;
							margin-left: -7px;
						}

						#dropZone p {
							font-size: 10pt;
							letter-spacing: -0.3pt;
							margin-bottom: 0px;
						}

						#dropzone .fa {
							font-size: 15pt;
						}

						.jqte {
							border: #dce4ec 2px solid !important;
							border-radius: 3px !important;
							-webkit-border-radius: 3px !important;
							box-shadow: 0 0 2px #dce4ec !important;
							-webkit-box-shadow: 0 0 0px #dce4ec !important;
							-moz-box-shadow: 0 0 3px #dce4ec !important;
							transition: box-shadow 0.4s, border 0.4s;
							margin-top: 0px !important;
							margin-bottom: 0px !important;
						}

						.jqte_toolbar {
							background: #fff !important;
							border-bottom: none !important;
						}

						.jqte_focused {
							/*border: none!important;*/
							box-shadow: 0 0 3px #00BDFF;
							-webkit-box-shadow: 0 0 3px #00BDFF;
							-moz-box-shadow: 0 0 3px #00BDFF;
						}

						.jqte_titleText {
							border: none !important;
							border-radius: 3px;
							-webkit-border-radius: 3px;
							-moz-border-radius: 3px;
							word-wrap: break-word;
							-ms-word-wrap: break-word
						}

						.jqte_tool,
						.jqte_tool_icon,
						.jqte_tool_label {
							border: none !important;
						}

						.jqte_tool_icon:hover {
							border: none !important;
							box-shadow: 1px 5px #EEE;
						}
					</style>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<div class="row">

								<div class="col-md-4 col-sm-4 col-xs-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Atribuir para:</label>
										<select data-placeholder="Selecione um usuário" name="COD_USURES" id="COD_USURES" class="chosen-select-deselect" style="width:100%!important;">
											<optgroup label="Usuários Envolvidos">
												<?php

												$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
																		where usuarios.COD_EMPRESA = $cod_empresa
																		and usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

												while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
																				";
												}
												?>
											</optgroup>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4 col-sm-4 col-xs-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Status</label>
										<select class="chosen-select-deselect" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS" style="width:100%!important;">
											<?php

											$sql = "SELECT COD_STATUS, DES_STATUS FROM ATENDIMENTO_STATUS WHERE COD_EMPRESA = $cod_empresa";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4 col-sm-4 col-xs-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Prazo</label>
										<div class="input-group date datePicker" id="DAT_ENTREGA_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_ENTREGA" id="DAT_ENTREGA" value="<?= $dat_entrega ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<!-- <div class="col-md-1">   
													<div class="form-group">
														<label for="inputName" class="control-label">Análise?</label> 
														<div class="push5"></div>
															<label class="switch">
															<input type="checkbox" name="LOG_ANALISE" id="LOG_ANALISE" class="switch" value="S" <?= $checkAnalise . " " . $disableAnalise ?>>
															<span></span>
															</label>
													</div>
												</div> -->

							</div>

							<div class="push20"></div>

							<div class="row">

								<!-- <div class="col-md-2">   
													<div class="form-group">
														<label for="inputName" class="control-label">Gera Agendamento?</label> 
														<div class="push5"></div>
														<label class="switch">
														<input type="checkbox" name="LOG_AGENDA" id="LOG_AGENDA" class="switch" value="S">
														<span></span>
														</label>
													</div>
												</div> -->

								<div class="col-md-2">
									<label class="control-label required">Tipo de comentário: </label>
								</div>
								<ul class="chec-radio">

									<li class="col-md-2">
										<div class="form-group">
											<label class="radio-inline">
												<input type="radio" id="TP_COMENTARIO" name="TP_COMENTARIO" value="1" required>
												<div class="clab">Público</div>
											</label>
										</div>
									</li>

									<li class="col-md-2">
										<div class="form-group">
											<label class="radio-inline">
												<input type="radio" id="TP_COMENTARIO" name="TP_COMENTARIO" value="2" checked>
												<div class="clab">Interno</div>
											</label>
										</div>
									</li>

								</ul>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-lg-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Mensagem: </label>
										<textarea class="editor form-control input-sm" rows="6" name="DES_COMENTARIO" id="DES_COMENTARIO" required></textarea>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-3 btn-anexo">
									<div class="collapse-plus">
										<a data-toggle="collapse" class="collapsed btn btn-sm btn-success" href="#collapseFilter2" style="width: 90%;">
											<span class="fas fa-times" aria-hidden="true"></span>&nbsp;
											Criar Novo Anexo
										</a>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-6 area-anexo">
									<div class="collapse area" id="collapseFilter2">
										<div id="dropZone">

											<div class="row">

												<div class="push15"></div>

												<div class="col-sm-1"></div>

												<div class="col-sm-2">
													<a type="button" name="btnBusca" id="btnBusca" class="btn btn-primary upload" idinput="SAC_ANEXO" extensao="all"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</div>

												<div class="col-sm-8 text-center">
													<div class="push5"></div>
													<p>Upload de Arquivos</p>
													<input type="text" name="SAC_ANEXO" id="SAC_ANEXO" maxlength="100" hidden>
													<span class="help-block">(Tamanho máximo de 20MB por anexo)</span>
													<div class="push15"></div>
												</div>

												<div class="col-sm-1"></div>

											</div>


										</div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-6">
									<table class="table">
										<tbody id="relatorioConteudo">
											<?php
											$sql = "SELECT * FROM ATENDIMENTO_ANEXO WHERE COD_REFDOWN = $conta AND COD_EMPRESA = $cod_empresa ORDER BY DAT_CADASTR DESC";
											//fnEscreve($sql);

											$arrayquery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrAnexo = mysqli_fetch_assoc($arrayquery)) {

											?>

												<tr>
													<td><a href="../media/clientes/<?php echo $cod_empresa; ?>/helpdesk/<?php echo $qrAnexo['NOM_ARQUIVO']; ?>"><span class="fa fa-download"></span></a></td>
													<td><?php echo $qrAnexo['NOM_ARQUIVO']; ?></td>
													<td><small><?php echo date("d/m/Y", strtotime($qrAnexo['DAT_CADASTR'])) ?></small>&nbsp;<small><?php echo date("H:i:s", strtotime($qrAnexo['DAT_CADASTR'])) ?></small></td>
												</tr>

											<?php
											}
											?>
										</tbody>
									</table>
								</div>

							</div>



							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">
								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-send" aria-hidden="true"></i>&nbsp; Enviar</button>
							</div>

							<input type="hidden" name="COD_ATENDIMENTO" id="COD_ATENDIMENTO" value="<?= $cod_atendimento ?>">
							<input type="hidden" name="COD_USURES_OLD" id="COD_USURES_OLD" value="<?= $qrChmd['COD_USURES'] ?>">
							<input type="hidden" name="COD_STATUS_OLD" id="COD_STATUS_OLD" value="<?= $qrChmd['COD_STATUS'] ?>">
							<input type="hidden" name="DAT_ENTREGA_OLD" id="DAT_ENTREGA_OLD" value="<?= fnDataShort($qrChmd['DAT_ENTREGA']) ?>">
							<input type="hidden" name="PRIMEIRO_UP" id="PRIMEIRO_UP" value="<?php echo $primeiroUp; ?>">
							<input type="hidden" name="COD_REFDOWN" id="COD_REFDOWN" value="<?php echo $conta; ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

					</div>

					<div class="push"></div>

				</div>

				</div><!-- fim Portlet -->
			</div>

	</div>

</div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>


<script type="text/javascript">
	$(function() {

		// TextArea
		$(".editor").jqte({
			sup: false,
			sub: false,
			outdent: false,
			indent: false,
			left: false,
			center: false,
			color: false,
			right: false,
			strike: false,
			source: false,
			link: false,
			unlink: false,
			remove: false,
			rule: false,
			fsize: false,
			format: false,
		});

		$('#COD_USURES').val("<?= $qrChmd[COD_USURES] ?>").trigger("chosen:updated");
		$('#COD_STATUS').val("<?= $qrChmd[COD_STATUS] ?>").trigger("chosen:updated");

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

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
		formData.append('diretorio', '../media/clientes/');
		formData.append('diretorioAdicional', "helpdesk");
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
			url: '../uploads/uploaddocSac.php',
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

					//ajax da lista de arquivos upados

					$.ajax({
						type: "POST",
						url: "ajxAtendimentoAnexo.php",
						data: $('#formulario').serialize(),
						beforeSend: function() {
							$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							console.log(data);
							$('#relatorioConteudo').html(data);
							$('#PRIMEIRO_UP').val("N");
							$('.btn-anexo, .area-anexo').fadeOut('fast');
						},
						error: function() {
							$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Itens não encontrados...</p>');
						}
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