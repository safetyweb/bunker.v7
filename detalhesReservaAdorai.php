<?php

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_pedido = fnLimpaCampoZero(fnDecode($_GET['idp']));
$cod_propriedade = fnLimpaCampo($_POST['COD_PROPRIEDADE']);

$qtd_hospedes = 2;
?>

<style>
	.table-container td {
		padding: 8px;
	}

	.table-container tbody tr:last-child td {
		border-bottom: 1px solid #dddddd;
	}

	ul.summary-list {
		display: inline-block;
		padding-left: 0;
		width: 100%;
		margin-bottom: 0;
	}

	ul.summary-list>li {
		display: inline-block;
		width: 19.5%;
		text-align: center;
	}

	ul.summary-list>li>a>i {
		display: block;
		font-size: 18px;
		padding-bottom: 5px;
	}

	ul.summary-list>li>a {
		padding: 10px 0;
		display: inline-block;
		color: #818181;
	}

	ul.summary-list>li {
		border-right: 1px solid #eaeaea;
	}

	ul.summary-list>li:last-child {
		border-right: none;
	}
</style>

<div class="row" style="padding: 0 20px 20px 20px;">
	<div class="col-md12 margin-bottom-30">
		<div class="portlet">
			<div class="portlet-body">

				<?php
				$abaAdorai = 2012;
				include "abasReservaAdorai.php";
				?>
				<div class="push20"></div>

				<form action="" method="post">
					<!--<div class="row text-center">-->


					<?php

					include_once "headerAdoraiPedido.php";

					$data_inicio = new DateTime("$qrBusca[DAT_INICIAL]");
					$data_fim = new DateTime("$qrBusca[DAT_FINAL]");

					// Resgata diferença entre as datas
					$dateInterval = $data_inicio->diff($data_fim);
					?>
					<div class="push5"></div>

					<fieldset>
						<legend>Dados da Reserva</legend>
						<div class="push10"></div>
						<div class="row">
							<?php
							//INFORMAÇÕES SÃO INCLUIDAS PELA HEADERADORAIPEDIDO
							$porcent_pago = ($total_pago / ($tot_reserva - $descCupom)) * 100;
							?>

							<div class="col-md-1">
								<div class="form-group">
									<label for="inputName" class="control-label">Cód. Pedido</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PEDIDO" id="COD_PEDIDO" value="<?= $qrBusca['COD_PEDIDO'] ?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Porcentagem paga</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="PCT_PAGO" id="PCT_PAGO" value="<?= fnValor($porcent_pago, 0) ?>%">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Noites</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_NOITES" id="NUM_NOITES" value="<?= $dateInterval->days ?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Data da Reserva</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_CADASTR" id="DAT_CADASTR" value="<?= fnDataShort($qrBusca['DAT_CADASTR']) ?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<?php
							if (!empty($qrBusca['ABV_FORMAPAG'])) {
								$abv_formapag = $qrBusca['ABV_FORMAPAG'];
							} else {
								$abv_formapag = "Não identificado";
							}
							?>
							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Forma de Pagamento</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_NOITES" id="NUM_NOITES" value="<?= $abv_formapag ?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Dat. Pagamento</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NUM_NOITES" id="NUM_NOITES" value="<?= fnDataShort($qrBusca['DAT_STATUSPAG']) ?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>

						</div>

						<div class="row">
							<?php if ($cod_formapag == 1 && $pct != $val_cobrado) { ?>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Desconto Pix</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="QTD_HOSPIDES" id="QTD_HOSPIDES" value="R$ <?= fnValor($val_descPix, 2) ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>
							<?php } ?>
							<?php if ($cod_cupom != "") { ?>
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cupom</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="CUPOM" id="CUPOM" value="<?= $cod_cupom ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Desconto Cupom</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DESC_CUPOM" id="DESC_CUPOM" value="R$ <?= fnValor($descCupom, 2) ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>
							<?php } ?>
							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Hóspedes</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="QTD_HOSPIDES" id="QTD_HOSPIDES" value="<?= $qtd_hospedes ?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="col-md-5">
								<div class="form-group">
									<label for="inputName" class="control-label">Observações</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="QTD_HOSPIDES" id="QTD_HOSPIDES" value="<?= $qrBusca['DES_COMMENT'] ?>">
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>

					</fieldset>

					<div class="push20"></div>

					<fieldset>
						<legend>Comentário ADM</legend>

						<div class="row">
							<div class="col-md-12">

								<a href='#' class='editable'
									data-type='text'
									data-title='Editar Comentário'
									data-pk='<?= $qrBusca['DES_OBSERVA'] ?>'
									data-name='COMMENT'
									data-coditem='<?= $cod_pedido ?>'>
									<?= $qrBusca['DES_OBSERVA'] ?>
								</a>

							</div>

						</div>

					</fieldset>

					<div class="push20"></div>

					<div class="row">
						<div class="col text-left">
							<h4>Hóspedes</h4>
						</div>
					</div>
					<div class="push"></div>


					<table class="table table-bordered table-hover table-sortable tablesorter">
						<thead>
							<tr>
								<th>Nome</th>
								<th>CPF</th>
								<th>Email</th>
								<th>Telefone</th>
								<th>Sexo</th>
								<th>Nascimento</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sqlHospedes = "SELECT * FROM HOSPEDES_ADORAI WHERE COD_PEDIDO =  $cod_pedido AND COD_EMPRESA =  $cod_empresa";
							$queryHospedes = mysqli_query(connTemp($cod_empresa, ''), $sqlHospedes);

							if (mysqli_num_rows($queryHospedes) > 0) {

								while ($hospedesResult = mysqli_fetch_assoc($queryHospedes)) {

									switch ($hospedesResult['DES_SEXOPES']) {
										case '1':
											$sexo = 'Masculino';
											break;
										case '2':
											$sexo = 'Feminino';
											break;
										case '3':
											$sexo = 'Prefiro não informar';
											break;
										default:
											# code...
											break;
									}
									echo "
										<tr>
										<td>" . strtoupper($hospedesResult['NOM_HOSP']) . " " . strtoupper($hospedesResult['SOBRENOM_HOSP']) . "</td>														
										<td>" . $hospedesResult['NUM_CGCECPF'] . "</td>														
										<td>" . $hospedesResult['DES_EMAILUS'] . "</td>							
										<td>" . $hospedesResult['NUM_TELEFONE'] . "</td>
										<td>" . $sexo . "</td>
										<td>" . fnDataShort($hospedesResult['DAT_NASCIME']) . "</td>
										<td width='40' class='text-center'>
										<small>
										<div class='btn-group dropdown dropleft'>
										<a href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
										<span style='opacity: 0.4;' class='fal fa-ellipsis-v fa-2x'></span>
										</a>
										<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu'>
										<li><a href='javascript:void(0)' data-url='action.php?mod=" . fnEncode(2018) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&idhp=" . fnEncode($hospedesResult['COD_HOSPEDE']) . "&pop=true' class='addBox' data-title='Editar hospede'>Editar hospede</a></li>
										<li class='divider'></li>
										<li><a href='https://roteirosadorai.com.br/hospedes.php?id=" . fnEncode($qrBusca['UUID']) . "' target='_blank'>Hospedes info</a></li>
										<li class='divider'></li>
										<li><a href='https://roteirosadorai.com.br/invoice.php?id=" . fnEncode($qrBusca['UUID']) . "' target='_blank'>Invoice</a></li>

										</ul>

										</div>
										</small>
										</td>																
										</tr>
										";
								}
							} else {
								$sqlHospedes = "SELECT * FROM ADORAI_PEDIDO WHERE COD_PEDIDO = $cod_pedido AND COD_EMPRESA = $cod_empresa";
								$queryHospedes = mysqli_query(connTemp($cod_empresa, ''), $sqlHospedes);

								if ($hospedesResult = mysqli_fetch_assoc($queryHospedes)) {
									echo "
										<tr>
											<td>" . strtoupper($hospedesResult['NOME']) . " " . strtoupper($hospedesResult['SOBRENOME']) . "</td>	
											<td>" . fnformatCnpjCpf($hospedesResult['CPF']) . "</td>														
											<td>" . $hospedesResult['EMAIL'] . "</td>							
											<td>" . fnmasktelefone($hospedesResult['TELEFONE']) . "</td>
											<td></td>
											<td></td>
											<td width='40' class='text-center'>
											</td>																
										</tr>
										";
								}
							}

							?>
						</tbody>
					</table>



					<div class="push20"></div>
					<div class="row">
						<div class="col text-left">
							<h4>Detalhes da Reserva</h4>
						</div>
					</div>
					<div class="push10"></div>

					<div class="row">

						<table class="table table-bordered table-hover table-sortable tablesorter">
							<thead>
								<th>Descrição</th>
								<th class='text-center'>Quantidade</th>
								<th class='text-center'>Valor Base.</th>
								<th class='text-right'>Valor Unitário</th>
								<th class='text-right'>Total do Item</th>
								<th class='text-center'>Lançamento</th>
								<th class="{sorter:false}" width="40"></th>
							</thead>
							<tbody>
								<tr>
									<td><?= $chale ?></td>
									<td class='text-center'>1</td>
									<td class='text-center'>
										<a href='#' class='editable'
											data-type='text'
											data-title='Editar Valor'
											data-pk='<?= fnValor($val_referencia_chale, 2) ?>'
											data-name='CHALE'
											data-coditem='<?= $cod_pedido ?>'>
											<?= fnValor($val_referencia_chale, 2) ?>
										</a>
									</td>
									<td class='text-right'><?= "R$ " . fnValor($valor_chale, 2) ?></td>
									<td class='text-right'><?= "R$ " . fnValor($valor_chale, 2) ?></td>
									<td class='text-center'></td>
									<td class='text-center' width='40'></td>
								</tr>
								<?php

								$sqlopc = "SELECT 
									OA.COD_OPCIONAL, 
									OA.VAL_VALOR,
									OA.ABV_OPCIONAL,
									OA.LOG_CORTESIA,
									ACP.VALOR,
									ACP.QTD_OPCIONAL,
									ACP.DES_OBSERVA,
									OA.VAL_EFETIVO,
									ACP.VAL_REFERENCIA_OPCIONAL,
									ACP.COD_ITEM_OPCIONAL,
									ACP.TIPO_LANCAMENTO,
									OA.TIP_CALCULO
									FROM adorai_pedido_opcionais AS ACP
									INNER JOIN opcionais_adorai as OA ON OA.COD_OPCIONAL = ACP.COD_OPCIONAL AND OA.COD_EXCLUSA IS NULL
									INNER JOIN ADORAI_PEDIDO AS AP ON AP.COD_PEDIDO = ACP.COD_PEDIDO
									WHERE AP.COD_EMPRESA = 274 AND ACP.COD_PEDIDO = $cod_pedido
									AND ACP.COD_EXCLUSA IS NULL";
								// fnEscreve($sqlopc);
								$queryopc = mysqli_query(connTemp($cod_empresa, ''), $sqlopc);
								while ($qrBuscaOpcionais = mysqli_fetch_assoc($queryopc)) {

									if ($qrBuscaOpcionais['LOG_CORTESIA'] == "S") {
										$val_opcionalFinal = "<span style='text-decoration: line-through'>" . fnValor($qrBuscaOpcionais['VALOR'], 2) . "</span>";
									} else {
										$val_opcionalFinal = fnValor($qrBuscaOpcionais['VALOR'], 2);
									}

									if ($qrBuscaOpcionais['VAL_REFERENCIA_OPCIONAL'] != "" && $qrBuscaOpcionais['VAL_REFERENCIA_OPCIONAL'] != 0) {
										$val_efetivo = $qrBuscaOpcionais['VAL_REFERENCIA_OPCIONAL'];
									} else {
										$val_efetivo = $qrBuscaOpcionais['VAL_EFETIVO'];
									}

									$formaLancame = "";
									if ($qrBuscaOpcionais['TIPO_LANCAMENTO'] == 2) {
										$formaLancame = 'Manual';
									}

									echo "
										<tr>
										<td>" . $qrBuscaOpcionais['ABV_OPCIONAL'] . "</td>
										<td class='text-center'>" . $qrBuscaOpcionais['QTD_OPCIONAL'] . "</td>
										<td class='text-center'>
									      	<a href='#' class='editable' 
									          	data-type='text' 
									          	data-title='Editar Valor' 
									          	data-pk='$val_efetivo' 
									          	data-name='$qrBuscaOpcionais[COD_OPCIONAL]'
									          	data-coditem='$qrBuscaOpcionais[COD_ITEM_OPCIONAL]'>
									      		" . fnValor($val_efetivo, 2) . "
									      	</a>
									    </td>
										<td class='text-right'>R$ " . fnValor($qrBuscaOpcionais['VAL_VALOR'], 2) . "</td>
										<td class='text-right'>R$ " . $val_opcionalFinal . "</td>
										<td class='text-center'>" . $formaLancame . "</td>
										<td width='40' class='text-right'>
											<div class='btn-group dropdown dropleft'>
											<a href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
											<span style='opacity: 0.4;' class='fal fa-ellipsis-v fa-2x'></span>
											</a>
											<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu'>
											<li><a href='javascript:void(0)' data-url='action.do?mod=" . fnEncode(2099) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($cod_pedido) . "&tpl=" . $qrBuscaOpcionais['TIPO_LANCAMENTO'] . "&pop=true' class='addBox' data-title='Lançamentos Adicionais'>Editar</a></li>
											</ul>
											</div>
										</td>
										</tr>
										";
								}

								?>
							</tbody>

							<tfooter>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td class='text-right'><b>Total da Reserva</b></td>
									<td class='text-right'><b>R$ <?= fnValor($tot_reserva, 2) ?></b></td>
									<td></td>
									<td class="{sorter:false}" width="40"></td>
								</tr>
								<?php
								$sqlopc = "SELECT DISTINCT
									SUM(ACP.VALOR) AS TOT_ADICIONAL
									FROM adorai_pedido_opcionais AS ACP
									INNER JOIN opcionais_adorai as OA ON OA.COD_OPCIONAL = ACP.COD_OPCIONAL AND OA.COD_EXCLUSA IS NULL
									INNER JOIN ADORAI_PEDIDO AS AP ON AP.COD_PEDIDO = ACP.COD_PEDIDO
									WHERE AP.COD_EMPRESA = 274 AND ACP.TIPO_LANCAMENTO = 2 AND ACP.COD_PEDIDO = $cod_pedido
									AND LOG_CORTESIA = 'N' AND ACP.COD_EXCLUSA IS NULL";

								$queryopc = mysqli_query(connTemp($cod_empresa, ''), $sqlopc);
								if ($queryopc) {
									$qrResult = mysqli_fetch_assoc($queryopc);
									$totalLancAdi = $qrResult['TOT_ADICIONAL'];
								} else {
									$totalLancAdi = 0;
								}
								?>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td class='text-right'><b>Lançamentos Adicionais</b></td>
									<!-- <td class='text-right'><a href='javascript:void(0)' style="margin-right: 12px;"><i class="fal fa-plus-circle Primary addBox" data-title="Lançamentos Adicionais" data-url="action.do?mod=<?= fnEncode(2099) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($cod_pedido) ?>&tpl=2&pop=true" style="font-size: 24; color: #0d6efd;"></i></a><b>Lançamentos Adicionais</b></td> -->
									<td class='text-right'><b>R$ <?= fnValor($totalLancAdi, 2) ?></b></td>
									<td></td>
									<td width='40' class='text-right'>
										<div class='btn-group dropdown dropleft'>
											<a href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
												<span style='opacity: 0.4;' class='fal fa-ellipsis-v fa-2x'></span>
											</a>
											<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu'>
												<li><a href='javascript:void(0)' data-url='action.do?mod=<?= fnEncode(2099) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=<?= fnEncode($cod_pedido) ?>&tpl=2&pop=true' class='addBox' data-title='Lançamentos Adicionais'>Editar</a></li>
											</ul>
										</div>
									</td>
								</tr>
								<?php  ?>
								<!-- ADICIONANDO CAMPO DE DESCONTO PIX -->
								<?php if ($cod_formapag == 1 && $divDescCupom == "" && $pct != $val_cobrado) {
								?>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td class='text-right'><b>Desconto Pix</td>
										<td class='text-right'><b>- R$ <?= fnValor($val_descPix, 2) ?></b></td>
										<td></td>
										<td class="{sorter:false}" width="40"></td>
									</tr>
								<?php } ?>
								<?php echo $divDescCupom ?>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td class='text-right'><b>Total Pago</td>
									<td class='text-right'><b>R$ <?= fnValor($total_pago, 2) ?></b></td>
									<td></td>
									<td class="{sorter:false}" width="40"></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td class='text-right'><b>Saldo a Pagar</b></td>
									<td class='text-right'><b>R$ <?= $restaPagar ?></b></td>
									<td></td>
									<td class="{sorter:false}" width="40"></td>
								</tr>
							</tfooter>
						</table>

						<div class="col-md-3 col-lg-3 col-sm-3"></div>


						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>

