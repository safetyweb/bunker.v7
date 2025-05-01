<?php

$hashLocal = mt_rand();
$qtd_totFPaga = 0;
$qtd_indica = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_extra = fnLimpaCampoZero($_REQUEST['COD_EXTRA']);
		$qtd_extracad = fnLimpaCampo($_REQUEST['QTD_EXTRACAD']);
		$tip_extracad = fnLimpaCampo($_REQUEST['TIP_EXTRACAD']);
		$dia_extracad = fnLimpaCampoZero($_REQUEST['DIA_EXTRACAD']);
		$ini_extracad = fnLimpaCampo($_REQUEST['INI_EXTRACAD']);
		$qtd_extraani = fnLimpaCampo($_REQUEST['QTD_EXTRAANI']);
		$tip_extraani = fnLimpaCampo($_REQUEST['TIP_EXTRAANI']);
		$dia_extraani = fnLimpaCampoZero($_REQUEST['DIA_EXTRAANI']);
		$ini_extraani = fnLimpaCampo($_REQUEST['INI_EXTRAANI']);

		$qtd_totindica = fnLimpaCampoZero($_REQUEST['QTD_TOTINDICA']);
		$qtd_totfaixa = fnLimpaCampoZero($_REQUEST['QTD_TOTFAIXA']);
		$qtd_totitens = fnLimpaCampoZero($_REQUEST['QTD_TOTITENS']);
		$qtd_totprodu = fnLimpaCampoZero($_REQUEST['QTD_TOTPRODU']);
		$qtd_categor = fnLimpaCampoZero($_REQUEST['QTD_CATEGOR']);

		$cod_program = fnLimpaCampoZero($_REQUEST['COD_PROGRAM']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_VANTAGEMEXTRA (
				 '" . $cod_extra . "', 
				 '" . $cod_program . "', 
				 '" . $cod_empresa . "', 
				 '" . fnValorSql($qtd_extracad) . "', 
				 '" . $tip_extracad . "', 
				 '" . $dia_extracad . "', 
				 '" . $ini_extracad . "', 
				 '" . fnValorSql($qtd_extraani) . "', 
				 '" . $tip_extraani . "', 
				 '" . $dia_extraani . "', 
				 '" . $ini_extraani . "', 
				 '" . fnValorSql($qtd_totfaixa) . "', 
				 '" . fnValorSql($qtd_totitens) . "', 
				 '" . fnValorSql($qtd_totprodu) . "', 
				 '" . fnValorSql($qtd_categor) . "', 
				 '" . $cod_usucada . "',    
				 '" . $opcao . "'    
				) ";

			//echo $sql;
			//fnEscreve($sql);				

			mysqli_query(connTemp($cod_empresa, ''), trim($sql));

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, LOG_CATEGORIA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$log_categoria = $qrBuscaEmpresa['LOG_CATEGORIA'];

		//liberação das abas
		$abaPersona	= "S";
		$abaVantagem = "S";
		$abaRegras = "N";
		$abaComunica = "N";
		$abaAtivacao = "N";
		$abaResultado = "N";

		$abaPersonaComp = "active ";
		$abaCampanhaComp = "active";
		$abaVantagemComp = "active ";
		$abaRegrasComp = "completed ";
		$abaComunicaComp = "";
		$abaAtivacaoComp = "";
		$abaResultadoComp = "";
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//busca dados da campanha
$cod_campanha = fnDecode($_GET['idc']);
$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
	$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
	$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
	$des_icone = $qrBuscaCampanha['DES_ICONE'];
	$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
	$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
}

//busca dados do tipo da campanha
$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
	$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
	$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
	$label_1 = $qrBuscaTpCampanha['LABEL_1'];
	$label_2 = $qrBuscaTpCampanha['LABEL_2'];
	$label_3 = $qrBuscaTpCampanha['LABEL_3'];
	$label_4 = $qrBuscaTpCampanha['LABEL_4'];
	$label_5 = $qrBuscaTpCampanha['LABEL_5'];
}

