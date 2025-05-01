<?php
if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$mod = "";
$cod_chamado = "";
$dat_cadastro = "";
$tp_pag = "";
$sqlStatus = "";
$qrCodStatus = "";
$cod_status_chamado = "";
$msgRetorno = "";
$msgTipo = "";
$des_comentario = "";
$cod_usures_old = "";
$cod_status_old = "";
$dat_entrega_old = "";
$dat_proxint_old = "";
$cod_usures = "";
$cod_status = "";
$cod_tpsolicitacao = "";
$cod_tpsolicita_old = "";
$dat_entrega = "";
$dat_proxint = "";
$log_agenda = "";
$cod_refdown = "";
$primeiroUp = "";
$tp_comentario = "";
$cor = "";
$log_interac = "";
$cod_usucada = "";
$upSolicitacao = "";
$msgSolicitacao = "";
$upUsures = "";
$msgUsuRes = "";
$upStatus = "";
$msgStatus = "";
$upDatEnt = "";
$msgDatEnt = "";
$upDatProxint = "";
$msgDatProxint = "";
$msgFeed = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrComent = "";
$tipo_email = "";
$novo_chamado = "";
$cod_chamado_sql = "";
$todos = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrChmd = "";
$conta = "";
$qrCont = "";
$popUp = "";
$qrLista = "";
$qrStatus = "";
$qrSolicitacao = "";


$hashLocal = mt_rand();
$mod = fnDecode(@$_GET['mod']);
$cod_chamado = fnLimpaCampoZero(fnDecode(@$_GET['idC']));
$dat_cadastro = date("Y-m-d H:i:s");
$tp_pag = 'COM';

// fnEscreve($mod);
// fnEscreve($cod_chamado);

