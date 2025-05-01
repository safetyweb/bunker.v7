<?php include "_system/_functionsMain.php";

//echo fnDebug('true');

$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
$dat_ini = fnDataSql($_GET['dat_ini']);
$dat_fim = fnDataSql($_GET['dat_fim']);
$modulo = fnDecode($_GET['mod']);
//fnEscreve($buscaAjx3);

if ($buscaAjx3 == "EXC") {

	$sql = "CALL SP_ALTERA_AUXVENDA (
	'" . $buscaAjx4 . "', 
	'" . $buscaAjx2 . "', 
	'0',
	'0', 
	'0',
	'EXC'    
) ";

	//echo $sql;
	mysqli_query(connTemp($buscaAjx1, ''), trim($sql));
}

$sql = "SELECT TIP_RETORNO, LOG_ATIVCAD FROM empresas where COD_EMPRESA = '" . $buscaAjx1 . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
	$log_ativcadLGPD = $qrBuscaEmpresa['LOG_ATIVCAD'];
	//fnEscreve($log_ativcad);

	if ($tip_retorno == 1) {
		$casasDec = 0;
		$txtTipo = "Pontos";
	} else {
		$casasDec = 2;
		$txtTipo = "Créditos";
	}
}

$sql2 = "SELECT
LOG_CADOK,
LOG_TERMO,
LOG_DESBLOK
FROM CLIENTES 
WHERE COD_CLIENTE = $buscaAjx2
";

$arrayQuery2 = mysqli_query(connTemp($buscaAjx1, ''), $sql2);
$qrBloqueiaCredito = mysqli_fetch_assoc($arrayQuery2);

if (isset($arrayQuery2)) {
	$log_cadokLGPD = $qrBloqueiaCredito['LOG_CADOK'];
	$log_termoLGPD = $qrBloqueiaCredito['LOG_TERMO'];
	$log_desblok = $qrBloqueiaCredito['LOG_DESBLOK'];
}
//fnEscreve($log_cadok);
//fnEscreve($log_termo);

if ($log_ativcadLGPD == "S" && $log_cadokLGPD == "N" && $log_termoLGPD == "N") {
	$bloqueiaDesbloqueio = "S";
} else {
	$bloqueiaDesbloqueio = "N";
}

if ($log_desblok == "S") {
	$checkDesblok = "checked";
} else {
	$checkDesblok = "";
}

if ($dat_ini == "") {
	$andDat = "";
} else {
	$andDat = "	A.dat_cadastr_ws >= '$dat_ini 00:00:00' AND
	A.dat_cadastr_ws <= '$dat_fim 23:59:59' AND";
}


?>

<style>
	.btSmall {
		padding: 3px 4px !important;
		font-size: 12px !important;
		line-height: 1.0 !important;
		border-radius: 3px !important;
	}
</style>

<?php
if ($bloqueiaDesbloqueio == "S") {
	//fnEscreve("bloqueia");
?>
	<div class="push20"></div>
	<div class="alert alert-danger alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
		Termos LGPD <b>desatualizados</b>. <br />
		<?php echo $txtTipo; ?> não podem ser <b>desbloqueados</b>.
	</div>
<?php
}
?>


