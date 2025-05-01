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
$cod_cadastr = "";
$nom_univend = "";
$nom_respons = "";
$num_cgcecpf = "";
$log_estatus = "";
$log_delivery = "";
$log_especial = "";
$log_cobranca = "";
$log_ativohs = "";
$log_unipref = "";
$num_whatsapp = "";
$des_horatend = "";
$num_escrica = "";
$nom_fantasi = "";
$num_telefon = "";
$num_celular = "";
$des_enderec = "";
$num_enderec = "";
$des_complem = "";
$des_bairroc = "";
$num_cepozof = "";
$nom_cidadec = "";
$cod_estadof = "";
$cod_bandeira = "";
$cod_tpunive = "";
$cod_propriedade = "";
$nom_email = "";
$cod_externo = "";
$cod_fantasi = "";
$des_img = "";
$lat = "";
$lng = "";
$comis_vendedor = "";
$comis_parceiro = "";
$log_status = "";
$log_token = "";
$cod_integradora = "";
$cod_versaointegra = "";
$num_decimais = "";
$tip_retorno = "";
$cod_dataws = "";
$log_cadvendedor = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$cod_sistemas = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaControleUnidade = "";
$controle_unidade = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_master = "";
$texto_envio = "";
$email = "";
$sqlBand = "";
$sqlUpdt = "";
$arrayUpdate = [];
$sqlUpdate = "";
$esconde = "";
$popUp = "";
$abaEmpresa = "";
$abaUniv = "";
$abaUsuario = "";
$qrListaTpUnidade = "";
$qrListaGrTrabalho = "";
$qrListaProp = "";
$qrListaIntegradora = "";
$qrIntegracao = "";
$qrListaTipoData = "";
$andFiltro = "";
$qrListaUniVendas = "";
$mostraAtivo = "";
$mostraAtivoDelivery = "";
$mostraAtivoCobranca = "";
$tdCobranca = "";
$mostraAtivoGeo = "";
$sqlUni = "";
$arrayQueryUni = [];
$qrControleUnidade = "";
$mostraLicenca = "";
$content = "";


