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
$cod_servidor = "";
$des_servidor = "";
$des_abrevia = "";
$des_geral = "";
$cod_operacional = "";
$des_observa = "";
$filtro = "";
$val_pesquisa = "";
$hHabilitado = "";
$hashForm = "";
$cod_submenus = "";
$esconde = "";
$popUp = "";
$arrayQuery = [];
$qrListaDatabase = "";
$tipoServer = "";
$qrListaServidor = "";
$arrayQuery2 = [];

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_servidor = fnLimpaCampoZero(@$_REQUEST['COD_SERVIDOR']);
		$des_servidor = fnLimpaCampo(@$_POST['DES_SERVIDOR']);
		$des_abrevia = fnLimpaCampo(@$_POST['DES_ABREVIA']);
		$des_geral = fnLimpaCampo(@$_POST['DES_GERAL']);
		$cod_operacional = fnLimpaCampoZero(@$_POST['COD_OPERACIONAL']);
		$des_observa = fnLimpaCampo(@$_POST['DES_OBSERVA']);

		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_SERVIDORES (
				 '" . $cod_servidor . "', 
				 '" . $des_servidor . "', 
				 '" . $des_abrevia . "', 
				 '" . $cod_operacional . "', 
				 '" . $des_geral . "', 
				 '" . $des_observa . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;
			//fnEscreve($cod_submenus);

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

if ($val_pesquisa != '') {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}

//fnMostraForm();

?>

<?php if ($popUp != "true") { ?>
	<div class="push30"></div>
<?php } ?>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
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

				<div class="push30"></div>

				<div class="row">

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
									<!-- <li><a href="#NOM_EMPRESA">Razão social</a></li>
									                    <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
									                    <li><a href="#CNPJ">CNPJ</a></li> -->
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

				</div>

				<div class="push30"></div>

				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#databases">Lista Databases</a></li>
					<li><a data-toggle="tab" href="#servers">Lista Servidores</a></li>
				</ul>

				<div class="tab-content buscavel">
					<!-- aba databases -->
					<div id="databases" class="tab-pane fade in active">

						<div class="push30"></div>

						<div class="row">


							<div class="col-md-2">

								<div class="panelBox primary">
									<a href="#">
										<div class="addBox" data-url="action.php?mod=<?php echo fnEncode(1027) ?>&id=K2xr0lE3UHI¢&pop=true" data-title="Database">
											<i class="fa fa-plus fa-2x" aria-hidden="true" style="margin: 70px 0 75px 0;"></i>
										</div>
									</a>
								</div>

							</div>



							<?php

							$sql = "select A.*,
															(select C.DES_SERVIDOR from servidores C where C.COD_SERVIDOR = A.COD_SERVIDOR ) as NOM_SERVIDOR,													
															(select B.NOM_FANTASI from empresas B where B.COD_EMPRESA = A.COD_EMPRESA ) as NOM_FANTASI													
															from tab_database A order by des_database ";
							$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

							// fnEscreve($sql);

							$count = 0;
							while ($qrListaDatabase = mysqli_fetch_assoc($arrayQuery)) {
								$count++;
								//if ($qrListaDatabase['COD_SERVIDOR'] == 1 ) {
								//	$tipoServer = "fa-windows";	
								//}else {$tipoServer = "fa-linux";	}
							?>

								<div class="col-md-2 item-bd">

									<div class="panel">
										<a href="#" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1027) . "&id=" . fnEncode($qrListaDatabase['COD_DATABASE']) ?>&pop=true" data-title="Database">
											<div class="top primary"><i class="fa fa-database fa-3x iwhite" aria-hidden="true"></i>
												<h6><?php echo $qrListaDatabase['NOM_DATABASE'] ?> &nbsp; <?php echo $qrListaDatabase['COD_EMPRESA'] ?> </h6>
											</div>
											<div class="bottom" style="height: 90px;">
												<h2 class="referencia-busca" style="font-size: 18px; margin: 0 0 10px 0;"><?php echo $qrListaDatabase['DES_DATABASE'] ?> </h2>
												<h6 class="referencia-busca" style="padding: 10px 0 0 0;"><?php echo $qrListaDatabase['NOM_FANTASI'] ?></h6>
											</div>
										</a>
									</div>

								</div>


							<?php
							}

							?>



						</div>


					</div>

					<!-- aba totem -->
					<div id="servers" class="tab-pane fade">

						<div class="push30"></div>

						<?php
						$sql = "select COD_SERVIDOR, DES_SERVIDOR from servidores order by des_servidor ";
						$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

						while ($qrListaServidor = mysqli_fetch_assoc($arrayQuery)) {

							$cod_servidor = $qrListaServidor['COD_SERVIDOR'];

						?>

							<h4><?php echo $qrListaServidor['DES_SERVIDOR']; ?> </h4>

							<div class="push20"></div>

							<?php

							$sql = "select A.*,
															(select C.DES_SERVIDOR from servidores C where C.COD_SERVIDOR = A.COD_SERVIDOR ) as NOM_SERVIDOR,													
															(select B.NOM_FANTASI from empresas B where B.COD_EMPRESA = A.COD_EMPRESA ) as NOM_FANTASI													
															from tab_database A where A.COD_SERVIDOR = $cod_servidor order by des_database ";

							$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql);

							$count = 0;
							while ($qrListaDatabase = mysqli_fetch_assoc($arrayQuery2)) {
								$count++;
								//if ($qrListaDatabase['COD_SERVIDOR'] == 1 ) {
								//	$tipoServer = "fa-windows";	
								//}else {$tipoServer = "fa-linux";	}
							?>

								<div class="col-md-2 text-center item-bd" style="min-height: 120px;">

									<i class="fa fa-database fa-2x" aria-hidden="true"></i>
									<h6 class="referencia-busca" style="padding: 0 0 0 0;"><?php echo $qrListaDatabase['NOM_DATABASE'] ?> &nbsp; <?php echo $qrListaDatabase['COD_EMPRESA'] ?> </h6>
									<h2 class="referencia-busca" style="font-size: 18px; margin: 10px 0 0 0;"><?php echo $qrListaDatabase['DES_DATABASE'] ?></h2>

								</div>

							<?php
							}
							?>

							<div class="push30"></div>

						<?php

						}
						?>


					</div>

					<div class="push30"></div>

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

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push30"></div>

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
				$(".buscavel .item-bd").each(function(index) {
					if (!index) return;
					$(this).find(".referencia-busca").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('.item-bd').toggle(!sem_registro);
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
			$(".buscavel .item-bd").each(function(index) {
				if (!index) return;
				$(this).find(".referencia-busca").each(function() {
					var id = $(this).text().toLowerCase().trim();
					var sem_registro = (id.indexOf(value) == -1);
					$(this).closest('.item-bd').toggle(!sem_registro);
					return sem_registro;
				});
			});
		}
	}

	//-----------------------------------------------------------------------------------		

	function retornaForm(index) {
		$("#formulario #COD_SERVIDOR").val($("#ret_COD_SERVIDOR_" + index).val());
		$("#formulario #DES_SERVIDOR").val($("#ret_DES_SERVIDOR_" + index).val());
		$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_" + index).val());
		$("#formulario #DES_GERAL").val($("#ret_DES_GERAL_" + index).val());
		$("#formulario #COD_OPERACIONAL").val($("#ret_COD_OPERACIONAL_" + index).val()).trigger("chosen:updated");
		$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>