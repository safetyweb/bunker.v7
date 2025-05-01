<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$tipoVenda = "";
$hashLocal = "";
$hoje = "";
$request = "";
$msgRetorno = "";
$msgTipo = "";
$des_email = "";
$num_cgcecpf = "";
$num_celular = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$checkTodas = "";
$checkCreditos = "";
$arrayParamAutorizacao = "";
$autoriza = "";
$lojasSelecionadas = "";
$cod_univendUsu = "";
$qtd_univendUsu = "";
$lojasAut = "";
$usuReportAdm = "";
$lojasReportAdm = "";
$formBack = "";
$andEmail = "";
$andCpf = "";
$andCelular = "";
$contador = "";
$contador_por_pagina = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$mostraAtivo = "";
$sqlToken = "";
$vendaFim = "";
$totalVenda = "";
$content = "";
$itens_por_pagina = 50;
$pagina = 1;


$tipoVenda = "T";
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));
// $cod_univend = "9999"; //todas revendas - default

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		// $cod_univend = @$_POST['COD_UNIVEND'];
		$des_email = fnLimpaCampo(@$_POST['DES_EMAIL']);
		$num_cgcecpf = fnLimpaDoc(fnLimpaCampo(@$_POST['NUM_CGCECPF']));
		$num_celular = fnLimpaDoc(fnLimpaCampo(@$_POST['NUM_CELULAR']));
		// fnEscreve($des_email);
		// fnEscreve($num_cgcecpf);
		// fnEscreve($num_celular);

		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		// $tipoVenda = @$_POST['tipoVenda'];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {
		}
	}
}

//busca dados url
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
	$nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}
// if (strlen($cod_univend ) == 0){
// 	$cod_univend = "9999"; 
// }

if ($tipoVenda == "T") {
	$checkTodas = "checked";
	$checkCreditos = "";
} else {
	$checkTodas = "";
	$checkCreditos = "checked";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";


//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

if (fnControlaAcesso("1024", $arrayParamAutorizacao) === true) {
	$autoriza = 1;
} else {
	$autoriza = 0;
}

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($lojasSelecionadas);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($lojasReportAdm);

?>

<style>
	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}
</style>

<div class="push30"></div>

