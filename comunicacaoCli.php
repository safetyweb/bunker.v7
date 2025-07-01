<?php

//echo fnDebug('true');

$hashLocal = mt_rand();
$msgRetorno = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
		$cod_cliente = fnLimpacampoZero($_REQUEST['COD_CLIENTE']);
		if (isset($_REQUEST['COD_TIPMOTI '])) {
			$cod_tipmoti = fnLimpacampoZero($_REQUEST['COD_TIPMOTI']);
		}

		$num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO']);

		if (isset($_REQUEST['NUM_CARTAO_NOVO'])) {
			$num_cartao_novo = fnLimpacampo($_REQUEST['NUM_CARTAO_NOVO']);
		}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			//busca dados da empresa
			$sql = "select LOG_AUTOCAD FROM EMPRESAS WHERE COD_EMPRESA = '" . $cod_empresa . "' ";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
			$qrBuscaLOG_AUTOCAD = mysqli_fetch_assoc($arrayQuery);
			$log_autocad = $qrBuscaLOG_AUTOCAD['LOG_AUTOCAD'];

			$sql1 = "CALL SP_ALTERA_NUMEROCARTAO(
						'" . $cod_cliente . "',
						'" . $cod_empresa . "',
						'" . $num_cartao . "',
						'" . $num_cartao_novo . "',
						'" . $cod_usucada . "',
						'" . $cod_tipmoti . "',
						'" . $log_autocad . "',
						'" . $opcao . "'  
					) ";

			//echo $sql1;	

			//$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql1);
			//$qrBuscaRetorno = mysqli_fetch_assoc($arrayQuery);
			//$mensagem_retorno = $qrBuscaRetorno['mensagem_retorno'];

			//mensagem de retorno
			$msgRetorno = $mensagem_retorno;
			if ($mensagem_retorno != "Alterado com sucesso!") {
				$msgTipo = 'alert-danger';
			} else {
				$msgTipo = 'alert-success';
			}
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);

	if (isset($_GET['tp'])) {
		$cod_cliente = fnLimpaCampoZero($_GET['idC']);
	} else {
		$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idC']));
	}

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
	$nom_empresa = "";
}


//busca dados do cliente
$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE FROM CLIENTES where COD_CLIENTE = '" . $cod_cliente . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {

	$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
	$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
} else {

	$nom_cliente = "";
	$cod_cliente = "";
	$num_cartao = "";
	$num_cgcecpf = "";
}

include "labelLibrary.php";

//fnMostraForm();
//fnEscreve($mensagem_retorno);

?>

