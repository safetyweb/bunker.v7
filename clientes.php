<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$cod_multemp = "";
$countFiltros = "";
$msgRetorno = "";
$msgTipo = "";
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
$Arr_COD_PERFILS = "";
$Arr_COD_SISTEMAS = "";
$i = 0;
$cod_perfils = "";
$Arr_COD_MULTEMP = "";
$des_apelido = "";
$cod_profiss = "";
$cod_univend_pref = "";
$des_contato = "";
$log_email = "";
$log_sms = "";
$c = "";
$log_telemark = "";
$log_whatsapp = "";
$log_push = "";
$log_fidelizado = "";
$log_ofertas = "";
$log_funciona = "";
$log_master = "";
$nom_pai = "";
$nom_mae = "";
$cod_chaveco = "";
$key_externo = "";
$tip_cliente = "";
$des_coment = "";
$nom_usuarioSESSION = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$sql1 = "";
$semCPF = "";
$execCliente = "";
$qrGravaCliente = "";
$cod_clienteRetorno = "";
$mensagem = "";
$newDate = "";
$dia = "";
$mes = "";
$ano = "";
$hoje = "";
$idade = "";
$sqlDt = "";
$cod_filtro = "";
$cod_tpfiltro = "";
$sqlFiltro = "";
$arrayFiltro = [];
$cod_erro = "";
$cod_cliente = "";
$veri_cliente = "";
$dados_cli = "";
$conadmmysql = "";
$contemporaria = "";
$tesr = "";
$sql2 = "";
$arrayUpdate = [];
$sqlExc = "";
$arrayExc = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$log_categoria = "";
$log_autocad = "";
$qrBuscaCliente = "";
$dat_cadastr = "";
$check_ativo = "";
$check_troca = "";
$check_master = "";
$des_senhaus = "";
$check_funciona = "";
$check_mail = "";
$check_sms = "";
$check_telemark = "";
$check_whatsapp = "";
$check_push = "";
$check_fidelizado = "";
$check_ofertas = "";
$cod_entidad = "";
$cod_categoria = "";
$nom_faixacat = "";
$cod_indicad = "";
$dat_indicad = "";
$des_token = "";
$arrayQuery = [];
$qrIndicad = "";
$nom_indicad = "";
$rqrDAT_NASCIME = "";
$sqlObriga = "";
$arrayObriga = [];
$reqDES_APELIDO = "";
$reqNUM_CGCECPF = "";
$reqNUM_RGPESSO = "";
$reqCOD_ESTACIV = "";
$reqCOD_SEXOPES = "";
$reqCOD_TPCLIENTE = "";
$reqCOD_PROFISS = "";
$reqNOM_PAI = "";
$reqNOM_MAE = "";
$reqDES_EMAILUS = "";
$reqDES_CONTATO = "";
$reqNUM_TELEFON = "";
$reqNUM_CELULAR = "";
$reqNUM_COMERCIAL = "";
$reqDES_COMENT = "";
$reqDES_ENDEREC = "";
$reqNUM_ENDEREC = "";
$reqDES_COMPLEM = "";
$reqDES_BAIRROC = "";
$reqNUM_CEPOZOF = "";
$reqNOM_CIDADEC = "";
$reqCOD_ESTADOF = "";
$qrObriga = "";
$rqrDES_APELIDO = "";
$rqrNUM_RGPESSO = "";
$rqrCOD_ESTACIV = "";
$rqrCOD_SEXOPES = "";
$rqrCOD_TPCLIENTE = "";
$rqrCOD_PROFISS = "";
$rqrNOM_PAI = "";
$rqrNOM_MAE = "";
$rqrDES_EMAILUS = "";
$rqrDES_CONTATO = "";
$rqrNUM_TELEFON = "";
$rqrNUM_CELULAR = "";
$rqrNUM_COMERCIAL = "";
$rqrDES_COMENT = "";
$rqrDES_ENDEREC = "";
$rqrNUM_ENDEREC = "";
$rqrDES_COMPLEM = "";
$rqrDES_BAIRROC = "";
$rqrNUM_CEPOZOF = "";
$rqrNOM_CIDADEC = "";
$rqrCOD_ESTADOF = "";
$sqlControle = "";
$arrayControle = [];
$qrControle = "";
$log_separa = "";
$log_lgpd = "";
$sqlCanal = "";
$arrayCanal = [];
$qrCanal = "";
$canal = "";
$dat_cadastr_canal = "";
$tipoAtiv = "";
$arrayParamAutorizacao = [];
$bloquSenha = "";
$formBack = "";
$sql4 = "";
$qrBuscaBloqueio = "";
$tem_bloqueio = "";
$abaEmpresa = "";
$abaCli = "";
$sql3 = "";
$qrBuscaEntidade = "";
$nom_entidad = "";
$cartaoObg = "";
$qrListaEstCivil = "";
$qrListaSexo = "";
$qrListaTipoCli = "";
$qrListaProfi = "";
$sqlIndica = "";
$queryIndica = "";
$qrIndica = "";
$qtd_indica = 0;
$desabi = "";
$qrListaTagPersonaOff = "";
$qrListaTagCampanha = "";
$qrListaTagPersonaOn = "";
$tipo = "";
$qrBuscaFAQ = "";
$obrigaChk = "";
$sqlChk = "";
$arrayChk = [];
$chkTermo = "";
$sqlTermos = "";
$arrayTermos = [];
$des_bloco = "";
$qrTermos = "";
$qrTipo = "";
$arrayFiltros = [];
$qrFiltros = "";
$sqlChosen = "";
$arrayChosen = [];
$qrChosen = "";
$qrVeic = "";
$qrListaPrecos = "";
$qrUsu = "";
$qrListaUnidade = "";
$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

//inicialização das variáveis
@$cod_multemp = "0";
@$countFiltros = "";

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
}

if (isset($_GET['ido'])) {
	//busca dados da empresa
	$msgRetorno = "Registro criado com <strong>sucesso!</strong>";
	$msgTipo = 'alert-success';
}

