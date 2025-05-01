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
$cod_comunic = "";
$cod_campanha = "";
$cod_comunicacao = "";
$cod_tipcomu = "";
$des_texto_sms = "";
$cod_disparo = "";
$cod_modmail = "";
$cod_ctrlenv = "";
$log_saldo = "";
$log_totem = "";
$log_web = "";
$log_hotsite = "";
$arrayQuery = [];
$qrListaVariaveis = "";
$cod_bancovar = "";
$cod_program = "";
$nom_empresa = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$qrBuscaEmpresa = "";
$abaPersona = "";
$abaVantagem = "";
$abaRegras = "";
$abaComunica = "";
$abaAtivacao = "";
$abaResultado = "";
$abaPersonaComp = "";
$abaVantagemComp = "";
$abaRegrasComp = "";
$abaComunicaComp = "";
$abaAtivacaoComp = "";
$abaResultadoComp = "";
$qrBuscaCampanha = "";
$log_ativo = "";
$des_campanha = "";
$abr_campanha = "";
$des_icone = "";
$tip_campanha = "";
$log_realtime = "";
$qrBuscaTpCampanha = "";
$nom_tpcampa = "";
$abv_tpcampa = "";
$des_iconecp = "";
$label_1 = "";
$label_2 = "";
$label_3 = "";
$label_4 = "";
$label_5 = "";
$cod_persona = "";
$tem_personas = "";
$pct_vantagem = "";
$qtd_vantagem = 0;
$qtd_resultado = 0;
$nom_vantagem = "";
$num_pessoas = "";
$cod_vantage = "";
$num_minresg = "";
$formBack = "";
$abaCampanhas = "";
$qrBuscaFases = "";
$sql2 = "";
$qrBuscaFasesCupom = "";
$qrListaComunica = "";
$desabilitado = "";
$qrBuscaComunicacao = "";
$temp = "";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_comunic = fnLimpaCampoZero(@$_REQUEST['COD_COMUNIC']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
		$cod_campanha = fnLimpaCampo(@$_REQUEST['COD_CAMPANHA']);
		$cod_comunicacao = fnLimpaCampoZero(@$_REQUEST['COD_COMUNICACAO']);
		$cod_tipcomu = 4; //tipo sms transacional -- comunicacao_tipo
		$des_texto_sms = @$_REQUEST['DES_TEXTO_SMS'];
		$cod_disparo = 0;
		$cod_modmail = 0;
		$cod_ctrlenv = fnLimpaCampoZero(@$_REQUEST['COD_CTRLENV']);
		if (empty(@$_REQUEST['LOG_SALDO'])) {
			$log_saldo = 'N';
		} else {
			$log_saldo = @$_REQUEST['LOG_SALDO'];
		}
		if (empty(@$_REQUEST['LOG_TOTEM'])) {
			$log_totem = 'N';
		} else {
			$log_totem = @$_REQUEST['LOG_TOTEM'];
		}
		if (empty(@$_REQUEST['LOG_WEB'])) {
			$log_web = 'N';
		} else {
			$log_web = @$_REQUEST['LOG_WEB'];
		}
		if (empty(@$_REQUEST['LOG_HOTSITE'])) {
			$log_hotsite = 'N';
		} else {
			$log_hotsite = @$_REQUEST['LOG_HOTSITE'];
		}

		$sql = "select * from VARIAVEIS order by NUM_ORDENAC ";
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

		while ($qrListaVariaveis = mysqli_fetch_assoc($arrayQuery)) {

			if (strlen(strstr($des_texto_sms, $qrListaVariaveis['KEY_BANCOVAR'])) > 0) {
				//fnEscreve($qrListaVariaveis['NOM_BANCOVAR']);
				$cod_bancovar = $cod_bancovar . $qrListaVariaveis['COD_BANCOVAR'] . ",";
			} else {
				$cod_bancovar = "";
			}
		}

		$cod_bancovar = rtrim(ltrim($cod_bancovar, ','), ',');
		//fnEscreve($cod_bancovar);		

		$cod_program = fnLimpaCampoZero(@$_REQUEST['COD_PROGRAM']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$nom_empresa = fnLimpaCampo(@$_REQUEST['NOM_EMPRESA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			$sql = "CALL SP_ALTERA_COMUNICACAO_MODELO (
				 '" . $cod_comunic . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_campanha . "', 
				 '" . $cod_comunicacao . "', 
				 '" . $cod_tipcomu . "', 
				 '" . $des_texto_sms . "', 
				 '" . $cod_bancovar . "', 
				 '" . $cod_usucada . "', 
				 '" . $cod_disparo . "', 
				 '" . $cod_modmail . "', 
				 '" . $cod_ctrlenv . "',   
				 '" . $log_saldo . "',   
				 '" . $log_totem . "',   
				 '" . $log_web . "',   
				 '" . $log_hotsite . "',   
				 '" . $opcao . "'    
				) ";

			// fnEscreve($sql);
			// fntestesql(connTemp($cod_empresa,""),trim($sql));

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
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];

		//liberação das abas
		$abaPersona	= "S";
		$abaVantagem = "S";
		$abaRegras = "S";
		$abaComunica = "N";
		$abaAtivacao = "N";
		$abaResultado = "N";

		$abaPersonaComp = "completed ";
		$abaVantagemComp = "completed ";
		$abaRegrasComp = "completed ";
		$abaComunicaComp = "active";
		$abaAtivacaoComp = "";
		$abaResultadoComp = "";
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//busca dados da campanha
$cod_campanha = fnDecode(@$_GET['idc']);
$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaCampanha)) {
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

if (isset($qrBuscaTpCampanha)) {
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
	$cod_persona = @$qrBuscaTpCampanha['COD_PERSONA'];
	if (!empty($cod_persona)) {
		$tem_personas = "sim";
	} else {
		$tem_personas = "nao";
	}
	$pct_vantagem = @$qrBuscaTpCampanha['PCT_VANTAGEM'];
	$qtd_vantagem = @$qrBuscaTpCampanha['QTD_VANTAGEM'];
	$qtd_resultado = @$qrBuscaTpCampanha['QTD_RESULTADO'];
	$nom_vantagem = @$qrBuscaTpCampanha['NOM_VANTAGE'];
	$num_pessoas = @$qrBuscaTpCampanha['NUM_PESSOAS'];
	$cod_vantage = @$qrBuscaTpCampanha['COD_VANTAGE'];
}


//fnMostraForm();	
//fnEscreve($num_minresg);

?>

<link rel="stylesheet" href="css/widgets.css" />

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				//$formBack = "1169";
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

				<?php $abaCampanhas = 1169;
				include "abasCampanhasConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_COMUNIC" id="COD_COMUNIC" value="">
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

						<fieldset>
							<legend>Banco de Variáveis <small>(<b>Clique e arraste</b> a tag desejada ou <b>copie</b> na área desejada)</small> </legend>

							<div class="row">

								<div class="col-md-12">
									<?php

									//fnEscreve($cod_campanha);

									//busca dados da campanha
									$cod_campanha = fnDecode(@$_GET['idc']);
									$sql = "SELECT TIP_CAMPANHA FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

									if (isset($qrBuscaCampanha)) {
										$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
									}
									//fnEscreve($tip_campanha);
									//fnEscreve(1);

									// $sql = "select * from VARIAVEIS where COD_BANCOVAR in (3,23,39,41,44,45) order by NUM_ORDENAC";
									$sql = "select * from VARIAVEIS where LOG_SMS = 'S' order by NUM_ORDENAC";
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

									while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
									?>
										<a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
											dragTagName="<?= $qrBuscaFases['KEY_BANCOVAR'] ?>"
											onclick="$(function(){quickCopy('<?= $qrBuscaFases['KEY_BANCOVAR'] ?>')});">
											<span><?= $qrBuscaFases['ABV_BANCOVAR'] ?></span>
										</a>

										<?php
									}

									if ($tip_campanha == 20) {

										$sql2 = "select * from VARIAVEIS where COD_BANCOVAR in (33,34) order by NUM_ORDENAC";
										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql2);
										while ($qrBuscaFasesCupom = mysqli_fetch_assoc($arrayQuery)) {
										?>
											<a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
												dragTagName="<?= $qrBuscaFasesCupom['KEY_BANCOVAR'] ?>"
												onclick="$(function(){quickCopy('<?= $qrBuscaFasesCupom['KEY_BANCOVAR'] ?>')});">
												<span><?= $qrBuscaFasesCupom['ABV_BANCOVAR'] ?></span>
											</a>

									<?php
										}
									}


									?>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<fieldset>
							<legend>Dados do Sms</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Gatilho </label>
										<select data-placeholder="Selecione o gatilho" name="COD_COMUNICACAO" id="COD_COMUNICACAO" class="chosen-select-deselect requiredChk" required>
											<option value=""></option>
											<option value="99">Aniversário</option>
											<option value="98">Atualização de Cadastro</option>
											<!-- <?php
													$sql = "select * from comunicacao order by DES_COMUNICACAO ";
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

													while ($qrListaComunica = mysqli_fetch_assoc($arrayQuery)) {
														if ($qrListaComunica['COD_COMUNICACAO'] == 1) {
															$desabilitado = "disabled";
														} else { {
																$desabilitado = "";
															}
														}
														echo "
																				  <option value='" . $qrListaComunica['COD_COMUNICACAO'] . "' $desabilitado >" . $qrListaComunica['DES_COMUNICACAO'] . "</option> 
																				";
													}
													?> -->
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Controle de Exibição </label>
										<select data-placeholder="Selecione o controle" name="COD_CTRLENV" id="COD_CTRLENV" class="chosen-select-deselect requiredChk" required>
											<option value=""></option>
											<!-- <option value="0">Enviar a cada evento</option>											   -->
											<option value="1">No dia</option>
											<option value="7">Na semana</option>
											<option value="30">No mês</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Tela de Saldo</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_SALDO" id="LOG_SALDO" class="switch" value="S">
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Totem</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_TOTEM" id="LOG_TOTEM" class="switch" value="S">
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Hotsite</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_HOTSITE" id="LOG_HOTSITE" class="switch" value="S">
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Webservice</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_WEB" id="LOG_WEB" class="switch" value="S">
											<span></span>
										</label>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-11">
									<div class="form-group">
										<label for="inputName" class="control-label required">Texto da Mensagem</label>
										<input type="text" class="form-control input-sm" name="DES_TEXTO_SMS" id="DES_TEXTO_SMS" maxlength="160" value="" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Caracteres</label>
										<input type="text" class="form-control input-sm text-center leitura" readonly="readonly" name="nType" id="nType" value="200">
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="COD_PROGRAM" id="COD_PROGRAM" value="<?php echo $cod_campanha ?>">
						<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div id="div_Ordena"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tablesorter">
									<thead>
										<tr>
											<th class="{sorter:false}" width="40"></th>
											<th>Código</th>
											<th>Momento</th>
											<th>Texto</th>
										</tr>
									</thead>
									<tbody>

										<?php
										$sql = "select A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
													LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
													where COMUNICACAO_MODELO.cod_empresa = $cod_empresa AND COD_CAMPANHA = '$cod_campanha' AND COD_TIPCOMU = '4'  AND COD_EXCLUSA = 0 
													ORDER BY COD_COMUNICACAO
													";
										//fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

										$count = 0;

										while ($qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											echo "
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaComunicacao['COD_COMUNIC'] . "</td>
															  <td>" . $qrBuscaComunicacao['DES_COMUNICACAO'] . "</td>
															  <td>" . $qrBuscaComunicacao['DES_TEXTO_SMS'] . "</td>
															</tr>
															<input type='hidden' id='ret_COD_COMUNIC_" . $count . "' value='" . $qrBuscaComunicacao['COD_COMUNIC'] . "'>
															<input type='hidden' id='ret_COD_CTRLENV_" . $count . "' value='" . $qrBuscaComunicacao['COD_CTRLENV'] . "'>
															<input type='hidden' id='ret_COD_COMUNICACAO_" . $count . "' value='" . $qrBuscaComunicacao['COD_COMUNICACAO'] . "'>
															<input type='hidden' id='ret_DES_TEXTO_SMS_" . $count . "' value='" . $qrBuscaComunicacao['DES_TEXTO_SMS'] . "'>
															<input type='hidden' id='ret_LOG_SALDO_" . $count . "' value='" . $qrBuscaComunicacao['LOG_SALDO'] . "'>
															<input type='hidden' id='ret_LOG_TOTEM_" . $count . "' value='" . $qrBuscaComunicacao['LOG_TOTEM'] . "'>
															<input type='hidden' id='ret_LOG_WEB_" . $count . "' value='" . $qrBuscaComunicacao['LOG_WEB'] . "'>
															<input type='hidden' id='ret_LOG_HOTSITE_" . $count . "' value='" . $qrBuscaComunicacao['LOG_HOTSITE'] . "'>
															";
										}

										?>

									</tbody>
								</table>

							</form>

						</div>

					</div>

					<div class="push30"></div>

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

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$("#COD_COMUNICACAO").change(function() {
			var cod = $(this).val();

			$("#COD_CTRLENV option").remove();

			if (cod == 98) {

				$("#COD_CTRLENV")
					.append('<option value=""></option>')
					.append('<option value="6">6 meses</option>')
					.append('<option value="365">12 meses</option>')
					.trigger("chosen:updated");

			} else {

				$("#COD_CTRLENV")
					.append('<option value=""></option>')
					.append('<option value="1">No dia</option>')
					.append('<option value="7">Na semana</option>')
					.append('<option value="30">No mês</option>')
					.trigger("chosen:updated");

			}

		});

	});


	$('.dragTag').on('dragstart', function(event) {
		var tag = $(this).attr('dragTagName');
		event.originalEvent.dataTransfer.setData("text", ' ' + tag + ' ');
		event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
	});


	$('.dragTag').on('click', function(event) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val(" @" + $(this).text() + " ").select();
		document.execCommand("copy");
		$temp.remove();
	});




	function quickCopy(tag) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val("@" + tag + " ").select();
		document.execCommand("copy");
		$temp.remove();
	}

	$('#DES_TEXTO_SMS').keyup(updateCount);
	$('#DES_TEXTO_SMS').keydown(updateCount);
	$('#DES_TEXTO_SMS').change(updateCount);

	function updateCount() {
		var cs = [200 - $(this).val().length];
		//var cs = [$(this).val().length];
		//$('#characters').text(cs);
		$('#nType').val(cs);
	}

	function retornaForm(index) {

		$("#formulario #COD_COMUNIC").val($("#ret_COD_COMUNIC_" + index).val());
		$("#formulario #DES_TEXTO_SMS").val($("#ret_DES_TEXTO_SMS_" + index).val());
		$("#formulario #COD_COMUNICACAO").val($("#ret_COD_COMUNICACAO_" + index).val()).trigger("chosen:updated");

		var cod = $("#ret_COD_COMUNICACAO_" + index).val();
		$("#COD_CTRLENV option").remove();

		if (cod == 98) {

			$("#COD_CTRLENV")
				.append('<option value="6">6 meses</option>')
				.append('<option value="365">12 meses</option>')
				.trigger("chosen:updated");

		} else {

			$("#COD_CTRLENV")
				.append('<option value="1">No dia</option>')
				.append('<option value="7">Na semana</option>')
				.append('<option value="30">No mês</option>')
				.trigger("chosen:updated");

		}
		$("#formulario #COD_CTRLENV").val($("#ret_COD_CTRLENV_" + index).val()).trigger("chosen:updated");

		if ($("#ret_LOG_SALDO_" + index).val() == 'S') {
			$('#formulario #LOG_SALDO').prop('checked', true);
		} else {
			$('#formulario #LOG_SALDO').prop('checked', false);
		}
		if ($("#ret_LOG_TOTEM_" + index).val() == 'S') {
			$('#formulario #LOG_TOTEM').prop('checked', true);
		} else {
			$('#formulario #LOG_TOTEM').prop('checked', false);
		}
		if ($("#ret_LOG_WEB_" + index).val() == 'S') {
			$('#formulario #LOG_WEB').prop('checked', true);
		} else {
			$('#formulario #LOG_WEB').prop('checked', false);
		}
		if ($("#ret_LOG_HOTSITE_" + index).val() == 'S') {
			$('#formulario #LOG_HOTSITE').prop('checked', true);
		} else {
			$('#formulario #LOG_HOTSITE').prop('checked', false);
		}
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>