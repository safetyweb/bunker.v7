<?php

/*******
 * SELECT DO ADORAI PEDIDOS
 * ********/
$sql = "SELECT 
		AP.*,
		AP.VALOR AS TOT_PEDIDO,
		API.*,
		UNV.NOM_FANTASI,
		AC.NOM_QUARTO,
		AC.COD_HOTEL,
		AC.COD_EXTERNO AS COD_CHALE,
		AC.VAL_EFETIVO,
		AST.ABV_STATUSPAG,
		AF.DES_FORMAPAG,
		AF.ABV_FORMAPAG,
		AP.VALOR_PEDIDO,
		AF.COD_FORMAPAG,
		UNV.COD_EXTERNO,
		AP.DAT_CADASTR,
		CP.TIP_DESCONTO,
		CP.VAL_DESCONTO,
		AP.VAL_REFERENCIA_CHALE,
		AP.COD_CUPOM,
		AP.VALOR_COBRADO,
		(
			SELECT SUM(C.val_credito)
			FROM caixa AS c 
			INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
			INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO
			WHERE p.COD_EMPRESA = 274 
			AND p.COD_PEDIDO = $cod_pedido
			AND c.cod_contrat = AP.COD_PEDIDO
			AND TC.TIP_OPERACAO = 'C'
			) AS tot_val_credito,
		(
			SELECT SUM(C.val_credito) 
			FROM caixa AS c 
			INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
			INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO 
			WHERE p.COD_EMPRESA = 274 
			AND p.COD_PEDIDO = $cod_pedido
			AND c.cod_contrat = AP.COD_PEDIDO
			AND TC.TIP_OPERACAO = 'D'
			) AS TOT_LANC_ADIC
		FROM adorai_pedido AS AP
		INNER JOIN adorai_pedido_items AS API ON API.COD_PEDIDO = AP.COD_PEDIDO
		INNER JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = API.COD_PROPRIEDADE
		INNER JOIN adorai_chales AS AC ON AC.COD_EXTERNO = API.COD_CHALE
		INNER JOIN adorai_statuspag AS AST ON AST.COD_STATUSPAG = AP.COD_STATUSPAG
		INNER JOIN adorai_formapag AS AF ON AF.COD_FORMAPAG = AP.COD_FORMAPAG
		LEFT JOIN CUPOM_ADORAI AS CP ON CP.DES_CHAVECUPOM = AP.COD_CUPOM
		WHERE AP.COD_EMPRESA = $cod_empresa AND AP.COD_PEDIDO  = $cod_pedido
		GROUP BY AP.COD_PEDIDO";
//fnEscreve($sql);

$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
if ($qrBusca = mysqli_fetch_assoc($query)) {

	$chale = $qrBusca['NOM_QUARTO'];
	$valor_chale = $qrBusca['VALOR_PEDIDO'];
	$tot_lanc_adic = $qrBusca['TOT_LANC_ADIC'];
	$cod_cupom = $qrBusca['COD_CUPOM'];
	$cod_formapag = $qrBusca['COD_FORMAPAG'];
	$total_pago = $qrBusca['tot_val_credito'];
	$cod_hotel = $qrBusca['COD_HOTEL'];
	$val_cobrado = $qrBusca['VALOR_COBRADO'];
} else {
	$chale = 0;
	$valor_chale = 0;
	$tot_lanc_adic = 0;
	$cod_cupom = 0;
	$cod_formapag = 0;
	$total_pago = 0;
}

if ($qrBusca['VAL_REFERENCIA_CHALE'] != "" && $qrBusca['VAL_REFERENCIA_CHALE'] != 0) {
	$val_referencia_chale = $qrBusca['VAL_REFERENCIA_CHALE'];
} else {
	$val_referencia_chale = $qrBusca['VAL_EFETIVO'];
}
// fnEscreve($sql);
/**********
 * SELECT DA LISTA DE OPCIONAIS
 * **********/

$sqlopc = "SELECT 
		OA.COD_OPCIONAL, 
		OA.VAL_VALOR,
		OA.ABV_OPCIONAL,
		OA.LOG_CORTESIA,
		ACP.VALOR,
		ACP.QTD_OPCIONAL,
		ACP.DES_OBSERVA,
		AP.ID_RESERVA,
		OA.TIP_CALCULO
		FROM adorai_pedido_opcionais AS ACP
		INNER JOIN opcionais_adorai as OA ON OA.COD_OPCIONAL = ACP.COD_OPCIONAL AND OA.COD_EXCLUSA IS NULL
		INNER JOIN ADORAI_PEDIDO AS AP ON AP.COD_PEDIDO = ACP.COD_PEDIDO
		WHERE AP.COD_EMPRESA = 274 AND ACP.COD_PEDIDO = $cod_pedido
		AND ACP.COD_EXCLUSA IS NULL";

