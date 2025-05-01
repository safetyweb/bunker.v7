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
$des_grupotr = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente_av = "";
$des_sufixo = "";
$qrBuscaDadosAdm = "";
$log_configu = "";
$base = "";
$usuario = "";
$unidade_venda = "";
$sql2 = "";
$qrBuscaDadosEmpresa = "";
$personas = "";
$campanhas = "";
$abaEmpresa = "";
$corDataBase = "";
$txtDataBase = "";
$qrBuscaClienteAvulso = "";
$cod_avulso = "";
$arrayQuery2 = [];
$arrayQuery3 = [];
$qrBuscaConfereAvulso = "";
$corClienteAvulso = "";
$txtClienteAvulso = "";
$corUsuario = "";
$txtUsuario = "";
$btnUsuario = "";
$corUsuario2 = "";
$txtUsuario2 = "";
$btnUsuario2 = "";
$qrBuscaUsuWS = "";
$tem_usuario = "";
$sqlUsu = "";
$corUsuarioWS = "";
$txtUsuarioWS = "";
$btnUsuarioWS = "";
$qrBuscaMatriz = "";
$tem_matriz = "";
$corMatriz = "";
$txtMatriz = "";
$btnMatriz = "";
$corUnidade = "";
$txtUnidade = "";
$btnUnidade = "";
$corPersonas = "";
$txtPersonas = "";
$btnPersonas = "";
$corCampanhas = "";
$txtCampanhas = "";
$btnCampanhas = "";



