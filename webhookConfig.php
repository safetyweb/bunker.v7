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
$cod_webhook = "";
$log_estatus = "";
$cod_usuario = "";
$tip_categoria = "";
$tip_webhook = "";
$url = "";
$des_senha = "";
$des_senhamarka = "";
$des_chave = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$sqlUnidade = "";
$arrUnidade = "";
$qrUni = "";
$arrayInsert = [];
$cod_erro = "";
$sqlBusca = "";
$arrayBusca = [];
$qrBuscaModulos = "";
$senhaus = "";
$urlwebhook = "";
$sqlSenha = "";
$arraySenha = [];
$sqlRegistro = "";
$arrayRegistro = [];
$corMsg = "";
$sqlUpdate = "";
$arrayUpdate = [];
$sqlExc = "";
$arrayExc = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaEmpresa = "";
$qrLista = "";
$tipo = "";
$status = "";
$urlvenda = "";
$urlbusca = "";
$urlcadastro = "";
$btnUrl = "";
$univend = "";


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

		$cod_webhook = fnLimpaCampoZero(@$_REQUEST['COD_WEBHOOK']);
		if (empty(@$_REQUEST['LOG_ESTATUS'])) {
			$log_estatus = 'N';
		} else {
			$log_estatus = @$_REQUEST['LOG_ESTATUS'];
		}
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_usuario = fnLimpaCampoZero(@$_REQUEST['COD_USUARIO']);
		$cod_univend = fnLimpaCampoZero(@$_REQUEST['COD_UNIVEND']);
		$tip_categoria = fnLimpaCampoZero(@$_REQUEST['TIP_CATEGORIA']);
		$tip_webhook = fnLimpaCampo(@$_REQUEST['TIP_WEBHOOK']);
		$url = fnLimpaCampo(@$_REQUEST['URL']);
		$des_senha = fnLimpaCampo(@$_REQUEST['DES_SENHA']);
		$des_senhamarka = fnLimpaCampo(@$_REQUEST['DES_SENHAMARKA']);
		$des_chave = fnLimpaCampo(@$_REQUEST['DES_CHAVE']);

		if ($des_senha == "") {
			$des_senha = $des_chave;
		}

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sqlUnidade = "SELECT COD_UNIVEND FROM WEBHOOK 
									   WHERE COD_UNIVEND = $cod_univend 
									   AND COD_EMPRESA = $cod_empresa
									   AND COD_UNIVEND != 0
									   AND COD_UNIVEND IS NOT NULL";

					$arrUnidade = mysqli_query($adm, trim($sql));

					$qrUni = mysqli_fetch_assoc($arrUnidade);

					if ($qrUni['COD_UNIVEND'] == "" && $cod_univend != '99999') {

						if ($tip_webhook == 6) {

							$sql = "INSERT INTO WEBHOOK(
													LOG_ESTATUS,
													COD_EMPRESA,
													COD_USUARIO,
													COD_UNIVEND,
													TIP_WEBHOOK,
													TIP_CATEGORIA,
													URL,
													DES_SENHA,
													COD_USUCADA
													) VALUES
													(
													'$log_estatus',
													$cod_empresa,
													0,
													0,
													'$tip_webhook',
													'http://externo.bunker.mk/C5/consulta_cliente.php?id=$cod_empresa&id2=$cod_univend',
													'0',
													$cod_usucada
													),
													(
													'$log_estatus',
													$cod_empresa,
													0,
													0,
													'$tip_webhook',
													'http://externo.bunker.mk/C5/inserir_venda.php?id=$cod_empresa&id2=$cod_univend',
													'0',
													$cod_usucada
													)";

							mysqli_query($adm, trim($sql));
							//fnEscreve($sql);
						} else {

							$sql = "INSERT INTO WEBHOOK(
													LOG_ESTATUS,
													COD_EMPRESA,
													COD_USUARIO,
													COD_UNIVEND,
													TIP_WEBHOOK,
													TIP_CATEGORIA,
													URL,
													DES_SENHA,
													COD_USUCADA
													) VALUES(
													'$log_estatus',
													$cod_empresa,
													$cod_usuario,
													$cod_univend,
													'$tip_webhook',
													'$tip_categoria',
													'$url',
													'$des_senha',
													$cod_usucada
													)";

							// fnEscreve($sql);

							$arrayInsert = mysqli_query($adm, trim($sql));

							// fnEscreve($sql);

							//fnTesteSql($adm, trim($sql));

							//fnEscreve($arrayInsert);
							if (!$arrayInsert) {

								$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
							}

							$sqlBusca = "SELECT WH.*, US.NOM_USUARIO, US.LOG_USUARIO, US.DES_SENHAUS, UV.NOM_FANTASI FROM WEBHOOK WH
										LEFT JOIN USUARIOS US ON US.COD_USUARIO = WH.COD_USUARIO
										LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = WH.COD_UNIVEND
										WHERE WH.COD_EMPRESA = $cod_empresa
										AND WH.COD_WEBHOOK = (SELECT MAX(COD_WEBHOOK) FROM WEBHOOK 
															   WHERE COD_EMPRESA = $cod_empresa 
															   AND COD_USUCADA = $cod_usucada)";


							$arrayBusca = mysqli_query($adm, $sqlBusca);

							$qrBuscaModulos = mysqli_fetch_assoc($arrayBusca);

							$senhaus = fnDecode($qrBuscaModulos['DES_SENHAUS']);

							$urlwebhook = fnEncode(
								$qrBuscaModulos['LOG_USUARIO'] . ';'
									. $senhaus . ';'
									. $qrBuscaModulos['COD_UNIVEND'] . ';'
									. 'webhook' . ';'
									. $cod_empresa
							);

							$des_senhamarka = fnLimpaCampo(base64_encode($urlwebhook));

							$sqlSenha = "UPDATE WEBHOOK SET 
							  						DES_SENHAMARKA = '$des_senhamarka'
							  				 WHERE COD_EMPRESA = $cod_empresa
							  				 AND COD_WEBHOOK = $qrBuscaModulos[COD_WEBHOOK]";

							$arraySenha = mysqli_query($adm, $sqlSenha);


							if ($cod_univend != '99999') {

								$sqlRegistro = "INSERT INTO `controle_linx` (`CONTROLE_LINX`, `COD_CONSULTA`, `COD_EMPRESA`, `COD_UNIVEND`, `TIP_CONTROLE`, `DES_CONTROLE`) 
								  					VALUES (6, 0, $cod_empresa, $cod_univend, 2, 'CADASTRO'),
								  						   (7, 0, $cod_empresa, $cod_univend, 1, 'VENDA'),
								  						   (10,0, $cod_empresa, $cod_univend, 3, 'TROCAS')";

								$arrayRegistro = mysqli_query($conn, $sqlRegistro);
							}
						}

						//fnEscreve($sql);

						if ($cod_erro == 0 || $cod_erro ==  "") {
							$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						} else {
							$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
						}
					} else {
						$msgRetorno = "Unidade já <strong>existe!</strong> Registro <strong>não cadastrado</strong>.";
						$corMsg = 1;
					}


					break;
				case 'ALT':
					$sqlUpdate = "UPDATE WEBHOOK SET 
									   LOG_ESTATUS = '$log_estatus',
									   COD_USUARIO = $cod_usuario,
									   COD_UNIVEND = $cod_univend,
									   TIP_WEBHOOK = '$tip_webhook',
									   TIP_CATEGORIA = '$tip_categoria',
									   URL = '$url',								   								   
									   DES_SENHA = '$des_senha',									   								   
									   DES_SENHAMARKA = '$des_senhamarka'									   								   
									   WHERE 
									   COD_WEBHOOK = $cod_webhook
									   ";

					//fnEscreve($sql);

					$arrayUpdate = mysqli_query($adm, $sqlUpdate);

					fnEscreve($arrayUpdate);

					if (!$arrayUpdate) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					$sqlExc = "DELETE FROM WEBHOOK WHERE COD_WEBHOOK = $cod_webhook";

					$arrayExc = mysqli_query($connAdm->connAdm(), trim($sqlExc));


					if (!$arrayExc) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlExc, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}

			if (isset($corMsg) && $corMsg == 1) {
				$msgTipo = 'alert-warning';
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

//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1420";
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

				<?php $abaEmpresa = 1420;
				include "abasEmpresaConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<div class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Ativo</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" checked>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo</label>
										<select data-placeholder="Selecione um tipo" name="TIP_WEBHOOK" id="TIP_WEBHOOK" class="chosen-select-deselect" style="width:100%;">
											<option value=""></option>
											<option value="1">web ticket</option>
											<option value="2">integração SH</option>
											<option value="3">integração fBits</option>
											<option value="4">envio email</option>
											<option value="5">integração Js</option>
											<option value="6">consico</option>
											<option value="7">totvs</option>
											<option value="8">linx microvix</option>
											<option value="9">trier</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3 consico">
									<div class="form-group">
										<label for="inputName" class="control-label required">Usuário</label>
										<select data-placeholder="Selecione um usuário" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" style="width:100%;" required>
											<option value=""></option>
											<?php

											$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
																		where usuarios.COD_EMPRESA = $cod_empresa
																		and usuarios.DAT_EXCLUSA is null
																		AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
											$arrayQuery = mysqli_query($adm, $sql);

											while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
												echo "
													<option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3 consico">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade</label>
										<select data-placeholder="Selecione uma unidade" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" style="width:100%;" required>
											<option value=""></option>
											<option value="99999">Todas Unidades</option>
											<?php

											$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA 
																				WHERE COD_EMPRESA = $cod_empresa 
																				AND (COD_EXCLUSA=0 OR COD_EXCLUSA IS NULL)";
											$arrayQuery = mysqli_query($adm, $sql);

											while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
												echo "
													<option value='" . $qrLista['COD_UNIVEND'] . "'>" . $qrLista['NOM_FANTASI'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3 escondeChave" style="display: none;">
									<div class="form-group">
										<label for="inputName" class="control-label">Chave</label>
										<input type="text" class="form-control input-sm" name="DES_CHAVE" id="DES_CHAVE" value="">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Categoria</label>
										<select data-placeholder="Selecione um tipo" name="TIP_CATEGORIA" id="TIP_CATEGORIA" class="chosen-select-deselect" style="width:100%;">
											<option value="0">nomeClassificacao</option>
											<option value="1">nomeCategoria</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3 esconde" style="display: none;">
									<div class="form-group">
										<label for="inputName" class="control-label">URL</label>
										<input type="text" class="form-control input-sm" name="URL" id="URL" value="" readonly>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3 escondeSenha" style="display: none;">
									<div class="form-group">
										<label for="inputName" class="control-label">Senha</label>
										<input type="text" class="form-control input-sm" name="DES_SENHA" id="DES_SENHA" value="" readonly>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="COD_WEBHOOK" id="COD_WEBHOOK" value="">
						<input type="hidden" name="DES_SENHAMARKA" id="DES_SENHAMARKA" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th class="{ sorter: false }" width="40"></th>
											<th>Código</th>
											<th>Ativo</th>
											<th>Tipo Webhook</th>
											<th>Usuário</th>
											<th>Unidade</th>
											<th>URL</th>
											<th>Senha</th>
											<th>Acesso</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT WH.*, US.NOM_USUARIO, US.LOG_USUARIO, US.DES_SENHAUS, UV.NOM_FANTASI FROM WEBHOOK WH
															LEFT JOIN USUARIOS US ON US.COD_USUARIO = WH.COD_USUARIO
															LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = WH.COD_UNIVEND
															WHERE WH.COD_EMPRESA = $cod_empresa";



										$arrayQuery = mysqli_query($adm, $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

											$tipo = "";
											$status = "";
											$senhaus = fnDecode($qrBuscaModulos['DES_SENHAUS']);

											$urlwebhook = fnEncode(
												$qrBuscaModulos['LOG_USUARIO'] . ';'
													. $senhaus . ';'
													. $qrBuscaModulos['COD_UNIVEND'] . ';'
													. 'webhook' . ';'
													. $cod_empresa
											);

											// fnEscreve(fnDecode($urlwebhook));

											switch ($qrBuscaModulos['TIP_WEBHOOK']) {
												case 1:
													$tipo = "web ticket";
													$url = "http://webhook.bunker.mk/tktweb.do?key=$urlwebhook";
													break;
												case 2:
													$tipo = "integração SH";
													$url = "http://webhook.bunker.mk/vetex.do?id=$cod_empresa";
													break;
												case 3:
													$tipo = "integração fBits";
													$url = "http://webhook.bunker.mk/fbits.do?id=$cod_empresa";
													break;
												case 4:
													$tipo = "envio email";
													$url = "http://externo.bunker.mk/email/emailcampanha.do?id=$cod_empresa";
													break;
												case 5:
													$tipo = "integração Js";
													$url = "";
													break;
												case 6:
													$tipo = "consico";
													$url = "";
													break;
												case 7:
													$tipo = "totvs";
													$urlvenda = "http://externo.bunker.mk/totvs/venda.do?id=$urlwebhook";
													$urlbusca = "http://externo.bunker.mk/totvs/busca.do?id=$urlwebhook";
													$urlcadastro = "http://externo.bunker.mk/totvs/cadastro.do?id=$urlwebhook";
													break;
												default:
													break;
											}

											if ($qrBuscaModulos['LOG_ESTATUS'] == 'S') {
												$status = "<span class='fas fa-check text-success'></span>";
											} else {
												$status = "<span class='fas fa-times text-danger'></span>";
											}

											$btnUrl = " <td class='text-center'>
													  					<a class='btn btn-xs btn-info' href='" . $url . "' target='_blank'>
																	  		<i class='fa fa-share'></i>
																	  		&nbsp; Acessar 
																  		</a>
																  	</td>";

											if ($qrBuscaModulos['TIP_WEBHOOK'] == 7) {
												$btnUrl = "
													  			<td class='dropdown'>  
																    <a class='dropdown-toggle btn-xs btn-info' data-toggle='dropdown' href='#'> ações &nbsp;
																	  <span class='fa fa-caret-down'></span>
																    </a>
																	<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenuButton'>
																		<li class='pull-left'><a class='btn btn-xs btn' href='" . $urlvenda . "' target='_blank'><i class='fa fa-dollar-sign'></i>&nbsp; Acessar Venda </a></li>
																		<li class='pull-left'><a class='btn btn-xs btn' href='" . $urlbusca . "' target='_blank'><i class='fa fa-search'></i>&nbsp; Acessar Busca </a></li>
																		<li class='pull-left'><a class='btn btn-xs btn' href='" . $urlcadastro . "' target='_blank'><i class='fa fa-user'></i>&nbsp; Acessar Cadastro </a></li>
																	</ul>
																</td>
													  		";
											}

											$univend = $qrBuscaModulos['NOM_FANTASI'];

											if ($qrBuscaModulos['COD_UNIVEND'] == '99999') {
												$univend = "Todas Unidades";
											}

											// http://externo.bunker.mk/totvs/identifications/authentication
											// http://externo.bunker.mk/totvs/identifications/forms
											// http://externo.bunker.mk/totvs/identifications/identification
											// http://externo.bunker.mk/totvs/bonuses/bonus
											// http://externo.bunker.mk/totvs/identifications/cancel
											// http://externo.bunker.mk/totvs/identifications/finalize
											// http://externo.bunker.mk/totvs/order


											$count++;
											echo "
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaModulos['COD_WEBHOOK'] . "</td>
															  <td class='text-center'>" . $status . "</td>
															  <td>" . $tipo . "</td>															  
															  <td>" . $qrBuscaModulos['NOM_USUARIO'] . "</td>															  
															  <td>" . $univend . "</td>															  
															  <td>" . $qrBuscaModulos['URL'] . "</td>															  
															  <td>" . base64_encode($urlwebhook) . "</td>
															  <td class='dropdown'>  
																    <a class='dropdown-toggle btn-xs btn-info' data-toggle='dropdown' href='#'> ações &nbsp;
																	  <span class='fa fa-caret-down'></span>
																    </a>
																	<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenuButton'>
																		<li class='pull-left'><a class='btn btn-xs btn' href='http://externo.bunker.mk/totvs/identifications/authentication' target='_blank'><i class='fa fa-dollar-sign'></i>&nbsp; Autenticação </a></li>
																		<li class='pull-left'><a class='btn btn-xs btn' href='http://externo.bunker.mk/totvs/identifications/forms' target='_blank'><i class='fa fa-search'></i>&nbsp; Formulários </a></li>
																		<li class='pull-left'><a class='btn btn-xs btn' href='http://externo.bunker.mk/totvs/identifications/identification' target='_blank'><i class='fa fa-user'></i>&nbsp; Identificação </a></li>
																		<li class='pull-left'><a class='btn btn-xs btn' href='http://externo.bunker.mk/totvs/bonuses/bonus' target='_blank'><i class='fa fa-user'></i>&nbsp; Bonus </a></li>
																		<li class='pull-left'><a class='btn btn-xs btn' href='http://externo.bunker.mk/totvs/identifications/cancel' target='_blank'><i class='fa fa-user'></i>&nbsp; Cancelamento </a></li>
																		<li class='pull-left'><a class='btn btn-xs btn' href='http://externo.bunker.mk/totvs/identifications/finalize' target='_blank'><i class='fa fa-user'></i>&nbsp; Finalizar Venda </a></li>
																		<li class='pull-left'><a class='btn btn-xs btn' href='http://externo.bunker.mk/totvs/order' target='_blank'><i class='fa fa-user'></i>&nbsp; Venda Avulsa </a></li>
																	</ul>
																</td>
															</tr>
															<input type='hidden' id='ret_COD_WEBHOOK_" . $count . "' value='" . $qrBuscaModulos['COD_WEBHOOK'] . "'>
															<input type='hidden' id='ret_LOG_ESTATUS_" . $count . "' value='" . $qrBuscaModulos['LOG_ESTATUS'] . "'>
															<input type='hidden' id='ret_TIP_WEBHOOK_" . $count . "' value='" . $qrBuscaModulos['TIP_WEBHOOK'] . "'>
															<input type='hidden' id='ret_TIP_CATEGORIA_" . $count . "' value='" . $qrBuscaModulos['TIP_CATEGORIA'] . "'>
															<input type='hidden' id='ret_COD_USUARIO_" . $count . "' value='" . $qrBuscaModulos['COD_USUARIO'] . "'>
															<input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . $qrBuscaModulos['COD_UNIVEND'] . "'>
															<input type='hidden' id='ret_URL_" . $count . "' value='" . $qrBuscaModulos['URL'] . "'>
															<input type='hidden' id='ret_DES_SENHA_" . $count . "' value='" . $qrBuscaModulos['DES_SENHA'] . "'>
															<input type='hidden' id='ret_DES_SENHAMARKA_" . $count . "' value='" . base64_encode($urlwebhook) . "'>
															";
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

<script type="text/javascript">
	$(function() {

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$("#TIP_WEBHOOK").change(function() {

			$("#COD_USUARIO").prop('required', true);
			$("#COD_UNIVEND").prop('required', true);
			$('.consico').fadeIn('fast');

			if ($(this).val() == 2) {

				$(".esconde").fadeIn('fast');
				$("#URL").prop("readonly", false);
				$(".escondeSenha").fadeOut('fast');
				$("#DES_SENHA").val('').prop("readonly", true);

			} else if ($(this).val() == 3 || $(this).val() == 9) {

				$(".escondeSenha").fadeIn('fast');
				$("#DES_SENHA").prop("readonly", false);
				$(".esconde").fadeIn('fast');
				$("#URL").prop("readonly", false);

			} else if ($(this).val() == 6) {

				$(".escondeSenha").fadeOut('fast');
				$("#DES_SENHA").fadeOut('fast');
				$(".esconde").fadeOut('fast');
				$("#URL").fadeOut('fast');
				$("#COD_USUARIO").prop('required', false);
				$("#COD_UNIVEND").prop('required', false);
				//$('.consico').fadeOut('fast');

			} else if ($(this).val() == 8) {

				$(".escondeSenha,.esconde").fadeOut('fast');
				$(".escondeChave").fadeIn('fast');
				$("#COD_USUARIO,#COD_UNIVEND").prop('required', true);

			} else {

				$(".esconde").fadeOut('fast');
				$("#URL").val('').prop("readonly", true);
				$(".escondeSenha").fadeOut('fast');
				$("#DES_SENHA").val('').prop("readonly", true);

			}

			$('#formulario').validator('validate');

		});

	});

	function retornaForm(index) {
		$("#formulario #COD_WEBHOOK").val($("#ret_COD_WEBHOOK_" + index).val());

		if ($("#ret_LOG_ESTATUS_" + index).val() == 'S') {
			$('#formulario #LOG_ESTATUS').prop('checked', true);
		} else {
			$('#formulario #LOG_ESTATUS').prop('checked', false);
		}

		$("#formulario #TIP_WEBHOOK").val($("#ret_TIP_WEBHOOK_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_USUARIO").val($("#ret_COD_USUARIO_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
		$("#formulario #TIP_CATEGORIA").val($("#ret_TIP_CATEGORIA_" + index).val()).trigger("chosen:updated");
		$("#formulario #DES_SENHAMARKA").val($("#ret_DES_SENHAMARKA_" + index).val());
		$("#COD_USUARIO").prop('required', true);
		$("#COD_UNIVEND").prop('required', true);
		$('.consico').fadeIn('fast');

		if ($("#ret_TIP_WEBHOOK_" + index).val() == 2) {

			$(".esconde").fadeIn('fast');
			$("#URL").prop("readonly", false);
			$("#formulario #URL").val($("#ret_URL_" + index).val()).trigger("chosen:updated");
			$(".escondeSenha").fadeOut('fast');
			$("#DES_SENHA").val('').prop("readonly", true);

		} else if ($("#ret_TIP_WEBHOOK_" + index).val() == 3 || $("#ret_TIP_WEBHOOK_" + index).val() == 9) {

			$(".esconde").fadeIn('fast');
			$("#URL").prop("readonly", false);
			$("#formulario #URL").val($("#ret_URL_" + index).val()).trigger("chosen:updated");
			$(".escondeSenha").fadeIn('fast');
			$("#DES_SENHA").prop("readonly", false);
			$("#formulario #DES_SENHA").val($("#ret_DES_SENHA_" + index).val()).trigger("chosen:updated");

		} else if ($("#ret_TIP_WEBHOOK_" + index).val() == 6) {

			$(".esconde").fadeOut('fast');
			$("#COD_USUARIO").prop('required', false);
			$("#COD_UNIVEND").prop('required', false);
			//$('.consico').fadeOut('fast');
			$("#URL").fadeOut("fast");
			$(".escondeSenha").fadeOut('fast');
			$("#DES_SENHA").prop('fast');

		} else if ($("#ret_TIP_WEBHOOK_" + index).val() == 8) {

			$(".escondeSenha,.esconde").fadeOut('fast');
			$(".escondeChave").fadeIn('fast').prop("readonly", false);
			$("#COD_USUARIO,#COD_UNIVEND").prop('required', true);
			$("#formulario #DES_CHAVE").val($("#ret_DES_SENHA_" + index).val());

		} else {

			$(".escondeSenha").fadeOut('fast');
			$("#DES_SENHA").val('').prop("readonly", true);
			$(".esconde").fadeOut('fast');
			$("#URL").val('').prop("readonly", true);

		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');

	}
</script>