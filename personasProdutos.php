<?php
if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
// $hashLocal = "";
// $msgRetorno = "";
// $msgTipo = "";
// $cod_redesoc = "";
// $des_redesoc = "";
// $cod_redes = "";
// $hHabilitado = "";
// $hashForm = "";
// $arrayQuery = [];
// $qrBuscaEmpresa = "";
// $bl2_tip_filtro = "";
// $i = 0;
// $lblAtributo = "";
// $limit = "";
// $cod_persona = fnDecode(@$_GET['idx']);
// $qrAttr = "";
// $atribObrig = "";
// $hide = "";
// $qrParam = "";
// $bloqueiaAlt = "";
// $cod_campanha = "";
// $qrListaCategoria = "";
// $qrListaPersonasProdutos = "";
// $el = "";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_redesoc = fnLimpaCampoZero(@$_REQUEST['COD_REDESOC']);
		$des_redesoc = fnLimpaCampo(@$_REQUEST['DES_REDESOC']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
		$cod_redes = fnLimpaCampoZero(@$_REQUEST['COD_REDES']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			if ($opcao != '') {
			}

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

//fnMostraForm();

?>


<script src="js/jquery-ui.min.js" type="text/javascript"></script>

<style>
	.chosen-container {
		width: 100% !important;
	}
</style>


<div class="push30"></div>

<div class="row">

	<div class="col-md-3">
		<div class="form-group">
			<label for="inputName" class="control-label">Tipo de filtragem dos atributos</label>
			<input type="hidden" class="form-control input-sm" name="BL2_TIP_FILTROATTOUR" id="BL2_TIP_FILTROATTOUR" maxlength="9">
			<select data-placeholder="Selecione um tipo de filtro" name="BL2_TIP_FILTROAT" id="BL2_TIP_FILTROAT" class="chosen-select-deselect" onchange='gravaFilAtributos(this)'>
				<option value="1">Combina os atributos A+B (and)</option>
				<option value="2">Qualquer atributo A ou B (or)</option>
			</select>

			<div class="help-block with-errors"></div>
		</div>
	</div>

</div>

<div class="push10"></div>

<div class="row">


	<?php
	$sql = "select  A.*,B.NOM_EMPRESA as NOM_EMPRESA from EMPRESACOMPLEMENTO A 
								INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
								where A.COD_EMPRESA = $cod_empresa ";


	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);


	/****************************************************/
	$bl2_tip_filtro = "";
	for ($i = 1; $i <= 13; $i++) {
		$lblAtributo = $qrBuscaEmpresa["ATRIBUTO" . $i];
		$limit = 10;

		$sql = "SELECT GROUP_CONCAT(COD_ATRIBUTO) COD_ATRIBUTO,TIP_FILTRO FROM ATRIBUTOS_PRODUTOPERSONA 
								WHERE COD_PERSONA = $cod_persona 
								AND COD_EMPRESA = $cod_empresa 
								AND TIP_ATRIBUTO = $i";

		$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));
		$bl2_tip_filtro = ($bl2_tip_filtro == "" ? $qrAttr["TIP_FILTRO"] : $bl2_tip_filtro);
		//$qrAttr["COD_ATRIBUTO"] = 2496;

		if ($lblAtributo != '') {
			$atribObrig = "";
			$hide = "";
		} else {
			$atribObrig = "";
			$hide = "hidden";
		} ?>
		<div class="col-md-3 <?php echo $hide; ?>">
			<div class="form-group ATRIBUTO" data-param=<?= $i ?>>
				<label for="inputName" class="control-label <?php echo $atribObrig; ?>"><?php echo $lblAtributo; ?> </label>
				<select multiple=true data-page=1 data-limit=<?= $limit ?> data-placeholder="Opções de <?php echo strtolower($lblAtributo); ?>" name="ATRIBUTO<?= $i ?>" id="ATRIBUTO<?= $i ?>" class="select-atributo chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,<?= $i ?>)'>
					<option value=""></option>
					<?php
					$sql = "";
					$sql .= "(SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO" . $i . " WHERE COD_EMPRESA = $cod_empresa AND COD_PARAMETRO IN (" . $qrAttr["COD_ATRIBUTO"] . "))";
					$sql .= " UNION ";
					$sql .= "(SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO" . $i . " WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO LIMIT $limit)";
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
					while ($qrParam = mysqli_fetch_assoc($arrayQuery)) {

					?>

						<option value="<?= $qrParam["COD_PARAMETRO"] ?>"><?= $qrParam['DES_PARAMETRO'] ?></option>

					<?php

					}

					?>
					<option class="opt_load_more" value="+">[ Carregar todos ]</option>
				</select>

				<div class="help-block with-errors"></div>

				<script>
					$('#ATRIBUTO<?= $i ?>').val([<?= $qrAttr["COD_ATRIBUTO"] ?>]).trigger('chosen:updated');
				</script>
			</div>
		</div>
	<?php
	}
	/****************************************************/
	?>
	<script>
		$("#BL2_TIP_FILTROAT").val("<?= $bl2_tip_filtro ?>").trigger("chosen:updated");
	</script>