if (isset($_GET['idx'])) {
	//busca dados da empresa
	$msgRetorno = "Registro excluído com <strong>sucesso!</strong>";
	$msgTipo = 'alert-success';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_usuario = fnLimpacampoZero(@$_REQUEST['COD_USUARIO']);
		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$nom_usuario = fnLimpacampo(@$_REQUEST['NOM_USUARIO']);
		$log_usuario = fnLimpacampo(@$_REQUEST['LOG_USUARIO']);
		$des_emailus = fnLimpacampoemail(@$_REQUEST['DES_EMAILUS']);
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


		//fnEscreve($cod_perfils);

		$des_apelido = fnLimpacampo(@$_REQUEST['DES_APELIDO']);
		$cod_profiss = fnLimpacampoZero(@$_REQUEST['COD_PROFISS']);
		$cod_univend = fnLimpacampoZero(@$_REQUEST['COD_UNIVEND']);
		$cod_univend_pref = fnLimpacampoZero(@$_REQUEST['COD_UNIVEND_PREF']);
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
			$c = 'N';
		} else {
			$log_telemark = @$_REQUEST['LOG_TELEMARK'];
		}
		if (empty(@$_REQUEST['LOG_WHATSAPP'])) {
			$log_whatsapp = 'N';
		} else {
			$log_whatsapp = @$_REQUEST['LOG_WHATSAPP'];
		}
		if (empty(@$_REQUEST['LOG_PUSH'])) {
			$log_push = 'N';
		} else {
			$log_push = @$_REQUEST['LOG_PUSH'];
		}
		if (empty(@$_REQUEST['LOG_FIDELIZADO'])) {
			$log_fidelizado = 'N';
		} else {
			$log_fidelizado = @$_REQUEST['LOG_FIDELIZADO'];
		}
		if (empty(@$_REQUEST['LOG_OFERTAS'])) {
			$log_ofertas = 'N';
		} else {
			$log_ofertas = @$_REQUEST['LOG_OFERTAS'];
		}
		if (empty(@$_REQUEST['LOG_FUNCIONA'])) {
			$log_funciona = 'N';
		} else {
			$log_funciona = @$_REQUEST['LOG_FUNCIONA'];
		}
		if (empty(@$_REQUEST['LOG_MASTER'])) {
			$log_master = 'N';
		} else {
			$log_master = @$_REQUEST['LOG_MASTER'];
		}
		$nom_pai = fnLimpacampo(@$_REQUEST['NOM_PAI']);
		$nom_mae = fnLimpacampo(@$_REQUEST['NOM_MAE']);
		$cod_chaveco = fnLimpacampo(@$_REQUEST['COD_CHAVECO']);
		$key_externo = fnLimpacampo(@$_REQUEST['KEY_EXTERNO']);
		$tip_cliente = fnLimpacampo(@$_REQUEST['TIP_CLIENTE']);
		$des_coment = fnLimpacampo(@$_REQUEST['DES_COMENT']);
		// fnEscreve($log_email);
		// fnEscreve($log_sms);

		$nom_usuarioSESSION = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if (strlen(fnLimpaDoc($num_cgcecpf)) == '11') {
			$tip_cliente = "F";
		} else {
			$tip_cliente = "J";
		}

		$cod_usucada = 1;
		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {

				case 'CAD':

					//verifica 
					switch ($cod_chaveco) {

						case 1: //cpf
							$num_cartao = fnLimpaDoc($num_cgcecpf);
							break;
						case 2: //cartao pre cadastrado
							//$num_cartao = "active";
							$num_cartao = $num_cartao;
							break;
						case 3: //celular
							$num_cartao =  fnLimpaDoc($num_celular);
							break;
						case 4: //código externo
							$num_cartao = $num_cartao;
							break;
						case 5: //cartao + cpf
							$num_cartao = $num_cartao;
							break;
						case 6: //CPF/CNPJ/NASC/CEL/EMAIL
							$num_cartao = "0";
							break;
					}

					//RICARDO APOS AQUI - VAI TER TODAS AS CRÍTICIAS SE FOR TIPO COM CARTAO  
					//$cod_chaveco = 2 ou 5
					//VERIFICAR SE O CLIENTE JA EXISTE NA BASE DE DADOS


					$sql1 = "CALL SP_ALTERA_CLIENTES(
						'" . $cod_usuario . "',
						'" . $cod_empresa . "',
						'" . $nom_usuario . "',
						'" . $log_usuario . "',
						'" . $des_emailus . "',
						'" . $_SESSION["SYS_COD_USUARIO"] . "',    
						'" . fnLimpaDoc($num_cgcecpf) . "',
						'" . $log_estatus . "',
						'" . $log_trocaprod . "',
						'" . $num_rgpesso . "',
						'" . $dat_nascime . "',
						'" . $cod_estaciv . "',
						'" . $cod_sexopes . "',
						'" . fnLimpaDoc($num_telefon) . "',
						'" . fnLimpaDoc($num_celular) . "',
						'" . fnLimpaDoc($num_comercial) . "',
						'" . $cod_externo . "',
						'" . fnLimpaDoc($num_cartao) . "',
						'" . $num_tentati . "',
						'" . $des_enderec . "',
						'" . $num_enderec . "',
						'" . $des_complem . "',
						'" . $des_bairroc . "',
						'" . $num_cepozof . "',
						'" . $nom_cidadec . "',
						'" . $cod_estadof . "',
						'" . $des_apelido . "',
						'" . $cod_profiss . "',
						" . $cod_univend . ",
						" . $cod_univend_pref . ",
						'" . $tip_cliente . "',
						'" . $des_contato . "',
						'" . $log_email . "',
						'" . $log_sms . "',
						'" . $log_telemark . "',
						'" . $log_whatsapp . "',
						'" . $log_push . "',
						'" . $log_fidelizado . "',
						'" . $nom_pai . "',
						'" . $nom_mae . "',
						'" . $cod_chaveco . "',
						'" . $cod_multemp . "',
						'" . $key_externo . "',
						'" . $cod_tpcliente . "',
						'" . $log_funciona . "',
						'" . $log_master . "',
						'" . $log_ofertas . "',
						'" . $des_coment . "',
						'" . $opcao . "'   
					);";

					// fnEscreve($sql1);
					// exit();

					if ($cod_chaveco == 6) {
						$semCPF	= "S";
					} else {
						$semCPF	= "N";
					}

					//if($num_cgcecpf != "" && $num_cgcecpf != 0){
					if (($num_cgcecpf != 0 && $num_cgcecpf != "") || ($num_cgcecpf != 0 && $semCPF == "N")) {

						$execCliente = mysqli_query($conn, $sql1);
						$qrGravaCliente = mysqli_fetch_assoc($execCliente);
						$cod_clienteRetorno = $qrGravaCliente['COD_CLIENTE'];
						$mensagem = $qrGravaCliente['MENSAGEM'];
						$msgTipo = 'alert-success';

						$newDate = explode('/', $dat_nascime);
						$dia = $newDate['0'];
						$mes   = $newDate['1'];
						$ano  = $newDate['2'];

						$hoje = explode("/", date("d/m/Y"));
						$idade = $hoje['2'] - $newDate['2'];

						if ($newDate['1'] > $hoje['1']) {

							$idade = $idade - 1;
						} else if ($newDate['1'] == $hoje['1']) {

							if ($newDate['2'] <= $hoje['2']) {

								$idade = $idade;
							} else {

								$idade = $idade - 1;
							}
						} else {

							$idade = $idade;
						}

						$sqlDt = "UPDATE CLIENTES SET
						DIA = $dia,
						MES = $mes,
						ANO = $ano,
						IDADE = $idade
						WHERE COD_CLIENTE = $cod_clienteRetorno
						AND COD_EMPRESA = $cod_empresa";
						mysqli_query($conn, $sqlDt);

						$sqlDt = "INSERT INTO LOG_CANAL (
							COD_EMPRESA,
							COD_UNIVEND,
							COD_CLIENTE,
							COD_CANAL
							) VALUES (
							$cod_empresa, 
							$cod_univend, 
							$cod_clienteRetorno, 
							4
						)";
						mysqli_query(connTemp($cod_empresa, ''), $sqlDt);

						if ($count_filtros != '' && $count_filtros != 0) {

							$sql = "";
							$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

							for ($i = 0; $i < $count_filtros; $i++) {

								$cod_filtro = fnLimpacampoZero(@$_REQUEST["COD_FILTRO_$i"]);
								$cod_tpfiltro = fnLimpacampoZero(@$_REQUEST["COD_TPFILTRO_$i"]);

								if ($cod_filtro != 0 && $cod_filtro != '') {
									$sqlFiltro .= "INSERT INTO CLIENTE_FILTROS(
											COD_EMPRESA,
											COD_TPFILTRO,
											COD_FILTRO,
											COD_CLIENTE,
											COD_USUCADA
											)VALUES(
											$cod_empresa,
											$cod_tpfiltro,
											$cod_filtro,
											$cod_clienteRetorno,
											$cod_usucada
										);";
								}
							}

							//fnEscreve($sql);
							if ($sql != '' && $sql != 0) {
								//fnTestesql(connTemp($cod_empresa,''),$sql);
								$arrayFiltro = mysqli_multi_query($conn, $sqlFiltro);

								if (!$arrayFiltro) {

									$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlFiltro, $nom_usuarioSESSION);
								}
							}
						}
					} else {

						$cod_clienteRetorno = 0;
						$mensagem = "Cliente avulso não pode ser alterado!";
						$msgTipo = 'alert-danger';
					}

					//fnEscreve($cod_clienteRetorno);
					//fnEscreve($mensagem);
					if ($mensagem == "Este cliente já existe!") {

						$msgRetorno = $mensagem;
					} else if ($mensagem == "Novo cliente cadastrado com <strong> sucesso! </strong>") {
						$cod_empresa = fnEncode($cod_empresa);
						$cod_cliente = fnEncode($cod_clienteRetorno);
?>
						<script>
							window.location.replace("action.php?mod=PvUR9sokXEM¢&id=<?= $cod_empresa ?>&idC=<?= $cod_cliente ?>&ido=CAD");
						</script>
					<?php
					} else if ($cod_erro == 0 || $cod_erro == "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						$msgTipo = 'alert-success';
					} else if ($cod_erro != 0 || $cod_erro != "") {
						$msgRetorno = "Erro ao inserir registro: $cod_erro";
						$msgTipo = 'alert-danger';
					} else {

						$msgRetorno = $mensagem;
					}

					break;

				case 'ALT':

					//VERIFICAR SE O CLIENTE JA EXISTE NA BASE DE DADOS
					$veri_cliente = "SELECT NUM_CGCECPF FROM CLIENTES 
							WHERE NUM_CGCECPF = '" . fnLimpaDoc($num_cgcecpf) . "'
							AND COD_CLIENTE != '" . fnDecode(@$_GET['idC']) . "' 
							AND COD_EMPRESA='" . $cod_empresa . "';";

					$dados_cli = mysqli_fetch_assoc(mysqli_query($conn, $veri_cliente));

					// fnEscreve($veri_cliente);

					if (empty($dados_cli['NUM_CGCECPF']) || $dados_cli['NUM_CGCECPF'] != fnLimpaDoc($num_cgcecpf)) {

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

						$msgTipo = 'alert-success';

						$conadmmysql = $connAdm->connAdm();
						$contemporaria = connTemp($cod_empresa, '');
						@$_POST['NUM_TELEFON'] = fnlimpadoc(@$_POST['NUM_TELEFON']);
						@$_POST['NUM_CELULAR'] = fnlimpadoc(@$_POST['NUM_CELULAR']);
						@$_POST['NUM_COMERCIAL'] = fnlimpadoc(@$_POST['NUM_COMERCIAL']);
						@$_POST['NUM_CGCECPF'] = fnlimpadoc(@$_POST['NUM_CGCECPF']);
						@$_POST['NUM_CARTAO'] = fnlimpadoc(@$_POST['NUM_CARTAO']);
						$tesr = fnCompString($_POST, $cod_empresa, "$_POST[NUM_CGCECPF]", $connAdm->connAdm(), connTemp($cod_empresa, ''));

						$sql2 = "CALL SP_ALTERA_CLIENTES(
								'" . $cod_usuario . "',
								'" . $cod_empresa . "',
								'" . $nom_usuario . "',
								'" . $log_usuario . "',
								'" . $des_emailus . "',
								'" . $_SESSION["SYS_COD_USUARIO"] . "',    
								'" . fnLimpaDoc($num_cgcecpf) . "',
								'" . $log_estatus . "',
								'" . $log_trocaprod . "',
								'" . $num_rgpesso . "',
								'" . $dat_nascime . "',
								'" . $cod_estaciv . "',
								'" . $cod_sexopes . "',
								'" . fnLimpaDoc($num_telefon) . "',
								'" . fnLimpaDoc($num_celular) . "',
								'" . fnLimpaDoc($num_comercial) . "',
								'" . $cod_externo . "',
								'" . fnLimpaDoc($num_cartao) . "',
								'" . $num_tentati . "',
								'" . $des_enderec . "',
								'" . $num_enderec . "',
								'" . $des_complem . "',
								'" . $des_bairroc . "',
								'" . $num_cepozof . "',
								'" . $nom_cidadec . "',
								'" . $cod_estadof . "',
								'" . $des_apelido . "',
								'" . $cod_profiss . "',
								" . $cod_univend . ",
								" . $cod_univend_pref . ",
								'" . $tip_cliente . "',
								'" . $des_contato . "',
								'" . $log_email . "',
								'" . $log_sms . "',
								'" . $log_telemark . "',
								'" . $log_whatsapp . "',
								'" . $log_push . "',
								'" . $log_fidelizado . "',
								'" . $nom_pai . "',
								'" . $nom_mae . "',
								'" . $cod_chaveco . "',
								'" . $cod_multemp . "',
								'" . $key_externo . "',
								'" . $cod_tpcliente . "',
								'" . $log_funciona . "',
								'" . $log_master . "',
								'" . $log_ofertas . "',
								'" . $des_coment . "',
								'" . $opcao . "'   

							);";

						// fnEscreve($sql2);
						//if($num_cgcecpf != "" && $num_cgcecpf != 0){
						if (($num_cgcecpf != 0 && $num_cgcecpf != "") || ($num_cgcecpf != 0 && $semCPF = "N")) {

							$arrayUpdate = mysqli_query($conn, $sql2);

							if (!$arrayUpdate) {

								$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql2, $nom_usuarioSESSION);
							}

							if ($cod_erro == 0 || $cod_erro ==  "") {
								$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
								$msgTipo = 'alert-success';
							} else {
								$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
								$msgTipo = 'alert-danger';
							}

							$newDate = explode('/', $dat_nascime);
							$dia = $newDate['0'];
							$mes   = $newDate['1'];
							$ano  = $newDate['2'];

							$hoje = explode("/", date("d/m/Y"));
							$idade = $hoje['2'] - $newDate['2'];

							if ($newDate['1'] > $hoje['1']) {

								$idade = $idade - 1;
							} else if ($newDate['1'] == $hoje['1']) {

								if ($newDate['2'] <= $hoje['2']) {

									$idade = $idade;
								} else {

									$idade = $idade - 1;
								}
							} else {

								$idade = $idade;
							}

							$sqlDt = "UPDATE CLIENTES SET
								DIA = $dia,
								MES = $mes,
								ANO = $ano,
								IDADE = $idade
								WHERE COD_CLIENTE = $cod_usuario
								AND COD_EMPRESA = $cod_empresa";
							// fnEscreve($sqlDt);
							mysqli_query($conn, $sqlDt);

							if ($count_filtros != '' && $count_filtros != 0) {

								$sqlExclusa = "DELETE FROM CLIENTE_FILTROS WHERE COD_CLIENTE = $cod_usuario;";
								mysqli_query(connTemp($cod_empresa, ''), $sqlExclusa);
								$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

								for ($i = 0; $i < $count_filtros; $i++) {

									$cod_filtro = fnLimpacampoZero(@$_REQUEST["COD_FILTRO_$i"]);
									$cod_tpfiltro = fnLimpacampoZero(@$_REQUEST["COD_TPFILTRO_$i"]);

									if ($cod_filtro != 0 && $cod_filtro != '') {
										$sqlFiltro .= "INSERT INTO CLIENTE_FILTROS(
												COD_EMPRESA,
												COD_TPFILTRO,
												COD_FILTRO,
												COD_CLIENTE,
												COD_USUCADA
												)VALUES(
												$cod_empresa,
												$cod_tpfiltro,
												$cod_filtro,
												$cod_usuario,
												$cod_usucada
											);";
									}
								}

								//fnEscreve($sql);
								if (!empty($sqlFiltro)) {
									mysqli_multi_query(conntemp($cod_empresa, ''), $sqlFiltro);
								}
							}
						} else {
							$msgRetorno = "Cliente avulso não pode ser alterado!";
							$msgTipo = 'alert-danger';
						}
					} else {

						$msgRetorno = "Registro não pode ser altrado pois o cpf pertence a outro cliente!";
						$msgTipo = 'alert-danger';
					}
					break;

				case 'BUS':

					$sqlExc = "DELETE FROM CLIENTES WHERE COD_CLIENTE = $cod_usuario AND COD_EMPRESA = $cod_empresa";
					// fnEscreve($sql);
					$arrayExc = mysqli_query($conn, $sqlExc);

					if (!$arrayExc) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlExc, $nom_usuarioSESSION);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro excluído com <strong>sucesso!</strong>";
						$msgTipo = 'alert-success';
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
						$msgTipo = 'alert-danger';
					}

					?>
					<script>
						window.location.replace("action.php?mod=PvUR9sokXEM¢&id=<?= fnEncode($cod_empresa) ?>&idC=<?= fnEncode(0) ?>&idx=EXC");
					</script>
