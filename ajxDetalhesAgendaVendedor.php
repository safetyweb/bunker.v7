<?php

include '_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idC']));

$sqlCli = "SELECT NOM_CLIENTE, NUM_CGCECPF FROM CLIENTES 
				WHERE COD_CLIENTE ='$cod_cliente' 
				AND COD_EMPRESA = $cod_empresa";
$arrayQueryCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);
$qrCli = mysqli_fetch_assoc($arrayQueryCli);

$nome = explode(" ", $qrCli[NOM_CLIENTE]);
$nom_cliente = ucfirst(strtolower($nome[0])) . " " . ucfirst(strtolower(end($nome)));
$cpf = $qrCli[NUM_CGCECPF];

$sql = "CALL total_wallet('$cod_cliente', '$cod_empresa')";

//fnEscreve($sql);

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);


if (isset($arrayQuery)) {

	$total_creditos = $qrBuscaTotais['TOTAL_CREDITOS'];
	$total_debitos = $qrBuscaTotais['TOTAL_DEBITOS'];
	$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
	$credito_aliberar = $qrBuscaTotais['CREDITO_ALIBERAR'];
	$credito_expirados = $qrBuscaTotais['CREDITO_EXPIRADOS'];
	$credito_bloqueado = $qrBuscaTotais['CREDITO_BLOQUEADO'];
} else {

	$total_creditos = 0;
	$total_debitos = 0;
	$credito_disponivel = 0;
	$credito_aliberar = 0;
	$credito_expirados = 0;
	$credito_bloqueado = 0;
}

?>

