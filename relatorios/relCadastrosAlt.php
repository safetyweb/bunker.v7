<?php

//echo fnDebug('true');

$itens_por_pagina = 50;
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);

		if (isset($_POST['COD_INDICAD'])) {
			$Arr_COD_INDICAD = $_POST['COD_INDICAD'];
			// print_r($Arr_COD_INDICAD);			 

			for ($i = 0; $i < count($Arr_COD_INDICAD); $i++) {
				if ($Arr_COD_INDICAD[$i] != 0) {
					$cod_indicad = $cod_indicad . $Arr_COD_INDICAD[$i] . ",";
				}
			}

			$cod_indicad = ltrim(rtrim($cod_indicad, ','), ',');
		} else {
			$cod_indicad = "0";
		}


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
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

<div class="col-md12 margin-bottom-30">
	<!-- Portlet -->
	<div class="portlet portlet-bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="glyphicon glyphicon-calendar"></i>
				<span class="text-primary"> <?php echo $NomePg; ?></span>
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

							<div class="col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label ">Indicador</label>
									<select class="chosen-select-deselect" data-placeholder="Selecione o indicador" name="COD_INDICAD[]" id="COD_INDICAD" multiple="multiple" style="width:100%;" tabindex="1">
										<option value=""></option>
										<?php

										$sql = "SELECT DISTINCT A.COD_INDICAD,
																(SELECT DISTINCT NOM_CLIENTE FROM CLIENTES WHERE CLIENTES.COD_CLIENTE=A.COD_INDICAD) AS NOM_INDICADOR 
														FROM CLIENTES A 
														WHERE A.COD_EMPRESA = $cod_empresa
														ORDER BY NOM_INDICADOR";
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

										while ($qrIndica = mysqli_fetch_assoc($arrayQuery)) {
										?>
											<option value="<?php echo $qrIndica['COD_INDICAD']; ?>"><?php echo $qrIndica['NOM_INDICADOR']; ?></option>
										<?php
										}
										?>
									</select>
									<script type="text/javascript">
										$('#COD_INDICAD').val('<?= $cod_indicad ?>').trigger("chosen:updated");
									</script>
									<div class="help-block with-errors"></div>
								</div>
								<?php

								if ($disableCombo == 'disabled') {
								?>
									<input type="hidden" name="COD_INDICAD" id="COD_INDICAD" value="<?= $cod_indicad ?>">
								<?php
								}

								?>
							</div>

							<div class="col-md-2">
								<div class="push20"></div>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
							</div>


						</div>

					</fieldset>

					<div class="push20"></div>

					<?php

					$sql = "SELECT COD_CLIENTE
					FROM CLIENTES_COM_ALTERACAO";
					//fnTestesql(connTemp($cod_empresa,''),$sql);		
					//fnEscreve($sql);

					$qtd = mysqli_query(connTemp($cod_empresa, ''), $sql);
					$qtd_apoia_tot = mysqli_num_rows($qtd);

					?>

					<div class="row text-center">

						<div class="form-group text-center col-md-6 col-lg-6">

							<div class="push20"></div>

							<p><span id="QTD_APOIA_TOT"><?= fnValor($qtd_apoia_tot, 0) ?></span></p>
							<p><b>Total de Apoiadores Alterados</b></p>

							<div class="push20"></div>

						</div>

						<div class="form-group text-center col-md-6 col-lg-6">

							<div class="push20"></div>

							<p><span id="QTD_APOIA_PER">Carregando...</span></p>
							<p><b>Apoiadores Alterados no Período</b></p>

							<div class="push20"></div>

						</div>

					</div>

					<div class="push20"></div>

					<div>
						<div class="row">
							<div class="col-md-12">

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th>Cliente</th>
											<th>Dt. Alterac.</th>
											<th>Idade</th>
											<th>Email</th>
											<th>Celular</th>
											<th>Endereço</th>
											<th>Indicador</th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">

										<?php

										if ($cod_indicad != 0) {
											$andIndica = "AND COD_INDICAD IN($cod_indicad)";
										} else {
											$andIndica = "";
										}

										$sql = "SELECT COD_CLIENTE
										FROM CLIENTES_COM_ALTERACAO
										WHERE DAT_ALTERAC BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
										$andIndica
										";
										//fnTestesql(connTemp($cod_empresa,''),$sql);		
										//fnEscreve($sql);

										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$totalitens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										// Filtro por Grupo de Lojas
										//include "filtroGrupoLojas.php";

										$sql = "SELECT COD_CLIENTE,
										NOM_CLIENTE,
										DAT_ALTERAC,
										IDADE,
										DES_EMAILUS,
										NUM_CELULAR,
										DES_ENDEREC,
										NUM_ENDEREC,
										DES_COMPLEM,
										DES_BAIRROC,
										NUM_CEPOZOF,
										NOM_CIDADEC,
										COD_ESTADOF,
										NOM_INDICADOR 
										FROM CLIENTES_COM_ALTERACAO
										WHERE DAT_ALTERAC BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
										$andIndica
										LIMIT $inicio,$itens_por_pagina
										";

										// fnEscreve($sql);

										//fnTestesql(connTemp($cod_empresa,''),$sql);											
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

										$count = 0;

										while ($qrApoia = mysqli_fetch_assoc($arrayQuery)) {

											$count++;

											$endereco = "";

											if ($qrApoia['DES_ENDEREC'] != "") {
												$endereco .= $qrApoia['DES_ENDEREC'] . ', ';
											}

											if ($qrApoia['NUM_ENDEREC'] != "") {
												$endereco .= $qrApoia['NUM_ENDEREC'] . ', ';
											}

											if ($qrApoia['DES_COMPLEM'] != "") {
												$endereco .= "(" . $qrApoia['DES_COMPLEM'] . '), ';
											}

											if ($qrApoia['DES_BAIRROC'] != "") {
												$endereco = rtrim(rtrim($endereco, ' '), ',');
												$endereco .= " - " . $qrApoia['DES_BAIRROC'] . ', ';
											}

											if ($qrApoia['NUM_CEPOZOF'] != "") {
												$endereco = rtrim(rtrim($endereco, ' '), ',');
												$endereco .= "<br>" . $qrApoia['NUM_CEPOZOF'] . ' - ';
											}

											if ($qrApoia['NOM_CIDADEC'] != "") {
												$endereco .= $qrApoia['NOM_CIDADEC'] . '/';
											}

											if ($qrApoia['COD_ESTADOF'] != "") {
												$endereco .= $qrApoia['COD_ESTADOF'];
											}




										?>

											<tr>
												<td><a href="action.do?mod=<?= fnEncode(1423) ?>&id=<?= fnEncode($cod_empresa) ?>&idC=<?= fnEncode($qrApoia[COD_CLIENTE]) ?>" class="f14" target="_blank"><?= $qrApoia['NOM_CLIENTE'] ?></a></td>
												<td><small><?= fnDataShort($qrApoia['DAT_ALTERAC']) ?></small></td>
												<td><?= $qrApoia['IDADE'] ?></td>
												<td><?= $qrApoia['DES_EMAILUS'] ?></td>
												<td><?= $qrApoia['NUM_CELULAR'] ?></td>
												<td class="text-center"><small><?= $endereco ?></small></td>
												<td><?= $qrApoia['NOM_INDICADOR'] ?></td>
											</tr>

										<?php

										}

										?>
									</tbody>

									<tfoot>
										<!-- <tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
												</th>
											</tr> -->
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

					<input type="hidden" name="opcao" id="opcao" value="">
					<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					<div class="push5"></div>

				</form>

				<div class="push50"></div>

				<div class="push"></div>

			</div>

		</div>
	</div>
	<!-- fim Portlet -->
</div>



<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	//datas
	$(function() {

		var sistemasUni = "<?= $cod_indicad ?>";
		var sistemasUniArr = sistemasUni.split(',');
		//opções multiplas
		for (var i = 0; i < sistemasUniArr.length; i++) {
			$("#formulario #COD_INDICAD option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
		}
		$("#formulario #COD_INDICAD").trigger("chosen:updated");

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		$("#QTD_APOIA_PER").text("<?= fnValor($totalitens_por_pagina, 0) ?>");

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
										url: "relatorios/ajxRelCupons.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
			url: "relatorios/ajxRelCadastrosAlt.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				console.log(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}
</script>