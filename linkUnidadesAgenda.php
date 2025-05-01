<?php

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

		if ($opcao != '') {


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

//fnMostraForm();

if ($val_pesquisa != "") {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}


?>

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
	@media (max-width: 767px) {
		#INPUT {
			width: 100%;
		}
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
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

				<div class="push30"></div>

				<div class="login-form">

					<form method="post" id="formLista" action="action.php?mod=<?php echo $DestinoPg; ?>">

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

					</form>

					<div class="container" style="max-width: 500px;">
						<div class="input-group activeItem">
							<div class="input-group-btn search-panel">
								<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
									<span id="search_concept">Sem filtro</span>&nbsp;
									<span class="far fa-angle-down"></span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li class="divisor"><a href="#">Sem filtro</a></li>
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
					<div class="push30"></div>

					<?php

					$totVendedor = 0;

					$andUnidades = "AND UV.COD_UNIVEND IN($_SESSION[SYS_COD_UNIVEND])";

					if ($_SESSION['SYS_COD_EMPRESA'] == '2' || $_SESSION['SYS_COD_EMPRESA'] == '3') {
						$andUnidades = "";
					}


					$sql = "SELECT DISTINCT UV.NOM_FANTASI, UV.COD_UNIVEND
								FROM USUARIOS US
								INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = US.COD_UNIVEND
								WHERE US.COD_EMPRESA = $cod_empresa
								AND US.LOG_ESTATUS = 'S'
								$andUnidades
								AND (US.COD_EXCLUSA IS NULL OR US.COD_EXCLUSA = 0)
								AND US.COD_USUARIO IN(SELECT COD_RESPONSAVEL FROM DESAFIO_CONTROLE_V2 DC2
													   INNER JOIN DESAFIO_V2 D2 ON D2.COD_DESAFIO = DC2.COD_DESAFIO
													   WHERE DC2.COD_EMPRESA = $cod_empresa
													   AND D2.LOG_ATIVO = 'S'
													   AND D2.DAT_FIM >= NOW())
								ORDER BY NOM_FANTASI";

					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

					$num_lojas = mysqli_num_rows($arrayQuery);

					if ($num_lojas == 0) {
					?>

						<center>
							<h4>Não há agenda ativa/dentro da validade.</h4>
						</center>

					<?php
					}


					while ($qrListaUni = mysqli_fetch_assoc($arrayQuery)) {

						$unidadeLoop = $qrListaUni['COD_UNIVEND'];
						$countVendedores = 0;
					?>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<h4><b><?= $qrListaUni['NOM_FANTASI'] ?> <span class='f16'><i class='fal fa-user'></i>&nbsp; <span id="<?= $unidadeLoop ?>"></span></span></b></h4>
								<div class="push10"></div>

								<?php

								$sqlUsu = "SELECT US.COD_USUARIO,
															  US.NOM_USUARIO,
															  (SELECT COUNT(DISTINCT COD_CLIENTE) CLIENTES_DESAFIO 
															  	FROM DESAFIO_CONTROLE_V2 DC2 
															  	INNER JOIN DESAFIO_V2 D2 ON D2.COD_DESAFIO = DC2.COD_DESAFIO
															   WHERE DC2.COD_EMPRESA = $cod_empresa 
															   AND DC2.COD_RESPONSAVEL = US.COD_USUARIO
															   AND D2.LOG_ATIVO = 'S'
															   AND D2.DAT_FIM >= NOW()) CLIENTES_DESAFIO
											FROM USUARIOS US
											WHERE US.COD_EMPRESA = $cod_empresa
											AND US.LOG_ESTATUS = 'S'
											AND US.COD_UNIVEND = $qrListaUni[COD_UNIVEND]
											AND US.COD_TPUSUARIO IN(2,7,8,9,11)
											AND (US.COD_EXCLUSA IS NULL OR US.COD_EXCLUSA = 0)
											AND (SELECT COUNT(DISTINCT COD_CLIENTE) CLIENTES_DESAFIO 
															  	FROM DESAFIO_CONTROLE_V2 DC2 
															  	INNER JOIN DESAFIO_V2 D2 ON D2.COD_DESAFIO = DC2.COD_DESAFIO
															   WHERE DC2.COD_EMPRESA = $cod_empresa 
															   AND DC2.COD_RESPONSAVEL = US.COD_USUARIO
															   AND D2.LOG_ATIVO = 'S'
															   AND D2.DAT_FIM >= NOW()) > 0
											ORDER BY TRIM(US.NOM_USUARIO)";

								$arrUsu = mysqli_query(connTemp($cod_empresa, ''), $sqlUsu);
								// fnEscreve($sqlUsu);

								if ($qrListaUni['COD_UNIVEND'] == 97479) {

									// fnEscreve2($sqlUsu);
								}

								$count = 0;
								while ($qrListaUsu = mysqli_fetch_assoc($arrUsu)) {
									$count++;

									echo "
												<div class='teste'>
												<a href='action.do?mod=" . fnEncode($RedirectPg) . "&id=" . fnEncode($cod_empresa) . "&idU=" . fnEncode($qrListaUsu['COD_USUARIO']) . "&idUv=" . fnEncode($qrListaUni['COD_UNIVEND']) . "'>" . $qrListaUsu['NOM_USUARIO'] . "&nbsp;<span class='f10'>" . $qrListaUsu['COD_USUARIO'] . "</span>&nbsp;&nbsp;<span class='f16'><i class='fal fa-users'></i>&nbsp; $qrListaUsu[CLIENTES_DESAFIO]</span></a>
												<div class='push10'></div>
												</div>
												";

									$totVendedor++;
									$countVendedores++;
								}

								?>

								</tbody>
								</table>

								<script>
									$("#<?= $unidadeLoop ?>").text("<?= $countVendedores ?>");
								</script>

							</div>

						</div>

						<div class="push20"></div>

					<?php

					}

					?>

					<span>Total de Vendedores: <?php echo ($totVendedor); ?></span>

					<div class="push10"></div>

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
				$(".teste").each(function(index) {
					if (!index) return;
					$(this).find("a").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('.teste').toggle(!sem_registro);
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
			$(".teste").each(function(index) {
				if (!index) return;
				$(this).find("a").each(function() {
					var id = $(this).text().toLowerCase().trim();
					var sem_registro = (id.indexOf(value) == -1);
					$(this).closest('.teste').toggle(!sem_registro);
					return sem_registro;
				});
			});
		}
	}

	function retornaForm(index) {

		//$("#nomBusca").val($("#ret_NOM_EMPRESA_"+index).val());
		$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=<?php echo fnEncode($cod_empresa); ?>&idU=' + $("#ret_COD_USUARIO_" + index).val());
		$('#formLista').submit();

	}
</script>