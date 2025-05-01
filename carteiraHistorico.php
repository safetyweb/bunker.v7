<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$tem_prodaux = "";

$itens_carregar_mais = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		if (isset($_REQUEST['COD_VENDA'])) {
			$cod_venda = fnLimpacampoZero($_REQUEST['COD_VENDA']);
		} else {
			$cod_venda = "";
		}

		if (isset($_REQUEST['COD_ORCAMENTO'])) {
			$cod_orcamento = fnLimpacampoZero($_REQUEST['COD_ORCAMENTO']);
		} else {
			$cod_orcamento = "";
		}

		if (isset($_REQUEST['COD_EMPRESA'])) {
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
		} else {
			$cod_empresa = "";
		}

		if (isset($_REQUEST['COD_CLIENTE'])) {
			$cod_cliente = fnLimpacampoZero($_REQUEST['COD_CLIENTE']);
		} else {
			$cod_cliente = "";
		}

		if (isset($_REQUEST['COD_LANCAMEN'])) {
			$cod_lancamen = fnLimpacampoZero($_REQUEST['COD_LANCAMEN']);
		} else {
			$cod_lancamen = "";
		}

		if (isset($_REQUEST['COD_OCORREN'])) {
			$cod_ocorren = fnLimpacampoZero($_REQUEST['COD_OCORREN']);
		} else {
			$cod_ocorren = "";
		}

		if (isset($_REQUEST['COD_UNIVEND'])) {
			$cod_univend = fnLimpacampoZero($_REQUEST['COD_UNIVEND']);
		} else {
			$cod_univend = "";
		}

		if (isset($_REQUEST['COD_FORMAPA'])) {
			$cod_formapa = fnLimpacampoZero($_REQUEST['COD_FORMAPA']);
		} else {
			$cod_formapa = "";
		}

		if (isset($_REQUEST['TEM_PRODAUX'])) {
			$tem_prodaux = fnLimpacampoZero($_REQUEST['TEM_PRODAUX']);
		} else {
			$tem_prodaux = "";
		}


		if (isset($_REQUEST['VAL_TOTPRODU'])) {
			$val_totprodu = fnLimpacampo($_REQUEST['VAL_TOTPRODU']);
		} else {
			$val_totprodu = "";
		}

		if (isset($_REQUEST['VAL_RESGATE'])) {
			$val_resgate = fnLimpacampo($_REQUEST['VAL_RESGATE']);
		} else {
			$val_resgate = "";
		}

		if (isset($_REQUEST['VAL_DESCONTO'])) {
			$val_desconto = fnLimpacampo($_REQUEST['VAL_DESCONTO']);
		} else {
			$val_desconto = "";
		}

		if (isset($_REQUEST['VAL_TOTVENDA'])) {
			$val_totvenda = fnLimpacampo($_REQUEST['VAL_TOTVENDA']);
		} else {
			$val_totvenda = "";
		}

		if (isset($_REQUEST['COD_VENDAPDV'])) {
			$cod_vendapdv = fnLimpacampo($_REQUEST['COD_VENDAPDV']);
		} else {
			$cod_vendapdv = "";
		}


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


			//echo $sql1;	

			mysqli_query(connTemp(fnDecode($_GET['key']), ''), $sql1);

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

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_cliente = fnDecode($_GET['idC']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, TIP_RETORNO, TIP_CAMPANHA, NUM_DECIMAIS_B, LOG_ATIVCAD FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
		$tip_campanha = $qrBuscaEmpresa['TIP_CAMPANHA'];
		$log_ativcadLGPD = $qrBuscaEmpresa['LOG_ATIVCAD'];
		$NUM_DECIMAIS_B = $qrBuscaEmpresa['NUM_DECIMAIS_B'];

		if ($tip_retorno == 2) {
			$casasDec = $NUM_DECIMAIS_B;
		} else {
			$casasDec = '0';
		}
	}

	//novas verificações LGPD
	if ($tip_retorno == 1) {
		$txtTipo = "pontos";
	} else {
		$txtTipo = "créditos";
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
	$casasDec = 2;
}


