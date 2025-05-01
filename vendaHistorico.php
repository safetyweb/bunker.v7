<?php
$hashLocal = "";
$itens_carregar_mais = "";
$msgRetorno = "";
$msgTipo = "";
$cod_venda = "";
$cod_orcamento = "";
$cod_cliente = "";
$cod_lancamen = "";
$cod_ocorren = "";
$cod_formapa = "";
$tem_prodaux = "";
$val_totprodu = "";
$val_resgate = "";
$val_desconto = "";
$val_totvenda = "";
$cod_vendapdv = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$sql1 = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente_av = "";
$qrBuscaCliente = "";
$nom_cliente = "";
$num_cartao = "";
$num_cgcecpf = "";
$mod = "";
$formBack = "";
$sql4 = "";
$qrBuscaBloqueio = "";
$tem_bloqueio = "";
$abaEmpresa = "";
$abaCli = "";
$valorTTotal = "";
$valorTResgate = "";
$valorResgate = "";
$valorTDesconto = "";
$valorTvenda = "";
$classeExc = "";
$qrBuscaProdutos = "";
$tootlip = "";
$tooltip = "";
$dat_expira = "";
$classeExc2 = "";
$mostraItemExcluido = "";
$colunaEspecial = "";
$tokem = "";
$tokemexec = "";
$rwtokem = "";
$colunaEspecialPlaca = "";
$colunaPlaca = "";
$content = "";


$hashLocal = mt_rand();

