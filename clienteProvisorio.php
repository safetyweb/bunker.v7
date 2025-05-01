<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$countFiltros = "";
$check_ativo = "";
$check_troca = "";
$check_funciona = "";
$check_mail = "";
$check_sms = "";
$check_telemark = "";
$msgRetorno = "";
$msgTipo = "";
$cod_univend_pref = "";
$cod_usuario = "";
$nom_usuario = "";
$log_usuario = "";
$des_emailus = "";
$log_estatus = "";
$log_trocaprod = "";
$num_rgpesso = "";
$dat_nascime = "";
$cod_estaciv = "";
$cod_sexopes = "";
$num_tentati = "";
$num_telefon = "";
$num_celular = "";
$num_comercial = "";
$cod_externo = "";
$num_cartao = "";
$num_cgcecpf = "";
$des_enderec = "";
$num_enderec = "";
$des_complem = "";
$des_bairroc = "";
$num_cepozof = "";
$nom_cidadec = "";
$cod_estadof = "";
$cod_tpcliente = "";
$count_filtros = "";
$des_apelido = "";
$cod_profiss = "";
$des_contato = "";
$log_email = "";
$log_sms = "";
$log_telemark = "";
$log_funciona = "";
$nom_pai = "";
$nom_mae = "";
$cod_chaveco = "";
$key_externo = "";
$tip_cliente = "";
$des_coment = "";
$nom_indicador = "";
$des_zona = "";
$des_auxfiltro = "";
$des_regadm = "";
$des_pref = "";
$des_subpref = "";
$des_igreja = "";
$des_local = "";
$cod_estado = "";
$cod_municipio = "";
$Arr_COD_PERFILS = "";
$Arr_COD_SISTEMAS = "";
$i = "";
$cod_perfils = "";
$Arr_COD_MULTEMP = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$sql1 = "";
$cod_clienteRetorno = "";
$cod_cliente = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$log_categoria = "";
$log_autocad = "";
$obgDAT_NASCIME = "";
$endObriga = "";
$sqlInd = "";
$qrUsu = "";
$master = "";
$mod = "";
$popUp = "";
$formBack = "";
$sql4 = "";
$qrBuscaBloqueio = "";
$tem_bloqueio = "";
$sql3 = "";
$cod_entidad = "";
$qrBuscaEntidade = "";
$nom_entidad = "";
$nom_faixacat = "";
$arrayQuery = [];
$qrListaEstCivil = "";
$qrListaSexo = "";
$qrListaProfi = "";
$qrListaTipoCli = "";
$arrayEstado = [];
$qrEstado = "";
$latitude = "";
$longitude = "";


$hashLocal = mt_rand();

