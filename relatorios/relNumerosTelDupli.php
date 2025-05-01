
<?php

//echo fnDebug('true');

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = "1";

$dat_ini = "";
$dat_fim = "";

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

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
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


//busca revendas do usuário
include "unidadesAutorizadas.php";


?>

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
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>
							</div>

						</fieldset>

						<div class="push20"></div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">


						<div class="push5"></div>

					</form>

				</div>
			</div>
		</div>

		<div class="push30"></div>

		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="login-form">
					<div class="row">
						<div class="col-md-12">

							<table class="table table-bordered table-hover tablesorter buscavel">

								<thead>
									<tr>
										<th>Cód. Vendedor</th>
										<th>Nome</th>
										<th>Unidade</th>
										<th>Núm. Celular</th>
										<th>Qtd. Repetições</th>
									</tr>
								</thead>

								<tbody id="relatorioConteudo">

									<?php
                                    // Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									$sql = "SELECT 1
									FROM 
									clientes AS CL
									INNER JOIN unidadevenda AS UNV ON UNV.COD_UNIVEND = CL.COD_UNIVEND
									LEFT JOIN USUARIOS AS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
									WHERE 
									CL.COD_EMPRESA = $cod_empresa
									AND CL.COD_UNIVEND IN($lojasSelecionadas)
									GROUP BY
									CL.NUM_CELULAR,
									CL.COD_VENDEDOR
									HAVING 
									COUNT(*) > 2
									ORDER BY 
									QTD_REPETICOES DESC";

                                    // fnEscreve($sql);
									$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
									$totalitens_por_pagina = mysqli_num_rows($retorno);

                                    // fnescreve($sql);
									$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

                                    // fnEscreve($numPaginas);
                                    //variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                    // ================================================================================
									$sql = "
									SELECT 
									CL.NUM_CELULAR,
									CL.COD_VENDEDOR,
									USU.NOM_USUARIO,
									UNV.NOM_FANTASI,
									COUNT(*) AS QTD_REPETICOES
									FROM 
									clientes AS CL
									INNER JOIN unidadevenda AS UNV ON UNV.COD_UNIVEND = CL.COD_UNIVEND
									LEFT JOIN USUARIOS AS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
									WHERE 
									CL.COD_EMPRESA = $cod_empresa
									AND CL.COD_UNIVEND IN($lojasSelecionadas)
									GROUP BY
									CL.NUM_CELULAR,
									CL.COD_VENDEDOR
									HAVING 
									COUNT(*) > 2
									ORDER BY 
									QTD_REPETICOES DESC
									LIMIT $inicio, $itens_por_pagina
									";

									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$count = 0;
									while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

										?>  
										<tr>
											<td><?= $qrListaVendas['COD_VENDEDOR']; ?></td>
											<td><?= $qrListaVendas['NOM_USUARIO']; ?></td>
											<td><?= $qrListaVendas['NOM_FANTASI']; ?></td>
											<td><?= fnmasktelefone($qrListaVendas['NUM_CELULAR']); ?></td>
											<td><?= $qrListaVendas['QTD_REPETICOES']; ?></td>
										</tr>
										<?php

										$count++;    
									}

									?>

								</tbody>

								<tfoot>

									<tr>
										<th colspan="100">
											<div class="btn-group dropdown left">
												<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-excel" aria-hidden="true"></i>
													&nbsp; Exportar&nbsp;
													<span class="fas fa-caret-down"></span>
												</button>
												<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">                           
													<li><a class="btn btn-sm exportarCSV" data-attr="agrupado" style="text-align: left">&nbsp; Exportar Agrupado Vendedor</a></li>
													<li><a class="btn btn-sm exportDupli" data-attr="detalhado" style="text-align: left">&nbsp; Exportar Detalhado </a></li>
												</ul>
											</div>
										</th>
									</tr>									<tr>
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
	$(document).ready(function() {

		var numPaginas = <?php echo $numPaginas; ?>;
		if(numPaginas != 0){
			carregarPaginacao(numPaginas);
		}

		$('#DAT_INI_GRP, #DAT_FIM_GRP').datetimepicker({
			format: 'DD/MM/YYYY'
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
			var tipo = $(this).attr("data-attr");
			exportar(tipo);		
		});

		$(".exportDupli").click(function() {
			var tipo = $(this).attr("data-attr");
			exportar(tipo);		
		});

		function exportar(tipo){

			var tipoExport = tipo;
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
						action: function () {
							var nome = this.$content.find('.nome').val();
							if(!nome){
								$.alert('Por favor, insira um nome');
								return false;
							}

							$.confirm({
								title: 'Mensagem',
								type: 'green',
								icon: 'fal fa-check-square-o',
								content: function(){
									var self = this;
									return $.ajax({
										url: "relatorios/ajxRelNumerosTelDupli.do?opcao="+tipoExport+"&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>", 
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function (response) {
										console.log(response);
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
											//console.log(response);
									}).fail(function(){
										self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
									});
								},							
								buttons: {
									fechar: function () {
											//close
									}									
								}
							});								
						}
					},
					cancelar: function () {
							//close
					},
				}
			});	
		}

	});

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelNumerosTelDupli.php.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend:function(){
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success:function(data){
				$("#relatorioConteudo").html(data);                                     
				console.log(data);                                 
			},
			error:function(){
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});     
	}
</script>