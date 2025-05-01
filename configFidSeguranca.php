<?php
//echo fnDebug('true');

$hashLocal = mt_rand();
$val_minresg = 0;
$val_maxresg_pct = 0;
$ro_min = "";
$ro_max = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_resgate = fnLimpaCampoZero($_REQUEST['COD_RESGATE']);
		$tip_momresg = fnLimpaCampo($_REQUEST['TIP_MOMRESG']);
		$num_diasrsg = fnLimpaCampoZero($_REQUEST['NUM_DIASRSG']);
		$qtd_validad = fnLimpaCampoZero($_REQUEST['QTD_VALIDAD']);
		$tip_diasvld = fnLimpaCampo($_REQUEST['TIP_DIASVLD']);
		$qtd_inativo = fnLimpaCampoZero($_REQUEST['QTD_INATIVO']);
		$num_inativo = fnLimpaCampo($_REQUEST['NUM_INATIVO']);
		$num_minresg = fnLimpaCampo($_REQUEST['NUM_MINRESG']);
		$pct_maxresg = fnLimpaCampo($_REQUEST['PCT_MAXRESG']);

		$qtd_fraudes = fnLimpaCampoZero(@$_REQUEST['QTD_FRAUDES']);
		$tip_fraudes = fnLimpaCampo(@$_REQUEST['TIP_FRAUDES']);
		$log_fraudecli = fnLimpaCampo(@$_REQUEST['LOG_FRAUDECLI']);

		$qtd_fraudes2 = fnLimpaCampoZero(@$_REQUEST['QTD_FRAUDES2']);
		$tip_fraudes2 = fnLimpaCampo(@$_REQUEST['TIP_FRAUDES2']);

		$tip_libfunc = fnLimpaCampo(@$_REQUEST['TIP_LIBFUNC']);
		$tip_libclie = fnLimpaCampo(@$_REQUEST['TIP_LIBCLIE']);
		$tip_relinfo = fnLimpaCampo(@$_REQUEST['TIP_RELINFO']);
		$hor_relinfo = fnLimpaCampo(@$_REQUEST['HOR_RELINFO']);
		$val_fraudfu = fnLimpaCampo(@$_REQUEST['VAL_FRAUDFU']);
		$tip_fraudfu = fnLimpaCampo(@$_REQUEST['TIP_FRAUDFU']);

		//$cod_mailusu = fnLimpaCampo($_REQUEST['COD_MAILUSU']);			
		//array das usuários email
		if (isset($_POST['COD_MAILUSU'])) {
			$Arr_COD_MAILUSU = $_POST['COD_MAILUSU'];
			//print_r($Arr_COD_MAILUSU);			 
			for ($i = 0; $i < count($Arr_COD_MAILUSU); $i++) {
				$cod_mailusu = $cod_mailusu . $Arr_COD_MAILUSU[$i] . ",";
			}
			$cod_mailusu = substr($cod_mailusu, 0, -1);
		} else {
			$cod_mailusu = "0";
		}

		//$cod_acesusu = fnLimpaCampo($_REQUEST['COD_ACESUSU']);
		//array das usuários de acesso
		if (isset($_POST['COD_ACESUSU'])) {
			$Arr_COD_ACESUSU = $_POST['COD_ACESUSU'];
			//print_r($Arr_COD_ACESUSU);			 
			for ($i = 0; $i < count($Arr_COD_ACESUSU); $i++) {
				$cod_acesusu = @$cod_acesusu . $Arr_COD_ACESUSU[$i] . ",";
			}
			$cod_acesusu = substr($cod_acesusu, 0, -1);
		} else {
			$cod_acesusu = "0";
		}

		$qtd_alertreg = fnLimpaCampoZero($_REQUEST['QTD_ALERTREG']);
		$tip_alertreg = fnLimpaCampo($_REQUEST['TIP_ALERTREG']);


		$cod_program = fnLimpaCampoZero($_REQUEST['COD_PROGRAM']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			$sql = "CALL SP_ALTERA_CAMPANHARESGATE (
				 '" . $cod_resgate . "', 
				 '" . $cod_program . "', 
				 '" . $cod_empresa . "', 
				 '" . $tip_momresg . "', 
				 '" . $num_diasrsg . "', 
				 '" . $qtd_validad . "', 
				 '" . $tip_diasvld . "', 
				 '" . $qtd_inativo . "', 
				 '" . $num_inativo . "', 
				 '" . fnValorSql($num_minresg) . "', 
				 '" . fnvalorSql($pct_maxresg) . "', 
				 '" . $qtd_fraudes . "', 
				 '" . $tip_fraudes . "', 
				 '" . $qtd_fraudes2 . "', 
				 '" . $tip_fraudes2 . "',
				 '" . $log_fraudecli . "',
				 '" . $tip_libfunc . "', 
				 '" . $tip_libclie . "', 
				 '" . $tip_relinfo . "', 
				 '" . $hor_relinfo . "', 
				 '" . $cod_mailusu . "', 
				 '" . $cod_acesusu . "', 
				 '" . $cod_usucada . "', 
				 '" . fnValorSql($val_fraudfu) . "', 
				 '" . $tip_fraudfu . "', 
				 '" . $qtd_alertreg . "', 
				 '" . $tip_alertreg . "', 
				 '" . $opcao . "'    
				) ";

			// fnTestesql(connTemp($cod_empresa,''),$sql);

			// fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa, ''), trim($sql));


			//controle de alertas por email
			if ($tip_relinfo != "" && $hor_relinfo != "" && $cod_mailusu != "") {

				$cod_campanha = fnDecode($_GET['idc']);

				$sql2 = "delete from alerta_email where cod_empresa = $cod_empresa and cod_campanha = $cod_campanha ";
				mysqli_query($connAdm->connAdm(), $sql2);

				$sql3 = "INSERT INTO alerta_email(
										COD_EMPRESA,
										COD_CAMPANHA,
										COD_TIPO
										) VALUES(
										$cod_empresa,
										$cod_campanha,
										1
										)";
				//fnEscreve($sql);

				mysqli_query($connAdm->connAdm(), $sql3);
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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

		//liberação das abas
		$abaPersona	= "S";
		$abaVantagem = "S";
		$abaRegras = "S";
		$abaComunica = "S";
		$abaAtivacao = "N";
		$abaResultado = "N";

		$abaPersonaComp = "active ";
		$abaCampanhaComp = "active";
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