//inicialização das variáveis
@$cod_multemp = "0";
@$countFiltros = "";
@$check_ativo = 'checked';
@$check_troca = 'checked';
@$check_funciona = '';
@$check_mail = 'checked';
@$check_sms = 'checked';
@$check_telemark = 'checked';

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode(@$_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		$cod_univend_pref = 0;

		$cod_usuario = fnLimpacampoZero(@$_REQUEST['COD_USUARIO']);
		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$nom_usuario = fnLimpacampo(@$_REQUEST['NOM_USUARIO']);
		$log_usuario = fnLimpacampo(@$_REQUEST['LOG_USUARIO']);
		$des_emailus = fnLimpacampo(@$_REQUEST['DES_EMAILUS']);
		if (empty(@$_REQUEST['LOG_ESTATUS'])) {
			$log_estatus = 'N';
		} else {
			$log_estatus = @$_REQUEST['LOG_ESTATUS'];
		}
		if (empty(@$_REQUEST['LOG_TROCAPROD'])) {
			$log_trocaprod = 'N';
		} else {
			$log_trocaprod = @$_REQUEST['LOG_TROCAPROD'];
		}
		$num_rgpesso = fnLimpacampo(@$_REQUEST['NUM_RGPESSO']);
		$dat_nascime = fnLimpacampo(@$_REQUEST['DAT_NASCIME']);
		$cod_estaciv = fnLimpaCampoZero(@$_REQUEST['COD_ESTACIV']);
		$cod_sexopes = fnLimpacampoZero(@$_REQUEST['COD_SEXOPES']);
		$num_tentati = fnLimpacampoZero(@$_REQUEST['NUM_TENTATI']);
		$num_telefon = fnLimpacampo(@$_REQUEST['NUM_TELEFON']);
		$num_celular = fnLimpacampo(@$_REQUEST['NUM_CELULAR']);
		$num_comercial = fnLimpacampo(@$_REQUEST['NUM_COMERCIAL']);
		$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
		$num_cartao = fnLimpacampoZero(@$_REQUEST['NUM_CARTAO']);
		$num_cgcecpf = fnLimpacampo(@$_REQUEST['NUM_CGCECPF']);
		if ($num_cartao == 0 || $num_cartao == "") {
			$num_cartao = fnLimpacampoZero(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
		}
		$des_enderec = fnLimpacampo(@$_REQUEST['DES_ENDEREC']);
		$num_enderec = fnLimpacampo(@$_REQUEST['NUM_ENDEREC']);
		$des_complem = fnLimpacampo(@$_REQUEST['DES_COMPLEM']);
		$des_bairroc = fnLimpacampo(@$_REQUEST['DES_BAIRROC']);
		$num_cepozof = fnLimpacampo(@$_REQUEST['NUM_CEPOZOF']);
		$nom_cidadec = fnLimpacampo(@$_REQUEST['NOM_CIDADEC']);
		$cod_estadof = fnLimpacampo(@$_REQUEST['COD_ESTADOF']);
		$cod_tpcliente = fnLimpacampoZero(@$_REQUEST['COD_TPCLIENTE']);
		$count_filtros = fnLimpacampo(@$_REQUEST['COUNT_FILTROS']);
		$des_apelido = fnLimpacampo(@$_REQUEST['DES_APELIDO']);
		$cod_profiss = fnLimpacampoZero(@$_REQUEST['COD_PROFISS']);
		$cod_univend = fnLimpacampoZero(@$_REQUEST['COD_UNIVEND']);
		$des_contato = fnLimpacampo(@$_REQUEST['DES_CONTATO']);
		if (empty(@$_REQUEST['LOG_EMAIL'])) {
			$log_email = 'N';
		} else {
			$log_email = @$_REQUEST['LOG_EMAIL'];
		}
		if (empty(@$_REQUEST['LOG_SMS'])) {
			$log_sms = 'N';
		} else {
			$log_sms = @$_REQUEST['LOG_SMS'];
		}
		if (empty(@$_REQUEST['LOG_TELEMARK'])) {
			$log_telemark = 'N';
		} else {
			$log_telemark = @$_REQUEST['LOG_TELEMARK'];
		}
		if (empty(@$_REQUEST['LOG_FUNCIONA'])) {
			$log_funciona = 'N';
		} else {
			$log_funciona = @$_REQUEST['LOG_FUNCIONA'];
		}
		$nom_pai = fnLimpacampo(@$_REQUEST['NOM_PAI']);
		$nom_mae = fnLimpacampo(@$_REQUEST['NOM_MAE']);
		$cod_chaveco = fnLimpacampo(@$_REQUEST['COD_CHAVECO']);
		$key_externo = fnLimpacampo(@$_REQUEST['KEY_EXTERNO']);
		$tip_cliente = fnLimpacampo(@$_REQUEST['TIP_CLIENTE']);
		$des_coment = fnLimpacampo(@$_REQUEST['DES_COMENT']);
		$nom_indicador = fnLimpacampo(@$_REQUEST['NOM_INDICADOR']);

		$des_zona = fnLimpacampo(@$_REQUEST['DES_ZONA']);
		$des_auxfiltro = fnLimpacampo(@$_REQUEST['DES_AUXFILTRO']);
		$des_regadm = fnLimpacampo(@$_REQUEST['DES_REGADM']);
		$des_pref = fnLimpacampo(@$_REQUEST['DES_PREF']);
		$des_subpref = fnLimpacampo(@$_REQUEST['DES_SUBPREF']);
		$des_igreja = fnLimpacampo(@$_REQUEST['DES_IGREJA']);
		$des_local = fnLimpacampo(@$_REQUEST['DES_LOCAL']);
		$cod_estado = fnLimpacampoZero(@$_REQUEST['COD_ESTADO']);
		$cod_municipio = fnLimpacampoZero(@$_REQUEST['COD_MUNICIPIO']);
		// fnEscreve($num_cartao);
		//array dos sistemas da empresas
		if (isset($_POST['COD_PERFILS'])) {
			$Arr_COD_PERFILS = @$_POST['COD_PERFILS'];
			//print_r($Arr_COD_SISTEMAS);			 

			for ($i = 0; $i < count($Arr_COD_PERFILS); $i++) {
				$cod_perfils = $cod_perfils . $Arr_COD_PERFILS[$i] . ",";
			}

			$cod_perfils = substr($cod_perfils, 0, -1);
		} else {
			$cod_perfils = "0";
		}


		//array das empresas multiacesso
		if (isset($_POST['COD_MULTEMP'])) {
			$Arr_COD_MULTEMP = @$_POST['COD_MULTEMP'];
			//print_r($Arr_COD_MULTEMP);			 

			for ($i = 0; $i < count($Arr_COD_MULTEMP); $i++) {
				$cod_multemp = $cod_multemp . $Arr_COD_MULTEMP[$i] . ",";
			}

			$cod_multemp = substr($cod_multemp, 0, -1);
		} else {
			$cod_multemp = "0";
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':


					$sql1 = "INSERT INTO CLIENTES_EXTERNO(
												COD_EMPRESA,
												NOM_CLIENTE,
												LOG_USUARIO,
												DES_EMAILUS,
												LOG_ESTATUS,
												LOG_TROCAPROD,
												NUM_RGPESSO,
												DAT_NASCIME,
												COD_ESTACIV,
												COD_SEXOPES,
												NUM_TENTATI,
												NUM_TELEFON,
												NUM_CELULAR,
												COD_EXTERNO,
												NUM_CARTAO,
												NUM_CGCECPF,
												DES_ENDEREC,
												NUM_ENDEREC,
												DES_COMPLEM,
												DES_BAIRROC,
												NUM_CEPOZOF,
												NOM_CIDADEC,
												COD_ESTADOF,
												COD_TPCLIENTE,
												DES_APELIDO,
												COD_PROFISS,
												COD_UNIVEND,
												DES_CONTATO,
												LOG_EMAIL,
												LOG_SMS,
												LOG_TELEMARK,
												LOG_FUNCIONA,
												NOM_PAI,
												NOM_MAE,
												KEY_EXTERNO,
												TIP_CLIENTE,
												DES_COMENT,
												COD_ESTADO,
												COD_MUNICIPIO,
												NOM_INDICADOR,
												COD_USUCADA
											) VALUES(
												'$cod_empresa',
												'$nom_usuario',
												'$log_usuario',
												'$des_emailus',
												'$log_estatus',
												'$log_trocaprod',
												'$num_rgpesso',
												'$dat_nascime',
												'$cod_estaciv',
												'$cod_sexopes',
												'$num_tentati',
												'$num_telefon',
												'$num_celular',
												'$cod_externo',
												'" . fnLimpaDoc($num_cartao) . "',
												'" . fnLimpaDoc($num_cgcecpf) . "',
												'$des_enderec',
												'$num_enderec',
												'$des_complem',
												'$des_bairroc',
												'$num_cepozof',
												'$nom_cidadec',
												'$cod_estadof',
												'$cod_tpcliente',
												'$des_apelido',
												'$cod_profiss',
												'$cod_univend',
												'$des_contato',
												'$log_email',
												'$log_sms',
												'$log_telemark',
												'$log_funciona',
												'$nom_pai',
												'$nom_mae',
												'$key_externo',
												'$tip_cliente',
												'$des_coment',
												'$cod_estado',
												'$cod_municipio',
												'$nom_indicador',
												'$cod_usucada'
											)";

					mysqli_query(connTemp($cod_empresa, ''), $sql1);

					// unset(@$_POST);

					$msgRetorno = "Registro criado com <strong>sucesso!</strong>";
					$msgTipo = 'alert-success';

					break;

				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					$msgTipo = 'alert-success';

					break;
			}
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {

	$cod_empresa = fnDecode(@$_GET['id']);
	if (empty($cod_clienteRetorno)) {
		//fnEscreve("if");
		if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idC'])))) {
			//fnEscreve("if1");
			$cod_cliente = fnDecode(@$_GET['idC']);
			//fnEscreve($cod_cliente);		
		} else {
			//fnEscreve("if2");
			$cod_cliente = 0;
		}
	} else {
		//fnEscreve("else");
		$cod_cliente = $cod_clienteRetorno;
	}

	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CHAVECO, LOG_CATEGORIA, LOG_AUTOCAD
			  FROM empresas WHERE COD_EMPRESA=$cod_empresa";

	//fnEscreve($sql);		
	$qrBuscaEmpresa = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), trim($sql)));
	$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	$cod_chaveco = $qrBuscaEmpresa['COD_CHAVECO'];
	$log_categoria = $qrBuscaEmpresa['LOG_CATEGORIA'];
	$log_autocad = $qrBuscaEmpresa['LOG_AUTOCAD'];
}