<style>
	.chosen-big+div>.chosen-single {
		height: 45px !important;
		line-height: 20px !important;
		padding: 10px 15px !important;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>

				<?php
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 16: //gerenciador social
						$formBack = "1424";
						break;
					default;
						//$formBack = "1015";
						break;
				}
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
				$abaCli = 1253;
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 19:
					case 20:
						include "abasClienteRH.php";
						break;
					case 21: //gestão garantias
						include "abasGestaoGarantiasCli.php";
						break;
					default:
						include "abasClienteConfig.php";
						break;
				}
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código do Cliente</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>" required>
									</div>
								</div>

								<div class="col-md-5">
									<label for="inputName" class="control-label required"><?= ($labelNome); ?></label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Venda Avulsa - Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
										</span>
										<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leituraOff" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente; ?>">
										<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>" required>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Número do Cartão</label>
										<input type="text" class="form-control input-sm text-right leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>

						<?php
						switch ($_SESSION["SYS_COD_SISTEMA"]) {
							case 3: //amd marka
							case 4: //fidelidade
						?>
								<fieldset>
									<legend>Filtros</legend>

									<div class="row">

										<div class="col-md-1">
											<div class="form-group">
												<label for="inputName" class="control-label">e-Mail</label>
												<div class="push5"></div>
												<label class="switch">
													<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" checked>
													<span></span>
												</label>
											</div>
										</div>

										<div class="col-md-1">
											<div class="form-group">
												<label for="inputName" class="control-label">Sms</label>
												<div class="push5"></div>
												<label class="switch">
													<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" checked>
													<span></span>
												</label>
											</div>
										</div>

										<div class="col-md-1">
											<div class="form-group">
												<label for="inputName" class="control-label">Whats Up</label>
												<div class="push5"></div>
												<label class="switch">
													<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" checked>
													<span></span>
												</label>
											</div>
										</div>

										<div class="col-md-1">
											<div class="form-group">
												<label for="inputName" class="control-label">Contatos</label>
												<div class="push5"></div>
												<label class="switch">
													<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" checked>
													<span></span>
												</label>
											</div>
										</div>

										<div class="col-md-1">
											<div class="form-group">
												<label for="inputName" class="control-label">Pesquisas</label>
												<div class="push5"></div>
												<label class="switch">
													<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" checked>
													<span></span>
												</label>
											</div>
										</div>

										<div class="col-md-2">
											<div class="push20"></div>
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
										</div>

									</div>

								</fieldset>

						<?php
								echo "<div class='push30'></div>";
								break;
						}
						?>

						<div class="push20"></div>

						<div class="row">

							<?php
							if ($cod_cliente != 0) {
							?>

								<div class="col-md-12">
									<a href="javascript:void(0)" class="btn btn-info addBox pull-right" data-url="action.php?mod=<?php echo fnEncode(1396) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?= fnEncode($cod_cliente) ?>&pop=true" data-title="Follow Up Manual - <?= $nom_cliente ?>">Cadastrar novo&nbsp;<span class="fas fa-plus"></span></a>
								</div>


							<?php
							} else {
							?>

								<div class="col-md-12">
									<a href="javascript:void(0)" class="btn btn-info pull-right" disabled>Nenhum Cliente Selecionado&nbsp;<span class="fas fa-ban"></span></a>
								</div>

							<?php
							}
							?>


						</div>

						<div class="push20"></div>

						<style>
							/**
 * Colors:
 *
 * - light-blue: rgb(107, 191, 238)
 * - dark-blue: rgb(52, 148, 203)
 * - grayish blue: #BDD0DC (time)
 */

							@import url("https://use.fontawesome.com/releases/v5.8.1/css/all.css");

							ol.timeline {
								border-left: 2px dashed;
								border-color: rgb(107, 191, 238);
								padding-left: 40px;
								margin-left: 8em;
								list-style: none;
							}

							.timeline>li {
								position: relative;
								margin-top: 10pt;
								color: white;
							}

							.timeline>li:before {
								background-color: #6BBFEE;
								text-align: center;

								width: 35px;
								height: 35px;
								line-height: 18px;

								font-size: 110%;

								border: 0.5em solid rgb(107, 191, 238);
								border-radius: 50%;

								position: absolute;
								left: -3.5em;
							}

							.timeline>li.call:before {
								content: "\260E";
							}

							.timeline>li.flight:before {
								content: '\2708';
							}

							.timeline>li.todo:before {
								content: '\2714';
							}

							.timeline>li.email:before {
								content: '\F0E0';
								/* You should use \ and not /*/
								font-family: "Font Awesome 5 Free";
								/* This is the correct font-family*/
								font-style: normal;
							}

							.timeline>li time {
								display: block;
								float: left;
								position: absolute;
								left: -9em;
								text-align: right;
							}

							.timeline>li time>* {
								display: block;
							}

							.timeline>li time small {
								color: #BDD0DC;
								font-size: 90%;
							}

							.timeline>li time big {
								color: rgb(107, 191, 238);
								font-size: 150%;
							}

							.timeline>li:nth-child(even) time big {
								color: rgb(52, 148, 203);
								/* dark blue */
							}

							.timeline>li article {
								background-color: rgb(107, 191, 238);
								margin: 0;
								padding: 1px 30px 20px 30px;
								border-radius: 5pt;
								box-shadow: 0px 3px 25px 0px rgba(10, 55, 90, 0.2)
							}

							.timeline>li article:after {
								content: "\25C0";
								color: rgb(107, 191, 238);
								position: absolute;
								top: 0.75em;
								left: -0.6em;
							}

							/* http://css-tricks.com/how-nth-child-works/ */
							.timeline>li:nth-child(even) article {
								background-color: rgb(52, 148, 203);
								/* dark blue */
							}

							.timeline>li:nth-child(even) article:after {
								color: rgb(52, 148, 203);
								/* dark blue */
							}

							.timeline>li article h3 {
								font-size: 20px;
								padding-bottom: 5pt;
								border-bottom: 1pt dashed;
								margin-bottom: 10pt;
							}
						</style>

						<div class="col-md-6 col-md-offset-3">
							<ol class="timeline" id="relatorioConteudo">

								<?php

								//setando locale da data
								setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
								date_default_timezone_set('America/Sao_Paulo');

								$sql2 = "SELECT FC.*, CA.DES_CLASSIFICA FROM FOLLOW_CLIENTE FC 
												LEFT JOIN CLASSIFICA_ATENDIMENTO CA ON CA.COD_CLASSIFICA = FC.COD_CLASSIFICA 
												WHERE FC.COD_EMPRESA = $cod_empresa AND FC.COD_CLIENTE = $cod_cliente
												ORDER BY FC.DAT_CADASTR DESC";

								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);
								while ($qrFollow = mysqli_fetch_assoc($arrayQuery)) {

									if ($qrFollow['COD_DESAFIO'] != 0) {
										$titulo = $qrFollow['DES_CLASSIFICA'];
									} else {
										$titulo = $qrFollow['NOM_FOLLOW'];
									}

									$mes = strtoupper(strftime('%B', strtotime($qrFollow['DAT_CADASTR'])));
									$mes = substr("$mes", 0, 3);
								?>


									<li class="email">
										<time><small><?php echo strftime('%d ', strtotime($qrFollow['DAT_CADASTR'])) . "" . $mes; ?></small> <big><?php echo date("H:i", strtotime($qrFollow['DAT_CADASTR'])); ?></big></time>
										<article>
											<h3><?= $titulo ?></h3>

											<p>
												<?= $qrFollow['DES_COMENT'] ?>
											</p>
										</article>
									</li>

								<?php
								}
								?>

							</ol>
						</div>


						<div class="push50"></div>
						<hr>

						<div class="row">

							<div class="col-md-12 text-center">
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-retweet" aria-hidden="true"></i>&nbsp; Carregar mais registros</button>
							</div>

						</div>

						<div class="push50"></div>

						<div class="form-group text-center col-lg-12">



						</div>

						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="REFRESH_FOLLOW" id="REFRESH_FOLLOW" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="push"></div>

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
	$(document).ready(function() {

		$(".calcula").change(function() {
			recalcula();
		});

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//modal close
		$('.modal').on('hidden.bs.modal', function() {
			// alert('fecha');

			if ($('#REFRESH_CLIENTE').val() == "S") {
				var newCli = $('#COD_CLIENTE').val();
				window.location.href = "action.php?mod=<?php echo fnEncode(1253); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=" + newCli + "&tp=B ";
				// $('#REFRESH_PRODUTOS').val("N");				
			}

			if ($('#REFRESH_FOLLOW').val() == "S") {

				$.ajax({
					type: "POST",
					url: "ajxFollowManual.php",
					data: {
						COD_EMPRESA: <?= $cod_empresa ?>,
						COD_CLIENTE: "<?= $cod_cliente ?>"
					},
					beforeSend: function() {
						$("#relatorioConteudo").html('<div class="loading" style="width: 100%;"></div>');
					},
					success: function(data) {
						$("#relatorioConteudo").html(data);
					},
					error: function() {
						$("#relatorioConteudo").html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});

			}

		});

	});

	function recalcula() {

		var valTotal = 0;
		$('.calcula').each(function(index, item) {
			if ($(item).val() != "") {
				if ($(item).attr('id') == "VAL_RESGATE" || $(item).attr('id') == "VAL_DESCONTO") {
					valTotal = valTotal - limpaValor($(item).val());
				} else {
					valTotal = valTotal + limpaValor($(item).val());
				}
			}
		});
		$('#VAL_TOTVENDA').val();
		$('#VAL_TOTVENDA').unmask();
		$('#VAL_TOTVENDA').val(valTotal.toFixed(2));
		$('#VAL_TOTVENDA').mask("#.##0,00", {
			reverse: true
		});

	}

	function abreDetail(idVenda) {
		RefreshProdutos(<?php echo $cod_empresa; ?>, idVenda);
	}

	function RefreshProdutos(idEmp, idVenda) {
		var idItem = $('#abreDetail_' + idVenda);

		if (!idItem.is(':visible')) {
			$.ajax({
				type: "GET",
				url: "ajxProdutosVenda.php",
				data: {
					ajx1: idEmp,
					ajx2: idVenda
				},
				beforeSend: function() {
					$("#mostraDetail_" + idVenda).html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#mostraDetail_" + idVenda).html(data);
				},
				error: function() {
					$("#mostraDetail_" + idVenda).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});

			idItem.show();

			$('#cod_venda_' + idVenda).find($(".fa")).removeClass('fa-plus').addClass('fa-minus');
		} else {
			idItem.hide();
			$('#cod_venda_' + idVenda).find($(".fa")).removeClass('fa-minus').addClass('fa-plus');
		}
	}

	function RefreshProdutosExc(idEmp, idOrc, tipo, idItem) {
		$.ajax({
			type: "GET",
			url: "ajxListaOrcamento.php",
			data: {
				ajx1: idEmp,
				ajx2: idOrc,
				ajx3: tipo,
				ajx4: idItem
			},
			beforeSend: function() {
				$('#div_Produtos').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_Produtos").html(data);
				//recalcula();					
			},
			error: function() {
				$('#div_Produtos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {

	}
</script>