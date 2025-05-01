<?php
// include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$array = "";
$key = "";
$default = "";
$hoje = "";
$dias30 = "";
$itens_por_pagina = "";
$pagina = "";
$hashLocal = "";
$cod_empresa = "";
$sql = "";
$request = "";
$msgRetorno = "";
$msgTipo = "";
$cod_univend = "";
$dat_ini = "";
$dat_fim = "";
$log_aceite = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$tip_retorno = "";
$casasDec = "";
$checkAceites = "";
$arrayParamAutorizacao = "";
$autoriza = "";
$cod_persona = "";
$ARRAY_UNIDADE1 = "";
$ARRAY_UNIDADE = "";
$lojasSelecionadas = "";
$andAceite = "";
$qrClientesTop100 = "";
$NOM_ARRAY_UNIDADE = "";
$loja = "";
$aceite = "";
$mostraSexo = "";
$email = "";
$ticketMedio = "";
$sqlCel = "";
$arrayCel = "";
$qrCel = "";
$colCliente = "";
$totalCompras = 0;
$totalQtd = 0;
$totalCred = 0;
$totalTM = 0;
$content = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}


//echo fnDebug('true');

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode(getInput($_GET, 'id'));

$sql = "SELECT NOM_FANTASI, TIP_RETORNO
	FROM empresas where COD_EMPRESA = $cod_empresa ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(getInput($_POST, 'COD_EMPRESA'));
		$cod_univend = getInput($_POST, 'COD_UNIVEND');
		$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
		$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
		if (empty($_REQUEST['LOG_ACEITE'])) {
			$log_aceite = 'N';
		} else {
			$log_aceite = $_REQUEST['LOG_ACEITE'];
		}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

