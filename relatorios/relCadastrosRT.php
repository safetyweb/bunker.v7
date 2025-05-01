<?php

// echo fnDebug('true');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


$hashLocal = mt_rand();

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

$dias30 = '';
$dat_ini = '';
$dat_fim = '';
$totalVenda = 0;
$totalFidelizado = 0;
$totalAvulso = 0;
$totalCliente = 0;
$totalMasculino = 0;
$totalFeminino = 0;
$totalIndefinido = 0;



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate($valorDataPrimeiraVenda['primeira_venda']);
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

//busca revendas do usuário
include "unidadesAutorizadas.php";

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//fnMostraForm();
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojas);
//fnEscreve($lojasAut);
//fnEscreve($cod_univend);
//fnEscreve($lojasSelecionadas);

$hor_ini = " 00:00:00";
$hor_fim = " 23:59:59";

?>
<script src="js/pie-chart.js"></script>

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

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
				</div>
				<?php
				include "backReport.php";
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

							<div class="row">

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
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>

					</form>
				</div>
			</div>
		</div>

		<div class="push30"></div>

		<div class="portlet portlet-bordered">
			<div class="portlet-body">
				<div class="login-form">

					<div class="row text-center">

						<div class="form-group text-center col-lg-12">
							<h4>Visão Geral dos Cadastros</h4>
							<div class="push20"></div>

							<?php

							// Filtro por Grupo de Lojas
							include "filtroGrupoLojas.php";

							//total de cadastros com loja
							$sql = "select count(1) as TOTAL_CADASTRO
														from clientes where cod_empresa = $cod_empresa
														AND DAT_CADASTR between '$dat_ini $hor_ini' 
														AND '$dat_fim $hor_fim'
														AND COD_UNIVEND IN($lojasSelecionadas)
														AND COD_EXCLUSA = 0
														";

							//fnEscreve($sql);	

							$arrayQuery = mysqli_query($conn, $sql);
							$qrTotalCadastro = mysqli_fetch_assoc($arrayQuery);

							$total_cadastro = $qrTotalCadastro['TOTAL_CADASTRO'];

							?>

							<?php

							//total de cadastros sem loja
							$sql2 = "select count(1) as TOTAL_CADASTRO_0
														from clientes where cod_empresa = $cod_empresa
														AND DAT_CADASTR between '$dat_ini $hor_ini' 
														AND '$dat_fim $hor_fim'
														AND COD_UNIVEND = 0
														AND COD_EXCLUSA = 0
														";

							//fnEscreve($sql2);	

							$arrayQuery = mysqli_query($conn, $sql2);
							$qrTotalCadastro_0 = mysqli_fetch_assoc($arrayQuery);

							$total_cadastro_0 = $qrTotalCadastro_0['TOTAL_CADASTRO_0'];

							?>
							<style>
								.content-top {
									background-color: #f8f8f8;
									border: 0px;
									min-height: 100px;
									border-radius: 4px;
									-webkit-border-radius: 4px;
									-o-border-radius: 4px;
									-moz-border-radius: 4px;
									-ms-border-radius: 4px;
									-webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
									box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
								}
							</style>

							<div class="col-md-4">
								<div class="content-top">
									<div class="col-md-8 top-content">
										<p>Cadastros</p>
										<label><?php echo fnValor($total_cadastro, 0); ?></label>
										<?php if ($total_cadastro_0 > 0) { ?>
											<br />
											<span class="bg-danger f12" style="padding: 1px 4px; color: #fff; border-radius: 3px;"><?php echo fnValor($total_cadastro_0, 0); ?></span>
										<?php } ?>
									</div>
									<div class="col-md-4">
										<div id="main-pie" class="pie-title-center" data-percent="100">
											<span class="pie-value">100%</span>
										</div>
									</div>
									<div class="clearfix"> </div>
								</div>
							</div>

							<?php

							// Filtro por Grupo de Lojas
							include "filtroGrupoLojas.php";

							//busca cadastro - loop																						


							$sql = "select A.NOM_FANTASI,
														COUNT(COD_CLIENTE) AS TOTAL_CLIENTE
														from webtools.unidadevenda A
														LEFT JOIN CLIENTES ON CLIENTES.COD_UNIVEND =  A.COD_UNIVEND  
														AND CLIENTES.DAT_CADASTR between '$dat_ini $hor_ini' 
														AND '$dat_fim $hor_fim'
														WHERE A.COD_EMPRESA = $cod_empresa
														AND A.COD_UNIVEND IN($lojasSelecionadas)
														AND A.COD_EXCLUSA = 0
														GROUP BY A.COD_UNIVEND
														order by A.NOM_UNIVEND;															
														";

							//fnEscreve($sql);	

							$arrayQuery = mysqli_query($conn, $sql);
							$countPie = 1;
							while ($qrBuscaDados = mysqli_fetch_assoc($arrayQuery)) {
								//$cod_univend = $qrBuscaDados['COD_UNIVEND'];
								$nom_univend = $qrBuscaDados['NOM_FANTASI'];
								//$venda_total = $qrBuscaDados['VENDA_TOTAL'];
								$total_cliente = $qrBuscaDados['TOTAL_CLIENTE'];

								$pct_cadUnive = ($total_cadastro != 0) ? ($total_cliente * 100) / $total_cadastro : 0;

							?>

								<div class="col-md-4">
									<div class="content-top">
										<div class="col-md-8 top-content">
											<p style="font-size: 14px;"><?php echo $nom_univend; ?></p>
											<label><?php echo fnValor($total_cliente, 0); ?></label>
										</div>
										<div class="col-md-4">
											<div id="pie-<?php echo $countPie; ?>" class="pie-title-center" data-percent="<?php echo fnValor($pct_cadUnive, 0); ?>">
												<span class="pie-value"><?php echo fnValor($pct_cadUnive, 2); ?>%</span>
											</div>
										</div>
										<div class="clearfix"> </div>
									</div>
								</div>

							<?php
								$countPie++;
							}
							?>



						</div>

					</div>

				</div>
			</div>
		</div>

		<div class="push30"></div>

		<div class="portlet portlet-bordered">
			<div class="portlet-body">
				<div class="login-form">

					<div class="row text-center">

						<div class="form-group text-center col-lg-12">
							<h4>Visão Geral de Vendas X Cadastros </h4>
							<div class="push20"></div>


							<table class="table table-bordered table-hover">

								<thead>
									<tr>
										<th class="f14 text-center"><b><span class="fa fa-map-marker"></span>&nbsp; Loja</b></th>
										<th class="f14 text-center"><b><span class="fa fa-shopping-basket"></span>&nbsp; Vendas <br />Total</th>
										<th class="f14 text-center"><b><span class="fa fa-shopping-bag"></span>&nbsp; Vendas <br />Fidelizadas</th>
										<th class="f14 text-center"><b><span class="fa fa-eye-slash"></span>&nbsp; Vendas <br />Avulsas</th>
										<!--<th class="f14 text-center"><b><span class="fa fa-user"></span>&nbsp; Vendas<br/>Clientes </th>
													  <th class="f14 text-center"><b><span class="fa fa-retweet"></span>&nbsp;Vendas <br>Outros Canais</th>-->
										<th class="f14 text-center"><b><span class="fa fa-users"></span>&nbsp; Cadastros</th>
										<th class="f14 text-center"><b><span class="fa fa-male"></span>&nbsp; Masculino</th>
										<th class="f14 text-center"><b><span class="fa fa-female"></span>&nbsp; Feminino</th>
										<th class="f14 text-center"><b><span class="fa fa-venus-mars"></span>&nbsp; Indefinido</b></th>
									</tr>
								</thead>

								<?php

								// Filtro por Grupo de Lojas
								include "filtroGrupoLojas.php";

								//busca resgates - loop															

								$sql = "select A.COD_UNIVEND,
																A.NOM_FANTASI,
																
																SUM(QTD_VENDA) TOTAL_VENDAS, 
																SUM(CASE WHEN B.COD_AVULSO=2 THEN QTD_VENDA ELSE 0 END) VENDAS_CLIENTES, 
																SUM(CASE WHEN B.COD_AVULSO=1 THEN QTD_VENDA ELSE 0 END) VENDAS_AVULSA,																
																
															   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' ) TOT_CLIENTE,
															   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND D.COD_SEXOPES=1) TOT_MASCULINO,
															   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND D.COD_SEXOPES=2) TOT_FEMININO,
															   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND D.COD_SEXOPES=3) TOT_INDEFINIDO
														
														from webtools.unidadevenda A
														LEFT   JOIN VENDAS B ON B.COD_UNIVEND=A.COD_UNIVEND AND B.DAT_CADASTR_WS between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND B.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9)
														WHERE A.COD_EMPRESA = $cod_empresa
														AND A.COD_UNIVEND IN($lojasSelecionadas)
														AND A.cod_exclusa = 0
														GROUP BY A.COD_UNIVEND
														ORDER by A.NOM_FANTASI; ";

								/*
												$sql = "select A.COD_UNIVEND,
																A.NOM_FANTASI,
																
																COUNT(COD_VENDA) TOTAL_VENDAS,
																SUM(CASE WHEN C.LOG_AVULSO='N' THEN
																1
																ELSE
																0
																END) VENDAS_CLIENTES,
																SUM(CASE WHEN C.LOG_AVULSO='S' THEN
																1
																ELSE
															    0
																END) VENDAS_AVULSA,														
																
															   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' ) TOT_CLIENTE,
															   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND D.COD_SEXOPES=1) TOT_MASCULINO,
															   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND D.COD_SEXOPES=2) TOT_FEMININO,
															   (SELECT COUNT(1) FROM CLIENTES D WHERE D.COD_UNIVEND=A.COD_UNIVEND AND D.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND D.COD_SEXOPES=3) TOT_INDEFINIDO
														
														from webtools.unidadevenda A
														LEFT   JOIN VENDAS B ON B.COD_UNIVEND=A.COD_UNIVEND AND B.DAT_CADASTR between '$dat_ini $hor_ini' and '$dat_fim $hor_fim' AND B.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9)
														LEFT   JOIN CLIENTES C ON B.COD_CLIENTE=C.COD_CLIENTE
														WHERE A.COD_EMPRESA = $cod_empresa
														AND A.COD_UNIVEND IN($lojasSelecionadas)
														AND A.cod_exclusa = 0
														GROUP BY A.COD_UNIVEND
														ORDER by A.NOM_FANTASI; ";
												*/

								//fnEscreve($sql);												
								//fnTestesql(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());	
								$arrayQuery = mysqli_query($conn, $sql);


								while ($qrBuscaDados = mysqli_fetch_assoc($arrayQuery)) {
									$nom_univend = @$qrBuscaDados['NOM_FANTASI'];
									$venda_total = @$qrBuscaDados['TOTAL_VENDAS'];
									$clientes_compra = @$qrBuscaDados['VENDAS_CLIENTES'];
									$total_cliente = @$qrBuscaDados['TOT_CLIENTE'];
									$clientes = @$qrBuscaDados['CLIENTES'];
									$avulso = @$qrBuscaDados['VENDAS_AVULSA'];
									//$clientes_outras = $qrBuscaDados['CLIENTES_OUTRAS'];
									$masculino = @$qrBuscaDados['TOT_MASCULINO'];
									$feminino = @$qrBuscaDados['TOT_FEMININO'];
									$indefinido = @$qrBuscaDados['TOT_INDEFINIDO'];

									$totalVenda = $totalVenda + $venda_total;
									$totalFidelizado = $totalFidelizado + ($venda_total - $avulso);
									$totalAvulso = $totalAvulso + $avulso;
									//$totalCliCompra = $totalCliCompra + $clientes_outras;
									$totalCliente = $totalCliente + $total_cliente;
									$totalMasculino = $totalMasculino + $masculino;
									$totalFeminino = $totalFeminino + $feminino;
									$totalIndefinido = $totalIndefinido + $indefinido;
								?>

									<tr>
										<td><?php echo $nom_univend; ?></td>
										<td class="text-right"><b class="f14 text-info"><?php echo fnValor($venda_total, 0); ?></b></td>
										<td class="text-right"><b class="f14 text-info"><?php echo fnValor(($venda_total - $avulso), 0); ?></b></td>
										<td class="text-right"><b class="f14 text-info"><?php echo fnValor($avulso, 0); ?></b></td>
										<!--<td class="text-right"><b class="f14 text-info"><?php echo fnValor($clientes_compra, 0); ?></b></td>
													  <td class="text-right"><a href=""><b class="f14"><?php echo fnValor($clientes_outras, 0); ?></b></a></td>-->
										<td class="text-right"><b class="f14 text-info"><?php echo fnValor($total_cliente, 0); ?></b></td>
										<td class="text-right"><b class="f14 text-info"><?php echo fnValor($masculino, 0); ?></b></td>
										<td class="text-right"><b class="f14 text-info"><?php echo fnValor($feminino, 0); ?></b></td>
										<td class="text-right"><b class="f14 text-info"><?php echo fnValor($indefinido, 0); ?></b></td>
									</tr>

								<?php
								}
								?>

								</tbody>

								<tfoot>
									<tr>
										<th class="f14 text-right"></th>
										<th class="f14 text-right"><b><?php echo fnValor($totalVenda, 0); ?></b></th>
										<th class="f14 text-right"><b><?php echo fnValor($totalFidelizado, 0); ?></b></th>
										<th class="f14 text-right"><b><?php echo fnValor($totalAvulso, 0); ?></b></th>
										<th class="f14 text-right"><b><?php echo fnValor($totalCliente, 0); ?></b></th>
										<th class="f14 text-right"><b><?php echo fnValor($totalMasculino, 0); ?></b></th>
										<th class="f14 text-right"><b><?php echo fnValor($totalFeminino, 0); ?></b></th>
										<th class="f14 text-right"><b><?php echo fnValor($totalIndefinido, 0); ?></b></th>
									</tr>
									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
										</th>
									</tr>
								</tfoot>

							</table>

						</div>

					</div>

					<div class="push30"></div>


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

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script>
	//datas
	$(function() {

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
										url: "relatorios/ajxRelCadastrosRT.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	//graficos
	$(document).ready(function() {

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$('#main-pie').pieChart({
			barColor: '#2c3e50',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				$(this.element).find('.pie-value').text(Math.round(percent) + '%');
			}
		});

		<?php
		//fnEscreve($countPie-1);
		for ($i = 1; $i < ($countPie); $i++) {
		?>
			$('#pie-<?php echo $i; ?>').pieChart({
				barColor: '#3bb2d0',
				trackColor: '#eee',
				lineCap: 'round',
				lineWidth: 8,
				onStep: function(from, to, percent) {
					$(this.element).find('.pie-value').text(Math.round(percent) + '%');
				}
			});

		<?php
		}
		?>



	});
</script>