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
$filtro = "";
$val_pesquisa = "";
$cod_usuario = "";
$nom_usuario = "";
$des_senhaus = "";
$log_usuario = "";
$des_emailus = "";
$log_estatus = "";
$log_inativos = "";
$log_usudev = "";
$cod_perfilcom = "";
$hor_devdias = "";
$hor_devfds = "";
$hor_entrada = "";
$num_cgcecpf = "";
$num_rgpesso = "";
$dat_nascime = "";
$cod_estaciv = "";
$cod_sexopes = "";
$num_tentati = "";
$num_telefon = "";
$num_celular = "";
$cod_externo = "";
$des_apelido = "";
$cod_tpusuario = "";
$cod_defsist = "";
$cod_indicador = "";
$Arr_COD_MULTEMP = "";
$i = 0;
$cod_multemp = "";
$Arr_COD_UNIVEND = "";
$Arr_COD_PERFILS = "";
$Arr_COD_SISTEMAS = "";
$cod_perfils = "";
$Arr_COD_USUARIOS_AGE = "";
$cod_usuarios_age = "";
$Arr_COD_USUARIOS_ATE = "";
$cod_usuarios_ate = "";
$nom_usuarioSESSION = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$sqlAgenda = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$des_sufixo = "";
$loginMontado = "";
$arrayProc = [];
$cod_erro = "";
$sqlTurno = "";
$cod_turno = "";
$qrCod = "";
$arrayInsert = [];
$sqlAgendaInsert = "";
$arrayUpdate = [];
$sqlIndicadorAdm = "";
$sqlIndicador = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$esconde = "";
$checkInativos = "";
$andInativos = "";
$mod = "";
$obriga_email = "";
$labelIndicador = "";
$unidade = "";
$andFiltro = "";
$sqlUnivend = "";
$arrUni = "";
$cod_unidades = "";
$qrUni = "";
$tipoUsuario = "";
$abaEmpresa = "";
$abaUsuario = "";
$qrListaEstCivil = "";
$qrListaSexo = "";
$qrListaTipoUsu = "";
$qrSistemasEmpresa = "";
$sistemasEmpresa = "";
$obrigaExterno = "";
$cod_cliente = "";
$qrListaUnive = "";
$disabled = "";
$andPerfil = "";
$qrListaSistemas = "";
$qrLista = "";
$qrListaEmpresas = "";
$retorno = "";
$inicio = "";
$qrListaUsuario = "";
$mostraAtivo = "";
$tem_perfil = "";
$tem_unive = "";
$tem_master = "";
$tem_usuarios_age = "";
$loginLimpo = "";
$sqlCli = "";
$qrIndicad = "";
$nom_indicad = "";
$sqlPerfil = "";
$arrPerfil = "";
$perfis = "";
$qrPerfil = "";
$content = "";


