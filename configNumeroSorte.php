<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
		$cod_cupom = fnLimpaCampo($_REQUEST['COD_CUPOM']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$num_sorteio = fnLimpaCampoZero($_REQUEST['NUM_SORTEIO']);
		$num_sorteado = fnLimpaCampoZero($_REQUEST['NUM_SORTEADO']);
		$num_tamanho = fnLimpaCampoZero($_REQUEST['NUM_TAMANHO']);
		$num_faixain = fnLimpaCampoZero($_REQUEST['NUM_FAIXAIN']);
		$num_faixafi = fnLimpaCampoZero($_REQUEST['NUM_FAIXAFI']);
		$hor_ini = fnLimpaCampo($_REQUEST['HOR_INI']);
		$dat_inicio = fndatasql($_REQUEST['DAT_INICIO']);
		$dat_fim = fndatasql($_REQUEST['DAT_FIM']);
		$hor_fim = fnLimpaCampo($_REQUEST['HOR_FIM']);
		if ($_REQUEST['DAT_SORTEIO'] != "") {
			$dat_sorteio = fnLimpaCampo(fnDataSql($_REQUEST['DAT_SORTEIO']));
		} else {
			$dat_sorteio = "1969-12-31";
		}

		// //array das unidades de venda
		if (isset($_POST['COD_UNIVEND'])) {
			$Arr_COD_UNIVEND = $_POST['COD_UNIVEND'];
			//print_r($Arr_COD_MULTEMP);			 

			for ($i = 0; $i < count($Arr_COD_UNIVEND); $i++) {
				$cod_univend = $cod_univend . $Arr_COD_UNIVEND[$i] . ",";
			}

			$cod_univend = substr($cod_univend, 0, -1);
		} else {
			$cod_univend = "";
		}


		// //array das regiões
		if (isset($_POST['COD_TIPOREG'])) {
			$Arr_COD_TIPOREG = $_POST['COD_TIPOREG'];
			//print_r($Arr_COD_MULTEMP);			 

			for ($i = 0; $i < count($Arr_COD_TIPOREG); $i++) {
				$cod_tiporeg = $cod_tiporeg . $Arr_COD_TIPOREG[$i] . ",";
			}

			$cod_tiporeg = substr($cod_tiporeg, 0, -1);
		} else {
			$cod_tiporeg = "0";
		}

		$geral = fnLimpaCampo($_REQUEST['GERAL']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$sql = "SELECT * FROM CUPOM WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";

		$arrayQuery = mysqli_query($conn, $sql);

		if ($opcao != '') {

			$sql = "CALL SP_CUPOM (
			'" . $cod_empresa . "', 
			'" . $cod_campanha . "', 
			'" . $num_sorteio . "', 
			'" . $dat_sorteio . "',    
			'" . $num_sorteado . "',    
			'" . $cod_univend . "',    
			'" . $num_tamanho . "',    
			'" . $num_faixain . "',    
			'" . $num_faixafi . "',    
			'" . $cod_usucada . "',
			'" . $cod_tiporeg . "',
			'" . $geral . "',
			'" . $dat_inicio . "',
			'" . $dat_fim . "', 
			'" . $hor_ini . "',
			'" . $hor_fim . "'
		) ";

			// fnEscreve($sql);								
			$arrayProc = mysqli_query($conn, $sql);

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			}
			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
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
			$opcao = "";
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, LOG_CATEGORIA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$log_categoria = $qrBuscaEmpresa['LOG_CATEGORIA'];

		//liberação das abas
		$abaPersona	= "S";
		$abaVantagem = "S";
		$abaRegras = "S";
		$abaComunica = "N";
		$abaAtivacao = "N";
		$abaResultado = "N";

		$abaPersonaComp = "completed ";
		$abaVantagemComp = "completed ";
		$abaRegrasComp = "active ";
		$abaComunicaComp = "";
		$abaResultadoComp = "";
		//revalidada na aba de regras	
		$abaAtivacaoComp = "";
	}

	//busca dados da campanha
	$cod_campanha = fnDecode($_GET['idc']);
	$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($conn, $sql);
	$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
		$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
		$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
		$des_icone = $qrBuscaCampanha['DES_ICONE'];
		$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
		$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
		$dat_inicio = $qrBuscaCampanha['DAT_INI'];
		$hor_ini = $qrBuscaCampanha['HOR_INI'];
		$dat_fim = $qrBuscaCampanha['DAT_FIM'];
		$hor_fim = $qrBuscaCampanha['HOR_FIM'];

		if ($log_realtime == "S") {
			$maxPersona = 1;
			$msgPersona = "Campanhas em <b>tempo real</b> permitem a utilização de <b>uma persona por campanha</b>";
		} else {
			$maxPersona = 10;
			$msgPersona = "";
		}
	}

	//fnEscreve($tip_campanha);

	//busca dados do tipo da campanha
	$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
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
	$arrayQuery = mysqli_query($conn, $sql);
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
		$custoReal = fnValor($qtd_vantagem / $qtd_resultado, 2);

		$log_cpfcnpj = $qrBuscaTpCampanha['LOG_CPFCNPJ'];
		if ($log_cpfcnpj == 'S') {
			$checaCPF = 'checked';
		} else {
			$checaCPF = '';
		}
		$log_email = $qrBuscaTpCampanha['LOG_EMAIL'];
		if ($log_email == 'S') {
			$checaMail = 'checked';
		} else {
			$checaMail = '';
		}
		$log_celular = $qrBuscaTpCampanha['LOG_CELULAR'];
		if ($log_celular == 'S') {
			$checaCel = 'checked';
		} else {
			$checaCel = '';
		}
		$log_produto = $qrBuscaTpCampanha['LOG_PRODUTO'];
		if ($log_produto == 'S') {
			$checaProduto = 'checked';
		} else {
			$checaProduto = '';
		}

		$tip_geracao = $qrBuscaTpCampanha['TIP_GERACAO'];
	} else {

		$cod_persona = 0;
		$pct_vantagem = "";
		$qtd_vantagem = "";
		$qtd_vantagem = "";
		$nom_vantagem = "";
		$num_pessoas = 0;
		$cod_vantage = 0;
		$custoReal = "";
		$tip_geracao = "";
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');

}

