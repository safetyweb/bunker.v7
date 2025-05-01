<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_configu = "";
$log_ativo_tkt = "";
$log_emisdia = "";
$log_listaws = "";
$cod_template_tkt = "";
$qtd_compras_tkt = 0;
$qtd_ofertas_tkt = 0;
$qtd_ofertws_tkt = 0;
$qtd_ofertas_lst = 0;
$num_historico_tkt = "";
$num_historico_Array = [];
$min_historico_tkt = "";
$max_historico_tkt = "";
$qtd_categor_tkt = 0;
$qtd_produtos_tkt = 0;
$qtd_produtos_cat = 0;
$des_pratprc = "";
$des_validade = "";
$Arr_COD_BLKLIST = "";
$Arr_COD_MULTEMP = "";
$i = "";
$cod_blklist = "";
$hHabilitado = "";
$hashForm = "";
$des_icones = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaConfiguracao = "";
$mostraLOG_ATIVO_TKT = "";
$mostraLOG_EMISDIA = "";
$mostraLOG_LISTAWS = "";
$popUp = "";
$abaModulo = "";
$qrListaPersonas = "";
$desabilitado = "";
$qrListaBlkList = "";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_configu = fnLimpaCampoZero(@$_REQUEST['COD_CONFIGU']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		//$log_ativo_tkt = fnLimpaCampo(@$_REQUEST['LOG_ATIVO_TKT']);
		if (empty(@$_REQUEST['LOG_ATIVO_TKT'])) {
			$log_ativo_tkt = 'N';
		} else {
			$log_ativo_tkt = @$_REQUEST['LOG_ATIVO_TKT'];
		}
		if (empty(@$_REQUEST['LOG_EMISDIA'])) {
			$log_emisdia = 'N';
		} else {
			$log_emisdia = @$_REQUEST['LOG_EMISDIA'];
		}
		if (empty(@$_REQUEST['LOG_LISTAWS'])) {
			$log_listaws = 'N';
		} else {
			$log_listaws = @$_REQUEST['LOG_LISTAWS'];
		}
		$cod_template_tkt = fnLimpaCampoZero(@$_REQUEST['COD_TEMPLATE_TKT']);
		$qtd_compras_tkt = fnLimpaCampoZero(@$_REQUEST['QTD_COMPRAS_TKT']);
		$qtd_ofertas_tkt = fnLimpaCampoZero(@$_REQUEST['QTD_OFERTAS_TKT']);
		$qtd_ofertws_tkt = fnLimpaCampoZero(@$_REQUEST['QTD_OFERTWS_TKT']);
		$qtd_ofertas_lst = fnLimpaCampoZero(@$_REQUEST['QTD_OFERTAS_LST']);
		$num_historico_tkt = @$_REQUEST['NUM_HISTORICO_TKT'][0];
		$num_historico_Array = explode(";", @$_REQUEST['NUM_HISTORICO_TKT'][0]);
		$min_historico_tkt = $num_historico_Array['0'];
		$max_historico_tkt = $num_historico_Array['1'];
		$qtd_categor_tkt = fnLimpaCampoZero(@$_REQUEST['QTD_CATEGOR_TKT']);
		$qtd_produtos_tkt = fnLimpaCampoZero(@$_REQUEST['QTD_PRODUTOS_TKT']);
		$qtd_produtos_cat = fnLimpaCampoZero(@$_REQUEST['QTD_PRODUTOS_CAT']);
		$des_pratprc = fnLimpaCampo(@$_REQUEST['DES_PRATPRC']);
		$des_validade = fnLimpaCampoZero(@$_REQUEST['DES_VALIDADE']);

		//array das empresas multiacesso
		if (isset($_POST['COD_BLKLIST'])) {
			$Arr_COD_BLKLIST = @$_POST['COD_BLKLIST'];
			//print_r($Arr_COD_MULTEMP);			 
			for ($i = 0; $i < count($Arr_COD_BLKLIST); $i++) {
				$cod_blklist = $cod_blklist . $Arr_COD_BLKLIST[$i] . ",";
			}
			$cod_blklist = substr($cod_blklist, 0, -1);
		} else {
			$cod_blklist = "0";
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CONFIGURACAO_TICKET (
				 '" . $cod_configu . "', 
				 '" . $cod_empresa . "', 
				 '" . $log_ativo_tkt . "', 
				 '" . $cod_template_tkt . "', 
				 '" . $qtd_compras_tkt . "', 
				 '" . $qtd_ofertas_tkt . "', 
				 '" . $num_historico_tkt . "', 
				 '" . $min_historico_tkt . "', 
				 '" . $max_historico_tkt . "', 
				 '" . $qtd_categor_tkt . "', 
				 '" . $cod_blklist . "', 
				 '" . $qtd_produtos_tkt . "', 
				 '" . $log_emisdia . "', 
				 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
				 '" . $qtd_ofertas_lst . "', 
				 '" . $qtd_ofertws_tkt . "', 
				 '" . $log_listaws . "', 
				 '" . $des_pratprc . "', 
				 '" . $des_validade . "', 
				 '" . $qtd_produtos_cat . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;		
			// fnEscreve($sql);
			//fnTesteSql(connTemp($cod_empresa,""),$sql);

			mysqli_query(connTemp($cod_empresa, ""), trim($sql));

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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}


//busca dados da configuração	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA = '" . $cod_empresa . "' ";

	//fnEscreve($sql);
	//fnTesteSql(connTemp($cod_empresa,""),trim($sql));

	$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));
	$qrBuscaConfiguracao = mysqli_fetch_assoc($arrayQuery);

	//print_r($arrayQuery);	

	if (isset($qrBuscaConfiguracao)) {
		$cod_configu = $qrBuscaConfiguracao['COD_CONFIGU'];
		$log_ativo_tkt = $qrBuscaConfiguracao['LOG_ATIVO_TKT'];
		if ($log_ativo_tkt == "S") {
			$mostraLOG_ATIVO_TKT = "checked";
		} else {
			$mostraLOG_ATIVO_TKT = "";
		}
		$log_emisdia = $qrBuscaConfiguracao['LOG_EMISDIA'];
		if ($log_emisdia == "S") {
			$mostraLOG_EMISDIA = "checked";
		} else {
			$mostraLOG_EMISDIA = "";
		}
		$cod_template_tkt = $qrBuscaConfiguracao['COD_TEMPLATE_TKT'];
		$qtd_compras_tkt = $qrBuscaConfiguracao['QTD_COMPRAS_TKT'];
		$qtd_ofertas_tkt = $qrBuscaConfiguracao['QTD_OFERTAS_TKT'];
		$qtd_ofertws_tkt = $qrBuscaConfiguracao['QTD_OFERTWS_TKT'];
		$qtd_ofertas_lst = $qrBuscaConfiguracao['QTD_OFERTAS_LST'];
		$qtd_categor_tkt = $qrBuscaConfiguracao['QTD_CATEGOR_TKT'];
		$qtd_produtos_tkt = $qrBuscaConfiguracao['QTD_PRODUTOS_TKT'];
		$qtd_produtos_cat = $qrBuscaConfiguracao['QTD_PRODUTOS_CAT'];
		$num_historico_tkt = $qrBuscaConfiguracao['NUM_HISTORICO_TKT'];
		$min_historico_tkt = $qrBuscaConfiguracao['MIN_HISTORICO_TKT'];
		$max_historico_tkt = $qrBuscaConfiguracao['MAX_HISTORICO_TKT'];
		$cod_blklist = $qrBuscaConfiguracao['COD_BLKLIST'];
		$des_pratprc = $qrBuscaConfiguracao['DES_PRATPRC'];
		$des_validade = $qrBuscaConfiguracao['DES_VALIDADE'];
		$log_listaws = $qrBuscaConfiguracao['LOG_LISTAWS'];
		if ($log_listaws == "S") {
			$mostraLOG_LISTAWS = "checked";
		} else {
			$mostraLOG_LISTAWS = "";
		}
	} else {
		$cod_configu = 0;
		$log_ativo_tkt = "";
		$log_emisdia = "";
		$cod_template_tkt = 0;
		$qtd_compras_tkt = "";
		$qtd_ofertas_tkt = "";
		$qtd_ofertws_tkt = "";
		$qtd_ofertas_lst = "";
		$qtd_categor_tkt = "";
		$qtd_produtos_tkt = "1";
		$qtd_produtos_cat = "1";
		$num_historico_tkt = "";
		$min_historico_tkt = "0";
		$max_historico_tkt = "30";
		$cod_blklist = "0";
		$des_validade = "0";
		$mostraLOG_EMISDIA = '';
		$mostraLOG_ATIVO_TKT = '';
		$mostraLOG_LISTAWS = '';
	}
} else {
	$cod_configu = 0;
	$log_ativo_tkt = "";
	$log_emisdia = "";
	$cod_template_tkt = 0;
	$qtd_compras_tkt = "";
	$qtd_ofertas_tkt = "";
	$qtd_ofertws_tkt = "";
	$qtd_categor_tkt = "";
	$qtd_produtos_tkt = "";
	$num_historico_tkt = "";
	$min_historico_tkt = "0";
	$max_historico_tkt = "30";
	$des_validade = "0";
	$cod_blklist = "0";
	$mostraLOG_EMISDIA = '';
	$mostraLOG_ATIVO_TKT = '';
	$mostraLOG_LISTAWS = '';
}
//print_r(explode(@$_REQUEST['NUM_HISTORICO_TKT']));	
//fnMostraForm();
//fnEscreve($min_historico_tkt);	
//fnEscreve($max_historico_tkt);	

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
							<i class="fal fa-terminal"></i>
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

					<?php $abaModulo = 1126;
					include "abasTicketConfig.php"; ?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>
									</div>

									<div class="col-md-2 text-center">
										<div class="form-group">
											<label for="inputName" class="control-label">Ticket ativo</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_ATIVO_TKT" id="LOG_ATIVO_TKT" class="switch" value="S" <?php echo $mostraLOG_ATIVO_TKT; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2 text-center">
										<div class="form-group">
											<label for="inputName" class="control-label">Retorna Lista WS</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_LISTAWS" id="LOG_LISTAWS" class="switch" value="S" <?php echo $mostraLOG_LISTAWS; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2 text-center">
										<div class="form-group">
											<label for="inputName" class="control-label">Emissão Diária</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_EMISDIA" id="LOG_EMISDIA" class="switch" value="S" <?php echo $mostraLOG_EMISDIA; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label LBL_EMISDIA">Validade do Ticket</label>
											<select data-placeholder="Selecione uma prática de preço" name="DES_VALIDADE" id="DES_VALIDADE" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1">
												<option></option>
												<option value="1">1 dia</option>
												<option value="2">2 dias</option>
												<option value="3">3 dias</option>
												<option value="4">4 dias</option>
												<option value="5">5 dias</option>
												<option value="6">6 dias</option>
												<option value="7">7 dias</option>
											</select>
											<script>
												$("#formulario #DES_VALIDADE").val("<?php echo $des_validade; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Prática de preço</label>
											<select data-placeholder="Selecione uma prática de preço" name="DES_PRATPRC" id="DES_PRATPRC" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<option></option>
												<option value="Gestão ofertas" disabled>Gestão de ofertas MARKA</option>
												<option value="Sistema PDV">Sistema de PDV/ERP</option>
												<option value="Menor" disabled>Menor preço</option>
											</select>
											<script>
												$("#formulario #DES_PRATPRC").val("<?php echo $des_pratprc; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>

									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Modelo do template do ticket</label>

											<select data-placeholder="Selecione um modelo de ticket" name="COD_TEMPLATE_TKT" id="COD_TEMPLATE_TKT" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<option></option>
												<?php
												$sql = "SELECT  * FROM TEMPLATE WHERE cod_empresa = $cod_empresa ORDER BY NOM_TEMPLATE ";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
												while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

													if ($qrListaPersonas['LOG_ATIVO'] != "S") {
														$desabilitado = "disabled";
													} else {
														$desabilitado = "";
													}

													echo "
															  <option value='" . $qrListaPersonas['COD_TEMPLATE'] . "' " . $desabilitado . ">" . ucfirst($qrListaPersonas['NOM_TEMPLATE']) . "</option> 
															";
												}
												?>
											</select>
											<script>
												$("#formulario #COD_TEMPLATE_TKT").val("<?php echo $cod_template_tkt; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>

									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Quantidade de produtos <br /> no hábito de compra</label>
											<input type="text" class="form-control input-sm text-center int" name="QTD_COMPRAS_TKT" id="QTD_COMPRAS_TKT" maxlength="2" value="<?php echo $qtd_compras_tkt; ?>" required>
											<div class="help-block with-errors"></div>
											<span class="help-block"></span>
											<!--<span class="help-block">Leve também</span>-->
										</div>
									</div>


									<?php
									$sql = "select COUNT(COD_CATEGORTKT) AS QTD_CATEGORIAS from CATEGORIATKT where COD_EMPRESA = '" . $cod_empresa . "' AND DAT_EXCLUSA IS NULL";
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$qrListaPersonas = mysqli_fetch_assoc($arrayQuery);
									$qtd_categorias = $qrListaPersonas['QTD_CATEGORIAS'];

									?>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label" style="margin-bottom: 4px;">Categorias Ativas</label>
											<div class="push15"></div>
											<input type="text" class="form-control input-sm text-center int" readonly="readonly" name="QTD_CATEGORIAS" id="QTD_CATEGORIAS" maxlength="2" value="<?php echo $qtd_categorias; ?>">
											<div class="help-block with-errors"></div>
											<!--<span class="help-block">Lista de ofertas</span>-->
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Quantidade <b>máxima</b> de produtos<br />em cada categoria</label>
											<input type="text" class="form-control input-sm text-center int" name="QTD_PRODUTOS_CAT" id="QTD_PRODUTOS_CAT" maxlength="2" value="<?php echo $qtd_produtos_cat; ?>" required>
											<div class="help-block with-errors"></div>
											<span class="help-block"></span>
											<!--<span class="help-block">Lista de ofertas</span>-->
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Quantidade <b>máxima</b> de<br />produtos apresentados</label>
											<input type="text" class="form-control input-sm text-center int" name="QTD_PRODUTOS_TKT" id="QTD_PRODUTOS_TKT" maxlength="2" value="<?php echo $qtd_produtos_tkt; ?>" required>
											<div class="help-block with-errors"></div>
											<span class="help-block">Necessário produtos ativos e na validade</span>
											<!--<span class="help-block">Lista de ofertas</span>-->
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Quantidade de <br />ofertas em destaque</label>
											<input type="text" class="form-control input-sm text-center int" name="QTD_OFERTAS_TKT" id="QTD_OFERTAS_TKT" maxlength="2" value="<?php echo $qtd_ofertas_tkt; ?>" required>
											<div class="help-block with-errors"></div>
											<span class="help-block"></span>
											<!--<span class="help-block">Oferta em destaque</span>-->
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Quantidade da lista <br />adicional de ofertas</label>
											<input type="text" class="form-control input-sm text-center int" name="QTD_OFERTWS_TKT" id="QTD_OFERTWS_TKT" maxlength="2" value="<?php echo $qtd_ofertws_tkt; ?>" required>
											<div class="help-block with-errors"></div>
											<span class="help-block">Web service</span>
										</div>
									</div>


								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label">Hábitos de exclusão</label>

											<select data-placeholder="Selecione um ou mais hábitos de consumo para exclusão" name="COD_BLKLIST[]" id="COD_BLKLIST" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
												<?php
												$sql = "SELECT COD_BLKLIST," .
													"ABV_BLKLIST " .
													"FROM blacklisttkt where COD_EMPRESA = $cod_empresa and COD_EXCLUSA = 0 ";

												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
												while ($qrListaBlkList = mysqli_fetch_assoc($arrayQuery)) {
													echo "
															  <option value='" . $qrListaBlkList['COD_BLKLIST'] . "'>" . ucfirst($qrListaBlkList['ABV_BLKLIST']) . "</option> 
															";
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
											<script>
												//retorno combo multiplo
												if ("<?php echo $cod_blklist; ?>" != "0") {
													var sistemasHab = "<?php echo $cod_blklist; ?>";
													var sistemasHabArr = sistemasHab.split(',');
													//opções multiplas
													for (var i = 0; i < sistemasHabArr.length; i++) {
														$("#formulario #COD_BLKLIST option[value=" + sistemasHabArr[i] + "]").prop("selected", "true");
													}
													$("#formulario #COD_BLKLIST").trigger("chosen:updated");
												} else {
													$("#formulario #COD_BLKLIST").val('').trigger("chosen:updated");
												}
											</script>
										</div>
									</div>

									<div class="col-md-1">
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Período de busca no histórico de compras do cliente</label>
											<div class="push10"></div>
											<input type="text" name="NUM_HISTORICO_TKT[]" id="NUM_HISTORICO_TKT" value="" />
											<div class="push30"></div>
											<span class="help-block">Intervalo de dias</span>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php

								//fnEscreve($cod_configu);
								if ($cod_configu == "0") { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Atualizar Configuração</button>
								<?php } ?>

							</div>

							<input type="hidden" name="QTD_OFERTAS_LST" id="QTD_OFERTAS_LST" value="0">
							<input type="hidden" name="QTD_CATEGOR_TKT" id="QTD_CATEGOR_TKT" value="0">
							<input type="hidden" name="COD_CONFIGU" id="COD_CONFIGU" value="<?php echo $cod_configu; ?>">
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

	<script src="js/plugins/ion.rangeSlider.js"></script>
	<link rel="stylesheet" href="css/ion.rangeSlider.css" />
	<link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.css" />

	<div class="push20"></div>

	<script type="text/javascript">
		$(document).ready(function() {

			//chosen

			$("#LOG_EMISDIA").on("change", function() {
				if ($(this).is(':checked')) {
					$("#DES_VALIDADE").chosen("destroy");
					$("#DES_VALIDADE").attr("required", true).chosen({
						allow_single_deselect: true
					});
					$(".LBL_EMISDIA").addClass("required");

				} else {
					$("#DES_VALIDADE").chosen("destroy");
					$("#DES_VALIDADE").attr("required", false).chosen({
						allow_single_deselect: true
					});
					$(".LBL_EMISDIA").removeClass("required");
				}
				$('#formulario').validator('destroy');
				$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
				$('#formulario').validator();
			});


		});

		$(function() {

			$("#NUM_HISTORICO_TKT").ionRangeSlider({
				hide_min_max: true,
				keyboard: true,
				min: 0,
				max: 120,
				from: <?php echo $min_historico_tkt; ?>,
				to: <?php echo $max_historico_tkt; ?>,
				type: 'int',
				step: 5,
				//prettify_enabled: true,
				//prettify_separator: "."
				//prefix: "Idade ",
				postfix: " dias",
				max_postfix: ""
				//grid: true
			});
			/*
			$("#range").ionRangeSlider();
			*/

		});

		function retornaForm(index) {
			$("#formulario #COD_BLKLIST").val($("#ret_COD_BLKLIST_" + index).val());
			$("#formulario #TIP_BLKLIST").val($("#ret_TIP_BLKLIST_" + index).val()).trigger("chosen:updated");
			$("#formulario #NOM_BLKLIST").val($("#ret_NOM_BLKLIST_" + index).val());
			$("#formulario #ABV_BLKLIST").val($("#ret_ABV_BLKLIST_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>