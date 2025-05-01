<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$conadmmysql = "";
$cod_usucada = "";
$val_pesquisa = "";
$esconde = "";
$hashLocal = "";
$arrayQuery = [];
$countLinha = "";
$qrListaEmpresas = "";
$arrayQuery2 = [];
$registros = "";
$filtro = "";

$conadmmysql = $connAdm->connAdm();

$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

?>
<div class="row">

	<div class="col-md12 margin-bottom-30">

		<div class="push20"></div>

		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>
			</div>

			<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">
				<!--<fieldset>
					<legend>Dados Gerais</legend>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleFormControlTextarea1">Mensagem de Cobrança</label>
								<textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
							</div>
						</div>
					</div>
				</fieldset>
				<div class="push10"></div>
				<hr>	
				<div class="form-group text-right col-lg-12">
					<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
					<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
					<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
					<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
				</div>
				<div class="push10"></div>-->

				<div class="push10"></div>

				<div class="push30"></div>
				<div class="col-md-4 col-xs-12">
					<div class="form-group">
						<label for="inputName" class="control-label">Status Msg Cobrança</label>
						<div class="push5"></div>
						<label class="switch">
							<input type="checkbox" name="LOG_TODAS" id="LOG_TODAS" class="switch" value="S" onchange="filtraEmpresaAtiva(this)">
							<span></span>
						</label>
					</div>
					<div class="push10"></div>
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
				<!-- <input type="hidden" name="COD_SISTEMAS" id="COD_SISTEMAS" value="" /> -->
				<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

			</form>


			<div class="portlet-body">

				<div class="login-form">

					<div class="push20"></div>

					<div class="row">

						<div class="push20"></div>

						<table class="table table-bordered table-hover tablesorter buscavel">

							<thead>
								<tr>
									<th class="{ sorter: false }"></th>
									<th><small>Código</small></th>
									<th><small>Nome Fantasia</small></th>
									<th class="text-center"><small>Responsável</small></th>
									<th class="text-center"><small>Telefones</small></th>
									<th class="text-center"><small>Msg Cobrança</small></th>

								</tr>
							</thead>

							<tbody id="relatorioConteudo">

								<?php

								$sql = "SELECT * FROM empresas WHERE LOG_ATIVO='S' AND COD_SISTEMAS NOT IN (2,12,19,16,21,13,15)";

								$arrayQuery = mysqli_query($conadmmysql, $sql);

								$countLinha = 1;
								while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

									$sql = "SELECT COUNT(*) as total_registros FROM unidadevenda WHERE COD_EMPRESA = {$qrListaEmpresas['COD_EMPRESA']} AND LOG_MSGCOBR = 'S'";

									$arrayQuery2 = mysqli_query($conadmmysql, $sql);

									$registros = mysqli_fetch_assoc($arrayQuery2);

								?>
									<tr id='EMPRESA_<?php echo $qrListaEmpresas['COD_EMPRESA']; ?> ' style="<?= ($registros['total_registros'] > 0) ? 'background-color: #E1FEF2;' : '' ?>">
										<td class='text-center'><a href='javascript:void(0);' onclick='abreDetail(<?php echo $qrListaEmpresas['COD_EMPRESA']; ?>)'><i class='fal fa-angle-right' aria-hidden='true'></i></a></td>
										<td><small><?php echo $qrListaEmpresas['COD_EMPRESA']; ?></small></td>
										<td><small><?php echo $qrListaEmpresas['NOM_FANTASI']; ?></small></td>
										<td class="text-center"><small><?php echo $qrListaEmpresas['NOM_RESPONS']; ?></small></td>
										<td class="text-center"><small><?php echo $qrListaEmpresas['NUM_TELEFON'] . " /" . $qrListaEmpresas['NUM_CELULAR']; ?></small></td>
										<td class="text-center">
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_MSGCOBR_<?= $qrListaEmpresas['COD_EMPRESA'] ?>" id="LOG_MSGCOBR_<?= $qrListaEmpresas['COD_EMPRESA'] ?>" class="switch" value="S" <?= $qrListaEmpresas['LOG_MSGCOBR'] == 'S' ? 'checked' : '' ?>>
												<span></span>
											</label>
										</td>
										<td class="hidden total-registros"><?php echo $registros['total_registros']; ?></td>
									</tr>

									<thead class='no-weight' style='display:none; background-color: #fff;' id='abreDetail_<?php echo $qrListaEmpresas['COD_EMPRESA']; ?>'>
									</thead>

								<?php

									$countLinha++;
								}
								?>

							</tbody>

						</table>
					</div>

					<div class="push50"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
	</div>
