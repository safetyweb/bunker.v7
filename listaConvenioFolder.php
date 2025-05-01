<?php

//echo fnDebug('true');

$log_obrigat = "N";
$val_pesquisa = "";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_entidad = fnLimpaCampoZero($_REQUEST['COD_ENTIDAD']);
		$num_process = fnLimpaCampo($_REQUEST['NUM_PROCESS']);
		$num_conveni = fnLimpaCampo($_REQUEST['NUM_CONVENI']);
		$nom_conveni = fnLimpaCampo($_REQUEST['NOM_CONVENI']);
		$nom_abrevia = fnLimpaCampo($_REQUEST['NOM_ABREVIA']);
		$des_descric = fnLimpaCampo($_REQUEST['DES_DESCRIC']);
		$val_valor = fnLimpaCampo($_REQUEST['VAL_VALOR']);
		$val_contpar = fnLimpaCampo($_REQUEST['VAL_CONTPAR']);
		$dat_inicinv = fnLimpaCampo($_REQUEST['DAT_INICINV']);
		$dat_fimconv = fnLimpaCampo($_REQUEST['DAT_FIMCONV']);
		$dat_assinat = fnLimpaCampo($_REQUEST['DAT_ASSINAT']);

		$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CONVENIO (
				 '" . $cod_conveni . "', 
				 '" . $cod_empresa . "',
				 '" . $cod_entidad . "', 
				 '" . $num_process . "', 
				 '" . $num_conveni . "',
				 '" . $nom_conveni . "',
				 '" . $nom_abrevia . "',
				 '" . $des_descric . "',
				 '" . fnValorSql2($val_valor) . "',
				 '" . fnValorSql2($val_contpar) . "',
				 '" . fnDataSql($dat_inicinv) . "',
				 '" . fnDataSql($dat_fimconv) . "',
				 '" . fnDataSql($dat_assinat) . "',
				 '" . $opcao . "'    
			        );";

			//fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa, ''), $sql);

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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$nom_empresa = "";
}

if ($val_pesquisa != "") {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}

//fnMostraForm();
//fnEscreve($cod_checkli);