<div class="col-md-12">

	<div id="close_filtros" class="margin-left-15 margin-top-80">
		<a href="javascript:void(0)" onclick="mostraFiltros('detalhes_prod')" style="padding: 15px 15px 15px 0; color: #2C3E50;">
			<b><span class="far fa-arrow-left fa-2x"></span></b>
		</a>
	</div>

	<div class="col-xs-12 text-center" style="padding: 0;">
		<h2><b><?= $nom_cliente ?></b></h2>
		<p class="f12 text-muted"> Cartão: <b><?= $cpf ?></b></p>
	</div>

	<div class="push10"></div>

	<div class="col-xs-12" style="padding: 0;">
		<div class="portlet portlet-bordered shadow2">
			<div class="row">
				<div class="col-xs-6 text-center">
					<span class="text-success"><b>Crédito Disponível</b></span>
					<div class="push"></div>
					<span class="text-muted f16">R$<?= fnValor($credito_disponivel, 2) ?></span>
				</div>
				<div class="col-xs-6 text-center">
					<span class="text-danger"><b>Resgates Efetuados</b></span>
					<div class="push"></div>
					<span class="text-muted f16">R$<?= fnValor($total_debitos, 2) ?></span>
				</div>
				<!-- <div class="col-xs-4 text-center">
					<span class="text-primary"><b>Total</b></span>
					<div class="push"></div>
					<span class="text-muted f16">R$<?= fnValor($total_creditos, 2) ?></span>
				</div> -->
			</div>
		</div>
	</div>

	<div class="push50"></div>

	<?php

	$sqlVenda = "SELECT
					    Subquery.ordenacao,
					    Subquery.COD_EMPRESA,
					    Subquery.COD_CLIENTE,
					    Subquery.NOM_FANTASI,
					    Subquery.COD_UNIVEND_CAD,
					    Subquery.COD_VENDEDOR,
					    Subquery.COD_VENDA,
					    Subquery.COD_UNIVEND_VEN, 
					    Subquery.DAT_CADASTR_WS,
					    Subquery.VAL_TOTPRODU,
					    Subquery.VAL_RESGATE,
					    Subquery.VAL_DESCONTO,
					    Subquery.VAL_TOTVENDA,
					    NULL AS COD_PRODUTO,
					    NULL AS DES_PRODUTO,
					    NULL AS QTD_PRODUTO,
					    NULL AS VAL_TOTITEM
					FROM (
					    SELECT
					        1 ordenacao,
					        P.COD_EMPRESA,
					        P.COD_CLIENTE,
					        UV.NOM_FANTASI,
					        v.COD_VENDA,
					        P.COD_UNIVEND AS COD_UNIVEND_CAD,
					        v.COD_VENDEDOR,
					        v.COD_UNIVEND AS COD_UNIVEND_VEN, 
					        v.DAT_CADASTR_WS,
					        v.VAL_TOTPRODU,
					        v.VAL_RESGATE,
					        v.VAL_DESCONTO,
					        v.VAL_TOTVENDA
					    FROM CLIENTES P
					    INNER JOIN vendas v ON v.COD_CLIENTE = P.COD_CLIENTE AND v.COD_AVULSO = 2
					    INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = V.COD_UNIVEND
					    WHERE P.COD_EMPRESA = $cod_empresa
					        AND P.COD_CLIENTE = $cod_cliente
					        AND V.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9,10)
					        ORDER BY COD_VENDA DESC 
					        LIMIT 3
					) AS Subquery";

	// fnEscreve($sqlVenda);

	$arrayVenda = mysqli_query(connTemp($cod_empresa, ''), $sqlVenda);

	while ($qrVenda = mysqli_fetch_assoc($arrayVenda)) {

	?>

		<div class="col-xs-10" style="padding: 0; margin-left: 10px;margin-right: -10px;">
			<b><i class="far fa-calendar"></i>&nbsp; <span class="f15" style="font-weight: 800!important;"><b><?= fnDataShort($qrVenda[DAT_CADASTR_WS]) ?></b></span></b>
			<div class="push"></div>
			<b><i class="far fa-map-marker-alt text-success"></i></b>&nbsp; <span class="f15 text-muted"><?= $qrVenda[NOM_FANTASI] ?></span>
		</div>
		<div class="col-xs-2 text-right" style="padding: 0; margin-right: 20px;margin-left: -20px;">
			<span class="f15 text-primary"><b>R$<?= fnValor($qrVenda[VAL_TOTPRODU], 2) ?></b></span>
		</div>
		<div class="push10"></div>

		<?php


		$sqlItem = "SELECT
								    Subquery.ordenacao,
								    Subquery.COD_EMPRESA,
								    Subquery.COD_CLIENTE,
								    Subquery.COD_UNIVEND_CAD,
								    Subquery.COD_VENDEDOR,
								    Subquery.COD_VENDA,
								    Subquery.COD_UNIVEND_VEN, 
								    Subquery.DAT_CADASTR_WS,
								    Subquery.VAL_TOTPRODU,
								    Subquery.VAL_RESGATE,
								    Subquery.VAL_DESCONTO,
								    Subquery.VAL_TOTVENDA,
								    Items.COD_PRODUTO,
								    PROD.DES_PRODUTO,
								    PROD.DES_IMAGEM,
								    PROD.COD_EXTERNO,
								    Items.QTD_PRODUTO,
								    Items.VAL_TOTITEM
								FROM (
								    SELECT
								        2 ordenacao,
								        P.COD_EMPRESA,
								        P.COD_CLIENTE,
								        v.COD_VENDA,
								         NULL COD_UNIVEND_CAD,
								         NULL COD_VENDEDOR,
								         NULL COD_UNIVEND_VEN, 
								         NULL DAT_CADASTR_WS,
								         NULL VAL_TOTPRODU,
								         NULL VAL_RESGATE,
								         NULL VAL_DESCONTO,
								         NULL VAL_TOTVENDA
								    FROM CLIENTES P
								    INNER JOIN vendas v ON v.COD_CLIENTE = P.COD_CLIENTE AND v.COD_AVULSO = 2
								    WHERE P.COD_EMPRESA = $cod_empresa
								   	AND P.COD_CLIENTE = $cod_cliente
								   	AND V.COD_VENDA = $qrVenda[COD_VENDA]
								   	AND V.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9,10)
								) AS Subquery
								JOIN ITEMVENDA Items ON Subquery.COD_VENDA = Items.COD_VENDA
								JOIN produtocliente PROD ON PROD.COD_PRODUTO = Items.COD_PRODUTO
								ORDER BY Items.COD_ITEMVEN DESC LIMIT 3";

		// fnEscreve($sqlItem);

		$arrayItem = mysqli_query(connTemp($cod_empresa, ''), $sqlItem);

		while ($qrItem = mysqli_fetch_assoc($arrayItem)) {

			$nomProd = explode("|", $qrItem["DES_PRODUTO"]);
			$detalhesProd = array_values(array_filter(explode(" ", $nomProd[1])));
			$nomProd = $nomProd[0];
			$refProd = $nomProd[0];

			$cor = $detalhesProd[count($detalhesProd) - 2];

			// print_r($detalhesProd);

			if (count($detalhesProd) == 4) {
				$cor = $detalhesProd[count($detalhesProd) - 3] . " " . $detalhesProd[count($detalhesProd) - 2];
			}

		?>

			<div class="col-xs-6 margin-bottom-20" style="padding: 0;">
				<div class="portlet portlet-bordered shadow2 item div-produto" data-id="<?= $qrItem["COD_PRODUTO"] ?>" data-desc="<?= $qrItem["DES_PRODUTO"] ?>" oncontextmenu="return false;" ontouchstart="return false;">
					<div class="row">
						<div class="col-xs-12">
							<?php
							if ($qrItem["DES_IMAGEM"] != "") {
								//Usa imagem do banco de dados
								$url = "https://img.bunker.mk/media/clientes/$cod_empresa/produtos/" . $qrItem["DES_IMAGEM"] . "?" . date("Ymdhis");
								$class = "";
							} else {
								$url = "";
								$class = "no-image";
							?>
							<?php } ?>
							<div class="loading-img" style="height: 185px;overflow: hidden;width: 100%;position: relative;">
								<div></div>
							</div>
							<img class="panel-img-top img-responsive <?= $class ?>" data-id="<?= $qrItem["COD_PRODUTO"] ?>" data-desc="<?= $qrItem["DES_PRODUTO"] ?>" src="<?= $url ?>" alt="<?= $qrItem["DES_PRODUTO"] ?>" style="height: 185px;width: 100%;object-fit: contain;display: none;">
						</div>
					</div>

					<div class="panel-body">
						<div class="row text-left margin-bottom-0">
							<div class="col-xs-12">
								<span class="f14 text-muted" style="display:block;height: 37px;"><b><?= $nomProd ?></b></span>
								<div class="push5"></div>
								<span class="f12 text-muted">Ref. <b><?= $detalhesProd[0] ?></b></span>
								<div class="push"></div>
								<span class="f12 text-muted">Cód. Externo: <b><?= $qrItem[COD_EXTERNO] ?></b></span>
								<div class="push"></div>
								<span class="f12 text-muted">Tamanho: <b><?= end($detalhesProd) ?></b></span>
								<div class="push"></div>
								<span class="f12 text-muted">Cor: <b><?= $cor ?></b></span>
								<div class="push"></div>
								<span class="f12 text-muted">Qtd: <b><?= fnValor($qrItem[QTD_PRODUTO], 0) ?></b></span>
							</div>
							<div class="col-xs-12">
								<h5><b>R$<?= fnValor($qrItem[VAL_TOTITEM], 2) ?></b></h5>
							</div>
						</div>
					</div>
				</div>

			</div>

		<?php

			$count++;
		}
		?>
		<div class="push20"></div>
	<?php
	}

	?>

</div>

<div class="push100"></div>