$itens_carregar_mais = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_venda = fnLimpacampoZero(@$_REQUEST['COD_VENDA']);
		$cod_orcamento = fnLimpacampoZero(@$_REQUEST['COD_ORCAMENTO']);
		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_cliente = fnLimpacampoZero(@$_REQUEST['COD_CLIENTE']);
		$cod_lancamen = fnLimpacampoZero(@$_REQUEST['COD_LANCAMEN']);
		$cod_ocorren = fnLimpacampoZero(@$_REQUEST['COD_OCORREN']);
		$cod_univend = fnLimpacampoZero(@$_REQUEST['COD_UNIVEND']);
		$cod_formapa = fnLimpacampoZero(@$_REQUEST['COD_FORMAPA']);
		$tem_prodaux = fnLimpacampoZero(@$_REQUEST['TEM_PRODAUX']);

		$val_totprodu = fnLimpacampo(@$_REQUEST['VAL_TOTPRODU']);
		$val_resgate = fnLimpacampo(@$_REQUEST['VAL_RESGATE']);
		$val_desconto = fnLimpacampo(@$_REQUEST['VAL_DESCONTO']);
		$val_totvenda = fnLimpacampo(@$_REQUEST['VAL_TOTVENDA']);
		$cod_vendapdv = fnLimpacampo(@$_REQUEST['COD_VENDAPDV']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			$sql1 = "CALL SP_INSERE_VENDAS1(
							'" . $cod_venda . "',
							'" . $cod_orcamento . "',
							'" . $cod_empresa . "',
							'" . $cod_cliente . "',
							'" . $cod_lancamen . "',
							'" . $cod_ocorren . "',
							'" . $cod_univend . "',
							'" . $cod_formapa . "',
							'" . fnValorSql($val_totprodu) . "',
							'" . $tem_prodaux . "',
							'" . fnValorSql($val_resgate) . "',
							'" . fnValorSql($val_desconto) . "',
							'" . fnValorSql($val_totvenda) . "',
							'" . $cod_vendapdv . "',
							'" . $cod_usucada . "'   
						) ";

			//echo $sql1;	

			mysqli_query(connTemp(fnDecode(@$_GET['key']), ''), $sql1);

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

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_cliente = fnDecode(@$_GET['idC']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_cliente_av = $qrBuscaEmpresa['COD_CLIENTE_AV'];
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

$mod = fnLimpacampo(fnDecode(@$_GET['mod']));

//fnMostraForm();
//fnEscreve($cod_cliente_av);

?>

<style>
	.alert .alert-link {
		text-decoration: none;
	}

	.alert:hover .alert-link:hover {
		text-decoration: underline;
	}

	.modal-content {
		width: 70vw;
		margin-left: auto;
		margin-right: auto;
		height: auto;
	}

	.modal-dialog {
		max-width: 70vw !important;
		width: auto !important;
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
				$formBack = "1015";
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
				//verifica se tem bloqueio
				$sql4 = "SELECT COUNT(*) as TEM_BLOQUEIO
											FROM CLIENTES A, VENDAS B
											LEFT JOIN unidadevenda d ON d.cod_univend = b.cod_univend 
											WHERE A.COD_CLIENTE=B.COD_CLIENTE AND 
											B.COD_STATUSCRED=3 AND 
                                                                                        B.cod_avulso!=1 AND
											A.COD_EMPRESA = $cod_empresa and
											A.COD_CLIENTE = $cod_cliente ";
				$qrBuscaBloqueio = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql4));
				//fnEscreve($sql4);

				$tem_bloqueio = $qrBuscaBloqueio['TEM_BLOQUEIO'];

				if ($tem_bloqueio > 0) { ?>

					<div class="alert alert-warning alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						Cliente possui vendas bloqueadas. <br />
						<a href="action.do?mod=<?php echo fnEncode(1099); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank" class="alert-link">&rsaquo; Acessar tela de desbloqueio</a>
					</div>
				<?php } ?>

				<?php
				//menu superior - cliente
				$abaEmpresa = 1020;
				$abaCli = fnDecode(@$_GET['mod']);
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 14: //rede duque
						include "abasClienteDuque.php";
						break;
					case 13: //sh manager
						include "abasIntegradoraCli.php";
						break;
					case 18: //mais cash
						include "abasMaisCashCli.php";
						break;
					default;
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
									<label for="inputName" class="control-label required">Nome do Usuário</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
										</span>
										<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leituraOff" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente; ?>">
										<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>" required>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Número do Cartão</label>
										<input type="text" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>


						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover  ">
									<thead>
										<tr>
											<th></th>
											<th></th>
											<th>Data</th>
											<th>ID</th>
											<th>ID Venda</th>
											<th>Cupom</th>
											<th>Tipo</th>
											<?php if ($cod_empresa != 19) { ?>
												<th>Motivo</th>
											<?php } else { ?>
												<th>Token</th>
												<th>Placa</th>
											<?php } ?>
											<th>Loja</th>
											<th>Vl. Total</th>
											<th>Vl. Resgate</th>
											<th>Vl. Desconto</th>
											<th>Vl. Venda</th>
											<th>Pagamento</th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										$sql = "CALL LISTA_COMPRA('$cod_cliente', '$cod_empresa', '$itens_carregar_mais', '15')";

										//fnEscreve(fnDecode("7T3jekr0Xfk¢"));
										// fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$count = 0;
										$valorTTotal = 0;
										$valorTResgate = 0;
										$valorResgate = 0;
										$valorTDesconto = 0;
										$valorTvenda = 0;
										$classeExc = "";

										while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {

											$count++;
											$tootlip = "";
											if (@$qrBuscaProdutos['EXCLUIDO'] != 1) {
												$valorTTotal = $valorTTotal + $qrBuscaProdutos['VAL_TOTPRODU'];
												$valorTResgate = $valorTResgate + $qrBuscaProdutos['VAL_RESGATE'];
												$valorResgate = $qrBuscaProdutos['VAL_RESGATE'];
												$valorTDesconto = $valorTDesconto + $qrBuscaProdutos['VAL_DESCONTO'];
												$valorTvenda = $valorTvenda + $qrBuscaProdutos['VAL_TOTVENDA'];
												$classeExc = "";
											} else {
												$classeExc = "text-danger";
											}

											$count++;
											if ($qrBuscaProdutos['TOTAL'] == 'S') {
												$tooltip = 'data-toggle="tooltip" data-placement="top" data-html="true" data-original-title="Extornado em ' . fnDataFull($qrBuscaProdutos['DAT_EXCLUSAO']) . '"';
												$dat_expira = "";
											}
											if ($qrBuscaProdutos['EXCLUIDO'] == 0) {
												$classeExc2 = "";
												$mostraItemExcluido = "";
											} else {
												$classeExc2 = "text-danger";
												$mostraItemExcluido = "<i class='fal fa-minus-circle' aria-hidden='true' $tooltip></i>";
											}


											if ($cod_empresa != 19) {
												if ($qrBuscaProdutos['EXCLUIDO'] != 1) {
													$colunaEspecial = $qrBuscaProdutos['DES_OCORREN'];
													if ($qrBuscaProdutos['COD_CREDITOU'] == 4) {
														$colunaEspecial = "Pontuação Inativa";
													}
												} else {
													$colunaEspecial = "venda estornada";
													if ($qrBuscaProdutos['COD_CREDITOU'] == 4) {
														$colunaEspecial = "Pontuação Inativa";
													}
												}
											} else {
												$tokem = "select itemvenda.COD_VENDA,itemvenda.DES_PARAM1,
																			  itemvenda.DES_PARAM2,vendas.COD_VENDAPDV 
																			  from itemvenda 
																		inner join vendas on itemvenda.COD_VENDA= vendas.COD_VENDA
																		where vendas.COD_VENDA='" . $qrBuscaProdutos['COD_VENDA'] . "'";
												$tokemexec = mysqli_query(connTemp($cod_empresa, ''), $tokem);
												$rwtokem = mysqli_fetch_assoc($tokemexec);
												$colunaEspecial = $rwtokem['DES_PARAM2'];
												$colunaEspecialPlaca = $rwtokem['DES_PARAM1'];
												if ($colunaEspecial == '') {
													$colunaEspecial = '<i class="fal fa-times text-danger fa-2x" aria-hidden="true" ' . $tooltip . '></i>';
												}
											}
											if ($cod_empresa == 19) {
												$colunaPlaca = "<td class='" . $classeExc . "'  class='text-center'><small>" . $colunaEspecialPlaca . "</small></td>";
											}


											echo "
															<tr id=" . "cod_venda_" . $qrBuscaProdutos['COD_VENDA'] . ">															
															  <td class='text-center " . $classeExc . "'><a href='javascript:void(0);' onclick='abreDetail(" . $qrBuscaProdutos['COD_VENDA'] . ",\"" . $qrBuscaProdutos['NOM_VENDEDOR'] . "\",\"" . $qrBuscaProdutos['NOM_ATENDENTE'] . "\")'><i class='expande fa fa-plus' aria-hidden='true'></i></a></td>
															  <td class='text-center " . $classeExc2 . "'>" . $mostraItemExcluido . "</td>
															  <td class='" . $classeExc . "'><small>" . fnFormatDateTime($qrBuscaProdutos['DAT_CADASTR_WS']) . "</small></td>
															  <td class='" . $classeExc . "' >" . $qrBuscaProdutos['COD_VENDA'] . "</td>												
															  <td class='" . $classeExc . "' >" . $qrBuscaProdutos['COD_VENDAPDV'] . "</td>												
															  <td class='" . $classeExc . "' >" . $qrBuscaProdutos['COD_CUPOM'] . "</td>												
															  <td class='" . $classeExc . "' >" . $qrBuscaProdutos['DES_LANCAMEN'] . "</td>												
															  <td class='" . $classeExc . "'  class='text-center'><small>" . $colunaEspecial . "</small></td>
															  $colunaPlaca											
															  <td class='" . $classeExc . "' >" . $qrBuscaProdutos['NOM_FANTASI'] . "</td>												
															  <td class='" . $classeExc . " text-right'><b>" . fnValor($qrBuscaProdutos['VAL_TOTPRODU'], 2) . "</b></td>
															  <td class='" . $classeExc . " text-right'>" . fnValor($valorResgate, 2) . "</td>
															  <td class='" . $classeExc . " text-right'>" . fnValor($qrBuscaProdutos['VAL_DESCONTO'], 2) . "</td>
															  <td class='" . $classeExc . " text-right'>" . fnValor($qrBuscaProdutos['VAL_TOTVENDA'], 2) . "</td>
															  <td class='" . $classeExc . "' >" . fnAcentos($qrBuscaProdutos['DES_FORMAPA']) . "</td>												
															</tr>
															
														  <tr style='display:none; background-color: #fff;' id='abreDetail_" . $qrBuscaProdutos['COD_VENDA'] . "'>
															<td></td>
															<td colspan='13'>
															<div id='mostraDetail_" . $qrBuscaProdutos['COD_VENDA'] . "'>

															
															</div>
															</td>
														  </tr>
														  
															";
										}

										?>

									</tbody>
									<tfoot>
										<tr>
											<th></th>
											<th></th>
											<th colspan="7">Total</th>
											<th id="VL_TOTAL_TFOOT" class="text-right"><?php echo fnValor($valorTTotal, 2); ?></th>
											<th id="VL_REGASTE_TFOOT" class="text-right"><?php echo fnValor($valorTResgate, 2); ?></th>
											<th id="VL_DESCONTO_TFOOT" class="text-right"><?php echo fnValor($valorTDesconto, 2); ?></th>
											<th id="VL_VENDA_TFOOT" class="text-right"><?php echo fnValor($valorTvenda, 2); ?></th>
											<th colspan="2"></th>
										</tr>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"><i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
											</th>
										</tr>
									</tfoot>
								</table>
								<div id="carregarMaisAjax"></div>
								<input type="hidden" name="TEM_PRODAUX" id="TEM_PRODAUX" value="<?php echo $tem_prodaux; ?>">
							</div>
						</div>

						<div class="row">
							<div class="col-md-12 text-center">
								<button type="button" class="btn btn-info btn-hg carregarMais">Carregar mais</button>
							</div>
						</div>

						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="VL_TOTAL" id="VL_TOTAL" value="<?= $valorTTotal ?>">
						<input type="hidden" name="VL_RESGATE" id="VL_RESGATE" value="<?= $valorTResgate ?>">
						<input type="hidden" name="VL_DESCONTO" id="VL_DESCONTO" value="<?= $valorTDesconto ?>">
						<input type="hidden" name="VL_VENDA" id="VL_VENDA" value="<?= $valorTvenda ?>">
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
<style>
	.modal-dialog {
		width: 360px;
	}
