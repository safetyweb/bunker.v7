<?php

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";

$dias30 = "";
$dat_ini = "";
$dat_fim = "";

$cod_externo = "";
$cod_empresa = "";
$nom_chamado = "";

$cod_tpsolicitacao = "";
$cod_status = "";
$cod_status_exc = "13";
$cod_integradora = "";
$cod_plataforma = "";
$cod_versaointegra = "";
$cod_prioridade = "";

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);
		$dat_ini_ent = fnDataSql($_POST['DAT_INI_ENT']);
		$dat_fim_ent = fnDataSql($_POST['DAT_FIM_ENT']);
		$cod_chamado = $_POST['COD_CHAMADO'];
		$cod_externo = $_POST['COD_EXTERNO'];
		$cod_empresa = $_POST['COD_EMPRESA'];
		$cod_univend_ate = fnLimpacampoZero($_POST['COD_UNIVEND_ATE']);
		$nom_chamado = $_POST['NOM_CHAMADO'];

		$cod_tpsolicitacao = $_POST['COD_TPSOLICITACAO'];
		$cod_status = $_POST['COD_STATUS'];
		// $cod_status_exc = $_POST['COD_STATUS_EXC'];
		$cod_integradora = $_POST['COD_INTEGRADORA'];
		$cod_plataforma = $_POST['COD_PLATAFORMA'];
		$cod_versaointegra = $_POST['COD_VERSAOINTEGRA'];
		$cod_prioridade = $_POST['COD_PRIORIDADE'];
		$cod_usuario = $_POST['COD_USUARIO'];
		$cod_usures = $_POST['COD_USURES'];
		$cod_usuarios_env = $_POST['COD_USUARIOS_ENV'];
		$andFiltros = "";
		$des_tpfiltros = array();
		$colunas = "";
		$filtros = "";

		if (isset($_POST['COD_CLIENTES_ENV'])) {
			$Arr_COD_CLIENTES_ENV = $_POST['COD_CLIENTES_ENV'];

			for ($i = 0; $i < count($Arr_COD_CLIENTES_ENV); $i++) {
				$cod_clientes_env = $cod_clientes_env . $Arr_COD_CLIENTES_ENV[$i] . ",";
			}
			$cod_clientes_env = substr($cod_clientes_env, 0, -1);
		} else {
			$cod_clientes_env = "0";
		}

		if (isset($_POST['COD_STATUS_EXC'])) {
			$Arr_COD_STATUS_EXC = $_POST['COD_STATUS_EXC'];
			$cod_status_exc = "";

			for ($i = 0; $i < count($Arr_COD_STATUS_EXC); $i++) {
				$cod_status_exc = $cod_status_exc . $Arr_COD_STATUS_EXC[$i] . ",";
			}

			$cod_status_exc = rtrim($cod_status_exc, ',');
		} else {
			$cod_status_exc = "0";
		}

		$count_filtros = fnLimpacampo($_REQUEST['COUNT_FILTROS']);

		if($count_filtros != ""){

			for ($i=0; $i < $count_filtros; $i++) {

				$cod_filtro = "";

				if (isset($_POST["COD_FILTRO_$i"])){

					$Arr_COD_FILTRO = $_POST["COD_FILTRO_$i"];

					if(fnLimpacampo($_POST["COD_TPFILTRO_$i"]) != ''){

						$cod_filtro = $cod_filtro.fnLimpacampo($_POST["COD_TPFILTRO_$i"]).":";

					}

				    for ($j=0;$j<count($Arr_COD_FILTRO);$j++){

						$cod_filtro = $cod_filtro.$Arr_COD_FILTRO[$j].",";
						$filtros = $filtros.$Arr_COD_FILTRO[$j].",";

				    }

				}

				if($_POST["COD_FILTRO_$i"] != ''){

					$cod_filtro = rtrim($cod_filtro,',');

					$tpFiltros_e_filtros = $tpFiltros_e_filtros.$cod_filtro.';'; 

					$filtros_div = explode(':', $cod_filtro);

					$cod_tpfiltro = $filtros_div[0];
					$cod_filtros = $filtros_div[1];

					$sql = "SELECT DES_TPFILTRO FROM TIPO_FILTRO_ATENDIMENTO WHERE COD_TPFILTRO = $cod_tpfiltro";
					$qrTipo = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
					array_push($des_tpfiltros, $qrTipo['DES_TPFILTRO']);
					$campo = explode(' ',strtoupper(fnacentos($qrTipo['DES_TPFILTRO'])));

					$colunas .= $campo[0].$i.".DES_FILTRO AS $campo[0],";

					$cod_filtros = rtrim(ltrim($cod_filtros,','),',');

					$innerJoin .= "
								  INNER JOIN ATENDIMENTO_FILTROS ".$campo[0]." ON ".$campo[0].".COD_FILTRO IN($cod_filtros) AND ".$campo[0].".COD_TPFILTRO = $cod_tpfiltro AND ".$campo[0].".COD_ATENDIMENTO=AC.COD_ATENDIMENTO 
								  LEFT JOIN FILTROS_ATENDIMENTO ".$campo[0].$i." ON ".$campo[0].".COD_FILTRO = ".$campo[0].$i.".COD_FILTRO
					";

				}	

			}

			$filtros = rtrim(ltrim($filtros,','),',');
			// fnEscreve($innerJoin);

			if ($log_termo == "S") {
				$check_termo = "checked";
				$check_termoIni = "";
			}else{
				$check_termo = "";
				$check_termoIni = "";
			}
			// echo "<pre>";
			// print_r($des_tpfiltros);
			// echo "</pre>";

		}


		// fnEscreve($cod_status_exc);
		// fnEscreve($dat_fim_ent);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
		$usu_cadastr = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {


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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
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
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = "";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if (strlen($dat_ini_ent) == 0 || $dat_ini_ent == "1969-12-31") {
	$dat_ini_ent = fnDataSql($dias30);
}
if (strlen($dat_fim_ent) == 0 || $dat_fim_ent == "1969-12-31") {
	$dat_fim_ent = "";
}

if (isset($_GET['x'])) {
	$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
	$msgTipo = 'alert-success';
}

$usuResponsavel = $_SESSION['SYS_COD_USUARIO'];

$sqlResponsavel = "SELECT COD_USUARIOS_ATE 
						FROM USUARIOS 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_USUARIO = $usuResponsavel";

$arrayResp = mysqli_query($connAdm->connAdm(), $sqlResponsavel);
$qrResp = mysqli_fetch_assoc($arrayResp);

$usuariosAutorizados = $qrResp['COD_USUARIOS_ATE'];

//fnEscreve($cod_empresa);	
//fnEscreve($nom_empresa);	

//fnMostraForm('#formulario');



?>

<style type="text/css">
	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}

	.badge {
		display: table-cell;
		border-radius: 30px 30px 30px 30px;
		width: 26px;
		height: 26px;
		/*text-align: center;*/
		color: white;
		font-size: 11px;
		/*margin-right: auto;
    margin-left: auto;*/
	}

	.txtBadge {
		display: table-cell;
		vertical-align: middle;
	}

	.txtSideBadge {
		position: relative;
		display: table-cell;
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

	#impressao{
		display: none;
	}

	@media print {
	  	#impressao{
			display: block;
		}
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
					<span class="text-primary"><?php echo $NomePg; ?> </span>
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

				<?php $abaInfoAtendimento = 1435;
				include "abasInfoAtendimento.php";  ?>

				<div class="push20"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros para Busca</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Externo</label>
										<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="45" value="<?php echo $cod_externo; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código do Chamado</label>
										<input type="text" class="form-control input-sm" name="COD_CHAMADO" id="COD_CHAMADO" maxlength="45" value="<?php echo $cod_chamado; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Título do Chamado</label>
										<input type="text" class="form-control input-sm" name="NOM_CHAMADO" id="NOM_CHAMADO" maxlength="50" value="<?php echo $nom_chamado; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo de Solicitação</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o tipo" name="COD_TPSOLICITACAO" id="COD_TPSOLICITACAO">
											<option value=""></option>
											<?php

											$sql = "SELECT * FROM ATENDIMENTO_TPSOLICITACAO WHERE COD_EMPRESA = $cod_empresa";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

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
										<label for="inputName" class="control-label">Status</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS">
											<option value=""></option>
											<?php

											$sql = "SELECT * FROM ATENDIMENTO_STATUS WHERE COD_EMPRESA = $cod_empresa";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Prioridade</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Prioridade" name="COD_PRIORIDADE" id="COD_PRIORIDADE">
											<option value=""></option>
											<?php

											$sql = "SELECT COD_PRIORIDADE, ABV_PRIORIDADE FROM ATENDIMENTO_PRIORIDADE WHERE COD_EMPRESA = $cod_empresa";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrPrioridade = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrPrioridade['COD_PRIORIDADE']; ?>"><?php echo $qrPrioridade['ABV_PRIORIDADE']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-4">

									<fieldset>
										<legend>Data de Cadastro</legend>

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Inicial</label>

												<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="" />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Final</label>

												<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="push5"></div>

									</fieldset>

								</div>

								<div class="col-md-4">

									<fieldset>
										<legend>Prazo</legend>

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Inicial</label>

												<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_INI_ENT" id="DAT_INI_ENT" value="" />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Final</label>

												<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
													<input type='text' class="form-control input-sm data" name="DAT_FIM_ENT" id="DAT_FIM_ENT" value="<?php echo fnFormatDate($dat_fim_ent); ?>" />
													<span class="input-group-addon">
														<span class="glyphicon glyphicon-calendar"></span>
													</span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="push5"></div>

									</fieldset>

								</div>

								<div class="col-md-4">

									<div class="row">

										<!-- <div class="col-md-6">
																<div class="form-group">
																	<label for="inputName" class="control-label">Responsável</label>
																		<select data-placeholder="Selecione um colaborador" name="COD_USURES" id="COD_USURES" class="chosen-select-deselect requiredChk" style="width:100%;">
																			<option value="">Todos os Responsáveis</option>
																			<option value="0">Sem Responsável</option>
																		    <?php

																			$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
																				where usuarios.COD_EMPRESA = $cod_empresa
																				and usuarios.DAT_EXCLUSA is null
																				AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
																			$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

																			while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
																				echo "
																					  <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
																					";
																			}
																			?> 
																		</select>
																	<div class="help-block with-errors"></div>
																</div>
															</div> -->

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Responsável</label>
												<select data-placeholder="Selecione um colaborador" name="COD_USURES" id="COD_USURES" class="chosen-select-deselect requiredChk" style="width:100%;">
													<option value=""></option>
													<?php

													if ($usuariosAutorizados == "") {
														$andUsuAutorizados = "AND COD_USUARIO IN(0)";
													} else if ($usuariosAutorizados == "9999") {
														$andUsuAutorizados = "";
													} else {
														$andUsuAutorizados = "AND COD_USUARIO IN($usuariosAutorizados)";
													}

													$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
																				where usuarios.COD_EMPRESA = $cod_empresa
																				$andUsuAutorizados
																				and usuarios.DAT_EXCLUSA is null
																				
																				AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

													while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																					  <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
																					";
													}
													?>
												</select>
												<div class="help-block with-errors"></div>
												<script>
													$("#COD_USURES").val("<?= $cod_usures ?>").trigger("chosen:updated");
												</script>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Cadastrou</label>

												<select data-placeholder="Colaboradores" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect requiredChk" style="width:100%;">
													<option value=""></option>
													<?php
													$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
																				where usuarios.COD_EMPRESA = $cod_empresa
																				and usuarios.DAT_EXCLUSA is null
																				AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

													while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																					  <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
																					";
													}
													?>
												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>

									<div class="row">

										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label">Remover Status</label>
												<select data-placeholder="Selecione o status" name="COD_STATUS_EXC[]" id="COD_STATUS_EXC" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
													<?php

													$sql = "SELECT * FROM ATENDIMENTO_STATUS WHERE COD_EMPRESA = $cod_empresa";
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

													while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
													?>
														<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
													<?php
													}
													?>
												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-6">
											<label for="inputName" class="control-label">Solicitantes</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&op=AGE&pop=true" data-title="Busca Apoiadores"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
												</span>
												<select data-placeholder="Nenhum colaborador selecionado" name="COD_CLIENTES_ENV[]" id="COD_CLIENTES_ENV" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
													<?php

													if ($cod_clientes_env != "") {
														$sql = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE IN($cod_clientes_env)";
														$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

														while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
															echo "
																						  <option value='" . $qrLista['COD_CLIENTE'] . "'>" . $qrLista['NOM_CLIENTE'] . "</option> 
																						";
														}
													}

													?>
												</select>
												<!-- <?php if ($cod_clientes_env != "") {
															fnEscreve($sql);
														} ?> -->
											</div>
											<div class="help-block with-errors"></div>
										</div>

									</div>

								</div>

							</div>

							<?php if ($cod_empresa == 311) { ?>

								<div class="push10"></div>

								<div class="row">

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Secretaria</label>
											<div id="relatorioUnivend">
												<select data-placeholder="Selecione a Secretaria" name="COD_UNIVEND_ATE" id="COD_UNIVEND_ATE" class="chosen-select-deselect">
													<option value=""></option>
													<?php
													$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' AND LOG_ESTATUS = 'S' order by NOM_UNIVEND ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

													while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																					<option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
																				";
													}
													?>
												</select>
												<div class="help-block with-errors"></div>
												<script>
													$("#COD_UNIVEND_ATE").val("<?= $cod_univend_ate ?>").trigger("chosen:updated");
												</script>
											</div>
										</div>
									</div>

								</div>

							<?php } ?>

							<div class="push10"></div>

							<?php
								//FILTROS DINÂMICOS
								$countFiltros = 0;

								$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO_ATENDIMENTO
								WHERE COD_EMPRESA = $cod_empresa
								ORDER BY NUM_ORDENAC";
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),trim($sql));

								if(mysqli_num_rows($arrayQuery) > 0){
								
								$countObjeto = 0
							?>
							
							<!-- filtros dinâmicos -->
							
							<fieldset>
								<legend>Filtros Dinâmicos</legend>

											
								<div class="row">
												
								<?php 
									while($qrTipo = mysqli_fetch_assoc($arrayQuery)){
								?>

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label"><?=$qrTipo['DES_TPFILTRO']?></label>
												<div id="relatorioFiltro_<?=$countFiltros?>">
													<input type="hidden" name="COD_TPFILTRO_<?=$countFiltros?>" id="COD_TPFILTRO_<?=$countFiltros?>" value="<?=$qrTipo['COD_TPFILTRO']?>">
													<select data-placeholder="Selecione os filtros" name="COD_FILTRO_<?=$countFiltros?>[]" id="COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?>" multiple="multiple" class="chosen-select-deselect last-chosen-link">
														<option value=""></option>
														<option value="0">Sem Informação</option>
								<?php
														$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_ATENDIMENTO
																	  WHERE COD_TPFILTRO = $qrTipo[COD_TPFILTRO]
																	  ORDER BY DES_FILTRO";

														$arrayFiltros = mysqli_query(connTemp($cod_empresa,''),trim($sqlFiltro));
														while($qrFiltros = mysqli_fetch_assoc($arrayFiltros)){
								?>

															<option value="<?=$qrFiltros['COD_FILTRO']?>"><?=$qrFiltros['DES_FILTRO']?></option>

								<?php 
														}
															
								?>
														<script>

														</script>
											
													</select>
													<div class="help-block with-errors"></div>
													<a class="btn btn-default btn-sm" id="iAll_<?=$countFiltros?>" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
													<a class="btn btn-default btn-sm" id="iNone_<?=$countFiltros?>" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
													<script>
														$(function(){
															$('#iAll_<?=$countFiltros?>').on('click', function(e){
															  e.preventDefault();
															  $('#COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?> option').prop('selected', true).trigger('chosen:updated');
															});

															$('#iNone_<?=$countFiltros?>').on('click', function(e){
															  e.preventDefault();
															  $("#COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?> option:selected").removeAttr("selected").trigger('chosen:updated');
															});
														});
													</script>
												</div>
											</div>
										</div>

								<?php 	
										if($countObjeto == 3){
											$countObjeto = 0;
											echo '<div class="push10"></div>';
										}else{
											$countObjeto++;
										}
										$countFiltros++;
									}
								?>										
										
								</div>

												

									<?php 
										}
									?>										
								
							</fieldset>	

						<div class="push30"></div>

						<div class="row">

							<div class="col-md-2">
								<label for="inputName" class="control-label">&nbsp;</label>
								<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
							</div>

						</div>

					</fieldset>

					<div class="push30"></div>

					<a href="action.php?mod=<?php echo fnEncode(1436) . "&id=" . fnEncode($cod_empresa); ?>" name="ADD" id="ADD" class="btn btn-success pull-left"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Criar Novo Atendimento</a>

						
						<div class="col-md-4 col-sm-offset-1">
							<div class="content-top">											
								<div class="col-md-8 top-content">
									<p>Cadastros Totais</p>
									<?php
										$sql = "SELECT COD_ATENDIMENTO FROM ATENDIMENTO_CHAMADOS WHERE COD_EMPRESA = $cod_empresa";
										$totAtendimento = mysqli_num_rows(mysqli_query(connTemp($cod_empresa,''),$sql));
									?>
									<label><?=fnValor($totAtendimento,0)?></label>
									<?php if (00 > 0){ ?>
									<br/>
									<span class="bg-danger f12" style="padding: 1px 4px; color: #fff; border-radius: 3px;"><?= fnValor(00,0); ?></span>
									<?php } ?>
								</div>
								<div class="col-md-4">	   
									<div id="main-pie" class="pie-title-center" data-percent="100">
										<span class="pie-value">100%</span>
									</div>
								</div>
								<div class="clearfix"> </div>
							</div>	
						</div>

						<div class="col-md-4">
							<div class="content-top">											
								<div class="col-md-8 top-content">
									<p>Cadastros Busca</p>
									<label id="total"></label>
									<?php if (00 > 0){ ?>
									<br/>
									<span class="bg-danger f12" style="padding: 1px 4px; color: #fff; border-radius: 3px;"><?= fnValor(00,0); ?></span>
									<?php } ?>
								</div>
								<div class="col-md-4">	   
									<div id="main-pie2" class="pie-title-center" data-percent="">
										<span class="pie-value" id="percent"></span>
									</div>
								</div>
								<div class="clearfix"> </div>
							</div>	
						</div>
						<div class="push10"></div>
						
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="COD_CLIENTE_ENV" id="COD_CLIENTE_ENV" value="">
						<input type="hidden" name="NOM_CLIENTE_ENV" id="NOM_CLIENTE_ENV" value="">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
						<input type="hidden" name="COUNT_FILTROS" id="COUNT_FILTROS" value="<?=$countFiltros?>">
						<input type="hidden" name="USUARIOS_AUT" id="USUARIOS_AUT" value="<?= fnEncode($usuariosAutorizados) ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>
				</div>
			</div>
		</div>
		<div class="push20"></div>
		
		<div class="portlet portlet-bordered">
			
			<div class="portlet-body">
				
				<div class="login-form">

					<div class="push30"></div>

					<div class="col-lg-12" style="padding:0;">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th><small>Chamado</small></th>
											<th><small>Título</small></th>
											<?php if ($cod_empresa == 311) { ?>
												<th><small>Secretaria</small></th>
											<?php } ?>
											<th><small>Solicitantes</small></th>
											<th><small>Solicitação</small></th>
											<!-- <th><small>Responsável</small></th> -->
											<th><small>Prioridade</small></th>
											<th><small>Status</small></th>
											<th><small>Cadastro</small></th>
											<th><small>Prazo</small></th>
											<th><small>Atualizado</small></th>
											<?php

												for ($i=0; $i < count($des_tpfiltros); $i++) { 
											?>
													<th><small><?=$des_tpfiltros[$i]?></small></th>
											<?php	
												}

											?>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										if ($dat_ini == "") {
											$ANDdatIni = " ";
										} else {
											$ANDdatIni = "AND DATE_FORMAT(AC.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";
										}

										if ($dat_ini_ent == date('Y-m-d')) {
											$ANDdatIniEnt = " ";
										} else {
											$ANDdatIniEnt = "AND DATE_FORMAT(AC.DAT_ENTREGA, '%Y-%m-%d') >= '$dat_ini_ent'";
										}

										if ($dat_fim_ent == "") {
											$ANDdatFimEnt = " ";
										} else {
											$ANDdatFimEnt = "AND DATE_FORMAT(AC.DAT_ENTREGA, '%Y-%m-%d') <= '$dat_fim_ent'";
										}

										if ($cod_externo == "") {
											$ANDcodExterno = " ";
										} else {
											$ANDcodExterno = "AND AC.COD_EXTERNO LIKE '%$cod_externo%' ";
										}

										if ($cod_chamado == "") {
											$ANDcodChamado = " ";
										} else {
											$ANDcodChamado = "AND AC.COD_ATENDIMENTO = $cod_chamado ";
										}

										if ($cod_empresa == "") {
											$ANDcodEmpresa = " ";
										} else {
											$ANDcodEmpresa = "AND AC.COD_EMPRESA = $cod_empresa ";
										}

										if ($nom_chamado == "") {
											$ANDnomChamado = " ";
										} else {
											$ANDnomChamado = "AND AC.NOM_CHAMADO LIKE '%$nom_chamado%' ";
										}

										if ($cod_tpsolicitacao == "") {
											$ANDcodTipo = " ";
										} else {
											$ANDcodTipo = "AND AC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";
										}

										if ($cod_status == "") {
											$ANDcodStatus = "";
										} else {
											$ANDcodStatus = "AND AC.COD_STATUS = $cod_status ";
										}

										if ($cod_status_exc == "0") {
											$ANDcodStatusExc = "";
										} else {
											$ANDcodStatusExc = "AND AC.COD_STATUS NOT IN($cod_status_exc) ";
										}

										if ($cod_integradora == "") {
											$ANDcodIntegradora = " ";
										} else {
											$ANDcodIntegradora = "AND AC.COD_INTEGRADORA = $cod_integradora ";
										}

										if ($cod_plataforma == "") {
											$ANDcodPlataforma = " ";
										} else {
											$ANDcodPlataforma = "AND AC.COD_PLATAFORMA = $cod_plataforma ";
										}

										if ($cod_versaointegra == "") {
											$ANDcodVersaointegra = " ";
										} else {
											$ANDcodStatus = "AND AC.COD_VERSAOINTEGRA = $cod_versaointegra ";
										}

										if ($cod_prioridade == "") {
											$ANDcodPrioridade = " ";
										} else {
											$ANDcodPrioridade = "AND AC.COD_PRIORIDADE = $cod_prioridade ";
										}

										if ($cod_usuario == "") {
											$ANDcodUsuario = " ";
										} else {
											$ANDcodUsuario = "AND AC.COD_SOLICITANTE = $cod_usuario ";
										}

										if ($cod_usures == "") {
											$ANDcod_usures = " ";
										} else {
											$ANDcod_usures = "AND AC.COD_USURES = $cod_usures ";
										}

										if ($cod_univend_ate == 0) {
											$ANDcod_univend_ate = " ";
										} else {
											$ANDcod_univend_ate = "AND AC.COD_UNIVEND_ATE = $cod_univend_ate ";
										}

										if ($cod_usuarios_env == "") {
											$ANDcod_usuarios_env = " ";
										} else {
											$ANDcod_usuarios_env = "AND AC.COD_USUARIOS_ENV IN($cod_usuarios_env)";
										}

										if ($cod_clientes_env == "" || $cod_clientes_env == 0) {
											$ANDcod_clientes_env = " ";
										} else {

											$clientes = explode(',', $cod_clientes_env);

											if (count($clientes) > 1) {

												$ANDcod_clientes_env = "AND (";

												for ($i = 0; $i < count($clientes); $i++) {

													if ($i == 0) {
														$ANDcod_clientes_env .= "FIND_IN_SET('" . $clientes[0] . "', AC.COD_CLIENTES_ENV) ";
													} else {
														$ANDcod_clientes_env .= "OR FIND_IN_SET('" . $clientes[$i] . "', AC.COD_CLIENTES_ENV) ";
													}
												}

												$ANDcod_clientes_env .= ")";
											} else {

												$ANDcod_clientes_env = "AND FIND_IN_SET('$cod_clientes_env', AC.COD_CLIENTES_ENV)";
											}
										}

										if ($usuariosAutorizados == "") {
											$andAutorizados = "AND COD_USURES IN(0)";
										} else if ($usuariosAutorizados == "9999") {
											$andAutorizados = "";
										} else {
											$andAutorizados = "AND COD_USURES IN($usuariosAutorizados)";
										}


										$sqlCount = "SELECT AC.COD_ATENDIMENTO FROM ATENDIMENTO_CHAMADOS AC 
																$innerJoin
												  				WHERE AC.COD_EMPRESA = $cod_empresa
												  				AND DATE_FORMAT(AC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim'
												  				$andAutorizados
												  				$ANDdatIni
												  				$ANDcodExterno
												  				$ANDcodChamado
												  				$ANDnomChamado
												  				$ANDcodStatus
												  				$ANDcodTipo
												  				$ANDcodIntegradora
												  				$ANDcodPlataforma
												  				$ANDcodVersaointegra
												  				$ANDcodPrioridade
												  				$ANDcod_usures
												  				$ANDcodUsuario
												  				$ANDcodStatusExc
												  				$ANDdatIniEnt
												  				$ANDdatFimEnt	
												  				$ANDcod_usuarios_env											  				
												  				$ANDcod_clientes_env
												  				$ANDcod_univend_ate										  				
																";
										// fnEscreve($sqlCount);

										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sqlCount);
										$total_itens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sqlSac = "SELECT AC.*, AT.DES_TPSOLICITACAO, 
																AP.DES_PRIORIDADE, AP.DES_COR AS COR_PRIORIDADE, AP.DES_ICONE AS ICO_PRIORIDADE,
																AST.ABV_STATUS, AST.DES_COR AS COR_STATUS, AST.DES_ICONE AS ICO_STATUS,
																$colunas
																UV.NOM_FANTASI AS SECRETARIA
																FROM ATENDIMENTO_CHAMADOS AC
																$innerJoin
																LEFT JOIN ATENDIMENTO_PRIORIDADE AP ON AP.COD_PRIORIDADE = AC.COD_PRIORIDADE
																LEFT JOIN ATENDIMENTO_STATUS AST ON AST.COD_STATUS = AC.COD_STATUS
																LEFT JOIN ATENDIMENTO_TPSOLICITACAO AT ON AT.COD_TPSOLICITACAO = AC.COD_TPSOLICITACAO
																LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND=AC.COD_UNIVEND_ATE
																WHERE AC.COD_EMPRESA = $cod_empresa
												  				AND DATE_FORMAT(AC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim'
												  				$andAutorizados
												  				$ANDdatIni
												  				$ANDcodExterno
												  				$ANDcodChamado
												  				$ANDnomChamado
												  				$ANDcodStatus
												  				$ANDcodTipo
												  				$ANDcodIntegradora
												  				$ANDcodPlataforma
												  				$ANDcodVersaointegra
												  				$ANDcodPrioridade
												  				$ANDcod_usures
												  				$ANDcodUsuario
												  				$ANDcodStatusExc
												  				$ANDdatIniEnt
												  				$ANDdatFimEnt
												  				$ANDcod_usuarios_env
												  				$ANDcod_clientes_env
												  				$ANDcod_univend_ate
																ORDER BY AC.COD_ATENDIMENTO DESC limit $inicio,$itens_por_pagina
																";
										// fnEscreve($sqlSac);

										$arrayQuerySac = mysqli_query(connTemp($cod_empresa, ''), $sqlSac);

										$count = 0;
										$adm = "";
										$entrega = "";
										while ($qrSac = mysqli_fetch_assoc($arrayQuerySac)) {

											if ($qrSac['LOG_ADM'] == 'S') {
												$adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
											} else {
												$adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
											}

											$count++;


											$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_SOLICITANTE]) AS NOM_SOLICITANTE,
																				(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
											$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));
											//fnEscreve($sqlUsuarios);										  

											if ($qrSac['DAT_ENTREGA'] == "1969-12-31") {
												$entrega = "";
											} else {
												$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
											}

											if ($qrSac['DAT_INTERAC'] != "") {
												if (fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)) {
													$atualizado = "Hoje";
												} else if (fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))) {
													$atualizado = "Ontem";
												} else {
													$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
												}
											} else {
												$atualizado = "";
											}

											$clientes_env = "";

											$sqlCli = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES 
																WHERE COD_CLIENTE IN($qrSac[COD_CLIENTES_ENV])";
											$arrayQueryCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

											while ($qrLista = mysqli_fetch_assoc($arrayQueryCli)) {
												$clientes_env .= $qrLista[NOM_CLIENTE] . ", ";
											}

											$clientes_env = rtrim(ltrim(trim($clientes_env), ','), ',');

											$linhas = "";

											for ($i=0; $i < count($des_tpfiltros); $i++) {
												$alias = explode(' ',strtoupper(fnacentos($des_tpfiltros[$i])));
												if($qrSac[$alias[0]] == ""){
													$linhas .= "<td><small>SEM INFORMAÇÃO</small></td>"; 
												}else{
													$linhas .= "<td><small>".$qrSac[$alias[0]]."</small></td>";
												}
											}

											//$diff_dias = fnDateDif($qrSac['DAT_CADASTR'],Date("Y-m-d"));
											// fnEscreve(fnDatasql($qrSac['DAT_INTERAC']));
										?>

											<tr>
												<td class="text-center">
													<small>
														<a href="action.php?mod=<?= fnEncode(1440); ?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']); ?>&idC=<?php echo fnEncode($qrSac['COD_ATENDIMENTO']); ?>" target="_blank"><?= $qrSac['COD_ATENDIMENTO'] ?>&nbsp;
															<span class="fa fa-external-link-square"></span>
														</a>
													</small>
												</td>
												<td><small><?= $qrSac['NOM_CHAMADO'] ?></small></td>
												<?php if ($cod_empresa == 311) { ?>
													<td><small><?= $qrSac['SECRETARIA'] ?></small></td>
												<?php } ?>
												<td><small><?= $clientes_env ?></small></td>
												<td><small><?= $qrSac['DES_TPSOLICITACAO'] ?></small></td>
												<!-- <td><small><?= $qrNomUsu['NOM_RESPONSAVEL'] ?></small></td> -->

												<td class="text-center">
													<small>
														<p class="label" style="background-color: <?php echo $qrSac['COR_PRIORIDADE'] ?>">
															<span class="<?php echo $qrSac['ICO_PRIORIDADE']; ?>" style="color: #FFF;"></span>
															<!-- &nbsp; <?php echo $qrSac['DES_PRIORIDADE']; ?> -->
														</p>
													</small>
												</td>

												<td class="text-center">
													<small>
														<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>">
															<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
															&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
														</p>
													</small>
												</td>

												<td class="text-center"><small><?= fnDataShort($qrSac['DAT_CADASTR']); ?></small></td>
												<td class="text-center"><small><?= $entrega ?></small></td>
												<td class="text-center"><small><?= $atualizado ?></small></td>
												<?=$linhas?>

											</tr>
										<?php
										}
										?>

									</tbody>
									<tfoot>
										<tr>
											<th>
												<a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
											</th>
											<th>
												<a class="btn btn-primary btn-sm print"> <i class="fal fa-print" aria-hidden="true"></i>&nbsp; Imprimir</a>
											</th>
										</tr>
										<tr>
											<th class="" colspan="100">
												<center>
													<ul id="paginacao" class="pagination-sm"></ul>
												</center>
											</th>
										</tr>
									</tfoot>
								</table>



							</form>

							<div class="push10"></div>

						</div>

					</div>

					<div class="push30"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div id="impressao"></div>

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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<script src="js/pie-chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>    
<script src="js/plugins/Chart_Js/utils.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
<script src='js/printThis.js'></script>

