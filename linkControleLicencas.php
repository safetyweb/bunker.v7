<?php

//echo fnDebug('true');

$filtro = '';
$corLojaAtv = '';
$val_pesquisa = '';


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		// - variáveis da barra de pesquisa -------------
		$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo($_POST['INPUT']);
		// ----------------------------------------------

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
	}
}

// esquema do X da barra - (recarregar pesquisa)
if ($val_pesquisa != "") {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}
// ---------------------------------------------

// fnEscreve($filtro);
// fnEscreve($val_pesquisa);
?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg ?></span>
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
				<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
				<div class="push30"></div>

				<div class="row">
					<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

						<div class="col-md-4 col-xs-12">
							<div class="form-group">
								<label for="inputName" class="control-label">Todas as Empresas</label>
								<div class="push5"></div>
								<label class="switch">
									<input type="checkbox" name="LOG_TODAS" id="LOG_TODAS" class="switch" value="S" onchange="filtraEmpresaAtiva(this)">
									<span></span>
								</label>
							</div>
						</div>

						<div class="col-md-4 col-xs-12">
							<div class="push20"></div>

							<div class="input-group activeItem">
								<div class="input-group-btn search-panel">
									<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
										<span id="search_concept">Sem filtro</span>&nbsp;
										<span class="far fa-angle-down"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<li class="divisor"><a href="#">Sem filtro</a></li>
										<!-- <li class="divider"></li> -->
										<li><a href="#NOM_EMPRESA">Razão social</a></li>
										<li><a href="#NOM_FANTASI">Nome fantasia</a></li>
										<li><a href="#NUM_CGCECPF">CNPJ</a></li>
										<li><a href="#NOM_CONSULTOR">Coordenador</a></li>
									</ul>
								</div>
								<input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA" value="<?= $filtro ?>" />
								<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
								<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
									<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
								</div>
								<div class="input-group-btn">
									<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
								</div>
							</div>

						</div>

						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>

				</div>

				<div class="push30"></div>

				<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

				<div class="col-lg-12">

					<div class="no-more-tables">

						<form name="formLista" id="formLista" method="post" action="action.php?mod=<?php echo @$DestinoPg; ?>&id=0">

							<!-- usar classe "buscavel" nos elementos-pai a serem filtrados -->
							<table class="table table-bordered table-striped table-hover tablesorter buscavel">
								<thead>
									<tr>
										<th class="{sorter:false}" width="40"></th>
										<th>Código</th>
										<th>Nome Fantasia</th>
										<th>Responsável</th>
										<th style='display:none;'>Telefones</th>
										<th>Coordenador</th>
										<th>Integradora</th>
										<th>% Parc.</th>
										<th>Lojas</th>
										<th>Ativas</th>
										<th class="{sorter:false}">Cobrança</th>
										<th class="{sorter:false}">SH</th>
										<th class="{sorter:false}">Ativo</th>
										<th class="{sorter:false}">BD</th>
										<th>Status</th>
										<th>Produção</th>
									</tr>
								</thead>
								<tbody id="relatorioEmpresas">

									<?php

									$andAtivo = "AND LOG_ATIVO = 'S'";


									// filtro do banco de dados (precisa existir antes do sql)-------------------------------------------------------------------------------------------------
									if ($filtro != "") {
										if ($filtro == 'NOM_CONSULTOR') {
											$sql = "SELECT COD_USUARIO FROM USUARIOS WHERE COD_EMPRESA = 3 AND NOM_USUARIO LIKE '%$val_pesquisa%'";
											$query = mysqli_query($connAdm->connAdm(), $sql);

											$cod_consultores = "";
											while ($qrCon = mysqli_fetch_assoc($query)) {
												$cod_consultores .= $qrCon['COD_USUARIO'] . ',';
											}
											$cod_consultores = rtrim($cod_consultores, ',');

											$andFiltro = " AND COD_CONSULTOR IN ($cod_consultores)";
										} else {
											$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
										}
									} else {
										$andFiltro = " ";
									}
									// --------------------------------------------------------------------------------------------------------------------------------------------------------

									// fnEscreve($_SESSION["SYS_COD_MASTER"]);
									if ($_SESSION["SYS_COD_MASTER"] == "2") {
										$sql = "SELECT STATUSSISTEMA.DES_STATUS, empresas.*,
																(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = empresas.COD_EMPRESA) as COD_DATABASE,
																(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,	
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_COBRANCA = 'S') AS COBRANCA_ATIVA,	
																(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
																B.COD_DATABASE, 
																B.NOM_DATABASE 
																FROM empresas  
																LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS
																LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
																WHERE empresas.COD_EMPRESA <> 1 
																$andFiltro
																$andAtivo
																ORDER by NOM_FANTASI";
										//fnEscreve("1");
									} else {
										$sql = "SELECT STATUSSISTEMA.DES_STATUS,empresas.*,
																(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = empresas.COD_EMPRESA) as COD_DATABASE, 
																(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,	
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,
																(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_COBRANCA = 'S') AS COBRANCA_ATIVA,	
																(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
																B.COD_DATABASE, 
																B.NOM_DATABASE 
																FROM empresas  
																LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS
																LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
																WHERE empresas.COD_EMPRESA IN (" . $_SESSION["SYS_COD_MULTEMP"] . ")
																$andFiltro
																$andAtivo
																ORDER by NOM_FANTASI";
										//fnEscreve("2");
										//fnEscreve($_SESSION["SYS_COD_MULTEMP"]);
									}

									//fnEscreve($sql);
									//echo $sql;

									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

									$count = 0;
									while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										if ($qrListaEmpresas['LOG_ATIVO'] == 'S') {
											// $mostraAtivo = '<i class="fal fa-check" aria-hidden="true"></i>';	
											$mostraAtivo = '<i class="fal fa-check" aria-hidden="true"></i>';
											$radioAcesso = "<input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>";
										} else {
											$mostraAtivo = '';
											$radioAcesso = "";
										}

										if ($qrListaEmpresas['LOG_INTEGRADORA'] == 'S') {
											$mostraSH = '<i class="fal fa-check" aria-hidden="true"></i>';
										} else {
											$mostraSH = '';
										}


										if ($qrListaEmpresas['COD_DATABASE'] > 0) {
											if ($qrListaEmpresas['NOM_DATABASE'] == "db_host1" || $qrListaEmpresas['NOM_DATABASE'] == "db_host2") {
												// $mostraAtivoBD = '<i class="fal fa-clock-o" aria-hidden="true"></i>';
												$mostraAtivoBD = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
												$mostraEmpresa = "<a href='action.do?mod=" . fnEncode(1020) . "&id=" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'>" . $qrListaEmpresas['NOM_FANTASI'] . "</a>";
											} else {
												// $mostraAtivoBD = '<i class="fal fa-check" aria-hidden="true"></i>';	
												$mostraAtivoBD = '<i class="fal fa-check" aria-hidden="true"></i>';
												$mostraEmpresa = "<a href='action.do?mod=" . fnEncode(1020) . "&id=" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'>" . $qrListaEmpresas['NOM_FANTASI'] . "</a>";
											}
										} else {
											$mostraAtivoBD = '';
											$mostraEmpresa = $qrListaEmpresas['NOM_FANTASI'];
										}

										if ($qrListaEmpresas['COD_DATABASE'] > 0) {
											// $mostraBD = '<i class="fal fa-check-square-o" aria-hidden="true"></i>';
											$mostraBD = '<i class="fal fa-check-square-o" aria-hidden="true"></i>';
										} else {
											$mostraBD = '';
										}

										if (!empty($qrListaEmpresas['COD_SISTEMAS'])) {
											$tem_sistema = "tem";
										} else {
											$tem_sistema = "nao";
										}

										echo "
															<tr>
															  <td class='text-center'>$radioAcesso</th>
															  <td class='text-center'>" . $qrListaEmpresas['COD_EMPRESA'] . "</td>
															  <td>" . $mostraEmpresa . "</td>
															  <td>" . $qrListaEmpresas['NOM_RESPONS'] . "</td>
															  <td style='display:none;'>" . $qrListaEmpresas['NUM_TELEFON'] . " / " . $qrListaEmpresas['NUM_CELULAR'] . "</td>
															  <td>" . $qrListaEmpresas['NOM_CONSULTOR'] . "</td>
															  <td>" . $qrListaEmpresas['NOM_INTEGRADORA'] . "</td>
															  <td>" . fnValor($qrListaEmpresas['PCT_PARCEIRO'], 2) . "</td>
															  <td align='center'>" . $qrListaEmpresas['LOJAS'] . "</td>
															  <td align='center'><span class='" . $corLojaAtv . "'>" . $qrListaEmpresas['LOJAS_ATIVAS'] . "</td>
															  <td align='center'>" . fnValor($qrListaEmpresas['COBRANCA_ATIVA'], 0) . "</td>
															  <td align='center'>" . $mostraSH . "</td>
															  <td align='center'>" . $mostraAtivo . "</td>
															  <td align='center'>" . $mostraAtivoBD . "</td>
															  <td align='center'>" . $qrListaEmpresas['DES_STATUS'] . "</td>
															  <!-- <td align='center'>" . $mostraBD . "</td> -->
															  <td><small>" . fnDateRetorno($qrListaEmpresas['DAT_PRODUCAO']) . "</small></td>
															</tr>
															<input type='hidden' id='ret_IDC_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_EMPRESA']) . "'>
															<input type='hidden' id='ret_ID_" . $count . "' value='" . $qrListaEmpresas['COD_EMPRESA'] . "'>
															<input type='hidden' id='ret_NOM_EMPRESA_" . $count . "' value='" . $qrListaEmpresas['NOM_EMPRESA'] . "'>
															";
									}

									?>

								</tbody>

								<tfoot>
									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
										</th>
									</tr>
								</tfoot>
							</table>

							<div class="push50"></div>

							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="codBusca" id="codBusca" value="">
							<input type="hidden" name="nomBusca" id="nomBusca" value="">

						</form>

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

