<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$produto = "";
$ean1 = "";
$id_patologia = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$cod_campanha = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abasMedicamentos = "";
$qrListaUnidades = "";
$selecionado = "";
$andMedicamento = "";
$andEan = "";
$andPatologia = "";
$sqlCount = "";
$retorno = "";
$inicio = "";
$qrBuscaMedicamento = "";
$content = "";


//echo "<h5>_".$opcao."</h5>";

//definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";
$hashLocal = mt_rand();

// $conn = conntemp($cod_empresa,"");
$adm = $Cdashboard->connAdm(); //conexao com o banco
// fnEscreve($adm);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$produto = @$_REQUEST['PRODUTO'];
		$ean1 = @$_REQUEST['EAN1'];
		$id_patologia = fnLimpaCampo(@$_REQUEST['ID_PATOLOGIA']);
		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		// fnEscreve($opcao);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		// fnEscreve($cod_usucada);
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_campanha = fnDecode(@$_GET['idc']);
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
				$formBack = "1019";
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

				<?php
				$abasMedicamentos = 2001;
				include "abasMedicamentos.php";
				?>

				<div class="push20"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<!--<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Medicamento</label>
										<input type="text" class="form-control input-sm" name="PRODUTO" id="PRODUTO" maxlength="50" value="<?php echo $produto; ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">EAN</label>
										<input type="text" class="form-control input-sm" name="EAN1" id="EAN1" maxlength="50" value="<?php echo $ean1; ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>-->

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Patologia</label>
										<select data-placeholder="Selecione a patologia" name="ID_PATOLOGIA" id="ID_PATOLOGIA" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "SELECT ID_PATOLOGIA, NOM_PATOLOGIA FROM patologia ORDER BY NOM_PATOLOGIA";
											$arrayQuery = mysqli_query($prod_continuo->connUser(), $sql);

											while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery)) {
												if ($id_patologia == $qrListaUnidades['ID_PATOLOGIA']) {
													$selecionado = "selected";
												} else {
													$selecionado = "";
												}
												echo "
												<option value='" . $qrListaUnidades['ID_PATOLOGIA'] . "' " . $selecionado . ">" . $qrListaUnidades['NOM_PATOLOGIA'] . "</option> 
												";
											}

											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="patolSelecio" id="patolSelecio" value="<?php echo $id_patologia; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<table class="table table-bordered table-striped table-hover tableSorter">
								<thead>
									<tr>
										<th>ID</th>
										<th>Patologia</th>
									</tr>
								</thead>
								<tbody id="relatorioConteudo">

									<?php

									/*if($produto == ""){
										$andMedicamento = " ";
									}else{
										$andMedicamento = "WHERE PRODUTO LIKE '$produto'";
									}
									if($ean1 == ""){
										$andEan = " ";
									}else {
										$andEan = "WHERE EAN1 = '$ean1'";
									}*/
									if ($id_patologia == "") {
										$andPatologia = " ";
									} else {
										$andPatologia = "WHERE ID_PATOLOGIA = '$id_patologia'";
									}

									$sqlCount = "SELECT COUNT(*) as CONTADOR FROM patologia
									$andPatologia
									";
									// fnEscreve($sql);

									$retorno = mysqli_query($prod_continuo->connUser(), $sqlCount);
									$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

									$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									//consulta principal da tabela.
									$sql =  "SELECT * FROM patologia
									$andPatologia
									LIMIT $inicio,$itens_por_pagina";
									//fnEscreve($sql);

									$arrayQuery = mysqli_query($prod_continuo->connUser(), $sql);


									$count = 0;
									while ($qrBuscaMedicamento = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										echo "
										<tr>
										<td>" . $qrBuscaMedicamento['ID_PATOLOGIA'] . "</td>
										<td>" . $qrBuscaMedicamento['NOM_PATOLOGIA'] . "</td>
										</tr>
										";
									}

									?>

								</tbody>

								<tfoot>
									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
										</th>
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

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	$(document).ready(function(e) {

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

	});

	function reloadPage(idPage) {
		// console.log("aqui funcionou!");
		$.ajax({
			type: "POST",
			url: "ajxListaPatologias.do?opcao=paginar&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	function exportarCSV(btn) {
		// alert(id);
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
							icon: 'fa fa-check-square',
							content: function() {
								var self = this;
								return $.ajax({
									url: "ajxListaPatologias.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
	}
</script>