$queryopc = mysqli_query(connTemp($cod_empresa, ''), $sqlopc);

while ($qrBuscaOpcionais = mysqli_fetch_assoc($queryopc)) {


	if ($qrBuscaOpcionais['LOG_CORTESIA'] != "S") {
		$valor_total_op += $qrBuscaOpcionais['VALOR'];
	}
}

$tot_reserva = $valor_chale + $valor_total_op - $tot_lanc_adic;

//LOGICA PARA TIPO DE CUPOM
if ($cod_cupom != "") {
	$descCupom = $qrBusca['VAL_CUPOM'];

	$divDescCupom = "<tr>
		<td></td>
		<td></td>
		<td></td>
		<td class='text-right'><b>Desconto Cupom</b></td>
		<td class='text-right'><b>- R$ " . fnValor($descCupom, 2) . "</b></td>
		<td class='{sorter:false}' width='40'></td>
		</tr>";
} else {
	$descCupom = 0;
	$divDescCupom = "";
}

$val_descPix = 0;
if ($qrBusca['PIX_50'] != "S") {
	$val_descPix = $qrBusca['DESCONTO_PIX'];
} else {
	$val_descPix = 0;
}

$restaPagar = fnValor(($tot_reserva - $total_pago - $descCupom - $val_descPix), 2);

?>
<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<div class="panel-body">
				<ul class="summary-list">
					<li>
						<a href="javascript:;">
							<i class="fal fa-tags text-info"></i>
							<?= $qrBusca['ID_RESERVA'] ?><br><span class="f12"><b>Id Reserva Foco</b></span>
						</a>
					</li>
					<li>
						<a href="javascript:;">
							<i class="fal fa-map-marked-alt text-info"></i>
							<?= $qrBusca['NOM_FANTASI'] ?><br><span class="f12"><b>Hospedagem</b></span>
						</a>
					</li>
					<li>
						<a href="javascript:;">
							<i class="fal fa-house text-info"></i>
							<?= $qrBusca['NOM_QUARTO'] ?><br><span class="f12"><b>Acomodação</b></span>
						</a>
					</li>
					<li>
						<a href="javascript:;">
							<i class="fal fa-calendar-minus text-info"></i>
							<span class="text-success"><?= fnDataShort($qrBusca['DAT_INICIAL']) ?></span></br><span class="f12"><b>Check-in: </b></span>
						</a>
					</li>
					<li>
						<a href="javascript:;">
							<i class="fal fa-calendar-star text-info"></i>
							<span class="text-danger"><?= fnDataShort($qrBusca['DAT_FINAL']) ?></span></br><span class="f12"><b>Check-out: </b></span>
						</a>
					</li>
				</ul>
				<hr style="margin:10px;">
				<ul class="summary-list">
					<li>
						<a href="javascript:;">
							<i class="fal fa-money-check-edit-alt text-info"></i>
							<?= $qrBusca['DES_FORMAPAG'] ?><br><span class="f12"><b>Forma de Pagamento</b></span>
						</a>
					</li>
					<li>
						<a href="javascript:;">
							<i class="fal fa-search-dollar text-info"></i>
							<?= $qrBusca['ABV_STATUSPAG'] ?><br><span class="f12"><b>Status</b></span>
						</a>
					</li>
					<li>
						<a href="javascript:;">
							<i class="fal fa-sack-dollar text-info"></i>
							<?= "R$ " . fnValor($tot_reserva - $val_descPix - $descCupom, 2) ?><br><span class="f12"><b>Total da Reserva</b></span>
						</a>
					</li>
					<li>
						<a href="javascript:;">
							<i class="fal fa-hands-usd text-info"></i>
							<span class="text-success"><?= "R$ " . fnValor($total_pago, 2) ?></span><br><span class="f12"><b>Total Pago</b></span>
						</a>
					</li>
					<li>
						<a href="javascript:;">
							<i class="fal fa-hand-holding-usd text-info"></i>
							<span class="text-danger"><?= "R$ " . $restaPagar ?></span><br><span class="f12"><b>Saldo a pagar</b></span>
						</a>
					</li>
				</ul>
			</div>
		</section>
	</div>
</div>