if (isset($qrBuscaTpCampanha)) {
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
} else {

	$cod_persona = 0;
	$pct_vantagem = "";
	$qtd_vantagem = "";
	$qtd_vantagem = "";
	$nom_vantagem = "";
	$num_pessoas = 0;
	$cod_vantage = 0;
}

//busca dados do resgate 
$sql = "SELECT * FROM CAMPANHARESGATE where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrCampanhaResgate = mysqli_fetch_assoc($arrayQuery);

//echo "<pre>";
//print_r($arrayQuery);
//echo "</pre>";

if (isset($qrCampanhaResgate)) {

	$cod_resgate = $qrCampanhaResgate['COD_RESGATE'];
	$tip_momresg = $qrCampanhaResgate['TIP_MOMRESG'];
	if ($tip_momresg == 'I') {
		$checaTIP_MOMRESG_I = 'checked';
	} else {
		$checaTIP_MOMRESG_I = '';
	}
	if ($tip_momresg == 'D') {
		$checaTIP_MOMRESG_D = 'checked';
	} else {
		$checaTIP_MOMRESG_D = '';
	}

	$num_diasrsg = $qrCampanhaResgate['NUM_DIASRSG'];
	$qtd_validad = $qrCampanhaResgate['QTD_VALIDAD'];
	$tip_diasvld = $qrCampanhaResgate['TIP_DIASVLD'];
	$qtd_inativo = fnLimpaCampoZero($qrCampanhaResgate['QTD_INATIVO']);
	$num_inativo = $qrCampanhaResgate['NUM_INATIVO'];
	$num_minresg = $qrCampanhaResgate['NUM_MINRESG'];
	$pct_maxresg = $qrCampanhaResgate['PCT_MAXRESG'];

	$qtd_fraudes = $qrCampanhaResgate['QTD_FRAUDES'];
	$tip_fraudes = $qrCampanhaResgate['TIP_FRAUDES'];
	$log_fraudecli = $qrCampanhaResgate['LOG_FRAUDECLI'];
	if ($log_fraudecli == 'S') {
		$checalog_fraudecli = 'checked';
	} else {
		$checalog_fraudecli = '';
	}

	$qtd_fraudes2 = $qrCampanhaResgate['QTD_FRAUDES2'];
	$tip_fraudes2 = $qrCampanhaResgate['TIP_FRAUDES2'];

	$tip_libfunc = $qrCampanhaResgate['TIP_LIBFUNC'];
	if ($tip_libfunc == 'SEMSENHA') {
		$checaTIP_LIBFUNC_S = 'checked';
	} else {
		$checaTIP_LIBFUNC_S = '';
	}
	if ($tip_libfunc == 'CAIXA') {
		$checaTIP_LIBFUNC_C = 'checked';
	} else {
		$checaTIP_LIBFUNC_C = '';
	}
	if ($tip_libfunc == 'BALCONISTA') {
		$checaTIP_LIBFUNC_B = 'checked';
	} else {
		$checaTIP_LIBFUNC_B = '';
	}
	if ($tip_libfunc == 'GERENTE') {
		$checaTIP_LIBFUNC_G = 'checked';
	} else {
		$checaTIP_LIBFUNC_G = '';
	}
	if ($tip_libfunc == 'SUPERVISOR') {
		$checaTIP_LIBFUNC_SP = 'checked';
	} else {
		$checaTIP_LIBFUNC_SP = '';
	}
	$tip_libclie = $qrCampanhaResgate['TIP_LIBCLIE'];
	if ($tip_libclie == 'SEMSENHA') {
		$checaTIP_LIBCLIE_S = 'checked';
	} else {
		$checaTIP_LIBCLIE_S = '';
	}
	if ($tip_libclie == 'COMSENHA') {
		$checaTIP_LIBCLIE_C = 'checked';
	} else {
		$checaTIP_LIBCLIE_C = '';
	}
	if ($tip_libclie == 'SMS') {
		$checaTIP_LIBCLIE_SM = 'checked';
	} else {
		$checaTIP_LIBCLIE_SM = '';
	}
	$tip_relinfo = $qrCampanhaResgate['TIP_RELINFO'];
	$hor_relinfo = $qrCampanhaResgate['HOR_RELINFO'];
	$cod_mailusu = $qrCampanhaResgate['COD_MAILUSU'];
	$cod_acesusu = $qrCampanhaResgate['COD_ACESUSU'];
	$val_fraudfu = $qrCampanhaResgate['VAL_FRAUDFU'];
	$tip_fraudfu = $qrCampanhaResgate['TIP_FRAUDFU'];
} else {

	$cod_resgate = 0;
	$checaTIP_MOMRESG_I = "checked";
	$checaTIP_LIBFUNC_S = 'checked';
	$checaTIP_LIBCLIE_S = 'checked';
}