$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);


		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_cadastr = '1';
		$nom_univend = fnLimpacampo(@$_REQUEST['NOM_UNIVEND']);
		$nom_respons = fnLimpacampo(@$_REQUEST['NOM_RESPONS']);
		$num_cgcecpf = fnLimpacampo(@$_REQUEST['NUM_CGCECPF']);
		//$log_estatus = fnLimpacampo(@$_REQUEST['LOG_ESTATUS']);
		if (empty(@$_REQUEST['LOG_ESTATUS'])) {
			$log_estatus = 'N';
		} else {
			$log_estatus = @$_REQUEST['LOG_ESTATUS'];
		}
		if (empty(@$_REQUEST['LOG_DELIVERY'])) {
			$log_delivery = 'N';
		} else {
			$log_delivery = @$_REQUEST['LOG_DELIVERY'];
		}
		if (empty(@$_REQUEST['LOG_ESPECIAL'])) {
			$log_especial = 'N';
		} else {
			$log_especial = @$_REQUEST['LOG_ESPECIAL'];
		}
		if (empty(@$_REQUEST['LOG_COBRANCA'])) {
			$log_cobranca = 'N';
		} else {
			$log_cobranca = @$_REQUEST['LOG_COBRANCA'];
		}
		if (empty(@$_REQUEST['LOG_ATIVOHS'])) {
			$log_ativohs = 'N';
		} else {
			$log_ativohs = @$_REQUEST['LOG_ATIVOHS'];
		}
		if (empty(@$_REQUEST['LOG_UNIPREF'])) {
			$log_unipref = 'N';
		} else {
			$log_unipref = @$_REQUEST['LOG_UNIPREF'];
		}
		if (empty(@$_GET['idU'])) {
			$cod_univend = fnLimpacampoZero(@$_REQUEST['COD_UNIVEND']);
		} else {
			$cod_univend = fnDecode(@$_GET['idU']);
		}
		$num_whatsapp = fnLimpacampo(@$_REQUEST['NUM_WHATSAPP']);
		$des_horatend = fnLimpacampo(@$_REQUEST['DES_HORATEND']);
		$num_escrica = fnLimpacampo(@$_REQUEST['NUM_ESCRICA']);
		$nom_fantasi = fnLimpacampo(@$_REQUEST['NOM_FANTASI']);
		$num_telefon = fnLimpacampo(@$_REQUEST['NUM_TELEFON']);
		$num_celular = fnLimpacampo(@$_REQUEST['NUM_CELULAR']);
		$des_enderec = fnLimpacampo(@$_REQUEST['DES_ENDEREC']);
		$num_enderec = fnLimpacampo(@$_REQUEST['NUM_ENDEREC']);
		$des_complem = fnLimpacampo(@$_REQUEST['DES_COMPLEM']);
		$des_bairroc = fnLimpacampo(@$_REQUEST['DES_BAIRROC']);
		$num_cepozof = fnLimpacampo(@$_REQUEST['NUM_CEPOZOF']);
		$nom_cidadec = fnLimpacampo(@$_REQUEST['NOM_CIDADEC']);
		$cod_estadof = fnLimpacampo(@$_REQUEST['COD_ESTADOF']);
		$cod_bandeira = fnLimpacampo(@$_REQUEST['COD_BANDEIRA']);
		$cod_tpunive = fnLimpacampoZero(@$_REQUEST['COD_TPUNIVE']);
		$cod_propriedade = fnLimpacampoZero(@$_REQUEST['COD_PROPRIEDADE']);
		$nom_email = fnLimpacampo(@$_REQUEST['NOM_EMAIL']);
		$cod_grupotr = fnLimpacampoZero(@$_REQUEST['COD_GRUPOTR']);
		$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
		$cod_fantasi = fnLimpacampo(@$_REQUEST['COD_FANTASI']);
		$cod_tiporeg = fnLimpacampo(@$_REQUEST['COD_TIPOREG']);
		$des_img = fnLimpacampo(@$_REQUEST['DES_IMG']);
		$lat = fnLimpacampoZero(@$_REQUEST['lat']);
		$lng = fnLimpacampoZero(@$_REQUEST['lng']);

		$comis_vendedor = fnLimpaCampo(fnValorSql(@$_REQUEST['COMIS_VENDEDOR'], 2));
		$comis_parceiro = fnLimpaCampo(fnValorSql(@$_REQUEST['COMIS_PARCEIRO'], 2));

		//controlde de licenças por unidade
		//rone
		if (empty(@$_REQUEST['LOG_STATUS'])) {
			$log_status = 'N';
		} else {
			$log_status = @$_REQUEST['LOG_STATUS'];
		}
		if (empty(@$_REQUEST['LOG_TOKEN'])) {
			$log_token = 'N';
		} else {
			$log_token = @$_REQUEST['LOG_TOKEN'];
		}
		$cod_integradora = fnLimpacampoZero(@$_REQUEST['COD_INTEGRADORA']);
		$cod_versaointegra = fnLimpacampoZero(@$_REQUEST['COD_VERSAOINTEGRA']);
		$num_decimais = fnLimpacampoZero(@$_REQUEST['NUM_DECIMAIS']);
		$tip_retorno = fnLimpacampoZero(@$_REQUEST['TIP_RETORNO']);
		$cod_dataws = fnLimpacampoZero(@$_REQUEST['COD_DATAWS']);
		$log_cadvendedor = fnLimpacampoZero(@$_REQUEST['LOG_CADVENDEDOR']);

		/*
		fnEscreve($log_status);
		fnEscreve($cod_integradora);
		fnEscreve($cod_versaointegra);
		fnEscreve($num_decimais);
		fnEscreve($tip_retorno);
		fnEscreve($cod_dataws);
		fnEscreve($log_cadvendedor);
		*/

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		//fnEscreve($cod_sistemas);
		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_UNIDADEVENDA (
			'" . $cod_univend . "', 
			'" . $cod_cadastr . "', 
			'" . $nom_univend . "', 
			'" . $nom_respons . "', 
			'" . $num_cgcecpf . "', 
			'" . $log_estatus . "', 
			'" . $num_escrica . "', 
			'" . $nom_fantasi . "', 
			'" . $num_telefon . "', 
			'" . $num_celular . "', 
			'" . $num_whatsapp . "', 
			'" . $des_enderec . "', 
			'" . $des_horatend . "', 
			'" . $num_enderec . "', 
			'" . $des_complem . "', 
			'" . $des_bairroc . "', 				 
			'" . $num_cepozof . "',				 
			'" . $nom_cidadec . "',    
			'" . $cod_estadof . "',    
			'" . $cod_tpunive . "',    
			'" . $cod_propriedade . "',    
			'" . $nom_email . "',    
			'" . $cod_empresa . "',    
			'" . $cod_grupotr . "',    
			'" . $cod_externo . "',    
			'" . $cod_fantasi . "',    
			'" . $cod_tiporeg . "', 
			'" . $log_ativohs . "',    
			'" . $log_delivery . "',    
			'" . $log_especial . "',    
			'" . $log_cobranca . "',    
			'" . $des_img . "',    
			'" . $lat . "',    
			'" . $lng . "',
			'" . $comis_vendedor . "',
			'" . $comis_parceiro . "',
			'" . $log_token . "',    
			'" . $opcao . "'    
		) ";

			// echo($num_cepozof);
			// echo(trim($num_cepozof));
			//  fnEscreve($sql);
			// fnTestesql($connAdm->connAdm(),$sql);
			$arrayProc = mysqli_query($adm, trim($sql));

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			}

			$sql = "SELECT COUNT(COD_UNIVENDA) CONTROLE_UNIDADE FROM unidades_parametro WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVENDA = $cod_univend ";
			//fnEscreve($sql);
			$arrayQuery =  mysqli_query($conn, $sql);
			$qrBuscaControleUnidade = mysqli_fetch_assoc($arrayQuery);
			$controle_unidade = $qrBuscaControleUnidade['CONTROLE_UNIDADE'];
			//fnEscreve($controle_unidade);	
			//fnEscreve($log_status);

			//se tem regra para unidade
			if ($controle_unidade > 0) {

				//altera	
				$sql = "UPDATE unidades_parametro 
				SET 
				COD_USUALT = " . $_SESSION["SYS_COD_USUARIO"] . ", 
				DAT_ALTERAC = now(),
				COD_INTEGRADORA = $cod_integradora, 
				COD_VERSAOINTEGRA = $cod_versaointegra,
				NUM_DECIMAIS = $num_decimais, 
				TIP_RETORNO = $tip_retorno, 
				COD_DATAWS = $cod_dataws,
				LOG_CADVENDEDOR = $log_cadvendedor, 
				LOG_STATUS = '$log_status'
				WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVENDA = $cod_univend ";

				//fnEscreve($sql);
				$arrayQuery =  mysqli_query($conn, $sql);
			} else {

				//cadastra	
				if ($log_status == "S") {

					$sql = "INSERT INTO unidades_parametro
					(
					 COD_EMPRESA, 
					 COD_UNIVENDA, 
					 COD_USUCADA,  
					 COD_INTEGRADORA, 
					 TIP_RETORNO,					 
					 COD_VERSAOINTEGRA, 
					 NUM_DECIMAIS, 
					 COD_DATAWS, 
					 LOG_CADVENDEDOR, 
					 LOG_STATUS
					 ) 
					VALUES 
					(
					$cod_empresa, 
					$cod_univend, 
					" . $_SESSION["SYS_COD_USUARIO"] . ", 
					$cod_integradora, 
					$tip_retorno,
					$cod_versaointegra,
					$num_decimais,
					$cod_dataws,
					$log_cadvendedor,
					'$log_status'
					); ";

					//fnEscreve($sql);
					$arrayQuery =  mysqli_query($conn, $sql);
				}
			}


			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "SELECT NOM_FANTASI, COD_MASTER FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
					//fnEscreve($sql);
					$arrayQuery = mysqli_query($adm, $sql);
					$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

					if (isset($arrayQuery)) {
						$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
						$cod_master = $qrBuscaEmpresa['COD_MASTER'];
					}

					if ($cod_master == 3) {

						include 'externo/email/envio_sac.php';

						// fnEscreve('envio');
						$texto_envio = "					
				<h3 style='font-size: 18px;'>Nova Unidade Cadastrada</h3>
				<div style='clear: both; height: 5px;'/>
				<span style='font-size: 14px;'>
				Empresa: <b>" . $nom_empresa . "</b>  <div style='clear: both; height: 8px;'/>
				<span style='font-size: 14px;'>Unidade: <b>" . $nom_fantasi . "</b></span>
				<div style='clear: both; height: 5px;'/>";

						// fnEscreve($texto_envio);

						// $email['email1']='ricardoaugusto6693@gmail.com';
						$email = [];
						$email['email1'] = 'margareth@markafidelizacao.com.br';

						fnsacmail(
							$email,
							'Suporte Marka',
							"<html>" . $texto_envio . "</html>",
							"Nova Unidade Cadastrada",
							'Bunker',
							$connAdm->connAdm(),
							connTemp('3', ""),
							'3'
						);
					}

					$cod_univend = "(SELECT MAX(COD_UNIVEND) FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa)";

					// salvar bandeira

					$sqlBand = "UPDATE UNIDADEVENDA SET COD_BANDEIRA = $cod_bandeira 
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_UNIVEND = $cod_univend";
					mysqli_query($adm, $sqlBand);

					////////////////////////////////////////////////////////////////

					if ($log_unipref == 'S') {


						$sqlUpdt = "UPDATE UNIDADEVENDA
								SET LOG_UNIPREF = 'N'
								WHERE COD_EMPRESA = $cod_empresa;

								UPDATE UNIDADEVENDA
								SET LOG_UNIPREF = 'S'
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_UNIVEND = $cod_univend;";

						$arrayUpdate = mysqli_multi_query($adm, $sqlUpdt);

						if (!$arrayUpdate) {

							$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdt, $nom_usuario);
						}
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':

					// salvar bandeira

					$sqlBand = "UPDATE UNIDADEVENDA SET COD_BANDEIRA = $cod_bandeira 
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_UNIVEND = $cod_univend";
					mysqli_query($adm, $sqlBand);

					////////////////////////////////////////////////////////////////

					if ($log_unipref == 'S') {

						$sqlUpdate = "UPDATE UNIDADEVENDA
								SET LOG_UNIPREF = 'N'
								WHERE COD_EMPRESA = $cod_empresa;
								UPDATE UNIDADEVENDA
								SET LOG_UNIPREF = 'S'
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_UNIVEND = $cod_univend;";

						$arrayUpdate = mysqli_multi_query($adm, trim($sqlUpdate));

						if (!$arrayUpdate) {

							$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
						}

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

if ($val_pesquisa != '' && $val_pesquisa != 0) {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}

//fnEscreve($qrBuscaEmpresa['COD_MASTER']);

//zera variáveis bloco controle
$log_status = "";
$cod_integradora = "";
$cod_versaointegra = "";
$num_decimais = "";
$tip_retorno = "";
$cod_dataws = "";
$log_cadvendedor = "";

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

				<div class="push10"></div>

				<?php
				//menu superior - empresas
				if ($popUp != "true") {

					//aba default
					$abaEmpresa = 1023;

					//menu abas
					include "abasEmpresas.php";
				}
				?>

				<div class="push30"></div>

				<?php

				if (@$_GET['popUp'] != "true") {

					$abaUniv = fnDecode(@$_GET['mod']);
					//echo $abaUsuario;
					include "abasUnidadesEmpresa.php";
				}
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) { ?>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Unidade Ativa</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S">
												<span></span>
											</label>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								<?php
								} else {
								?>
									<div class="col-md-2">
										<div class="disabledBlock"></div>
										<div class="form-group">
											<label for="inputName" class="control-label">Unidade Ativa</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S">
												<span></span>
											</label>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								<?php
								}
								?>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Mostrar Unidade no Hot Site / APP</label>
										<div class="push5"></div>
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_ATIVOHS" id="LOG_ATIVOHS" class="switch" value="S">
											<span></span>
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Possui Delivery</label>
										<div class="push5"></div>
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_DELIVERY" id="LOG_DELIVERY" class="switch" value="S">
											<span></span>
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Controle Especial</label>
										<div class="push5"></div>
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_ESPECIAL" id="LOG_ESPECIAL" class="switch" value="S">
											<span></span>
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) { ?>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Cobrança Ativa</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_COBRANCA" id="LOG_COBRANCA" class="switch" value="S">
												<span></span>
											</label>
										</div>
										<div class="help-block with-errors"></div>
									</div>

								<?php } else { ?>

									<input type="hidden" name="LOG_COBRANCA" id="LOG_COBRANCA" value="">

								<?php } ?>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Unidade Preferencial</label>
										<div class="push5"></div>
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_UNIPREF" id="LOG_UNIPREF" class="switch" value="S">
											<span></span>
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Ativar Token de Resgate</label>
										<div class="push5"></div>
										<input type="hidden" name="TOUR_LOG_TOKEN" id="TOUR_LOG_TOKEN">
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_TOKEN" id="LOG_TOKEN" class="switch" value="S">
											<span></span>
										</label>
									</div>
									<div class="help-block with-errors"></div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_UNIVEND" id="COD_UNIVEND" value="">
									</div>

								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo da Unidade</label>
										<select data-placeholder="Selecione o tipo da unidade" name="COD_TPUNIVE" id="COD_TPUNIVE" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											//opções para empresa do blockchain

											if (($_SESSION["SYS_COD_SISTEMA"] == "21") or ($_SESSION["SYS_COD_MASTER"] == "2")) {
												$sql = "select COD_TPUNIVE, NOM_TPUNIVE from tpunidadevenda WHERE cod_tpunive in (6,7,8,9) ORDER BY NOM_TPUNIVE";
											} else {
												$sql = "select COD_TPUNIVE, NOM_TPUNIVE from tpunidadevenda WHERE cod_tpunive not in (6,7,8,9) ORDER BY NOM_TPUNIVE";
											}

											$arrayQuery = mysqli_query($adm, $sql);

											while ($qrListaTpUnidade = mysqli_fetch_assoc($arrayQuery)) {
												echo "
												<option value='" . $qrListaTpUnidade['COD_TPUNIVE'] . "'>" . $qrListaTpUnidade['NOM_TPUNIVE'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Trabalho</label>
										<select data-placeholder="Selecione o grupo de trabalho" name="COD_GRUPOTR" id="COD_GRUPOTR" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "select COD_GRUPOTR, DES_GRUPOTR from grupotrabalho where COD_EMPRESA = '" . $cod_empresa . "' ORDER BY DES_GRUPOTR";
											$arrayQuery = mysqli_query($adm, $sql);

											while ($qrListaGrTrabalho = mysqli_fetch_assoc($arrayQuery)) {
												echo "
												<option value='" . $qrListaGrTrabalho['COD_GRUPOTR'] . "'>" . $qrListaGrTrabalho['DES_GRUPOTR'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo Propriedade</label>
										<select data-placeholder="Selecione o tipo da prop" name="COD_PROPRIEDADE" id="COD_PROPRIEDADE" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "select COD_PROPRIEDADE, DES_PROPRIEDADE from tppropriedade ORDER BY DES_PROPRIEDADE";
											$arrayQuery = mysqli_query($adm, $sql);

											while ($qrListaProp = mysqli_fetch_assoc($arrayQuery)) {
												echo "
												<option value='" . $qrListaProp['COD_PROPRIEDADE'] . "'>" . $qrListaProp['DES_PROPRIEDADE'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Fantasia</label>
										<input type="text" class="form-control input-sm" name="COD_FANTASI" id="COD_FANTASI" maxlength="3">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Bandeira da Unidade</label>
										<select data-placeholder="Selecione a bandeira" name="COD_BANDEIRA" id="COD_BANDEIRA" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "SELECT COD_TIPO, DES_TIPO from TIPO_UNIDADE ORDER BY DES_TIPO";
											$arrayQuery = mysqli_query($adm, $sql);

											while ($qrListaTpUnidade = mysqli_fetch_assoc($arrayQuery)) {
												echo "
												<option value='" . $qrListaTpUnidade['COD_TIPO'] . "'>" . $qrListaTpUnidade['DES_TIPO'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Externo</label>
										<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="18" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome da Unidade</label>
										<input type="text" class="form-control input-sm" name="NOM_UNIVEND" id="NOM_UNIVEND" maxlength="100" value="" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome Fantasia</label>
										<input type="text" class="form-control input-sm" name="NOM_FANTASI" id="NOM_FANTASI" maxlength="249" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">CNPJ/CPF</label>
										<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Inscrição Estadual</label>
										<input type="text" class="form-control input-sm" name="NUM_ESCRICA" id="NUM_ESCRICA" maxlength="20" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<?php
							if ($cod_empresa == 274) {
							?>
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Comissão de Vendedor %</label>
											<input type="text" class="form-control input-sm text-center money" name="COMIS_VENDEDOR" id="COMIS_VENDEDOR" value="" maxlength="10">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Comissão de Parceiros %</label>
											<input type="text" class="form-control input-sm text-center money" name="COMIS_PARCEIRO" id="COMIS_PARCEIRO" value="" maxlength="10">
										</div>
									</div>

								</div>
							<?php
							}
							?>

						</fieldset>

						<div class="push10"></div>

						<fieldset>
							<legend>Comunicação</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<?php if ($cod_empresa == 274) { ?>
											<label for="inputName" class="control-label">Chave única URL/Slug</label>
										<?php } else { ?>
											<label for="inputName" class="control-label">Contato</label>
										<?php } ?>
										<input type="text" class="form-control input-sm" name="NOM_RESPONS" id="NOM_RESPONS" maxlength="40" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">e-Mail</label>
										<input type="text" class="form-control input-sm" name="NOM_EMAIL" id="NOM_EMAIL" maxlength="100" value="" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Telefone Principal</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_TELEFON" id="NUM_TELEFON" maxlength="20">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Telefone Celular</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="20">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">WhatsApp</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_WHATSAPP" id="NUM_WHATSAPP" maxlength="20">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>

						<fieldset>
							<legend>Localização</legend>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Região de Agrupamento</label>
										<select data-placeholder="Selecione o agrupamento da região" name="COD_TIPOREG" id="COD_TIPOREG" class="chosen-select-deselect requiredChk" required>
											<option value="">&nbsp;</option>
											<?php
											$sql = "select * from regiao_grupo where cod_empresa = $cod_empresa order by des_tiporeg";
											$arrayQuery = mysqli_query($conn, $sql);
											while ($qrListaGrTrabalho = mysqli_fetch_assoc($arrayQuery)) {
												echo "<option value='" . $qrListaGrTrabalho['COD_TIPOREG'] . "'>" . $qrListaGrTrabalho['DES_TIPOREG'] . "</option>";
											}
											//fntesteSql(connTemp($cod_empresa,""),$sql);
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Horário de Atendimento</label>
										<input type="text" class="form-control input-sm" name="DES_HORATEND" id="DES_HORATEND" maxlength="200">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<label for="inputName" class="control-label">Imagem do Local</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="hidden" name="DES_IMG" id="DES_IMG" maxlength="100" value="<?php echo $des_img; ?>">
										<input type="text" name="IMG" id="IMG" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_img; ?>">

									</div>
									<span class="help-block">(.png, .jpg 280px X 400px)</span>
								</div>

							</div>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Endereço</label>
										<input type="text" class="form-control input-sm" name="DES_ENDEREC" id="DES_ENDEREC" maxlength="40">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Número</label>
										<input type="text" class="form-control input-sm" name="NUM_ENDEREC" id="NUM_ENDEREC" maxlength="10">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Complemento</label>
										<input type="text" class="form-control input-sm" name="DES_COMPLEM" id="DES_COMPLEM" maxlength="99">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Bairro</label>
										<input type="text" class="form-control input-sm" name="DES_BAIRROC" id="DES_BAIRROC" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">CEP</label>
										<input type="text" class="form-control input-sm cep" name="NUM_CEPOZOF" id="NUM_CEPOZOF" maxlength="9">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cidade</label>
										<input type="text" class="form-control input-sm" name="NOM_CIDADEC" id="NOM_CIDADEC" maxlength="40">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Estado</label>
										<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect">
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
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Latitude</label>
										<input type="text" class="form-control input-sm" name="lat" id="lat" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Longitude</label>
										<input type="text" class="form-control input-sm" name="lng" id="lng" value="">
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>

						<?php
						if ($_SESSION["SYS_COD_EMPRESA"] == "2" || $_SESSION["SYS_COD_EMPRESA"] == "3") {
						?>

							<div class="push10"></div>

							<fieldset style="background: #F4F6F6;">
								<legend>Controle de Licenças - Por Unidade</legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Controlar Unidade</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_STATUS" id="LOG_STATUS" class="switch" value="S">
												<span></span>
											</label>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="push10"></div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Integradora</label>
											<select data-placeholder="Selecione a integradora" name="COD_INTEGRADORA" id="COD_INTEGRADORA" class="chosen-select-deselect">
												<option value=""></option>
												<?php

												$sql = "select * from empresas where COD_EMPRESA <> 1 and LOG_INTEGRADORA = 'S' order by NOM_FANTASI";
												$arrayQuery = mysqli_query($adm, $sql);

												while ($qrListaIntegradora = mysqli_fetch_assoc($arrayQuery)) {

													echo "
															  <option value='" . $qrListaIntegradora['COD_EMPRESA'] . "' " . (@$cod_integradora == $qrListaIntegradora['COD_EMPRESA'] ? "selected" : "") . ">" . $qrListaIntegradora['NOM_FANTASI'] . "</option> 
															";
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>

									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Versão da Integração</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione a versão" name="COD_VERSAOINTEGRA" id="COD_VERSAOINTEGRA">
												<option value=""></option>
												<?php

												$sql = "SELECT * FROM SAC_VERSAOINTEGRA";
												$arrayQuery = mysqli_query($adm, $sql);

												while ($qrIntegracao = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrIntegracao['COD_VERSAOINTEGRA']; ?>" <?= (@$cod_versaointegra == $qrIntegracao['COD_VERSAOINTEGRA'] ? "selected" : "") ?>><?php echo $qrIntegracao['DES_VERSAOINTEGRA']; ?></option>
												<?php } ?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Casas Decimais</label>
											<select data-placeholder="Selecione um decimal" name="NUM_DECIMAIS" id="NUM_DECIMAIS" class="chosen-select-deselect">
												<option value=""></option>
												<option value="2" <?= (@$num_decimais == "2" ? "selected" : "") ?>>2</option>
												<option value="3" <?= (@$num_decimais == "3" ? "selected" : "") ?>>3</option>
												<option value="4" <?= (@$num_decimais == "4" ? "selected" : "") ?>>4</option>
												<option value="5" <?= (@$num_decimais == "5" ? "selected" : "") ?>>5</option>

											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Visualização / Retorno </label>
											<select data-placeholder="Selecione um tipo de visualização dos retornos" name="TIP_RETORNO" id="TIP_RETORNO" class="chosen-select-deselect">
												<option value=""></option>
												<option value="1" <?= (@$tip_retorno == "1" ? "selected" : "") ?>>Valor inteiro</option>
												<option value="2" <?= (@$tip_retorno == "2" ? "selected" : "") ?>>Valor decimal</option>
											</select>
											<div class="help-block with-errors">webservices/relatórios</div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Formato de Data </label>
											<select data-placeholder="Selecione um formato de data" name="COD_DATAWS" id="COD_DATAWS" class="chosen-select-deselect requiredChk">
												<option value=""></option>
												<?php

												$sql = "select * from DATAWS order by COD_DATAWS";
												$arrayQuery = mysqli_query($adm, $sql);

												while ($qrListaTipoData = mysqli_fetch_assoc($arrayQuery)) {

													echo "
															  <option value='" . $qrListaTipoData['COD_DATAWS'] . "' " . (@$cod_dataws == $qrListaTipoData['COD_DATAWS'] ? "selected" : "") . ">" . $qrListaTipoData['FORMATO_WEB'] . "</option> 
															";
												}
												?>
											</select>
											<div class="help-block with-errors">entrada de webservices</div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Informação do Vendedor</label>
											<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a origem da informação" name="LOG_CADVENDEDOR" id="LOG_CADVENDEDOR">
												<option value=""></option>
												<option value="1">tag dados login</option>
												<option value="2">tag venda</option>

											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

						<?php
						} else {
						?>

							<input type="hidden" name="LOG_STATUS" id="LOG_STATUS" value="">
							<input type="hidden" name="COD_INTEGRADORA" id="COD_INTEGRADORA" value="">
							<input type="hidden" name="COD_VERSAOINTEGRA" id="COD_VERSAOINTEGRA" value="">
							<input type="hidden" name="NUM_DECIMAIS" id="NUM_DECIMAIS" value="">
							<input type="hidden" name="TIP_RETORNO" id="TIP_RETORNO" value="">
							<input type="hidden" name="COD_DATAWS" id="COD_DATAWS" value="">
							<input type="hidden" name="LOG_CADVENDEDOR" id="LOG_CADVENDEDOR" value="">

						<?php
						}
						?>

						<div class="push10"></div>
						<hr>
						<div class="col-lg-4">
							<a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar Unidades</a>
						</div>
						<div class="form-group text-right col-lg-8">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push30"></div>

					<div class="row">
						<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

							<div class="col-xs-4 col-xs-offset-4">
								<div class="input-group activeItem">
									<div class="input-group-btn search-panel">
										<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
											<span id="search_concept">Sem filtro</span>&nbsp;
											<span class="far fa-angle-down"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li class="divisor"><a href="#">Sem filtro</a></li>
											<!-- <li class="divider"></li> -->
											<li><a href="#NOM_UNIVEND">Nome Empresa</a></li>
											<li><a href="#NOM_FANTASI">Nome Fantasia</a></li>
											<li><a href="#NOM_RESPONS">Responsável</a></li>
											<li><a href="#NUM_CGCECPF">CNPJ</a></li>
										</ul>
									</div>
									<input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">
									<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
									<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
										<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
									</div>
									<div class="input-group-btn">
										<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
									</div>
								</div>
							</div>

							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						</form>

					</div>

					<div class="push30"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter buscavel">
									<thead>
										<tr>
											<th class="{ sorter: false }" width="40"></th>
											<th>Código</th>
											<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) { ?>
												<th>Controle de Licenças</th>
											<?php } ?>
											<th>Cód. Externo</th>
											<th>Nome da Unidade</th>
											<th>Nome Fantasia</th>
											<th>Responsável</th>
											<th>Telefones</th>
											<th>Tipo</th>
											<th>Dt. Cadastro</th>
											<th>Delivery</th>
											<th>Geolocalização</th>
											<th>Ativo</th>
											<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) { ?>
												<th>Cobrança</th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>

										<?php

										if ($filtro != '') {
											if ($filtro == "NUM_CGCECPF") {
												// fnEscreve($val_pesquisa);
												$val_pesquisa = fnCompletaDoc($val_pesquisa, "J");
												if (strlen($val_pesquisa) == 14) {
													$val_pesquisa = substr($val_pesquisa, 0, 2) . '.' . substr($val_pesquisa, 2, 3) . '.' . substr($val_pesquisa, 5, 3) . '/' . substr($val_pesquisa, 8, 4) . '-' . substr($val_pesquisa, 12, 2);
												}
											}
											$andFiltro = " AND UV.$filtro LIKE '%$val_pesquisa%' ";
										} else {
											$andFiltro = " ";
										}

										// FNeSCREVE($andFiltro);

										$sql = "SELECT UV.*, TP.DES_PROPRIEDADE from unidadevenda UV
										LEFT JOIN TPPROPRIEDADE TP ON TP.COD_PROPRIEDADE = UV.COD_PROPRIEDADE
										where UV.COD_EMPRESA = '" . $cod_empresa . "' and UV.cod_exclusa =0 $andFiltro order by UV.NOM_FANTASI ASC";
										$arrayQuery = mysqli_query($adm, $sql);
										//fnEscreve($sql);

										$count = 0;
										while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											if ($qrListaUniVendas['LOG_ESTATUS'] == 'S') {
												$mostraAtivo = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraAtivo = ' ';
											}

											if ($qrListaUniVendas['LOG_DELIVERY'] == 'S') {
												$mostraAtivoDelivery = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraAtivoDelivery = ' ';
											}

											if ($qrListaUniVendas['LOG_COBRANCA'] == 'S') {
												$mostraAtivoCobranca = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraAtivoCobranca = ' ';
											}

											if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) {
												$tdCobranca = "<td align='center'><small>" . $mostraAtivoCobranca . "</td>";
											} else {
												$tdCobranca = "";
											}

											if ($qrListaUniVendas['lat'] != "" && $qrListaUniVendas['lat'] != "0.0000000" && $qrListaUniVendas['lng'] != "" && $qrListaUniVendas['lng'] != "0.0000000") {
												$mostraAtivoGeo = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraAtivoGeo = '';
											}

											$sqlUni = "SELECT * FROM unidades_parametro WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVENDA = $qrListaUniVendas[COD_UNIVEND] ";
											//fnEscreve($sqlUni);
											$arrayQueryUni =  mysqli_query($conn, $sqlUni);
											if (!$arrayQueryUni) {

												$cod_erro = Log_error_comand($conn, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUni, $nom_usuario);
											}
											/*************  ✨ Smart Paste 📚  *************/
											/******  1e484bc2-fa5c-415a-9f18-bf92b34e0623  *******/
											$qrControleUnidade = mysqli_fetch_assoc($arrayQueryUni);

											//fnEscrevearray($qrControleUnidade);

											if (@$qrControleUnidade['LOG_STATUS'] == 'S') {
												$mostraLicenca = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraLicenca = ' ';
											}

											echo "
											<tr>
											<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
											<td><small>" . $qrListaUniVendas['COD_UNIVEND'] . "</td>
											";
											if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) {
												echo "<td align='center'><small>" . $mostraLicenca . "</td>";
											}
											echo "
											<td><small>" . $qrListaUniVendas['COD_EXTERNO'] . "</td>
											<td><small>" . $qrListaUniVendas['NOM_UNIVEND'] . "</td>
											<td><small>" . $qrListaUniVendas['NOM_FANTASI'] . "</td>
											<td><small>" . $qrListaUniVendas['NOM_RESPONS'] . "</td>
											<td><small>" . $qrListaUniVendas['NUM_TELEFON'] . " / " . $qrListaUniVendas['NUM_CELULAR'] . "</td>
											<td><small>" . $qrListaUniVendas['DES_PROPRIEDADE'] . "</td>
											<td><small>" . fnDataFull($qrListaUniVendas['DAT_CADASTR']) . "</td>
											<td align='center'><small>" . $mostraAtivoDelivery . "</td>
											<td align='center'><small>" . $mostraAtivoGeo . "</td>
											<td align='center'><small>" . $mostraAtivo . "</td>
											" . $tdCobranca . "
											</tr>
											<input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . @$qrListaUniVendas['COD_UNIVEND'] . "'>
											<input type='hidden' id='ret_COD_BANDEIRA_" . $count . "' value='" . @$qrListaUniVendas['COD_BANDEIRA'] . "'>
											<input type='hidden' id='ret_NOM_UNIVEND_" . $count . "' value='" . @$qrListaUniVendas['NOM_UNIVEND'] . "'>
											<input type='hidden' id='ret_NOM_FANTASI_" . $count . "' value='" . @$qrListaUniVendas['NOM_FANTASI'] . "'>
											<input type='hidden' id='ret_NUM_CGCECPF_" . $count . "' value='" . @$qrListaUniVendas['NUM_CGCECPF'] . "'>
											<input type='hidden' id='ret_NUM_ESCRICA_" . $count . "' value='" . @$qrListaUniVendas['NUM_ESCRICA'] . "'>
											<input type='hidden' id='ret_NOM_RESPONS_" . $count . "' value='" . @$qrListaUniVendas['NOM_RESPONS'] . "'>
											<input type='hidden' id='ret_LOG_ESTATUS_" . $count . "' value='" . @$qrListaUniVendas['LOG_ESTATUS'] . "'>
											<input type='hidden' id='ret_NUM_TELEFON_" . $count . "' value='" . @$qrListaUniVendas['NUM_TELEFON'] . "'>
											<input type='hidden' id='ret_NUM_CELULAR_" . $count . "' value='" . @$qrListaUniVendas['NUM_CELULAR'] . "'>
											<input type='hidden' id='ret_NUM_WHATSAPP_" . $count . "' value='" . @$qrListaUniVendas['NUM_WHATSAPP'] . "'>
											<input type='hidden' id='ret_DES_HORATEND_" . $count . "' value='" . @$qrListaUniVendas['DES_HORATEND'] . "'>
											<input type='hidden' id='ret_DES_ENDEREC_" . $count . "' value='" . @$qrListaUniVendas['DES_ENDEREC'] . "'>
											<input type='hidden' id='ret_NUM_ENDEREC_" . $count . "' value='" . @$qrListaUniVendas['NUM_ENDEREC'] . "'>
											<input type='hidden' id='ret_DES_COMPLEM_" . $count . "' value='" . @$qrListaUniVendas['DES_COMPLEM'] . "'>
											<input type='hidden' id='ret_DES_BAIRROC_" . $count . "' value='" . @$qrListaUniVendas['DES_BAIRROC'] . "'>
											<input type='hidden' id='ret_NUM_CEPOZOF_" . $count . "' value='" . @$qrListaUniVendas['NUM_CEPOZOF'] . "'>
											<input type='hidden' id='ret_NOM_CIDADEC_" . $count . "' value='" . @$qrListaUniVendas['NOM_CIDADEC'] . "'>
											<input type='hidden' id='ret_COD_ESTADOF_" . $count . "' value='" . @$qrListaUniVendas['COD_ESTADOF'] . "'>
											<input type='hidden' id='ret_COD_TPUNIVE_" . $count . "' value='" . @$qrListaUniVendas['COD_TPUNIVE'] . "'>
											<input type='hidden' id='ret_COD_GRUPOTR_" . $count . "' value='" . @$qrListaUniVendas['COD_GRUPOTR'] . "'>
											<input type='hidden' id='ret_COD_PROPRIEDADE_" . $count . "' value='" . @$qrListaUniVendas['COD_PROPRIEDADE'] . "'>
											<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . @$qrListaUniVendas['COD_EXTERNO'] . "'>
											<input type='hidden' id='ret_COD_FANTASI_" . $count . "' value='" . @$qrListaUniVendas['COD_FANTASI'] . "'>
											<input type='hidden' id='ret_NOM_EMAIL_" . $count . "' value='" . @$qrListaUniVendas['NOM_EMAIL'] . "'>															
											<input type='hidden' id='ret_COD_TIPOREG_" . $count . "' value='" . @$qrListaUniVendas['COD_TIPOREG'] . "'>															
											<input type='hidden' id='ret_LOG_ATIVOHS_" . $count . "' value='" . @$qrListaUniVendas['LOG_ATIVOHS'] . "'>															
											<input type='hidden' id='ret_LOG_DELIVERY_" . $count . "' value='" . @$qrListaUniVendas['LOG_DELIVERY'] . "'>															
											<input type='hidden' id='ret_LOG_ESPECIAL_" . $count . "' value='" . @$qrListaUniVendas['LOG_ESPECIAL'] . "'>															
											<input type='hidden' id='ret_LOG_COBRANCA_" . $count . "' value='" . @$qrListaUniVendas['LOG_COBRANCA'] . "'>															
											<input type='hidden' id='ret_LOG_UNIPREF_" . $count . "' value='" . @$qrListaUniVendas['LOG_UNIPREF'] . "'>															
											<input type='hidden' id='ret_DES_IMG_" . $count . "' value='" . @$qrListaUniVendas['DES_IMG'] . "'>															
											<input type='hidden' id='ret_IMG_" . $count . "' value='" . decodificar($qrListaUniVendas['DES_IMG']) . "'>															
											<input type='hidden' id='ret_lat_" . $count . "' value='" . @$qrListaUniVendas['lat'] . "'>															
											<input type='hidden' id='ret_lng_" . $count . "' value='" . @$qrListaUniVendas['lng'] . "'>
											<input type='hidden' id='ret_COMIS_VENDEDOR_" . $count . "' value='" . fnValor(@$qrListaUniVendas['COMIS_VENDEDOR'], 2) . "'>
											<input type='hidden' id='ret_COMIS_PARCEIRO_" . $count . "' value='" . fnValor(@$qrListaUniVendas['COMIS_PARCEIRO'], 2) . "'>
											<input type='hidden' id='ret_LOG_TOKEN_" . $count . "' value='" . @$qrListaUniVendas['LOG_TOKEN'] . "'>
											
											<input type='hidden' id='ret_LOG_STATUS_" . $count . "' value='" . @$qrControleUnidade['LOG_STATUS'] . "'>
											<input type='hidden' id='ret_COD_INTEGRADORA_" . $count . "' value='" . @$qrControleUnidade['COD_INTEGRADORA'] . "'>
											<input type='hidden' id='ret_COD_VERSAOINTEGRA_" . $count . "' value='" . @$qrControleUnidade['COD_VERSAOINTEGRA'] . "'>
											<input type='hidden' id='ret_NUM_DECIMAIS_" . $count . "' value='" . @$qrControleUnidade['NUM_DECIMAIS'] . "'>
											<input type='hidden' id='ret_TIP_RETORNO_" . $count . "' value='" . @$qrControleUnidade['TIP_RETORNO'] . "'>
											<input type='hidden' id='ret_COD_DATAWS_" . $count . "' value='" . @$qrControleUnidade['COD_DATAWS'] . "'>
											<input type='hidden' id='ret_LOG_CADVENDEDOR_" . $count . "' value='" . @$qrControleUnidade['LOG_CADVENDEDOR'] . "'>
											";
										}

										?>

									</tbody>

								</table>

							</form>

						</div>

					</div>

					<span>Qtd. Unidades: <b><?php echo ($count); ?></b></span>

					<div class="push10"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
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

		//-----------------------Sorter por icone
		$.tablesorter.addParser({
			// Set a unique id
			id: 'iconParser',
			is: function(s) {
				// Return false so this parser is not auto detected
				return false;
			},
			format: function(s, table, cell, cellIndex) {
				// Retorna 1 se o ícone estiver presente e 0 se não estiver
				return $(cell).find('i').length > 0 ? 1 : 0;
			},
			// Indique que tipo de ordenação será feita
			type: 'numeric'
		});

		$(".tableSorter").tablesorter({
			headers: {
				// Indique o índice da coluna que vai usar o parser personalizado
				2: {
					sorter: 'iconParser'
				},
				10: {
					sorter: 'iconParser'
				},
				11: {
					sorter: 'iconParser'
				},
				12: {
					sorter: 'iconParser'
				},
				13: {
					sorter: 'iconParser'
				},
				// Adicione mais colunas se necessário
			}
		});

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		var SPMaskBehavior = function(val) {
				return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
				onKeyPress: function(val, e, field, options) {
					field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};

		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

		$('#formulario input').keydown(function(e) {
			if (e.keyCode == 13) {
				var inputs = $(this).parents("#formulario").eq(0).find(":input");
				if (inputs[inputs.index(this) + 1] != null) {
					inputs[inputs.index(this) + 1].focus();
				}
				e.preventDefault();
				return false;
			}
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
										url: "ajxUnidades.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										//console.log('media/excel/' + fileName);
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

	});

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

				var data = JSON.parse(data);

				$('.jconfirm-open').fadeOut(300, function() {
					$(this).remove();
				});
				if (data.success) {
					$('#' + idField.replace("arqUpload_DES_", "")).val(nomeArquivo);
					$('#' + idField.replace("arqUpload_", "")).val(data.nome_arquivo);

					//guardando caminho do upload para usar depois
					var pathLogo = 'media/clientes/<?php echo $cod_empresa ?>/' + nomeArquivo;
					//--------------------------------------------------
					$.alert({
						title: "Mensagem",
						content: "Upload feito com sucesso",
						type: 'green'
					});

					//se upar a logo
					if (idField == 'arqUpload_DES_IMG') {
						//usando o caminho do upload como parâmetro
						//exibindo imagem na logo
						$('#logoCel1').attr('src', pathLogo);
						$('#logoCel2').attr('src', pathLogo);
						//se upar o fundo
					} else {
						$('#fundoCel1').css('background', 'url(' + pathLogo + ')');
					}

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

	function retornaForm(index) {
		$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val());
		$("#formulario #NOM_UNIVEND").val($("#ret_NOM_UNIVEND_" + index).val());
		$("#formulario #NOM_RESPONS").val($("#ret_NOM_RESPONS_" + index).val());
		$("#formulario #NUM_CGCECPF").val($("#ret_NUM_CGCECPF_" + index).val());
		if ($("#ret_LOG_ESTATUS_" + index).val() == 'S') {
			$('#formulario #LOG_ESTATUS').prop('checked', true);
		} else {
			$('#formulario #LOG_ESTATUS').prop('checked', false);
		}
		if ($("#ret_LOG_DELIVERY_" + index).val() == 'S') {
			$('#formulario #LOG_DELIVERY').prop('checked', true);
		} else {
			$('#formulario #LOG_DELIVERY').prop('checked', false);
		}
		if ($("#ret_LOG_ESPECIAL_" + index).val() == 'S') {
			$('#formulario #LOG_ESPECIAL').prop('checked', true);
		} else {
			$('#formulario #LOG_ESPECIAL').prop('checked', false);
		}
		if ($("#ret_LOG_UNIPREF_" + index).val() == 'S') {
			$('#formulario #LOG_UNIPREF').prop('checked', true);
		} else {
			$('#formulario #LOG_UNIPREF').prop('checked', false);
		}
		<?php if ($_SESSION['SYS_COD_EMPRESA'] == 2 || $_SESSION['SYS_COD_EMPRESA'] == 3) { ?>
			if ($("#ret_LOG_COBRANCA_" + index).val() == 'S') {
				$('#formulario #LOG_COBRANCA').prop('checked', true);
			} else {
				$('#formulario #LOG_COBRANCA').prop('checked', false);
			}
		<?php } else { ?>
			$('#formulario #LOG_COBRANCA').val($("#ret_LOG_COBRANCA_" + index).val());
		<?php } ?>
		$("#formulario #DES_IMG").val($("#ret_DES_IMG_" + index).val());
		$("#formulario #IMG").val($("#ret_IMG_" + index).val());
		$("#formulario #NUM_ESCRICA").val($("#ret_NUM_ESCRICA_" + index).val());
		$("#formulario #NOM_FANTASI").val($("#ret_NOM_FANTASI_" + index).val());
		$("#formulario #NUM_TELEFON").val($("#ret_NUM_TELEFON_" + index).val());
		$("#formulario #NUM_CELULAR").val($("#ret_NUM_CELULAR_" + index).val());
		$("#formulario #NUM_WHATSAPP").val($("#ret_NUM_WHATSAPP_" + index).val());
		$("#formulario #DES_HORATEND").val($("#ret_DES_HORATEND_" + index).val());
		$("#formulario #DES_ENDEREC").val($("#ret_DES_ENDEREC_" + index).val());
		$("#formulario #NUM_ENDEREC").val($("#ret_NUM_ENDEREC_" + index).val());
		$("#formulario #DES_COMPLEM").val($("#ret_DES_COMPLEM_" + index).val());
		$("#formulario #DES_BAIRROC").val($("#ret_DES_BAIRROC_" + index).val());
		$("#formulario #NUM_CEPOZOF").val($("#ret_NUM_CEPOZOF_" + index).val().trim());
		$("#formulario #NOM_CIDADEC").val($("#ret_NOM_CIDADEC_" + index).val());
		$("#formulario #COD_BANDEIRA").val($("#ret_COD_BANDEIRA_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_ESTADOF").val($("#ret_COD_ESTADOF_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_TPUNIVE").val($("#ret_COD_TPUNIVE_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_PROPRIEDADE").val($("#ret_COD_PROPRIEDADE_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
		$("#formulario #COD_FANTASI").val($("#ret_COD_FANTASI_" + index).val());
		$("#formulario #NOM_EMAIL").val($("#ret_NOM_EMAIL_" + index).val());
		$("#formulario #COD_TIPOREG").val($("#ret_COD_TIPOREG_" + index).val()).trigger("chosen:updated");
		if ($("#ret_LOG_ATIVOHS_" + index).val() == 'S') {
			$('#formulario #LOG_ATIVOHS').prop('checked', true);
		} else {
			$('#formulario #LOG_ATIVOHS').prop('checked', false);
		}
		$("#formulario #lat").val($("#ret_lat_" + index).val());
		$("#formulario #lng").val($("#ret_lng_" + index).val());

		if ($("#ret_LOG_STATUS_" + index).val() == 'S') {
			$('#formulario #LOG_STATUS').prop('checked', true);
		} else {
			$('#formulario #LOG_STATUS').prop('checked', false);
		}

		if ($("#ret_LOG_TOKEN_" + index).val() == 'S') {
			$('#formulario #LOG_TOKEN').prop('checked', true);
		} else {
			$('#formulario #LOG_TOKEN').prop('checked', false);
		}

		$("#formulario #COD_INTEGRADORA").val($("#ret_COD_INTEGRADORA_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_VERSAOINTEGRA").val($("#ret_COD_VERSAOINTEGRA_" + index).val()).trigger("chosen:updated");
		$("#formulario #NUM_DECIMAIS").val($("#ret_NUM_DECIMAIS_" + index).val()).trigger("chosen:updated");
		$("#formulario #TIP_RETORNO").val($("#ret_TIP_RETORNO_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_DATAWS").val($("#ret_COD_DATAWS_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_TPUNIVE").val($("#ret_COD_TPUNIVE_" + index).val()).trigger("chosen:updated");
		$("#formulario #LOG_CADVENDEDOR").val($("#ret_LOG_CADVENDEDOR_" + index).val()).trigger("chosen:updated");
		$("#formulario #COMIS_VENDEDOR").val($("#ret_COMIS_VENDEDOR_" + index).val()).trigger("chosen:updated");
		$("#formulario #COMIS_PARCEIRO").val($("#ret_COMIS_PARCEIRO_" + index).val()).trigger("chosen:updated");

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>