if (@$_GET["tp"] == "popup_unive") {
	include("usuariosUnidades.php");
} else {

	$conn = conntemp($cod_empresa, "");
	$adm = $connAdm->connAdm();

	$hashLocal = mt_rand();

	// definir o numero de itens por pagina
	$itens_por_pagina = 50;
	$pagina  = "1";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$request = md5(serialize($_POST));

		if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		} else {
			$_SESSION['last_request']  = $request;

			$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
			$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

			$cod_usuario = fnLimpacampoZero(@$_REQUEST['COD_USUARIO']);
			$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
			$nom_usuario = fnLimpacampo(@$_REQUEST['NOM_USUARIO']);
			// $des_senhaus = fnLimpacampo(@$_REQUEST['DES_SENHAUS']);
			$log_usuario = fnLimpacampo(@$_REQUEST['LOG_USUARIO']);
			$des_emailus = fnLimpacampo(@$_REQUEST['DES_EMAILUS']);
			//$log_estatus = fnLimpacampo(@$_REQUEST['LOG_ESTATUS']);
			if (empty(@$_REQUEST['LOG_ESTATUS'])) {
				$log_estatus = 'N';
			} else {
				$log_estatus = @$_REQUEST['LOG_ESTATUS'];
			}
			if (empty(@$_REQUEST['LOG_INATIVOS'])) {
				$log_inativos = 'N';
			} else {
				$log_inativos = @$_REQUEST['LOG_INATIVOS'];
			}
			if (empty(@$_REQUEST['LOG_USUDEV'])) {
				$log_usudev = 'N';
			} else {
				$log_usudev = 'S';
			}
			$cod_perfilcom = fnLimpacampoZero(@$_REQUEST['COD_PERFILCOM']);
			$hor_devdias = fnLimpacampoZero(@$_REQUEST['HOR_DEVDIAS']);
			$hor_devfds = fnLimpacampoZero(@$_REQUEST['HOR_DEVFDS']);
			$hor_entrada = fnLimpacampo(@$_REQUEST['HOR_ENTRADA']);
			$num_cgcecpf = fnLimpacampo(@$_REQUEST['NUM_CGCECPF']);
			$num_rgpesso = fnLimpacampo(@$_REQUEST['NUM_RGPESSO']);
			$dat_nascime = fnLimpacampo(@$_REQUEST['DAT_NASCIME']);
			$cod_estaciv = fnLimpacampoZero(@$_REQUEST['COD_ESTACIV']);
			$cod_sexopes = fnLimpacampoZero(@$_REQUEST['COD_SEXOPES']);
			//if (empty(fnLimpacampo(@$_REQUEST['NUM_TENTATI']))) {$num_tentati=0;}else{$num_tentati=nLimpacampo(@$_REQUEST['NUM_TENTATI']);}
			$num_tentati = fnLimpacampo(fnLimpaCampoZero(@$_REQUEST['NUM_TENTATI']));
			$num_telefon = fnLimpacampo(@$_REQUEST['NUM_TELEFON']);
			$num_celular = fnLimpacampo(@$_REQUEST['NUM_CELULAR']);
			$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
			$des_apelido = fnLimpacampo(@$_REQUEST['DES_APELIDO']);
			$cod_tpusuario = fnLimpacampoZero(@$_REQUEST['COD_TPUSUARIO']);
			$cod_defsist = fnLimpacampoZero(@$_REQUEST['COD_DEFSIST']);
			$cod_indicador = fnLimpacampoZero(@$_REQUEST['COD_INDICA']);

			// fnEscreve($log_usudev);

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

			if ($cod_multemp == "0") {
				$cod_multemp = $cod_empresa;
			}

			//array das unidades de venda
			if (isset($_POST['COD_UNIVEND'])) {
				$Arr_COD_UNIVEND = @$_POST['COD_UNIVEND'];
				//print_r($Arr_COD_MULTEMP);			 

				for ($i = 0; $i < count($Arr_COD_UNIVEND); $i++) {
					@$cod_univend = @$cod_univend . $Arr_COD_UNIVEND[$i] . ",";
				}

				$cod_univend = substr($cod_univend, 0, -1);
			} else {
				$cod_univend = "0";
			}


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

			//array dusuarios da agenda
			if (isset($_POST['COD_USUARIOS_AGE'])) {
				$Arr_COD_USUARIOS_AGE = @$_POST['COD_USUARIOS_AGE'];
				//print_r($Arr_COD_USUARIOS_AGE);			 

				for ($i = 0; $i < count($Arr_COD_USUARIOS_AGE); $i++) {
					$cod_usuarios_age = $cod_usuarios_age . $Arr_COD_USUARIOS_AGE[$i] . ",";
				}

				$cod_usuarios_age = substr($cod_usuarios_age, 0, -1);
			} else {
				$cod_usuarios_age = "0";
			}

			//array dusuarios do atendimento
			if (isset($_POST['COD_USUARIOS_ATE'])) {
				$Arr_COD_USUARIOS_ATE = @$_POST['COD_USUARIOS_ATE'];
				//print_r($Arr_COD_USUARIOS_ATE);			 

				for ($i = 0; $i < count($Arr_COD_USUARIOS_ATE); $i++) {
					$cod_usuarios_ate = $cod_usuarios_ate . $Arr_COD_USUARIOS_ATE[$i] . ",";
				}

				$cod_usuarios_ate = substr($cod_usuarios_ate, 0, -1);
			} else {
				$cod_usuarios_ate = "0";
			}

			//fnEscreve($cod_perfils);

			$nom_usuarioSESSION = $_SESSION["SYS_NOM_USUARIO"];
			$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$MODULO = @$_GET['mod'];
			$COD_MODULO = fndecode(@$_GET['mod']);


			$sqlAgenda = "";
			$opcao = @$_REQUEST['opcao'];
			$hHabilitado = @$_REQUEST['hHabilitado'];
			$hashForm = @$_REQUEST['hashForm'];

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
			$des_sufixo = fnLimpacampo(@$_REQUEST['DES_SUFIXO']);

			if ($log_usuario == "") {
				$loginMontado = "";
			} else {
				$loginMontado = $log_usuario . "." . $des_sufixo;
			}

			$cod_univend = str_replace("Array", "", $cod_univend);

			if ($opcao != '') {

				$sql = "CALL SP_ALTERA_USUARIOS (
					 '" . $cod_usuario . "', 
					 '" . $cod_empresa . "', 
					 '" . $nom_usuario . "', 
					 '" . $loginMontado . "', 
					 '" . $des_emailus . "', 
					 '" . $log_usudev . "', 
					 '" . $cod_perfilcom . "', 
					 '" . $hor_devdias . "', 
					 '" . $hor_devfds . "', 
					 '" . $hor_entrada . "', 
					 '" . $cod_usucada . "', 
					 '" . $num_cgcecpf . "', 
					 '" . $log_estatus . "', 
					 '" . $num_rgpesso . "', 
					 '" . $dat_nascime . "', 
					 '" . $cod_estaciv . "', 
					 '" . $cod_sexopes . "', 
					 '" . $num_telefon . "', 
					 '" . $num_celular . "', 				 
					 '" . $cod_externo . "',				 
					 '" . $cod_tpusuario . "',    
					 '" . $cod_perfils . "',    
					 '" . $num_tentati . "',    
					 '" . $cod_defsist . "',    
					 '" . $cod_multemp . "',    
					 '" . $cod_univend . "',    
					 '" . $cod_usuarios_ate . "',    
					 '" . $des_apelido . "',    
					 '" . $opcao . "'   
					) ";

				// fnEscreve($sql);
				//fnTestesql(connTemp($cod_empresa,''),$sql);
				$arrayProc = mysqli_query($connAdm->connAdm(), $sql);


				if (!$arrayProc) {

					$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
				}


				$sqlTurno = "";
				$sqlTurno .= "DELETE FROM usuarios_turno WHERE COD_USUARIO = $cod_usuario  AND COD_EMPRESA=$cod_empresa;";
				if (is_array(@$_POST["COD_TURNO"])) {
					foreach (@$_POST["COD_TURNO"] as $cod_turno) {
						$sqlTurno .= "INSERT INTO usuarios_turno(
												COD_TURNO,
												COD_EMPRESA,
												COD_USUARIO,
												COD_USUCADA
											) VALUES(
												0$cod_turno,
												0$cod_empresa,
												0$cod_usuario,
												0$cod_usucada
											);";
					}
					//fnEscreve($sqlTurno);
				}
				mysqli_multi_query($connAdm->connAdm(), trim($sqlTurno));



				//mensagem de retorno
				switch ($opcao) {
					case 'CAD':

						$sql = "SELECT MAX(COD_USUARIO) AS COD_USUARIO FROM USUARIOS WHERE COD_EMPRESA = $cod_empresa";
						$qrCod = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), trim($sql)));

						$cod_usuario = $qrCod['COD_USUARIO'];

						if ($_SESSION["SYS_COD_SISTEMA"] == 16) {

							$sqlAgenda = "INSERT INTO USUARIOS_AGENDA(
													COD_EMPRESA,
													COD_USUARIO,
													COD_USUARIOS_AGE,
													COD_USUCADA
													) VALUES(
													$cod_empresa,
													$cod_usuario,
													'$cod_usuarios_age',
													$cod_usucada
													)";
							// fnEscreve($sqlAgenda);
							$arrayInsert = mysqli_query($connAdm->connAdm(), trim($sqlAgenda));

							if (!$arrayInsert) {

								$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAgenda, $nom_usuarioSESSION);
							}
						}

						if ($cod_erro == 0 || $cod_erro ==  "") {
							$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						} else {
							$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
						}

						break;
					case 'ALT':

						if ($_SESSION["SYS_COD_SISTEMA"] == 16) {

							$sqlAgenda .= "DELETE FROM USUARIOS_AGENDA WHERE COD_USUARIO = $cod_usuario;";

							$sqlAgendaInsert .= "INSERT INTO USUARIOS_AGENDA(
													COD_EMPRESA,
													COD_USUARIO,
													COD_USUARIOS_AGE,
													COD_USUCADA
													) VALUES(
													$cod_empresa,
													$cod_usuario,
													'$cod_usuarios_age',
													$cod_usucada
													);";
							//echo($sqlAgenda);
							mysqli_query($connAdm->connAdm(), trim($sqlAgenda));

							$arrayUpdate = mysqli_query($connAdm->connAdm(), $sqlAgendaInsert);

							if (!$arrayUpdate) {

								$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAgendaInsert, $nom_usuarioSESSION);
							}

							//echo $cod_erro;

							if ($cod_erro == 0 || $cod_erro ==  "") {
								$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
							} else {
								$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
							}
						}

						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
						break;
				}

				if ($opcao == 'CAD' || $opcao == 'ALT') {
					$sqlIndicadorAdm = "UPDATE USUARIOS SET COD_INDICADOR = $cod_indicador WHERE COD_USUARIO = $cod_usuario";
					mysqli_query($connAdm->connAdm(), trim($sqlIndicadorAdm));

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
				}
				if ($opcao == 'CAD' || $opcao == 'ALT') {
					$sqlIndicador = "UPDATE USUARIOS SET COD_INDICADOR = $cod_indicador WHERE COD_USUARIO = $cod_usuario";
					mysqli_query(conntemp($cod_empresa, ''), trim($sqlIndicador));
				}

				if ($cod_erro == 0 || $cod_erro == "") {
					$msgTipo = 'alert-success';
				} else {
					$msgTipo = 'alert-danger';
				}
			}
		}
	}

	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
		//busca dados da empresa
		$cod_empresa = fnDecode(@$_GET['id']);
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DES_SUFIXO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaEmpresa)) {
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$des_sufixo = $qrBuscaEmpresa['DES_SUFIXO'];
		}
	} else {
		$cod_empresa = 0;
		//fnEscreve('entrou else');
	}

	if ($val_pesquisa != '') {
		$esconde = " ";
	} else {
		$esconde = "display: none;";
	}

	if ($log_inativos == "S") {
		$checkInativos = "checked";
		$andInativos = "AND USUARIOS.LOG_ESTATUS = 'N'";
		//DAT_EXCLUSA IS NOT NULL
	} else {
		$checkInativos = "";
		$andInativos = "AND USUARIOS.LOG_ESTATUS = 'S'";
	}

	$mod = fnLimpaCampo(fnDecode(@$_GET['mod']));

	// fnEscreve($mod);

	if ($mod == 1017) {
		$obriga_email = "required";
	} else {
		$obriga_email = "";
	}

	if ($_SESSION["SYS_COD_SISTEMA"] == 136) {
		$labelIndicador = "Indicador Associado";
	} else {
		$labelIndicador = "Cliente Associado";
	}

	// fnEscreve(fnDecode("jbjBvPJIg2VsvcWX6NNSmQ¢¢"));

	//fnMostraForm();
	//fnEscreve($filtro);
	//fnEscreve($val_pesquisa);

	switch ($cod_empresa) {
		case 311:
			$unidade = "Secretarias";
			break;
		case 136:
			$unidade = "Gabinete";
			break;
		case 224:
			$unidade = "Indústria";
			break;
		default:
			$unidade = "Unidade de Venda";
	}


	if ($filtro != '') {

		if ($filtro == "usuarios.COD_EXTERNO") {
			$andFiltro = " AND $filtro IN($val_pesquisa) ";
		} else if ($filtro != "COD_UNIVEND") {

			$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
		} else {

			$sqlUnivend = "SELECT COD_UNIVEND FROM UNIDADEVENDA WHERE NOM_FANTASI LIKE '%$val_pesquisa%'";

			// fnEscreve($sqlUnivend);

			$arrUni = mysqli_query($connAdm->connAdm(), $sqlUnivend);

			$cod_unidades = "";

			while ($qrUni = mysqli_fetch_assoc($arrUni)) {

				$cod_unidades .= $qrUni['COD_UNIVEND'] . ",";
			}

			$cod_unidades = rtrim(ltrim($cod_unidades, ","), ",");

			$andFiltro = "";

			if ($cod_unidades != '') {

				$andFiltro = "AND USUARIOS.COD_UNIVEND IN($cod_unidades)";
			}
		}
	} else {

		$andFiltro = " ";
	}

	if ($cod_empresa == 332) {
		$tipoUsuario = "16,15,9,3,6,1";
	}