</style>
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

		var itens_carregar_mais = <?php echo $itens_carregar_mais; ?>;

		// $(".calcula").change(function(){				
		// 	recalcula();
		// });

		// //chosen
		// $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		// $('#formulario').validator();

		//modal close
		$('.modal').on('hidden.bs.modal', function() {

			if ($('#REFRESH_CLIENTE').val() == "S") {
				var newCli = $('#NOVO_CLIENTE').val();
				window.location.href = "action.php?mod=<?php echo fnEncode(1072); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=" + newCli + " ";
				$('#REFRESH_PRODUTOS').val("N");
			}

		});

		$(".carregarMais").click(function() {
			itens_carregar_mais += 15;
			$.ajax({
				type: "POST",
				url: "ajxVendaHistorico.do?opcao=carregarMais&id=<?php echo fnEncode($cod_empresa); ?>&itens_carregar_mais=" + itens_carregar_mais,
				data: $('#formulario').serialize(),
				beforeSend: function() {
					$('#carregarMaisAjax').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					//console.log(data);	
					$(data).hide().appendTo("#relatorioConteudo").fadeIn(1000);
					$('[id^="abreDetail_"]').hide();
					$('#carregarMaisAjax').html('');
					setTimeout(function() {
						$('html, body').animate({
							scrollTop: $("#carregarMaisAjax").offset().top
						}, 1000);
					}, 500);
				},
				error: function() {
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});
		});
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
								icon: 'fa fa-check-square-o',
								content: function() {
									var self = this;
									return $.ajax({
										url: "ajxVendaHistorico.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&itens_carregar_mais=" + itens_carregar_mais,
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
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

	// function recalcula(){

	// 	var valTotal = 0;
	// 	$('.calcula').each(function(index,item){
	// 		if($(item).val() != ""){
	// 			if($(item).attr('id') == "VAL_RESGATE" || $(item).attr('id') == "VAL_DESCONTO" ){
	// 				valTotal = valTotal - limpaValor($(item).val());
	// 			}else{
	// 				valTotal = valTotal + limpaValor($(item).val());
	// 			}				
	// 		}
	// 	 });
	// 	$('#VAL_TOTVENDA').val();				 
	// 	$('#VAL_TOTVENDA').unmask();
	// 	$('#VAL_TOTVENDA').val(valTotal.toFixed(2));				 
	// 	$('#VAL_TOTVENDA').mask("#.##0,00", {reverse: true});

	// }	

	function abreDetail(idVenda, nom_vendedor, nom_atendente) {
		RefreshProdutos(<?php echo $cod_empresa; ?>, idVenda, nom_vendedor, nom_atendente);
	}

	function RefreshProdutos(idEmp, pIdVenda, nom_vendedor, nom_atendente) {
		var idItem = $('#abreDetail_' + pIdVenda);

		if (!idItem.is(':visible')) {
			$.ajax({
				type: "GET",
				url: "ajxProdutosVenda.do?mod=<?= fnEncode($mod) ?>",
				data: {
					cod_empresa: idEmp,
					idVenda: pIdVenda,
					opcao: 'mostrarDetalhe',
					NOM_VENDEDOR: nom_vendedor,
					NOM_ATENDENTE: nom_atendente
				},
				beforeSend: function() {
					$("#mostraDetail_" + pIdVenda).html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#mostraDetail_" + pIdVenda).html(data);
				},
				error: function() {
					$("#mostraDetail_" + pIdVenda).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});

			idItem.show();

			$('#cod_venda_' + pIdVenda).find($(".expande.fa")).removeClass('fa-plus').addClass('fa-minus');
		} else {
			idItem.hide();
			$('#cod_venda_' + pIdVenda).find($(".expande.fa")).removeClass('fa-minus').addClass('fa-plus');
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
				console.log(response);
				//recalcula();					
			},
			error: function() {
				$('#div_Produtos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function excluirItens(pCod_itemext, pCodPdv, pCodProduto, qtde, pIdVenda) {
		$.confirm({
			icon: 'fal fa-trash-alt',
			title: 'Estorno de itens',
			content: '' +
				'<div class="row">                                                                                                                  ' +
				'<div class="col-md-6">                                                                                                         ' +
				'<label class="control-label">Quantidade dos itens</label>   						                                        ' +
				'<input type="text" placeholder="Seu texto" class="form-control text-center input-sm" value="' + qtde + '" readonly />          ' +
				'</div>                                                                                                                         ' +
				'<div class="col-md-6">                                                                                                         ' +
				'<label class="control-label">Quantidade à estonar</label>   						                                        ' +
				'<input type="text" placeholder="Digite aqui" class="texto form-control text-center input-sm" value="" />                   ' +
				'</div>                                                                                                                         ' +
				'</div>                                                                                                                             ' +
				'',

			buttons: {
				cancelar: function() {
					//close
				},
				formSubmit: {
					text: 'Excluir',
					btnClass: 'btn-red',
					action: function() {
						var texto = this.$content.find('.texto').val();
						if (!texto) {
							$.alert('Por favor, digite a quantidade!');
							return false;
						}

						var pQtdeDigitada = this.$content.find('.texto').val();
						$.ajax({
							type: "GET",
							url: "ajxProdutosVenda.php",
							data: {
								opcao: 'excluirItem',
								idVenda: pIdVenda,
								cod_cliente: <?php echo $cod_cliente; ?>,
								cod_itemext: pCod_itemext,
								cod_pdv: pCodPdv,
								qtdeDigitada: pQtdeDigitada,
								codProduto: pCodProduto,
								cod_empresa: <?php echo $cod_empresa; ?>
							},
							beforeSend: function() {
								$('#mostraDetail_' + pIdVenda).html('<div class="loading" style="width: 100%; "></div>');
							},
							success: function(response) {
								$("#mostraDetail_" + pIdVenda).html(response);
								console.log(response);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError);
							}
						});
					}
				}
			}
		})
	}


	function excluirVenda(pCodPdv, pIdVenda) {
		$.confirm({
			title: 'Atenção!',
			animation: 'opacity',
			closeAnimation: 'opacity',
			content: 'Deseja realmente estornar essa venda?',
			buttons: {
				confirmar: function() {
					$.ajax({
						type: "GET",
						url: "ajxProdutosVenda.php",
						data: {
							cod_pdv: pCodPdv,
							idVenda: pIdVenda,
							cod_empresa: <?php echo $cod_empresa; ?>,
							opcao: 'excluirVenda'
						},
						beforeSend: function() {
							$('#mostraDetail_' + pIdVenda).html('<div class="loading" style="width: 100%; "></div>');
						},
						success: function(response) {
							$("#mostraDetail_" + pIdVenda).html(response);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//On error, we alert user
							alert(thrownError);
						}
					});
				},
				cancelar: function() {

				},
			}
		});
	}
</script>