<div class="modal fade" style="margin-left:14px;" id="popModal" tabindex='-1'>
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body" style="height:70%;">
				<iframe frameborder="0" style="width: 100%; height: 100%;"></iframe>
				<div class="push20"></div>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	$('.datePicker').datetimepicker({
		format: 'DD/MM/YYYY'
	}).on('changeDate', function(e) {
		$(this).datetimepicker('hide');
	});

	$("#DAT_INI_GRP").on("dp.change", function(e) {
		$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
	});

	$("#DAT_FIM_GRP").on("dp.change", function(e) {
		$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
	});


	// ajax
	$("#COD_PROPRIEDADE").ready(function() {
		var codBusca = $("#COD_PROPRIEDADE").val();
		var codBusca3 = $("#COD_EMPRESA").val();
		buscaSubCat(codBusca, codBusca3);
	});

	// $(function(){
	//     $('.editable').each(function() {
	//         $(this).editable({
	//             emptytext: '_______________',
	//             url: function(params) {
	//                 // Simula o que seria enviado via AJAX
	//                 console.log('Simulação de envio:', params);

	//                 // Retorna um "sucesso" sem realmente chamar o servidor
	//                 return new $.Deferred().resolve('Teste: Sucesso!').promise();
	//             },
	//             ajaxOptions: {
	//                 type: 'post'
	//             },
	//             params: function(params) {
	//                 // Adiciona o codempresa e o ID do item aos parâmetros
	//                 params.coditem = $(this).data('coditem');
	//                 params.name = $(this).data('name'); // Adiciona o id_opcional
	//                 return params;
	//             },
	//             success: function(data) {
	//                 // Exibe os dados simulados
	//                 console.log('Dados retornados (simulação):', data);
	//             }
	//         });
	//     });
	// });

	$(function() {
		$('.editable').each(function() {
			$(this).editable({
				emptytext: '_______________',
				url: 'ajxDetalhesReservaAdorai.php',
				ajaxOptions: {
					type: 'POST'
				},
				params: function(params) {

					params.coditem = $(this).data('coditem');
					params.name = $(this).data('name');
					return params;
				},
				success: function(data) {
					console.log(data);
					//alert('Edição salva com sucesso!');
				},
				error: function(xhr, status, error) {
					console.error('Erro na solicitação:', error);
					alert('Houve um erro ao salvar a edição.');
				}
			});
		});
	});



	function buscaSubCat(codprop, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxCheckoutAdorai.do?opcao=SubBusca",
			data: {
				COD_PROPRIEDADE: codprop,
				COD_EMPRESA: idEmp
			},

			beforeSend: function() {
				$('.divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$(".divId_sub").html(data);
			},
			error: function() {
				$('.divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}
</script>