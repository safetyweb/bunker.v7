<?php

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_pedido = fnLimpaCampoZero(fnDecode($_GET['idp']));
$cod_propriedade = fnLimpaCampo($_POST['COD_PROPRIEDADE']);

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
				$abaAdorai = 2023;
				include "abasReservaAdorai.php";
				?>

				<div class="push20"></div>

				<?php

				include_once "headerAdoraiPedido.php";
				?>


				<div class="push20"></div>

				<div class="row justify-content-end">
					<div class="col ">
						<a class='btn btn-info addBox' data-url='action.php?mod=<?= fnEncode(2024) ?>&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_pedido) ?>&idm=<?= fnEncode($cod_mes) ?>&pop=true' data-title='Cadastro de Lançamento'>Adicionar Lançamento</a>
					</div>
				</div>

				<div class="push20"></div>

				<div class="row">

					<table class="table table-bordered table-hover table-sortable tablesorter">
						<thead>
							<th class="{sorter:false}"></th>
							<th>Descrição do Pagamento</th>
							<th>Tipo de Lançamento</th>
							<th class='text-right'>Usuário Cadastro</th>
							<th class='text-right'>Total do Lançamento</th>
							<th class='text-right'>Data de Lançamento</th>
							<th class='text-right'>Data Cadastro</th>
							<th class='text-right'>Observação</th>
						</thead>
						<tbody>
							<?php

							$sql = "SELECT 
									CX.VAL_CREDITO,
									CX.DAT_CADASTR,
									CX.DAT_LANCAME,
									CX.DES_COMENT,
									TC.TIP_OPERACAO,
									TC.DES_TIPO,
									USU.NOM_USUARIO
									FROM CAIXA AS CX
									INNER JOIN adorai_pedido AS AP ON AP.COD_PEDIDO = CX.COD_CONTRAT
									INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = CX.COD_TIPO
									LEFT JOIN usuarios AS USU ON USU.COD_USUARIO = CX.COD_USUCADA
									WHERE CX.COD_CONTRAT = $cod_pedido AND CX.COD_EMPRESA = 274";

							$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

							while ($qrBuscaPagamento = mysqli_fetch_assoc($query)) {

								if ($qrBuscaPagamento['TIP_OPERACAO'] == 'C') {
									$tipo = 'Crédito';
									$valor = 'R$ ' . fnValor($qrBuscaPagamento['VAL_CREDITO'], 2);
									$debito = '';
								} else {
									$tipo = 'Débito';
									$valor = ' - R$' . fnValor($qrBuscaPagamento['VAL_CREDITO'], 2);
									$debito = 'style= color:red;';
								}

								echo "
										<tr>
										<td></td>
										<td>" . $qrBuscaPagamento['DES_TIPO'] . "</td>
										<td>" . $tipo . "</td>
										<td class='text-right'>" . $qrBuscaPagamento['NOM_USUARIO'] . "</td>	
										<td class='text-right' " . $debito . ">" . $valor . "</td>	
										<td class='text-right'>" . fnDataShort($qrBuscaPagamento['DAT_LANCAME']) . "</td>	
										<td class='text-right'>" . fnDataFull($qrBuscaPagamento['DAT_CADASTR']) . "</td>		
										<td class='text-right'>" . $qrBuscaPagamento['DES_COMENT'] . "</td>	
										</tr>
										";
							}

							?>


						</tbody>
					</table>

					<div class="col-md-3 col-lg-3 col-sm-3"></div>
				</div>
			</div>
		</div>
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
	</div>
</div>