//busca dados do cliente
$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE, LOG_FUNCIONA, LOG_ESTATUS, LOG_CADOK,	LOG_TERMO FROM CLIENTES where COD_CLIENTE = '" . $cod_cliente . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

//echo "<h3>Chegou...</h3>";

if (isset($arrayQuery)) {

	$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
	$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
	$log_funciona = $qrBuscaCliente['LOG_FUNCIONA'];
	$log_estatus = $qrBuscaCliente['LOG_ESTATUS'];
	$log_cadokLGPD = $qrBuscaCliente['LOG_CADOK'];
	$log_termoLGPD = $qrBuscaCliente['LOG_TERMO'];
} else {

	$nom_cliente = "";
	$cod_cliente = "";
	$num_cartao = "";
	$num_cgcecpf = "";
	$log_estatus = "";
}

if ($log_estatus == "N") {
	$msgRetorno = 'Cliente <strong>inativo</strong>.';
	$msgTipo = 'alert-warning';
}

//novas verificações LGPD
if ($log_ativcadLGPD == "S" && $log_cadokLGPD == "N" && $log_termoLGPD == "N") {
	$bloqueiaDesbloqueio = "S";
} else {
	$bloqueiaDesbloqueio = "N";
}

//fnEscreve2("<h4>Chegou tb...</h4>");

include "moduloControlaAcesso.php";

//fnMostraForm();
//fnEscreve2($cod_cliente);
//fnEscreve2("<h4>Chegou tb...</h4>");