$qtd_alertreg = $qrCampanhaResgate['QTD_ALERTREG'];
$tip_alertreg = $qrCampanhaResgate['TIP_ALERTREG'];

$sqlPessoas = "SELECT COUNT(*) as PESSOAS FROM PERSONACLASSIFICA WHERE COD_PERSONA = $cod_persona AND COD_EMPRESA = $cod_empresa";

$arrayPessoas = mysqli_query(connTemp($cod_empresa, ''), $sqlPessoas);
if ($qrBuscaPessoas = mysqli_fetch_assoc($arrayPessoas)) {
	$num_pessoas = $qrBuscaPessoas['PESSOAS'];
} else {
	$num_pessoas = 0;
}
//fnMostraForm();	
//fnEscreve($num_minresg);

$sql2 = "SELECT CR.NUM_MINRESG ,CR.PCT_MAXRESG
			FROM campanha C
			INNER JOIN CAMPANHARESGATE CR ON CR.COD_CAMPANHA=C.COD_CAMPANHA
			WHERE LOG_ATIVO='S'
			AND C.cod_empresa=$cod_empresa 
			AND  c.LOG_REALTIME='S' AND
			c.COD_EXCLUSA=0 AND 
				((C.LOG_CONTINU='S'AND CONCAT(C.DAT_INI,' ', C.HOR_INI) <= NOW()) OR
				((C.LOG_CONTINU='N') AND
				(CONCAT(C.DAT_INI,' ', C.HOR_INI) <= NOW()) and
				(CONCAT(C.DAT_FIM,' ', C.HOR_FIM) > NOW()) 
					)) AND 
					c.dat_cadastr=(SELECT min(c.dat_cadastr)
								FROM campanha C
								INNER JOIN CAMPANHARESGATE CR ON CR.COD_CAMPANHA=C.COD_CAMPANHA
													WHERE LOG_ATIVO='S' 
															AND C.cod_empresa=$cod_empresa 
																AND  c.LOG_REALTIME='S' AND
														c.COD_EXCLUSA=0 AND 
														((C.LOG_CONTINU='S'AND CONCAT(C.DAT_INI,' ', C.HOR_INI) <= NOW()) OR
														((C.LOG_CONTINU='N') AND
														(CONCAT(C.DAT_INI,' ', C.HOR_INI) <= NOW()) and
														(CONCAT(C.DAT_FIM,' ', C.HOR_FIM) > NOW()) 
															)))";