$sql = "SELECT COD_STATUS FROM SAC_CHAMADOS WHERE COD_CHAMADO = $cod_chamado";
$sqlStatus = mysqli_query($connAdmSAC->connAdm(), $sql);
$qrCodStatus = mysqli_fetch_assoc($sqlStatus);
$cod_status_chamado = $qrCodStatus['COD_STATUS'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$des_comentario = addslashes(htmlentities(@$_REQUEST['DES_COMENTARIO']));
		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_usures_old = fnLimpacampoZero(@$_REQUEST['COD_USURES_OLD']);
		$cod_status_old = fnLimpacampoZero(@$_REQUEST['COD_STATUS_OLD']);
		$dat_entrega_old = fnLimpacampo(@$_REQUEST['DAT_ENTREGA_OLD']);
		$dat_proxint_old = fnLimpacampo(@$_REQUEST['DAT_PROXINT_OLD']);
		$cod_usures = fnLimpacampoZero(@$_REQUEST['COD_USURES']);
		$cod_status = fnLimpacampoZero(@$_REQUEST['COD_STATUS']);
		$cod_tpsolicitacao = fnLimpacampoZero(@$_REQUEST['COD_TPSOLICITACAO']);
		$cod_tpsolicita_old = fnLimpacampoZero(@$_REQUEST['COD_TPSOLICITA_OLD']);
		$dat_entrega = fnLimpacampo(@$_REQUEST['DAT_ENTREGA']);
		$dat_proxint = fnLimpacampo(@$_REQUEST['DAT_PROXINT']);
		if (empty(@$_REQUEST['LOG_AGENDA'])) {
			$log_agenda = 'N';
		} else {
			$log_agenda = @$_REQUEST['LOG_AGENDA'];
		}
		$cod_refdown = fnLimpacampo(@$_REQUEST['COD_REFDOWN']);
		$primeiroUp = fnLimpaCampo(@$_REQUEST['PRIMEIRO_UP']);

		if ($dat_entrega == "") {
			$dat_entrega = "31/12/1969";
		}
		if ($dat_proxint == "") {
			$dat_proxint = "31/12/1969";
		}
		if ($cod_tpsolicitacao == 0) {
			$cod_tpsolicitacao = $cod_tpsolicita_old;
		}

		// fnEscreve($cod_status);
		// fnEscreve($dat_entrega_old);

		if ($mod == 1287 || $mod == 1461) {
			$tp_comentario = fnLimpacampo(@$_REQUEST['TP_COMENTARIO']);
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

		if ($cod_tpsolicita_old != $cod_tpsolicitacao && $mod != 1289) {
			$upSolicitacao = ", COD_TPSOLICITACAO = $cod_tpsolicitacao
								  , COD_TPSOLICITA_OLD = $cod_tpsolicita_old";
			$msgSolicitacao = "'\r\Alterou o tipo da solicitação para <i>',(SELECT DES_TPSOLICITACAO FROM SAC_TPSOLICITACAO WHERE COD_TPSOLICITACAO = $cod_tpsolicitacao),'</i><br/>',";
		} else {
			$upSolicitacao = "";
			$msgSolicitacao = "";
		}

		if ($cod_usures_old != $cod_usures && $mod != 1289) {
			$upUsures = ", COD_USURES = $cod_usures";
			$msgUsuRes = "'\r\nAtribuiu <i>',(SELECT NOM_USUARIO FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO = $cod_usures),'</i> como responsável<br/>',";
		} else {
			$upUsures = "";
			$msgUsuRes = "";
		}

		if ($cod_status_old != $cod_status && $mod != 1289) {
			$upStatus = ", COD_STATUS = $cod_status";
			$msgStatus = "'\r\nAlterou o status de: <i>',(SELECT DES_STATUS FROM SAC_STATUS WHERE COD_STATUS = $cod_status_old), 
					 			'</i> para: <i>',(SELECT DES_STATUS FROM SAC_STATUS WHERE COD_STATUS = $cod_status),'</i><br/>'";
		} else {

			if ($cod_status_chamado == 14) {
				$upStatus = ", COD_STATUS = 15";
			} else {
				$upStatus = "";
			}

			$msgStatus = "";
		}

		if ($dat_entrega_old != $dat_entrega && $mod != 1289) {

			$upDatEnt = ", DAT_ENTREGA = '" . fnDataSql($dat_entrega) . "'";

			if ($dat_entrega == "31/12/1969") {

				$msgDatEnt = "'\r\nAlterou que a data de <i>entrega</i> está para <i>ser definida</i> <br/>',";
			} else {

				$msgDatEnt = "'\r\nAlterou a data de <i>entrega</i> para <i>" . $dat_entrega . "</i> <br/>',";
			}
		} else {

			$upDatEnt = "";
			$msgDatEnt = "";
		}

		if ($dat_proxint_old != $dat_proxint && $mod != 1289) {

			$upDatProxint = ", DAT_PROXINT = '" . fnDataSql($dat_proxint) . "'";

			if ($dat_proxint == "31/12/1969") {

				$msgDatProxint = "'\r\nAlterou que a data da <i>próxima interação</i> está para <i>ser definida</i> <br/>',";
			} else {

				$msgDatProxint = "'\r\nAlterou a data da <i>próxima interação</i> para <i>" . $dat_proxint . "</i> <br/>',";
			}
		} else {

			$upDatProxint = "";
			$msgDatProxint = "";
		}

		if (($cod_usures_old != $cod_usures || $cod_status_old != $cod_status || $dat_entrega_old != $dat_entrega || $dat_proxint_old != $dat_proxint) && $mod != 1289) {
			$msgFeed = ",(
					'$cod_chamado',
					CONCAT(
					$msgSolicitacao $msgUsuRes $msgStatus $msgDatEnt $msgDatProxint
					' '),
					 1,
					'$cod_empresa',
					$cod_usucada,
					'$dat_cadastro',
					'$cor',
					'$cod_status'
					 )";
		}

		if ($mod == 1289) {
			$cod_status = $cod_status_old;

			if ($cod_status == 6 || $cod_status == 10) {
				$cod_status = 16;
				$upStatus = ", COD_STATUS = 16";
			}
		}



		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$sql = "";

		if ($opcao != '') {

			if ($opcao == 'CAD') {

				if ($mod == 1289 && $cod_status == 10) {
					$cod_status = 16;
				}

				$sql .= "INSERT INTO SAC_COMENTARIO(
									COD_CHAMADO,
									DES_COMENTARIO,
									TP_COMENTARIO,
									COD_EMPRESA,
									COD_USUARIO,
									DAT_CADASTRO,
									COD_COR,
									COD_STATUS
									) VALUES(
									'$cod_chamado',
									'$des_comentario',
									'$tp_comentario',
									'$cod_empresa',
									$cod_usucada,
									'$dat_cadastro',
									'$cor',
									'$cod_status'
									) $msgFeed;";


				$sql .= "UPDATE SAC_CHAMADOS SET
					LOG_ANALISE=''
					$upSolicitacao
					$upUsures
					$upStatus
					$upDatEnt
					$upDatProxint
					WHERE COD_CHAMADO = $cod_chamado";

				// fnEscreve($sql);

				//fnTestesql($connAdmSAC->connAdm(), $sql);
				mysqli_multi_query($connAdmSAC->connAdm(), $sql);
				//fnMostraForm('#formulario');
?>
				<script>
					try {
						parent.$('#REFRESH_COMENTARIO').val("S");
					} catch (err) {}
				</script>
<?php

				if ($primeiroUp == "N") {

					$sql = "SELECT MAX(COD_COMENTARIO) AS COD_COMENTARIO FROM SAC_COMENTARIO WHERE COD_CHAMADO = $cod_chamado";
					$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);
					$qrComent = mysqli_fetch_assoc($arrayQuery);

					$sql = "UPDATE SAC_ANEXO SET 
									   COD_CHAMADO = $cod_chamado,
									   COD_EMPRESA = $cod_empresa,
									   COD_COMENTARIO = " . $qrComent['COD_COMENTARIO'] . "
									   WHERE 
									   COD_REFDOWN = $cod_refdown
									   ";
					// fnEscreve($sql);

					mysqli_query($connAdmSAC->connAdm(), $sql);
				}
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					$tipo_email = "Comentado";
					$novo_chamado = "Atualização - ";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					$tipo_email = "Alterado";
					$novo_chamado = "Atualização - ";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}

			$cod_chamado_sql = $cod_chamado;

			if ($tp_comentario == 2) {
				$todos = "N";
			} else {
				$todos = "";
			}

			/////////////////--Envio do Email--/////////////////
			/**/
			include 'envioEmailSac.php';		    /**/
			////////////////////////////////////////////////////			
			$msgTipo = 'alert-success';
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id']))) && fnDecode(@$_GET['id']) != 0) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 7;
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}

	//fnEscreve('entrou else');
}

