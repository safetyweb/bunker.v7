<?php

//echo fnDebug('true');

$hashLocal = mt_rand();
// fnEscreve('chega');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_usuario = fnLimpaCampoZero($_REQUEST['COD_USUARIO']);
		$cod_municipio_e = fnLimpaCampoZero($_REQUEST['COD_MUNICIPIO_E']);
		$cod_filtro = fnLimpaCampo($_REQUEST['COD_FILTRO']);
		$qtd_membros = fnLimpaCampoZero($_REQUEST['QTD_MEMBROS']);
		$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo($_POST['INPUT']);
		//array dos filtros
		if (isset($_POST['COD_FILTRO'])) {
			$arr_cod_filtro = $_POST['COD_FILTRO'];
			//print_r($Arr_COD_FILTRO);			 

			for ($i = 0; $i < count($arr_cod_filtro); $i++) {
				$cod_filtro = $cod_filtro . $arr_cod_filtro[$i] . ",";
			}

			$cod_filtro = rtrim($cod_filtro, ',');
		} else {
			$cod_filtro = "0";
		}

		//fnEscreve($cod_sistemas);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];


		if ($opcao != '') {

			$sqlUsu = "SELECT COD_USUARIO FROM REGIAO_USUARIO WHERE COD_USUARIO = $cod_usuario AND COD_MUNICIPIO_E = $cod_municipio_e";
			$countUsu = mysqli_query(connTemp($cod_empresa, ''), $sqlUsu);

			$sqlCidade = "SELECT COD_MUNICIPIO FROM MUNICIPIOS WHERE COD_MUNICIPIO_E = $cod_municipio_e";
			$qrCidade = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlCidade));

			if (mysqli_num_rows($countUsu) == 0) {

				$sql = "INSERT INTO REGIAO_USUARIO(
										COD_USUARIO,
										COD_MUNICIPIO,
										COD_MUNICIPIO_E,
										COD_TPFILTRO,
										COD_FILTRO,
										COD_EMPRESA
									) VALUES(
										$cod_usuario,
										$qrCidade[COD_MUNICIPIO],
										$cod_municipio_e,
										28,
										'$cod_filtro',
										$cod_empresa
					)";

				$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				//fnEscreve($sql);
			} else {

				$sql = "UPDATE REGIAO_USUARIO SET
								   COD_FILTRO = '$cod_filtro'
							WHERE COD_USUARIO = $cod_usuario 
							AND COD_MUNICIPIO_E = $cod_municipio_e";

				$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
			}

			mysqli_query(connTemp($cod_empresa, ''), $sql);

			$cod_municipio = $qrCidade["COD_MUNICIPIO"];
			$sql = "SELECT COD_MUNICIPIO FROM MEMBROS_CIDADE WHERE COD_EMPRESA = $cod_empresa AND COD_MUNICIPIO = $cod_municipio";
			$count = mysqli_query(connTemp($cod_empresa, ''), $sql);
			if (mysqli_num_rows($count) == 0) {

				$sql = "INSERT INTO MEMBROS_CIDADE(
										COD_EMPRESA,
										COD_MUNICIPIO,
										QTD_MEMBROS 
									) VALUES(
										$cod_empresa,
										$qrCidade[COD_MUNICIPIO],
										'$qtd_membros'
					)";
			} else {

				$sql = "UPDATE MEMBROS_CIDADE SET
								   QTD_MEMBROS = '$qtd_membros'
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_MUNICIPIO = $cod_municipio";
			}

			mysqli_query(connTemp($cod_empresa, ''), $sql);

			$msgTipo = 'alert-success';
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	//fnEscreve('entrou if');

	$sql = "SELECT STATUSSISTEMA.DES_STATUS,empresas.* FROM empresas  
				LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS
				where COD_EMPRESA = '" . $cod_empresa . "' 
		";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$cod_cadastr = $qrBuscaEmpresa['COD_CADASTR'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$des_abrevia = $qrBuscaEmpresa['DES_ABREVIA'];
		$nom_respons = $qrBuscaEmpresa['NOM_RESPONS'];
		$num_cgcecpf = $qrBuscaEmpresa['NUM_CGCECPF'];
		$cod_estatus = $qrBuscaEmpresa['COD_STATUS'];
		$log_ativo = $qrBuscaEmpresa['LOG_ATIVO'];
		if ($log_ativo == 'S') {
			$checadoLog_ativo = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$checadoLog_ativo = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}
		if ($log_precuni == 'S') {
			$checadoLog_precuni = 'checked';
		} else {
			$checadoLog_precuni = '';
		}
		if ($log_precuni == 'S') {
			$tem_precuni = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$tem_precuni = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}
		if ($log_estoque == 'S') {
			$checadoLog_estoque = 'checked';
		} else {
			$checadoLog_estoque = '';
		}
		if ($log_estoque == 'S') {
			$tem_estoque = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$tem_estoque = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}
		$num_escrica = $qrBuscaEmpresa['NUM_ESCRICA'];
		$nom_fantasi = $qrBuscaEmpresa['NOM_FANTASI'];
		$num_telefon = $qrBuscaEmpresa['NUM_TELEFON'];
		$num_celular = $qrBuscaEmpresa['NUM_CELULAR'];
		$des_enderec = $qrBuscaEmpresa['DES_ENDEREC'];
		$num_enderec = $qrBuscaEmpresa['NUM_ENDEREC'];
		$des_complem = $qrBuscaEmpresa['DES_COMPLEM'];
		$des_bairroc = $qrBuscaEmpresa['DES_BAIRROC'];
		$num_cepozof = $qrBuscaEmpresa['NUM_CEPOZOF'];
		$nom_cidadec = $qrBuscaEmpresa['NOM_CIDADEC'];
		$cod_estadof = $qrBuscaEmpresa['COD_ESTADOF'];
		$cod_estado = $qrBuscaEmpresa['COD_ESTADO'];
		$cod_sistemas = $qrBuscaEmpresa['COD_SISTEMAS'];
		if (!empty($cod_sistemas)) {
			$tem_sistemas = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$tem_sistemas = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}
		$cod_master = $qrBuscaEmpresa['COD_MASTER'];
		$cod_layout = $qrBuscaEmpresa['COD_LAYOUT'];
		$cod_segment = $qrBuscaEmpresa['COD_SEGMENT'];
		$des_sufixo = $qrBuscaEmpresa['DES_SUFIXO'];
		$des_status = $qrBuscaEmpresa['DES_STATUS'];
		$log_consext = $qrBuscaEmpresa['LOG_CONSEXT'];
		if ($log_consext == 'S') {
			$tem_consext = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$tem_consext = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}
		$log_autocad = $qrBuscaEmpresa['LOG_AUTOCAD'];
		if ($log_autocad == 'S') {
			$tem_autocad = '<i class="fa fa-check-square text-success" aria-hidden="true"></i>';
		} else {
			$tem_autocad = '<i class="fa fa-times-square text-danger" aria-hidden="true"></i>';
		}

		$tip_contabil = $qrBuscaEmpresa['TIP_CONTABIL'];
		if ($tip_contabil == 'RESG') {
			$tip_contabil = 'Resgate';
		} else {
			$tip_contabil = 'Desconto';
		}
		$site = $qrBuscaEmpresa['SITE'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

// esquema do X da barra - (recarregar pesquisa)
if ($val_pesquisa != "") {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}
// ---------------------------------------------

//fnEscreve($_SESSION["SYS_COD_EMPRESA"]);	
//fnEscreve(fnDecode($_GET['ID']));	
//fnEscreve(fnDecode($_GET['id']));		
//fnMostraForm();

// fnEscreve('chega');


?>
<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
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
				//manu superior - empresas
				$abaEmpresa = 1590;
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 16: //gabinete
						include "abasGabinete.php";
						break;
					default;
						include "abasEmpresaConfig.php";
						break;
				}
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais </legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Usuários</label>
										<select data-placeholder="Selecione um usuário" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" required>
											<option value=""></option>
											<?php

											$sql = "SELECT COD_USUARIO, NOM_USUARIO FROM USUARIOS WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S' ORDER BY NOM_USUARIO";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

											while ($qrLayout = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																			  <option value='" . $qrLayout['COD_USUARIO'] . "'>" . $qrLayout['NOM_USUARIO'] . "</option> 
																			";
											}
											?>
										</select>
										<script>
											$("#formulario #COD_LAYOUT").val("<?php echo $cod_layout; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Cidade</label>

										<select data-placeholder="Selecione um município" name="COD_MUNICIPIO_E" id="COD_MUNICIPIO_E" class="chosen-select-deselect" required>
											<option value=""></option>
											<?php

											$sql = "SELECT COD_MUNICIPIO_E, NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_ESTADO = $cod_estado AND COD_MUNICIPIO_E != 0 ORDER BY NOM_MUNICIPIO";
											$arrayCidade = mysqli_query(connTemp($cod_empresa, ''), $sql);
											while ($qrCidade = mysqli_fetch_assoc($arrayCidade)) {
											?>
												<option value="<?= $qrCidade['COD_MUNICIPIO_E'] ?>"><?= $qrCidade['NOM_MUNICIPIO'] ?></option>
											<?php
											}

											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Região</label>
										<select data-placeholder="Selecione o filtro" name="COD_FILTRO" id="COD_FILTRO" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
											<?php

											$sql = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																			WHERE COD_EMPRESA = $cod_empresa
																			AND COD_TPFILTRO = 28
																			ORDER BY DES_FILTRO";

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

											while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrStatus['COD_FILTRO']; ?>"><?php echo $qrStatus['DES_FILTRO']; ?></option>
											<?php
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>


								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Qtd. Membros</label>
										<input type="text" class="form-control input-sm" name="QTD_MEMBROS" id="QTD_MEMBROS" value="<?php echo $qtd_membros; ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-save" aria-hidden="true"></i>&nbsp; Salvar</button>
						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
					<div class="push30"></div>

					<div class="row">
						<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

							<div class="col-xs-4 col-xs-offset-4">
								<div class="input-group activeItem">
									<div class="input-group-btn search-panel">
										<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
											<span id="search_concept">Sem filtro</span>&nbsp;
											<span class="fal fa-angle-down"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li class="divisor"><a href="#">Sem filtro</a></li>
											<li class="divisor"><a href="#US.NOM_USUARIO">Usuário</a></li>
											<li class="divisor"><a href="#MU.NOM_MUNICIPIO">Cidade</a></li>
											<li class="divisor"><a href="#F.DES_FILTRO">Região</a></li>
											<!-- <li class="divider"></li> -->
										</ul>
									</div>
									<input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">
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

					<div class="portlet portlet-bordered">
						<div class="portlet-body">

							<div class="login-form">
								<div class="row">
									<div class="col-md-12">

										<table class="table table-bordered table-striped table-hover tablesorter buscavel">
											<thead>
												<tr>
													<th class="{ sorter: false }" width="40"></th>
													<th>Usuário</th>
													<th>Cidade</th>
													<th>Qtd. Membros</th>
													<th>Região</th>
												</tr>
											</thead>
											<tbody>

												<?php

												if ($filtro != "") {
													$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
												} else {
													$andFiltro = " ";
												}
												$sql = "SELECT RU.*,M.QTD_MEMBROS, MU.NOM_MUNICIPIO, US.NOM_USUARIO,F.DES_FILTRO FROM REGIAO_USUARIO RU 
																INNER JOIN MUNICIPIOS MU ON MU.COD_MUNICIPIO = RU.COD_MUNICIPIO
																INNER JOIN WEBTOOLS.USUARIOS US ON US.COD_USUARIO = RU.COD_USUARIO
																INNER JOIN MEMBROS_CIDADE M ON M.COD_EMPRESA = RU.COD_EMPRESA AND M.COD_MUNICIPIO = RU.COD_MUNICIPIO
																INNER JOIN FILTROS_CLIENTE F ON F.COD_TPFILTRO=RU.COD_TPFILTRO AND F.COD_FILTRO=RU.COD_FILTRO AND F.COD_EMPRESA=RU.COD_EMPRESA
																WHERE RU.COD_EMPRESA = $cod_empresa  
																$andFiltro
																ORDER BY US.NOM_USUARIO, MU.NOM_MUNICIPIO";

												//echo($sql);

												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												$count = 0;
												while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

													$count++;
													echo "
															<tr>
																<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
																<td>" . $qrBuscaModulos['NOM_USUARIO'] . "</td>
																<td>" . $qrBuscaModulos['NOM_MUNICIPIO'] . "</td>
																<td>" . $qrBuscaModulos['QTD_MEMBROS'] . "</td>
																<td>" . $qrBuscaModulos['DES_FILTRO'] . "</td>
															</tr>
															<input type='hidden' id='ret_COD_USUARIO_" . $count . "' value='" . $qrBuscaModulos['COD_USUARIO'] . "'>
																															<input type='hidden' id='ret_NOM_MUNICIPIO_" . $count . "' value='" . $qrBuscaModulos['NOM_MUNICIPIO'] . "'>
																															<input type='hidden' id='ret_DES_FILTRO_" . $count . "' value='" . $qrFiltros['DES_FILTRO'] . "'>
															<input type='hidden' id='ret_COD_MUNICIPIO_E_" . $count . "' value='" . $qrBuscaModulos['COD_MUNICIPIO_E'] . "'>
															<input type='hidden' id='ret_COD_FILTRO_" . $count . "' value='" . $qrBuscaModulos['COD_FILTRO'] . "'>
															<input type='hidden' id='ret_QTD_MEMBROS_" . $count . "' value='" . $qrBuscaModulos['QTD_MEMBROS'] . "'>
															";
												}

												?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

					</div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

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

	$(document).ready(function() {
		$(".nav-tabs li").on("click", function(e) {
			if ($(this).hasClass("disabled")) {
				e.preventDefault();
				return false;
			}
		});
	});

	function retornaForm(index) {
		$("#formulario #COD_USUARIO").val($("#ret_COD_USUARIO_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_MUNICIPIO_E").val($("#ret_COD_MUNICIPIO_E_" + index).val()).trigger("chosen:updated");
		$("#formulario #QTD_MEMBROS").val($("#ret_QTD_MEMBROS_" + index).val());

		var cod_filtro = $('#ret_COD_FILTRO_' + index).val();
		if (cod_filtro != 0 && cod_filtro != "") {
			//retorno combo multiplo - cod_filtro
			$("#formulario #COD_FILTRO").val('').trigger("chosen:updated");

			var sistemasUni = cod_filtro;
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_FILTRO option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_FILTRO").trigger("chosen:updated");
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>