//busca dados da regra 
$sql = "SELECT * FROM CAMPANHAREGRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$cod_persona = $qrBuscaTpCampanha['COD_PERSONA'];
	if (!empty($cod_persona)) {
		$tem_personas = "sim";
	} else {
		$tem_personas = "nao";
	}
	$pct_vantagem = $qrBuscaTpCampanha['PCT_VANTAGEM'];
	$qtd_vantagem = $qrBuscaTpCampanha['QTD_VANTAGEM'];
	$qtd_resultado = $qrBuscaTpCampanha['QTD_RESULTADO'];
	$nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
	$num_pessoas = $qrBuscaTpCampanha['NUM_PESSOAS'];
	$cod_vantage = $qrBuscaTpCampanha['COD_VANTAGE'];
} else {

	$cod_persona = 0;
	$pct_vantagem = "";
	$qtd_vantagem = "";
	$qtd_vantagem = "";
	$nom_vantagem = "";
	$num_pessoas = 0;
	$cod_vantage = 0;
}


//busca dados da regra extra (tela) 
$sql = "SELECT * FROM VANTAGEMEXTRA where COD_CAMPANHA = '" . $cod_campanha . "' ";

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery);
//echo $sql;
if (isset($arrayQuery) && mysqli_num_rows($arrayQuery) > 0) {

	// echo '<pre>';
	// print_r($qrBuscaCampanhaExtra['QTD_TOTITENS']);
	// echo '</pre>';

	$cod_extra = $qrBuscaCampanhaExtra['COD_EXTRA'];
	$qtd_extracad = $qrBuscaCampanhaExtra['QTD_EXTRACAD'];
	$tip_extracad = $qrBuscaCampanhaExtra['TIP_EXTRACAD'];
	$dia_extracad = $qrBuscaCampanhaExtra['DIA_EXTRACAD'];
	$ini_extracad = $qrBuscaCampanhaExtra['INI_EXTRACAD'];
	$qtd_extraani = $qrBuscaCampanhaExtra['QTD_EXTRAANI'];
	$tip_extraani = $qrBuscaCampanhaExtra['TIP_EXTRAANI'];
	$dia_extraani = $qrBuscaCampanhaExtra['DIA_EXTRAANI'];
	$ini_extraani = $qrBuscaCampanhaExtra['INI_EXTRAANI'];
	$qtd_totfaixa = $qrBuscaCampanhaExtra['QTD_TOTFAIXA'];
	$qtd_totitens = $qrBuscaCampanhaExtra['QTD_TOTITENS'];
	$qtd_totprodu = $qrBuscaCampanhaExtra['QTD_TOTPRODU'];
	$qtd_totFPaga = $qrBuscaCampanhaExtra['QTD_TOTFPAGA'];
	$qtd_categor = $qrBuscaCampanhaExtra['QTD_CATEGOR'];
	$qtd_indica = $qrBuscaCampanhaExtra['QTD_INDICA'];
} else {

	$cod_extra = 0;
	$qtd_extracad = "";
	$tip_extracad = "";
	$dia_extracad = 0;
	$ini_extracad = "";
	$qtd_extraani = "";
	$tip_extraani = "";
	$dia_extraani = 0;
	$ini_extraani = "";
	$qtd_totfaixa = 0;
	$qtd_totitens = 0;
	$qtd_totprodu = 0;
	$qtd_categor = 0;
}

if ($qtd_totfaixa == 0) {
	$txtBntExtra1 = "Cadastrar";
	$icoBntExtra1 = "fa-plus";
} else {
	$txtBntExtra1 = "Editar";
	$icoBntExtra1 = "fa-pencil";
}

if ($qtd_totitens == 0) {
	$txtBntExtra2 = "Cadastrar";
	$icoBntExtra2 = "fa-plus";
} else {
	$txtBntExtra2 = "Editar";
	$icoBntExtra2 = "fa-pencil";
}

if ($qtd_totprodu == 0) {
	$txtBntExtra3 = "Cadastrar";
	$icoBntExtra3 = "fa-plus";
} else {
	$txtBntExtra3 = "Editar";
	$icoBntExtra3 = "fa-pencil";
}

if ($qtd_totFPaga == 0) {
	$txtBntExtra4 = "Cadastrar";
	$icoBntExtra4 = "fa-plus";
} else {
	$txtBntExtra4 = "Editar";
	$icoBntExtra4 = "fa-pencil";
}

if ($qtd_categor == 0) {
	$sql2 = "select count(*) as VALORFAIXA from CATEGORIA_CLIENTE_CAMPANHA where COD_EMPRESA = '" . $cod_empresa . "' and  COD_CAMPANHA = '" . $cod_campanha . "'  ";
	$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);
	$qrCat = mysqli_fetch_assoc($arrayQuery2);

	if ($qrCat['VALORFAIXA'] == 0) {
		$txtBntExtra5 = "Cadastrar";
		$icoBntExtra5 = "fa-plus";
	} else {
		$txtBntExtra5 = "Editar";
		$icoBntExtra5 = "fa-pencil";
		$qtd_categor = $qrCat['VALORFAIXA'];
	}
} else {
	$txtBntExtra5 = "Editar";
	$icoBntExtra5 = "fa-pencil";
}