?>
<link rel="stylesheet" href="css/widgets.css" />
<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>
				<?php include "atalhosPortlet.php"; ?>
			</div>
			<div class="portlet-body buscavel">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php
				//manu superior - empresas
				$abaEmpresa = 1096;
				include "abasGabinete.php";

				?>

				<div class="push30"></div>

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
										<li><a href="#NOM_EMPRESA">Objeto (observação)</a></li>
										<li><a href="#NOM_FANTASI">Período</a></li>
										<li><a href="#CNPJ">Nro. da licitação</a></li>
										<li><a href="#CNPJ">Orgão</a></li>
										<li><a href="#CNPJ">Valor</a></li>
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

						<input type="hidden" name="REFRESH_CONVENIOS" id="REFRESH_CONVENIOS" value="N">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>

				</div>

				<div class="push50"></div>

				<style>
					.change-icon .fal+.fal,
					.change-icon:hover .fal:not(.fa-edit) {
						display: none;
					}

					.change-icon:hover .fal+.fal:not(.fa-edit) {
						display: inherit;
					}

					.fa-edit:hover {
						color: #18bc9c;
						cursor: pointer;
					}

					.item {
						padding-top: 0;
					}
				</style>


				<div class="col-md-2">

					<div class="panelBox borda">

						<div class="addBox item-busca" data-url="action.php?mod=<?php echo fnEncode(1097) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode(0); ?>&tipo=<?php echo fnEncode('CAD') ?>&pop=true" data-title="Convênio">
							<i class="fal fa-plus fa-2x" aria-hidden="true" style="margin: 40px 0 40px 0;"></i>
						</div>
					</div>

				</div>

				<div id="listaConvenios">
					<?php
					$sql = "SELECT  CONVENIO.COD_CONVENI,
													CONVENIO.COD_EMPRESA,
													CONVENIO.COD_ENTIDAD,
													CONVENIO.NUM_PROCESS,
													CONVENIO.NUM_CONVENI,
													CONVENIO.NOM_CONVENI,
													CONVENIO.NOM_ABREVIA,
													CONVENIO.DES_DESCRIC,
													CONVENIO.VAL_VALOR,
													CONVENIO.VAL_CONTPAR,
													CONVENIO.DAT_INICINV,
													CONVENIO.DAT_FIMCONV,
													CONVENIO.DAT_ASSINAT,
													EMPRESAS.NOM_EMPRESA,
													ENTIDADE.NOM_ENTIDAD 
										FROM CONVENIO
											LEFT JOIN $connAdm->DB.empresas ON CONVENIO.COD_EMPRESA = empresas.COD_EMPRESA
											LEFT JOIN ENTIDADE ON CONVENIO.COD_ENTIDAD = ENTIDADE.COD_ENTIDAD
										WHERE empresas.COD_EMPRESA = $cod_empresa
										ORDER BY COD_CONVENI";

					//fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

					$count = 0;
					while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
						$count++;
					?>

						<div class="col-md-2 item-busca">
							<div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
								<a data-url="action.php?mod=<?php echo fnEncode(1097) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($qrBuscaModulos['COD_CONVENI']); ?>&tipo=<?php echo fnEncode('ALT') ?>&pop=true" data-title="Template" class="informer informer-default addBox" style="color: #2c3e50;">
									<span class="fa fa-edit"></span>
								</a>
								<a href='action.php?mod=<?php echo fnEncode(1098) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($qrBuscaModulos['COD_CONVENI']) ?>' style='color: #2c3e50; border: none; text-decoration: none;'>
									<i class="fal fa-folder fa-lg" style="font-size: 40px"></i>
									<i class="fal fa-folder-open fa-lg" style="font-size: 40px"></i>
									<p class="folder referencia-busca"><?php echo $qrBuscaModulos['NOM_CONVENI']; ?></p>
								</a>
							</div>
						</div>

					<?php
					}
					?>
				</div>
			</div>

			<div class="push50"></div>

		</div>

	</div>
</div>
<!-- fim Portlet -->
</div>

</div>

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
				$(".buscavel .item-busca").each(function(index) {
					if (!index) return;
					$(this).find(".referencia-busca").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('.item-busca').toggle(!sem_registro);
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
			$(".buscavel .item-busca").each(function(index) {
				if (!index) return;
				$(this).find(".referencia-busca").each(function() {
					var id = $(this).text().toLowerCase().trim();
					var sem_registro = (id.indexOf(value) == -1);
					$(this).closest('.item-busca').toggle(!sem_registro);
					return sem_registro;
				});
			});
		}
	}

	//-----------------------------------------------------------------------------------		

	$(document).ready(function() {

		//modal close
		$('.modal').on('hidden.bs.modal', function() {
			//console.log('entrou');
			if ($('#REFRESH_CONVENIOS').val() == "S") {
				//alert("atualiza");
				RefreshConvenios(<?php echo $cod_empresa; ?>, <?php echo $cod_template; ?>);
				$('#REFRESH_CONVENIOS').val("N");
			}
		});

	});

	function RefreshConvenios(idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshConvenios.php",
			data: {
				ajx1: idEmp
			},
			beforeSend: function() {
				$('#listaConvenios').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#listaConvenios").html(data);
			},
			error: function() {
				$('#listaConvenios').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {
		$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_" + index).val());
		$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val());
		$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_" + index).val()).trigger("chosen:updated");
		$("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_" + index).val());
		$("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_" + index).val());
		$("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_" + index).val());
		$("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_" + index).val());
		$("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_" + index).val());
		$("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_" + index).val());
		$("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_" + index).val());
		$("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_" + index).val());
		$("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_" + index).val());
		$("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>