<?php

					break;
			}
		}

		$newDate = explode('/', $dat_nascime);

		if (count($newDate) === 3) {
			$dia = $newDate[0];
			$mes = $newDate[1];
			$ano = $newDate[2];
			$sql = "UPDATE CLIENTES SET DIA = $dia, MES = $mes, ANO = $ano WHERE NUM_CGCECPF = " . fnLimpaDoc($num_cgcecpf);
			//fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa, ''), $sql);
		}
	}
}


//busca dados da url	
// fnEscreve(fnDecode(@$_GET['id'])); 
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

	//categoria de clientes		
	$sql2 = "SELECT B.NOM_FAIXACAT,A.* 
				FROM clientes A
				left join categoria_cliente B ON B.COD_CATEGORIA=A.COD_CATEGORIA
				WHERE A.COD_CLIENTE = $cod_cliente and 
				A.COD_EMPRESA = $cod_empresa";

	$qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql2));
	// fnEscreve($sql2);

	if (isset($qrBuscaCliente)) {

		if ($cod_cliente != 0 && $cod_cliente != '') {
			$cod_usuario = $qrBuscaCliente['COD_CLIENTE'];
			$cod_externo = $qrBuscaCliente['COD_EXTERNO'];
			$nom_usuario = $qrBuscaCliente['NOM_CLIENTE'];
			$num_cartao =  $qrBuscaCliente['NUM_CARTAO'];
			$num_cgcecpf = fnCompletaDoc($qrBuscaCliente['NUM_CGCECPF'], $qrBuscaCliente['TIP_CLIENTE']);
			$num_rgpesso = $qrBuscaCliente['NUM_RGPESSO'];
			$dat_nascime = $qrBuscaCliente['DAT_NASCIME'];
			$cod_estaciv = $qrBuscaCliente['COD_ESTACIV'];
			$cod_sexopes = $qrBuscaCliente['COD_SEXOPES'];
			$des_emailus = $qrBuscaCliente['DES_EMAILUS'];
			$num_telefon = $qrBuscaCliente['NUM_TELEFON'];
			$num_celular = $qrBuscaCliente['NUM_CELULAR'];
			$num_comercial = $qrBuscaCliente['NUM_COMERCI'];
			$des_enderec = $qrBuscaCliente['DES_ENDEREC'];
			$num_enderec = $qrBuscaCliente['NUM_ENDEREC'];
			$des_complem = $qrBuscaCliente['DES_COMPLEM'];
			$des_bairroc = $qrBuscaCliente['DES_BAIRROC'];
			$num_cepozof = $qrBuscaCliente['NUM_CEPOZOF'];
			$nom_cidadec = $qrBuscaCliente['NOM_CIDADEC'];
			$cod_estadof = $qrBuscaCliente['COD_ESTADOF'];
			$dat_cadastr = fnFormatDateTime($qrBuscaCliente['DAT_CADASTR']);
			$log_usuario = $qrBuscaCliente['LOG_USUARIO'];
			if ($qrBuscaCliente['LOG_ESTATUS'] == 'S') {
				$check_ativo = 'checked';
			} else {
				$check_ativo = '';
			}
			//fnEscreve($qrBuscaCliente['LOG_ESTATUS']);
			if ($qrBuscaCliente['LOG_TROCAPROD'] == 'S') {
				$check_troca = 'checked';
			} else {
				$check_troca = '';
			}
			if ($qrBuscaCliente['LOG_MASTER'] == 'S') {
				$check_master = 'checked';
			} else {
				$check_master = '';
			}
			$des_senhaus = fnDecode($qrBuscaCliente['DES_SENHAUS']);
			$num_tentati = $qrBuscaCliente['NUM_TENTATI'];
			$des_apelido = $qrBuscaCliente['DES_APELIDO'];
			$cod_profiss = $qrBuscaCliente['COD_PROFISS'];
			$cod_univend = $qrBuscaCliente['COD_UNIVEND'];
			$cod_univend_pref = $qrBuscaCliente['COD_UNIVEND_PREF'];
			$cod_tpcliente = $qrBuscaCliente['COD_TPCLIENTE'];
			$tip_cliente = $qrBuscaCliente['TIP_CLIENTE'];
			$des_contato = $qrBuscaCliente['DES_CONTATO'];
			if ($qrBuscaCliente['LOG_FUNCIONA'] == 'S') {
				$check_funciona = 'checked';
			} else {
				$check_funciona = '';
			}
			if ($qrBuscaCliente['LOG_EMAIL'] == 'S') {
				$check_mail = 'checked';
			} else {
				$check_mail = '';
			}
			if ($qrBuscaCliente['LOG_SMS'] == 'S') {
				$check_sms = 'checked';
			} else {
				$check_sms = '';
			}
			if ($qrBuscaCliente['LOG_TELEMARK'] == 'S') {
				$check_telemark = 'checked';
			} else {
				$check_telemark = '';
			}
			if ($qrBuscaCliente['LOG_WHATSAPP'] == 'S') {
				$check_whatsapp = 'checked';
			} else {
				$check_whatsapp = '';
			}
			if ($qrBuscaCliente['LOG_PUSH'] == 'S') {
				$check_push = 'checked';
			} else {
				$check_push = '';
			}
			if ($qrBuscaCliente['LOG_FIDELIZADO'] == 'S') {
				$check_fidelizado = 'checked';
			} else {
				$check_fidelizado = '';
			}
			if ($qrBuscaCliente['LOG_OFERTAS'] == 'S') {
				$check_ofertas = 'checked';
			} else {
				$check_ofertas = '';
			}
			$nom_pai = $qrBuscaCliente['NOM_PAI'];
			$nom_mae = $qrBuscaCliente['NOM_MAE'];
			$cod_entidad = $qrBuscaCliente['COD_ENTIDAD'];
			$cod_multemp = $qrBuscaCliente['COD_MULTEMP'];
			if (empty($cod_multemp)) {
				$cod_multemp = "0";
			}
			$key_externo = $qrBuscaCliente['KEY_EXTERNO'];
			$cod_categoria = $qrBuscaCliente['COD_CATEGORIA'];
			$nom_faixacat = $qrBuscaCliente['NOM_FAIXACAT'];
			$cod_indicad = $qrBuscaCliente['COD_INDICAD'];
			$dat_indicad = $qrBuscaCliente['DAT_INDICAD'];
			$des_coment = $qrBuscaCliente['DES_COMENT'];
			$cod_usucada = $qrBuscaCliente['COD_ATENDENTE'];
			$des_token = $qrBuscaCliente['DES_TOKEN'];
		} else {
			@$cod_usuario = 0;
			@$nom_usuario = '';
			@$cod_externo = '';
			@$num_cartao = '';
			@$num_cgcecpf = '';
			@$num_rgpesso = '';
			@$dat_nascime = '';
			@$cod_estaciv = 0;
			@$cod_sexopes = 0;
			@$des_emailus = '';
			@$num_telefon = '';
			@$num_celular = '';
			@$num_comercial = '';
			@$des_enderec = '';
			@$num_enderec = '';
			@$des_complem = '';
			@$des_bairroc = '';
			@$num_cepozof = '';
			@$nom_cidadec = '';
			@$cod_estadof = 0;
			@$dat_cadastr = '';
			@$log_usuario = '';
			@$des_senhaus = '';
			@$num_tentati = '';
			@$des_apelido = '';
			@$cod_profiss = '';
			@$cod_univend = '';
			@$cod_univend_pref = '';
			@$des_contato = '';
			@$log_email = '';
			@$log_sms = '';
			@$log_telemark = '';
			@$nom_pai = '';
			@$nom_mae = '';
			@$check_ativo = 'checked';
			@$check_troca = 'checked';
			@$check_funciona = '';
			@$check_mail = '';
			@$check_sms = '';
			@$check_telemark = '';
			@$check_whatsapp = 'checked';
			@$check_push = 'checked';
			@$check_fidelizado = 'checked';
			@$check_ofertas = 'checked';
			@$cod_entidad = 0;
			@$cod_multemp = "0";
			@$key_externo = "";
			@$cod_tpcliente = "";
			@$cod_tpcliente = "";
			@$check_funciona = '';
			@$cod_indicad = 0;
			@$dat_indicad = '';
			@$cod_usucada = '';
			@$des_token = '';
		}
	} else {
		@$check_whatsapp = 'checked';
		@$check_push = 'checked';
		@$check_fidelizado = 'checked';
	}
} else {
	@$cod_empresa = 0;
	@$nom_empresa = '';
	@$cod_externo = '';
	@$cod_usuario = 0;
	@$nom_usuario = '';
	@$num_cartao = '';
	@$num_cgcecpf = '';
	@$num_rgpesso = '';
	@$dat_nascime = '';
	@$cod_estaciv = 0;
	@$cod_sexopes = 0;
	@$des_emailus = '';
	@$num_telefon = '';
	@$num_celular = '';
	@$num_comercial = '';
	@$des_enderec = '';
	@$num_enderec = '';
	@$des_complem = '';
	@$des_bairroc = '';
	@$num_cepozof = '';
	@$nom_cidadec = '';
	@$cod_estadof = 0;
	@$dat_cadastr = '';
	@$log_usuario = '';
	@$des_senhaus = '';
	@$num_tentati = '';
	@$des_apelido = '';
	@$cod_profiss = '';
	@$cod_univend = '';
	@$cod_univend_pref = '';
	@$des_contato = '';
	@$log_email = '';
	@$log_sms = '';
	@$log_telemark = '';
	@$nom_pai = '';
	@$nom_mae = '';
	@$cod_chaveco = 0;
	@$cod_entidad = 0;
	@$cod_multemp = "0";
	@$key_externo = "";
	@$cod_tpcliente = "";
	@$check_ativo = 'checked';
	@$check_troca = 'checked';
	@$check_whatsapp = 'checked';
	@$check_push = 'checked';
	@$check_fidelizado = 'checked';
	@$check_ofertas = 'checked';
	@$check_funciona = '';
	@$cod_indicad = 0;
	@$dat_indicad = '';
	@$cod_usucada = '';
	@$des_token = '';
}

