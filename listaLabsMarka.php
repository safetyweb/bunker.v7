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
	$sql1 = "";
	$cod_perfils = "";
	$arrayQuery1 = [];
	$qrBuscaPerfil = "";
	$sql2 = "";
	$arrayQuery2 = [];
	$qrBuscaAutorizacao = "";
	$cod_modulos_aut = "";
	$modulosAutorizados = "";
	$arrayAutorizado = [];
	$arrayParamAutorizacao = [];
	$codRelatorio = "";
	$paramAutRelatorio = "";
	$arrayCompara = [];
	$retornoAut = "";
	$modulosRelatorios = "";
	$formBack = "";

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

		if (isset($arrayQuery)) {
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
	} else {
		$cod_empresa = 0;
		//fnEscreve('entrou else');
	}

	//busca perfil do usuário 
	//4 - fidelidade
	$sql1 = "select cod_usuario,cod_defsist,cod_perfils
			from usuarios
			where cod_empresa = " . $_SESSION["SYS_COD_EMPRESA"] . " and
				  cod_defsist = 4 and
				  cod_usuario = " . $_SESSION["SYS_COD_USUARIO"] . " ";

	//fnEscreve($sql1);			  
	if ($_SESSION["SYS_COD_SISTEMA"] == 3) {
		$cod_perfils = '9999';
	} else {
		$arrayQuery1 = mysqli_query($connAdm->connAdm(), $sql1);
		$qrBuscaPerfil = mysqli_fetch_assoc($arrayQuery1);
		$cod_perfils = $qrBuscaPerfil['cod_perfils'];
	}

	//busca modulos autorizados
	$sql2 = "select cod_modulos from perfil
			where cod_sistema=4 and
			cod_perfils in($cod_perfils)";

	//fnEscreve($sql2);			
	$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);

	$count = 0;
	while ($qrBuscaAutorizacao = mysqli_fetch_assoc($arrayQuery2)) {
		$cod_modulos_aut = $qrBuscaAutorizacao['cod_modulos'];
		$modulosAutorizados = $modulosAutorizados . $cod_modulos_aut . ",";
	}

	$arrayAutorizado = explode(",", $modulosAutorizados);


	//fnEscreve($sql2);

	$arrayParamAutorizacao = array(
		'COD_MODULO' => "9999",
		'MODULOS_AUT' => $arrayAutorizado,
		'COD_SISTEMA' => $_SESSION["SYS_COD_SISTEMA"]
	);

	//echo "<pre>";	
	//print_r($arrayParamAutorizacao);	
	//echo "</pre>";	

	function fnAutRelatorio($codRelatorio, $paramAutRelatorio)
	{
		$arrayCompara = $paramAutRelatorio['MODULOS_AUT'];
		//se sistema adm marka
		if ($paramAutRelatorio['COD_SISTEMA'] == 3) {
			$retornoAut = true;
		} else {
			if (recursive_array_search($codRelatorio, $arrayCompara) !== false) {
				$retornoAut = true;
			} else {
				$retornoAut = false;
			}
		}
		return $retornoAut;
	}

	//fnEscreve($cod_perfils);
	//fnEscreve($modulosAutorizados);
	//fnEscreve($_SESSION["SYS_COD_USUARIO"]);
	//fnEscreve($_SESSION["SYS_COD_EMPRESA"]);	
	//fnEscreve($_SESSION["SYS_COD_SISTEMA"]);	
	//fnMostraForm();
	//fnEscreve($modulosRelatorios);
	?>


	<style>
		#services {}

		#services .services-top {
			padding: 70px 0 50px;
		}

		#services .services-list {
			padding-top: 50px;
		}

		.services-list .service-block {
			margin-bottom: 25px;
		}

		.services-list .service-block .ico {
			font-size: 38px;
			float: left;
		}

		.services-list .service-block .text-block {
			margin-left: 58px;
		}

		.services-list .service-block .text-block .name {
			font-size: 20px;
			font-weight: 900;
			margin-bottom: 5px;
		}

		.services-list .service-block .text-block .info {
			font-size: 16px;
			font-weight: 300;
			margin-bottom: 10px;
		}

		.services-list .service-block .text-block .text {
			font-size: 12px;
			line-height: normal;
			font-weight: 300;
		}

		.highlight {
			color: #2ac5ed;
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
						<span class="text-primary"><?php echo $NomePg; ?></span>
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
					<?php } ?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<h4><?php echo $nom_empresa; ?></h4>

							<div class="push30"></div>

							<div class="row">
								<div class="services-list">

									<div class="row" style="margin: 0 0 0 1px;">

										<div class="col-sm-6 col-md-3">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-code highlight"></div>
												<div class="text-block">
													<h4>Em Desenvolvimento</h4>
													<div class="text">No forno</div>
													<div class="push10"></div>

													<a href="action.do?mod=<?php echo fnEncode(1680); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Mais Cash</a> <br />

													<a href="action.do?mod=<?php echo fnEncode(1479); ?>" target="_blank">&rsaquo; Tela de listagem dos Artigos do Tutorial </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1337); ?>" target="_blank">&rsaquo; Banner do Login </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1300); ?>" target="_blank">&rsaquo; Controle de Metas</a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1329); ?>" target="_blank">&rsaquo; Valores de Rateio</a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1283); ?>" target="_blank">&rsaquo; Tiles</a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1793); ?>" target="_blank">&rsaquo; Banner Marka</a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1890); ?>" target="_blank">&rsaquo; Documentos</a> <br />

												</div>
											</div>
										</div>

										<div class="col-sm-6 col-md-3">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-clipboard-check highlight"></div>
												<div class="text-block">
													<h4>Finalizados</h4>
													<div class="text">Em Produção</div>
													<div class="push10"></div>


													<a href="action.do?mod=<?php echo fnEncode(1395); ?>&id=nlQKAomhw30¢" target="_blank">&rsaquo; Dash Lucratividade </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1480); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Relatórios de Análises (Índices) </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1381); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Relatório de Desafio</a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1490); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Dash Analytics</a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1327); ?>" target="_blank">&rsaquo; Dashboard Geolocalização</a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1384); ?>&id=nlQKAomhw30¢" target="_blank">&rsaquo; Dash Faturamento </a> <br />
													<a href="action.do?mod=OwYfV3z8SeQ%C2%A2&id=nlQKAomhw30¢" target="_blank">&rsaquo; Dash Engajamento </a> <br />
													<a href="action.do?mod=CZulp£tmbzM8¢&id=nlQKAomhw30¢" target="_blank">&rsaquo; Dash Resgate </a> <br />
													<a href="action.do?mod=us£uTXdaEin4¢&id=nlQKAomhw30¢" target="_blank">&rsaquo; Dash Performace </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1391); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Resgate Múltiplo</a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1408); ?>" target="_blank">&rsaquo; Template de Email </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1429); ?>&id=<?php echo fnEncode(136); ?>" target="_blank">&rsaquo; Relatórios Social </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1457); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Envio Simples de Email </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1448); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Indicação de Produtos (+ vendidos) </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1453); ?>&id=<?php echo fnEncode(39); ?>&idP=<?php echo fnEncode(8); ?>" target="_blank">&rsaquo; Categoria de Produtos Top </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1456); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Configuração de Frequência (Cliente) </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1460); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Relatório de Troca de Cartões </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1459); ?>&id=<?php echo fnEncode(39); ?>" target="_blank">&rsaquo; Relatório de Movimentação Clientes </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1466); ?>" target="_blank">&rsaquo; Relatório de Logs de Usuários (ADM) </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1471); ?>" target="_blank">&rsaquo; Categorias do Tutorial </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1458); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Matriz de Configuração de Preços (Email) </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1463); ?>&id=<?php echo fnEncode(39); ?>" target="_blank">&rsaquo; Percentual de Vendas por Período (Persona) </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1472); ?>&id=<?php echo fnEncode(7); ?>" target="_blank">&rsaquo; Relatório de Logs de Usuários </a> <br />
													<a href="action.do?mod=<?php echo fnEncode(1469); ?>&id=<?php echo fnEncode(77); ?>" target="_blank">&rsaquo; Relatório de Indicação de Produtos (Grupo) </a> <br />

												</div>
											</div>
										</div>

										<div class="push30"></div>

									</div>

								</div>

							</div>


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


		$(document).ready(function() {


		});
	</script>