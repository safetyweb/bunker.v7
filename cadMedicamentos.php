<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_medicam = "";
$nom_medicam = "";
$codigo_barra = "";
$duracao = "";
$filtro = "";
$val_pesquisa = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$sqlGrava = "";
$sqlEdita = "";
$sqlApaga = "";
$formBack = "";
$abasMedicamentos = "";
$esconde = "";
$andFiltro = "";
$sqlCount = "";
$retorno = "";
$inicio = "";
$qrBuscaMedicamento = "";


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

		$cod_medicam = fnLimpaCampoZero(@$_REQUEST['ID']);
		$nom_medicam = fnLimpaCampo(@$_REQUEST['NOM_MEDICAMENTO']);
		$codigo_barra = fnLimpaCampo(@$_REQUEST['CODIGO_BARRA']);
		$duracao = fnLimpaCampo(@$_REQUEST['DURACAO']);

		$filtro = fnLimpaCampo(@$_REQUEST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_REQUEST['INPUT']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		// fnEscreve($opcao);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		// fnEscreve($cod_usucada);
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
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

if (@$opcao != '') {

	switch ($opcao) {
		case 'CAD':

			$sqlGrava = "INSERT INTO produtos_marka_to(
			COD_EMPRESA,
			NOM_MEDICAMENTO,
			CODIGO_BARRA,
			DURACAO,
			COD_CADASTR
			) VALUES (
			'$cod_empresa',
			'$nom_medicam',
			'$codigo_barra',
			'$duracao',
			'$cod_usucada'
		)";

			mysqli_query($adm, $sqlGrava);
			$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
			// fnTesteSql($adm, $sqlGrava);
			// fnEscreve($sqlGrava);

			break;
		case 'ALT':

			$sqlEdita = "UPDATE produtos_marka_to SET
			NOM_MEDICAMENTO = '$nom_medicam',
			CODIGO_BARRA = '$codigo_barra',
			DURACAO = '$duracao',
			COD_ALTERAC = '$cod_usucada',
			DAT_ALTERAC = NOW()
			WHERE ID = '$cod_medicam'
			AND COD_EMPRESA = '$cod_empresa'
			";

			mysqli_query($adm, $sqlEdita);
			$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
			// fnTesteSql($adm, $sqlEdita);

			break;
		case 'EXC':

			$sqlApaga = "UPDATE produtos_marka_to SET
			COD_EXCLUSA = '$cod_usucada',
			DAT_EXCLUSA = NOW()
			WHERE ID = '$cod_medicam'
			AND COD_EMPRESA = '$cod_empresa'
			";

			mysqli_query($adm, $sqlApaga);
			$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
			// fnTesteSql($adm, $sqlApaga);

			break;
	}
	$msgTipo = 'alert-success';
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
				$abasMedicamentos = 1899;
				include "abasMedicamentos.php";
				?>

				<div class="push20"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ID" id="ID" value="">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome Medicamento</label>
										<input type="text" class="form-control input-sm" name="NOM_MEDICAMENTO" id="NOM_MEDICAMENTO" maxlength="50">
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código de Barra</label>
										<input type="text" class="form-control input-sm" name="CODIGO_BARRA" id="CODIGO_BARRA" maxlength="50">
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Duração</label>
										<input type="text" class="form-control input-sm" name="DURACAO" id="DURACAO" maxlength="50">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<div class="col-md-2">
								<button class="col-md-12 btn btn-default tmplt" name="tmplt"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp;&nbsp; Template</button>
								<script>
									$(".tmplt").click(function(e) {
										e.preventDefault();
										location.href = "https://adm.bunker.mk/media/clientes/7/Template_Importacao_Medicamentos.xlsx";
									});
								</script>
							</div>
							<div class="col-md-2">
								<button type="button" class="btn btn-info btn-block upload"><i class="fas fa-upload"></i>&nbsp; Importar Carga</button>
							</div>
							<!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="row">
						<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

							<div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4">
								<div class="input-group activeItem">
									<div class="input-group-btn search-panel">
										<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
											<span id="search_concept">Sem filtro</span>&nbsp;
											<span class="far fa-angle-down"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li class="divisor"><a href="#">Sem filtro</a></li>
											<!-- <li class="divider"></li> -->
											<li><a class="item-filtro" href="#ID">Código do produto</a></li>
											<li><a class="item-filtro" href="#NOM_MEDICAMENTO">Nome</a></li>
											<li><a class="item-filtro" href="#CODIGO_BARRA">Código de Barra</a></li>
											<li><a class="item-filtro" href="#DURACAO">Duração</a></li>
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

						</form>

					</div>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<table class="table table-bordered table-striped table-hover tableSorter">
								<thead>
									<tr>
										<th class="{ sorter: false }" width="40"></th>
										<th>Cód. Produto</th>
										<th>Nome do Medicamento</th>
										<th>Código de Barra</th>
										<th>Duração</th>
									</tr>
								</thead>
								<tbody id="relatorioConteudo">

									<?php

									if ($filtro != '') {
										if ($filtro == "CODIGO_BARRA") {
											$andFiltro = " AND A.CODIGO_BARRA = '$val_pesquisa' ";
										} else if ($filtro == "ID") {
											$andFiltro = " AND A.ID = '$val_pesquisa' ";
										} else if ($filtro == "NOM_MEDICAMENTO") {
											$andFiltro = " AND A.NOM_MEDICAMENTO = '$val_pesquisa' ";
										} else if ($filtro == "DURACAO") {
											$andFiltro = " AND A.DURACAO = '$val_pesquisa' ";
										} else {
											$andFiltro = " AND A.$filtro LIKE '%$val_pesquisa%' ";
										}
									} else {
										$andFiltro = " ";
									}
									//contador
									$sqlCount = "SELECT COUNT(*) as CONTADOR from PRODUTOS_MARKA_TO A  
										WHERE A.COD_EXCLUSA = 0 
										$andFiltro ORDER BY A.ID";
									// fnEscreve($sql);

									$retorno = mysqli_query($adm, $sqlCount);
									$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

									$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									//consulta principal da tabela.
									$sql =  "SELECT * FROM PRODUTOS_MARKA_TO A 
										WHERE A.COD_EXCLUSA = 0
										$andFiltro
										ORDER BY A.ID LIMIT $inicio,$itens_por_pagina";
									//fnEscreve($sql);


									$arrayQuery = mysqli_query($adm, $sql);

									$count = 0;
									while ($qrBuscaMedicamento = mysqli_fetch_assoc($arrayQuery)) {
										$count++;
										echo "
											<tr>
											<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
											<td>" . $qrBuscaMedicamento['ID'] . "</td>
											<td>" . $qrBuscaMedicamento['NOM_MEDICAMENTO'] . "</td>
											<td>" . $qrBuscaMedicamento['CODIGO_BARRA'] . "</td>
											<td>" . $qrBuscaMedicamento['DURACAO'] . "</td>
											</tr>
											<input type='hidden' id='ret_ID_" . $count . "' value='" . $qrBuscaMedicamento['ID'] . "'>
											<input type='hidden' id='ret_NOM_MEDICAMENTO_" . $count . "' value='" . $qrBuscaMedicamento['NOM_MEDICAMENTO'] . "'>
											<input type='hidden' id='ret_CODIGO_BARRA_" . $count . "' value='" . $qrBuscaMedicamento['CODIGO_BARRA'] . "'>
											<input type='hidden' id='ret_DURACAO_" . $count . "' value='" . $qrBuscaMedicamento['DURACAO'] . "'>
											";
									}

									?>

								</tbody>

								<tfoot>
									<!-- <tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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
				value = $('#INPUT').val().toLowerCase().trim();
				if (value) {
					$('#CLEARDIV').show();
				} else {
					$('#CLEARDIV').hide();
				}
				$(".tableSorter tr").each(function(index) {
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

	function reloadPage(idPage) {
		// console.log("aqui funcionou!");
		$.ajax({
			type: "POST",
			url: "ajxMedicamentos.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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

	function buscaRegistro(el) {
		var filtro = $('#search_concept').text().toLowerCase();

		if (filtro == "sem filtro") {
			var value = $(el).val().toLowerCase().trim();
			if (value) {
				$('#CLEARDIV').show();
			} else {
				$('#CLEARDIV').hide();
			}
			$(".tableSorter tr").each(function(index) {
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

	function retornaForm(index) {
		$("#formulario #ID").val($("#ret_ID_" + index).val());
		$("#formulario #NOM_MEDICAMENTO").val($("#ret_NOM_MEDICAMENTO_" + index).val());
		$("#formulario #CODIGO_BARRA").val($("#ret_CODIGO_BARRA_" + index).val());
		$("#formulario #DURACAO").val($("#ret_DURACAO_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$('.upload').on('click', function(e) {
		var idField = 'arqUpload_' + $(this).attr('idinput');
		var typeFile = $(this).attr('extensao');

		$.dialog({
			title: 'Arquivo',
			content: '' +
				'<form method = "POST" enctype = "multipart/form-data">' +
				'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
				'<div class="progress" style="display: none">' +
				'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
				'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
				'</div>' +
				'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
				'</form>'
		});
	});

	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		// console.log($('#' + idField)[0].files[0]);

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes/');
		formData.append('id', <?php echo $cod_empresa ?>);
		formData.append('typeFile', typeFile);

		//capturando extensão do arquivo------------------
		var value = $('#' + idField).val(),
			file = value.toLowerCase(),
			ext = file.substring(file.lastIndexOf('.') + 1);

		// console.log(value);
		//------------------------------------------------

		if (ext == 'xlsx') {

			$('.progress').show();
			$.ajax({
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					$('#btnUploadFile').addClass('disabled');
					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							percentComplete = parseInt(percentComplete * 100);
							if (percentComplete !== 100) {
								$('.progress-bar').css('width', percentComplete + "%");
								$('.progress-bar > span').html(percentComplete + "%");
							}
						}
					}, false);
					return xhr;
				},
				url: '../uploads/uploadMedicamento.php?acao=gravar&id=<?php echo $cod_empresa; ?>',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(data) {
					console.log(data);
					$('.jconfirm-open').fadeOut(300, function() {
						$(this).remove();
					});

					let msg = "Importação concluída";

					if (data > 0) {
						msg = "Importação concluída. " + data + " produtos já estavam cadastrados e nenhuma ação foi tomada.";
					}
					$.alert({
						title: "Mensagem",
						content: msg,
						type: 'green',
						buttons: {
							"Ok": {
								btnClass: 'btn-success',
								action: function() {
									window.location.reload();
								}
							}
						},
						backgroundDismiss: function() {
							return 'Ok';
						}
					});

				}

			});
		} else {
			$.alert({
				title: "Erro ao efetuar o upload",
				content: 'Somente arquivos .xlsx são suportados.',
				type: 'red'
			});
		}
	}
</script>