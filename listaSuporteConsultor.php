<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$cod_status_exc = "";
$cod_tipo_exc = "";
$hashLocal = "";
$hoje = "";
$dias30 = "";
$cod_usucada = "";
$cod_usuario = "";
$cod_usures = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$dat_ini_ent = "";
$dat_fim_ent = "";
$cod_chamado = "";
$cod_externo = "";
$nom_chamado = "";
$cod_tpsolicitacao = "";
$cod_status = "";
$cod_integradora = "";
$cod_plataforma = "";
$cod_versaointegra = "";
$cod_prioridade = "";
$Arr_COD_STATUS_EXC = "";
$hHabilitado = "";
$hashForm = "";
$usu_cadastr = "";
$nom_empresa = "";
$formBack = "";
$abaInfoSuporte = "";
$andFiltro = "";
$arrayQuery = [];
$qrEmpresa = "";
$qrStatus = "";
$qrPrioridade = "";
$qrTipo = "";
$ANDdatIni = "";
$ANDdatIniEnt = "";
$ANDdatFimEnt = "";
$ANDcodExterno = "";
$ANDcodChamado = "";
$ANDcodEmpresa = "";
$ANDnomChamado = "";
$ANDcodTipo = "";
$ANDcodStatus = "";
$ANDcodStatusExc = "";
$ANDcodTipoExc = "";
$ANDcodIntegradora = "";
$ANDcodPlataforma = "";
$ANDcodVersaointegra = "";
$ANDcodPrioridade = "";
$ANDcodUsuario = "";
$ANDcod_usures = "";
$sqlCount = "";
$retorno = "";
$inicio = "";
$sqlSac = "";
$arrayQuerySac = [];
$entrega = "";
$qrSac = "";
$sqlEmpresa = "";
$qrNomEmp = "";
$sqlUsuarios = "";
$qrNomUsu = "";
$proxInt = "";
$atualizado = "";
$difference = "";
$corDiff = "";
$badgeDias = "";
$diff_dias = "";




// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";
$cod_status_exc = "10,6";
$cod_tipo_exc = "21";
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));


$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