</div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe id="modalIframe" frameborder="0" style="width: 100%; height: 80%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="push20"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


<script>
	// Filtragem checkbox status cobrança
	$(document).ready(function() {
		$('#LOG_TODAS').change(function() {
			var isChecked = $(this).is(':checked');
			if (isChecked) {
				$('.buscavel tr').each(function() {
					var totalRegistros = parseInt($(this).find('.total-registros').text());

					if (totalRegistros === 0 || isNaN(totalRegistros)) {
						$(this).hide();
					} else {
						$(this).show();
					}
				});
			} else {
				$('.buscavel tr').show();
			}
		});
	});
	//-----------------------------------------


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

	$('input[name^="LOG_MSGCOBR_"]').on('change', function() {
		var valorCheckbox = $(this).prop('checked') ? 'S' : 'N';
		var codEmpresa = $(this).closest('tr').attr('id').replace('EMPRESA_', '');

		$.ajax({
			type: "POST",
			url: "ajxControleCobranca.do?opcao=empresa&idU=<?= fnEncode($cod_usucada); ?>",
			data: {
				codEmpresa: codEmpresa,
				valorCheckbox: valorCheckbox
			},
			success: function(data) {
				console.log(data);
				if (valorCheckbox == 'S') {
					url = 'action.do?mod=<?php echo fnEncode(1968) ?>&id=' + codEmpresa.trim() + '&pop=true';
					modalTitle = 'Cadastrar Mensagem de Cobrança'
					abrirModal(url, modalTitle);
				} else {
					location.reload();
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error("AJAX Error:", textStatus, errorThrown);
			}
		});
	});


	$(document).on('change', 'input[name^="LOG_MSGCOBRUNI_"]', function() {
		var valorCheckbox = $(this).prop('checked') ? 'S' : 'N';
		var codUnivend = $(this).closest('tr').attr('id').replace('UNIVEND_', '');
		var codEmpresa = $(this).closest('thead').attr('id').replace('abreDetail_', '');

		$.ajax({
			type: "POST",
			url: "ajxControleCobranca.do?opcao=univend&idU=<?= fnEncode($cod_usucada); ?>",
			data: {
				codUnivend: codUnivend,
				valorCheckbox: valorCheckbox
			},
			success: function(data) {
				console.log(data);

				if (valorCheckbox == 'S') {
					url = 'action.do?mod=<?php echo fnEncode(1968) ?>&id=' + codEmpresa.trim() + '&idU=' + codUnivend.trim() + '&pop=true';
					console.log(url);
					modalTitle = 'Cadastrar Mensagem de Cobrança'
					abrirModal(url, modalTitle);
				} else {
					location.reload();
				}
			},
			error: function(data) {
				console.error(data);
			}
		});
	});

	function abrirModal(url, data_title) {
		$('#popModal').appendTo('body').modal('show');
		$('#popModal iframe').attr('src', url);
		$('#popModal .modal-title').text(data_title);
	}

	function abreDetail(idEmp) {
		RefreshCampanha(idEmp);
	}

	function RefreshCampanha(idEmp) {
		var idItem = $('#abreDetail_' + idEmp);
		console.log(idItem);

		if (!idItem.is(':visible')) {
			$.ajax({
				type: "POST",
				url: "ajxControleCobranca.do?ide=" + idEmp,
				data: $("#formulario").serialize(),
				beforeSend: function() {
					$("#abreDetail_" + idEmp).html('<div class="loading" style="width: 100%;"></div>');

				},
				success: function(data) {
					$("#abreDetail_" + idEmp).html(data);
					//console.log(data);
				},
				error: function(data) {
					$("#abreDetail_" + idEmp).html(data);
				}
			});

			idItem.show();

			$('#EMPRESA_' + idEmp).find($(".fa-angle-right")).removeClass('fa-angle-right').addClass('fa-angle-down');
		} else {
			idItem.hide();
			$('#EMPRESA_' + idEmp).find($(".fa-angle-down")).removeClass('fa-angle-down').addClass('fa-angle-right');
		}
	}
</script>