//fnEscreve($sql);
$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);
$qrMinMax = mysqli_fetch_assoc($arrayQuery2);

if ($qrMinMax['NUM_MINRESG'] > 0 && $qrMinMax['PCT_MAXRESG'] > 0) {

	$val_minresg = $qrMinMax['NUM_MINRESG'];
	$val_maxresg_pct = $qrMinMax['PCT_MAXRESG'];

	$num_minresg = $val_minresg;
	$pct_maxresg = $val_maxresg_pct;
}

if (($qrMinMax['NUM_MINRESG'] > 0 && $qrMinMax['PCT_MAXRESG'] > 0) && ($_SESSION['SYS_COD_EMPRESA'] != 2 && $_SESSION['SYS_COD_EMPRESA'] != 3)) {
	$ro_min = "readonly";
	$ro_max = "readonly";
}


?>
<!-- Versão do fontawesome compatível com as checkbox (não remover) -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
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
					<div class="push10"></div>
				<?php } ?>

				<?php
				if ($_SESSION['SYS_COD_EMPRESA'] != 3 && $_SESSION['SYS_COD_EMPRESA'] != 2) {
				?>
					<div class="alert alert-warning top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Opções de preenchimento e alterações são <b>exclusivas do seu consultor</b>. <br />
						Entre em contato para mais informações.
					</div>
				<?php } ?>

				<?php $abaCampanhas = 1022;
				include "abasCampanhasConfig.php"; ?>

				<div class="push10"></div>

				<?php $abaCli = 1041;
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
										<span class="fas fa-users"></span>&nbsp; <?php echo number_format($num_pessoas, 0, ",", "."); ?>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<div class="row">
							<div class="col-md-6">

								<fieldset>
									<legend>Resgates</legend>

									<div class="row">

										<div class="col-md-12">
											<div class="form-group">
												<label for="inputName" class="control-label">Vantagem pode ser resgatada a partir de qual momento?</label>
												<div class="push5"></div>
												<div class="checkbox checkbox-info">
													<input type="radio" name="TIP_MOMRESG" id="TIP_MOMRESG_1" value="I" <?php echo $checaTIP_MOMRESG_I; ?>>
													<label for="TIP_MOMRESG_1">
														Imediatamente após a compra
													</label>
												</div>

												<div class="checkbox checkbox-info radio-inline" style="margin-left: 15px;">
													<input type="radio" name="TIP_MOMRESG" id="TIP_MOMRESG_2" value="D" <?php echo $checaTIP_MOMRESG_D; ?>>
													<label for="TIP_MOMRESG_2">
														A partir de
													</label>
												</div>
												<div class="radio-inline" style="padding-left: 0;">
													<select data-placeholder="..." name="NUM_DIASRSG" id="NUM_DIASRSG" style="width: 110px; text-align: center;" class="chosen-select-deselect">
														<option value="0"></option>
														<option value="1">1</option>
														<option value="2">2</option>
														<option value="3">3</option>
														<option value="5">5</option>
														<option value="7">7</option>
														<option value="15">15</option>
													</select>
													<script>
														$("#NUM_DIASRSG").val("<?php echo $num_diasrsg; ?>").trigger("chosen:updated");
													</script>
													<label for="inlineRadio2">&nbsp; dias </label>
												</div>

											</div>
										</div>

									</div>

									<div class="push10"></div>

									<div class="row">

										<div class="col-md-12">
											<div class="form-group">
												<label for="inputName" class="control-label">Validade da vantagem</label>
												<div class="push5"></div>

												<div class="col-md-2">
													<div class="form-group">
														<label for="inputName" class="control-label required">Qtd.</label>
														<input type="text" class="form-control text-center int input-sm" name="QTD_VALIDAD" id="QTD_VALIDAD" maxlength="3" value="<?php echo $qtd_validad; ?>" required>
													</div>
												</div>

												<div class="col-md-4">
													<label>&nbsp;</label>
													<div class="form-group">
														<label for="inputName" class="control-label required">&nbsp;</label>
														<select data-placeholder="..." name="TIP_DIASVLD" id="TIP_DIASVLD" style="width: 150px; text-align: center;" class="chosen-select-deselect" required>
															<option value="0"></option>
															<option value="DIA">dias</option>
															<option value="NEX">não expira</option>
														</select>
														<script>
															$("#TIP_DIASVLD").val("<?php echo $tip_diasvld; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>
											</div>
										</div>

									</div>

									<div class="push10"></div>

									<div class="row">

										<div class="col-md-12">
											<div class="form-group">
												<label for="inputName" class="control-label">Vantagem expira caso o cliente fique sem comprar por:</label>
												<div class="push5"></div>

												<div class="col-md-2">
													<div class="form-group">
														<label for="inputName" class="control-label required">Qtd.</label>
														<input type="text" class="form-control text-center int input-sm" name="QTD_INATIVO" id="QTD_INATIVO" maxlength="3" value="<?php echo $qtd_inativo; ?>" required>
													</div>
												</div>

												<div class="col-md-4">
													<label>&nbsp;</label>
													<div class="form-group">
														<label for="inputName" class="control-label required">&nbsp;</label>
														<select data-placeholder="informe o período" name="NUM_INATIVO" id="NUM_INATIVO" style="width: 220px; text-align: center;" class="chosen-select-deselect" required>
															<option value="0"></option>
															<option value="DIA">dias</option>
															<option value="NEX">não expira por inatividade</option>
														</select>
														<script>
															$("#NUM_INATIVO").val("<?php echo $num_inativo; ?>").trigger("chosen:updated");
														</script>
													</div>
												</div>
											</div>
										</div>

									</div>

									<div class="push10"></div>

									<div class="row">

										<div class="col-md-4" style="margin-left: 15px;">
											<div class="form-group">
												<label for="inputName" class="control-label required">Valor mínimo de resgate</label>
												<input type="text" class="form-control text-center input-sm money" name="NUM_MINRESG" id="NUM_MINRESG" value="<?php echo fnValor($num_minresg, 2); ?>" <?= $ro_min ?> required>
											</div>
										</div>

										<div class="col-md-6">
											<div class="push25"></div>
											<?php echo $nom_tpcampa; ?> (<?php echo $nom_vantagem; ?>)
										</div>
									</div>

									<div class="row">

										<div class="col-md-4" style="margin-left: 15px;">
											<div class="form-group">
												<label for="inputName" class="control-label required">Valor máx. de resgate</label>
												<input type="text" class="form-control text-center input-sm money" name="PCT_MAXRESG" id="PCT_MAXRESG" value="<?php echo fnValor($pct_maxresg, 2); ?>" <?= $ro_max ?> required>
											</div>
											<span class="help-block">Percentual</span>
										</div>

										<div class="col-md-4">
											<div class="push25"></div>
											%
										</div>
									</div>

									<div class="push20"></div>
									<div class="push5"></div>

								</fieldset>

							</div>

							<div class="col-md-6">

								<fieldset>
									<legend>Anti Fraude</legend>

									<div class="row">

										<div class="col-md-12" style="float: left; ">
											<div class="form-group">
												<label for="inputName" class="control-label">Quantidade máxima de vezes que poderá ser concedido a vantagem para o mesmo CPF</label>
												<div class="push5"></div>

												<div class="col-md-2" style="padding-right: 0;">
													<div class="form-group">
														<label for="inputName" class="control-label">Qtd.</label>
														<input type="text" class="form-control text-center int input-sm" name="QTD_FRAUDES" id="QTD_FRAUDES" maxlength="3" value="<?php echo $qtd_fraudes; ?>">
													</div>
												</div>

												<div class="col-md-7" style="padding-left: 0;">
													<div class="push15"></div>
													<div class="checkbox-info radio-inline">

														<label for="inlineRadio2"><b>vezes</b> por &nbsp; </label>
													</div>
													<div class="radio-inline" style="padding-left: 0; width: 120px;">
														<select data-placeholder="..." name="TIP_FRAUDES" id="TIP_FRAUDES" style="width: 150px; text-align: center;" class="chosen-select-deselect">
															<option value="0"></option>
															<option value="DIA">Dia</option>
															<option value="SEM">Semana</option>
															<option value="MES">Mês</option>
															<option value="ILM">Ilimitado</option>
														</select>
														<script>
															$("#TIP_FRAUDES").val("<?php echo $tip_fraudes; ?>").trigger("chosen:updated");
														</script>
													</div>

												</div>

											</div>
										</div>

										<div class="push10"></div>

										<div class="col-md-12" style="float: left; ">
											<div class="form-group">
												<div class="col-md-2" style="padding-right: 0;">
													<div class="form-group">
														<label for="inputName" class="control-label">Qtd.</label>
														<input type="text" class="form-control text-center int input-sm" name="QTD_FRAUDES2" id="QTD_FRAUDES2" maxlength="3" value="<?php echo $qtd_fraudes2; ?>">
													</div>
												</div>

												<div class="col-md-7" style="padding-left: 0;">
													<div class="push15"></div>
													<div class="checkbox-info radio-inline">

														<label for="inlineRadio2"><b>vezes</b> por &nbsp; </label>
													</div>
													<div class="radio-inline" style="padding-left: 0;  width: 120px;">
														<select data-placeholder="..." name="TIP_FRAUDES2" id="TIP_FRAUDES2" style="width: 150px; text-align: center;" class="chosen-select-deselect">
															<option value="0"></option>
															<option value="DIA">Dia</option>
															<option value="SEM">Semana</option>
															<option value="MES">Mês</option>
															<option value="ILM">Ilimitado</option>
														</select>
														<script>
															$("#TIP_FRAUDES2").val("<?php echo $tip_fraudes2; ?>").trigger("chosen:updated");
														</script>
													</div>

												</div>

											</div>
										</div>

										<div class="push10"></div>

										<div class="col-md-12" style="float: left; ">
											<div class="form-group col-md-12">
												<label for="inputName" class="control-label">Bloquear cliente após anti fraude</label>
												<div class="push5"></div>
												<label class="switch switch-small">
													<input type="checkbox" name="LOG_FRAUDECLI" id="LOG_FRAUDECLI" class="switch switch-small" value="S" <?php echo $checalog_fraudecli; ?>>
													<span></span>
												</label>
											</div>
										</div>

										<div class="push20"></div>

										<div class="col-md-12" style="float: left; ">
											<div class="form-group">
												<label for="inputName" class="control-label">Para <b>funcionários</b> considerar apenas compras <b>acima</b> de:</label>
												<div class="push5"></div>

												<div class="col-md-3" style="padding-right: 0;">
													<div class="form-group">
														<label for="inputName" class="control-label">Valor da Compra</label>
														<input type="text" class="form-control text-center money input-sm" name="VAL_FRAUDFU" id="VAL_FRAUDFU" maxlength="7" value="<?php echo fnVlVazio($val_fraudfu); ?>">
													</div>
													<div class="help-block with-errors">em reais (R$)</div>
												</div>

												<div class="col-md-7" style="padding-left: 0;">
													<div class="push15"></div>
													<div class="checkbox-info radio-inline">
														<label for="inlineRadio3"><b>por</b> &nbsp; </label>
													</div>
													<div class="radio-inline" style="padding-left: 0;">
														<select data-placeholder="..." name="TIP_FRAUDFU" id="TIP_FRAUDFU" style="width: 150px; text-align: center;" class="chosen-select-deselect">
															<option value="0"></option>
															<option value="DIA">Dia</option>
															<option value="SEM">Semana</option>
															<option value="MES">Mês</option>
														</select>
														<script>
															$("#TIP_FRAUDFU").val("<?php echo $tip_fraudfu; ?>").trigger("chosen:updated");
														</script>
													</div>

												</div>

											</div>

										</div>

									</div>

									<div class="push20"></div>

									<div class="row">

										<div class="col-md-12">
											<div class="disabledBlock"></div>
											<div class="form-group">
												<label for="inputName" class="control-label">Senha para liberação de resgate</label>
												<div class="push5"></div>

												<div class="col-md-6">
													<label><b>Funcionários</b></label>
													<div class="checkbox checkbox-info">
														<input type="radio" name="TIP_LIBFUNC" id="TIP_LIBFUNC_1" value="SEMSENHA" <?php echo $checaTIP_LIBFUNC_S; ?>>
														<label for="TIP_LIBFUNC_1">
															Não solicitar senha
														</label>
													</div>

													<div class="checkbox checkbox-info">
														<input type="radio" name="TIP_LIBFUNC" id="TIP_LIBFUNC_2" value="CAIXA" <?php echo $checaTIP_LIBFUNC_C; ?>>
														<label for="TIP_LIBFUNC_2">
															Caixa
														</label>
													</div>

													<div class="checkbox checkbox-info">
														<input type="radio" name="TIP_LIBFUNC" id="TIP_LIBFUNC_3" value="BALCONISTA" <?php echo $checaTIP_LIBFUNC_B; ?>>
														<label for="TIP_LIBFUNC_3">
															Balconista
														</label>
													</div>

													<div class="checkbox checkbox-info">
														<input type="radio" name="TIP_LIBFUNC" id="TIP_LIBFUNC_4" value="GERENTE" <?php echo $checaTIP_LIBFUNC_G; ?>>
														<label for="TIP_LIBFUNC_4">
															Gerente
														</label>
													</div>

													<div class="checkbox checkbox-info">
														<input type="radio" name="TIP_LIBFUNC" id="TIP_LIBFUNC_5" value="SUPERVISOR" <?php echo $checaTIP_LIBFUNC_SP; ?>>
														<label for="TIP_LIBFUNC_5">
															Supervisor
														</label>
													</div>

												</div>

												<div class="col-md-6">
													<div class="disabledBlock"></div>
													<label><b>Clientes</b></label>
													<div class="checkbox checkbox-info">
														<input type="radio" name="TIP_LIBCLIE" id="TIP_LIBCLIE_1" value="SEMSENHA" <?php echo $checaTIP_LIBCLIE_S; ?>>
														<label for="TIP_LIBCLIE_1">
															Não solicitar senha
														</label>
													</div>

													<div class="checkbox checkbox-info">
														<input type="radio" name="TIP_LIBCLIE" id="TIP_LIBCLIE_2" value="COMSENHA" <?php echo $checaTIP_LIBCLIE_C; ?>>
														<label for="TIP_LIBCLIE_2">
															Cliente digitar senha
														</label>
													</div>

													<div class="checkbox checkbox-info">
														<input type="radio" name="TIP_LIBCLIE" id="TIP_LIBCLIE_3" value="SMS" <?php echo $checaTIP_LIBCLIE_SM; ?>>
														<label for="TIP_LIBCLIE_3">
															Envio de senha por SMS
														</label>
													</div>

												</div>


											</div>
										</div>

									</div>

									<div class="push15"></div>

								</fieldset>

							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-12">

								<fieldset>
									<legend>Auditoria</legend>

									<div class="col-md-4">

										<div class="form-group">
											<label for="inputName" class="control-label">Enviar relatório de não conformidade</label>
											<div class="push5"></div>
											<label for="TIP_RELINFO_1">
												Por
											</label>
											<div class="radio-inline" style="padding-left: 0;">
												<select data-placeholder="..." name="TIP_RELINFO" id="TIP_RELINFO" style="width: 150px; text-align: center;" class="chosen-select-deselect">
													<option value=""></option>
													<!--<option value="HOR">Hora</option>-->
													<option value="DIA">Diário</option>
													<option value="SEM">Semanal (sextas)</option>
													<option value="MES">Mensal (último dia)</option>
												</select>
												<script>
													$("#TIP_RELINFO").val("<?php echo $tip_relinfo; ?>").trigger("chosen:updated");
												</script>
												<label for="inlineRadio2">&nbsp; às </label>
											</div>
											<div class="radio-inline" style="padding-left: 0;">
												<select data-placeholder="..." name="HOR_RELINFO" id="HOR_RELINFO" style="width: 150px; text-align: center;" class="chosen-select-deselect">
													<option value="0"></option>
													<option value="8">8h</option>
													<!--<option value="10">10</option> 
																			<option value="12">12</option> 
																			<option value="15">15</option>-->
													<!--<option value="18">18h</option>-->
													<option value="20">20</option>
													<!--<option value="22">22h</option>-->
												</select>
												<script>
													$("#HOR_RELINFO").val("<?php echo $hor_relinfo; ?>").trigger("chosen:updated");
												</script>
												<label for="inlineRadio2">&nbsp; horas </label>
											</div>

											<div class="push10"></div>

										</div>

									</div>


									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Gerar <b>Alerta</b> de <b>Resgates</b> para:</label>
											<div class="push5"></div>

											<div class="col-md-3" style="padding-right: 0;">
												<div class="form-group">
													<input type="text" class="form-control text-center int input-sm" name="QTD_ALERTREG" id="QTD_ALERTREG" maxlength="4" value="<?php echo fnVlVazio($qtd_alertreg); ?>">
												</div>
												<div class="help-block with-errors">qtd. resgates</div>
											</div>

											<div class="col-md-3 text-center">
												<div class="push5"></div>
												<b class="f14">resgates por</b>
											</div>

											<div class="col-md-6" style="padding-left: 0;">
												<div class="radio-inline" style="padding-left: 0;">
													<select data-placeholder="..." name="TIP_ALERTREG" id="TIP_ALERTREG" style="width: 150px; text-align: center;" class="chosen-select-deselect">
														<option value="0"></option>
														<option value="DIA">Dia</option>
														<option value="SEM">Semana</option>
														<option value="MES">Mês</option>
													</select>
													<div class="help-block with-errors">por usuário</div>
													<script>
														$("#TIP_ALERTREG").val("<?php echo $tip_alertreg; ?>").trigger("chosen:updated");
													</script>
												</div>

											</div>

										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Enviar relatório de não conformidade para:</label>

											<select data-placeholder="Selecione o usuário de destino" name="COD_MAILUSU[]" id="COD_MAILUSU" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
												<?php
												//se sistema marka
												$sql = " select * from usuarios where COD_EMPRESA = '" . $cod_empresa . "' and DAT_EXCLUSA is null order by nom_usuario  ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery)) {

													if ($qrListaUsuario['DES_EMAILUS'] == "") {
														$disabled = "disabled";
													} else {
														$disabled = "";
													}

													echo "
																				  <option value='" . $qrListaUsuario['COD_USUARIO'] . "'" . $disabled . ">" . ucfirst($qrListaUsuario['NOM_USUARIO']) . "</option> 
																				";
												}
												?>
											</select>

											<script>
												//retorno combo multiplo
												var comboValor = "<?php echo $cod_mailusu; ?>";
												var comboArr = comboValor.split(',');
												//opções multiplas
												for (var i = 0; i < comboArr.length; i++) {
													$("#COD_MAILUSU option[value=" + comboArr[i] + "]").prop("selected", "true");
												}
												$("#COD_MAILUSU").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<!--
														<div class="col-md-4">
															<div class="disabledBlock"></div>
															<div class="form-group">
																<label for="inputName" class="control-label">Usuário de liberação ou bloqueio da não conformidade:</label>
																
																	<select data-placeholder="Selecione o usuário de destino" name="COD_ACESUSU[]" id="COD_ACESUSU" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
																		<?php
																		//se sistema marka
																		$sql = " select * from usuarios where COD_EMPRESA = '" . $cod_empresa . "' and DAT_EXCLUSA is null order by nom_usuario  ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
																		while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery)) {
																			echo "
																				  <option value='" . $qrListaUsuario['COD_USUARIO'] . "'>" . ucfirst($qrListaUsuario['NOM_USUARIO']) . "</option> 
																				";
																		}
																		?>								
																	</select>
																	<script>
																		//retorno combo multiplo
																		var comboValor = "<?php echo $cod_acesusu; ?>";
																		var comboArr = comboValor.split(',');
																		//opções multiplas
																		for (var i = 0; i < comboArr.length; i++) {
																		  $("#COD_ACESUSU option[value=" + comboArr[i] + "]").prop("selected", "true");				  
																		}
																		$("#COD_ACESUSU").trigger("chosen:updated");    
																	</script>	
																	<div class="help-block with-errors"></div>
															</div>
														</div>													
														-->
									<input type="hidden" name="COD_ACESUSU[]" id="COD_ACESUSU" value="0">

								</fieldset>


							</div>

						</div>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="far fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>

							<?php
							//echo($_SESSION['SYS_COD_EMPRESA']);
							//liberado para a duque #4403
							if ($_SESSION['SYS_COD_EMPRESA'] != 3 && $_SESSION['SYS_COD_EMPRESA'] != 2 && $cod_empresa != 19) {
							?>

								<?php if ($cod_resgate == "0") { ?>
									<button type="button" name="CAD2" id="CAD2" class="btn btn-primary getBtn disabled" disabled><i class="far fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar (consulte seu consultor)</button>
								<?php } else { ?>
									<button type="button" name="ALT2" id="ALT2" class="btn btn-primary getBtn disabled" disabled><i class="far fa-repeat" aria-hidden="true"></i>&nbsp; Alterar (consulte seu consultor)</button>
								<?php } ?>

							<?php } else { ?>

								<?php if ($cod_resgate == "0") { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="far fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="far fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php } ?>

							<?php } ?>


						</div>

						<input type="hidden" name="COD_RESGATE" id="COD_RESGATE" value="<?php echo $cod_resgate ?>">
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

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>