@$sql = "SELECT COD_USURES, COD_STATUS, DAT_ENTREGA, DAT_PROXINT, COD_TPSOLICITACAO 
			FROM SAC_CHAMADOS WHERE COD_CHAMADO = $cod_chamado";
// fnEscreve($sql);
@$qrChmd = mysqli_fetch_assoc(mysqli_query($connAdmSAC->connAdm(), $sql));

if (fnDataShort($qrChmd['DAT_ENTREGA']) == "31/12/1969") {
	$dat_entrega = "";
} else {
	$dat_entrega = fnDataShort($qrChmd['DAT_ENTREGA']);
}

if (fnDataShort($qrChmd['DAT_PROXINT']) == "31/12/1969") {
	$dat_proxint = "";
} else {
	$dat_proxint = fnDataShort($qrChmd['DAT_PROXINT']);
}

if (isset($_GET['idU'])) {
	$conta = fnDecode(@$_GET['idU']);
}

// $sql = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE COD_CONTADOR = 2";
// mysqli_query($connAdmSAC->connAdm(),$sql);

// $sql = "SELECT NUM_CONTADOR FROM CONTADOR WHERE COD_CONTADOR = 2";
// $arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql);
// $qrCont = mysqli_fetch_assoc($arrayQuery);

// $conta = $qrCont['NUM_CONTADOR'];
// $primeiroUp = "S";



