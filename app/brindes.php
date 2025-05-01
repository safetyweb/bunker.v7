<?php
include_once 'header.php';
$tituloPagina = "Prêmios";
include_once "navegacao.php";

list($r_cor_backpag, $g_cor_backpag, $b_cor_backpag) = sscanf($cor_backpag, "#%02x%02x%02x");

if ($r_cor_backpag > 50) {
	$r = ($r_cor_backpag - 50);
} else {
	$r = ($r_cor_backpag + 50);
	if ($r_cor_backpag < 30) {
		$r = $r_cor_backpag;
	}
}
if ($g_cor_backpag > 50) {
	$g = ($g_cor_backpag - 50);
} else {
	$g = ($g_cor_backpag + 50);
	if ($g_cor_backpag < 30) {
		$g = $g_cor_backpag;
	}
}
if ($b_cor_backpag > 50) {
	$b = ($b_cor_backpag - 50);
} else {
	$b = ($b_cor_backpag + 50);
	if ($b_cor_backpag < 30) {
		$b = $b_cor_backpag;
	}
}

if ($r_cor_backpag <= 50 && $g_cor_backpag <= 50 && $b_cor_backpag <= 50) {
	$r = ($r_cor_backpag + 40);
	$g = ($g_cor_backpag + 40);
	$b = ($b_cor_backpag + 40);
}


$dat_ini = date("Y-m-d", strtotime("-30 days"));


$sqlCli = "SELECT COD_CLIENTE, NOM_CLIENTE
			FROM CLIENTES 
			WHERE NUM_CGCECPF = $usuario 
			AND COD_EMPRESA = $cod_empresa";

$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

$cod_cliente = $qrCli['COD_CLIENTE'];
$nom_cliente = $qrCli['NOM_CLIENTE'];
$nom_cliente = explode(" ", $nom_cliente);
$nom_cliente = ucfirst(strtolower($nom_cliente[0]));

$linkCode = "123456789";
?>

<style>
	body {
		background-color: <?= $cor_backpag ?>;
	}

	.shadow {
		-webkit-box-shadow: 0px 0px 18px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		-moz-box-shadow: 0px 0px 18px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		box-shadow: 0px 0px 18px -2px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		width: 100%;
		border-radius: 5px;
	}

	.shadow2 {
		-webkit-box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		-moz-box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		box-shadow: 0px 5px 8px 0px rgba(<?= $r ?>, <?= $g ?>, <?= $b ?>, 0.8);
		width: 100%;
		border-radius: 5px;
	}

	.reduzMargem {
		margin-bottom: 10px;
	}

	.dashed-round {
		border-radius: 5px;
		border: 2px dashed <?= $cor_textos ?>;
	}
</style>

<link href="libs/jquery-confirm.min.css" rel="stylesheet"/>
<script src="libs/jquery-confirm.min.js"></script>

<div class="container">

	<div class="push30"></div>
	<div class="push30"></div>

	<div class="row">
		<div class="col-xs-12">
			<h4 style="font-weight: 900!important;">PRÊMIOS PARA RESGATE</h4>
		</div>
		<div class="col-md-12">
			<hr style="margin:0; border-color: #3c3c3c; width: 100%; max-width: 100%;">
		</div>
	</div>

	<div class="push10"></div>

	<?php

	$sqlBrindesDisp = "SELECT BE.*, PC.DES_PRODUTO, PC.COD_PRODUTO, PC.EAN FROM BRINDEEXTRA BE
   						  INNER JOIN PRODUTOCLIENTE PC ON PC.COD_PRODUTO = BE.COD_PRODUTO
						  WHERE BE.COD_EMPRESA = $cod_empresa
						  AND BE.COD_CLIENTE = $cod_cliente
						  and BE.COD_STATUS = 1
						  AND BE.DAT_EXPIRA >= NOW()";

	// echo($sql);

	$arrBrindesDisp = mysqli_query(connTemp($cod_empresa, ''), $sqlBrindesDisp);

	while ($qrBrindesDisp = mysqli_fetch_assoc($arrBrindesDisp)) {

	?>

		<div class="col-xs-12 zeraPadLateral corIcones" style="color: <?= $cor_textos ?>">
			<div class="shadow2">
				<div class="push10"></div>
				<div class="col-xs-8">
					<p><b><?= $qrBrindesDisp['DES_PRODUTO'] ?></b><br><small><small>EAN:</small>&nbsp;<?= $qrBrindesDisp['EAN'] ?></small></p>
					<p><b>Valido até <?= fnDataShort($qrBrindesDisp['DAT_EXPIRA']) ?></b></p>
				</div>
				<div class="col-xs-4 text-right">
					<p><small><?= fnDataShort($qrBrindesDisp['DAT_CADASTR']) ?></small></p>
					<a href="validaDadosBrinde.do?key=<?=$_GET['key']?>&idU=<?=$_GET['idU']?>&idP=<?=fnEncode($qrBrindesDisp['COD_PRODUTO'])?>&t=<?=$rand?>" class="btn btn-xs btn-info"><small>Resgatar</small></a>
				</div>
				<div class="push5"></div>
			</div>
		</div>

	<?php
	}

	?>

	<div class="push30"></div>

	<div class="row">
		<div class="col-xs-12">
			<h4 style="font-weight: 900!important;">PRÊMIOS RESGATADOS</h4>
		</div>
		<div class="col-md-12">
			<hr style="margin:0; border-color: #3c3c3c; width: 100%; max-width: 100%;">
		</div>
	</div>

	<div class="push10"></div>

	<?php

	$sqlBrindesResg = "SELECT BE.*, PC.DES_PRODUTO, PC.COD_PRODUTO, PC.EAN FROM BRINDEEXTRA BE
   						  INNER JOIN PRODUTOCLIENTE PC ON PC.COD_PRODUTO = BE.COD_PRODUTO
						  WHERE BE.COD_EMPRESA = $cod_empresa
						  AND BE.COD_CLIENTE = $cod_cliente
						  and BE.COD_STATUS = 2";

	// echo($sql);

	$arrBrindesResg = mysqli_query(connTemp($cod_empresa, ''), $sqlBrindesResg);

	while ($qrBrindesResg = mysqli_fetch_assoc($arrBrindesResg)) {

	?>

		<div class="col-xs-12 zeraPadLateral corIcones" style="color: <?= $cor_textos ?>">
			<div class="shadow2">
				<div class="push10"></div>
				<div class="col-xs-7">
					<p><b><?= $qrBrindesResg['DES_PRODUTO'] ?></b><br><small><small>EAN:</small>&nbsp;<?= $qrBrindesResg['EAN'] ?></small></p>
				</div>
				<div class="col-xs-5 text-right">
					<p><small><small>Resgatado:</small>&nbsp;<?= fnDataShort($qrBrindesResg['DAT_RESGATE']) ?></small></p>
				</div>
				<div class="push5"></div>
			</div>
		</div>

	<?php
	}

	?>

