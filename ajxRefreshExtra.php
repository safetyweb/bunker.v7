<?php include "_system/_functionsMain.php";

//echo fnDebug('true');

$cod_empresa = fnLimpacampo($_GET['ajx1']);
$cod_campanha = fnLimpacampo($_GET['ajx2']);
$tip_faixas = fnLimpacampo($_GET['ajx3']);

//fnEscreve($cod_empresa);
//fnEscreve($cod_campanha);
//fnEscreve($tip_faixas);

if($tip_faixas == "DIAS"){
	$sqlDias = "SELECT COUNT(*) AS DIAS_SEMANA FROM DIAS_SEMANA_CAMPANHA 
	WHERE COD_EMPRESA = $cod_empresa 
	AND COD_CAMPANHA = $cod_campanha
	AND COD_EXCLUSA = 0";
	$arrayDias = mysqli_query(connTemp($cod_empresa,''), $sqlDias);
	$qrDias = mysqli_fetch_assoc($arrayDias);
	$dias = $qrDias['DIAS_SEMANA'];

}

if ($tip_faixas == "IND") {
	$sql2 = "select count(*) as VALORFAIXA from INDICA_CLIENTE_CAMPANHA where COD_EMPRESA = '" . $cod_empresa . "' and  COD_CAMPANHA = '" . $cod_campanha . "'  ";
} else {

	//busca quantidade total de itens

	if ($tip_faixas != "CAT") {
		//todas execeto por categoria de cliente	
		$sql2 = "select count(*) as VALORFAIXA from VANTAGEMEXTRAFAIXA where COD_CAMPANHA = '" . $cod_campanha . "' AND TIP_FAIXAS = '" . $tip_faixas . "' ";
	} else {
		//por categoria de cliente
		$sql2 = "select count(*) as VALORFAIXA from CATEGORIA_CLIENTE_CAMPANHA where COD_EMPRESA = '" . $cod_empresa . "' and  COD_CAMPANHA = '" . $cod_campanha . "'  ";
	}
	//fnEscreve($sql2);
}

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);
$qrBuscaTotalExtra = mysqli_fetch_assoc($arrayQuery);
$valorFaixa = $qrBuscaTotalExtra['VALORFAIXA'];


if ($tip_faixas == "VAL") {
	if ($valorFaixa == 0) {
		$txtBntExtra1 = "Cadastrar";
		$icoBntExtra1 = "fa-plus";
	} else {
		$txtBntExtra1 = "Editar";
		$icoBntExtra1 = "fa-pencil";
	}
?>

	<div class="widget widget-default widget-item-icon">
		<div class="widget-item-left">
			<span class="fal fa-chart-bar"></span>
		</div>
		<div class="widget-data">
			<div class="widget-title">Faixa de Valores</div>
			<div class="widget-int" id=""><?= number_format($valorFaixa, 0, ",", "."); ?></div>
			<div class="widget-title" style="font-weight: 400; font-size: 14px;">Faixas de valores cadastrados</div>
			<div class="widget-subtitle">
				<div class="push20"></div>
				<div class="push5"></div>
				<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1059) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Faixa de Valores"><i class="fa <?php echo $icoBntExtra1; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra1; ?></a>
				<div class="push5"></div>
			</div>
		</div>
	</div>
	<script>
		try {
			parent.$('#QTD_TOTFAIXA').val(<?= number_format($valorFaixa, 0, ",", "."); ?>);
		} catch (err) {}
	</script>

<?php
}

if ($tip_faixas == "ITM") {
	if ($valorFaixa == 0) {
		$txtBntExtra2 = "Cadastrar";
		$icoBntExtra2 = "fa-plus";
	} else {
		$txtBntExtra2 = "Editar";
		$icoBntExtra2 = "fa-pencil";
	}

?>

	<div class="widget widget-default widget-item-icon">
		<div class="widget-item-left">
			<span class="fal fa-cubes"></span>
		</div>
		<div class="widget-data">
			<div class="widget-title">Quantidade de Itens</div>
			<div class="widget-int" id=""><?= number_format($valorFaixa, 0, ",", "."); ?></div>
			<div class="widget-title" style="font-weight: 400; font-size: 14px;">Faixas de itens cadastrados</div>
			<div class="widget-subtitle">
				<div class="push20"></div>
				<div class="push5"></div>
				<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1060) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Faixa de Valores"><i class="fa <?php echo $icoBntExtra2; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra2; ?></a>
				<div class="push5"></div>
			</div>
		</div>
	</div>
	<script>
		try {
			parent.$('#QTD_TOTITENS').val(<?= number_format($valorFaixa, 0, ",", "."); ?>);
		} catch (err) {}
	</script>
<?php
}

if ($tip_faixas == "PRD") {
	if ($valorFaixa == 0) {
		$txtBntExtra3 = "Cadastrar";
		$icoBntExtra3 = "fa-plus";
	} else {
		$txtBntExtra3 = "Editar";
		$icoBntExtra3 = "fa-pencil";
	}

?>

	<div class="widget widget-default widget-item-icon">
		<div class="widget-item-left">
			<span class="fal fa-bullseye"></span>
		</div>
		<div class="widget-data">
			<div class="widget-title">Produtos Específicos</div>
			<div class="widget-int" id=""><?= number_format($valorFaixa, 0, ",", "."); ?></div>
			<div class="widget-title" style="font-weight: 400; font-size: 14px;">Produtos cadastrados</div>
			<div class="widget-subtitle">
				<div class="push20"></div>
				<div class="push5"></div>
				<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1063) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Produtos Específicos"><i class="fa <?php echo $icoBntExtra3; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra3; ?></a>
				<div class="push5"></div>
			</div>
		</div>
	</div>
	</div>
	<script>
		try {
			parent.$('#QTD_TOTPRODU').val(<?= number_format($valorFaixa, 0, ",", "."); ?>);
		} catch (err) {}
	</script>