// fnEscreve($conta);

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

							<?php if ($mod == 1287) { ?>

								<div class="row">

									<div class="col-md-3 col-sm-3 col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Atribuir para:</label>
											<select data-placeholder="Selecione um usuário" name="COD_USURES" id="COD_USURES" class="chosen-select-deselect" style="width:100%!important;">
												<optgroup label="Usuários Marka">
													<?php

													$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
																	where (usuarios.COD_EMPRESA = 2 OR usuarios.COD_EMPRESA = 3)
																	and usuarios.DAT_EXCLUSA is null 
																	AND COD_TPUSUARIO IN(9,6,1,3)
																	AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

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

									<div class="col-md-3 col-sm-3 col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Status</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS" style="width:100%!important;">
												<?php

												$sql = "SELECT * FROM SAC_STATUS";
												$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);

												while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
												<?php } ?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3 col-sm-3 col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Prazo (interação)</label>
											<div class="input-group date datePicker" id="DAT_PROXINT_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_PROXINT" id="DAT_PROXINT" value="<?php echo $dat_proxint; ?>" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3 col-sm-3 col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Entrega</label>
											<div class="input-group date datePicker" id="DAT_ENTREGA_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_ENTREGA" id="DAT_ENTREGA" value="<?= $dat_entrega ?>" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo de Solicitação</label>
											<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o tipo" name="COD_TPSOLICITACAO" id="COD_TPSOLICITACAO" required>
												<option value=""></option>
												<?php

												$sql = "SELECT * FROM SAC_TPSOLICITACAO";
												$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);

												while ($qrSolicitacao = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrSolicitacao['COD_TPSOLICITACAO']; ?>"><?php echo $qrSolicitacao['DES_TPSOLICITACAO']; ?></option>
												<?php } ?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Gera Agendamento?</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_AGENDA" id="LOG_AGENDA" class="switch" value="S">
												<span></span>
											</label>
										</div>
									</div>

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
													<input type="radio" id="TP_COMENTARIO" name="TP_COMENTARIO" value="2">
													<div class="clab">Interno</div>
												</label>
											</div>
										</li>

									</ul>

								</div>

								<div class="push10"></div>
							<?php } else if ($mod == 1461) {
							?>

								<div class="row">

									<div class="col-md-3 col-sm-3 col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Status</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS" style="width:100%!important;">
												<?php

												$sql = "SELECT * FROM SAC_STATUS";
												$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);

												while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
												<?php } ?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

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
													<input type="radio" id="TP_COMENTARIO" name="TP_COMENTARIO" value="2">
													<div class="clab">Interno</div>
												</label>
											</div>
										</li>

									</ul>

								</div>

								<input type="hidden" name="COD_USURES" id="COD_USURES" value="<?= $qrChmd['COD_USURES'] ?>">
								<input type="hidden" name="DAT_ENTREGA" id="DAT_ENTREGA" value="<?= $dat_entrega ?>">
								<input type="hidden" name="DAT_PROXINT" id="DAT_PROXINT" value="<?= $dat_proxint ?>">

							<?php
							} else {

							?>

								<input type="hidden" name="COD_USURES" id="COD_USURES" value="<?= $qrChmd['COD_USURES'] ?>">
								<input type="hidden" name="COD_STATUS" id="COD_STATUS" value="<?= $qrChmd['COD_STATUS'] ?>">
								<input type="hidden" name="DAT_ENTREGA" id="DAT_ENTREGA" value="<?= $dat_entrega ?>">
								<input type="hidden" name="DAT_PROXINT" id="DAT_PROXINT" value="<?= $dat_proxint ?>">

							<?php

							} ?>

							<div class="row">

								<div class="col-lg-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Mensagem: </label>
										<textarea class="editor form-control input-sm" rows="6" name="DES_COMENTARIO" id="DES_COMENTARIO"></textarea>
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
									<?php include "addAnexoSac.php"; ?>
								</div>

							</div>

							<div class="row">

								<div class="col-md-6">
									<?php include "listaUploadSac.php"; ?>
								</div>

							</div>



							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-send" aria-hidden="true"></i>&nbsp; Enviar</button>
							</div>

							<input type="hidden" name="COD_CHAMADO" id="COD_CHAMADO" value="<?= $cod_chamado ?>">
							<input type="hidden" name="COD_USURES_OLD" id="COD_USURES_OLD" value="<?= $qrChmd['COD_USURES'] ?>">
							<input type="hidden" name="COD_STATUS_OLD" id="COD_STATUS_OLD" value="<?= $qrChmd['COD_STATUS'] ?>">
							<input type="hidden" name="DAT_ENTREGA_OLD" id="DAT_ENTREGA_OLD" value="<?= $dat_entrega ?>">
							<input type="hidden" name="DAT_PROXINT_OLD" id="DAT_PROXINT_OLD" value="<?= $dat_proxint ?>">
							<input type="hidden" name="COD_TPSOLICITA_OLD" id="COD_TPSOLICITA_OLD" value="<?= $qrChmd['COD_TPSOLICITACAO'] ?>">
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

		$('#COD_USURES').val("<?= $qrChmd['COD_USURES'] ?>").trigger("chosen:updated");
		$('#COD_STATUS').val("<?= $qrChmd['COD_STATUS'] ?>").trigger("chosen:updated");
		$('#COD_TPSOLICITACAO').val("<?= $qrChmd['COD_TPSOLICITACAO'] ?>").trigger("chosen:updated");

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#CAD").click(function(e) {
			if ($(".jqte_editor").text().trim() == "") {
				e.preventDefault();
				$.alert({
					title: "Aviso",
					content: "A mensagem não pode ser vazia.",
					type: 'orange'
				});
			}
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
						url: "ajxSacAnexo.php",
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