$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_grupotr = fnLimpaCampoZero(@$_REQUEST['COD_GRUPOTR']);
		$des_grupotr = fnLimpaCampo(@$_REQUEST['DES_GRUPOTR']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '" . $cod_grupotr . "', 
				 '" . $des_grupotr . "', 
				 '" . $cod_empresa . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			mysqli_query($connAdm->connAdm(), trim($sql));

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV, DES_SUFIXO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_cliente_av = $qrBuscaEmpresa['COD_CLIENTE_AV'];
		$des_sufixo = $qrBuscaEmpresa['DES_SUFIXO'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//busca dados adm	
$sql = "SELECT LOG_CONFIGU,
			(select COUNT(*) FROM tab_database where COD_EMPRESA = $cod_empresa) AS BASE,
			(select COUNT(*) from usuarios where COD_EMPRESA = $cod_empresa) AS USUARIO,
			(select COUNT(*) from unidadevenda  where COD_EMPRESA =$cod_empresa) AS UNIDADE_VENDA
			FROM EMPRESAS
			WHERE COD_EMPRESA = $cod_empresa ";

//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaDadosAdm = mysqli_fetch_assoc($arrayQuery);

$log_configu = $qrBuscaDadosAdm['LOG_CONFIGU'];
$base = $qrBuscaDadosAdm['BASE'];
$usuario = $qrBuscaDadosAdm['USUARIO'];
$unidade_venda = $qrBuscaDadosAdm['UNIDADE_VENDA'];

//busca dados empresa	
$sql = "SELECT 
			(SELECT COUNT(*) FROM PERSONA A,PERSONAREGRA B
			WHERE 
			A.COD_PERSONA = B.COD_PERSONA AND
			A.COD_EMPRESA = $cod_empresa AND
			A.LOG_ATIVO='S') AS PERSONAS,
			(SELECT COUNT(*) FROM CAMPANHA A, CAMPANHAREGRA B
			WHERE A.COD_CAMPANHA=B.COD_CAMPANHA AND
			  A.COD_EMPRESA = $cod_empresa AND
			  A.LOG_ATIVO='S') AS CAMPANHAS ";

//fnEscreve($sql2);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaDadosEmpresa = mysqli_fetch_assoc($arrayQuery);

$personas = $qrBuscaDadosEmpresa['PERSONAS'];
$campanhas = $qrBuscaDadosEmpresa['CAMPANHAS'];

//fnEscreve($personas);
//fnEscreve($campanhas);	
//fnMostraForm();

?>

<style>
	.update-nag {
		display: inline-block;
		font-size: 16px;
		text-align: left;
		background-color: #fff;
		height: 50px;
		-webkit-box-shadow: 1px 2px 2px 1px rgba(0, 0, 0, .2);
		box-shadow: 1px 2px 2px 1px rgba(0, 0, 0, .1);
		margin-bottom: 20px;
		border: 1px solid #F2F3F4;
		border-radius: 5px;
		width: 100%;
	}

	.update-nag:hover {
		cursor: pointer;
		-webkit-box-shadow: 3px 3px 21px 0px rgba(50, 50, 50, 0.40);
		-moz-box-shadow: 3px 3px 21px 0px rgba(50, 50, 50, 0.40);
		box-shadow: 3px 3px 21px 0px rgba(50, 50, 50, 0.40);
	}

	.update-nag>.update-split {
		background: #337ab7;
		width: 63px;
		float: left;
		color: #fff !important;
		height: 100%;
		text-align: center;
		border-radius: 5px 0 0 5px;
	}

	.update-nag>.update-split>.glyphicon {
		position: relative;
		top: calc(50% - 9px) !important;
		/* 50% - 3/4 of icon height */
	}

	.update-nag>.update-split.update-success {
		background: #48C9B0 !important;
	}

	.update-nag>.update-split.update-danger {
		background: #EC7063 !important;
	}

	.update-nag>.update-split.update-info {
		background: #F4D03F !important;
	}

	.update-nag>.update-text {
		line-height: 19px;
		padding-top: 15px;
		padding-left: 75px;
		padding-right: 20px;
	}
</style>

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
				<?php include "atalhosPortlet.php"; ?>
			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php
				if ($log_configu == "N") {
				?>
					<div class="alert alert-warning top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Sua empresa <b>ainda não está </b> totalmente configurada e pronta pra uso. <br />
					</div>
				<?php
				}
				?>

				<?php
				//menu superior - empresas
				$abaEmpresa = 1021;
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 14: //rede duque
						include "abasEmpresaDuque.php";
						break;
					case 15: //quiz
						include "abasEmpresaQuiz.php";
						break;
					default;
						include "abasEmpresaConfig.php";
						break;
				}
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">


						<div class="row">

							<div class="col-md-6">

								<h4>Configuração Automática</h4>

								<div class="push20"></div>

								<?php
								if ($base > 0) {
									$corDataBase = "update-success";
									$txtDataBase = "Database criado com sucesso";
								} else {
									$corDataBase = "update-danger";
									$txtDataBase = "Sem database especificado";
								}
								?>

								<div class="col-md-12">
									<div class="update-nag">
										<div class="update-split <?php echo $corDataBase; ?>"><i class="glyphicon  fal fa-database"></i></div>
										<div class="update-text"><?php echo $txtDataBase; ?> </div>
									</div>
								</div>

								<?php

								//busca dados empresa
								$sql = "CALL SP_CADASTRA_CLIENTE_AVULSO (
												 '" . $cod_empresa . "', 
												 'S'   
												) ";

								//fnEscreve($sql);														
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
								$qrBuscaClienteAvulso = mysqli_fetch_assoc($arrayQuery);

								$cod_avulso = $qrBuscaClienteAvulso['COD_CLIENTE'];

								if ($cod_cliente_av == 0) {

									$sql2 = "UPDATE EMPRESAS SET COD_CLIENTE_AV = $cod_avulso WHERE COD_EMPRESA = $cod_empresa ";

									$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);

									//busca cliente avulso
									$sql = "SELECT COD_CLIENTE_AV FROM empresas where COD_EMPRESA = $cod_empresa ";
									//fnEscreve($sql);
									$arrayQuery3 = mysqli_query($connAdm->connAdm(), $sql);
									$qrBuscaConfereAvulso = mysqli_fetch_assoc($arrayQuery3);

									if (isset($arrayQuery2)) {
										$cod_cliente_av = $qrBuscaConfereAvulso['COD_CLIENTE_AV'];
									}
								}

								//fnEscreve($cod_cliente_av);	

								if ($cod_cliente_av != 0 && $cod_cliente_av != '') {
									$corClienteAvulso = "update-success";
									$txtClienteAvulso = "Cliente avulso criado com sucesso <small>(" . $cod_cliente_av . ")</SMALL>";
								} else {
									$corClienteAvulso = "update-danger";
									$txtClienteAvulso = "Sem cliente avulso especificado";
								}
								?>

								<div class="col-md-12">
									<div class="update-nag">
										<div class="update-split <?php echo $corClienteAvulso; ?>"><i class="glyphicon fal fa-user-times fa-lg" aria-hidden="true"></i></div>
										<div class="update-text"><?php echo $txtClienteAvulso; ?> </div>
									</div>
								</div>

								<?php
								if ($usuario > 0) {
									$corUsuario = "update-success";
									$txtUsuario = "Perfil de acesso criado com sucesso";
									$btnUsuario = "btn-success";
								} else {
									$corUsuario = "update-danger";
									$txtUsuario = "Sem perfil de acesso ao sistema";
									$btnUsuario = "btn-danger";
								}
								?>

								<div class="col-md-12">
									<div class="update-nag">
										<div class="update-split <?php echo $corUsuario; ?>"><i class="glyphicon fal fa-unlock-alt fa-lg" aria-hidden="true"></i></div>
										<div class="update-text"><?php echo $txtUsuario; ?> &nbsp;&nbsp;<a href="action.do?mod=<?php echo fnEncode(1018) . "&id=" . fnEncode($cod_empresa); ?>" class="btn <?php echo $btnUsuario; ?> btn-xs"> Acessar</a></div>
									</div>
								</div>

								<?php
								if ($usuario > 0) {
									$corUsuario2 = "update-success";
									$txtUsuario2 = "Usuário(s) criado(s) com sucesso ";
									$btnUsuario2 = "btn-success";
								} else {
									$corUsuario2 = "update-danger";
									$txtUsuario2 = "Falha na criação do(s) usuário(s)";
									$btnUsuario2 = "btn-danger";
								}
								?>

								<div class="col-md-12">
									<div class="update-nag">
										<div class="update-split <?php echo $corUsuario2; ?>"><i class="glyphicon fal fa-user-plus fa-lg"></i></div>
										<div class="update-text"><?php echo $txtUsuario2; ?> &nbsp;&nbsp;<a href="action.do?mod=<?php echo fnEncode(1017) . "&id=" . fnEncode($cod_empresa); ?>" class="btn <?php echo $btnUsuario2; ?> btn-xs">Acessar</a></div>
									</div>
								</div>


								<?php

								//busca dados adm	
								$sql = "SELECT COUNT(COD_USUARIO) AS TEM_USUARIO FROM USUARIOS
														WHERE COD_EMPRESA = $cod_empresa 
														AND COD_TPUSUARIO = 10 ";

								//fnEscreve($sql);
								$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
								$qrBuscaUsuWS = mysqli_fetch_assoc($arrayQuery);

								$tem_usuario = $qrBuscaUsuWS['TEM_USUARIO'];


								if ($tem_usuario == 1) {

									$sqlUsu = " INSERT INTO usuarios
												(
												COD_EMPRESA,
												COD_EXTERNO,
												NOM_USUARIO, 
												DES_SENHAUS,
												LOG_USUARIO,
												DES_EMAILUS,
												LOG_USUDEV,
												HOR_DEVDIAS, 
												HOR_DEVFDS, 
												HOR_ENTRADA,
												COD_PERFILCOM,
												COD_USUCADA, 
												DAT_CADASTR, 
												COD_ALTERAC,
												DAT_ALTERAC, 
												COD_EXCLUSA, 
												DAT_EXCLUSA, 
												NUM_CGCECPF,
												LOG_ESTATUS,
												NUM_RGPESSO,
												DAT_NASCIME,
												COD_ESTACIV,
												COD_SEXOPES,
												NUM_TENTATI, 
												NUM_TELEFON, 
												NUM_CELULAR, 
												COD_TPUSUARIO,
												COD_PERFILS,
												COD_DEFSIST, 
												COD_MULTEMP,
												COD_UNIVEND,
												COD_INDICADOR, 
												ID_OPERADOR,
												COD_TURNO, 
												SENHA_INI
												) 
												  VALUES (
												22, 
												'', 
												'Ws_$des_sufixo', 
												'X03XJ7ySNfw¢', 
												'ws.$des_sufixo', 
												'',
												'N', 
												0, 
												0, 
												'00:00:00',
												0, 
												34, 
												'2021-03-15 14:08:19', 
												0,
												'',
												0,
												NULL, 
												'', 
												'S', 
												'', 
												'', 
												0,
												0,
												2, 
												'', 
												'',
												10, 
												'0', 
												4, 
												'$cod_empresa', 
												'97317,97310,97311,97309,97315,97312,97320,97313',
												0,
												0, 
												NULL, 
												0);	";
								}


								//fnEscreve($sqlUsu);
								//fnEscreve($tem_usuario);
								//fnEscreve($des_sufixo);

								if ($tem_usuario > 0) {
									$corUsuarioWS = "update-success";
									$txtUsuarioWS = "Usuário Webservice criado com sucesso ";
									$btnUsuarioWS = "btn-success";
								} else {
									$corUsuarioWS = "update-danger";
									$txtUsuarioWS = "Falha na criação do usuário de Webservice";
									$btnUsuarioWS = "btn-danger";
								}
								?>

								<div class="col-md-12">
									<div class="update-nag">
										<div class="update-split <?php echo $corUsuarioWS; ?>"><i class="glyphicon fal fa-user-cog fa-lg"></i></div>
										<div class="update-text"><?php echo $txtUsuarioWS; ?> &nbsp;&nbsp;<a href="action.do?mod=<?php echo fnEncode(1252) . "&id=" . fnEncode($cod_empresa); ?>" class="btn <?php echo $btnUsuario2; ?> btn-xs">Acessar</a></div>
									</div>
								</div>


								<?php

								//busca dados adm	
								$sql = "SELECT 
														COUNT(COD_FASEVND) as TEM_FASE
														FROM MATRIZ_INTEGRACAO 
														WHERE cod_empresa = $cod_empresa  
														AND COD_FASEVND IN (1,2,7) ";




								//fnEscreve($sql);
								$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
								$qrBuscaMatriz = mysqli_fetch_assoc($arrayQuery);

								$tem_matriz = $qrBuscaMatriz['TEM_FASE'];

								//fnEscreve($sqlUsu);
								//fnEscreve($tem_usuario);
								//fnEscreve($des_sufixo);

								if ($tem_matriz > 0) {
									$corMatriz = "update-success";
									$txtMatriz = "Matriz de Integração ativa ";
									$btnMatriz = "btn-success";
								} else {
									$corMatriz = "update-danger";
									$txtMatriz = "Falha na matriz de integração";
									$btnMatriz = "btn-danger";
								}
								?>

								<div class="col-md-12">
									<div class="update-nag">
										<div class="update-split <?php echo $corMatriz; ?>"><i class="glyphicon fal fa-qrcode fa-lg"></i></div>
										<div class="update-text"><?php echo $txtMatriz; ?> &nbsp;&nbsp;<a href="action.do?mod=<?php echo fnEncode(1153) . "&id=" . fnEncode($cod_empresa); ?>" class="btn <?php echo $btnUsuario2; ?> btn-xs">Acessar</a></div>
									</div>
								</div>



							</div>

							<div class="col-md-6">

								<h4>Configuração Manual</h4>

								<div class="push20"></div>

								<?php
								if ($unidade_venda > 0) {
									$corUnidade = "update-info";
									$txtUnidade = "Unidade(s) de atendimento criadas com sucesso ";
									$btnUnidade = "btn-warning";
								} else {
									$corUnidade = "update-danger";
									$txtUnidade = "Nenhuma unidade de atendimento cadastrada";
									$btnUnidade = "btn-danger";
								}
								?>

								<div class="col-md-12">
									<div class="update-nag">
										<div class="update-split <?php echo $corUnidade; ?>"><i class="glyphicon fal fa-street-view fa-lg"></i></div>
										<div class="update-text"><?php echo $txtUnidade; ?> &nbsp;&nbsp;<a href="action.do?mod=<?php echo fnEncode(1023) . "&id=" . fnEncode($cod_empresa); ?>" class="btn <?php echo $btnUnidade; ?> btn-xs">Acessar</a> </div>
									</div>
								</div>

								<?php
								if ($personas > 0) {
									$corPersonas = "update-info";
									$txtPersonas = "Personas criada(s) e ativa(s) para uso";
									$btnPersonas = "btn-warning";
								} else {
									$corPersonas = "update-danger";
									$txtPersonas = "Nenhuma persona criada e ativa";
									$btnPersonas = "btn-danger";
								}
								?>

								<div class="col-md-12">
									<div class="update-nag">
										<div class="update-split <?php echo $corPersonas; ?>"><i class="glyphicon fal fa-users fa-lg"></i></div>
										<div class="update-text"><?php echo $txtPersonas; ?> &nbsp;&nbsp;<a href="action.do?mod=<?php echo fnEncode(1049) . "&id=" . fnEncode($cod_empresa); ?>" class="btn btn-warning btn-xs">Acessar</a> </div>
									</div>
								</div>

								<?php
								if ($campanhas > 0) {
									$corCampanhas = "update-info";
									$txtCampanhas = "Campanhas(s) criada(s) e ativa(s) para uso";
									$btnCampanhas = "btn-warning";
								} else {
									$corCampanhas = "update-danger";
									$txtCampanhas = "Nenhuma campanha criada e ativa";
									$btnCampanhas = "btn-danger";
								}
								?>

								<div class="col-md-12">
									<div class="update-nag">
										<div class="update-split <?php echo $corCampanhas; ?>"><i class="glyphicon fal fa-cubes fa-lg"></i></div>
										<div class="update-text"><?php echo $txtCampanhas; ?> &nbsp;&nbsp;<a href="action.do?mod=<?php echo fnEncode(1049) . "&id=" . fnEncode($cod_empresa); ?>" class="btn btn-warning btn-xs">Acessar</a> </div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="update-nag">
										<div class="update-split btn-error"><i class="glyphicon fal fa-cogs fa-lg"></i></div>
										<div class="update-text">Controles e geradores</div> <!-- ccontadores de pedidos e etc / deault vazio e não zero-->
									</div>
								</div>

							</div>

						</div>

						<div class="push100"></div>



						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

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

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>