?>

	<div class="push30"></div>

	<div class="row">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>

					<?php
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

					<?php
					//menu superior - empresas
					$abaEmpresa = 1017;

					//menu abas
					include "abasEmpresas.php";

					?>


					<?php

					$abaUsuario = fnDecode(@$_GET['mod']);
					//echo $abaUsuario;

					//se não for sistema de campanhas
					if ($_SESSION["SYS_COD_SISTEMA"] != 20) {

						echo ('<div class="push20"></div>');
						include "abasUsuariosEmpresa.php";
					}
					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">


									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Usuário Ativo</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" checked>
												<span></span>
											</label>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_USUARIO" id="COD_USUARIO" value="">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome do Usuário</label>
											<input type="text" class="form-control input-sm" name="NOM_USUARIO" id="NOM_USUARIO" maxlength="50" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Login Usuário</label>
											<input type="text" class="form-control input-sm" name="LOG_USUARIO" id="LOG_USUARIO" maxlength="50" data-error="Campo obrigatório">
											<div class="help-block with-errors logUsu"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Controle Login (sufixo)</label>
											<h4>.<?php echo $des_sufixo; ?></h4>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Nick Name</label>
											<input type="text" class="form-control input-sm" name="DES_APELIDO" id="DES_APELIDO" maxlength="50">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">CNPJ/CPF</label>
											<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">RG</label>
											<input type="text" class="form-control input-sm" name="NUM_RGPESSO" id="NUM_RGPESSO" maxlength="15" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Principal</label>
											<input type="text" class="form-control input-sm sp_celphones" name="NUM_TELEFON" id="NUM_TELEFON" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Telefone Celular</label>
											<input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="20">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $obriga_email ?>">e-Mail</label>
											<input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" maxlength="100" <?= $obriga_email ?>>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data de Nascimento</label>
											<input type="text" class="form-control input-sm data" name="DAT_NASCIME" id="DAT_NASCIME" maxlength="10">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Estado Civil</label>
											<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
												<?php
												$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaEstCivil['COD_ESTACIV'] . "'>" . $qrListaEstCivil['DES_ESTACIV'] . "</option> 
													";
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Sexo</label>
											<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
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
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">

											<label for="inputName" class="control-label required">Tipo de Usuário</label>
											<select data-placeholder="Selecione o tipo de usuário" name="COD_TPUSUARIO" id="COD_TPUSUARIO" class="chosen-select-deselect requiredChk" required>
												<option value="">&nbsp;</option>
												<?php
												if (($_SESSION["SYS_COD_SISTEMA"] == "21") or ($_SESSION["SYS_COD_MASTER"] == "2")) {
													if ($_SESSION["SYS_COD_MASTER"] == "2") {
														$sql = "select COD_TPUSUARIO, DES_TPUSUARIO from tipousuario WHERE COD_TPUSUARIO IN ($tipoUsuario,17,18) order by des_tpusuario ";
													} else {
														$sql = "select COD_TPUSUARIO, DES_TPUSUARIO from tipousuario WHERE COD_TPUSUARIO IN (17,18) order by des_tpusuario ";
													}
												} else {
													$sql = "select COD_TPUSUARIO, DES_TPUSUARIO from tipousuario WHERE COD_TPUSUARIO IN ($tipoUsuario) order by des_tpusuario ";
												}
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaTipoUsu = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaTipoUsu['COD_TPUSUARIO'] . "'>" . $qrListaTipoUsu['DES_TPUSUARIO'] . "</option> 
													";
												}
												?>
											</select>
											<?php // echo $sql; 
											?>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>


							</fieldset>

							<div class="push10"></div>

							<fieldset>
								<legend>Controle de Acesso</legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data de Cadastro</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_CADASTR" id="DAT_CADASTR" value="">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Sistema Default</label>
											<select data-placeholder="Selecione o tipo de usuário" name="COD_DEFSIST" id="COD_DEFSIST" class="chosen-select-deselect requiredChk" required>
												<option value="">&nbsp;</option>
												<?php
												$sql = "SELECT COD_SISTEMAS FROM EMPRESAS WHERE COD_EMPRESA = '" . $cod_empresa . "' ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												$qrSistemasEmpresa = mysqli_fetch_assoc($arrayQuery);
												$sistemasEmpresa = $qrSistemasEmpresa['COD_SISTEMAS'];

												$sql = "";
												$sql = "SELECT COD_SISTEMA,DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA IN(" . $sistemasEmpresa . ") ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaTipoUsu = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaTipoUsu['COD_SISTEMA'] . "'>" . $qrListaTipoUsu['DES_SISTEMA'] . "</option> 
													";
												}
												?>
											</select>
											<?php
											//fnEscreve($sql); 
											//fnEscreve($sistemasEmpresa);
											?>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<?php

									if (fnDecode(@$_GET['mod']) == 1185) {
										$obrigaExterno = "required";
									} else {
										$obrigaExterno = " ";
									}
									?>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label <?php echo $obrigaExterno; ?> ">Código Externo</label>
											<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="20" value="" <?php echo $obrigaExterno; ?>>
										</div>
									</div>

									<div class="col-md-2 text-center">
										<div class="form-group">
											<label>&nbsp;</label>
											<div class="push"></div>
											<a href="javascript:void(0)" class="btn btn-default addBox form-control" disabled data-title="Alterar Senha" style="height: 35px;padding-top: 5px;" id="btnSenha"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp; Senha</a>
										</div>
									</div>

									<!-- <div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Senha</label>
										<input type="password" class="form-control input-sm" name="DES_SENHAUS" id="DES_SENHAUS" maxlength="10" value="">
										<div class="help-block with-errors"></div>
										</div>
									</div> -->

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">N° Acessos</label>
											<input type="text" class="form-control input-sm" name="NUM_TENTATI" id="NUM_TENTATI" maxlength="2" value="">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<label for="inputName" class="control-label"><?= $labelIndicador ?></label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBuscaInd" id="btnBuscaInd" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1664) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($cod_cliente) ?>&pop=true" data-title="Busca Indicador"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
											</span>
											<input type="text" name="NOM_INDICA" id="NOM_INDICA" value="" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
											<input type="hidden" name="COD_INDICA" id="COD_INDICA" value="">
											<input type="hidden" name="COD_INDICA_ENC" id="COD_INDICA_ENC" value="">
										</div>
										<div class="help-block with-errors alert-clie"></div>
										<a class="btn btn-default btn-sm" onClick="acessaTelaCliente();" style="padding: 0 2px ; font-size: 10px;">acessar tela do cliente</a>&nbsp;
									</div>

									<!-- <div class="col-md-2">
										<div class="form-group">
										<label for="inputName" class="control-label">Data da Indicação</label>
										<input type="text" class="form-control input-sm leitura" name="DAT_INDICA" id="DAT_INDICA" value="" maxlength="50" readonly="readonly" >
										<div class="help-block with-errors"></div>
										</div>
									</div> -->

								</div>

								<div class="push10"></div>

								<?php if ($_SESSION["SYS_COD_SISTEMA"] != 16 && $_SESSION["SYS_COD_SISTEMA"] != 19 || $cod_empresa == 136 || $cod_empresa == 224 || $cod_empresa == 311) { ?>

									<div class="row">

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required"><?= $unidade ?></label>

												<select data-placeholder="Selecione uma ou mais unidades" name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
													<?php
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S' ORDER BY NOM_FANTASI ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
													while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {

														if ($qrListaUnive['LOG_ESTATUS'] == 'N') {
															$disabled = "disabled";
														} else {
															$disabled = " ";
														}

														echo "
															<option value='" . $qrListaUnive['COD_UNIVEND'] . "'" . $disabled . ">" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
														";
													}
													?>
												</select>
												<?php //fnEscreve($sql); 
												?>
												<div class="help-block with-errors"></div>

												<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
												<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

											</div>
										</div>

										<div class="col-md-1"></div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label <?php echo $obrigaExterno; ?> ">Agenda Gmail (e-Mail)</label>
												<input type="text" class="form-control input-sm" name="AGENDA_GMAIL" id="AGENDA_GMAIL" maxlength="20" value="">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label <?php echo $obrigaExterno; ?> ">Agenda Gmail (senha)</label>
												<input type="password" class="form-control input-sm" name="AGENDA_GMAIL" id="AGENDA_GMAIL" maxlength="20" value="">
											</div>
										</div>




									</div>

									<div class="push10"></div>

								<?php } else { ?>

									<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="0">

								<?php } ?>

								<div class="row">

									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Perfil</label>

											<select data-placeholder="Selecione um perfil de acesso" name="COD_PERFILS[]" id="COD_PERFILS" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<?php

												$sql = "SELECT COD_PERFILS,DES_PERFILS,PERFIL.COD_SISTEMA,PERFIL.COD_EMPRESA,COD_MODULOS, DES_ABREVIA 
														FROM PERFIL,SISTEMAS WHERE 
														PERFIL.COD_SISTEMA=SISTEMAS.COD_SISTEMA AND 
														PERFIL.COD_SISTEMA IN($sistemasEmpresa ) 
														AND  PERFIL.COD_EMPRESA IS NULL 
														UNION 
														SELECT COD_PERFILS,DES_PERFILS,PERFIL.COD_SISTEMA,PERFIL.COD_EMPRESA,COD_MODULOS, DES_ABREVIA 
														FROM PERFIL,SISTEMAS WHERE 
														PERFIL.COD_SISTEMA=SISTEMAS.COD_SISTEMA 
														AND PERFIL.COD_EMPRESA =  $cod_empresa 
														$andPerfil";

												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaSistemas = mysqli_fetch_assoc($arrayQuery)) {

													if ($cod_empresa == 311 && $qrListaSistemas['COD_PERFILS'] == 1149) {
														continue;
													}

													echo "
															<option value='" . $qrListaSistemas['COD_PERFILS'] . "'>" . ucfirst($qrListaSistemas['DES_ABREVIA']) . ' ' . $qrListaSistemas['DES_PERFILS'] . "</option> 
														";
												}
												?>
											</select>
											<?php //fnEscreve($sql); 
											?>
											<div class="help-block with-errors"></div>
										</div>
									</div>


								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-12">
										<div class="form-group">

											<label for="inputName" class="control-label">Turnos</label>
											<select multiple data-placeholder="Selecione o turno" name="COD_TURNO[]" id="COD_TURNO" class="chosen-select-deselect">
												<?php
												$sql = "select COD_TURNO, NOM_TURNO from turnostrabalho WHERE COD_EMPRESA=$cod_empresa AND COD_EXCLUSA=0 order by NOM_TURNO ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaTipoUsu = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaTipoUsu['COD_TURNO'] . "'>" . $qrListaTipoUsu['NOM_TURNO'] . "</option> 
													";
												}
												?>
											</select>

											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<?php
								if ($_SESSION["SYS_COD_SISTEMA"] == 16) {
								?>

									<div class="row">

										<div class="col-md-12">
											<div class="form-group">
												<label for="inputName" class="control-label">Usuários da Agenda</label>
												<select data-placeholder="Selecione os usuários" name="COD_USUARIOS_AGE[]" id="COD_USUARIOS_AGE" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
													<option value="9999">Todos os usuários</option>
													<?php

													$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
																			where COD_EMPRESA = $cod_empresa AND usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";

													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

													while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
														echo "
															<option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
														";
													}
													?>
												</select>
												<div class="help-block with-errors"></div>
												<!-- <a class="btn btn-default btn-sm" id="iAll2" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp; -->
												<a class="btn btn-default btn-sm" id="iNone2" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
											</div>

										</div>

									</div>

									<div class="push10"></div>

									<div class="row">

										<div class="col-md-12">
											<div class="form-group">
												<label for="inputName" class="control-label">Usuários do Atendimento</label>
												<select data-placeholder="Selecione os usuários" name="COD_USUARIOS_ATE[]" id="COD_USUARIOS_ATE" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
													<option value="9999">Todos os usuários</option>
													<?php

													$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
																			where COD_EMPRESA = $cod_empresa AND usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";

													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

													while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
														echo "
															<option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
														";
													}
													?>
												</select>
												<div class="help-block with-errors"></div>
												<!-- <a class="btn btn-default btn-sm" id="iAll3" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp; -->
												<a class="btn btn-default btn-sm" id="iNone3" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
											</div>

										</div>

									</div>

									<div class="push10"></div>

								<?php
								} else {
								?>
									<input type="hidden" name="COD_USUARIOS_AGE[]" id="COD_USUARIOS_AGE">
								<?php
								}
								?>

							</fieldset>

							<?php
							if ($_SESSION["SYS_COD_MASTER"] == "2" && $cod_empresa != 136 || $_SESSION["SYS_COD_MASTER"] == "3") {
							?>

								<div class="push10"></div>

								<fieldset style="background: #F4F6F6;">
									<legend>Controle Adm</legend>

									<?php
									//fnEscreve($_SESSION["SYS_COD_MASTER"]);
									//se sistema de cliente, não mostra combo
									if ($_SESSION["SYS_LOG_MULTEMPRESA"] == "S") {
									?>
										<div class="row">

											<div class="col-md-9">
												<div class="form-group">
													<label for="inputName" class="control-label required">Empresas de acesso (para uso Marka)</label>

													<select data-placeholder="Selecione uma empresa para acesso" name="COD_MULTEMP[]" id="COD_MULTEMP" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
														<?php
														//se sistema marka
														if ($_SESSION["SYS_COD_MASTER"] == "2") {
															$sql = ' SELECT * FROM EMPRESAS where cod_empresa <> 1 ';
														} else {
															$sql = "SELECT * FROM EMPRESAS WHERE COD_MASTER IN (1," . $_SESSION["SYS_COD_MASTER"] . "," . $_SESSION["SYS_COD_MULTEMP"] . ") ";
														}
														$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
														while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
															echo "
																<option value='" . $qrListaEmpresas['COD_EMPRESA'] . "'>" . ucfirst($qrListaEmpresas['NOM_FANTASI']) . "</option> 
															";
														}
														?>
													</select>
													<?php //fnEscreve($sql); 
													?>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-3">
												<div class="form-group">

													<label for="inputName" class="control-label">Perfil de Comunicação</label>
													<select data-placeholder="Selecione o perfil" name="COD_PERFILCOM" id="COD_PERFILCOM" class="chosen-select-deselect">
														<option value=""></option>
													</select>
													<div class="help-block with-errors"></div>
												</div>
											</div>

										</div>

										<div class="push10"></div>
									<?php
									} else {
									?>
										<input type="hidden" name="COD_MULTEMP[]" id="COD_MULTEMP" value="<?= $cod_empresa ?>">
									<?php
									}
									?>

									<?php
									if ($_SESSION["SYS_COD_MASTER"] == "2") {
									?>

										<div class="row">

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Usuário é DEV</label>
													<div class="push5"></div>
													<label class="switch">
														<input type="checkbox" name="LOG_USUDEV" id="LOG_USUDEV" class="switch" value="S">
														<span></span>
													</label>
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Horas DEV(dia)</label>
													<input type="text" class="form-control input-sm money" name="HOR_DEVDIAS" id="HOR_DEVDIAS" value="">
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Horas DEV(fds)</label>
													<input type="text" class="form-control input-sm money" name="HOR_DEVFDS" id="HOR_DEVFDS" value="">
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Hora Entrada</label>
													<input type="time" class="form-control input-sm" name="HOR_ENTRADA" id="HOR_ENTRADA" value="">
												</div>
											</div>
										</div>

										<div class="push10"></div>

									<?php
									} else {
									?>

										<input type="hidden" name="LOG_USUDEV" id="LOG_USUDEV" value="">
										<input type="hidden" name="HOR_DEVDIAS" id="HOR_DEVDIAS" value="">
										<input type="hidden" name="HOR_DEVFDS" id="HOR_DEVFDS" value="">
										<input type="hidden" name="HOR_ENTRADA" id="HOR_ENTRADA" value="">

									<?php
									}
									?>

								</fieldset>

							<?php
							} else {
							?>

							<?php
							}
							?>


							<div class="push10"></div>
							<hr>
							<?php
							if ($cod_empresa != 136 && $cod_empresa != 224 && $cod_empresa != 274) {
							?>
								<div class="form-group text-left col-lg-4">
									<button type="button" class="btn btn-primary addBox" data-url="action.php?mod=<?= @$_GET["mod"] ?>&id=<?= @$_GET["id"] ?>&tp=popup_unive&pop=true&tipoUsuario=<?= $tipoUsuario ?>" data-title="Autorizar Unidades de Venda em Lote"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Autorizar Unidades em Lote</button>
								</div>
							<?php
							} else if ($cod_empresa == 274) {
							?>
								<div class="form-group text-left col-lg-4" id="divVendedor" style="display: none;">
									<button type="button" class="btn btn-primary addBox" id="btnVendedor" data-url="" data-title=""><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Cadastro de Vendedor</button>
								</div>
							<?php
							}
							?>
							<div class="form-group text-right col-lg-12">
								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
							</div>

							<input type="hidden" name="REFRESH_USU" id="REFRESH_USU" value="N">
							<input type="hidden" name="AND_FILTRO" id="AND_FILTRO" value="<?= $andFiltro ?>">
							<input type="hidden" name="AND_INATIVOS" id="AND_INATIVOS" value="<?= $andInativos ?>">
							<input type="hidden" name="DES_SUFIXO" id="DES_SUFIXO" value="<?php echo $des_sufixo; ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

							<div class="push5"></div>

						</form>

						<div class="push30"></div>

						<div class="row">
							<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">
								<?php
								if ($cod_empresa != 136 && $cod_empresa != 224) {
								?>

									<div class="col-md">
										<div class="form-group">
											<label for="inputName" class="control-label">Trazer Somente Inativos?</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_INATIVOS" id="LOG_INATIVOS" class="switch" value="S" <?= $checkInativos ?>>
												<span></span>
											</label>
										</div>
										<script>
											$("#LOG_INATIVOS").change(function() {
												$("#formLista2").submit();
											});
										</script>
									</div>
								<?php
								}
								?>
								<div class="col-xs-4 col-xs-offset-4">
									<div class="input-group activeItem">
										<div class="input-group-btn search-panel">
											<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
												<span id="search_concept">Sem filtro</span>&nbsp;
												<span class="fal fa-angle-down"></span>
											</button>
											<ul class="dropdown-menu" role="menu">
												<li class="divisor"><a href="#">Sem filtro</a></li>
												<!-- <li class="divider"></li> -->
												<li><a href="#usuarios.NOM_USUARIO">Nome do usuário</a></li>
												<li><a href="#usuarios.COD_EXTERNO">Código externo</a></li>
												<li><a href="#usuarios.LOG_USUARIO">Login</a></li>
												<li><a href="#COD_UNIVEND">Unidade</a></li>
											</ul>
										</div>
										<input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">
										<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
										<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
											<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										</div>
										<div class="input-group-btn">
											<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										</div>
									</div>
								</div>
								<input type="hidden" name="REFRESH_USU" id="REFRESH_USU" value="N">
								<input type="hidden" name="AND_FILTRO" id="AND_FILTRO" value="<?= $andFiltro ?>">
								<input type="hidden" name="AND_INATIVOS" id="AND_INATIVOS" value="<?= $andInativos ?>">
								<input type="hidden" name="DES_SUFIXO" id="DES_SUFIXO" value="<?php echo $des_sufixo; ?>">
								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
								<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">


							</form>

						</div>

						<div class="push30"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">



								<table class="table table-bordered table-striped table-hover tablesorter buscavel">
									<thead>
										<tr>
											<th class="{sorter:false}" width="40"></th>
											<th>Código</th>
											<th>Cód.Externo</th>
											<th>Unidade</th>
											<th>Nome do Usuário</th>
											<th>Login</th>
											<th>e-Mail</th>
											<th>Tipo de Usuário</th>
											<th>Perfis</th>
											<th>Ativo</th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php


										$sql = "
															SELECT 
																count(*) as CONTADOR
															FROM
																usuarios
															WHERE
																	usuarios.COD_EMPRESA = $cod_empresa 
																	AND usuarios.COD_TPUSUARIO IN ($tipoUsuario)
																	$andInativos
																	$andFiltro
															ORDER BY usuarios.NOM_USUARIO";

										//fnEscreve($sql);
										$retorno = mysqli_query($connAdm->connAdm(), $sql);
										$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

										$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT USUARIOS.*, UV.NOM_FANTASI, TIPOUSUARIO.*, USUARIOS_AGENDA.COD_USUARIOS_AGE,
																(SELECT GROUP_CONCAT(COD_TURNO) FROM usuarios_turno WHERE usuarios_turno.COD_USUARIO=USUARIOS.COD_USUARIO) COD_TURNO
														FROM USUARIOS 
														LEFT JOIN TIPOUSUARIO ON USUARIOS.COD_TPUSUARIO = TIPOUSUARIO.COD_TPUSUARIO
														LEFT JOIN USUARIOS_AGENDA ON USUARIOS_AGENDA.COD_USUARIO = USUARIOS.COD_USUARIO
														LEFT JOIN UNIDADEVENDA UV ON USUARIOS.COD_UNIVEND = UV.COD_UNIVEND
														WHERE USUARIOS.COD_EMPRESA = $cod_empresa 
														AND USUARIOS.COD_TPUSUARIO IN ($tipoUsuario)
														$andInativos 
														$andFiltro 
														ORDER BY USUARIOS.NOM_USUARIO LIMIT $inicio,$itens_por_pagina";

										//fnEscreve($sql);
										//--and log_usuario like '%arcio.fabian.mcoisas%'

										//fnEscreve($sql);
										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

										$count = 0;

										while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery)) {

											$count++;

											if ($qrListaUsuario['LOG_ESTATUS'] == 'S') {
												$mostraAtivo = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraAtivo = '';
											}

											if (!empty($qrListaUsuario['COD_PERFILS'])) {
												$tem_perfil = "sim";
											} else {
												$tem_perfil = "nao";
											}

											if (!empty($qrListaUsuario['COD_UNIVEND'])) {
												$tem_unive = "sim";
											} else {
												$tem_unive = "nao";
											}

											if (!empty($qrListaUsuario['COD_MULTEMP'])) {
												$tem_master = "sim";
											} else {
												$tem_master = "nao";
											}

											if (!empty($qrListaUsuario['COD_USUARIOS_AGE'])) {
												$tem_usuarios_age = "sim";
											} else {
												$tem_usuarios_age = "nao";
											}

											$loginLimpo =  str_replace('.' . $des_sufixo, '', $qrListaUsuario['LOG_USUARIO']);
											if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
												// fnConsole($qrListaUsuario['NOM_USUARIO'] . " " . fnDecode($qrListaUsuario['DES_SENHAUS']));
											}

											if ($qrListaUsuario['COD_INDICADOR'] != '') {
												$sqlCli = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_CLIENTE = $qrListaUsuario[COD_INDICADOR]";
												$qrIndicad = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlCli));
												$nom_indicad = @$qrIndicad['NOM_CLIENTE'];
											} else {
												$nom_indicad = "";
											}

											$sqlPerfil = "SELECT DES_PERFILS FROM PERFIL WHERE COD_PERFILS IN ($qrListaUsuario[COD_PERFILS])";
											$arrPerfil = mysqli_query($connAdm->connAdm(), $sqlPerfil);
											$perfis = "";
											while (@$qrPerfil = mysqli_fetch_assoc($arrPerfil)) {
												$perfis .= $qrPerfil['DES_PERFILS'] . ", ";
											}

											$perfis = rtrim(trim($perfis), ',');

											echo "
																<tr>
																  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
																  <td>" . $qrListaUsuario['COD_USUARIO'] . "</td>
																  <td>" . $qrListaUsuario['COD_EXTERNO'] . "</td>
																  <td>" . $qrListaUsuario['NOM_FANTASI'] . "</td>
																  <td>
																  	<a href='#' class='editable' 
																		data-type='text' 
																		data-title='Editar Nome de Usuário'
																		data-pk='COD_USUARIO' 
																		data-name='NOM_USUARIO'
																		data-id='" . $qrListaUsuario['COD_USUARIO'] . "'
																		data-codempresa='" . $cod_empresa . "' >" . $qrListaUsuario['NOM_USUARIO'] . "
															  		</a>
																  </td>
																  <td>" . $qrListaUsuario['LOG_USUARIO'] . "</td>
																  <td>" . $qrListaUsuario['DES_EMAILUS'] . "</td>
																  <td>" . $qrListaUsuario['DES_TPUSUARIO'] . " / " . $qrListaUsuario['NUM_CELULAR'] . "</td>
																  <td><small>" . $perfis . "</small></td>
																  <td align='center'>" . $mostraAtivo . "</td>
																</tr>
																<input type='hidden' id='ret_COD_USUARIO_" . $count . "' value='" . $qrListaUsuario['COD_USUARIO'] . "'>
																<input type='hidden' id='ret_COD_USUARIO_ENC_" . $count . "' value='" . fnEncode($qrListaUsuario['COD_USUARIO']) . "'>
																<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrListaUsuario['COD_EMPRESA'] . "'>
																<input type='hidden' id='ret_DAT_CADASTR_" . $count . "' value='" . fnFormatDateTime($qrListaUsuario['DAT_CADASTR']) . "'>
																<input type='hidden' id='ret_NOM_USUARIO_" . $count . "' value='" . $qrListaUsuario['NOM_USUARIO'] . "'>
																<input type='hidden' id='ret_LOG_USUARIO_" . $count . "' value='" . $loginLimpo . "'>
																<input type='hidden' id='ret_LOG_ESTATUS_" . $count . "' value='" . $qrListaUsuario['LOG_ESTATUS'] . "'>
																<input type='hidden' id='ret_LOG_USUDEV_" . $count . "' value='" . $qrListaUsuario['LOG_USUDEV'] . "'>
																<input type='hidden' id='ret_DES_EMAILUS_" . $count . "' value='" . $qrListaUsuario['DES_EMAILUS'] . "'>
																<input type='hidden' id='ret_COD_PERFILCOM_" . $count . "' value='" . $qrListaUsuario['COD_PERFILCOM'] . "'>
																<input type='hidden' id='ret_HOR_DEVDIAS_" . $count . "' value='" . $qrListaUsuario['HOR_DEVDIAS'] . "'>
																<input type='hidden' id='ret_HOR_DEVFDS_" . $count . "' value='" . $qrListaUsuario['HOR_DEVFDS'] . "'>
																<input type='hidden' id='ret_HOR_ENTRADA_" . $count . "' value='" . $qrListaUsuario['HOR_ENTRADA'] . "'>
																<input type='hidden' id='ret_NUM_CGCECPF_" . $count . "' value='" . $qrListaUsuario['NUM_CGCECPF'] . "'>
																<input type='hidden' id='ret_NUM_RGPESSO_" . $count . "' value='" . $qrListaUsuario['NUM_RGPESSO'] . "'>
																<input type='hidden' id='ret_DAT_NASCIME_" . $count . "' value='" . $qrListaUsuario['DAT_NASCIME'] . "'>
																<input type='hidden' id='ret_COD_ESTACIV_" . $count . "' value='" . $qrListaUsuario['COD_ESTACIV'] . "'>
																<input type='hidden' id='ret_COD_SEXOPES_" . $count . "' value='" . $qrListaUsuario['COD_SEXOPES'] . "'>
																<input type='hidden' id='ret_NUM_TENTATI_" . $count . "' value='" . $qrListaUsuario['NUM_TENTATI'] . "'>
																<input type='hidden' id='ret_NUM_TELEFON_" . $count . "' value='" . $qrListaUsuario['NUM_TELEFON'] . "'>
																<input type='hidden' id='ret_NUM_CELULAR_" . $count . "' value='" . $qrListaUsuario['NUM_CELULAR'] . "'>
																<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrListaUsuario['COD_EXTERNO'] . "'>
																<input type='hidden' id='ret_COD_TPUSUARIO_" . $count . "' value='" . $qrListaUsuario['COD_TPUSUARIO'] . "'>
																<input type='hidden' id='ret_COD_PERFILS_" . $count . "' value='" . $qrListaUsuario['COD_PERFILS'] . "'>
																<input type='hidden' id='ret_COD_USUARIOS_AGE_" . $count . "' value='" . $qrListaUsuario['COD_USUARIOS_AGE'] . "'>
																<input type='hidden' id='ret_COD_USUARIOS_ATE_" . $count . "' value='" . $qrListaUsuario['COD_USUARIOS_ATE'] . "'>
																<input type='hidden' id='ret_COD_MULTEMP_" . $count . "' value='" . $qrListaUsuario['COD_MULTEMP'] . "'>
																<input type='hidden' id='ret_COD_DEFSIST_" . $count . "' value='" . $qrListaUsuario['COD_DEFSIST'] . "'>
																<input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . $qrListaUsuario['COD_UNIVEND'] . "'>
																<input type='hidden' id='ret_TEM_PERFIL_" . $count . "' value='" . $tem_perfil . "'>
																<input type='hidden' id='ret_TEM_USUARIOS_AGE_" . $count . "' value='" . $tem_usuarios_age . "'>
																<input type='hidden' id='ret_TEM_MASTER_" . $count . "' value='" . $tem_master . "'>
																<input type='hidden' id='ret_TEM_UNIVE_" . $count . "' value='" . $tem_unive . "'>
																<input type='hidden' id='ret_COD_INDICA_" . $count . "' value='" . $qrListaUsuario['COD_INDICADOR'] . "'>
																<input type='hidden' id='ret_COD_INDICA_ENC_" . $count . "' value='" . fnEncode($qrListaUsuario['COD_INDICADOR']) . "'>
																<input type='hidden' id='ret_NOM_INDICA_" . $count . "' value='" . $nom_indicad . "'>
																<input type='hidden' id='ret_COD_TURNO_" . $count . "' value='" . $qrListaUsuario['COD_TURNO'] . "'>
																<input type='hidden' id='ret_DES_APELIDO_" . $count . "' value='" . $qrListaUsuario['DES_APELIDO'] . "'>
																";
										}

										?>

									</tbody>

									<tfoot>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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


	<script type="text/javascript">
		function fnEditable() {
			$('.editable').editable({
				emptytext: '_______________',
				url: 'ajxAltUsuario.php',
				ajaxOptions: {
					type: 'post'
				},
				params: function(params) {
					params.codempresa = $(this).data('codempresa');
					params.value_id = $(this).data('id');
					return params;
				},
				success: function(data) {
					console.log(data);
				}
			});
		}
		$(function() {
			fnEditable();
		});

		//Barra de pesquisa essentials ------------------------------------------------------
		$(document).ready(function(e) {
			var value = $('#INPUT').val().toLowerCase().trim();
			if (value) {
				$('#CLEARDIV').show();
			} else {
				$('#CLEARDIV').hide();
			}
			$('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#", "");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
			});

			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
			});

			$('#CLEAR').click(function() {
				$('#INPUT').val('');
				$('#INPUT').focus();
				$('#CLEARDIV').hide();
				if ("<?= $filtro ?>" != "") {
					location.reload();
				} else {
					var value = $('#INPUT').val().toLowerCase().trim();
					if (value) {
						$('#CLEARDIV').show();
					} else {
						$('#CLEARDIV').hide();
					}
					$(".buscavel tr").each(function(index) {
						if (!index) return;
						$(this).find("td").each(function() {
							var id = $(this).text().toLowerCase().trim();
							var sem_registro = (id.indexOf(value) == -1);
							$(this).closest('tr').toggle(!sem_registro);
							return sem_registro;
						});
					});
				}
			});

			$('#SEARCH').click(function() {
				buscaRegistro($('#INPUT'));
			});


		});

		function buscaRegistro(el) {
			var filtro = $('#search_concept').text().toLowerCase();

			if (filtro == "sem filtro") {
				var value = $(el).val().toLowerCase().trim();
				if (value) {
					$('#CLEARDIV').show();
				} else {
					$('#CLEARDIV').hide();
				}
				$(".buscavel tr").each(function(index) {
					if (!index) return;
					$(this).find("td").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('tr').toggle(!sem_registro);
						return sem_registro;
					});
				});
			}
		}

		//-----------------------------------------------------------------------------------

		$(document).ready(function() {

			$('#LOG_USUARIO').on('blur', function() {
				let log_usuario = $('#LOG_USUARIO').val() + '.<?= $des_sufixo ?>';
				$.ajax({
					type: "POST",
					url: "ajxUsuarios.do?opcao=pesquisar&id=<?php echo fnEncode($cod_empresa); ?>",
					data: {
						LOG_USUARIO: log_usuario
					},
					success: function(data) {
						console.log(data);
						if (data == '0') {
							$('.logUsu').text('');
						} else {
							$('.logUsu')
								.text('Já existe um usuário cadastrado com esse login!')
								.css('color', 'red');
							$('#LOG_USUARIO').val('');
						}
					},
					error: function() {
						$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
					}
				});
			});

			//modal close
			$('.modal').on('hidden.bs.modal', function() {
				if ($("#REFRESH_USU").val() == 'S') {
					window.location.replace("<?= $cmdPage ?>");
				}
				$(".alert-clie").html("");
			});

			$(".addBox").click(function(e) {
				if ($(this).attr("disabled")) {
					e.stopPropagation();
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

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			// mascaraCpfCnpj($("#formulario #NUM_CGCECPF"));

			var numPaginas = <?php echo $numPaginas; ?>;
			if (numPaginas != 0) {
				carregarPaginacao(numPaginas);
			}

			$('#iAll').on('click', function(e) {
				e.preventDefault();
				$('#COD_UNIVEND option').prop('selected', true).trigger('chosen:updated');
			});

			$('#iNone').on('click', function(e) {
				e.preventDefault();
				$("#COD_UNIVEND option:selected").removeAttr("selected").trigger('chosen:updated');
			});

			$('#iAll2').on('click', function(e) {
				e.preventDefault();
				$('#COD_USUARIOS_AGE option').prop('selected', true).trigger('chosen:updated');
			});

			$('#iNone2').on('click', function(e) {
				e.preventDefault();
				$("#COD_USUARIOS_AGE option:selected").removeAttr("selected").trigger('chosen:updated');
			});

			$('#iAll3').on('click', function(e) {
				e.preventDefault();
				$('#COD_USUARIOS_ATE option').prop('selected', true).trigger('chosen:updated');
			});

			$('#iNone3').on('click', function(e) {
				e.preventDefault();
				$("#COD_USUARIOS_ATE option:selected").removeAttr("selected").trigger('chosen:updated');
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
										url: "ajxUsuarios.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=exportar&tpUsu=<?php echo $tipoUsuario; ?>&nomeRel=" + nome,
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

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxUsuarios.do?opcao=paginar&mod=<?php echo @$_GET['mod']; ?>&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&tpUsu=<?php echo $tipoUsuario; ?>&des_sufixo=<?php echo $des_sufixo; ?>",
				data: $('#formLista2').serialize(),
				beforeSend: function() {
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#relatorioConteudo").html(data);
					$(".tablesorter").trigger("updateAll");
					console.log(data);

					fnEditable();
				},
				error: function() {
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});
		}

		function retornaForm(index) {
			$("#formulario #COD_USUARIO").val($("#ret_COD_USUARIO_" + index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val());
			$("#formulario #DAT_CADASTR").val($("#ret_DAT_CADASTR_" + index).val());
			$("#formulario #DES_APELIDO").val($("#ret_DES_APELIDO_" + index).val());
			$("#formulario #NOM_USUARIO").val($("#ret_NOM_USUARIO_" + index).val());
			$("#btnSenha").attr("data-url", "action.php?mod=<?= fnEncode(1516) ?>&id=<?= fnEncode($cod_empresa) ?>&idu=" + $("#ret_COD_USUARIO_ENC_" + index).val() + "&pop=true").removeAttr('disabled');
			$("#formulario #LOG_USUARIO").val($("#ret_LOG_USUARIO_" + index).val());
			if ($("#ret_LOG_ESTATUS_" + index).val() == 'S') {
				$('#formulario #LOG_ESTATUS').prop('checked', true);
			} else {
				$('#formulario #LOG_ESTATUS').prop('checked', false);
			}
			<?php
			if ($_SESSION["SYS_COD_MASTER"] != "2") {
			?>

				$('#formulario #LOG_USUDEV').val($("#ret_LOG_USUDEV_" + index).val());

			<?php
			} else {
			?>

				if ($("#ret_LOG_USUDEV_" + index).val() == 'S') {
					$('#formulario #LOG_USUDEV').prop('checked', true);
				} else {
					$('#formulario #LOG_USUDEV').prop('checked', false);
				}

			<?php
			}
			?>

			$("#formulario #DES_EMAILUS").val($("#ret_DES_EMAILUS_" + index).val());
			$("#formulario #HOR_DEVDIAS").val($("#ret_HOR_DEVDIAS_" + index).val());
			$("#formulario #HOR_DEVFDS").val($("#ret_HOR_DEVFDS_" + index).val());
			$("#formulario #HOR_ENTRADA").val($("#ret_HOR_ENTRADA_" + index).val());
			$("#formulario #NUM_CGCECPF").val($("#ret_NUM_CGCECPF_" + index).val());
			$("#formulario #NUM_RGPESSO").val($("#ret_NUM_RGPESSO_" + index).val());
			$("#formulario #DAT_NASCIME").val($("#ret_DAT_NASCIME_" + index).val());
			$("#formulario #NUM_TENTATI").val($("#ret_NUM_TENTATI_" + index).val());
			$("#formulario #NUM_TELEFON").val($("#ret_NUM_TELEFON_" + index).val());
			$("#formulario #NUM_CELULAR").val($("#ret_NUM_CELULAR_" + index).val());
			$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
			$("#formulario #COD_INDICA").val($("#ret_COD_INDICA_" + index).val());
			$("#formulario #COD_INDICA_ENC").val($("#ret_COD_INDICA_ENC_" + index).val());
			$("#formulario #NOM_INDICA").val($("#ret_NOM_INDICA_" + index).val());
			$("#formulario #COD_PERFILCOM").val($("#ret_COD_PERFILCOM_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_ESTACIV").val($("#ret_COD_ESTACIV_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_SEXOPES").val($("#ret_COD_SEXOPES_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_TPUSUARIO").val($("#ret_COD_TPUSUARIO_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_DEFSIST").val($("#ret_COD_DEFSIST_" + index).val()).trigger("chosen:updated");

			var cod_turno = $("#ret_COD_TURNO_" + index).val();
			//console.log(cod_turno);
			if (cod_turno != "" && cod_turno !== undefined) {
				$("#formulario #COD_TURNO").val(cod_turno.split(",")).trigger("chosen:updated");
			}


			var usuarios_age = $('#ret_COD_USUARIOS_AGE_' + index).val();
			if (usuarios_age != 0) {
				//retorno combo multiplo - USUARIOS_AGE
				$("#formulario #COD_USUARIOS_AGE").val('').trigger("chosen:updated");

				var sistemasUni = usuarios_age;
				var sistemasUniArr = sistemasUni.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
					$("#formulario #COD_USUARIOS_AGE option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
				}
				$("#formulario #COD_USUARIOS_AGE").trigger("chosen:updated");
			}

			var usuarios_ate = $('#ret_COD_USUARIOS_ATE_' + index).val();
			if (usuarios_ate != 0) {
				//retorno combo multiplo - USUARIOS_ATE
				$("#formulario #COD_USUARIOS_ATE").val('').trigger("chosen:updated");

				var sistemasUni = usuarios_ate;
				var sistemasUniArr = sistemasUni.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
					$("#formulario #COD_USUARIOS_ATE option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
				}
				$("#formulario #COD_USUARIOS_ATE").trigger("chosen:updated");
			}

			//retorno combo multiplo - perfil
			$("#formulario #COD_PERFILS").val('').trigger("chosen:updated");
			if ($("#ret_TEM_PERFIL_" + index).val() == "sim") {

				var sistemasCli = $("#ret_COD_PERFILS_" + index).val();
				var sistemasCliArr = sistemasCli.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasCliArr.length; i++) {
					$("#formulario #COD_PERFILS option[value=" + sistemasCliArr[i] + "]").prop("selected", "true");
				}
				$("#formulario #COD_PERFILS").trigger("chosen:updated");
			} else {
				$("#formulario #COD_PERFILS").val('').trigger("chosen:updated");
			}

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

			<?php
			//se sistema de cliente, não mostra combo
			if ($_SESSION["SYS_LOG_MULTEMPRESA"] == "S") {
			?>
				//retorno combo multiplo - master
				$("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");
				if ($("#ret_TEM_MASTER_" + index).val() == "sim") {
					//alert("entrou...");
					var sistemasMst = $("#ret_COD_MULTEMP_" + index).val();
					var sistemasMstArr = sistemasMst.split(',');
					//opções multiplas
					for (var i = 0; i < sistemasMstArr.length; i++) {
						$("#formulario #COD_MULTEMP option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");
					}
					$("#formulario #COD_MULTEMP").trigger("chosen:updated");
				} else {
					$("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");
				}
			<?php
			} else {
			?>
				$("#formulario #COD_MULTEMP").val($("#ret_COD_MULTEMP_" + index).val());
			<?php
			}
			?>

			<?php
			if ($cod_empresa == 274) {
			?>
				$("#btnVendedor").attr("data-url", "action.php?mod=<?= fnEncode(1837) ?>&id=<?= fnEncode($cod_empresa) ?>&idu=" + $("#ret_COD_USUARIO_ENC_" + index).val() + "&pop=true")
					.attr("data-title", "Cadastro de Vendedor - " + $("#ret_NOM_USUARIO_" + index).val());
				$("#divVendedor").fadeIn('fast');
			<?php
			}
			?>

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}

		function acessaTelaCliente() {
			$(".alert-clie").html("");
			if ($("#COD_INDICA").val() == "" || $("#COD_INDICA").val() == "0") {
				$(".alert-clie").html("<span class='text-danger'>Escolha um cliente!</span>");
				return false;
			}
			var idC = $("#COD_INDICA_ENC").val();
			window.open('http://adm.bunker.mk/action.do?mod=<?= fnEncode(1024) ?>&id=<?= @$_GET["id"] ?>&idC=' + idC, '_blank');
		}
	</script>

<?php
}
?>