if ($qtd_indica == 0) {
	$txtBntExtra6 = "Cadastrar";
	$icoBntExtra6 = "fa-plus";
} else {
	$txtBntExtra6 = "Editar";
	$icoBntExtra6 = "fa-pencil";
}

//fnMostraForm();
//fnEscreve($tip_campanha);

$sqlDias = "SELECT COUNT(*) AS DIAS_SEMANA FROM DIAS_SEMANA_CAMPANHA 
	WHERE COD_CAMPANHA = $cod_campanha 
	AND COD_EMPRESA = $cod_empresa
	AND COD_EXCLUSA = 0";
$arrayDias = mysqli_query(connTemp($cod_empresa, ''), $sqlDias);
$qrDias = mysqli_fetch_assoc($arrayDias);

if (isset($arrayDias)) {

	$qtd_dias = $qrDias['DIAS_SEMANA'];
}

$sqlPessoas = "SELECT COUNT(*) as PESSOAS FROM PERSONACLASSIFICA WHERE COD_PERSONA = $cod_persona AND COD_EMPRESA = $cod_empresa";

$arrayPessoas = mysqli_query(connTemp($cod_empresa, ''), $sqlPessoas);
if ($qrBuscaPessoas = mysqli_fetch_assoc($arrayPessoas)) {
	$num_pessoas = $qrBuscaPessoas['PESSOAS'];
} else {
	$num_pessoas = 0;
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
				$formBack = "1048";
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

				<?php $abaCampanhas = 1035;
				include "abasCampanhasConfig.php"; ?>

				<div class="push10"></div>

				<?php $abaCli = 1057;
				include "abasRegrasConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PROGRAM" id="COD_PROGRAM" value="<?php echo $cod_campanha ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Campanha</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $des_campanha ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo do Programa</label>
										<div class="push10"></div>
										<span class="fa <?php echo $des_iconecp; ?>"></span> <b><?php echo $nom_tpcampa; ?> (<?php echo $nom_vantagem; ?>) </b>
									</div>
								</div>


								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Pessoas Atingidas</label>
										<div class="push10"></div>
										<span class="fa fa-users"></span>&nbsp; <?php echo number_format($num_pessoas, 0, ",", "."); ?>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-5">

								<fieldset>
									<legend><?php echo $abv_tpcampa; ?> Extras por Cadastro </legend>

									<div class="row">

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Quantidade</label>
												<input type="text" class="form-control input-sm text-center money" name="QTD_EXTRACAD" id="QTD_EXTRACAD" maxlength="6" value="<?php echo $qtd_extracad ?>">
												<span class="help-block">valor</span>
											</div>
										</div>

										<div class="col-md-7">
											<div class="form-group">
												<label for="inputName" class="control-label">Tipo da Vantagem Extra</label>
												<select data-placeholder="Selecione a vantagem extra" name="TIP_EXTRACAD" id="TIP_EXTRACAD" class="chosen-select-deselect">
													<option value="">...</option>
													<option value="PCT">Percentual sobre a venda</option>
													<option value="PCV">Percentual sobre <?php echo strtolower($nom_vantagem); ?></option>
													<option value="ABS"><?php echo $nom_tpcampa; ?></option>
												</select>
												<script>
													$("#TIP_EXTRACAD").val("<?php echo $tip_extracad; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<input type="hidden" name="DIA_EXTRACAD" id="DIA_EXTRACAD" value="0">
										<input type="hidden" name="INI_EXTRACAD" id="INI_EXTRACAD" value="0">

									</div>

								</fieldset>

							</div>


							<div class="col-md-7">

								<fieldset>
									<legend><?php echo $abv_tpcampa; ?> Extras por Aniversário </legend>


									<div class="row">

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Quantidade</label>
												<input type="text" class="form-control input-sm text-center money" name="QTD_EXTRAANI" id="QTD_EXTRAANI" maxlength="6" value="<?php echo $qtd_extraani; ?>">
												<span class="help-block">valor</span>
											</div>
										</div>

										<div class="col-md-5">
											<div class="form-group">
												<label for="inputName" class="control-label">Tipo da Vantagem Extra</label>
												<select data-placeholder="Selecione um estado civil" name="TIP_EXTRAANI" id="TIP_EXTRAANI" class="chosen-select-deselect">
													<option value="">...</option>
													<option value="PCT">Percentual sobre a venda</option>
													<option value="PCV">Percentual sobre <?php echo strtolower($nom_vantagem); ?></option>
													<option value="ABS"><?php echo $nom_tpcampa; ?></option>
												</select>
												<script>
													$("#TIP_EXTRAANI").val("<?php echo $tip_extraani; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<input type="hidden" name="DIA_EXTRAANI" id="DIA_EXTRAANI" value="0">

										<div class="col-md-4">
											<div class="form-group">
												<label for="inputName" class="control-label">Período do Ganho</label>
												<select data-placeholder="Selecione um período" name="INI_EXTRAANI" id="INI_EXTRAANI" class="chosen-select-deselect">
													<option value="">...</option>
													<option value="D">Dia exato do aniversário</option>
													<option value="S">Semana do aniversário</option>
													<option value="M">Mês do aniversário</option>
												</select>
												<script>
													$("#INI_EXTRAANI").val("<?php echo $ini_extraani; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>

								</fieldset>

							</div>

						</div>

						<div class="push10"></div>

						<div class="row">

							<h4 style="padding: 0 0 20px 18px;">Conceda vantagens extras por:</h4>

							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<div id="div_refreshFaixa">
									<div class="widget widget-default widget-item-icon">
										<div class="widget-item-left">
											<span class="fal fa-chart-bar"></span>
										</div>
										<div class="widget-data">
											<div class="widget-title">Faixa de Valores</div>
											<div class="widget-int" id=""><?php echo number_format($qtd_totfaixa, 0, ",", "."); ?></div>
											<div class="widget-title" style="font-weight: 400; font-size: 14px;">Faixas de valores cadastrados</div>
											<div class="widget-subtitle">
												<div class="push20"></div>
												<div class="push5"></div>
												<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1059) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Faixa de Valores"><i class="fa <?php echo $icoBntExtra1; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra1; ?></a>
												<div class="push5"></div>
											</div>
										</div>
									</div>
								</div>

							</div>

							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<div id="div_refreshItens">
									<div class="widget widget-default widget-item-icon">
										<div class="widget-item-left">
											<span class="fal fa-cubes"></span>
										</div>
										<div class="widget-data">
											<div class="widget-title">Quantidade de Itens</div>
											<div class="widget-int"><?php echo fnvalor($qtd_totitens, 0); ?></div>
											<div class="widget-title" style="font-weight: 400; font-size: 14px;">Faixas de itens cadastrados</div>
											<div class="widget-subtitle">
												<div class="push20"></div>
												<div class="push5"></div>
												<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1060) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Quantidade de Itens"><i class="fa <?php echo $icoBntExtra2; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra2; ?></a>
												<div class="push5"></div>
											</div>
										</div>
									</div>
								</div>

							</div>

							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<div id="div_refreshProd">
									<div class="widget widget-default widget-item-icon">
										<div class="widget-item-left">
											<span class="fal fa-bullseye"></span>
										</div>
										<div class="widget-data">
											<div class="widget-title">Produtos Específicos</div>
											<div class="widget-int"><?php echo fnValor($qtd_totprodu, 0); ?></div>
											<div class="widget-title" style="font-weight: 400; font-size: 14px;">Produtos cadastrados</div>
											<div class="widget-subtitle">
												<div class="push20"></div>
												<div class="push5"></div>
												<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1063) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Produtos Específicos"><i class="fa <?php echo $icoBntExtra3; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra3; ?></a>
												<div class="push5"></div>
											</div>
										</div>
									</div>
								</div>

							</div>

							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<div id="div_refreshPag">
									<div class="widget widget-default widget-item-icon">
										<div class="widget-item-left">
											<span class="fal fa-credit-card"></span>
										</div>
										<div class="widget-data">
											<div class="widget-title">Formas de Pagamento</div>
											<div class="widget-int"><?php echo number_format($qtd_totFPaga, 0, ",", "."); ?></div>
											<div class="widget-title" style="font-weight: 400; font-size: 14px;">Tipos de Pagamentos </div>
											<div class="widget-subtitle">
												<div class="push20"></div>
												<div class="push5"></div>
												<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1094) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Formas de Pagamento"><i class="fa <?php echo $icoBntExtra4; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra4; ?></a>
												<div class="push5"></div>
											</div>
										</div>
									</div>
								</div>

							</div>

							<div class="push20"></div>

							<div class="push20"></div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<div id="div_refreshIndica">
									<div class="widget widget-default widget-item-icon">
										<div class="widget-item-left">
											<span class="fal fa-handshake"></span>
										</div>
										<div class="widget-data">
											<div class="widget-title">Indicação de Clientes</div>
											<div class="widget-int"><?php echo number_format($qtd_indica, 0, ",", "."); ?></div>
											<div class="widget-title" style="font-weight: 400; font-size: 14px;">Indicação de clientes </div>
											<div class="widget-subtitle">
												<div class="push20"></div>
												<div class="push5"></div>
												<?php //módulo antigo 1339 
												?>
												<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(2075) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Indicação de Cliente"><i class="fa <?php echo $icoBntExtra6; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra6; ?></a>
												<div class="push5"></div>
											</div>
										</div>
									</div>
								</div>

							</div>

							<?php
							if ($log_categoria == "S") {
							?>
								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

									<div id="div_refreshCatCli">
										<div class="widget widget-default widget-item-icon">
											<div class="widget-item-left">
												<span class="fal fa-user-tag"></span>
											</div>
											<div class="widget-data">
												<div class="widget-title">Categoria de Clientes</div>
												<div class="widget-int"><?php echo number_format($qtd_categor, 0, ",", "."); ?></div>
												<div class="widget-title" style="font-weight: 400; font-size: 14px;">Categoria(s) de clientes </div>
												<div class="widget-subtitle">
													<div class="push20"></div>
													<div class="push5"></div>
													<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1277) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Categoria de Cliente"><i class="fa <?php echo $icoBntExtra5; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra5; ?></a>
													<div class="push5"></div>
												</div>
											</div>
										</div>
									</div>

								</div>
							<?php
							}
							?>

							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

								<div id="div_refreshDias">
									<div class="widget widget-default widget-item-icon">
										<div class="widget-item-left">
											<span class="fal fa-calendar-alt"></span>
										</div>
										<div class="widget-data">
											<div class="widget-title">Dia da Semana</div>
											<div class="widget-int"><?= number_format($qtd_dias, 0, ",", ".") ?></div>
											<div class="widget-title" style="font-weight: 400; font-size: 14px;">Dia(s) da Semana </div>
											<div class="widget-subtitle">
												<div class="push20"></div>
												<div class="push5"></div>
												<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1821) ?>&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagem Extra-Dias da Semana"><i class="fa <?php echo $icoBntExtra5; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra5; ?></a>
												<div class="push5"></div>
											</div>
										</div>
									</div>
								</div>

							</div>

						</div>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<?php if ($cod_extra == 0) { ?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<?php } else { ?>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<?php } ?>

						</div>

						<input type="hidden" class="input-sm" name="REFRESH_INDICA" id="REFRESH_INDICA" value="N">
						<input type="hidden" class="input-sm" name="REFRESH_FAIXAS" id="REFRESH_FAIXAS" value="N">
						<input type="hidden" class="input-sm" name="REFRESH_ITENS" id="REFRESH_ITENS" value="N">
						<input type="hidden" class="input-sm" name="REFRESH_PROD" id="REFRESH_PROD" value="N">
						<input type="hidden" class="input-sm" name="REFRESH_PAG" id="REFRESH_PAG" value="N">
						<input type="hidden" class="input-sm" name="REFRESH_CAT" id="REFRESH_CAT" value="N">
						<input type="hidden" class="input-sm" name="REFRESH_DIAS" id="REFRESH_DIAS" value="N">

						<input type="hidden" name="COD_EXTRA" id="COD_EXTRA" value="">
						<input type="hidden" name="QTD_TOTFAIXA" id="QTD_TOTFAIXA" value="<?php echo $qtd_totfaixa; ?>">
						<input type="hidden" name="QTD_TOTITENS" id="QTD_TOTITENS" value="<?php echo $qtd_totitens; ?>">
						<input type="hidden" name="QTD_TOTPRODU" id="QTD_TOTPRODU" value="<?php echo $qtd_totprodu; ?>">
						<input type="hidden" name="QTD_TOTFPAGA" id="QTD_TOTFPAGA" value="<?php echo $qtd_totfpaga; ?>">
						<input type="hidden" name="QTD_TOTINDICA" id="QTD_TOTINDICA" value="<?php echo $qtd_totindica; ?>">
						<input type="hidden" name="QTD_CATEGOR" id="QTD_CATEGOR" value="<?php echo $qtd_categor; ?>">

						<input type="hidden" name="opcao" id="opcao" value="">
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