//fnEscreve($cod_cliente);

if ($cod_indicad != 0 && $cod_indicad != '') {
	$sql = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE = $cod_indicad";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

	$qrIndicad = mysqli_fetch_assoc($arrayQuery);
	$nom_indicad = $qrIndicad['NOM_CLIENTE'];
}

//fnEscreve($cod_chaveco);
/*Criticas chave de cadastro
	1 - CPF/CNPJ
	2 - CARTÃO PRE CADASTRADO
	3 - TELEFONE
	4 - CODIGO EXTERNO 
	5 - CPF/CNPJ+CARTAO 
	6 - CPF/CNPJ/NASC/CEL/EMAIL 
	*/


switch ($cod_chaveco) {
	case 6: //CPF/CNPJ/NASC/CEL/EMAIL
		$rqrDAT_NASCIME = "";
		break;
	default:
		$rqrDAT_NASCIME = "required";
}

$sqlObriga = " SELECT DES_CAMPOOBG
	FROM MATRIZ_CAMPO_INTEGRACAO                         
	INNER JOIN INTEGRA_CAMPOOBG ON INTEGRA_CAMPOOBG.COD_CAMPOOBG=MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG                         
	WHERE MATRIZ_CAMPO_INTEGRACAO.COD_EMPRESA = $cod_empresa
	and matriz_campo_integracao.TIP_CAMPOOBG ='OBG'";
$arrayObriga = mysqli_query($connAdm->connAdm(), $sqlObriga);

$reqDES_APELIDO = "";
$reqNUM_CGCECPF = "";
$reqNUM_RGPESSO = "";
$reqCOD_ESTACIV = "";
$reqCOD_SEXOPES = "";
$reqCOD_TPCLIENTE = "";
$reqCOD_PROFISS = "";
$reqNOM_PAI = "";
$reqNOM_MAE = "";
$reqDES_EMAILUS = "";
$reqDES_CONTATO = "";
$reqNUM_TELEFON = "";
$reqNUM_CELULAR = "";
$reqNUM_COMERCIAL = "";
$reqDES_COMENT = "";
$reqDES_ENDEREC = "";
$reqNUM_ENDEREC = "";
$reqDES_COMPLEM = "";
$reqDES_BAIRROC = "";
$reqNUM_CEPOZOF = "";
$reqNOM_CIDADEC = "";
$reqCOD_ESTADOF = "";

while ($qrObriga = mysqli_fetch_assoc($arrayObriga)) {

	// fnEscreve($qrObriga['DES_CAMPOOBG']);

	switch ($qrObriga['DES_CAMPOOBG']) {

		case 'DES_APELIDO':
			$rqrDES_APELIDO = "required";
			break;
		case 'DAT_NASCIME':
			$rqrDAT_NASCIME = "required";
			break;
		case 'NUM_RGPESSO':
			$rqrNUM_RGPESSO = "required";
			break;
		case 'COD_ESTACIV':
			$rqrCOD_ESTACIV = "required";
			break;
		case 'COD_SEXOPES':
			$rqrCOD_SEXOPES = "required";
			break;
		case 'COD_TPCLIENTE':
			$rqrCOD_TPCLIENTE = "required";
			break;
		case 'COD_PROFISS':
			$rqrCOD_PROFISS = "required";
			break;
		case 'NOM_PAI':
			$rqrNOM_PAI = "required";
			break;
		case 'NOM_MAE':
			$rqrNOM_MAE = "required";
			break;
		case 'DES_EMAILUS':
			$rqrDES_EMAILUS = "required";
			break;
		case 'DES_CONTATO':
			$rqrDES_CONTATO = "required";
			break;
		case 'NUM_TELEFON':
			$rqrNUM_TELEFON = "required";
			break;
		case 'NUM_CELULAR':
			$rqrNUM_CELULAR = "required";
			break;
		case 'NUM_COMERCIAL':
			$rqrNUM_COMERCIAL = "required";
			break;
		case 'DES_COMENT':
			$rqrDES_COMENT = "required";
			break;
		case 'DES_ENDEREC':
			$rqrDES_ENDEREC = "required";
			break;
		case 'NUM_ENDEREC':
			$rqrNUM_ENDEREC = "required";
			break;
		case 'DES_COMPLEM':
			$rqrDES_COMPLEM = "required";
			break;
		case 'DES_BAIRROC':
			$rqrDES_BAIRROC = "required";
			break;
		case 'NUM_CEPOZOF':
			$rqrNUM_CEPOZOF = "required";
			break;
		case 'NOM_CIDADEC':
			$rqrNOM_CIDADEC = "required";
			break;
		case 'COD_ESTADOF':
			$rqrCOD_ESTADOF = "required";
			break;
	}
}

// echo "<pre>";
// print_r($qrObriga);
// echo "</pre>";

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa, ''), $sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = "";
if (isset($qrControle['LOG_SEPARA'])) {
	$log_separa = $qrControle['LOG_SEPARA'];
}

$log_lgpd = "";
if (isset($qrControle['LOG_LGPD'])) {
	$log_lgpd = $qrControle['LOG_LGPD'];
}

$sqlCanal = "SELECT * FROM LOG_CANAL 
	WHERE COD_EMPRESA = $cod_empresa 
	AND COD_CLIENTE = $cod_cliente";
//echo 	$sqlCanal;		 
$arrayCanal = mysqli_query(connTemp($cod_empresa, ''), $sqlCanal);

if (mysqli_num_rows($arrayCanal) > 0) {

	$qrCanal = mysqli_fetch_assoc($arrayCanal);

	switch ($qrCanal['COD_CANAL']) {

		case 2:
			$canal = "TOTEM";
			break;

		case 3:
			$canal = "HOTSITE";
			break;

		case 4:
			$canal = "BUNKER";
			break;

		case 5:
			$canal = "PDV VIRTUAL";
			break;

		case 6:
			$canal = "MAIS CASH";
			break;

		default:
			$canal = "PDV SH";
			break;
	}

	$dat_cadastr_canal = fnDataFull($qrCanal['DAT_ATIV']);

	switch ($qrCanal['COD_TIPO']) {

		case 1:
			$tipoAtiv = "PDV SH";
			if ($qrCanal['DAT_ATIV'] == "") {
				$tipoAtiv = "Ag. Ativação";
			}
			break;

		case 2:
			$tipoAtiv = "TOTEM";
			break;

		case 3:
			$tipoAtiv = "HOTSITE";
			break;

		case 4:
			$tipoAtiv = "SMS";
			break;

		case 5:
			$tipoAtiv = "EMAIL";
			break;

		case 6:
			$tipoAtiv = "PDV VIRTUAL";
			break;

		default:
			$tipoAtiv = "Ag. Ativação";
			$dat_cadastr_canal = "";
			break;
	}
} else {

	$tipoAtiv = "Ag. Ativação";
	$canal = "PDV SH";
	$dat_cadastr_canal = "";
}

if ($cod_cliente == 0) {
	$check_ativo = 'checked';
}

//rotina de controle de acessos por módulo
// if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $_SESSION["SYS_COD_SISTEMA"] == 4){	
include "moduloControlaAcesso.php";
// }

// echo($_SESSION["SYS_COD_SISTEMA"]);

//echo("<h1>".$sql2."</h1>");
// echo "<pre>";
// print_r(@$_POST);
// echo "</pre>";
// fnMostraform();

if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	fnConsole("pwd: " . $des_senhaus);
}

if (fnControlaAcesso("1512", $arrayParamAutorizacao) === true) {
	$bloquSenha = "addBox";
} else {
	$bloquSenha = "disabled-senha";
}


?>

<style>
	.alert .alert-link {
		text-decoration: none;
	}

	.alert:hover .alert-link:hover {
		text-decoration: underline;
	}

	.forbidden {
		background-color: #fff;
		opacity: 0.7;
		cursor: not-allowed;
		z-index: 9999;
	}
</style>