</div>

<div class="push10"></div>
<hr>
<div class="form-group text-right col-lg-12">

	<button type="button" class="btn btn-success atualiza pull-left" <?php echo $bloqueiaAlt; ?>><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtros</button>

	<button type="button" class="btn btn-default limpaAtributo"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>

</div>

<div class="push50"></div>

<div class="row">

	<div class="col-md-8">
		<label for="inputName" class="control-label required">Produto </label>
		<div class="input-group">
			<span class="input-group-btn">
				<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1247) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
			</span>
			<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico...">
			<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="0">
			<input type="hidden" name="COD_PERPROD" id="COD_PERPROD" value="0">
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label for="inputName" class="control-label">Fornecedor</label>
			<select data-placeholder="Selecione o grupo" name="BL2_COD_FORNECEDOR" id="BL2_COD_FORNECEDOR" class="chosen-select-deselect">
				<option value=""></option>
				<?php
				$sql = "select * from FORNECEDORMRKA where COD_EMPRESA = $cod_empresa order by NOM_FORNECEDOR";
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

				while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
					echo "
													  <option value='" . $qrListaCategoria['COD_FORNECEDOR'] . "'>" . $qrListaCategoria['NOM_FORNECEDOR'] . "</option> 
													";
				}
				?>
			</select>
			<div class="help-block with-errors"></div>
		</div>
	</div>
	<div class="push10"></div>

	<div class="col-md-6">
		<div class="form-group">
			<label for="inputName" class="control-label">Grupo do Produto</label>
			<select data-placeholder="Selecione o grupo" name="BL2_COD_CATEGOR" id="BL2_COD_CATEGOR" class="chosen-select-deselect">
				<option value=""></option>
				<?php
				$sql = "select * from CATEGORIA where COD_EMPRESA = $cod_empresa AND COD_EXCLUSA is null order by DES_CATEGOR";
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

				while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
					echo "
													  <option value='" . $qrListaCategoria['COD_CATEGOR'] . "'>" . $qrListaCategoria['DES_CATEGOR'] . "</option> 
													";
				}
				?>
			</select>
			<div class="help-block with-errors"></div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label for="inputName" class="control-label">Sub Grupo do Produto</label>
			<div id="divId_sub">
				<select data-placeholder="Selecione o sub grupo" name="BL2_COD_SUBCATE" id="BL2_COD_SUBCATE" class="chosen-select-deselect">
					<option value=""></option>
				</select>
			</div>
			<div class="help-block with-errors"></div>
		</div>
	</div>

</div>


<div class="push10"></div>
<hr>
<div class="form-group text-right col-lg-12">

	<button type="button" class="btn btn-success atualiza pull-left" <?php echo $bloqueiaAlt; ?>><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtros</button>

	<button type="button" class="btn btn-default limpaProduto"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
	<button type="button" name="CAD" id="CAD" class="btn btn-primary getBtn addCadProd" <?php echo $bloqueiaAlt; ?>><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
	<button type="button" name="ALT" id="ALT" class="btn btn-primary getBtn addCadProd" <?php echo $bloqueiaAlt; ?>><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
	<button type="button" name="EXC" id="EXC" class="btn btn-primary getBtn" <?php echo $bloqueiaAlt; ?>><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

</div>

<div class="push50"></div>