if ($tipoVenda == "T") {
	$checkTodas = "checked";
	$checkCreditos = "";
} else {
	$checkTodas = "";
	$checkCreditos = "checked";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";


//fnMostraForm();
//fnEscreve($log_categoria);


?>

<style>
	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
	}

	#blocker div {
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
	<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)</div>
</div>


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
				$formBack = "1019";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php $abaCampanhas = 1022;
				include "abasCampanhasConfig.php"; ?>

				<div class="push10"></div>

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>


				<?php $abaCli = 1406;
				include "abasRegrasConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais do Sorteio</legend>

							<div class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha ?>">
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo do Programa</label>
										<div class="push10"></div>
										<span class="fa <?php echo $des_iconecp; ?>"></span> <b><?php echo $nom_tpcampa; ?> </b>
									</div>
								</div>
								<?php
								if ($tip_geracao != "") {
									switch ($tip_geracao) {
										case "UND":
											$txtTip_geracao = "Por Unidade Individual";
											break;

										case "GRL":
											$txtTip_geracao = "Por Unidades Geral";
											break;

										case "REG":
											$txtTip_geracao = "Por Região";
											break;
									}
								?>
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Tipo do Geração dos Números</label>
											<div class="push10"></div>
											<b><?php echo $txtTip_geracao; ?> </b>
										</div>
									</div>
								<?php
								}
								?>
								<input type="hidden" name="TIP_GERACAO" id="TIP_GERACAO" value="<?php echo $tip_geracao; ?>">

								<div class="push30"></div>

								<!--<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Número do Concurso</label>
										<input type="text" class="form-control input-sm int" name="NUM_SORTEIO" id="NUM_SORTEIO" maxlength="20">
										<div class="help-block with-errors">Loteria Federal</div>
									</div>
								</div>-->

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Número do Concurso</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_SORTEIO" id="NUM_SORTEIO" value="<?php echo $num_sorteio ?>">
										<div class="help-block with-errors">Loteria Federal</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data do Sorteio</label>

										<div class="input-group date datePicker" id="DAT_SORTEIO_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_SORTEIO" id="DAT_SORTEIO" value="" required>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Regra do Sorteio</label>
										<input type="text" class="form-control input-sm leitura" name="DES_REGRA" id="DES_REGRA" maxlength="9" readonly value="Nro. imediatamente anterior ao primeiro premio sorteado">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<div class="row">
											<label for="inputName" class="control-label">Número do Sorteio</label>
											<a data-url="action.php?mod=<?php echo fnEncode(1956) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true"><i class="fa fa-search" style="margin-left: 5px; display: none; cursor: pointer;" id="icone-search"></i></a>
										</div>
										<input type="text" class="form-control input-sm int leitura" name="NUM_SORTEADO" id="NUM_SORTEADO" maxlength="9" readonly>
										<div class="help-block with-errors">Loteria Federal</div>
									</div>
								</div>

								<div class="col-md-2" id="btnVisu" style="display: none;">
									<div class="form-group btn btn-primary getBtn">
										<a style="text-decoration: none;" data-url="action.php?mod=<?php echo fnEncode(1957) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true"><span style="color: white;">Visualizar Ganhadores &nbsp;</span><i class="fa fa-search" style="margin-left: 5px; color: white; cursor: pointer;" id="modalLista"></i></a>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<fieldset>
							<legend>Dados do Ganhador</legend>

							<div class="row">

								<div class="col-md-5">
									<div class="form-group">
										<label for="inputName" class="control-label">Ganhador do Sorteio</label>
										<input type="text" class="form-control input-sm leitura" name="NOM_SORTEADO" id="NOM_SORTEADO" maxlength="9" readonly>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cupom Sorteado</label>
										<input type="text" class="form-control input-sm leitura" name="CUPOM_SORTEADO" id="CUPOM_SORTEADO" maxlength="9" readonly>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>
						<div class="push20"></div>

						<fieldset>
							<legend>Dados da Campanha</legend>

							<div class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Campanha</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $des_campanha ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Início</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo fnDataShort($dat_inicio); ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Hora Início</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $hor_ini ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Fim</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo fnDataShort($dat_fim); ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Hora Fim</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $hor_fim ?>">
									</div>
								</div>

							</div>

						</fieldset>
						<div class="push20"></div>

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<?php

								switch ($tip_geracao) {
									case "UND":
										//"Por Unidade Individual";
								?>
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Unidade de Atendimento</label>
												<?php include "unidadesAutorizadasComboMulti.php"; ?>
											</div>
										</div>
										<input type="hidden" name="COD_TIPOREG" id="COD_TIPOREG" value="">
										<input type="hidden" name="GERAL" id="GERAL" value="N">
									<?php
										break;

									case "GRL":
										//"Por Unidades Gerais";
									?>
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Unidade de Atendimento</label><br />
												<div class="push5"></div>
												<b>Todas as Unidades</b>
											</div>
										</div>
										<input type="hidden" name="COD_TIPOREG" id="COD_TIPOREG" value="">
										<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="">
										<input type="hidden" name="GERAL" id="GERAL" value="S">
									<?php
										break;

									case "REG":
										//"Por Região";
									?>
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Unidade de Atendimento</label>
												<?php include "grupoRegiaoMulti.php"; ?>
											</div>
											<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="">
											<input type="hidden" name="GERAL" id="GERAL" value="N">

										</div>
								<?php
										break;
								}
								?>



								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tamanho do Número </label>
										<select data-placeholder="Selecione o tamanho do cartão" name="NUM_TAMANHO" id="NUM_TAMANHO" class="chosen-select-deselect" required>
											<!-- <option value=""></option> -->
											<option value="4" disabled>4</option>
											<option value="5" selected>5</option>
											<option value="6" disabled>6</option>
											<option value="8" disabled>8</option>
										</select>
										<div class="help-block with-errors">Máximo permitido para gerar cupom é de 5 digitos</div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Faixa Inicial</label>
										<input type="text" class="form-control input-sm int" name="NUM_FAIXAIN" id="NUM_FAIXAIN" maxlength="5" required>
										<div class="help-block with-errors">De 1 a quanto deseja iniciar sua faixa</div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Faixa Final</label>
										<input type="text" class="form-control input-sm int" name="NUM_FAIXAFI" id="NUM_FAIXAFI" maxlength="5" required>
										<div class="help-block with-errors">Faixa máxima de 99999</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data início do concurso</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP1">
											<input type='text' class="form-control input-sm data" name="DAT_INICIO" id="DAT_INICIO" value="" required>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Hora Início</label>

										<div id="HOR_INI_GRP" class='input-group date clockPicker'>
											<input type='text' class="form-control input-sm" name="HOR_INI" id="HOR_INI" value="" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data fim do concurso</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="" required>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
										<div class="help-block with-errors" id="help-datafim"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Hora Final</label>
										<div id="HOR_FIM_GRP" class='input-group date clockPicker'>
											<input type='text' class="form-control input-sm" name="HOR_FIM" id="HOR_FIM" value="" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-time"></span>
											</span>
										</div>
										<div id="error-message" style="color: red;"></div>
									</div>
								</div>

							</div>
						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

						</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="COD_CUPOM" id="COD_CUPOM" value="">
						<input type="hidden" name="ATUALIZA_TELA" id="ATUALIZA_TELA" value="N">


						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th></th>
											<th>Código</th>
											<th>Nro. Concurso</th>
											<th>Dt. Sorteio</th>
											<th>Nro. Sorteio</th>
											<th>Tamanho</th>
											<th>Fx. Inicial</th>
											<th>Fx. Final</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT * FROM CUPOM 
										WHERE COD_EMPRESA = $cod_empresa
										AND COD_CAMPANHA = $cod_campanha";

										//fnEscreve($sql);		

										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrCupom = mysqli_fetch_assoc($arrayQuery)) {

											if (!empty($qrCupom['COD_UNIVEND'])) {
												$tem_unive = "sim";
											} else {
												$tem_unive = "nao";
											}

											if ($qrCupom['DAT_SORTEIO'] == "1969-12-31") {
												$data = "";
											} else {
												$data = fnDataShort($qrCupom['DAT_SORTEIO']);
											}

											$count++;

											$sqlSorteado = "SELECT NOM_CLIENTE,COD_CUPOM,NUM_CUPOM FROM geracupom a, clientes b
											WHERE A.COD_CLIENTE=B.COD_CLIENTE AND 
											A.COD_EMPRESA = $cod_empresa AND 
											A.COD_CAMPANHA = $cod_campanha AND 
											A.cod_cupom = $qrCupom[COD_CUPOM] 
											AND A.LOG_SORTEADO = 'S'";

											$arraySorteado = mysqli_query(connTemp($cod_empresa, ''), $sqlSorteado);

											$qrSorteado = mysqli_fetch_assoc($arraySorteado);

											$sorteado = $qrSorteado['NOM_CLIENTE'];
											$num_sorte = $qrSorteado['NUM_CUPOM'];


											if ($qrSorteado['NOM_CLIENTE'] == "") {
												$sorteado = "Não apurado";
												$num_sorte = "Não apurado";
											}

										?>

											<tr>
												<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(<?= $count ?>)'></th>
												<td><?= $qrCupom['COD_CUPOM'] ?></td>
												<td><?= $qrCupom['NUM_SORTEIO'] ?></td>
												<td><?= $data ?></td>
												<td><?= $qrCupom['NUM_SORTEADO'] ?></td>
												<td><?= $qrCupom['NUM_TAMANHO'] ?></td>
												<td><?= $qrCupom['NUM_FAIXAIN'] ?></td>
												<td><?= $qrCupom['NUM_FAIXAFI'] ?></td>
												<?php //if($qrCupom['DAT_SORTEIO'] >= date("d-m-Y")){ 
												?>
												<td class="text-center">
													<small>
														<div class="btn-group dropdown dropleft">
															<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																ações &nbsp;
																<span class="fas fa-caret-down"></span>
															</button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">

																<!-- <li><a href="javascript:void(0)" onclick='excluiCampanha("<?= fnEncode($cod_empresa) ?>","<?= fnEncode($qrListaCampanha['COD_CAMPANHA']) ?>","<?= $qrListaCampanha['DES_CAMPANHA'] ?>")'>Excluir </a></li> -->
																<?php if ($qrCupom['NUM_SORTEADO'] > 0) { ?>
																	<li><a href="javascript:void(0)" class="addBox" data-title="Ganhadores" data-url="action.php?mod=<?php echo fnEncode(1957) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&idcp=<?= $qrCupom['COD_CUPOM'] ?>&pop=true"><span class="fal fa-ticket"></span> Visualizar Ganhadores</a></li>
																<?php } else { ?>
																	<li><a href="javascript:void(0)" class="addBox" data-title="Encerrar Sorteio" data-url="action.php?mod=<?= fnEncode(1796) ?>&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&idcp=<?= fnEncode($qrCupom['COD_CUPOM']) ?>&pop=true"><span class="fal fa-ticket"></span> Encerrar Sorteio</a></li>
																<?php } ?>
															</ul>
														</div>
													</small>
												</td>
												<?php //} 
												?>
											</tr>

											<input type='hidden' id='ret_COD_CUPOM_<?= $count ?>' value='<?= $qrCupom['COD_CUPOM'] ?>'>
											<input type='hidden' id='ret_NUM_SORTEIO_<?= $count ?>' value='<?= $qrCupom['NUM_SORTEIO'] ?>'>
											<input type='hidden' id='ret_DAT_SORTEIO_<?= $count ?>' value='<?= $data ?>'>
											<input type='hidden' id='ret_NUM_SORTEADO_<?= $count ?>' value='<?= $qrCupom['NUM_SORTEADO'] ?>'>
											<input type='hidden' id='ret_DAT_INICIO_<?= $count ?>' value='<?= fnDataShort($qrCupom['DAT_INICIO']) ?>'>
											<input type='hidden' id='ret_DAT_FIM_<?= $count ?>' value='<?= fnDataShort($qrCupom['DAT_FIM']) ?>'>
											<input type='hidden' id='ret_HOR_INI_<?= $count ?>' value='<?= $qrCupom['HOR_INI'] ?>'>
											<input type='hidden' id='ret_HOR_FIM_<?= $count ?>' value='<?= $qrCupom['HOR_FIM'] ?>'>
											<input type='hidden' id='ret_COD_UNIVEND_<?= $count ?>' value='<?= $qrCupom['COD_UNIVEND'] ?>'>
											<input type='hidden' id='ret_NUM_TAMANHO_<?= $count ?>' value='<?= $qrCupom['NUM_TAMANHO'] ?>'>
											<input type='hidden' id='ret_NUM_FAIXAIN_<?= $count ?>' value='<?= $qrCupom['NUM_FAIXAIN'] ?>'>
											<input type='hidden' id='ret_NUM_FAIXAFI_<?= $count ?>' value='<?= $qrCupom['NUM_FAIXAFI'] ?>'>
											<input type='hidden' id='ret_DAT_SORTEIO_SQL_<?= $count ?>' value='<?= $qrCupom['DAT_SORTEIO'] ?>'>
											<input type='hidden' id='LOG_SORTEADO<?= $count ?>' value='<?= $qrCupom['LOG_SORTEADO'] ?>'>
											<input type='hidden' id='ret_NOM_SORTEADO_<?= $count ?>' value='<?= $sorteado ?>'>
											<input type='hidden' id='ret_CUPOM_SORTEADO_<?= $count ?>' value='<?= $num_sorte ?>'>
											<input type='hidden' id='ret_TEM_UNIVE_<?= $count ?>' value='<?= $tem_unive ?>'>

										<?php

										}

										?>

									</tbody>
								</table>

							</form>

						</div>

					</div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex="-1">
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" style="width: 100%; height: 80%">
				<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	$(document).ready(function() {
		$("#icone-search").on("click", function() {

			var codCupom = $("#COD_CUPOM").val();

			var url = $(this).parent().data("url") + "&idcp=" + codCupom;

			$("#popModal .modal-title").text("Cadastro Sorteio");

			$("#popModal iframe").attr("src", url);

			$("#popModal").appendTo('body').modal("show");
		});

		$('#DAT_FIM, #DAT_SORTEIO').on('blur', function() {
			let datFim = $('#DAT_FIM').val();
			let datSorteio = $('#DAT_SORTEIO').val();

			// Só faz a verificação se as duas datas estiverem preenchidas
			if (datFim && datSorteio) {
				// Função pra converter de dd/mm/yyyy para Date
				function parseDateBR(dateStr) {
					let parts = dateStr.split('/');
					return new Date(parts[2], parts[1] - 1, parts[0]); // yyyy, mm (0-based), dd
				}

				let dateFim = parseDateBR(datFim);
				let dateSorteio = parseDateBR(datSorteio);

				if (dateSorteio <= dateFim) {
					$('#help-datafim').html('A data do sorteio não pode ser o mesmo dia ou dia anterior a data final.').css('color', 'red');
					$('#DAT_FIM').val('');
				} else {
					$('#help-datafim').html('').css('color', '');
				}
			}
		});

	});

	$('.datePicker').datetimepicker({
		format: 'DD/MM/YYYY',
	}).on('dp.change', function(e) {
		$("#DAT_INI").trigger("change");
	});

	$('.clockPicker').datetimepicker({
		format: 'LT',
	}).on('changeDate', function(e) {
		$(this).datetimepicker('hide');
	});

	$(document).ready(function() {
		$("#btnVisu").on("click", "a[data-url]", function(e) {
			e.preventDefault();

			var codCupom = $("#COD_CUPOM").val();
			var url = $(this).data("url") + "&idcp=" + codCupom;

			$("#popModal .modal-title").text("Cadastro Sorteio");
			$("#popModal iframe").attr("src", url);
			$("#popModal").appendTo('body').modal("show");
		});
	});


	$(function() {
		var horasDesabilitadasInicio = [];
		var horasDesabilitadasFim = [];

		function obterHorasDesabilitadasIni(valorInputHorIni) {
			var partesHora = valorInputHorIni.split(":");
			var horaDesejadaHora = parseInt(partesHora[0], 10);

			var horasDesabilitadas = [];
			for (var hora = 0; hora < horaDesejadaHora; hora++) {
				horasDesabilitadas.push(hora);
			}

			return horasDesabilitadas;
		}

		function atualizarHorasDesabilitadasIni() {
			$('#HOR_INI_GRP').datetimepicker({
				format: 'LT',
				// disabledHours: horasDesabilitadasInicio || [],
			}).on('changeDate', function(e) {
				$(this).datetimepicker('hide');
			});
		}

		$('.modal').on('hidden.bs.modal', function() {
			if ($('#ATUALIZA_TELA').val() == "S") {
				location.reload(true);
			}
		});

		$('#DAT_INI_GRP1').datetimepicker({
			format: 'DD/MM/YYYY',
			// maxDate: '<?= str_replace("/", ",", $dat_fim); ?>',
			// minDate: '<?= str_replace("/", ",", $dat_inicio); ?>'
		}).on('dp.change', function(e) {
			$(this).datetimepicker('hide');
			var valorInputDatInicio = $("#DAT_INICIO").val();
			var valorInputHorIni = "<?= $hor_ini ?>";

			// if (valorInputDatInicio == "<?= fnDataShort($dat_inicio); ?>") {
			// 	horasDesabilitadasInicio = obterHorasDesabilitadasIni(valorInputHorIni);
			// }
			// atualizarHorasDesabilitadasIni();


			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		function obterHorasDesabilitadasFim(valorInputHorFim) {
			var partesHora = valorInputHorFim.split(":");
			var horaDesejadaHora = parseInt(partesHora[0], 10);

			var horasDesabilitadas = [];
			for (var hora = 0; hora < horaDesejadaHora; hora++) {
				horasDesabilitadas.push(hora);
			}

			return horasDesabilitadas;
		}

		function atualizarHorasDesabilitadasFim() {
			$('#HOR_FIM_GRP').datetimepicker({
				format: 'LT',
				disabledHours: horasDesabilitadasFim || [],
			}).on('changeDate', function(e) {
				$(this).datetimepicker('hide');
			});
		}

		$('#DAT_FIM_GRP').datetimepicker({
			format: 'DD/MM/YYYY',
			// maxDate: '<?= str_replace("/", ",", $dat_fim); ?>',
			// minDate: '<?= str_replace("/", ",", $dat_inicio); ?>'
		}).on('dp.change', function(e) {
			$(this).datetimepicker('hide');
			var valorInputDatInicio = $("#DAT_INICIO").val();
			var valorInputDatFim = $("#DAT_FIM").val();
			var valorInputHorFim = $("#HOR_INI").val();
			// console.log(valorInputDatInicio);
			// console.log(valorInputDatFim);
			// console.log(valorInputHorFim);

			// if (valorInputDatInicio == valorInputDatFim) {
			// 	horasDesabilitadasFim = obterHorasDesabilitadasFim(valorInputHorFim);
			// }
			// atualizarHorasDesabilitadasFim();
		});

		$('#DAT_FIM').val('');
		$('#DAT_INICIO').val('');
		$('#HOR_INI').val('');
		$('#HOR_FIM').val('');
	});

	// $("#DAT_SORTEIO").on("dp.change", function (e) {
	// 	$('#DAT_SORTEIO').data("DateTimePicker").minDate(e.date);
	// });

	$('#NUM_TAMANHO').change(function() {
		faixa = $('#NUM_TAMANHO').val();
		$('#NUM_FAIXAIN, #NUM_FAIXAFI').val('').attr('maxlength', faixa);
	});

	//$("#btnVisu").hide();

	function retornaForm(index) {

		$("#formulario #COD_CUPOM").val($("#ret_COD_CUPOM_" + index).val());
		$("#formulario #NUM_SORTEIO").val($("#ret_NUM_SORTEIO_" + index).val());
		$("#formulario #DAT_SORTEIO").val($("#ret_DAT_SORTEIO_" + index).val());
		$("#formulario #NOM_SORTEADO").val($("#ret_NOM_SORTEADO_" + index).val());
		$("#formulario #CUPOM_SORTEADO").val($("#ret_CUPOM_SORTEADO_" + index).val());
		$("#formulario #NUM_TAMANHO").val($("#ret_NUM_TAMANHO_" + index).val()).trigger("chosen:updated");
		$("#formulario #NUM_FAIXAIN").val($("#ret_NUM_FAIXAIN_" + index).val());
		$("#formulario #NUM_FAIXAFI").val($("#ret_NUM_FAIXAFI_" + index).val());
		$("#formulario #DAT_INICIO").val($("#ret_DAT_INICIO_" + index).val());
		$("#formulario #DAT_FIM").val($("#ret_DAT_FIM_" + index).val());
		$("#formulario #HOR_INI").val($("#ret_HOR_INI_" + index).val());
		$("#formulario #HOR_FIM").val($("#ret_HOR_FIM_" + index).val());
		//retorno combo multiplo - lojas
		$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");
		if ($("#ret_TEM_UNIVE_" + index).val() == "sim") {
			var sistemasUni = $("#ret_COD_UNIVEND_" + index).val();
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_UNIVEND option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_UNIVEND").trigger("chosen:updated");
		} else {
			$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");
		}

		var numSorteadoValue = $("#ret_NUM_SORTEADO_" + index).val();
		if (numSorteadoValue > 0) {
			$("#formulario #NUM_SORTEADO").val($("#ret_NUM_SORTEADO_" + index).val());
			$("#icone-search").hide();
			$("#btnVisu").show();
		} else {
			$("#formulario #NUM_SORTEADO").val($("#ret_NUM_SORTEADO_" + index).val());
			$("#icone-search").show();
			$("#btnVisu").hide();
		}

		// if($("#ret_DAT_SORTEIO_SQL_" + index).val() <= dt){
		// $("#encerra_sorteio").fadeIn("fast").attr("data-url", "action.php?mod=<?= fnEncode(1796) ?>&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&idcp="+$("#ret_COD_CUPOM_" + index).val()+"&pop=true");
		// }

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');

	}
</script>