switch ($cod_chaveco) {
	case 6: //CPF/CNPJ/NASC/CEL/EMAIL
		$obgDAT_NASCIME = "";
		break;
	default:
		$obgDAT_NASCIME = "required";
}

if ($_SESSION['SYS_COD_USUARIO'] == 33103) {
	$endObriga = "";
} else {
	$endObriga = "required";
}

$sqlInd = "SELECT COD_PERFILS FROM USUARIOS WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), trim($sqlInd)));
// fnEscreve($cod_empresa);

if ($qrUsu['COD_PERFILS'] == 1154) {
	$master = "N";
} else {
	$master = "S";
}

// $mod = fnLimpaCampo(@$_GET['mod']);

// fnEscreve('PROV');

?>

<style>
	.alert .alert-link {
		text-decoration: none;
	}

	.alert:hover .alert-link:hover {
		text-decoration: underline;
	}

	.foto {
		margin-left: auto !important;
		margin-right: auto !important;
		border: 1px solid #dce4ec;
	}

	#btn-foto {
		width: 100% !important;
	}
</style>

<?php if ($popUp != "true") {  ?>
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

						<?php
						switch ($_SESSION["SYS_COD_SISTEMA"]) {
							case 16: //gerenciador social
								$formBack = "1424";
								break;
							default;
								$formBack = "1015";
								break;
						}
						include "atalhosPortlet.php";
						?>

					</div>
				<?php } ?>

				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php
					//verifica se tem bloqueio
					$sql4 = "SELECT COUNT(*) as TEM_BLOQUEIO
											FROM CLIENTES A, VENDAS B
											LEFT JOIN $connAdm->DB.unidadevenda d ON d.cod_univend = b.cod_univend 
											WHERE A.COD_CLIENTE=B.COD_CLIENTE AND 
											B.COD_STATUSCRED=3 AND 
                                            B.cod_avulso!=1 AND
											A.COD_EMPRESA = $cod_empresa and
											A.COD_CLIENTE = $cod_cliente ";

					//fnEscreve($sql4);
					$qrBuscaBloqueio = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql4));
					//fnEscreve($sql4);

					$tem_bloqueio = $qrBuscaBloqueio['TEM_BLOQUEIO'];

					if ($tem_bloqueio > 0) { ?>

						<div class="alert alert-warning alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							Apoiador possui vendas bloqueadas. <br />
							<a href="action.do?mod=<?php echo fnEncode(1099); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank" class="alert-link">&rsaquo; Acessar tela de desbloqueio</a>
						</div>
					<?php } ?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="action.php?mod=Msk5EmgRrD4¢&id=<?= fnEncode($cod_empresa) ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<!-- bloco dados básicos -->

								<div class="row">

									<?php
									if ($_SESSION["SYS_COD_SISTEMA"] == 14) {

										$sql3 = "select NOM_ENTIDAD from ENTIDADE where COD_ENTIDAD = $cod_entidad";
										$qrBuscaEntidade = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql3));
										//fnEscreve($sql3);	
										$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];
									?>

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Empresa Associada</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_ENTIDAD" id="NOM_ENTIDAD" value="<?php echo $nom_entidad; ?>" maxlength="50" data-error="Campo obrigatório">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="push10"></div>

									<?php
									}
									?>

									<div class="col-xs-2 hidden-print">
										<div class="form-group">
											<label for="inputName" class="control-label required hidden-print">Ativo</label><br />
											<label class="switch">
												<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" <?php echo $check_ativo; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>

									</div>

									<!--Apoiador é Funcionário / Permite Troca de Produtos -->
									<input type="hidden" name="LOG_FUNCIONA" id="LOG_FUNCIONA" value="N" />
									<input type="hidden" name="LOG_TROCAPROD" id="LOG_TROCAPROD" value="N" />

									<?php if ($log_categoria == "S") { ?>
										<div class="col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Categoria do Apoiador</label>
												<div class="push5"></div>
												<span class="label label-pill label-info f14"><i class="fa fa-bookmark"></i> &nbsp; <?php echo $nom_faixacat; ?></span>
											</div>
										</div>
									<?php } ?>

									<div class="push10"></div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_usuario; ?>">
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										</div>
									</div>

									<div class="col-xs-5">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome do Apoiador</label>
											<input type="text" name="NOM_USUARIO" id="NOM_USUARIO" value="<?php echo $nom_usuario; ?>" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Apelido</label>
											<input type="text" class="form-control input-sm" name="DES_APELIDO" id="DES_APELIDO" value="<?php echo $des_apelido; ?>" maxlength="18">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<input type="hidden" name="NUM_CARTAO" id="NUM_CARTAO" value="">

								</div>

								<div class="row">

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">CNPJ/CPF</label>
											<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo fnCompletaDoc($num_cgcecpf, 'F'); ?>" maxlength="18" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">RG</label>
											<input type="text" class="form-control input-sm" name="NUM_RGPESSO" id="NUM_RGPESSO" value="<?php echo $num_rgpesso; ?>" maxlength="15" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data de Nascimento</label>
											<input type="text" class="form-control input-sm data" name="DAT_NASCIME" value="<?php echo $dat_nascime; ?>" id="DAT_NASCIME" maxlength="10" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Estado Civil</label>
											<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect">
												<option value=""></option>
												<?php
												$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv; ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrListaEstCivil['COD_ESTACIV'] . "'>" . $qrListaEstCivil['DES_ESTACIV'] . "</option> 
																				";
												}
												?>
											</select>
											<script>
												$("#formulario #COD_ESTACIV").val("<?php echo $cod_estaciv; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Sexo</label>
											<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect requiredChk" required>
												<option value=""></option>
												<?php
												$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrListaSexo['COD_SEXOPES'] . "'>" . $qrListaSexo['DES_SEXOPES'] . "</option> 
																				";
												}
												?>
											</select>
											<script>
												$("#formulario #COD_SEXOPES").val("<?php echo $cod_sexopes; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Profissão </label>
											<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect">
												<option value=""></option>
												<?php
												$sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrListaProfi['COD_PROFISS'] . "'>" . $qrListaProfi['DES_PROFISS'] . "</option> 
																				";
												}
												?>
											</select>
											<script>
												$("#formulario #COD_PROFISS").val("<?php echo $cod_profiss; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-3" hidden="">
										<div class="form-group">
											<label for="inputName" class="control-label">Tipo do Cliente </label>
											<select data-placeholder="Selecione o tipo do cliente" name="COD_TPCLIENTE" id="COD_TPCLIENTE" class="chosen-select-deselect">
												<option value=""></option>
												<?php
												$sql = "select * from tipo_cliente where COD_EMPRESA = $cod_empresa order by DES_TIPOCLI ";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));

												while ($qrListaTipoCli = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrListaTipoCli['COD_TIPOCLI'] . "'>" . $qrListaTipoCli['DES_TIPOCLI'] . "</option> 
																				";
												}
												?>
											</select>
											<script>
												$("#formulario #COD_TPCLIENTE").val("<?php echo $cod_tpcliente; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome do Pai</label>
											<input type="text" class="form-control input-sm" name="NOM_PAI" id="NOM_PAI" value="<?php echo $nom_pai ?>" maxlength="60" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome da Mãe</label>
											<input type="text" class="form-control input-sm" name="NOM_MAE" id="NOM_MAE" value="<?php echo $nom_mae ?>" maxlength="60" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Colaborador que Indicou</label>
											<input type="text" class="form-control input-sm" name="NOM_INDICADOR" id="NOM_INDICADOR" maxlength="200">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<!-- fim bloco dados basicos -->
								</div>

							</fieldset>

							<div class="push10"></div>

							<fieldset>
								<legend>Comunicação</legend>

								<div class="row">

									<div class="col-xs-4">
										<div class="form-group">
											<label for="inputName" class="control-label">e-Mail</label>
											<input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" value="<?php echo $des_emailus; ?>" maxlength="100" value="" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Contato</label>
											<input type="text" class="form-control input-sm" name="DES_CONTATO" value="<?php echo $des_contato; ?>" id="DES_CONTATO" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Principal</label>
											<input type="text" class="form-control input-sm fone" name="NUM_TELEFON" value="<?php fnCorrigeTelefone($num_telefon); ?>" id="NUM_TELEFON" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Telefone Celular</label>
											<input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" value="<?php fnCorrigeTelefone($num_celular); ?>" id="NUM_CELULAR" maxlength="20" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Comercial</label>
											<input type="text" class="form-control input-sm sp_celphones" name="NUM_COMERCIAL" value="<?php fnCorrigeTelefone($num_comercial); ?>" id="NUM_COMERCIAL" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-xs-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />e-Mail</label><br />
											<label class="switch">
												<input type="checkbox" name="LOG_EMAIL" id="LOG_EMAIL" class="switch" value="S" <?php echo $check_mail; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />SMS</label><br />
											<label class="switch">
												<input type="checkbox" name="LOG_SMS" id="LOG_SMS" class="switch" value="S" <?php echo $check_sms; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />Telemarketing</label><br />
											<label class="switch">
												<input type="checkbox" name="LOG_TELEMARK" id="LOG_TELEMARK" class="switch" value="S" <?php echo $check_telemark; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>


								</div>

							</fieldset>

							<div class="push10"></div>

							<fieldset>
								<legend>Observação</legend>

								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<textarea class="form-control input-sm" rows="4" name="DES_COMENT" id="DES_COMENT"><?= $des_coment ?></textarea>
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

							</fieldset>

							<div class="push10"></div>

							<fieldset>
								<legend>Localização</legend>

								<div class="row">

									<div class="col-xs-1">

										<div class="push15"></div>
										<a href="javascript:void(0)" class="btn btn-info btn-block btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1444) ?>&id=<?php echo fnEncode($cod_empresa); ?>&pop=true" data-title="Busca CEP/Logradouro" data-toggle='tooltip' data-placement='top' data-original-title='Busca CEP/Logradouro'><i class="fal fa-map-marked-alt f16" aria-hidden="true"></i></a>

									</div>

									<div class="col-xs-4">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $endObriga ?>">Endereço</label>
											<input type="text" class="form-control input-sm" name="DES_ENDEREC" value="<?php echo $des_enderec; ?>" id="DES_ENDEREC" maxlength="40" <?= $endObriga ?>>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $endObriga ?>">Número</label>
											<input type="text" class="form-control input-sm" name="NUM_ENDEREC" value="<?php echo $num_enderec; ?>" id="NUM_ENDEREC" maxlength="10" <?= $endObriga ?>>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Complemento</label>
											<input type="text" class="form-control input-sm" name="DES_COMPLEM" value="<?php echo $des_complem; ?>" id="DES_COMPLEM" maxlength="100">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Bairro</label>
											<input type="text" class="form-control input-sm" name="DES_BAIRROC" value="<?php echo $des_bairroc; ?>" id="DES_BAIRROC" maxlength="60">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push10"></div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $endObriga ?>">CEP</label>
											<input type="text" class="form-control input-sm cep" name="NUM_CEPOZOF" value="<?php echo $num_cepozof; ?>" id="NUM_CEPOZOF" maxlength="9" <?= $endObriga ?>>
											<div class="help-block with-errors"></div>
										</div>
									</div>


									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $endObriga ?>">Estado</label>
											<select data-placeholder="Selecione um estado" name="COD_ESTADO" id="COD_ESTADO" class="chosen-select-deselect" <?= $endObriga ?>>
												<option value=""></option>
												<?php

												$sql = "SELECT COD_ESTADO, UF FROM ESTADO ORDER BY UF";
												$arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sql);
												while ($qrEstado = mysqli_fetch_assoc($arrayEstado)) {
												?>
													<option value="<?= $qrEstado['COD_ESTADO'] ?>"><?= $qrEstado['UF'] ?></option>
												<?php
												}

												?>
											</select>
											<script>
												$("#formulario #COD_ESTADO").val("<?php echo $cod_estado; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2" id="relatorioCidade">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $endObriga ?>">Cidade</label>
											<select data-placeholder="Selecione um estado" name="COD_MUNICIPIO" id="COD_MUNICIPIO" class="chosen-select-deselect" <?= $endObriga ?>>
												<option value=""></option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Latitude</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="LATITUDE" id="LATITUDE" value="<?= $latitude ?>">
										</div>
									</div>

									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Longitude</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="LONGITUDE" id="LONGITUDE" value="<?= $longitude ?>">
										</div>
									</div>


								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<?php
								//botoes normais 
								if ($popUp != "true") {
								?>

									<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
									<?php if ($cod_cliente == 0) { ?>
										<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
									<?php } else { ?>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
									<?php } ?>

								<?php } else { ?>

									<a href="javascript:window.print();" class="btn btn-info"><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Impressão de Cadastro </a>

								<?php } ?>

							</div>

							<input type="hidden" name="COD_INDICA" id="COD_INDICA" value="">
							<input type="hidden" name="DES_IGREJA" id="DES_IGREJA" value="">
							<input type="hidden" name="DES_LOCAL" id="DES_LOCAL" value="">
							<input type="hidden" name="DES_ZONA" id="DES_ZONA" value="">
							<input type="hidden" name="DES_REGADM" id="DES_REGADM" value="">
							<input type="hidden" name="DES_PREF" id="DES_PREF" value="">
							<input type="hidden" name="DES_SUBPREF" id="DES_SUBPREF" value="">

							<input type="hidden" name="COD_EXTERNO" id="COD_EXTERNO" value="">
							<input type="hidden" name="KEY_EXTERNO" id="KEY_EXTERNO" value="">
							<input type="hidden" name="LOG_USUARIO" id="LOG_USUARIO" value="">
							<input type="hidden" name="NUM_TENTATI" id="NUM_TENTATI" value="">
							<input type="hidden" name="USUCADA" id="USUCADA" value="">
							<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="">
							<input type="hidden" name="COD_MULTEMP" id="COD_MULTEMP" value="">

							<input type="hidden" name="COD_ESTADOF" id="COD_ESTADOF" value="<?= $cod_estadof ?>">
							<input type="hidden" name="NOM_CIDADEC" id="NOM_CIDADEC" value="<?= $nom_cidadec ?>">
							<input type="hidden" name="COD_MUNICIPIO_AUX" id="COD_MUNICIPIO_AUX" value="">
							<input type="hidden" name="LOG_FOTO" id="LOG_FOTO" value="N">
							<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
							<input type="hidden" name="REFRESH_FILTRO" id="REFRESH_FILTRO" value="N">
							<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
							<input type="hidden" name="COD_TPFILTRO" id="COD_TPFILTRO" value="">
							<input type="hidden" name="idS" id="idS" value="">

							<input type="hidden" name="COUNT_FILTROS" id="COUNT_FILTROS" value="<?= $countFiltros ?>">
							<input type="hidden" name="TIP_CLIENTE" id="TIP_CLIENTE" value="<?php echo $tip_cliente; ?>">
							<input type="hidden" name="COD_CHAVECO" id="COD_CHAVECO" value="<?php echo $cod_chaveco; ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div class="push"></div>

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

			carregaComboCidades('<?= $cod_estado ?>');

			$("#formulario #COD_ESTADOF").val($("#COD_ESTADO option:selected").text());

			$(".addBox").click(function() {
				if ($(this).attr("id") == "btn-foto") {
					$('#popModal').find('.modal-content').css({
						'width': '1000px',
						'height': '650px',
						'marginLeft': 'auto',
						'marginRight': 'auto'
					});
					$('#popModal').find('.modal-dialog').css({
						'maxWidth': '1080px'
					});
				} else if ($(this).attr("id") == "print") {
					$('#popModal').find('.modal-content').css({
						'width': '70vw',
						'height': 'auto',
						'marginLeft': 'auto',
						'marginRight': 'auto'
					});
					$('#popModal').find('.modal-dialog').css({
						'maxWidth': '100vw'
					});
				} else {
					$('#popModal').find('.modal-content').css({
						'width': 'auto',
						'height': 'auto'
					});
					$('#popModal').find('.modal-dialog').css({
						'maxWidth': '1080px'
					});
				}
			});

			var SPMaskBehavior = function(val) {
					return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
				},
				spOptions = {
					onKeyPress: function(val, e, field, options) {
						field.mask(SPMaskBehavior.apply({}, arguments), options);
					}
				};

			$('.sp_celphones').mask(SPMaskBehavior, spOptions);

			//mascaraCpfCnpj($("#formulario #NUM_CGCECPF"));
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//modal close
			$('.modal').on('hidden.bs.modal', function() {

				if ($('#REFRESH_CLIENTE').val() == "S") {
					var newCli = $('#NOVO_CLIENTE').val();
					// window.location.href = "action.php?mod=<?php echo @$_GET['mod']; ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC="+newCli+" ";
					$('#REFRESH_PRODUTOS').val("N");
				}

				if ($('#LOG_FOTO').val() == "S") {

					$.ajax({
						method: 'POST',
						url: 'ajxFotoApoiador.php?opcao=carregar',
						data: {
							COD_EMPRESA: <?= $cod_empresa ?>,
							COD_CLIENTE: <?= $cod_cliente ?>
						},
						beforeSend: function() {
							$('#div_perfil').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							$('#div_perfil').html(data);
						}
					});

				}

				if ($('#REFRESH_FILTRO').val() == "S") {

					$.ajax({
						method: 'POST',
						url: 'ajxTipoFiltro.php?idS=' + $('#idS').val(),
						data: {
							COD_EMPRESA: <?= $cod_empresa ?>,
							COD_TPFILTRO: $('#COD_TPFILTRO').val()
						},
						beforeSend: function() {
							$('#relatorioFiltro_' + $('#idS').val()).html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							// console.log(data);
							$('#relatorioFiltro_' + $('#idS').val()).html(data);
							$('#REFRESH_FILTRO').val("N");
						}
					});

				}

			});

			$("#COD_ESTADO").change(function() {
				cod_estado = $(this).val();
				carregaComboCidades(cod_estado);
				estado = $("#COD_ESTADO option:selected").text();
				$('#COD_ESTADOF').val(estado);
				$('#NOM_CIDADEC').val('');
			});

		});

		//retorno combo multiplo - master
		$("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");
		var sistemasMst = "<?php echo $cod_multemp; ?>";
		var sistemasMstArr = sistemasMst.split(',');
		//opções multiplas
		for (var i = 0; i < sistemasMstArr.length; i++) {
			$("#formulario #COD_MULTEMP option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");
		}
		$("#formulario #COD_MULTEMP").trigger("chosen:updated");

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

		function carregaComboCidades(cod_estado) {
			$.ajax({
				method: 'POST',
				url: 'ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>',
				data: {
					COD_ESTADO: cod_estado
				},
				beforeSend: function() {
					$('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#relatorioCidade").html(data);
					if ($("#formulario #COD_MUNICIPIO_AUX").val() != '') {
						$("#formulario #COD_MUNICIPIO").val($("#COD_MUNICIPIO_AUX").val()).trigger("chosen:updated");
					} else {
						$("#formulario #COD_MUNICIPIO").val("<?php echo $cod_municipio; ?>").trigger("chosen:updated");
					}
					$("#formulario #NOM_CIDADEC").val($("#COD_MUNICIPIO option:selected").text());
					// $('#formulario').validator('validate');
				}
			});
		}

		$(function() {
			$('html, body').animate({
				scrollTop: 0
			}, 500);
			$("#NOM_USUARIO").focus();
		});
	</script>