<div class="col-lg-12">

	<div class="no-more-tables">

		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th class="text-center" width="40"><small>Todos</small><br><input type='checkbox' id="selectAll"></th>
					<th><small>Cód.</small></th>
					<th><small>Cód. Ext.</small></th>
					<th><small>Produto</small></th>
					<th><small>Fornecedor</small></th>
					<th><small>Categoria</small></th>
					<th><small>Sub Categoria</small></th>
					<th><small>Chave</small></th>
				</tr>
			</thead>
			<tbody id="tablePersonasProdutos">

				<?php

				$sql = "SELECT 
										personas_produtos.COD_PERPROD,
										personas_produtos.COD_PRODUTO,
										personas_produtos.COD_FORNECEDOR,
										personas_produtos.COD_CATEGOR,
										personas_produtos.COD_SUBCATE,
										personas_produtos.DES_CHAVE,

										(SELECT DES_PRODUTO
										 FROM produtocliente
										WHERE produtocliente.COD_PRODUTO = personas_produtos.COD_PRODUTO) as DES_PRODUTO,
				
										(SELECT COD_EXTERNO
										 FROM produtocliente
										WHERE produtocliente.COD_PRODUTO = personas_produtos.COD_PRODUTO) as COD_EXTERNO,
										
										(SELECT NOM_FORNECEDOR
										 FROM fornecedormrka
										WHERE fornecedormrka.COD_FORNECEDOR = personas_produtos.COD_FORNECEDOR) as NOM_FORNECEDOR,
										 
										(SELECT DES_CATEGOR
										 FROM categoria
										WHERE categoria.COD_CATEGOR = personas_produtos.COD_CATEGOR) as DES_CATEGOR,  	 
										 
										(SELECT DES_SUBCATE
										 FROM subcategoria
										WHERE subcategoria.COD_SUBCATE = personas_produtos.COD_SUBCATE) as DES_SUBCATE     

									FROM personas_produtos 
									where COD_PERSONA = $cod_persona 
									AND COD_EMPRESA = $cod_empresa
									ORDER BY COD_PERPROD DESC";

				// fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

				$count = 0;
				while ($qrListaPersonasProdutos = mysqli_fetch_assoc($arrayQuery)) {
					$count++;
					echo "
										<tr>
										  <td class='text-center'><input type='checkbox' name='radio_$count' onclick='retornaFormPersonas(" . $count . ")'>&nbsp;</td>
										  <td><small>" . $qrListaPersonasProdutos['COD_PERPROD'] . "</small></td>
										  <td><small>" . $qrListaPersonasProdutos['COD_EXTERNO'] . "</small></td>
										  <td><small>" . $qrListaPersonasProdutos['DES_PRODUTO'] . "</small></td>
										  <td><small>" . $qrListaPersonasProdutos['NOM_FORNECEDOR'] . "</small></td>
										  <td><small>" . $qrListaPersonasProdutos['DES_CATEGOR'] . "</small></td>
										  <td><small>" . $qrListaPersonasProdutos['DES_SUBCATE'] . "</small></td>
										  <td><small>" . $qrListaPersonasProdutos['DES_CHAVE'] . "</small></td>
										</tr>
										<input type='hidden' id='ret_COD_PERPROD_" . $count . "' value='" . $qrListaPersonasProdutos['COD_PERPROD'] . "'>
										<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrListaPersonasProdutos['COD_PRODUTO'] . "'>
										<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrListaPersonasProdutos['DES_PRODUTO'] . "'>
										<input type='hidden' id='ret_COD_FORNECEDOR_" . $count . "' value='" . $qrListaPersonasProdutos['COD_FORNECEDOR'] . "'>
										<input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrListaPersonasProdutos['COD_CATEGOR'] . "'>
										<input type='hidden' id='ret_COD_SUBCATE_" . $count . "' value='" . $qrListaPersonasProdutos['COD_SUBCATE'] . "'>
										<input type='hidden' id='ret_DES_CHAVE_" . $count . "' value='" . $qrListaPersonasProdutos['DES_CHAVE'] . "'>
										";
				}

				?>

			</tbody>
		</table>

	</div>

</div>

<div class="push20"></div>

<input type="hidden" name="EXC_ATRIBUTO" id="EXC_ATRIBUTO" value="0">