<!--<style>

		.alert-secondary {
			color: #888!important;
			background-color: #F2F4F4!important;
			border-color: #F2F4F4!important;
		}

		.btn-clippy {
			bottom: 450px;
			right: -8px;
			position: fixed;
			background-color: transparent;
			margin: 0;
			height: 60px;
			width: 60px;
		}

		.clippy {
			overflow-x: hidden;
			-webkit-tap-highlight-color: transparent;
			height: 30px;
			width: 15px;
			border: 3px solid #333;
			border-top-left-radius: 15px;
			border-top-right-radius: 15px;
			border-bottom: none;
		}
		.clippy:before {
			position: absolute;
			top: 26px;
			width: 20px;
			height: 25px;
			border: 3px solid #333;
			left: 0;
			content: ' ';
			border-top: none;
			border-bottom-left-radius: 15px;
			border-bottom-right-radius: 15px;
		}
		.clippy:after {
			position: absolute;
			top: 28px;
			width: 9.5px;
			height: 13.125px;
			border: 3px solid #333;
			left: 5.5px;
			content: ' ';
			border-top: none;
			border-bottom-left-radius: 15px;
			border-bottom-right-radius: 15px;
		}
		.eye {
			position: absolute;
			height: 14px;
			width: 15px;
			border: 2.75px solid #333;
			border-radius: 50%;
			border-right-color: transparent;
			border-left-color: transparent;
			border-bottom-color: transparent;
			border-bottom-width: 0.75px;
			top: 11.25px;
			background: white;
		}
		.eye.left {
			left: -7px;
			transform: rotate(-20deg);
			animation: leftEye 2.5s infinite ease-in-out ;
		}
		.eye.right {
			left: 7px;
			transform: rotate(20deg);
			animation: rightEye 2.5s infinite ease-in-out ;
		}
		.eye:after {
			width: 11px;
			height: 11px;
			background-color: transparent;
			border: 3.5px solid #333;
			border-radius: 50%;
			position: absolute;
			top: 1.875px;
			content: ' ';
			transform-origin: center;
		}
		.eye:before {
			content: ' ';
			width: 7px;
			height: 7px;
			border-radius: 50%;
			background-color: #333;
			position: absolute;
			top: 3.75px;
			left: 3px;
			transform-origin: bottom;
			animation: blink 2.5s infinite ease-in-out normal;
		}

		@keyframes blink {
			0%, 75%, 100% {
				height: 0px;
			}
			90%{
				height: 7.5px;
				transform: rotate(-12deg)
			}
		}

		@keyframes leftEye {
			0%, 60% {
				transform: rotate(-20deg);
			}
			30% {
				transform: rotate(0deg) translateY(-15%) translateX(5%);
			}
		}

		@keyframes rightEye {
			0%, 60% {
				transform: rotate(20deg);
			}
			30% {
				transform: rotate(0deg) translateY(-15%) translateX(-5%);
			}

		}

	</style>-->

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>

				<?php
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 4: //fidelidade
						$formBack = "1102";
						break;
					case 14: //rede duque
						$formBack = "1102";
						break;
					default;
						$formBack = "1015";
						break;
				}
				include "atalhosPortlet.php";
				?>

			</div>
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
						Cliente possui vendas bloqueadas. <br />

						<?php
						if (fnControlaAcesso("1191", $arrayParamAutorizacao) === true) { ?>
							<!-- 1099 -->
							<a href="action.do?mod=<?php echo fnEncode(1191); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank" class="alert-link">&rsaquo; Acessar tela de desbloqueio</a>
						<?php } else { ?>
							<a href="javascript:$.alert('Você não possui acesso a este módulo');" class="alert-link">&rsaquo; Acessar tela de desbloqueio</a>
						<?php } ?>
					</div>
				<?php } ?>

				<?php

				if ($_SESSION['SYS_COD_EMPRESA'] == 2) {

				?>

					<div class="alert alert-secondary alert-dismissible top30" role="alert" id="msgInfo" style="display: none;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong><i class="fas fa-bullseye-arrow fa-lg"></i></i></strong>&nbsp; Este <b>é um texto</b> de informações gerais sobre a <b>tela</b>. <div class="push"></div>
						Aqui vai tudo sobre o que a tela faz ou deveria fazer, assim como qual é o conceito por trás dos dados da mesma.
					</div>

				<?php
				}

				?>

				<?php
				//menu superior - cliente
				$abaEmpresa = 1020;
				$abaCli = 1024;
				// echo $_SESSION["SYS_COD_SISTEMA"];								
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 14: //rede duque
						include "abasClienteDuque.php";
						break;
					case 13: //sh manager
						include "abasIntegradoraCli.php";
						break;
					case 18: //mais cash
						include "abasMaisCashCli.php";
					case 21: //gestão garantias
						include "abasGestaoGarantiasCli.php";
						break;
					default;
						include "abasClienteConfig.php";
						break;
				}

				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="action.php?mod=<?php echo fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($cod_cliente); ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<?php
								if ($_SESSION["SYS_COD_SISTEMA"] == 14) {

									$sql3 = "select NOM_ENTIDAD from ENTIDADE where COD_ENTIDAD = $cod_entidad";
									$qrBuscaEntidade = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql3));
									//fnEscreve($sql3);	
									$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];
								?>

									<div class="col-md-3">
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

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Ativo</label><br />
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" <?php echo $check_ativo; ?> />
											<span></span>
										</label>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cliente é Funcionário</label><br />
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_FUNCIONA" id="LOG_FUNCIONA" class="switch" value="S" <?php echo $check_funciona; ?> />
											<span></span>
										</label>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Permite Troca de Produtos</label><br />
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_TROCAPROD" id="LOG_TROCAPROD" class="switch" value="S" <?php echo $check_troca; ?> />
											<span></span>
										</label>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Não participa do Antifraude</label><br />
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_MASTER" id="LOG_MASTER" class="switch" value="S" <?php echo $check_master; ?> />
											<span></span>
										</label>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<?php if ($log_categoria == "S") { ?>
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Categoria do cliente</label>
											<div class="push5"></div>
											<span class="label label-pill label-info f14"><i class="fal fa-bookmark"></i> &nbsp; <?php echo $nom_faixacat; ?></span>
										</div>
									</div>
								<?php } ?>

								<div class="push10"></div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_usuario; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
									</div>
								</div>

								<div class="col-md-5">
									<label for="inputName" class="control-label required">Nome do Cliente</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
										</span>
										<input type="text" name="NOM_USUARIO" id="NOM_USUARIO" value="<?php echo $nom_usuario; ?>" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<?php
								switch ($_SESSION["SYS_COD_SISTEMA"]) {
									case 14: //rede duque
										$cartaoObg = "";
										break;
									default;
										$cartaoObg = "required";
										break;
								}
								?>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?php echo $cartaoObg; ?> ">Número do Cartão</label>
										<?php

										if ($cod_cliente == 0) {

											if ($cod_chaveco == 2 || $cod_chaveco == 5) {

										?>

												<!-- <input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="" maxlength="50" data-error="Campo obrigatório" required> -->

												<div class="input-group">
													<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1467) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?= fnEncode($cod_chaveco) ?>&pop=true" data-title="Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
													</span>
													<input type="text" name="NUM_CARTAO" id="NUM_CARTAO" value="<?= ($num_cartao == 0) ? '' : $num_cartao ?>" readonly maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
												</div>



											<?php

											} else { ?>

												<input type="text" class="form-control input-sm leitura" name="NUM_CARTAO_VAZIO" id="NUM_CARTAO_VAZIO" value="" maxlength="50" readonly="readonly" data-error="Campo obrigatório">
												<input type="hidden" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="">

											<?php
											}
											?>



										<?php } else { ?>

											<input type="text" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" readonly="readonly" data-error="Campo obrigatório" <?php echo $cartaoObg; ?>>

										<?php } ?>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrDES_APELIDO ?>">Apelido</label>
										<input type="text" class="form-control input-sm" name="DES_APELIDO" id="DES_APELIDO" value="<?php echo $des_apelido; ?>" maxlength="18" data-error="Campo obrigatório" <?= $rqrDES_APELIDO ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">CNPJ/CPF</label>
										<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo fnCompletaDoc($num_cgcecpf, 'F'); ?>" maxlength="18" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrNUM_RGPESSO ?>">RG</label>
										<input type="text" class="form-control input-sm" name="NUM_RGPESSO" id="NUM_RGPESSO" value="<?php echo $num_rgpesso; ?>" maxlength="15" data-error="Campo obrigatório" <?= $rqrNUM_RGPESSO ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrDAT_NASCIME ?>">Data de Nascimento</label>
										<input type="text" class="form-control input-sm data" name="DAT_NASCIME" value="<?= $dat_nascime ?>" id="DAT_NASCIME" data-minlength="10" data-minlength-error="O formato deve ser DD/MM/AAAA" maxlength="10" <?= $rqrDAT_NASCIME ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrCOD_ESTACIV ?>">Estado Civil</label>
										<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect" <?= $rqrCOD_ESTACIV ?>>
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

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrCOD_SEXOPES ?> required">Sexo</label>
										<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect requiredChk" required <?= $rqrCOD_SEXOPES ?>>
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrCOD_TPCLIENTE ?>">Tipo do Cliente </label>
										<select data-placeholder="Selecione o tipo do cliente" name="COD_TPCLIENTE" id="COD_TPCLIENTE" class="chosen-select-deselect" <?= $rqrCOD_TPCLIENTE ?>>
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrCOD_PROFISS ?>">Profissão </label>
										<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect" <?= $rqrCOD_PROFISS ?>>
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrNOM_PAI ?>">Nome do Pai</label>
										<input type="text" class="form-control input-sm" name="NOM_PAI" id="NOM_PAI" value="<?php echo $nom_pai ?>" maxlength="60" data-error="Campo obrigatório" <?= $rqrNOM_PAI ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrNOM_MAE ?>">Nome da Mãe</label>
										<input type="text" class="form-control input-sm" name="NOM_MAE" id="NOM_MAE" value="<?php echo $nom_mae ?>" maxlength="60" data-error="Campo obrigatório" <?= $rqrNOM_MAE ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<?php if ($cod_indicad == 0 && $cod_cliente != 0) { ?>
									<div class="col-md-4">
										<label for="inputName" class="control-label">Nome do Indicador</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBuscaInd" id="btnBuscaInd" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($cod_cliente) ?>&pop=true&op=IND" data-title="Busca Indicador"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
											</span>
											<input type="text" name="NOM_INDICA" id="NOM_INDICA" value="" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
											<input type="hidden" name="COD_INDICA" id="COD_INDICA" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data da Indicação</label>
											<input type="text" class="form-control input-sm leitura" name="DAT_INDICA" id="DAT_INDICA" value="" maxlength="50" readonly="readonly">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								<?php } else if ($cod_cliente == 0) { ?>

									<div class="col-md-4">
										<label for="inputName" class="control-label">Nome do Indicador</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBuscaInd" id="btnBuscaInd" style="height:35px;" class="btn btn-primary btn-sm" disabled><i class="fal fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
											</span>
											<input type="text" name="NOM_INDICA" id="NOM_INDICA" disabled value="" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
											<input type="hidden" name="COD_INDICA" id="COD_INDICA" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data da Indicação</label>
											<input type="text" class="form-control input-sm leitura" name="DAT_INDICA" id="DAT_INDICA" value="" maxlength="50" readonly="readonly">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								<?php } else { ?>
									<div class="col-md-4">
										<label for="inputName" class="control-label">Nome do Indicador</label>
										<div class="input-group">
											<span class="input-group-btn">
												<input type="text" name="NOM_INDICA" id="NOM_INDICA" value="<?php echo $nom_indicad; ?>" maxlength="50" class="form-control input-sm leitura" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
												<input type="hidden" name="COD_INDICA" id="COD_INDICA" value="<?php echo $cod_indicad; ?>">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data da Indicação</label>
											<input type="text" class="form-control input-sm leitura" name="DAT_INDICA" id="DAT_INDICA" value="<?php echo date("d/m/Y H:i:s", strtotime($dat_indicad)); ?>" maxlength="50" readonly="readonly">
											<div class="help-block with-errors"></div>
										</div>
									</div>
								<?php } ?>

								<?php
								$sqlIndica = "SELECT COUNT(COD_INDICACAO) as CONTADOR_INDICA FROM clientes_indicados WHERE COD_INDICAD = $cod_cliente";
								$queryIndica = mysqli_query(connTemp($cod_empresa, ''), $sqlIndica);

								if (@$qrIndica = mysqli_fetch_assoc($queryIndica)) {

									$qtd_indica = $qrIndica['CONTADOR_INDICA'];
									// $desabi = "";
								} else {
									$qtd_indica = 0;
									// $desabi = "disabled";
								}

								if ($qtd_indica != 0 && $qtd_indica != "") {
									$desabi = "";
								} else {
									$desabi = "disabled";
								}
								?>

								<div class="col-md-2">
									<div class="form-group">
										<label>&nbsp;</label>
										<div class="push"></div>
										<a type="button" class="btn btn-info position-relative addBox" style="height: 35px;padding-top: 5px; display: block;" data-url="action.php?mod=<?php echo fnEncode(2079) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?= fnEncode($cod_cliente) ?>&pop=true" data-title="Lista Indicação" <?= $desabi ?>>
											Indicações
											<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
												<?= $qtd_indica ?>
												<span class="visually-hidden"></span>
											</span>
										</a>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>

						<fieldset>
							<legend>Comunicação</legend>

							<div class="row">


								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrDES_EMAILUS ?>">e-Mail</label>
										<input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" value="<?php echo $des_emailus; ?>" maxlength="100" value="" data-error="Campo obrigatório" <?= $rqrDES_EMAILUS ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrDES_CONTATO ?>">Contato</label>
										<input type="text" class="form-control input-sm" name="DES_CONTATO" value="<?php echo $des_contato; ?>" id="DES_CONTATO" maxlength="20" <?= $rqrDES_CONTATO ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrNUM_TELEFON ?>">Telefone Principal</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_TELEFON" value="<?php ($num_telefon != "") ? fnCorrigeTelefone($num_telefon) : ""; ?>" id="NUM_TELEFON" maxlength="20" <?= $rqrNUM_TELEFON ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrNUM_CELULAR ?>">Telefone Celular</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" value="<?php ($num_celular != "") ? fnCorrigeTelefone($num_celular) : ""; ?>" id="NUM_CELULAR" maxlength="20" <?= $rqrNUM_CELULAR ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrNUM_COMERCIAL ?>">Telefone Comercial</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_COMERCIAL" value="<?php ($num_comercial != "") ? fnCorrigeTelefone($num_comercial) : ""; ?>" id="NUM_COMERCIAL" maxlength="20" <?= $rqrNUM_COMERCIAL ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push20"></div>

							<div class="row">

								<div class="col-md-4">


									<label for="inputName" class="control-label" id="TAG_HISTORICO"><b>Tag Persona (histórico)</b></label>

									<div class="push10"></div>

									<ul class="tag">
										<?php
										$sql = "SELECT DISTINCT B.DES_PERSONA
																FROM CREDITOSDEBITOS A, PERSONA B
																WHERE 
																A.COD_PERSONA=B.COD_PERSONA AND
																A.COD_CLIENTE=$cod_cliente ";

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
										while ($qrListaTagPersonaOff = mysqli_fetch_assoc($arrayQuery)) {
										?>
											<li class="tag"><span class="label label-warning">● &nbsp; <?php echo ucfirst($qrListaTagPersonaOff['DES_PERSONA']); ?></span></li>
										<?php
										}
										?>
									</ul>


								</div>

								<div class="col-md-4">
									<div class="push10"></div>

									<label for="inputName" class="control-label" id="TAG_CAMPANHAS"><b>Tag Campanhas</b></label>

									<div class="push10"></div>

									<ul class="tag">
										<?php
										$sql = "SELECT DISTINCT B.DES_CAMPANHA
																FROM CREDITOSDEBITOS A, CAMPANHA B
																WHERE 
																A.COD_CAMPANHA=B.COD_CAMPANHA AND
																A.COD_CLIENTE=$cod_cliente ";

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
										while ($qrListaTagCampanha = mysqli_fetch_assoc($arrayQuery)) {
										?>
											<li class="tag"><span class="label label-info">● &nbsp; <?php echo ucfirst($qrListaTagCampanha['DES_CAMPANHA']); ?></span></li>
										<?php
										}
										?>
									</ul>

								</div>

								<div class="col-md-4">
									<label for="inputName" class="control-label" id="TAG_ONLINE"><b>Tag Persona (online) </b></label>

									<div class="push10"></div>

									<ul class="tag">

										<?php
										$sql = "SELECT  B.DES_PERSONA, B.COD_PERSONA 
																FROM PERSONACLASSIFICA A, PERSONA B
																WHERE 
																A.COD_PERSONA=B.COD_PERSONA AND
																COD_CLIENTE=$cod_cliente ";
										//fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
										while ($qrListaTagPersonaOn = mysqli_fetch_assoc($arrayQuery)) {
										?>
											<li class="tag" idp="<?php echo $qrListaTagPersonaOn['COD_PERSONA']; ?>"><span class="label label-success">● &nbsp; <?php echo ucfirst($qrListaTagPersonaOn['DES_PERSONA']); ?></span></li>

										<?php
										}
										?>

									</ul>

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

						<?php if ($log_lgpd == 'S') { ?>

							<div class="push10"></div>

							<fieldset>
								<legend>Termos e Condições</legend>

								<div class="row" id="relatorioPreview">
									<div class="col-md-6 col-xs-12">
										<div class="alert alert-warning" role="alert">
											<strong>Atenção LGPD. </strong> Este bloco de informações é preenchido <b>exclusivamente</b> pelo cliente.
										</div>
									</div>
								</div>

								<div class="row" id="relatorioPreview">

									<div class="forbidden">

										<div class="push"></div>

										<div class="col-xs-12">
											<p><b><?= $qrControle['TXT_ACEITE'] ?></b></p>
										</div>

										<div class="push10"></div>

										<?php

										if ($log_separa == 'S') {

											$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' AND TIP_TERMO != 'COM' ORDER BY NUM_ORDENAC";
											// fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											$tipo = "";
											while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

												if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
													$obrigaChk = "required";
												} else {
													$obrigaChk = "";
												}


												$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
																		WHERE COD_CLIENTE = $cod_cliente
																		AND COD_EMPRESA = $cod_empresa
																		AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
																		AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
												// echo($sqlChk);
												$arrayChk = mysqli_query(connTemp($cod_empresa, ''), $sqlChk);

												$chkTermo = "";

												if (mysqli_num_rows($arrayChk) == 1) {
													$chkTermo = "checked";
												}

												$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
																		WHERE COD_EMPRESA = $cod_empresa
																		AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

												// fnEscreve($sqlTermos);

												$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

												$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

												while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
													// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

													$des_bloco = str_replace(
														"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
														'
																				</label>
																				
																				<a class="addBox f16 text-success" 
																				data-url="https://adm.bunker.mk/action.php?mod=' . fnEncode(1677) . '&id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																				data-title="Template do Email"
																				style="cursor:pointer;">
																				' . $qrTermos['ABV_TERMO'] . '
																				</a>
																				
																				<label class="f16" for="TERMOS_' . $qrBuscaFAQ['COD_BLOCO'] . '">
																				',
														$des_bloco
													);
												}

										?>

												<div class="form-group">
													<div class="col-xs-12">
														<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
															<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?> disabled>
															<label class="<?= $obrigaChk ?>"></label>
														</div>
														<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
															<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
																&nbsp;<?= $des_bloco ?>
															</label>
														</div>
													</div>
													<div class="help-block with-errors"></div>
													<div class="push10"></div>
													<div class="push5"></div>
												</div>

											<?php

												$count++;
											}

											?>

											<div class="col-xs-12">
												<h5>
													<b>
														<p><?= $qrControle['TXT_COMUNICA'] ?></p>
													</b>
												</h5>
											</div>
											<div class="push10"></div>

											<?php

											$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' AND TIP_TERMO = 'COM' ORDER BY NUM_ORDENAC";
											// fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											// $count=0;
											$tipo = "";
											while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

												if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
													$obrigaChk = "required";
												} else {
													$obrigaChk = "";
												}


												$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
																		WHERE COD_CLIENTE = $cod_cliente
																		AND COD_EMPRESA = $cod_empresa
																		AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
																		AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
												// echo($sqlChk);
												$arrayChk = mysqli_query(connTemp($cod_empresa, ''), $sqlChk);

												$chkTermo = "";

												if (mysqli_num_rows($arrayChk) == 1) {
													$chkTermo = "checked";
												}

												$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
																		WHERE COD_EMPRESA = $cod_empresa
																		AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

												// fnEscreve($sqlTermos);

												$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

												$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

												while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
													// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

													$des_bloco = str_replace(
														"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
														'
																				</label>
																				
																				<a class="addBox f16 text-success" 
																				data-url="https://adm.bunker.mk/action.php?mod=' . fnEncode(1677) . '&id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																				data-title="Template do Email"
																				style="cursor:pointer;">
																				' . $qrTermos['ABV_TERMO'] . '
																				</a>
																				
																				<label class="f16" for="TERMOS_' . $qrBuscaFAQ['COD_BLOCO'] . '">
																				',
														$des_bloco
													);
												}

											?>

												<div class="form-group">
													<div class="col-xs-12">
														<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
															<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?> disabled>
															<label class="<?= $obrigaChk ?>"></label>
														</div>
														<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
															<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
																&nbsp;<?= $des_bloco ?>
															</label>
														</div>
													</div>
													<div class="help-block with-errors"></div>
													<div class="push10"></div>
													<div class="push5"></div>
												</div>

											<?php

												$count++;
											}
										} else {

											$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' ORDER BY NUM_ORDENAC";
											// fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											$tipo = "";
											while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

												if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
													$obrigaChk = "required";
												} else {
													$obrigaChk = "";
												}


												$sqlChk = "SELECT 1 FROM CLIENTES_TERMOS
																		WHERE COD_CLIENTE = $cod_cliente
																		AND COD_EMPRESA = $cod_empresa
																		AND COD_BLOCO = $qrBuscaFAQ[COD_BLOCO]
																		AND COD_TERMOS = '$qrBuscaFAQ[COD_TERMO]'";
												// echo($sqlChk);
												$arrayChk = mysqli_query(connTemp($cod_empresa, ''), $sqlChk);

												$chkTermo = "";

												if (mysqli_num_rows($arrayChk) == 1) {
													$chkTermo = "checked";
												}

												$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
																		WHERE COD_EMPRESA = $cod_empresa
																		AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

												// fnEscreve($sqlTermos);

												$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

												$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

												while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
													// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));


													// if ($cod_cliente == "" || $cod_cliente == 0) {
													// 	if ($qrTermos['ABV_TERMO'] == 'email') {
													// 		$check_mail = "checked";
													// 	}

													// 	if ($qrTermos['ABV_TERMO'] == 'SMS') {
													// 		$check_sms = "checked";
													// 	}

													// 	if ($qrTermos['ABV_TERMO'] == 'WhatsApp') {
													// 		$check_whatsapp = "checked";
													// 	}

													// 	if ($qrTermos['ABV_TERMO'] == 'Push') {
													// 		$check_push = "checked";
													// 	}

													// 	if ($qrTermos['ABV_TERMO'] == 'Ofertas') {
													// 		$check_ofertas = "checked";
													// 	}

													// 	if ($qrTermos['ABV_TERMO'] == 'Telemarketing') {
													// 		$check_telemark = "checked";
													// 	}
													// }

													$des_bloco = str_replace(
														"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
														'
																				</label>
																				
																				<a class="addBox f16 text-success" 
																				data-url="https://adm.bunker.mk/action.php?mod=' . fnEncode(1677) . '&id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																				data-title="Template do Email"
																				style="cursor:pointer;">
																				' . $qrTermos['ABV_TERMO'] . '
																				</a>
																				
																				<label class="f16" for="TERMOS_' . $qrBuscaFAQ['COD_BLOCO'] . '">
																				',
														$des_bloco
													);
												}

											?>

												<div class="form-group">
													<div class="col-xs-12">
														<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
															<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?> disabled>
															<label class="<?= $obrigaChk ?>"></label>
														</div>
														<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
															<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
																&nbsp;<?= $des_bloco ?>
															</label>
														</div>
													</div>
													<div class="help-block with-errors"></div>
													<div class="push10"></div>
													<div class="push5"></div>
												</div>

										<?php

												$count++;
											}
										}

										?>

									</div>


								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Participa da Fidelização</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_FIDELIZADO" id="LOG_FIDELIZADO" class="switch" value="S" <?php echo $check_fidelizado; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />e-Mail</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_EMAIL" id="LOG_EMAIL" class="switch" value="S" <?php echo $check_mail; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />SMS</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_SMS" id="LOG_SMS" class="switch" value="S" <?php echo $check_sms; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />Whatsapp</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_WHATSAPP" id="LOG_WHATSAPP" class="switch" value="S" <?php echo $check_whatsapp; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />Push</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_PUSH" id="LOG_PUSH" class="switch" value="S" <?php echo $check_push; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Ofertas Personalizadas</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_OFERTAS" id="LOG_OFERTAS" class="switch" value="S" <?php echo $check_ofertas; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />Telemarketing</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_TELEMARK" id="LOG_TELEMARK" class="switch" value="S" <?php echo $check_telemark; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data de Cadastro</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" value="<?php echo $dat_cadastr; ?>" name="DAT_CADASTR_CLI" id="DAT_CADASTR_CLI">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Canal de Cadastro</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" value="<?php echo $canal; ?>" name="CANAL_CAD" id="CANAL_CAD">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data de Ativação</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" value="<?php echo $dat_cadastr_canal; ?>" name="DAT_CADASTR_CANAL" id="DAT_CADASTR_CANAL">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Canal de Ativação</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" value="<?php echo $tipoAtiv; ?>" name="COD_TIPOATV" id="COD_TIPOATV">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">ID de Ativação</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" value="<?php echo $des_token; ?>" name="COD_TIPOATV" id="COD_TIPOATV">
										</div>
									</div>

								</div>

							</fieldset>

						<?php } else { ?>

							<div class="push10"></div>

							<fieldset>
								<legend>Comunicação</legend>

								<div class="row">

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Participa da Fidelização</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_FIDELIZADO" id="LOG_FIDELIZADO" class="switch" value="S" <?php echo $check_fidelizado; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />e-Mail</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_EMAIL" id="LOG_EMAIL" class="switch" value="S" <?php echo $check_mail; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />SMS</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_SMS" id="LOG_SMS" class="switch" value="S" <?php echo $check_sms; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />Whatsapp</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_WHATSAPP" id="LOG_WHATSAPP" class="switch" value="S" <?php echo $check_whatsapp; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />Push</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_PUSH" id="LOG_PUSH" class="switch" value="S" <?php echo $check_push; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Ofertas Personalizadas</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_OFERTAS" id="LOG_OFERTAS" class="switch" value="S" <?php echo $check_ofertas; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Recebe <br />Telemarketing</label><br />
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_TELEMARK" id="LOG_TELEMARK" class="switch" value="S" <?php echo $check_telemark; ?> />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

						<?php } ?>

						<div class="push10"></div>

						<?php

						$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
												WHERE COD_EMPRESA = $cod_empresa
												ORDER BY NUM_ORDENAC";
						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

						if (mysqli_num_rows($arrayQuery) > 0) {
							$countFiltros = 0;
						?>
							<style>
								@import url("css/fa5all.css");
							</style>
							<fieldset>
								<legend>Filtros</legend>

								<div class="row">

									<?php
									while ($qrTipo = mysqli_fetch_assoc($arrayQuery)) {
									?>

										<style type="text/css">
											#COD_FILTRO_<?= $qrTipo["COD_TPFILTRO"] ?>_chosen .chosen-drop .chosen-results li:last-child {
												font-weight: bolder;
												font-size: 11px;
												color: #000;
											}

											#COD_FILTRO_<?= $qrTipo["COD_TPFILTRO"] ?>_chosen .chosen-drop .chosen-results li:last-child:before {
												content: '\002795';
												font-weight: bolder;
												font-size: 9px;
											}
										</style>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label"><?= $qrTipo['DES_TPFILTRO'] ?></label>
												<div id="relatorioFiltro_<?= $countFiltros ?>">
													<input type="hidden" name="COD_TPFILTRO_<?= $countFiltros ?>" id="COD_TPFILTRO_<?= $countFiltros ?>" value="<?= $qrTipo['COD_TPFILTRO'] ?>">
													<select data-placeholder="Selecione o filtro" name="COD_FILTRO_<?= $countFiltros ?>" id="COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?>" class="chosen-select-deselect last-chosen-link">
														<option value=""></option>
														<?php
														$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																				WHERE COD_TPFILTRO = " . $qrTipo['COD_TPFILTRO'];

														$arrayFiltros = mysqli_query(connTemp($cod_empresa, ''), trim($sqlFiltro));
														while ($qrFiltros = mysqli_fetch_assoc($arrayFiltros)) {
														?>

															<option value="<?= $qrFiltros['COD_FILTRO'] ?>"><?= $qrFiltros['DES_FILTRO'] ?></option>

															<?php
														}

														if ($cod_usuario != "" && $cod_usuario != 0) {
															$sqlChosen = "SELECT COD_FILTRO FROM CLIENTE_FILTROS
																					WHERE COD_CLIENTE = $cod_usuario AND COD_TPFILTRO =" . $qrTipo['COD_TPFILTRO'];
															$arrayChosen = mysqli_query(connTemp($cod_empresa, ''), $sqlChosen);
															if (mysqli_num_rows($arrayChosen) > 0) {
																$qrChosen = mysqli_fetch_assoc($arrayChosen);
															?>
																<script>
																	$('#COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?>').val(<?= $qrChosen['COD_FILTRO'] ?>).trigger('chosen:updated');
																</script>
														<?php
															}
														}
														?>
														<option value="add">&nbsp;ADICIONAR NOVO</option>
													</select>
													<script type="text/javascript">
														$('#COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?>').change(function() {
															valor = $(this).val();
															if (valor == "add") {
																$(this).val('').trigger("chosen:updated");
																$('#btnCad_<?= $countFiltros ?>').click();
															}
														});
													</script>
													<div class="help-block with-errors"></div>
												</div>
											</div>
										</div>
										<a type="hidden" name="btnCad_<?= $countFiltros ?>" id="btnCad_<?= $countFiltros ?>" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1398) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idF=<?= fnEncode($qrTipo['COD_TPFILTRO']) ?>&idS=<?= fnEncode($countFiltros) ?>&pop=true" data-title="Cadastrar Filtro - <?= $qrTipo['DES_TPFILTRO'] ?>"></a>

									<?php
										$countFiltros++;
									}
									?>

								</div>

							</fieldset>

							<div class="push10"></div>

						<?php
						}
						?>

						<fieldset>
							<legend>Localização</legend>

							<div class="row">


								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrDES_ENDEREC ?>">Endereço</label>
										<input type="text" class="form-control input-sm" name="DES_ENDEREC" value="<?php echo $des_enderec; ?>" id="DES_ENDEREC" maxlength="40" <?= $rqrDES_ENDEREC ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrNUM_ENDEREC ?>">Número</label>
										<input type="text" class="form-control input-sm" name="NUM_ENDEREC" value="<?php echo $num_enderec; ?>" id="NUM_ENDEREC" maxlength="10" <?= $rqrNUM_ENDEREC ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrDES_COMPLEM ?>">Complemento</label>
										<input type="text" class="form-control input-sm" name="DES_COMPLEM" value="<?php echo $des_complem; ?>" id="DES_COMPLEM" maxlength="20" <?= $rqrDES_COMPLEM ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrDES_BAIRROC ?>">Bairro</label>
										<input type="text" class="form-control input-sm" name="DES_BAIRROC" value="<?php echo $des_bairroc; ?>" id="DES_BAIRROC" maxlength="20" <?= $rqrDES_BAIRROC ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrNUM_CEPOZOF ?>">CEP</label>
										<input type="text" class="form-control input-sm cep" name="NUM_CEPOZOF" value="<?php echo $num_cepozof; ?>" id="NUM_CEPOZOF" maxlength="9" <?= $rqrNUM_CEPOZOF ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrNOM_CIDADEC ?>">Cidade</label>
										<input type="text" class="form-control input-sm" name="NOM_CIDADEC" value="<?php echo $nom_cidadec; ?>" id="NOM_CIDADEC" maxlength="40" <?= $rqrNOM_CIDADEC ?>>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label <?= $rqrCOD_ESTADOF ?>">Estado</label>
										<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect" <?= $rqrCOD_ESTADOF ?>>
											<option value=""></option>
											<option value="AC">AC</option>
											<option value="AL">AL</option>
											<option value="AM">AM</option>
											<option value="AP">AP</option>
											<option value="BA">BA</option>
											<option value="CE">CE</option>
											<option value="DF">DF</option>
											<option value="ES">ES</option>
											<option value="GO">GO</option>
											<option value="MA">MA</option>
											<option value="MG">MG</option>
											<option value="MS">MS</option>
											<option value="MT">MT</option>
											<option value="PA">PA</option>
											<option value="PB">PB</option>
											<option value="PE">PE</option>
											<option value="PI">PI</option>
											<option value="PR">PR</option>
											<option value="RJ">RJ</option>
											<option value="RN">RN</option>
											<option value="RO">RO</option>
											<option value="RR">RR</option>
											<option value="RS">RS</option>
											<option value="SC">SC</option>
											<option value="SE">SE</option>
											<option value="SP">SP</option>
											<option value="TO">TO</option>
										</select>
										<script>
											$("#formulario #COD_ESTADOF").val("<?php echo $cod_estadof; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>


								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Latitude</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="LATITUDE" id="LATITUDE" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Longitude</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="LONGITUDE" id="LONGITUDE" value="">
									</div>
								</div>


							</div>

						</fieldset>

						<?php
						if (($_SESSION["SYS_COD_SISTEMA"] == 14 || $cod_empresa == 19) && $cod_cliente != 0) {

							$sql3 = "select NOM_ENTIDAD from ENTIDADE where COD_ENTIDAD = $cod_entidad";
							$qrBuscaEntidade = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql3));
							//fnEscreve($sql3);	
							$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];
						?>

							<div class="push10"></div>


							<fieldset>
								<legend>Veículos</legend>

								<div class="row">
									<?php
									if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $cod_empresa == 19) { //Rede Duque App 
									?>

										<div class="col-md-3">

											<a href="https://adm.bunker.mk/action.do?mod=<?= fnEncode(1800) ?>&id=<?= fnEncode($cod_empresa) ?>&idC=<?= fnEncode($cod_cliente) ?>" target="_blank">&rsaquo; Veículos Excluídos</a> <br />

										</div>
									<?php
									}
									?>
									<div class="col-xs-4 col-xs-offset-1">

										<div class="row">

											<div class="col-xs-10">
												<div class="form-group">
													<input type="text" id="DES_PLACA" name="DES_PLACA" class="form-control input-hg text-center placa" placeholder="Sua placa" data-minlength="7" data-minlength-error="Formato inválido">
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-xs-2">
												<a href='javascript:void(0)' name="ADD" id="ADD" class="btn btn-info shadow" tabindex="5" disabled><span class="fal fa-plus"></span>&nbsp; Adicionar</a>
											</div>

										</div>

										<div class="push30"></div>

									</div>

									<div class="col-xs-4 col-xs-offset-5">

										<div class="row" id="placasConteudo">

											<?php
											$sql = "SELECT VEICULOS.COD_VEICULOS, 
																	VEICULOS.DES_PLACA, 
																	MARCA.COD_MARCA, 
																	VEICULOS.COD_EXTERNO,
																	MARCA.NOM_MARCA, 
																	MODELO.NOM_MODELO 
																	FROM VEICULOS 
																	LEFT JOIN MARCA ON MARCA.COD_MARCA=VEICULOS.COD_MARCA
																	LEFT JOIN MODELO ON MODELO.COD_MODELO=VEICULOS.COD_MODELO
																	WHERE COD_CLIENTE = $cod_cliente 
																	AND COD_EMPRESA = $cod_empresa";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrVeic = mysqli_fetch_assoc($arrayQuery)) {
											?>

												<div class="col-xs-8" style="font-size: 20px!important;">
													<div class="col-xs-9">
														<p class="text-muted"><span class="fal fa-car"></span>&nbsp; <?= $qrVeic['DES_PLACA'] ?></p>
													</div>
													<div class="col-xs-3 text-right">
														<a href="javascript:void(0)" onclick='excPlaca("<?= fnEncode($qrVeic['COD_VEICULOS']) ?>")'><span class="fal fa-trash text-danger" style="padding-top: 3.5px;"></span></a>
													</div>
												</div>

											<?php
											}
											?>

											<div class="col-md-3">

												<div class="push10"></div>
												<?php
												$sql = "select b.DES_PRODUTO,a.VAL_PRODUTO from plano_valor a,produtocliente b
																		where 
																		a.cod_produto=b.cod_produto and
																		a.cod_entidad = $cod_entidad group by a.COD_PRODUTO";
												//fnEscreve($sql);
												//precisa ver o group by e corrigir o select 
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
												while ($qrListaPrecos = mysqli_fetch_assoc($arrayQuery)) {
												?>

													<small><b> <?php echo $qrListaPrecos['DES_PRODUTO']; ?>:</b></small> <?php echo $qrListaPrecos['VAL_PRODUTO']; ?> <br />

												<?php
												}

												?>
											</div>


										</div>

									</div>

								</div>

							</fieldset>


						<?php
						} else if (($_SESSION["SYS_COD_SISTEMA"] == 14 || $cod_empresa == 19) && $cod_cliente == 0) {
							echo "<div class='push20'></div><div class='col-md-12 text-center text-danger'><h5>*É necessário realizar o cadastro do cliente para habilitar o cadastro de placas.</h5></div>";
						}
						?>

						<div class="push10"></div>

						<fieldset>
							<legend>Controle de Acesso</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data de Cadastro</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" value="<?php echo $dat_cadastr; ?>" name="DAT_CADASTR" id="DAT_CADASTR">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Atendente que Cadastrou</label>
										<?php

										$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $cod_usucada";
										$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));

										?>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" value="<?= @$qrUsu['NOM_USUARIO'] ?>" name="USUCADA" id="USUCADA">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Externo</label>
										<input type="text" class="form-control input-sm" name="COD_EXTERNO" value="<?php echo $cod_externo; ?>" id="COD_EXTERNO" maxlength="20">
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Chave Externa</label>
										<input type="text" class="form-control input-sm" name="KEY_EXTERNO" value="<?php echo $key_externo; ?>" id="KEY_EXTERNO" maxlength="20">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Login Usuário</label>
										<input type="text" class="form-control input-sm" name="LOG_USUARIO" id="LOG_USUARIO" value="<?php echo $log_usuario; ?>" maxlength="50" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2 text-center">
									<div class="form-group">
										<label>&nbsp;</label>
										<div class="push"></div>
										<a href="javascript:void(0)" class="btn btn-default form-control <?= $bloquSenha ?>" data-url="action.php?mod=<?php echo fnEncode(1512) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_cliente) ?>&pop=true" data-title="Alterar Senha" style="height: 35px;padding-top: 5px;"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp; Senha</a>
									</div>
								</div>

								<!-- <div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Senha</label>
                                                            <input type="password" class="form-control input-sm" name="DES_SENHAUS" id="DES_SENHAUS" maxlength="10" value="<?php echo $des_senhaus; ?>" >
															<div class="help-block with-errors"></div>
														</div>
													</div> -->

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">N° Acessos</label>
										<input type="text" class="form-control input-sm" name="NUM_TENTATI" id="NUM_TENTATI" value="<?php echo $num_tentati; ?>" maxlength="2" value="">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="push10"></div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Cadastro </label>
										<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" required>
											<option value=""></option>
											<?php
											$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' order by NOM_UNIVEND ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																	<option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Unidade de Preferência </label>
										<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND_PREF" id="COD_UNIVEND_PREF" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' order by NOM_UNIVEND ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																	<option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
																	";
											}
											?>
										</select>
										<script>
											$("#formulario #COD_UNIVEND_PREF").val("<?php echo $cod_univend_pref; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label">Acesso Múltiplo </label>
										<select data-placeholder="Selecione as unidades autorizadas" name="COD_MULTEMP[]" id="COD_MULTEMP" multiple="multiple" class="chosen-select-deselect">
											<?php
											$sql = "SELECT COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = $cod_empresa order by NOM_UNIVEND ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																	<option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
																	";
											}
											?>
										</select>

										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>


						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<?php if ($cod_cliente == 0) { ?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<?php } else { ?>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<?php } ?>

						</div>

						<a type="hidden" name="btnInativ" id="btnInativ" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1901) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_cliente) ?>&pop=true" data-title="Inativar Cliente - Motivo"></a>

						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="INATIVOU_CLI" id="INATIVOU_CLI" value="N">
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

