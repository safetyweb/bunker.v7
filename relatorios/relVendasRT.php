<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$cod_univend = $_POST['COD_UNIVEND'];
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
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, DAT_CADASTR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

/*
	$sql = "select min(dat_cadastr) as primeira_venda from vendas where cod_empresa=$cod_empresa";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$valorDataPrimeiraVenda = mysqli_fetch_assoc($arrayQuery);
	*/

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate($valorDataPrimeiraVenda['primeira_venda']);
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

$cod_univend = "9999";

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}
if (strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}
//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

//fnMostraForm();
//fnEscreve(substr($listaDiarioDiasFideliz,0,-1));
//fnEscreve($hoje);
//fnEscreve($dias30);
//fnEscreve(strlen($dat_ini));
//fnEscreve(strlen($dat_fim));
//fnEscreve($data_fim);
//fnEscreve($cod_univend);

$hor_ini = " 00:00";
$hor_fim = " 23:59";

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
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
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
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
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
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

								<!--
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Unidade de Atendimento</label>
																<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
																	<option value=""></option>					
																	<?php
																	if ($cod_univend == "9999") {
																		echo "<option value='9999' selected>Todas Unidades</option>";
																	} else {
																		echo "<option value='9999'>Todas Unidades</option>";
																	}

																	$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' and cod_exclusa =0 order by NOM_UNIVEND ";
																	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

																	while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery)) {
																		if ($cod_univend == $qrListaUnidades['COD_UNIVEND']) {
																			$selecionado = "selected";
																		} else {
																			$selecionado = "";
																		}
																		echo "
																				  <option value='" . $qrListaUnidades['COD_UNIVEND'] . "' " . $selecionado . ">" . $qrListaUnidades['NOM_FANTASI'] . "</option> 
																				";
																	}
																	?>	
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Inicial</label>
															
															<div class="input-group date datePicker" id="DAT_INI_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required/>
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
																<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="push20"></div>
														<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>
													-->

							</div>

						</fieldset>

						<div class="push50"></div>

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
											<th class="f14 text-center"><b><span class="fa fa-users"></span>&nbsp; Cadastros</th>
											<th class="f14 text-center"><b><span class="fa fa-male"></span>&nbsp; Masculino</th>
											<th class="f14 text-center"><b><span class="fa fa-female"></span>&nbsp; Feminino</th>
											<th class="f14 text-center"><b><span class="fa fa-venus-mars"></span>&nbsp; Indefinido</b></th>
										</tr>
									</thead>

									<?php

									//busca resgates - loop															
									$sql = "select 
														uni.COD_UNIVEND, 
														uni.NOM_FANTASI, 
														Sum(Case When ven.COD_STATUSCRED IN (0,1,2,3,4,5,7,8,9) Then 1 Else 0 end) as VENDA_TOTAL,
													  
														(0) TOTAL_CLIENTE,
												   
														count(distinct case when ven.COD_UNIVEND = uni.COD_UNIVEND and cli.LOG_AVULSO='N'  Then  cli.COD_CLIENTE  else 0 end) as CLIENTES_COMPRA,          
													
														sum(case when cli.LOG_AVULSO = 'S' and ven.COD_STATUSCRED IN (0,1,2,3,4,5,7,8,9) Then 1 else 0 end) as AVULSO,
														 
														COUNT( DISTINCT case when cli.COD_UNIVEND=uni.COD_UNIVEND and 
														cli.DAT_CADASTR >= '$dat_ini  00:00' and 
														cli.DAT_CADASTR <= '$dat_fim  23:59' and
														cli.COD_SEXOPES = 1 and
														cli.LOG_AVULSO = 'N'  then 
														cli.COD_CLIENTE  else 0 end ) MASCULINO,
														 
														count(distinct case when cli.COD_UNIVEND=uni.COD_UNIVEND and 
														cli.DAT_CADASTR >= '$dat_ini  00:00' and 
														cli.DAT_CADASTR <= '$dat_fim  23:59' and
														cli.COD_SEXOPES = 2 and
														cli.LOG_AVULSO = 'N'  then 
														cli.COD_CLIENTE  else 0 end ) as FEMININO,
														
														sum(distinct case when cli.COD_UNIVEND=uni.COD_UNIVEND and 
														cli.DAT_CADASTR >= '$dat_ini  00:00' and 
														cli.DAT_CADASTR <= '$dat_fim  23:59' and
														cli.COD_SEXOPES = 3 and
														cli.LOG_AVULSO = 'N' then 
														cli.COD_CLIENTE  else 0 end ) as INDEFINIDO 
												  
													from webtools.unidadevenda uni
													Inner join vendas ven
															on ven.COD_EMPRESA = uni.COD_EMPRESA
														   and ven.COD_UNIVEND = uni.COD_UNIVEND
														   and ven.DAT_CADASTR_WS >= '$dat_ini  00:00' 
														   and ven.DAT_CADASTR_WS <= '$dat_fim  23:59'        
														   AND ven.DAT_CADASTR < NOW()  
													Inner join clientes cli 
															on cli.COD_CLIENTE = ven.COD_CLIENTE 
													where uni.COD_EMPRESA = $cod_empresa
													 
													group by uni.cod_univend 

													order by uni.NOM_UNIVEND; ";

									//fnEscreve($sql);	

									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

									while ($qrBuscaDados = mysqli_fetch_assoc($arrayQuery)) {
										$nom_univend = $qrBuscaDados['NOM_FANTASI'];
										$venda_total = $qrBuscaDados['VENDA_TOTAL'];
										$clientes_compra = $qrBuscaDados['CLIENTES_COMPRA'];
										//$total_cliente = $qrBuscaDados['TOTAL_CLIENTE'];
										$clientes = $qrBuscaDados['CLIENTES'];
										$avulso = $qrBuscaDados['AVULSO'];
										$clientes_outras = $qrBuscaDados['CLIENTES_OUTRAS'];

										$masculino = $qrBuscaDados['MASCULINO'];
										$feminino = $qrBuscaDados['FEMININO'];
										$indefinido = $qrBuscaDados['INDEFINIDO'];
										$total_cliente = $masculino + $feminino + $indefinido;

										$totalVenda = $totalVenda + $venda_total;
										$totalFidelizado = $totalFidelizado + ($venda_total - $avulso);
										$totalAvulso = $totalAvulso + $avulso;
										$totalCliCompra = $totalCliCompra + $clientes_outras;
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

						<div class="push50"></div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

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
										url: "relatorios/ajxRelCadastrosRT.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
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