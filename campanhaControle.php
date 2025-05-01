<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$itens_por_pagina = 50;

// Página default
$pagina = "1";


$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$des_grupotr = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$val_pesquisa = "";
$esconde = "";
$formBack = "";
$abaEmpresa = "";
$ARRAY_UNIDADE1 = [];
$ARRAY_UNIDADE = [];
$arrayAutorizado = [];
$CarregaMaster = "";
$qrListaCampanha = "";
$campanhaAtivo = "";
$campanhaAtualiza = "";
$mod = "";
$dat_expira = "";
$cor = "";
$lojaLoop = "";
$nomeLoja = "";
$NOM_ARRAY_UNIDADE = [];
$checkRestrito = "";
$filtro = "";



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

			$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '" . $cod_grupotr . "', 
				 '" . $des_grupotr . "', 
				 '" . $cod_empresa . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			mysqli_query($connAdm->connAdm(), trim($sql));

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

// esquema do X da barra - (recarregar pesquisa)
if ($val_pesquisa != '') {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}
// ---------------------------------------------

//fnMostraForm();

?>

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

				<?php $abaEmpresa = 1600;
				include "abasEmpresaConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
					<div class="push30"></div>

					<div class="row">
						<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

							<div class="col-xs-4 col-xs-offset-4">
								<div class="input-group activeItem">
									<div class="input-group-btn search-panel">
										<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
											<span id="search_concept">Sem filtro</span>&nbsp;
											<span class="far fa-angle-down"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li class="divisor"><a href="#">Sem filtro</a></li>
											<!-- <li class="divider"></li> -->
										</ul>
									</div>
									<input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">
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

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter buscavel">
									<thead>
										<tr>
											<th class="{sorter:false} text-center">Bloqueado</th>
											<th>Nome da Campanha</th>
											<th class="text-center">Unidade</th>
											<th>Tipo Campanha</th>
											<th>Usuário Cad.</th>
											<th class="text-center {sorter:false}">Ativa</th>
											<th class="text-center {sorter:false}">Live Data</th>
											<th>Data de Criação</th>
											<th>Última Alteração</th>
											<th>Expira Em</th>
											<th class="{sorter:false}"></th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										// ========================================PAGINACAO========================================

										$sql = "SELECT 1
													FROM CAMPANHA A
													LEFT JOIN WEBTOOLS.TIPOCAMPANHA B ON B.COD_TPCAMPA = A.TIP_CAMPANHA
													LEFT JOIN WEBTOOLS.USUARIOS C ON C.COD_USUARIO = A.COD_USUCADA
													WHERE A.COD_EMPRESA = $cod_empresa 
													order by A.DES_CAMPANHA ";

										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$totalitens_por_pagina = mysqli_num_rows($retorno);


										$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										// ================================================================================

										$ARRAY_UNIDADE1 = array(
											'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
											'cod_empresa' => $cod_empresa,
											'conntadm' => $connAdm->connAdm(),
											'IN' => 'N',
											'nomecampo' => '',
											'conntemp' => '',
											'SQLIN' => ""
										);
										$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

										$arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

										$sql = "SELECT A.*, B.NOM_TPCAMPA, B.COD_TPCAMPA, C.NOM_USUARIO,
													IFNULL((SELECT B.NUM_PESSOAS FROM CAMPANHAREGRA B where B.COD_CAMPANHA = A.COD_CAMPANHA),0) as NUM_PESSOAS
													FROM CAMPANHA A
													LEFT JOIN WEBTOOLS.TIPOCAMPANHA B ON B.COD_TPCAMPA = A.TIP_CAMPANHA
													LEFT JOIN WEBTOOLS.USUARIOS C ON C.COD_USUARIO = A.COD_USUCADA
													WHERE A.COD_EMPRESA = $cod_empresa 
													order by A.DES_CAMPANHA
													LIMIT $inicio, $itens_por_pagina";
										//fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {
											$CarregaMaster = '1';
										} else {
											$CarregaMaster = '0';
										}

										$count = 0;
										while ($qrListaCampanha = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrListaCampanha['LOG_ATIVO'] == "S") {
												$campanhaAtivo = "<i class='fas fa-check' aria-hidden='true'></i>";
											} else {
												$campanhaAtivo = "";
											}

											if ($qrListaCampanha['LOG_ATUALIZA'] == "S") {
												$campanhaAtualiza = "<i class='fas fa-check' aria-hidden='true'></i>";
											} else {
												$campanhaAtualiza = "";
											}

											if ($qrListaCampanha['COD_TPCAMPA'] == 21) {
												$mod = 1169;
											} else {
												$mod = 1022;
											}

											$dat_expira = $qrListaCampanha['DAT_FIM'] . " " . $qrListaCampanha['HOR_FIM'];

											if ($dat_expira < date('Y-m-d H:i:s')) {
												$cor = "text-danger";
											} else {
												$cor = "";
											}

											if ($CarregaMaster == '1') {

												$lojaLoop = $qrListaCampanha['cod_univend'];
												if ($lojaLoop == 9999) {
													$nomeLoja = "Todas";
												} else {
													$NOM_ARRAY_UNIDADE = (array_search($qrListaCampanha['cod_univend'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
													$nomeLoja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
												}
											}

											if ($qrListaCampanha['LOG_RESTRITO'] == 'S') {
												$checkRestrito  = "checked";
											} else {
												$checkRestrito = "";
											}


										?>

											<tr>
												<td class="text-center">
													<label class="switch">
														<input type="checkbox" class="switch" onclick='toggleRestrito(this,"<?= fnEncode($cod_empresa) ?>","<?= fnEncode($qrListaCampanha['COD_CAMPANHA']) ?>")' <?= $checkRestrito ?>>
														<span style="height: 25px;"></span>
													</label>
												</td>
												<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaCampanha['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaCampanha['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaCampanha['DES_CAMPANHA'];; ?></td>
												<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
												<td><small><?php echo $qrListaCampanha['NOM_TPCAMPA']; ?></td>
												<td><small><?php echo $qrListaCampanha['NOM_USUARIO']; ?></td>
												<td class='text-center'><?php echo $campanhaAtivo; ?></td>
												<td class='text-center'><?php echo $campanhaAtualiza; ?></td>
												<td><small><?php echo fnDataFull($qrListaCampanha['DAT_CADASTR']); ?></td>
												<td><small><?php echo fnDataFull($qrListaCampanha['DAT_ALTERAC']); ?></td>
												<td class="<?= $cor ?>"><small><?php echo fnDataFull($dat_expira); ?></td>
												<td class="text-center">
													<small>
														<div class="btn-group dropdown dropleft">
															<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																ações &nbsp;
																<span class="fas fa-caret-down"></span>
															</button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1040) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA']) ?>&pop=true" data-title="Campanha / <?php echo $qrListaCampanha['DES_CAMPANHA']; ?>">Editar </a></li>
																<li><a href="action.do?mod=<?php echo fnEncode($mod); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA']); ?>&idt=<?php echo fnEncode($qrListaCampanha['COD_TPCAMPA']); ?>">Acessar </a></li>
																<!-- <li class="divider"></li> -->
																<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
															</ul>
														</div>
													</small>
												</td>
											</tr>

										<?php
										}

										?>

									</tbody>

									<tfoot>
										<tr>
											<th class="" colspan="100">
												<center>
													<ul id="paginacao" class="pagination-sm"></ul>
												</center>
											</th>
										</tr>
									</tfoot>
								</table>

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

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}


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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "ajxCampanhaControle.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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

	function toggleRestrito(obj, idEmp, idCamp) {

		var log = "";

		if ($(obj).prop('checked')) {
			log = 'S';
		} else {
			log = 'N';
		}

		$.ajax({
			type: "POST",
			url: "ajxCampanhaControle.do?opcao=restrito&id=" + idEmp + "&idc=" + idCamp + "&idl=" + log,
			success: function(data) {
				console.log(data);
			}
		});
	}
</script>