<script type="text/javascript">
	$(document).ready(function() {

		//modal close
		$('.modal').on('hidden.bs.modal', function() {

			if ($('#REFRESH_INDICA').val() == "S") {
				//alert("atualiza");
				RefreshIndica(<?php echo $cod_empresa; ?>, <?php echo $cod_campanha; ?>, "IND");
				$('#REFRESH_INDICA').val("N");
			}
			if ($('#REFRESH_FAIXAS').val() == "S") {
				//alert("atualiza");
				RefreshFaixas(<?php echo $cod_empresa; ?>, <?php echo $cod_campanha; ?>, "VAL");
				$('#REFRESH_FAIXAS').val("N");
			}

			if ($('#REFRESH_ITENS').val() == "S") {
				//alert("atualiza");
				RefreshItens(<?php echo $cod_empresa; ?>, <?php echo $cod_campanha; ?>, "ITM");
				$('#REFRESH_ITENS').val("N");
			}

			if ($('#REFRESH_PROD').val() == "S") {
				//alert("atualiza");
				RefreshProd(<?php echo $cod_empresa; ?>, <?php echo $cod_campanha; ?>, "PRD");
				$('#REFRESH_PROD').val("N");
			}

			if ($('#REFRESH_PAG').val() == "S") {
				//alert("atualiza");
				RefreshPag(<?php echo $cod_empresa; ?>, <?php echo $cod_campanha; ?>, "PAG");
				$('#REFRESH_PAG').val("N");
			}

			if ($('#REFRESH_CAT').val() == "S") {
				//alert("atualiza");
				RefreshCat(<?php echo $cod_empresa; ?>, <?php echo $cod_campanha; ?>, "CAT");
				$('#REFRESH_CAT').val("N");
			}
			if ($('#REFRESH_DIAS').val() == "S") {
				//alert("atualiza");
				RefreshDias(<?php echo $cod_empresa; ?>, <?php echo $cod_campanha; ?>, "DIAS");
				$('#REFRESH_DIAS').val("N");
			}

		});

	});

	function RefreshFaixas(idEmp, idCamp, idTipo) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshExtra.php",
			data: {
				ajx1: idEmp,
				ajx2: idCamp,
				ajx3: idTipo
			},
			beforeSend: function() {
				$('#div_refreshFaixa').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_refreshFaixa").html(data);
			},
			error: function() {
				$('#div_refreshFaixa').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function RefreshItens(idEmp, idCamp, idTipo) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshExtra.php",
			data: {
				ajx1: idEmp,
				ajx2: idCamp,
				ajx3: idTipo
			},
			beforeSend: function() {
				$('#div_refreshItens').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_refreshItens").html(data);
			},
			error: function() {
				$('#div_refreshItens').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function RefreshProd(idEmp, idCamp, idTipo) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshExtra.php",
			data: {
				ajx1: idEmp,
				ajx2: idCamp,
				ajx3: idTipo
			},
			beforeSend: function() {
				$('#div_refreshProd').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_refreshProd").html(data);
			},
			error: function() {
				$('#div_refreshProd').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function RefreshPag(idEmp, idCamp, idTipo) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshExtra.php",
			data: {
				ajx1: idEmp,
				ajx2: idCamp,
				ajx3: idTipo
			},
			beforeSend: function() {
				$('#div_refreshPag').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_refreshPag").html(data);
			},
			error: function() {
				$('#div_refreshPag').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function RefreshIndica(idEmp, idCamp, idTipo) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshExtra.php",
			data: {
				ajx1: idEmp,
				ajx2: idCamp,
				ajx3: idTipo
			},
			beforeSend: function() {
				$('#div_refreshIndica').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				console.log(data);
				$("#div_refreshIndica").html(data);
			},
			error: function() {
				$('#div_refreshIndica').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function RefreshCat(idEmp, idCamp, idTipo) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshExtra.php",
			data: {
				ajx1: idEmp,
				ajx2: idCamp,
				ajx3: idTipo
			},
			beforeSend: function() {
				$('#div_refreshCatCli').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_refreshCatCli").html(data);
			},
			error: function() {
				$('#div_refreshCatCli').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function RefreshDias(idEmp, idCamp, idTipo) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshExtra.php",
			data: {
				ajx1: idEmp,
				ajx2: idCamp,
				ajx3: idTipo
			},
			beforeSend: function() {
				$('#div_refreshDias').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_refreshDias").html(data);
			},
			error: function() {
				$('#div_refreshDias').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>