$cod_usuario = $cod_usucada;
$cod_usures = $cod_usucada;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$dat_ini_ent = fnDataSql(@$_POST['DAT_INI_ENT']);
		$dat_fim_ent = fnDataSql(@$_POST['DAT_FIM_ENT']);
		$cod_chamado = @$_POST['COD_CHAMADO'];
		$cod_externo = @$_POST['COD_EXTERNO'];
		$cod_empresa = @$_POST['COD_EMPRESA'];
		$nom_chamado = @$_POST['NOM_CHAMADO'];

		$cod_tpsolicitacao = @$_POST['COD_TPSOLICITACAO'];
		$cod_status = @$_POST['COD_STATUS'];
		// $cod_status_exc = @$_POST['COD_STATUS_EXC'];
		$cod_integradora = @$_POST['COD_INTEGRADORA'];
		$cod_plataforma = @$_POST['COD_PLATAFORMA'];
		$cod_versaointegra = @$_POST['COD_VERSAOINTEGRA'];
		$cod_prioridade = @$_POST['COD_PRIORIDADE'];
		$cod_usuario = @$_POST['COD_USUARIO'];
		$cod_usures = $cod_usucada;

		if (isset($_POST['COD_STATUS_EXC'])) {
			$Arr_COD_STATUS_EXC = @$_POST['COD_STATUS_EXC'];
			$cod_status_exc = "";

			for ($i = 0; $i < count($Arr_COD_STATUS_EXC); $i++) {
				$cod_status_exc = $cod_status_exc . $Arr_COD_STATUS_EXC[$i] . ",";
			}

			$cod_status_exc = rtrim($cod_status_exc, ',');
		} else {
			$cod_status_exc = "0";
		}

		if (isset($_POST['COD_TIPO_EXC'])) {
			$Arr_COD_TIPO_EXC = @$_POST['COD_TIPO_EXC'];
			$cod_tipo_exc = "";

			for ($i = 0; $i < count($Arr_COD_TIPO_EXC); $i++) {
				$cod_tipo_exc = $cod_tipo_exc . $Arr_COD_TIPO_EXC[$i] . ",";
			}

			$cod_tipo_exc = rtrim($cod_tipo_exc, ',');
		} else {
			$cod_tipo_exc = "0";
		}


		// fnEscreve($cod_status_exc);
		// fnEscreve($dat_fim_ent);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
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
</style>

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

				<?php $abaInfoSuporte = 1433;
				include "abasInfoSuporteConsultor.php";  ?>

				<div class="push20"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros para Busca</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Empresa</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a empresa" name="COD_EMPRESA" id="COD_EMPRESA">
											<option value=""></option>
											<?php

											//$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM EMPRESAS";
											if ($_SESSION["SYS_COD_MASTER"] == "2") {
												$sql = "SELECT empresas.COD_EMPRESA, empresas.NOM_FANTASI
																				FROM empresas  
																				WHERE empresas.COD_EMPRESA <> 1 
																				$andFiltro
																				ORDER by NOM_FANTASI
																		";
												//fnEscreve("1");
											} else {
												$sql = "SELECT empresas.COD_EMPRESA, empresas.NOM_FANTASI
																				FROM empresas  
																				WHERE COD_EMPRESA IN (" . $_SESSION["SYS_COD_MULTEMP"] . ")
																				$andFiltro
																				ORDER by NOM_FANTASI
																		";
												//fnEscreve("2");
											}

											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrEmpresa = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrEmpresa['COD_EMPRESA']; ?>"><?php echo $qrEmpresa['NOM_FANTASI']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Solicitante</label>
										<div id="relatorioUsu">
											<select data-placeholder="Usuários Marka" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect requiredChk" style="width:100%;">

											</select>
										</div>
										<div class="help-block with-errors">requisito: selecionar empresa</div>
									</div>
								</div>

								<!-- <div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Código Externo</label>
															<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="45" value="<?php echo $cod_externo; ?>">
														</div>
													</div> -->

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Cód. Chamado</label>
										<input type="text" class="form-control input-sm" name="COD_CHAMADO" id="COD_CHAMADO" maxlength="45" value="<?php echo $cod_externo; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Título do Chamado</label>
										<input type="text" class="form-control input-sm" name="NOM_CHAMADO" id="NOM_CHAMADO" maxlength="50" value="<?php echo $nom_chamado; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Status</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS">
											<option value=""></option>
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
									<div class="form-group">
										<label for="inputName" class="control-label">Prioridade</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Prioridade" name="COD_PRIORIDADE" id="COD_PRIORIDADE">
											<option value=""></option>
											<?php

											$sql = "SELECT COD_PRIORIDADE, ABV_PRIORIDADE FROM SAC_PRIORIDADE";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);

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

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Remover Status</label>
										<select data-placeholder="Selecione o status" name="COD_STATUS_EXC[]" id="COD_STATUS_EXC" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
											<?php

											$sql = "SELECT * FROM SAC_STATUS";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);

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

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Remover Tipo</label>
										<select data-placeholder="Selecione o status" name="COD_TIPO_EXC[]" id="COD_TIPO_EXC" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
											<?php

											$sql = "SELECT * FROM SAC_TPSOLICITACAO";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);

											while ($qrTipo = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrTipo['COD_TPSOLICITACAO']; ?>"><?php echo $qrTipo['DES_TPSOLICITACAO']; ?></option>
											<?php
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<div class="push30"></div>
						<a href="action.php?mod=<?php echo fnEncode(1434); ?>" name="ADD" id="ADD" class="btn btn-success pull-left"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Criar Novo Chamado</a>


						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>

					<div class="push30"></div>

					<div class="col-lg-12" style="padding:0;">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th><small>Chamado</small></th>
											<th><small>Empresa</small></th>
											<th><small>Título</small></th>
											<th><small>Solicitante</small></th>
											<th><small>Dt. Cadastro</small></th>
											<th><small>Solicitação</small></th>
											<th><small>Responsável</small></th>
											<th><small>Prioridade</small></th>
											<th><small>Status</small></th>
											<th><small>Próx. Interação</small></th>
											<th><small>Atualizado</small></th>
											<th><small>Previsão (Entrega)</small></th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										if ($dat_ini == "") {
											$ANDdatIni = " ";
										} else {
											$ANDdatIni = "AND DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";
										}

										if ($dat_ini_ent == date('Y-m-d')) {
											$ANDdatIniEnt = " ";
										} else {
											$ANDdatIniEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') >= '$dat_ini_ent'";
										}

										if ($dat_fim_ent == "") {
											$ANDdatFimEnt = " ";
										} else {
											$ANDdatFimEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') <= '$dat_fim_ent'";
										}

										if ($cod_externo == "") {
											$ANDcodExterno = " ";
										} else {
											$ANDcodExterno = "AND SC.COD_EXTERNO LIKE '%$cod_externo%' ";
										}

										if ($cod_chamado == "") {
											$ANDcodChamado = " ";
										} else {
											$ANDcodChamado = "AND SC.COD_CHAMADO = $cod_chamado ";
										}

										if ($cod_empresa == "") {
											$ANDcodEmpresa = " ";
										} else {
											$ANDcodEmpresa = "AND SC.COD_EMPRESA = $cod_empresa ";
										}

										if ($nom_chamado == "") {
											$ANDnomChamado = " ";
										} else {
											$ANDnomChamado = "AND SC.NOM_CHAMADO LIKE '%$nom_chamado%' ";
										}

										if ($cod_tpsolicitacao == "") {
											$ANDcodTipo = " ";
										} else {
											$ANDcodTipo = "AND SC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";
										}

										if ($cod_status == "") {
											$ANDcodStatus = "";
										} else {
											$ANDcodStatus = "AND SC.COD_STATUS = $cod_status ";
										}

										if ($cod_status_exc == "0") {
											$ANDcodStatusExc = "";
										} else {
											$ANDcodStatusExc = "AND SC.COD_STATUS NOT IN($cod_status_exc) ";
										}

										if ($cod_tipo_exc == "0") {
											$ANDcodTipoExc = "";
										} else {
											$ANDcodTipoExc = "AND SC.COD_TPSOLICITACAO NOT IN($cod_tipo_exc) ";
										}

										if ($cod_integradora == "") {
											$ANDcodIntegradora = " ";
										} else {
											$ANDcodIntegradora = "AND SC.COD_INTEGRADORA = $cod_integradora ";
										}

										if ($cod_plataforma == "") {
											$ANDcodPlataforma = " ";
										} else {
											$ANDcodPlataforma = "AND SC.COD_PLATAFORMA = $cod_plataforma ";
										}

										if ($cod_versaointegra == "") {
											$ANDcodVersaointegra = " ";
										} else {
											$ANDcodVersaointegra = "AND SC.COD_VERSAOINTEGRA = $cod_versaointegra ";
										}

										if ($cod_prioridade == "") {
											$ANDcodPrioridade = " ";
										} else {
											$ANDcodPrioridade = "AND SC.COD_PRIORIDADE = $cod_prioridade ";
										}

										if ($cod_usuario == "") {
											$ANDcodUsuario = " ";
										} else {
											$ANDcodUsuario = "AND SC.COD_USUARIO = $cod_usuario ";
										}



										if ($cod_usuario != "" && $cod_usures != "" && $cod_usuario == $cod_usures) {
											$ANDcod_usures = "AND (SC.COD_USUARIO = $cod_usuario OR SC.COD_USURES = $cod_usures OR SC.COD_CONSULTORES IN($cod_usuario) OR SC.COD_USUARIOS_ENV IN($cod_usuario)) ";
											$ANDcodUsuario = "";
										} else {
											$ANDcod_usures = "";
										}




										$sqlCount = "SELECT COUNT(*) AS CONTADOR FROM SAC_CHAMADOS SC 
																WHERE SC.NOM_CHAMADO LIKE '%$nom_chamado%'
												  				$ANDcodUsuario
												  				$ANDcod_usures
												  				$ANDcodExterno
												  				$ANDcodChamado
												  				$ANDcodEmpresa
												  				$ANDnomChamado
												  				$ANDcodStatus
												  				$ANDcodTipo
												  				$ANDcodIntegradora
												  				$ANDcodPlataforma
												  				$ANDcodVersaointegra
												  				$ANDcodPrioridade
												  				$ANDcodStatusExc
												  				$ANDcodTipoExc
												  				$ANDdatIniEnt
												  				$ANDdatFimEnt												  				
																ORDER BY SC.COD_PRIORIDADE ASC
																";
										// fnEscreve($sqlCount);

										$retorno = mysqli_query($connAdmSAC->connAdm(), $sqlCount);
										$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

										$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sqlSac = "SELECT SC.COD_CHAMADO, SC.COD_EMPRESA, SC.NOM_CHAMADO, SC.COD_EXTERNO,  SC.COD_STATUS, 
																SC.DAT_CADASTR, SC.DAT_CHAMADO, SC.DAT_ENTREGA, SC.DAT_PROXINT, SC.DES_PREVISAO, SC.COD_USUARIO,
																SC.COD_USURES, SC.LOG_ADM, SP.DES_PLATAFORMA, ST.DES_TPSOLICITACAO, 
																SV.DES_VERSAOINTEGRA, SPR.DES_PRIORIDADE, SPR.DES_COR AS COR_PRIORIDADE, SPR.DES_ICONE AS ICO_PRIORIDADE,
																SS.ABV_STATUS, SS.DES_COR AS COR_STATUS, SS.DES_ICONE AS ICO_STATUS,
																(SELECT MAX(SCM.DAT_CADASTRO) FROM SAC_COMENTARIO SCM WHERE SCM.COD_CHAMADO = SC.COD_CHAMADO) AS DAT_INTERAC
																FROM SAC_CHAMADOS SC 
																LEFT JOIN SAC_PLATAFORMA SP ON SP.COD_PLATAFORMA=SC.COD_PLATAFORMA
																LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
																LEFT JOIN SAC_VERSAOINTEGRA SV ON SV.COD_VERSAOINTEGRA=SC.COD_VERSAOINTEGRA
																LEFT JOIN SAC_PRIORIDADE SPR ON SPR.COD_PRIORIDADE=SC.COD_PRIORIDADE
																LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
																WHERE SC.NOM_CHAMADO LIKE '%$nom_chamado%'
																$ANDcodUsuario
																$ANDcod_usures
																$ANDcodExterno
																$ANDcodChamado
																$ANDcodEmpresa
																$ANDnomChamado
																$ANDcodStatus
																$ANDcodTipo
																$ANDcodIntegradora
																$ANDcodPlataforma
																$ANDcodVersaointegra
																$ANDcodPrioridade
																$ANDcodStatusExc
												  				$ANDcodTipoExc
																$ANDdatIniEnt
																$ANDdatFimEnt
																ORDER BY SC.COD_CHAMADO DESC limit $inicio,$itens_por_pagina
																";
										// fnEscreve($sqlSac);

										$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(), $sqlSac);

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

											$sqlEmpresa = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $qrSac[COD_EMPRESA]";
											$qrNomEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmpresa));

											$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USUARIO]) AS NOM_SOLICITANTE,
																				(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
											$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));
											//fnEscreve($sqlUsuarios);										  

											if ($qrSac['DAT_ENTREGA'] == "1969-12-31") {
												$entrega = "";
											} else {
												$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
												if (fnDatasql($entrega) < fnDatasql($hoje)) {
													$entrega = "<span class='text-danger'><b>" . fnDataShort($qrSac['DAT_ENTREGA']) . "</b></span>";
												}
											}

											if ($qrSac['DAT_PROXINT'] == "1969-12-31") {
												$proxInt = "";
											} else {
												$proxInt = fnDataShort($qrSac['DAT_PROXINT']);
												if (fnDatasql($proxInt) < fnDatasql($hoje)) {
													$proxInt = "<span class='text-danger'><b>" . fnDataShort($qrSac['DAT_PROXINT']) . "</b></span>";
												}
											}

											if ($qrSac['DAT_INTERAC'] != "") {
												if (fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)) {
													$atualizado = "<b>Hoje</b>";
													$f = "f17";
												} else if (fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))) {
													$atualizado = ("<b>Ontem</b>");
													$f = "f17";
												} else {
													$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
													$f = "f14";
												}
											} else {
												$atualizado = "";
											}

											if ($qrSac['COD_STATUS'] == 12) {

												$difference = fnValor((abs(strtotime(date("Y-m-d H:i:s")) - strtotime($qrSac['DAT_CADASTR'])) / 3600), 0);

												if ($difference <= 12) {
													$corDiff = "label-success";
												} else if ($difference > 12 && $difference <= 24) {
													$corDiff = "label-warning";
												} else {
													$corDiff = "label-danger";
												}

												$badgeDias = "<span class='label-as-badge text-center " . $corDiff . "'><span class='txtBadge'>" . $difference . "</span></span>";
											} else {
												$badgeDias = "";
											}

											//$diff_dias = fnDateDif($qrSac['DAT_CADASTR'],Date("Y-m-d"));
											// fnEscreve(fnDatasql($qrSac['DAT_INTERAC']));
										?>

											<tr>
												<td class="text-center">
													<small>
														<a href="action.php?mod=<?= fnEncode(1462); ?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']); ?>&idC=<?php echo fnEncode($qrSac['COD_CHAMADO']); ?>" target="_blank"><?= $qrSac['COD_CHAMADO'] ?>&nbsp;
															<span class="fal fa-external-link-square"></span>
														</a>
													</small>
												</td>
												<td>
													<small>
														<?= $adm ?> &nbsp;
														<?= isset($qrNomEmp['NOM_FANTASI']) ? $qrNomEmp['NOM_FANTASI'] : null ?>
													</small>
												</td>

												<td><small><?= $qrSac['NOM_CHAMADO'] ?></small></td>
												<td><small><?= $qrNomUsu['NOM_SOLICITANTE'] ?></small></td>
												<td><small><?= fnDataShort($qrSac['DAT_CADASTR']) ?></small></td>
												<td><small><?= $qrSac['DES_TPSOLICITACAO'] ?></small></td>
												<td><small><?= $qrNomUsu['NOM_RESPONSAVEL'] ?></small></td>

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
														&nbsp;
														<?= $badgeDias ?>
													</small>

													<!-- <div><?= $badgeDias ?></div> -->
												</td>

												<td class="text-center f14"><small><?= $proxInt ?></small></td>
												<td class="text-center <?= $f ?>"><small><?= $atualizado ?></small></td>
												<td class="text-center f14"><small><?= $entrega ?></small></td>

											</tr>
										<?php
										}
										?>

									</tbody>
									<tfoot>
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

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	function retornaForm(index) {

		var plataforma = '<?php echo $cod_plataforma; ?>';
		if (plataforma != 0 && plataforma != "") {
			$("#formulario #COD_PLATAFORMA").val(<?php echo $cod_plataforma; ?>).trigger("chosen:updated");
		}

		var empresa = '<?php echo $cod_empresa; ?>';
		if (empresa != 0 && empresa != "") {
			$("#formulario #COD_EMPRESA").val(<?php echo $cod_empresa; ?>).trigger("chosen:updated");
		}

		var empresa = '<?php echo $cod_empresa; ?>';
		if (empresa != 0 && empresa != "") {
			$("#formulario #COD_EMPRESA").val(<?php echo $cod_empresa; ?>).trigger("chosen:updated");
		}

		var versaointegra = '<?php echo $cod_versaointegra; ?>';
		if (versaointegra != 0 && versaointegra != "") {
			$("#formulario #COD_VERSAOINTEGRA").val(<?php echo $cod_versaointegra; ?>).trigger("chosen:updated");
		}

		var integradora = '<?php echo $cod_integradora; ?>';
		if (integradora != 0 && integradora != "") {
			$("#formulario #COD_INTEGRADORA").val(<?php echo $cod_integradora; ?>).trigger("chosen:updated");
		}

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

		var tipo_exc = '<?php echo $cod_tipo_exc; ?>';
		if (tipo_exc != 0 && tipo_exc != "") {
			$("#formulario #COD_TIPO_EXC").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_tipo_exc; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_TIPO_EXC option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_TIPO_EXC").trigger("chosen:updated");
		}

		var usuario = '<?php echo $cod_usuario; ?>';
		if (usuario != 0 && usuario != "") {
			$("#formulario #COD_USUARIO").val(<?php echo $cod_usuario; ?>).trigger("chosen:updated");
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$(document).ready(function() {

		retornaForm(0);

		$('#COD_EMPRESA').val('<?= $cod_empresa ?>').trigger('chosen:updated');

		var idEmp = $('#COD_EMPRESA').val();
		buscaCombo(idEmp);

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

	});

	$("#COD_EMPRESA").change(function() {
		var idEmp = $('#COD_EMPRESA').val();
		buscaCombo(idEmp);
	});

	function buscaCombo(idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxAddSuporte.php",
			data: {
				ajxEmp: idEmp
			},
			beforeSend: function() {
				$('#relatorioUsu').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				// console.log(data);	
				$('#relatorioUsu').html($('#relatorioUsuario', data));
				$('#COD_USUARIO').chosen();
				$('#COD_USUARIO').val('<?= $cod_usuario ?>').trigger('chosen:updated');
			},
			error: function() {
				$('#relatorioUsu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
			}
		});
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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "ajxListaSuporteConsultor.do?opcao=paginar&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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