?>
<style>
	.widget .widget-title {
		font-size: 14px;
	}

	.widget .widget-int {
		font-size: 18px;
		padding: 0 0 10px 0;
	}

	.widget .widget-item-left .fa,
	.widget .widget-item-right .fa,
	.widget .widget-item-left .glyphicon,
	.widget .widget-item-right .glyphicon {
		font-size: 35px;
	}

	.alert .alert-link {
		text-decoration: none;
	}

	.alert:hover .alert-link:hover {
		text-decoration: underline;
	}

	.widget-item-left span {
		font-size: 32px;
	}

	.badge {
		display: table;
		border-radius: 30px 30px 30px 30px;
		width: 26px;
		height: 26px;
		text-align: center;
		color: white;
		font-size: 11px;
		margin-right: auto;
		margin-left: auto;
	}

	.txtBadge {
		display: table-cell;
		vertical-align: middle;
	}

	/*.notify-badge span{
	margin: 0 auto;
}*/
</style>
<div class="push30"></div>

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
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $NomePg; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>

				<div class="portlet-body">
					<?php
					//verifica se tem bloqueio
					$sql4 = "SELECT COUNT(*) as TEM_BLOQUEIO
							FROM CLIENTES A, VENDAS B
							LEFT JOIN unidadevenda d ON d.cod_univend = b.cod_univend 
							WHERE A.COD_CLIENTE=B.COD_CLIENTE AND 
							B.COD_STATUSCRED=3 AND
                            B.cod_avulso!=1 AND
							A.COD_EMPRESA = $cod_empresa and
							A.COD_CLIENTE = $cod_cliente ";
					$qrBuscaBloqueio = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql4));
					// fnEscreve($sql4);

					$tem_bloqueio = $qrBuscaBloqueio['TEM_BLOQUEIO'];

					if ($tem_bloqueio > 0) { ?>

						<div class="alert alert-warning alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							Cliente possui vendas bloqueadas. <br />

							<?php
							if (fnControlaAcesso("1191", $arrayParamAutorizacao) === true) { ?>
								<!-- 1191 -->
								<a href="action.do?mod=<?php echo fnEncode(1191); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?= fnEncode($cod_cliente) ?>" target="_blank" class="alert-link">&rsaquo; Acessar tela de desbloqueio</a>
							<?php } else { ?>
								<a href="javascript:$.alert('Você não possui acesso a este módulo');" class="alert-link">&rsaquo; Acessar tela de desbloqueio</a>
							<?php } ?>

						</div>
					<?php } ?>

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php if ($log_funciona == 'S') { ?>
						<div class="alert alert-warning alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							Cliente é <b>funcionário</b> da empresa.
						</div>
					<?php } ?>


					<?php
					//menu superior - cliente
					$abaCli = 1081;
					switch ($_SESSION["SYS_COD_SISTEMA"]) {
						case 14: //rede duque
							include "abasClienteDuque.php";
							break;
						case 13: //sh manager
							include "abasIntegradoraCli.php";
							break;
						case 18: //mais cash
							include "abasMaisCashCli.php";
							break;
						default;
							include "abasClienteConfig.php";
							break;
					}
					?>

					<div class="push30"></div>
					<div class="push10"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<?php
							//visualização no hot site
							if (fnDecode($_GET['mod']) != "1211") {
							?>

								<fieldset>
									<legend>Dados Gerais</legend>

									<div class="row">

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Código do Cliente</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label required">Empresa</label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
												<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>" required>
											</div>
										</div>

										<div class="col-md-5">
											<label for="inputName" class="control-label required">Nome do Usuário</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Busca Clientes"><i class="fal fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
												</span>
												<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leituraOff" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente; ?>">
												<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>" required>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Número do Cartão</label>
												<input type="text" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" data-error="Campo obrigatório" required>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>

								</fieldset>

							<?php
							} else {
							?>

								<div class="row">

									<div class="col-md-4">
										<label for="inputName" class="control-label">Nome do Usuário</label>
										<div class="push5"></div>
										<h4><?php echo $nom_cliente; ?></h4>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Número do Cartão</label>
											<div class="push5"></div>
											<h4><?php echo $num_cartao; ?></h4>
										</div>
									</div>

								</div>


							<?php
							}
							?>

							<div class="push30"></div>

							<?php
							//busca dados do cliente
							$sql = "CALL total_wallet('$cod_cliente', '$cod_empresa')";

							//fnEscreve($sql);

							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
							$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);


							if (isset($arrayQuery)) {

								$total_creditos = $qrBuscaTotais['TOTAL_CREDITOS'];
								$total_debitos = $qrBuscaTotais['TOTAL_DEBITOS'];
								$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
								$credito_aliberar = $qrBuscaTotais['CREDITO_ALIBERAR'];
								$credito_bloqueado = $qrBuscaTotais['CREDITO_BLOQUEADO'];
								$credito_bloqueadoLGPD = $qrBuscaTotais['CREDITO_BLOQUEADO_LGPD'];
								$credito_expirados = $qrBuscaTotais['CREDITO_EXPIRADOS'];
							} else {

								$total_creditos = 0;
								$total_debitos = 0;
								$credito_disponivel = 0;
								$credito_aliberar = 0;
								$credito_expirados = 0;
								$credito_bloqueado = 0;
							}

							if ($log_estatus == "N") {
								$credito_disponivel = 0;
							}
							?>


							<?php if ($bloqueiaDesbloqueio == "S") { ?>
								<div class="row" style="background-color: #FDEDEC; border-radius: 10px;">

									<div class="push10"></div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 f14"><b>
											Termo LGPD desatualizado. <br />
											<?php if ($credito_bloqueadoLGPD > 0) { ?>
												Existem <span class="text-danger"><?php echo fnValor($credito_bloqueadoLGPD, $casasDec); ?></span> <?php echo $txtTipo; ?> bloqueados por desatualização.
											<?php } ?>
										</b>
										<div class="push20"></div>
									</div>

								<?php } else { ?>
									<div class="row">
									<?php } ?>


									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">

										<div class="widget widget-default widget-item-icon">
											<div class="widget-item-left">
												<span class="fal fa-cart-plus"></span>
											</div>
											<div class="widget-data">
												<div class="widget-int">
													<div class="push10"></div>
													<?php echo fnValor($total_creditos, $casasDec); ?>
												</div>
												<div class="widget-title">Total Ganho</div>
												<div class="widget-subtitle">
													<div class="push5"></div>
												</div>
											</div>
										</div>

									</div>

									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">

										<div class="widget widget-default widget-item-icon">
											<div class="widget-item-left">
												<span class="fal fa-cart-arrow-down"></span>
											</div>
											<div class="widget-data">
												<div class="widget-int">
													<div class="push10"></div>
													<?php echo fnValor($total_debitos, $casasDec); ?>
												</div>
												<div class="widget-title">Total Resgatado</div>
												<div class="widget-subtitle">
													<div class="push5"></div>
												</div>
											</div>
										</div>

									</div>

									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">

										<div class="widget widget-default widget-item-icon">
											<div class="widget-item-left">
												<span class="fal fa-hand-holding-usd"></span>
											</div>
											<div class="widget-data">
												<div class="widget-int">
													<div class="push10"></div>
													<?php echo fnValor($credito_disponivel, $casasDec); ?>
												</div>
												<div class="widget-title">Saldo Disponível</div>
												<div class="widget-subtitle">
													<div class="push5"></div>
												</div>
											</div>
										</div>

									</div>

									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">

										<div class="widget widget-default widget-item-icon">
											<div class="widget-item-left">
												<span class="fal fa-clock fa-3x"></span>
											</div>
											<div class="widget-data">
												<div class="widget-int">
													<div class="push10"></div>
													<?php echo fnValor($credito_aliberar, $casasDec); ?>
												</div>
												<div class="widget-title">Saldo à Liberar</div>
												<div class="widget-subtitle">
													<div class="push5"></div>
												</div>
											</div>
										</div>

									</div>

									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">

										<div class="widget widget-default widget-item-icon">
											<div class="widget-item-left">
												<span class="fal fa-unlock-alt"></span>
											</div>
											<div class="widget-data">
												<div class="widget-int">
													<div class="push10"></div>
													<?php echo fnValor($credito_bloqueado, $casasDec); ?>
												</div>
												<div class="widget-title">
													<?php
													if ($credito_bloqueado > 0) {
														// echo "<a href='action.do?mod=".fnEncode(1191)."&id=".fnEncode($cod_empresa)."' class='text-danger'>Saldo Bloqueado</a>";

														if (fnControlaAcesso("1191", $arrayParamAutorizacao) === true) { ?>
															<!-- 1191 -->
															<a href="action.do?mod=<?php echo fnEncode(1191); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?= fnEncode($cod_cliente) ?>" target="_blank" class="alert-link text-danger">&rsaquo; Saldo Bloqueado</a>
														<?php } else { ?>
															<a href="javascript:$.alert('Você não possui acesso a este módulo');" class="alert-link text-danger">&rsaquo; Saldo Bloqueado</a>
													<?php }
													} else {
														echo "Saldo Bloqueado";
													}
													?>
												</div>
												<div class="widget-subtitle">
													<div class="push5"></div>
												</div>
											</div>
										</div>

									</div>

									<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">

										<div class="widget widget-default widget-item-icon">
											<div class="widget-item-left">
												<span class="fal fa-calendar-times"></span>
											</div>
											<div class="widget-data">
												<div class="widget-int">
													<div class="push10"></div>
													<?php echo fnValor($credito_expirados, $casasDec); ?>
												</div>
												<div class="widget-title">Expirados</div>
												<div class="widget-subtitle">
													<div class="push5"></div>
												</div>
											</div>
										</div>

									</div>

									</div>

									<div class="push10"></div>

									<div class="row">

										<?php
										if ($qrBuscaEmpresa['TIP_CAMPANHA'] == '13') {
											$tipocampanha = 'Crédito';
										} elseif ($qrBuscaEmpresa['TIP_CAMPANHA'] == '12') {
											$tipocampanha = 'Pontos';
										} elseif ($qrBuscaEmpresa['TIP_CAMPANHA'] == '20') {
											$tipocampanha = 'Cupom';
										} else {
											$tipocampanha = 'Crédito';
										}
										?>

										<div class="col-md-12" id="div_Produtos">


											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<th></th>
														<th>Data</th>
														<th>ID</th>
														<th>ID Venda</th>
														<th>Cupom</th>
														<th>Tipo</th>
														<th><?php echo $tipocampanha; ?></th>
														<th>Resgate</th>
														<th>Data de Validade</th>
														<th>Dias para Expirar</th>
														<th>Origem</th>
														<th>Status Atual</th>
														<th>Loja</th>
														<?php
														if (fnDecode($_GET['mod']) != "1211") {
														?>
															<th>Campanha</th>
															<th>Persona</th>
														<?php
														}
														?>
													</tr>
												</thead>
												<tbody id="relatorioConteudo">

													<?php

													$sql = "CALL LISTA_WALLET('$cod_cliente', '$cod_empresa',0,15)";

													// fnEscreve($sql);
													// fnEscreve($sql);

													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


													$count = 0;
													$valorTTotal = 0;
													$valorTRegaste = 0;
													$valorTDesconto = 0;
													$valorTvenda = 0;

													while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {

														$count++;
														if ($qrBuscaProdutos['TIP_CREDITO'] == "D") {
															$textRed = "text-danger";
															$badge = "";
															$cor = "";
															$txtBadge = "";
															$valorCred = 0;
															$valorDeb = $qrBuscaProdutos['VAL_CREDITO'];
															$tag_campanha = "";
															$tag_persona =  "";
															$diff_dias =  "";
															$opcaoExpandir =  "";
															$mostra_expira = 0;
															$cor = "";

															//se débito tem recibo de venda
															if ($qrBuscaProdutos['COD_PRODUTO'] > 0) {
																// $opcaoExpandir =  "<a href='javascript:void(0);' onclick='abreDetail(".$qrBuscaProdutos['COD_CREDITO'].",".$qrBuscaProdutos['COD_VENDA'].")'><i class='fa fa-receipt' aria-hidden='true'></i></a>";

																$opcaoExpandir =  "<a type='button' class='addBox' data-title='Recibo de Resgate' data-url='action.php?mod=" . fnEncode(1250) . "&id=" . fnEncode($cod_empresa) . "&idR=" . fnEncode($qrBuscaProdutos['COD_CREDITO']) . "&idC=" . fnEncode(0) . "&pop=true'><i class='fa fa-receipt' aria-hidden='true'></i></a>";
															}
														} else {
															$badge = "badge";
															$txtBadge = "txtBadge";

															$diff_dias = fnDateDif(fnDataSql($qrBuscaProdutos['atual']), fnDataSql($qrBuscaProdutos['DAT_EXPIRA']));
															if ($diff_dias > 0) {
																$mostra_expira = $diff_dias;
																$cor = "background:#18bc9c;";
															} else {
																$mostra_expira = 0;
																$cor = "background:red; color:white;";
															}


															$textRed = "";
															$valorCred = $qrBuscaProdutos['VAL_CREDITO'];
															$valorDeb = 0;

															if ($qrBuscaProdutos['COD_VENDA'] != 0) {
																//mostrar detalhes da venda	
																$tag_campanha = "<li class='tag'><span class='label label-info'>● &nbsp; " . $qrBuscaProdutos['DES_CAMPANHA'] . "</span></li>";
																$tag_persona =  "<li class='tag'><span class='label label-warning'>● &nbsp; " . $qrBuscaProdutos['DES_PERSONA'] . "</span></li>";
																$opcaoExpandir =  "<a href='javascript:void(0);'onclick='abreDetail(" . $qrBuscaProdutos['COD_CREDITO'] . ",\"" . $qrBuscaProdutos['COD_VENDA'] . "\",\"" . $qrBuscaProdutos['COD_ITEMVEN'] . "\",\"" . $qrBuscaProdutos['NOM_VENDEDOR'] . "\",\"" . $qrBuscaProdutos['NOM_ATENDENTE'] . "\")'><i class='fa fa-plus' aria-hidden='true'></i></a>";
															} else {

																$tag_campanha = "";
																//$tag_persona =  "";	
																$tag_persona =  "<li class='tag'><span class='label label-warning'>● &nbsp; " . $qrBuscaProdutos['DES_PERSONA'] . "</span></li>";
																$opcaoExpandir = "";
															}


															if (strlen($qrBuscaProdutos['DAT_EXPIRA']) == 0 || $qrBuscaProdutos['DAT_EXPIRA'] == "1969-12-31") {
																$data = " ";
															} else {
																$data = date("d/m/Y", strtotime($qrBuscaProdutos['DAT_EXPIRA']));
															}
														}

														if ($qrBuscaProdutos['STATUS_AVULSO'] == 16) {
															$dataLancamento = 	fnDataFull($qrBuscaProdutos['DAT_CADASTR']);
															$codLancamento = $qrBuscaProdutos['COD_CREDITO'];
														} else {
															$dataLancamento = 	fnDataFull($qrBuscaProdutos['DAT_CADASTR2']);
															$codLancamento = $qrBuscaProdutos['COD_VENDAPDV'];
														}


														echo "
											<tr id=" . "cod_credito_" . $qrBuscaProdutos['COD_CREDITO'] . ">															
											  <td class='text-center'>" . $opcaoExpandir . "</td>
										      <td><small>" . $dataLancamento . "</small></td>
											  <td>" . $qrBuscaProdutos['COD_VENDA'] . "</td>												
											  <td>" . $codLancamento . "</td>												
											  <td>" . $qrBuscaProdutos['COD_CUPOM'] . "</td>												
											  <td class='text-center " . $textRed . " '>" . $qrBuscaProdutos['TIP_CREDITO'] . "</td>												
											  <td class='text-right " . $textRed . " " . $textRed . "'>" . fnValor($valorCred, 2) . "</td>
											  <td class='text-right " . $textRed . " '>" . fnValor($valorDeb, 2) . "</td>
											  <td><small>" . $data . "</small></td>												
											  <td class='text-center'><span class='" . $badge . " text-center' style='" . $cor . "'><span class='" . $txtBadge . " " . $textRed . "'>" . $mostra_expira . "</span></span></td>		
											  <td>" . $qrBuscaProdutos['DES_ABREVIA'] . "</td>												
											  <td class='" . $textRed . "'>" . $qrBuscaProdutos['DES_STATUSCRED'] . "</td>
											  <td>" . $qrBuscaProdutos['NOM_FANTASI'] . "</td>";
														if (fnDecode($_GET['mod']) != "1211") {
															echo "
											  <td>" . $tag_campanha . "</td>												
											  <td>" . $tag_persona . "</td>";
														}
														echo "											
											</tr>";
														echo "
											<tr style='display:none; background-color: #fff;' id='abreDetail_" . $qrBuscaProdutos['COD_CREDITO'] . "' idvenda='" . $qrBuscaProdutos['COD_VENDA'] . "'>
												<td></td>
												<td colspan='14'>
												<div id='mostraDetail_" . $qrBuscaProdutos['COD_CREDITO'] . "'>
												</div>
												</td>
											</tr>														  
											";
													}

													?>

												</tbody>
											</table>
											<div id="carregarMaisAjax"></div>
											<input type="hidden" name="TEM_PRODAUX" id="TEM_PRODAUX" value="<?php echo $tem_prodaux; ?>">
										</div>
									</div>
									<tfoot>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"><i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
											</th>
										</tr>
									</tfoot>

									<div class="row">
										<div class="col-md-12 text-center">
											<button type="button" class="btn btn-info btn-hg carregarMais">Carregar mais</button>
										</div>
									</div>

									<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
									<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
									<input type="hidden" name="opcao" id="opcao" value="">
									<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
									<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						</form>
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

	<script type="text/javascript">
		let itens_carregar_mais = 0;

		$(document).ready(function() {

			$(".calcula").change(function() {
				recalcula();
			});

			$('.push100').css('height', '40px');

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//modal close
			$('.modal').on('hidden.bs.modal', function() {

				if ($('#REFRESH_CLIENTE').val() == "S") {
					var newCli = $('#NOVO_CLIENTE').val();
					window.location.href = "action.php?mod=<?php echo fnEncode(1081); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=" + newCli + " ";
					$('#REFRESH_PRODUTOS').val("N");
				}

			});

			$(".carregarMais").click(function() {
				itens_carregar_mais += 15;
				$.ajax({
					type: "POST",
					url: "ajxCarteiraHistorico.do?opcao=carregarMais&id=<?php echo fnEncode($cod_empresa); ?>&itens_carregar_mais=" + itens_carregar_mais + "&casasDec=<?php echo '2'; ?>&mod=<?php echo $mod; ?>",
					data: $('#formulario').serialize(),
					beforeSend: function() {
						$('#carregarMaisAjax').html('<div class="loading" style="width: 100%;"></div>');
					},
					success: function(data) {
						//console.log(data);	
						$(data).hide().appendTo("#relatorioConteudo").fadeIn(1000);
						$('[id^="abreDetail_"]').hide();
						$('#carregarMaisAjax').html('');
						setTimeout(function() {
							$('html, body').animate({
								scrollTop: $("#carregarMaisAjax").offset().top
							}, 1000);
						}, 500);
						console.log(data);
					},
					error: function() {
						$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
					}
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
											url: "ajxCarteiraHistorico.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&itens_carregar_mais=" + itens_carregar_mais,
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
		});

		function recalcula() {

			var valTotal = 0;
			$('.calcula').each(function(index, item) {
				if ($(item).val() != "") {
					if ($(item).attr('id') == "VAL_RESGATE" || $(item).attr('id') == "VAL_DESCONTO") {
						valTotal = valTotal - limpaValor($(item).val());
					} else {
						valTotal = valTotal + limpaValor($(item).val());
					}
				}
			});
			$('#VAL_TOTVENDA').val();
			$('#VAL_TOTVENDA').unmask();
			$('#VAL_TOTVENDA').val(valTotal.toFixed(2));
			$('#VAL_TOTVENDA').mask("#.##0,00", {
				reverse: true
			});

		}

		function abreDetail(idCredito, idVenda, codItemven, nom_vendedor, nom_atendente) {
			RefreshProdutos(<?php echo $cod_empresa; ?>, idCredito, idVenda, codItemven, nom_vendedor, nom_atendente);
			//alert(codItemven);
		}

		function RefreshProdutos(idEmp, idCredito, pIdVenda, pCodItemven, nom_vendedor, nom_atendente) {
			var idItem = $('#abreDetail_' + idCredito);

			if (!idItem.is(':visible')) {
				$.ajax({
					type: "GET",
					url: "ajxProdutosVenda.do",
					data: {
						cod_empresa: idEmp,
						idVenda: pIdVenda,
						codItemven: pCodItemven,
						page: 2,
						opcao: 'mostrarDetalhe',
						NOM_VENDEDOR: nom_vendedor,
						NOM_ATENDENTE: nom_atendente
					},
					beforeSend: function() {
						$("#mostraDetail_" + idCredito).html('<div class="loading" style="width: 100%;"></div>');
					},
					success: function(data) {
						$("#mostraDetail_" + idCredito).html(data);
					},
					error: function() {
						$("#mostraDetail_" + idCredito).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});

				idItem.show();

				$('#cod_credito_' + idCredito).find($(".fa")).removeClass('fa-plus').addClass('fa-minus');
			} else {
				idItem.hide();
				$('#cod_credito_' + idCredito).find($(".fa")).removeClass('fa-minus').addClass('fa-plus');
			}
		}


		function RefreshProdutosExc(idEmp, idOrc, tipo, idItem) {
			$.ajax({
				type: "GET",
				url: "ajxListaOrcamento.php",
				data: {
					ajx1: idEmp,
					ajx2: idOrc,
					ajx3: tipo,
					ajx4: idItem
				},
				beforeSend: function() {
					$('#div_Produtos').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#div_Produtos").html(data);
					//recalcula();					
				},
				error: function() {
					$('#div_Produtos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});
		}

		function retornaForm(index) {

		}
	</script>