<script type="text/javascript">
	var listaProdutos = [];

	$(document).ready(function() {

		$("#BL2_COD_CATEGOR").chosen({
			width: "100%"
		});
		$("#BL2_COD_SUBCATE").chosen({
			width: "100%"
		});
		$("#BL2_COD_FORNECEDOR").chosen({
			width: "100%"
		});

		$('#popModalAux').on('hidden.bs.modal', function() {
			atualizarTable();
		});



		var refresh;
		var txt;
		$(document).ready(function() {
			$('.ATRIBUTO .chosen-search input,.ATRIBUTO .search-field input').keyup(function() {
				txt = this.value;
				$el = $(this).parent().parent().parent().parent();
				var param = $el.attr("data-param");
				data = "param=" + param + "&des=" + txt + "&cod_empresa=<?= $cod_empresa ?>";
				refresh = false;
				$el.find(".no-results").html("Carregando resultados...");
				$.ajax({
					url: "ajxPersonaAtributo.php?v=<?= date("Ymdhis") ?>" + param,
					data: data,
					dataType: "json",
					success: function(data) {
						if (data.length <= 0) {
							$el.find(".no-results").html("Sem resultados para " + txt + "...");
						} else {
							$.map(data, function(item) {
								//console.log(item.id,item.name);
								if ($el.find(".select-atributo option[value=" + item.id + "]").length <= 0) {
									$el.find(".select-atributo").append('<option value="' + item.id + '">' + item.name + '</option>');
									refresh = true;
								}
							});
							if (refresh) {
								$('#ATRIBUTO' + param).find('option.opt_load_more').appendTo($('#ATRIBUTO' + param));
								$el.find(".select-atributo").trigger("chosen:updated");
								$el.find('.chosen-search input').val(txt);
								$el.find('.chosen-search input').keyup();
								$el.find('.search-field input').val(txt);
								$el.find('.search-field input').keyup();
							}
						}
					}
				});
			});
		});
		$('.ATRIBUTO select').change(function() {
			if ($(this).val() == null) {
				return false;
			}
			if ($(this).val() === "+" || $(this).val().includes("+")) {
				console.log("todos");
				refresh = false;
				$el = $(this).parent();
				var param = $el.attr("data-param");
				data = "param=" + param + "&acao=all&cod_empresa=<?= $cod_empresa ?>";
				$("#blocker").show();
				$.ajax({
					url: "ajxPersonaAtributo.php?v=<?= date("Ymdhis") ?>",
					data: data,
					dataType: "json",
					success: function(data) {
						$("#blocker").hide();
						$.map(data, function(item) {
							//console.log(item.id,item.name);
							if ($el.find(".select-atributo option[value=" + item.id + "]").length <= 0) {
								$el.find(".select-atributo").append('<option value="' + item.id + '">' + item.name + '</option>');
								refresh = true;
							}
						});
						if (refresh) {
							$('#ATRIBUTO' + param).find('option.opt_load_more').appendTo($('#ATRIBUTO' + param));
							$el.find(".select-atributo").trigger("chosen:updated");
							$el.find('.chosen-search input').keyup();
							$el.find('.search-field input').keyup();
						}
					}
				});

				$('#ATRIBUTO' + param).find('option.opt_load_more').remove();
				$el.find(".select-atributo").trigger("chosen:updated");
				// console.log(param);
			}
		});
	});


	$(".limpaProduto").click(function() {
		$("#DES_PRODUTO").val("");
		$("#COD_PRODUTO").val("0");
		$("#COD_PERPROD").val("0");
		$("#BL2_COD_FORNECEDOR").val("").trigger("chosen:updated");
		$("#BL2_COD_CATEGOR").val("").trigger("chosen:updated");
		$("#BL2_COD_SUBCATE").val("").trigger("chosen:updated");
		$("#notificaProdutos").hide();
	});

	$(".limpaAtributo").click(function() {
		$(".select-atributo").val("0").trigger("chosen:updated");
		gravaAtributos("#EXC_ATRIBUTO", "exc");
		// $("#notificaProdutos").hide();
	});

	// ajax
	$("#BL2_COD_CATEGOR").change(function() {
		var codBusca = $("#BL2_COD_CATEGOR").val();
		var codBusca3 = $("#COD_EMPRESA").val();
		buscaSubCat(codBusca, 0, codBusca3);
	});


	$(".getBtn").click(function() {

		if (listaProdutos != "" || ($("#BL2_COD_CATEGOR").val() != "" || $("#BL2_COD_SUBCATE").val() != "" || $("#BL2_COD_FORNECEDOR").val() != "")) {

			if ($(this).attr('id') == 'EXC') {

				$.ajax({
					type: "POST",
					url: "ajxPersonasProdutos.php?acao=excProdutos&cod_empresa=<?php echo $cod_empresa; ?>",
					data: {
						listaProdutos: JSON.stringify(listaProdutos)
					},
					success: function(data) {
						console.log(data);
						$.confirm({
							title: '<small>Sucesso</small>',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: 'Produtos excluídos com sucesso!',
							buttons: {
								fechar: function() {
									atualizarTable();
								}
							}
						});
					},
					error: function() {}
				});

			} else {
				console.log('ajxPersonasProdutos.php?acao=proc&opcao=' + $(this).attr('name') + '&cod_empresa=' + $("#COD_EMPRESA").val());
				$.ajax({
					method: "POST",
					url: 'ajxPersonasProdutos.php?acao=proc&opcao=' + $(this).attr('name') + '&cod_empresa=' + $("#COD_EMPRESA").val(),
					data: $('#formulario').serialize(),
					beforeSend: function() {
						//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
					},
					success: function(data) {
						console.log(data);
						//alert('cadastro feito com sucesso');
						$.confirm({
							title: '<small>Produtos da Persona</small>',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: 'Registro atualizado com sucesso!',
							buttons: {
								fechar: function() {
									atualizarTable();
								}
							}
						});

					},
					error: function() {
						//$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});
			}

		} else {

			$.confirm({
				title: '<small>Produtos da Persona</small>',
				content: 'Nenhum produto selecionado.',
				buttons: {
					fechar: function() {}
				}
			});

		}

	});

	$('#selectAll').click(function() {
		$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
		attListaProdutos();
	});

	function attListaProdutos() {
		listaProdutos = [];
		$("table tr").each(function(index) {
			if ($(this).find("input[type='checkbox']:not('#selectAll')").is(':checked')) {
				var codigo = $(this).find("input[type='checkbox']").attr('name').replace('radio_', '');
				listaProdutos.push($("#ret_COD_PERPROD_" + index).val());
			}
		});
		if (listaProdutos == '') {
			$.each(listaProdutos, function(index, value) {
				//alert(index);
				if (index > 0) {
					$('.addCadProd').prop('disabled', true);
				} else {
					$('.addCadProd').prop('disabled', false);
				}
			});
		} else {
			// alert('vazio');
		}
	}

	function atualizarTable() {
		$.ajax({
			method: "GET",
			url: "ajxPersonasProdutos.php",
			data: {
				acao: 'consulta',
				cod_empresa: $("#COD_EMPRESA").val(),
				cod_persona: $("#COD_PERSONA").val()
			},
			beforeSend: function() {
				$('#tablePersonasProdutos').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				console.log(data);
				$("#tablePersonasProdutos").html(data);
			},
			error: function() {
				$('#tablePersonasProdutos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function buscaSubCat(idCat, idSub, idEmp) {
		$.ajax({
			method: "POST",
			url: "ajxBuscaSubGrupoPersonasProdutos.php",
			data: {
				ajx1: idCat,
				ajx2: idSub,
				ajx3: idEmp
			},
			beforeSend: function() {
				$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_sub").html(data);
			},
			error: function() {
				$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}


	function carregarCombo(idCat) {
		$.ajax({
			method: "POST",
			async: false,
			url: "ajxBuscaSubGrupoPersonasProdutos.php",
			data: {
				ajx1: idCat,
				ajx2: 0,
				ajx3: $("#COD_EMPRESA").val()
			},
			beforeSend: function() {
				$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_sub").html(data);
				// console.log(data);
			},
			error: function() {
				$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function gravaAtributos(el, tip_atributo) {
		$.ajax({
			method: 'POST',
			url: 'ajxSalvaAtributosPersona.do?id=<?= fnEncode($cod_empresa) ?>',
			data: {
				COD_PERSONA: "<?= fnEncode($cod_persona) ?>",
				COD_ATRIBUTO: $(el).val(),
				TIP_ATRIBUTO: tip_atributo,
				TIP_FILTRO: $("#BL2_TIP_FILTROAT").val()
			},
			success: function(data) {
				console.log(data);
			}
		});
	}

	function gravaFilAtributos(el) {
		$.ajax({
			method: 'POST',
			url: 'ajxSalvaAtributosPersona.do?id=<?= fnEncode($cod_empresa) ?>',
			data: {
				COD_PERSONA: "<?= fnEncode($cod_persona) ?>",
				TIP_FILTRO: $(el).val(),
				TIP_ATRIBUTO: "fil"
			},
			success: function(data) {
				console.log(data);
			}
		});
	}

	function retornaFormPersonas(index) {
		$("#formulario #COD_PERPROD").val($("#ret_COD_PERPROD_" + index).val());
		$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_" + index).val()).trigger("chosen:updated");
		$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_" + index).val());
		$("#formulario #BL2_COD_FORNECEDOR").val($("#ret_COD_FORNECEDOR_" + index).val()).trigger("chosen:updated");
		$("#formulario #BL2_COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger("chosen:updated");
		buscaSubCat($("#ret_COD_CATEGOR_" + index).val(), $("#ret_COD_SUBCATE_" + index).val(), $("#COD_EMPRESA").val());
		$("#formulario #BL2_COD_SUBCATE").val($("#ret_COD_SUBCATE_" + index).val()).trigger("chosen:updated");
		attListaProdutos();
	}
</script>