<script type="text/javascript">
	function retornaForm(index) {

		// var plataforma = '<?php echo $cod_plataforma; ?>';
		// if(plataforma != 0 && plataforma != ""){$("#formulario #COD_PLATAFORMA").val(<?php echo $cod_plataforma; ?>).trigger("chosen:updated");}

		var empresa = '<?php echo $cod_empresa; ?>';
		if (empresa != 0 && empresa != "") {
			$("#formulario #COD_EMPRESA").val(<?php echo $cod_empresa; ?>).trigger("chosen:updated");
		}

		// var versaointegra = '<?php echo $cod_versaointegra; ?>';
		// if(versaointegra != 0 && versaointegra != ""){$("#formulario #COD_VERSAOINTEGRA").val(<?php echo $cod_versaointegra; ?>).trigger("chosen:updated");}

		// var integradora = '<?php echo $cod_integradora; ?>';
		// if(integradora != 0 && integradora != ""){$("#formulario #COD_INTEGRADORA").val(<?php echo $cod_integradora; ?>).trigger("chosen:updated");}

		var tpsolicitacao = '<?php echo $cod_tpsolicitacao; ?>';
		if (tpsolicitacao != 0 && tpsolicitacao != "") {
			$("#formulario #COD_TPSOLICITACAO").val(<?php echo $cod_tpsolicitacao; ?>).trigger("chosen:updated");
		}

		var prioridade = '<?php echo $cod_prioridade; ?>';
		if (prioridade != 0 && prioridade != "") {
			$("#formulario #COD_PRIORIDADE").val(<?php echo $cod_prioridade; ?>).trigger("chosen:updated");
		}

		var status = '<?php echo $cod_status; ?>';
		if (status != 0 && status != "") {
			$("#formulario #COD_STATUS").val(<?php echo $cod_status; ?>).trigger("chosen:updated");
		}

		var status_exc = '<?php echo $cod_status_exc; ?>';
		if (status_exc != 0 && status_exc != "") {
			$("#formulario #COD_STATUS_EXC").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_status_exc; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_STATUS_EXC option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_STATUS_EXC").trigger("chosen:updated");
		}

		// var usures = '<?php echo $cod_usures; ?>';
		// if(usures != 0 && usures != ""){$("#formulario #COD_USURES").val(<?php echo $cod_usures; ?>).trigger("chosen:updated");}

		var sistemasUniArr = $("#COD_CLIENTES_ENV").val();

		// alert(sistemasUniArr);

		var clientes_env = '<?php echo $cod_clientes_env; ?>';
		if (clientes_env != 0 && clientes_env != "") {
			$("#formulario #COD_CLIENTES_ENV").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_clientes_env; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_CLIENTES_ENV option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_CLIENTES_ENV").trigger("chosen:updated");
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$(document).ready(function() {

		var percent = "<?=ceil((($total_itens_por_pagina/$totAtendimento)*100))?>";
		// alert(percent);

		$("#total").text("<?=$total_itens_por_pagina?>");
		$("#main-pie2").attr("data-percent",percent);
		// $("#percent").text('1');

		$('#main-pie,#main-pie2').pieChart({
            barColor: '#2c3e50',
            trackColor: '#eee',
            lineCap: 'round',
            lineWidth: 8,
            onStep: function (from, to, percent) {
                $(this.element).find('.pie-value').text(Math.round(percent) + '%');
            }
        });

		retornaForm(0);

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		$('.modal').on('hidden.bs.modal', function() {

			if ($('#REFRESH_CLIENTE').val() == "S") {

				$("#COD_CLIENTES_ENV").append('<option value="' + $("#COD_CLIENTE_ENV").val() + '">' + $("#NOM_CLIENTE_ENV").val() + '</option>').trigger("chosen:updated");

				var sistemasUniArr = $("#COD_CLIENTES_ENV").val();

				// alert(sistemasUniArr);

				if (sistemasUniArr) {

					//opções multiplas
					for (var i = 0; i < sistemasUniArr.length; i++) {
						$("#formulario #COD_CLIENTES_ENV option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
					}

				}

				$("#formulario #COD_CLIENTES_ENV option[value=" + $("#COD_CLIENTE_ENV").val() + "]").prop("selected", "true").trigger("chosen:updated");

				$('#REFRESH_CLIENTE').val('N');

			}

		});

		$(".print").click(function(){
			$.ajax({
				type: "POST",
				url: "ajxImpAtendimentos.do?id=<?= fnEncode($cod_empresa) ?>",
				data: $('#formulario').serialize(),
				beforeSend: function() {
					$('#blocker').show();
				},
				success: function(data) {
					$('#blocker').hide();
					$("#impressao").html(data).printThis();
				},
				error: function() {
					// $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});
		});

		$(".exportarCSV").click(function() {
				$.confirm({
					title: 'Exportação',
					content: '' +
						'<form action="" class="formName">' +
						'<div class="form-group">' +
						'<label>Insira o nome do arquivo:</label>' +
						'<input type="text" placeholder="Nome" class="nome form-control" required />' +
						'</div>' +
						'</form>',
					buttons: {
						formSubmit: {
							text: 'Gerar',
							btnClass: 'btn-blue',
							action: function() {
								var nome = this.$content.find('.nome').val();
								if (!nome) {
									$.alert('Por favor, insira um nome');
									return false;
								}

								$.confirm({
									title: 'Mensagem',
									type: 'green',
									icon: 'fa fa-check-square-o',
									content: function() {
										var self = this;
										return $.ajax({
											url: "ajxListaAtendimentos.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
											data: $('#formulario').serialize(),
											method: 'POST'
										}).done(function(response) {
											self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
											var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
											SaveToDisk('media/excel/' + fileName, fileName);
											console.log(response);
										}).fail(function() {
											self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
										});
									},
									buttons: {
										fechar: function() {
											//close
										}
									}
								});
							}
						},
						cancelar: function() {
							//close
						},
					}
				});
			});

		var tpFiltros_e_filtros = '<?php echo $tpFiltros_e_filtros; ?>';
			
		if(tpFiltros_e_filtros != ""){

			var todosFiltros = tpFiltros_e_filtros.split(';');

			for (var i = 0; i < todosFiltros.length; i++) {

				var arrTpFiltro_e_filtros = todosFiltros[i].split(':');

				if(arrTpFiltro_e_filtros[0] != ''){

					var filtros = arrTpFiltro_e_filtros[1].split(',');

					for (var j = 0; j < filtros.length; j++) {

						$("#COD_FILTRO_"+arrTpFiltro_e_filtros[0]+" option[value=" + Number(filtros[j]) + "]").prop("selected", "true");

					}

				}					

				$("#COD_FILTRO_"+arrTpFiltro_e_filtros[0]).trigger("chosen:updated");

			}

		}	

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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "ajxListaAtendimentos.do?id=<?= fnEncode($cod_empresa) ?>&opcao=paginar&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes/');
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
</script>