<div class="push30"></div>

<div class="row">
	<div class="col-xs-12">
		<h4 style="font-weight: 900!important;">PRÊMIOS EXPIRADOS</h4>
	</div>
	<div class="col-md-12">
		<hr style="margin:0; border-color: #3c3c3c; width: 100%; max-width: 100%;">
	</div>
</div>

<div class="push10"></div>

<?php

$sqlBrindesResg = "SELECT BE.*, PC.DES_PRODUTO, PC.COD_PRODUTO, PC.EAN FROM BRINDEEXTRA BE
						 INNER JOIN PRODUTOCLIENTE PC ON PC.COD_PRODUTO = BE.COD_PRODUTO
					  WHERE BE.COD_EMPRESA = $cod_empresa
					  AND BE.COD_CLIENTE = $cod_cliente
					  and BE.COD_STATUS = 2";

// echo($sql);

$arrBrindesResg = mysqli_query(connTemp($cod_empresa, ''), $sqlBrindesResg);

while ($qrBrindesResg = mysqli_fetch_assoc($arrBrindesResg)) {

?>

	<div class="col-xs-12 zeraPadLateral corIcones" style="color: <?= $cor_textos ?>">
		<div class="shadow2">
			<div class="push10"></div>
			<div class="col-xs-7">
				<p><b><?= $qrBrindesResg['DES_PRODUTO'] ?></b><br><small><small>EAN:</small>&nbsp;<?= $qrBrindesResg['EAN'] ?></small></p>
			</div>
			<div class="col-xs-5 text-right">
				<p><small><small>Expirado:</small>&nbsp;<span class="text-danger"><?= fnDataShort($qrBrindesResg['DAT_EXPIRA']) ?></span></small></p>
			</div>
			<div class="push5"></div>
		</div>
	</div>

<?php
}

?>

	<div class="push50"></div>

</div> <!-- /container -->



<script type="text/javascript">

	function geraQRCode() {

		// $.alert({
		// 	title: "Apresente o código ao atendente para regatar seu prêmio:",
		// 	content: "<span id='qrcodeCanvas'></span>",
		// 	columnClass: 'col-xs-12',
		// 	backgroundDismiss: true,
		// 	buttons: {
		// 		"OK": {
		// 			btnClass: 'btn-blue shadow',
		// 			action: function() {}
		// 		}
		// 	}
		// });

		$.confirm({
			content: function(){
				var self = this;
				return $.ajax({
					url: 'ajxqrCodeBrindes.php?id=<?=fnEncode($cod_empresa)?>',
					method: 'post'
				}).done(function (response) {
					self.setContentAppend(response);
				}).fail(function(response){
					self.setContentAppend('<div>Algo deu errado!</div>'+response);
				});
			},
			contentLoaded: function(data, status, xhr){
				// self.setContentAppend('<div>Content loaded!</div>');
			},
			onContentReady: function(){
				// this.setContentAppend('<div>Content ready!</div>');
			},
			icon: 'fa fa-check',
    		title: 'Sucesso!',
			backgroundDismiss: false,
			boxWidth: '90%',
			buttons: {
				"OK": {
					btnClass: 'btn-blue shadow',
					action: function(){}
				}
			}
		});

		

	}
</script>

<?php include 'footer.php' ?>