<?php

if ($_SESSION['SYS_COD_EMPRESA'] == 2) {

?>

	<!--<a href="javascript:void(0)" class="btn-clippy">
							<div class="clippy">
							  <div class="eye left"></div>
							  <div class="eye right"></div>
							</div>
						</a>-->

<?php
}

?>

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
		$(".disabled-senha").click(function(e) {
			$.alert({
				title: "AVISO",
				content: "Seu perfil não possui acesso ao módulo de Alteração de Senha do Cliente.",
				backgroundDismiss: true,
				type: 'red',
				buttons: {
					"OK": {
						btnClass: 'btn-secondary shadow',
						action: function() {}
					}
				}
			});
		});

		$("#ALT").click(function(e) {
			e.preventDefault();
			let log_estatus = "N";

			if ($("#LOG_ESTATUS").prop("checked")) {
				log_estatus = "S";
			}

			$.alert({
				title: "Confirmação",
				content: "Alteração de dados cadastrais ferem a Lei nº 13.7099/2018, sobre <b>LGPD</b>. <br/>Essa atividade ficará <b>registrada</b> em seu login de usuário. <br/>Tem certeza que deseja continuar e assumir essa <b>responsabilidade</b>?",
				type: 'red',
				buttons: {
					"ACEITAR": {
						btnClass: 'btn-success',
						action: function() {

							if ("<?= $qrBuscaCliente['LOG_ESTATUS'] ?>" == "S" && "<?= $qrBuscaCliente['LOG_ESTATUS'] ?>" != log_estatus) {
								$("#btnInativ").click();
							} else {
								$("#formulario").submit();
							}

						}
					},
					"CANCELAR": {
						btnClass: 'btn-default',
						action: function() {}
					},
				}
			})
		});

		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//modal close
		$('.modal').on('hidden.bs.modal', function() {

			if ($('#REFRESH_CLIENTE').val() == "S") {
				var newCli = $('#NOVO_CLIENTE').val();
				window.location.href = "action.php?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=" + newCli + " ";
				$('#REFRESH_PRODUTOS').val("N");
			}

			// if ($('#REFRESH_CLIENTE').val() == "S"){

			// }

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

	});

	$("#DES_PLACA").keyup(function() {
		if (($(this).parent().hasClass('has-error') || $(this).val() == "" || $(this).val().replace('-', '').length < 7)) {
			// alert("erro");
			$("#ADD").attr("disabled", true);
		} else {
			// alert("ok");
			$("#ADD").removeAttr("disabled", false);
		}
	});

	$("#ADD").click(function() {
		if (!$("#ADD").attr('disabled')) {
			$.ajax({
				method: 'POST',
				url: 'appduque/ajxCadVeiculo.php',
				data: {
					COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
					DES_PLACA: $("#DES_PLACA").val(),
					COD_CLIENTE: "<?= fnEncode($cod_cliente) ?>",
					NUM_CGCECPF: "<?= fnEncode($num_cgcecpf) ?>"
				},
				beforeSend: function() {
					$("#placasConteudo").html('<div class="loading" style="width: 100%;"></div>');
					$("#ADD").attr("disabled", true);
				},
				success: function(data) {
					$("#placasConteudo").html(data);
					$("#DES_PLACA").val('');
					$("#REFRESH_PLACA").val('S');
				}
			});
		} else {

		}
	});

	$("body").delegate('input.placa', 'paste', function(e) {
		$(this).unmask();
	});
	$("body").delegate('input.placa', 'input', function(e) {
		$('input.placa').mask(MercoSulMaskBehavior, mercoSulOptions);
	});

	function excPlaca(cod_veiculos) {
		$.ajax({
			method: 'POST',
			url: 'appduque/ajxCadVeiculo.php?opcao=excluir',
			data: {
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
				COD_CLIENTE: "<?= fnEncode($cod_cliente) ?>",
				NUM_CGCECPF: "<?= fnEncode($num_cgcecpf) ?>",
				COD_VEICULOS: cod_veiculos
			},
			beforeSend: function() {
				$("#placasConteudo").html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#placasConteudo").html(data);
				$.alert({
					title: "AVISO",
					content: "Veículo excluído.",
					columnClass: 'col-xs-12',
					backgroundDismiss: true,
					buttons: {
						"OK": {
							btnClass: 'btn-blue shadow',
							action: function() {}
						}
					}
				});
			}
		});
	}

	var MercoSulMaskBehavior = function(val) {
			var myMask = 'SSS0A00';
			var mercosul = /([A-Za-z]{3}[0-9]{1}[A-Za-z]{1})/;
			var normal = /([A-Za-z]{3}[0-9]{2})/;
			var replaced = val.replace(/[^\w]/g, '');
			if (normal.exec(replaced)) {
				myMask = 'SSS-0000';
			} else if (mercosul.exec(replaced)) {
				myMask = 'SSS0A00';
			}
			return myMask;
		},

		mercoSulOptions = {
			onKeyPress: function(val, e, field, options) {
				field.mask(MercoSulMaskBehavior.apply({}, arguments), options);
			}
		};

	//retorno combo multiplo - master
	$("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");
	var sistemasMst = "<?php echo $cod_multemp; ?>";
	var sistemasMstArr = sistemasMst.split(',');
	//opções multiplas
	for (var i = 0; i < sistemasMstArr.length; i++) {
		$("#formulario #COD_MULTEMP option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");
	}
	$("#formulario #COD_MULTEMP").trigger("chosen:updated");

	//$(".btn-clippy").click(function(){
	//$("#msgInfo").fadeIn('fast');
	//});
</script>