<table id="prodBloq" class="table" style="width: auto;">
	<?php if ($modulo != 1618) { ?>
		<tr>

			<?php if ($bloqueiaDesbloqueio == "S") { ?>
				<td colspan="2" class="borda"><small><a class="btn btn-success btn-sm" href="#" onClick="alert('Ops! Já não conversamos sobre isso?! :( ');"><i class="fa fa-unlock-alt" aria-hidden="true"></i>&nbsp; Desbloquear <b>todas</b> as vendas</a></small></td>
			<?php } else { ?>
				<td colspan="2" class="borda"><small><a class="btn btn-success btn-sm" href="#" onClick="desbloquearTodasVendas(this, <?php echo $buscaAjx1; ?>, <?php echo $buscaAjx2; ?>, <?php echo $buscaAjx3; ?>);"><i class="fa fa-unlock-alt" aria-hidden="true"></i>&nbsp; Desbloquear <b>todas</b> as vendas</a></small></td>
			<?php } ?>

			<td colspan="2">
				<div class="col-md-12">
					<div class="form-group">
						<label for="inputName" class="control-label">Não bloquear pelo resto do dia</label>
						<div class="push5"></div>
						<label class="switch switch-small">
							<input type="checkbox" name="LOG_DESBLOK" id="LOG_DESBLOK" class="switch" value="S" onClick='toggleBlock(this,"<?= $buscaAjx2 ?>")' <?= $checkDesblok ?>>
							<span></span>
						</label>
					</div>
				</div>
			</td>
			<td colspan="2"></td>
		</tr>
	<?php } ?>


	<tr>
		<th><small>Data</small></th>
		<th><small>ID Venda</small></th>
		<th><small>Tipo</small></th>
		<th><small>Pagamento</small></th>
		<!--<th><small>Motivo</small></th>-->
		<th class="text-left"><small>Vl. Total</small></th>
		<th class="text-left"><small>Vl/Qtd. Resgate</small></th>
		<th class="text-left"><small>Vl. Desconto</small></th>
		<th class="text-left"><small>Vl. Venda</small></th>
		<th class="text-left"><small>Vl. Bloqueado </small></th>
		<th class="text-left"><small>Caixa</small></th>
		<th class="text-left"><small>Venda</small></th>
	</tr>

	<?php

	$sql = "SELECT    f.NOM_CLIENTE,
	b.DES_LANCAMEN, 
	c.DES_OCORREN, 
	d.NOM_UNIVEND, 
	e.DES_FORMAPA,
	a.COD_UNIVEND,
	(SELECT NOM_USUARIO FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO = A.COD_VENDEDOR) AS VENDA,
	(SELECT NOM_USUARIO FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO = A.COD_ATENDENTE) AS CAIXA, 
	(SELECT SUM(VAL_CREDITO) FROM CREDITOSDEBITOS
		WHERE COD_VENDA =a.COD_VENDA AND
		TIP_CREDITO='C') AS VAL_CREDITOS, 
	a.* 
	FROM  vendas a 
	INNER JOIN clientes f ON f.COD_CLIENTE=A.COD_CLIENTE
	LEFT JOIN webtools.tipolancamentomarka b ON        b.cod_lancamen = a.cod_lancamen 
	LEFT JOIN webtools.ocorrenciamarka c ON        c.cod_ocorren = a.cod_ocorren 
	LEFT JOIN unidadevenda d ON        d.cod_univend = a.cod_univend 
	LEFT JOIN formapagamento e ON        e.cod_formapa = a.cod_formapa
	WHERE    a.COD_STATUSCRED=3 AND
	$andDat
	A.COD_CLIENTE = $buscaAjx2
	";

	//fnEscreve($sql);

	$totalDetalhe = 0;

	$arrayQuery = mysqli_query(connTemp($buscaAjx1, ''), $sql);

	//fnEscreve($sql);
	$totalProduto = 0;
	$totalCreditos = 0;
	while ($qrListaDetalheVenda = mysqli_fetch_assoc($arrayQuery)) {

		if ($qrListaDetalheVenda['COD_ATENDENTE'] == 0) {
			$caixa = "";
		} else {
			$caixa = @$ARRAY_VENDEDOR[$NOM_ARRAY_CAIXA]['NOM_USUARIO'];
		}

		$totalProduto = $totalProduto + $qrListaDetalheVenda['VAL_TOTPRODU'];
		$totalCreditos = $totalCreditos + $qrListaDetalheVenda['VAL_CREDITOS'];

	?>
		<tr cod_venda="<?php echo $qrListaDetalheVenda['COD_VENDA']; ?>">
			<td><small><?php echo fnDataFull($qrListaDetalheVenda['DAT_CADASTR']); ?></small></td>
			<td><small><?php echo $qrListaDetalheVenda['COD_VENDAPDV']; ?></small></td>
			<td><small><?php echo $qrListaDetalheVenda['DES_LANCAMEN']; ?></small></td>
			<td><small><?php echo $qrListaDetalheVenda['DES_FORMAPA']; ?></small></td>
			<!--<td><small><?php echo $qrListaDetalheVenda['DES_OCORREN']; ?></small></td>-->
			<td class="text-right"><small>
					<div class="prodBloqLinha"><?php echo fnValor($qrListaDetalheVenda['VAL_TOTPRODU'], 2); ?></div>
				</small></td>
			<td class="text-right"><small><?php echo fnValor($qrListaDetalheVenda['VAL_RESGATE'], 2); ?></small></td>
			<td class="text-right"><small><?php echo fnValor($qrListaDetalheVenda['VAL_DESCONTO'], 2); ?></small></td>
			<td class="text-right"><small><?php echo fnValor($qrListaDetalheVenda['VAL_TOTVENDA'], 2); ?></small></td>
			<td class="text-right"><small><b><?php echo fnValor($qrListaDetalheVenda['VAL_CREDITOS'], $casasDec); ?></b></small></td>
			<td><small><?= $qrListaDetalheVenda['CAIXA'] ?></small></td>
			<td><small><?= $qrListaDetalheVenda['VENDA'] ?></small></td>
			<td class="text-right"><small><a class="btn btn-default btn-sm btSmall addBox" data-url="action.php?mod=<?php echo fnEncode(1293) ?>&cod_empresa=<?php echo $buscaAjx1; ?>&idVenda=<?php echo $qrListaDetalheVenda['COD_VENDA']; ?>&opcao='mostrarDetalhe'&pop=true" data-title="Detalhes da Venda">Detalhes</a></small> </td>
			<?php if ($modulo != 1618) { ?>

				<?php if ($bloqueiaDesbloqueio == "S") { ?>
					<td class="text-right"><small><a class="btn btn-success btn-sm btSmall" onClick="alert('Ops! Já não conversamos sobre isso?! :( ');">Desbloquear venda</a></small> </td>
				<?php } else { ?>
					<td class="text-right"><small><a class="btn btn-success btn-sm btSmall" onClick="desbloquearVenda(this, <?php echo $buscaAjx1; ?>, <?php echo $buscaAjx2; ?>, <?php echo $qrListaDetalheVenda['COD_UNIVEND']; ?>);">Desbloquear venda</a></small> </td>
				<?php } ?>

			<?php } ?>
		</tr>


	<?php
	}
	?>
	<tr>
		<td><small><b>Total</b></small></td>
		<td class="text-right" colspan="4"><small><b>
					<div class="subtotalProdBloq"><?php echo fnValor($totalProduto, 2); ?></div>
				</b></small></td>
		<td class="text-right" colspan="4"><small><b><?php echo fnValor($totalCreditos, $casasDec); ?></b></small></td>
	</tr>

</table>