<?php
if (!is_null($RedirectPg)) {
	$DestinoPg = fnEncode($RedirectPg);
} else {
	$DestinoPg = "";
}
?>

<script type="text/javascript">
	//Barra de pesquisa essentials ------------------------------------------------------
	$(document).ready(function(e) {

		var value = $('#INPUT').val().toLowerCase().trim();
		if (value) {
			$('#CLEARDIV').show();
		} else {
			$('#CLEARDIV').hide();
		}
		$('.search-panel .dropdown-menu').find('a').click(function(e) {
			e.preventDefault();
			var param = $(this).attr("href").replace("#", "");
			var concept = $(this).text();
			$('.search-panel span#search_concept').text(concept);
			$('.input-group #VAL_PESQUISA').val(param);
			$('#INPUT').focus();
			console.log(param);
			console.log(concept);
		});

		$("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
		});

		$("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
		});

		$('#CLEAR').click(function() {
			$('#INPUT').val('');
			$('#INPUT').focus();
			$('#CLEARDIV').hide();
			if ("<?= $filtro ?>" != "") {
				location.reload();
			} else {
				var value = $('#INPUT').val().toLowerCase().trim();
				if (value) {
					$('#CLEARDIV').show();
				} else {
					$('#CLEARDIV').hide();
				}
				$(".buscavel tr").each(function(index) {
					if (!index) return;
					$(this).find("td").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('tr').toggle(!sem_registro);
						return sem_registro;
					});
				});
			}
		});

		// $('#SEARCH').click(function(){
		// 	$('#formulario').submit();
		// });

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
								icon: 'fal fa-check-square-o',
								content: function() {
									var self = this;
									return $.ajax({
										url: "ajxLinkControleLicencas.do?opcao=exportar&nomeRel=" + nome,
										data: $('#formLista2').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '3_' + nome + '.csv';
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


	function buscaRegistro(el) {
		var filtro = $('#search_concept').text().toLowerCase();

		if (filtro == "sem filtro") {
			var value = $(el).val().toLowerCase().trim();
			if (value) {
				$('#CLEARDIV').show();
			} else {
				$('#CLEARDIV').hide();
			}
			$(".buscavel tr").each(function(index) {
				if (!index) return;
				$(this).find("td").each(function() {
					var id = $(this).text().toLowerCase().trim();
					var sem_registro = (id.indexOf(value) == -1);
					$(this).closest('tr').toggle(!sem_registro);
					return sem_registro;
				});
			});
		}
	}

	//-----------------------------------------------------------------------------------

	function filtraEmpresaAtiva(el) {

		$.ajax({
			method: 'POST',
			url: 'ajxLinkEmpresas.do?opcao=filtra',
			data: $("#formLista2").serialize(),
			beforeSend: function() {
				$('#relatorioEmpresas').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$('#relatorioEmpresas').html(data);
				$(".tablesorter").trigger("updateAll");
			},
			error: function() {
				$('#relatorioEmpresas').html("Ops... Empresas não encontradas!");
			}
		});

	}


	function retornaForm(index) {

		$("#codBusca").val($("#ret_ID_" + index).val());
		$("#codBusca").val($("#ret_IDC_" + index).val());
		$("#nomBusca").val($("#ret_NOM_EMPRESA_" + index).val());
		$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=' + $("#ret_IDC_" + index).val());
		$('#formLista').submit();
	}
</script>