if ($tip_retorno == 1) {
	$casasDec = 0;
} else {
	$casasDec = 2;
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

$checkAceites = "";

if ($log_aceite == 'S') {
	$checkAceites = "checked";
}

//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

if (fnControlaAcesso("1081", $arrayParamAutorizacao) === true) {
	$autoriza = 1;
} else {
	$autoriza = 0;
}

//fnEscreve($cod_empresa); 	
//fnEscreve($cod_persona); 	
//fnMostraForm();

?>


<style>
	input[type="search"]::-webkit-search-cancel-button {
		height: 16px;
		width: 16px;
		background: url(images/close-filter.png) no-repeat right center;
		position: relative;
		cursor: pointer;
	}

	input.tableFilter {
		border: 0px;
		background-color: #fff;
	}

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

<div class="row">

	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?> </span>
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

					<div class="login-form">



						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
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
									<div class="form-group">
										<label for="inputName" class="control-label">Somente Aceites LGPD</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ACEITE" id="LOG_ACEITE" class="switch" value="S" <?= $checkAceites ?>>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>
					</div>
				</div>
			</div>

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">


						<div class="push20"></div>


						<div>
							<div class="row">
								<div class="col-lg-12">

									<div class="no-more-tables">


										<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
											<thead>
												<tr>
													<th><small>Nome</small></th>
													<th><small>Cartão</small></th>
													<th><small>e-Mail</small></th>
													<th><small>Celular</small></th>
													<th class="{sorter:false}"><small>Sexo</small></th>
													<th><small>Nascimento</small></th>
													<th><small>Compras</small></th>
													<th><small>Qtd.</small></th>
													<th><small>Ticket Médio</small></th>
													<th><small>Créditos/Pontos no Período</small></th>
													<th><small>Loja de Cadastro</small></th>
													<th class="{sorter:false}"><small>Aceite LGPD</small></th>
												</tr>
											</thead>

											<tbody id="relatorioConteudo">

												<?php

												/*$ARRAY_UNIDADE1=array(
																			   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
																			   'cod_empresa'=>$cod_empresa,
																			   'conntadm'=>$connAdm->connAdm(),
																			   'IN'=>'N',
																			   'nomecampo'=>'',
																			   'conntemp'=>'',
																			   'SQLIN'=> ""   
																			   );
																$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);*/

												/*$sql = "CALL SP_RELAT_TOP100CLIENTES(
																	".$cod_empresa.",
																	'".fnDataSql($dat_ini)."',
																	'".fnDataSql($dat_fim)."',
																	'".$lojasSelecionadas."'  
																	) ";*/

												$andAceite = "";

												if ($log_aceite == 'S') {
													$andAceite = "AND CLI.LOG_TERMO = 'S'";
												}

												$sql = "SELECT 
																			COD_CLIENTE,
																			NUM_CARTAO,
																			NUM_CGCECPF,
																			NOM_CLIENTE,   
																			DES_EMAILUS,
																			DAT_NASCIME, 
																			COD_SEXOPES,
																			COD_UNIVEND,
																			NOM_FANTASI,					      
																			DAT_REPROCE,
																			SUM(VAL_CREDITO) VAL_CREDITO,
																			COUNT(DISTINCT(COD_VENDA)) AS QTD_COMPRAS,
																			SUM(VAL_TOTPRODU) AS VAL_COMPRAS,
																			LOG_TERMO
  																	       FROM (
																					SELECT 
																							CLI.COD_CLIENTE,
																							CLI.NUM_CARTAO,
																							CLI.NUM_CGCECPF,
																							CLI.NOM_CLIENTE,   
																							CLI.DES_EMAILUS,
																							CLI.DAT_NASCIME, 
																							CLI.COD_SEXOPES,
																							UNI.COD_UNIVEND,
																							UNI.NOM_FANTASI,					      
																							(SELECT  Min(CRED.dat_reproce) FROM creditosdebitos CRED 
																							WHERE CRED.cod_venda = VEN.cod_venda
																							AND CRED.cod_statuscred IN ( 0, 1, 2, 3, 4, 5, 7, 8, 9 )
																							AND CRED.tip_credito = 'C' )  AS DAT_REPROCE,
																							(SELECT val_credito FROM creditosdebitos CRED 
																							WHERE CRED.cod_venda = VEN.cod_venda
																							AND CRED.cod_statuscred IN ( 0, 1, 2, 3, 4, 5, 7, 8, 9 )
																							AND CRED.tip_credito = 'C' GROUP BY CRED.COD_VENDA)  VAL_CREDITO,
																						   	VEN.COD_VENDA,
																						   	VEN.VAL_TOTPRODU,
																						   	CLI.LOG_TERMO																			 
																					FROM vendas VEN
																					INNER JOIN CLIENTES CLI ON CLI.COD_CLIENTE=VEN.COD_CLIENTE AND CLI.COD_EMPRESA=VEN.COD_EMPRESA
																					LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=VEN.COD_UNIVEND											                                 
																					WHERE DATE(DAT_CADASTR_WS) BETWEEN '" . fnDataSql($dat_ini) . "' AND '" . fnDataSql($dat_fim) . "'
																					AND VEN.COD_EMPRESA=$cod_empresa
																					AND VEN.COD_AVULSO=2
																					AND  VEN.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9) 
																					AND VEN.COD_UNIVEND IN ($lojasSelecionadas)
																					$andAceite
																					) TMP_VENDA
																					 GROUP  BY COD_CLIENTE
																					ORDER  BY VAL_COMPRAS DESC  
																					LIMIT 100
																					";

												//fnEscreve($sql);																
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												$count = 0;
												while ($qrClientesTop100 = mysqli_fetch_assoc($arrayQuery)) {
													$count++;

													//fnEscreve($qrClientesTop100['COD_UNIVEND']);

													// fnEscreve($qrClientesTop100['COD_UNIVEND']);

													//$NOM_ARRAY_UNIDADE = (array_search($qrClientesTop100['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
													$loja = "";
													if ($qrClientesTop100['COD_UNIVEND'] != 0 && $qrClientesTop100['COD_UNIVEND'] != "") {
														//$loja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
														$loja = $qrClientesTop100['NOM_FANTASI'];
													}

													$aceite = "";

													if ($qrClientesTop100['LOG_TERMO'] == 'S') {
														//$loja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
														$aceite = "<span class='fal fa-check'></span>";
													}

													if ($qrClientesTop100['COD_SEXOPES'] == 1) {
														$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';
													} else {
														$mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>';
													}
													if ($qrClientesTop100['DES_EMAILUS'] == "") {
														$email = "e-mail não cadastrado!";
													} else {
														$email = fnMascaraCampo($qrClientesTop100['DES_EMAILUS']);
													}


													$ticketMedio = $qrClientesTop100['VAL_COMPRAS'] / $qrClientesTop100['QTD_COMPRAS'];

													$sqlCel = "SELECT NUM_CELULAR FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $qrClientesTop100[COD_CLIENTE]";
													$arrayCel = mysqli_query(connTemp($cod_empresa, ''), $sqlCel);
													$qrCel = mysqli_fetch_assoc($arrayCel);

													if ($autoriza == 1) {
														$colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1081) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrClientesTop100['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrClientesTop100['NOM_CLIENTE']) . "</a></small></td>";
													} else {
														$colCliente = "<td><small>" . fnMascaraCampo($qrClientesTop100['NOM_CLIENTE']) . "</small></td>";
													}

													echo "
														<tr>
															" . $colCliente . "
															<td><small>" . fnMascaraCampo($qrClientesTop100['NUM_CARTAO']) . "</small></td>
															<td><small>" . $email . "</small></td>
															<td><small>" . fnmasktelefone($qrCel['NUM_CELULAR']) . "</small></td>
															<td class='text-center'>" . $mostraSexo . "</td>
															<td><small>" . fnMascaraCampo($qrClientesTop100['DAT_NASCIME']) . "</small></td>
															<td class='text-center'><small>" . fnvalor($qrClientesTop100['VAL_COMPRAS'], 2) . "</small></td>
															<td class='text-center'><small>" . fnvalor($qrClientesTop100['QTD_COMPRAS'], 0) . "</small></td>
															<td class='text-center'><small>" . fnvalor($ticketMedio, 2) . "</small></td>
															<td class='text-center'><small>" . fnvalor($qrClientesTop100['VAL_CREDITO'], $casasDec) . "</small></td>
															<td><small>" . $loja . "</small></td>
															<td class='text-center'>" . $aceite . "</td>
														</tr>
													";

													$totalCompras += $qrClientesTop100['VAL_COMPRAS'];
													$totalQtd += $qrClientesTop100['QTD_COMPRAS'];
													$totalCred += $qrClientesTop100['VAL_CREDITO'];
												}

												$totalTM = $totalCompras / $totalQtd;

												?>

											</tbody>

											<tfoot>
												<tr>
													<th colspan="5"></th>
													<th class="text-center"><?php echo fnValor($totalCompras, 2); ?></th>
													<th class="text-center"><?php echo fnValor($totalQtd, 0); ?></th>
													<th class="text-center"><?php echo fnValor($totalTM, 2); ?></th>
													<th class="text-center"><?php echo fnValor($totalCred, 2); ?></th>
													<th></th>
												</tr>

												<tr>
													<th colspan="100">
														<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
													</th>
												</tr>
											</tfoot>

										</table>

										<div class="push"></div>

										<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
										<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
										<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">


									</div>

								</div>
							</div>
						</div>

						<div class="push"></div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>
	</form>
</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	$(document).ready(function() {

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

		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//table sorter
		$(function() {
			var tabelaFiltro = $('table.tablesorter')
			tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function() {
				$(this).prev().find(":checkbox").click()
			});
			$("#filter").keyup(function() {
				$.uiTableFilter(tabelaFiltro, this.value);
			})
			$('#formLista').submit(function() {
				tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
				return false;
			}).focus();
		});

		//pesquisa table sorter
		$('.filter-all').on('input', function(e) {
			if ('' == this.value) {
				var lista = $("#filter").find("ul").find("li");
				filtrar(lista, "");
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
										url: "relatorios/ajxRelClientesTop100.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
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

	$(document).on('change', '#COD_EMPRESA', function() {
		$("#dKey").val($("#COD_EMPRESA").val());
	});


	function page(index) {

		$("#pagina").val(index);
		$("#formulario")[0].submit();
		//alert(index);	

	}
</script>