<div class="row" id="div_Report">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				//$formBack = "1015";
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

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<!-- <div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Nome do Cliente</label>
												<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="50" data-error="Campo obrigatório" required>
													<div class="help-block with-errors"></div>
											</div>
										</div> -->

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Email</label>
										<input type="text" class="form-control input-sm" name="DES_EMAIL" id="DES_EMAIL" value="">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">CPF</label>
										<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="">
										<div class="help-block with-errors"></div>
									</div>
								</div>


								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Celular</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" id="NUM_CELULAR" value="">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>


						</fieldset>

						<div class="push20"></div>

						<div>
							<div class="row">
								<div class="col-md-12" id="div_Produtos">

									<div class="push20"></div>

									<?php
									$sql =
										"SELECT 
														SUM(SMS) SMS,
														SUM(EMAIL) EMAIL,
														SUM(GERAL) GERAL,
														SUM(SMS_USADO) SMS_USADO,
														SUM(EMAIL_USADO) EMAIL_USADO,
														SUM(GERAL_NAO_USADO) GERAL_NAO_USADO
														FROM (
															SELECT 
															case when T.TIP_TOKEN=1 then 1 ELSE 0 END SMS,
															case when T.TIP_TOKEN=2 then 1 ELSE 0 END EMAIL,
															case when T.TIP_TOKEN IN (1,2) then 1 ELSE 0 END GERAL,
															case when T.TIP_TOKEN=1 AND T.LOG_USADO=2 then 1 ELSE 0 END SMS_USADO,
															case when T.TIP_TOKEN=2 AND T.LOG_USADO=2 then 1 ELSE 0 END EMAIL_USADO,
															case when T.TIP_TOKEN IN (1,2) AND T.LOG_USADO=1 then 1 ELSE 0 END GERAL_NAO_USADO
															FROM tokenapp T
															WHERE T.DAT_CADASTR BETWEEN '$dat_ini 00:00:00'  AND '$dat_fim 23:59:59'
																$andEmail
																$andCpf
																$andCelular
																AND T.COD_EMPRESA = $cod_empresa
																AND T.COD_UNIVEND IN($lojasSelecionadas)
																ORDER BY T.COD_TOKEN desc
														)tmptokenapp
													";

									// fnEscreve($sql);


									$contador = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$contador_por_pagina = mysqli_fetch_assoc($contador);

									// print_r($contador_por_pagina);

									?>


									<table class="table table-hover">

										<thead>
											<tr>
												<th class="text-info text-center">Total Geral:<b> &nbsp; <?php echo fnValor($contador_por_pagina['GERAL'], 0); ?></b></th>
												<th class="text-info text-center">Total de SMS:<b> &nbsp; <?php echo fnValor($contador_por_pagina['SMS'], 0); ?></b></th>
												<th class="text-info text-center">Total de EMAIL:<b> &nbsp; <?php echo fnValor($contador_por_pagina['EMAIL'], 0); ?></b></th>
												<th class="text-info text-center">SMS Usado:<b> &nbsp; <?php echo fnValor($contador_por_pagina['SMS_USADO'], 0); ?></b></th>
												<th class="text-info text-center">Emails Usado:<b> &nbsp; <?php echo fnValor($contador_por_pagina['EMAIL_USADO'], 0); ?></b></th>
												<th class="text-info text-center">Total Geral Não Usado:<b> &nbsp; <?php echo fnValor($contador_por_pagina['GERAL_NAO_USADO'], 0); ?></b></th>
											</tr>
										</thead>

									</table>

									<div class="push10"></div>

									<table class="table table-bordered table-hover tablesorter">

										<thead>
											<tr>
												<th><small>Nome Loja</small></th>
												<th><small>Cliente</small></th>
												<th><small>CPF</small></th>
												<th><small>Celular</small></th>
												<th><small>E-mail</small></th>
												<th><small>Cadastro</small></th>
												<th><small>Status</small></th>
												<th><small>Chave</small></th>
												<th><small>Log</small></th>

												<!-- <th><small>PDV</small></th>
											  <th><small>Vendedor</small></th>-->
												<!-- <th><small>Token</small></th> -->
												<!-- <th><small>Token Gerado</small></th> -->
												<!-- <th><small>Status</small></th> -->
												<!-- <th><small>Conformidade</small></th> -->
											</tr>
										</thead>
										<tbody id="relatorioConteudo">
											<?php

											if ($des_email != "") {
												$andEmail = "AND T.DES_EMAIL = '$des_email'";
											} else {
												$andEmail = "";
											}

											if ($num_cgcecpf != "") {
												$andCpf = "AND T.NUM_CGCECPF = '$num_cgcecpf'";
											} else {
												$andCpf = "";
											}

											if ($num_celular != "") {
												$andCelular = "AND T.NUM_CELULAR = '$num_celular'";
											} else {
												$andCelular = "";
											}

											$sql = "SELECT count(*) as contador FROM tokenapp T
														WHERE T.DAT_CADASTR BETWEEN '$dat_ini 00:00:00'  AND '$dat_fim 23:59:59'
														$andEmail
														AND T.COD_EMPRESA = $cod_empresa
														AND T.COD_UNIVEND IN($lojasSelecionadas)";

											// fnEscreve($sql);

											$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$totalitens_por_pagina = mysqli_fetch_assoc($retorno);

											// print_r($totalitens_por_pagina['contador']);

											$numPaginas = ceil($totalitens_por_pagina['contador'] / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

											//=======================================================
											// $sql = "
											// 		SELECT 
											// 		V.NOM_FANTASI,
											// 		T.NOM_CLIENTE,
											// 		T.NUM_CGCECPF,
											// 		T.NUM_CELULAR,
											// 		T.DES_EMAIL,
											// 		T.LOG_USADO,
											// 		T.DAT_CADASTR
											// 		FROM tokenapp T
											// 		INNER JOIN unidadevenda V ON V.COD_UNIVEND = T.COD_UNIVEND
											// 		WHERE T.DAT_CADASTR BETWEEN '$dat_ini 00:00:00'  AND '$dat_fim 23:59:59'
											// 		$andEmail
											// 		$andCpf
											// 		$andCelular
											// 		AND T.COD_EMPRESA = $cod_empresa
											// 		AND T.COD_UNIVEND IN($lojasSelecionadas)
											// 		order by T.COD_TOKEN desc
											// 		LIMIT $inicio, $itens_por_pagina
											// 		  ";

											$sql = "SELECT V.NOM_FANTASI, 
																T.NOM_CLIENTE, 
																T.NUM_CGCECPF, 
																T.NUM_CELULAR, 
																T.DES_EMAIL, 
																T.LOG_USADO, 
																T.DAT_CADASTR,
																ret.DES_STATUS,
																ret.CHAVE_CLIENTE
														FROM tokenapp T
														INNER JOIN unidadevenda V ON V.COD_UNIVEND = T.COD_UNIVEND
														left JOIN sms_lista_ret ret ON ret.COD_CLIENTE=T.COD_CLIENTE 
																					AND DATE(ret.DAT_CADASTR)=DATE(T.DAT_CADASTR) 
																					AND T.NUM_CELULAR!=''
														WHERE T.DAT_CADASTR BETWEEN '$dat_ini 00:00:00'  AND '$dat_fim 23:59:59'
														$andEmail
														$andCpf
														$andCelular
														AND T.COD_EMPRESA = $cod_empresa 
														AND T.COD_UNIVEND IN($lojasSelecionadas)
														ORDER BY T.COD_TOKEN DESC
														LIMIT $inicio, $itens_por_pagina";


											// fnEscreve($sql);											
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$countLinha = 1;
											while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
												if ($qrListaVendas['LOG_USADO'] == '2') {
													$mostraAtivo = '<i class="fa fa-check" aria-hidden="true" style="color:#32cd32"></i>';
												} elseif ($qrListaVendas['LOG_USADO'] == '1') {
													$mostraAtivo = '<i class="fa fa-times" aria-hidden="true" style="color:red"></i>';
												}
												//fnEscreve($sqlToken);

												// echo "<pre>";
												// print_r($qrListaVendas);
												// echo "</pre>";												

											?>
												<tr>
													<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
													<?php
													if ($autoriza == 1) {
													?>
														<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['NOM_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
													<?php
													} else {
													?>
														<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
													<?php
													}
													?>
													<!-- <td><small><?php echo $qrListaVendas['NOM_CLIENTE']; ?></small></td> -->
													<td><small><?php echo $qrListaVendas['NUM_CGCECPF']; ?></small></td>
													<td><small><?php echo $qrListaVendas['NUM_CELULAR']; ?></small></td>
													<td><small><?php echo $qrListaVendas['DES_EMAIL']; ?></small></td>
													<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
													<td><small><?php echo $qrListaVendas['DES_STATUS']; ?></small></td>
													<td><small><?php echo $qrListaVendas['CHAVE_CLIENTE']; ?></small></td>
													<!-- <td><small><?php echo $qrListaVendas['LOG_USADO']; ?></small></td> -->
													<td align='center'><small><?php echo $mostraAtivo; ?></small></td>

												</tr>

											<?php

												$vendaFim = $qrListaVendas['DAT_CADASTR'];
												$countLinha++;
											}

											?>


										</tbody>

										<tfoot>
											<!--
												<tr>
												  <th>
												  <?php echo $countLinha - 1; ?>
												  </th>
												  <th class="" colspan="5">											  
												  </th>
												  <th class="text-right" width="100">
												  R$ <?php echo fnValor($totalVenda, 2); ?>
												  </th>
												  <th class="" colspan="100">
												  </th>
												</tr>
												-->
											<tr>
												<td class="text-left">
													<small>
														<div class="btn-group dropdown left">
															<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
																&nbsp; Exportar &nbsp;
																<span class="fas fa-caret-down"></span>
															</button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																<li><a class="btn btn-sm exportarCSV" data-exportar="geral">&nbsp;Exportar Geral</a></li>
																<li><a class="btn btn-sm exportarCSV" data-exportar="email">&nbsp;Exportar Email</a></li>
																<li><a class="btn btn-sm exportarCSV" data-exportar="sms">&nbsp;Exportar SMS</a></li>
																<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
															</ul>
														</div>
													</small>
												</td>
												<!-- <th colspan="100">
														<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
													</th> -->
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
						</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />



<script>
	//datas
	$(function() {

		$.tablesorter.addParser({
			id: "moeda",
			is: function(s) {
				return true;
			},
			format: function(s) {
				return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9,]/g), ""));
			},
			type: "numeric"
		});

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		var SPMaskBehavior = function(val) {
				return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
				onKeyPress: function(val, e, field, options) {
					field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};

		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		$("#DAT_FIM_GRP").on("dp.change", function(e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});

		$(".exportarCSV").click(function() {
			let opcao = $(this).attr("data-exportar");
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
										url: "relatorios/ajxRelTokenSenhaApp.do?opcao=" + opcao + "&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelTokenSenhaApp.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				$(".tablesorter").trigger("updateAll");
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	// function abreDetail(idBloco){
	// 	var idItem = $('.abreDetail_' + idBloco)
	// 	if (!idItem.is(':visible')){
	// 		idItem.show();
	// 		$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
	// 	}else{
	// 		idItem.hide();
	// 		$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
	// 	}
	// }
</script>