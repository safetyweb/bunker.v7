<?php
//echo fnDebug('true');

$hashLocal = mt_rand();
$mostraPublico = "";

//verifica se vem da tela sem pop up
if (!isset($_GET['pre'])) {
	$log_preconf = 'N';
} else {
	$log_preconf = 'S';
}

if ($log_preconf == 'S') {
	$cod_preconf = $_GET['pre'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_persona = fnLimpaCampoZero($_REQUEST['COD_PERSONA']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		if (empty($_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = $_REQUEST['LOG_ATIVO'];
		}
		//$log_ativo = fnLimpaCampo($_REQUEST['LOG_ATIVO']);
		$des_persona = fnLimpaCampo($_REQUEST['DES_PERSONA']);
		$abr_persona = fnLimpaCampo(@$_REQUEST['ABR_PERSONA']);
		$des_icone = fnLimpaCampo($_REQUEST['DES_ICONE']);
		$des_cor = fnLimpaCampo($_REQUEST['DES_COR']);
		$des_observa = fnLimpaCampo($_REQUEST['DES_OBSERVA']);
		$cod_univend = fnLimpaCampo($_REQUEST['COD_UNIVEND']);
		if (empty($_REQUEST['LOG_BLOQUEA'])) {
			$log_bloquea = 'N';
		} else {
			$log_bloquea = $_REQUEST['LOG_BLOQUEA'];
		}
		if (empty($_REQUEST['LOG_PUBLICO'])) {
			$log_publico = 'N';
		} else {
			$log_publico = $_REQUEST['LOG_PUBLICO'];
		}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		//fnEscreve($cod_empresa);

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_PERSONA (
												'" . $cod_persona . "', 
												'" . $cod_empresa . "', 
												'" . $log_ativo . "', 
												'" . $des_persona . "', 
												'" . $abr_persona . "', 
												'" . $des_icone . "', 
												'" . $des_cor . "', 
												'" . $des_observa . "', 
												'" . $log_bloquea . "', 
												'" . $log_publico . "', 
												'" . $cod_usucada . "', 
												'" . $cod_univend . "', 
												'" . $opcao . "'    
												) ";

			// fnEscreve($sql);
			// //fnEscreve(connTemp($cod_empresa,"true"));
			// fnTestesql(connTemp($cod_empresa,''),$sql);

			$result = mysqli_query(connTemp($cod_empresa, ''), $sql);
			$qrBuscaNovo = mysqli_fetch_assoc($result);

			//fnEscreve($qrBuscaNovo["COD_NOVO"]);

			//$cod_persona = $qrBuscaNovo["COD_NOVO"];

			//atualiza lista iframe				
?>
			<script>
				try {
					parent.$('#REFRESH_PERSONA').val("S");
				} catch (err) {}
			</script>
			<?php


			//se pre configuração, redireciona para persona - já está no 
			if ($log_preconf == 'S') {
				//header("Location: action.php?mod=".fnEncode(1035)."&id=".fnEncode($cod_empresa)."&idx=".fnEncode($qrBuscaNovo["COD_NOVO"])."&pre=".$cod_preconf." ");
				//die();

			?>

				<div style="width: 100%; margin: auto;">
					<div class="loading" style="width: 100%;"></div>
					<center>Aguarde. Processando...</center>
				</div>
				<script>
					window.location = "action.php?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrBuscaNovo["COD_NOVO"]) ?>&pre=<?php echo $cod_preconf ?> ";
				</script>

<?php
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

					$sql = "SELECT MAX(COD_PERSONA) AS COD_PERSONA 
								FROM PERSONA 
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_USUCADA = $cod_usucada";

					$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

					$cod_persona = $qrCod['COD_PERSONA'];

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

//defaul - perfil

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($_GET['idx'])) {
		$verIdx = fnDecode($_GET['idx']);
		if ($verIdx == "0") {
			$cod_persona = 0;
		} else {
			$cod_persona = $verIdx;
		}
	} else {
		$cod_persona = 0;
	}

	//fnEscreve($cod_persona);

	if ($cod_persona != 0) {
		$sql = "select * from persona where cod_persona = " . $cod_persona;

		//fnEscreve($sql);
		$arrayQuery = mysqli_query(ConnTemp($cod_empresa, ''), $sql);
		$qrBuscaPersona = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)) {
			//fnEscreve('query busca');
			$log_ativo = $qrBuscaPersona['LOG_ATIVO'];
			$log_publico = $qrBuscaPersona['LOG_PUBLICO'];
			if ($log_ativo == "S") {
				$mostraChecado = "checked";
			} else {
				$mostraChecado = "";
			}
			if ($log_publico == "S") {
				$mostraPublico = "checked";
			} else {
				$mostraPublico = "";
			}

			$des_persona = $qrBuscaPersona['DES_PERSONA'];
			$abr_persona = $qrBuscaPersona['ABR_PERSONA'];
			$des_icone = $qrBuscaPersona['DES_ICONE'];
			$des_cor = $qrBuscaPersona['DES_COR'];
			$des_observa = $qrBuscaPersona['DES_OBSERVA'];
			$cod_univend = $qrBuscaPersona['COD_UNIVEND'];

			$log_bloquea = $qrBuscaPersona['LOG_BLOQUEA'];
			if ($log_bloquea == "N") {
				$mostraBloqueado = "";
			} else {
				$mostraBloqueado = "checked";
			}
		}
	} else {

		//fnEscreve('sem query busca');
		$cod_persona = 0;
		$log_ativo = "N";
		$mostraChecado = "checked";
		$des_persona = "";
		$abr_persona = "";
		$des_icone = "";
		$des_cor = "";
		$des_observa = "";
		$cod_univend = "";
		$mostraBloqueado = "";
	}

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$cod_persona = 0;
	//fnEscreve('entrou else');
	$log_ativo = "N";
	$mostraChecado = "checked";
	$des_persona = "";
	$abr_persona = "";
	$des_icone = "";
	$des_cor = "";
	$des_observa = "";
	$cod_univend = "";
	$mostraBloqueado = "";
}

//fnMostraForm();
//fnEscreve($cod_persona);
//fnEscreve($log_preconf);
//fnEscreve($_SESSION["SYS_COD_TPUSUARIO"]);
//fnEscreve($_SESSION["SYS_COD_EMPRESA"]);
//fnEscreve($_SESSION["SYS_COD_UNIVEND"]);
//fnEscreve($_SESSION["SYS_COD_USUARIO"]);

?>

<?php if ($popUp != "true") { ?>
	<div class="push30"></div>
<?php } ?>

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

					<?php if ($log_preconf == 'S') { ?>
						<div class="alert alert-warning top30 bottom30" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							Informe os dados para o preenchimento da sua <string>Persona</strong>.
						</div>
					<?php } ?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PERSONA" id="COD_PERSONA" value="<?php echo $cod_persona; ?>">
										</div>
									</div>

									<!--<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
														</div>														
													</div> -->

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Persona Ativa</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?php echo $mostraChecado; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Persona Pública</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_PUBLICO" id="LOG_PUBLICO" class="switch" value="S" <?php echo $mostraPublico; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Título da Persona</label>
											<div class="push5"></div>
											<input type="text" class="form-control input-sm" name="DES_PERSONA" id="DES_PERSONA" value="<?php echo $des_persona; ?>" maxlenght="50" required>
										</div>
									</div>

									<!-- <div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Bloquear Alteração</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_BLOQUEA" id="LOG_BLOQUEA" class="switch" value="S" <?php echo $mostraBloqueado; ?> >
																<span></span>
																</label>
														</div>
													</div> -->

									<!--<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Abreviação Persona</label>
															<input type="text" class="form-control input-sm" name="ABR_PERSONA" id="ABR_PERSONA" value="<?php echo $abr_persona; ?>">
														</div>														
													</div>-->

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Ícone</label>
											<div class="push5"></div>
											<button class="btn btn-sm btn-primary btnSearchIcon" id="btniconpicker" style="min-height: 34px;" data-icon="<?php echo $des_icone; ?>"></button>
											<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Cor</label>
											<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_COR" id="DES_COR" value="<?php echo $des_cor; ?>">
										</div>
										<div class="help-block with-errors"></div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">


									<?php
									//rotina de mostrar 
									if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])) {
									?>
										<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="9999">

									<?php
									} else {
									?>

										<div class="col-md-5">
											<div class="form-group">
												<label for="inputName" class="control-label required">Selecione a sua unidade de referência</label>
												<div class="push5"></div>
												<select data-placeholder="Selecione a sua unidade de referência" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" style="width:100%;" tabindex="1" required>
													<?php
													$lojasUsuario = $_SESSION["SYS_COD_UNIVEND"];
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND IN ($lojasUsuario) AND LOG_ESTATUS = 'S' AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
													while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																		  <option value='" . $qrListaUnive['COD_UNIVEND'] . "'>" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
																		";
													}
													?>
												</select>
												<script>
													$("#formulario #COD_UNIVEND").val("<?php echo $cod_univend; ?>").trigger("chosen:updated");
												</script>

												<div class="help-block with-errors"></div>
											</div>
										</div>
									<?php
									}
									?>

								</div>

								<div class="row">

									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Objetivo da Persona</label><br />
											<textarea class="form-control" rows="3" name="DES_OBSERVA" id="DES_OBSERVA" maxlength="200"><?php echo $des_observa; ?></textarea>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group col-md-4">
								<?php if ($cod_persona != 0) { ?>
									<a class="btn btn-info" href="action.do?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($cod_persona) ?>" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp; Acessar Persona</a>
								<?php }
								?>
								<a class="btn btn-info modalFull" href="action.do?mod=<?php echo fnEncode(1609) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($cod_persona) ?>&pop=true"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Importar</a>

							</div>
							<div class="form-group text-right col-md-8">
								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($cod_persona != 0) { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php } else { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } ?>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="LOG_BLOQUEA" id="LOG_BLOQUEA" value="<?= @$log_bloquea ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<!-- <input type="hidden" name="ABR_PERSONA" id="ABR_PERSONA" value="<?php echo $abr_persona; ?>"> -->
							<input type="hidden" name="COD_PERSONA" id="COD_PERSONA" value="<?php echo $cod_persona; ?>">
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
							<?php if ($cod_persona != 0) { ?>
							<?php } else { ?>
								<!-- <input type="hidden" name="hHabilitado" id="hHabilitado" value="N'">		 -->
							<?php } ?>

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

	<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css" />
	<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
	<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

	<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
	<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

	<script type="text/javascript">
		$(document).ready(function() {

			$(".modalFull").click(function() {
				parent.$('#popModal').find('.modal-content').animate({
					'width': '100vw',
					'height': '99.5vh',
					'marginLeft': 'auto',
					'marginRight': 'auto'

				});
				parent.$('#popModal').find('.modal-dialog').animate({
					'margin': '0'
				});
			});

			//color picker
			$('.pickColor').minicolors({
				control: $(this).attr('data-control') || 'hue',
				theme: 'bootstrap'
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

			icone = "<?php echo $des_icone ?>";

			cor = "<?php echo $des_cor ?>";

			if (icone == "") {
				icone = "fal fa-user-tag";
			}

			if (cor == "") {
				cor = "#2C3E50";
			}

			$("#btniconpicker").iconpicker('setIcon', icone);
			$("#DES_ICONE").val(icone);

			$("#DES_COR").minicolors('value', cor);

		});

		function retornaForm(index) {
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>