<?php
}

if ($tip_faixas == "PAG") {
	if ($valorFaixa == 0) {
		$txtBntExtra4 = "Cadastrar";
		$icoBntExtra4 = "fa-plus";
	} else {
		$txtBntExtra4 = "Editar";
		$icoBntExtra4 = "fa-pencil";
	}

?>

	<div class="widget widget-default widget-item-icon">
		<div class="widget-item-left">
			<span class="fal fa-credit-card"></span>
		</div>
		<div class="widget-data">
			<div class="widget-title">Formas de Pagamento</div>
			<div class="widget-int" id=""><?= number_format($valorFaixa, 0, ",", "."); ?></div>
			<div class="widget-title" style="font-weight: 400; font-size: 14px;">Tipos de Pagamentos</div>
			<div class="widget-subtitle">
				<div class="push20"></div>
				<div class="push5"></div>
				<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1094) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Formas de Pagamento"><i class="fa <?php echo $icoBntExtra4; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra4; ?></a>
				<div class="push5"></div>
			</div>
		</div>
	</div>
	</div>
	<script>
		try {
			parent.$('#QTD_TOTFPAGA').val(<?= number_format($valorFaixa, 0, ",", "."); ?>);
		} catch (err) {}
	</script>
<?php
}

if ($tip_faixas == "IND") {
	if ($valorFaixa == 0) {
		$txtBntExtra5 = "Cadastrar";
		$icoBntExtra5 = "fa-plus";
	} else {
		$txtBntExtra5 = "Editar";
		$icoBntExtra5 = "fa-pencil";
	}
?>

	<div class="widget widget-default widget-item-icon">
		<div class="widget-item-left">
			<span class="fal fa-handshake"></span>
		</div>
		<div class="widget-data">
			<div class="widget-title">Indicação de Clientes</div>
			<div class="widget-int"><?= number_format($valorFaixa, 0, ",", "."); ?></div>
			<div class="widget-title" style="font-weight: 400; font-size: 14px;">Indicação de clientes </div>
			<div class="widget-subtitle">
				<div class="push20"></div>
				<div class="push5"></div>
				<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(2075) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Indicação de Cliente"><i class="fa <?php echo $icoBntExtra5; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra5; ?></a>
				<div class="push5"></div>
			</div>
		</div>
	</div>
	<script>
		try {
			parent.$('#QTD_TOTINDICA').val(<?= number_format($valorFaixa, 0, ",", "."); ?>);
		} catch (err) {}
	</script>

<?php
}

if ($tip_faixas == "CAT") {
	if ($valorFaixa == 0) {
		$txtBntExtra6 = "Cadastrar";
		$icoBntExtra6 = "fa-plus";
	} else {
		$txtBntExtra6 = "Editar";
		$icoBntExtra6 = "fa-pencil";
	}

?>

	<div class="widget widget-default widget-item-icon">
		<div class="widget-item-left">
			<span class="fal fa-user-tag"></span>
		</div>
		<div class="widget-data">
			<div class="widget-title">Categoria de Clientes</div>
			<div class="widget-int" id=""><?= number_format($valorFaixa, 0, ",", "."); ?></div>
			<div class="widget-title" style="font-weight: 400; font-size: 14px;">Categoria de Clientes</div>
			<div class="widget-subtitle">
				<div class="push20"></div>
				<div class="push5"></div>
				<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1277) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagens Extras - Categoria de Cliente"><i class="fa <?php echo $icoBntExtra6; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra6; ?></a>
				<div class="push5"></div>
			</div>
		</div>
	</div>
	</div>
	<script>
		try {
			parent.$('#QTD_CATEGOR').val(<?= number_format($valorFaixa, 0, ",", "."); ?>);
		} catch (err) {}
	</script>
<?php
}
if ($tip_faixas == "DIAS") {
	if ($dias == 0) {
		$txtBntExtra6 = "Cadastrar";
		$icoBntExtra6 = "fa-plus";
	} else {
		$txtBntExtra6 = "Editar";
		$icoBntExtra6 = "fa-pencil";
	}
?>
	<div class="widget widget-default widget-item-icon">
		<div class="widget-item-left">
			<span class="fal fa-calendar-alt"></span>
		</div>
		<div class="widget-data">
			<div class="widget-title">Dia da Semana</div>
			<div class="widget-int"><?=number_format($dias, 0, ",", "."); ?></div>
			<div class="widget-title" style="font-weight: 400; font-size: 14px;">Dia(s) da Semana </div>
			<div class="widget-subtitle">
				<div class="push20"></div>
				<div class="push5"></div>
				<a class="btn btn-primary btn-sm addBox" style="padding: 0 4px 0 4px; float: right; margin-right: 15px;" data-url="action.php?mod=<?php echo fnEncode(1821) ?>&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&pop=true" data-title="Vantagem Extra-Dias da Semana"><i class="fa <?php echo $icoBntExtra6; ?>" aria-hidden="true"></i>&nbsp; <?php echo $txtBntExtra6; ?></a>
				<div class="push5"></div>
			</div>
		</div>
	</div>
	<script>
		try {
			parent.$('#QTD_TOTDIAS').val(<?= number_format($dias, 0, ",", "."); ?>);
		} catch (err) {}
	</script>
<?php
}
