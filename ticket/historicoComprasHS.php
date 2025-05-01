<?php

include "../_system/_functionsMain.php";

$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idC']));
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));

//fnEscreve($cod_cliente);
//fnEscreve($cod_empresa);
//fnEscreve("carregando...");

//busca dados do cliente
$sql = "SELECT * FROM CLIENTES where COD_CLIENTE = '" . $cod_cliente . "' AND COD_EMPRESA = $cod_empresa";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {

	$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
	$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
	$log_estatus = $qrBuscaCliente['LOG_ESTATUS'];
} else {

	$nom_cliente = "";
	$cod_cliente = "";
	$num_cartao = "";
	$num_cgcecpf = "";
	$log_estatus = "";
}

$sql = "SELECT EM.TIP_RETORNO, SE.DES_DOMINIO FROM WEBTOOLS.empresas EM 
			LEFT JOIN SITE_EXTRATO SE ON SE.COD_EMPRESA = EM.COD_EMPRESA
			where EM.COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {

	$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
	$des_dominio = $qrBuscaEmpresa['DES_DOMINIO'];

	if ($tip_retorno == 2) {
		$casasDec = 2;
	} else {
		$casasDec = 0;
	}
}

$mostraMsgCad = "none";
$mostraMsgAniv = "none";

if ($cod_cliente != 0) {

	$arrayNome = explode(" ", $qrBuscaCliente['NOM_CLIENTE']);
	$nome = $arrayNome[0];
	$dia_nascime = $qrBuscaCliente['DIA'];
	$mes_nascime = $qrBuscaCliente['MES'];
	$ano_nascime = $qrBuscaCliente['ANO'];
	$dia_hoje = date('d');
	$mes_hoje = date('m');
	$ano_hoje = date('Y');
	$dat_atualiza = $qrBuscaCliente['DAT_ALTERAC'];

	$sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
	LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
	where COMUNICACAO_MODELO.cod_empresa = $cod_empresa 
	AND COD_TIPCOMU = '4' 
	AND COMUNICACAO_MODELO.COD_COMUNICACAO = '98' 
	AND COMUNICACAO_MODELO.LOG_HOTSITE = 'S'
	AND COD_EXCLUSA = 0 
	ORDER BY COD_COMUNIC DESC LIMIT 1
	";
	// echo($sql);
	$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ""), $sql);

	$count = 0;

	$qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery2);

	$today = date("Y-m-d");

	if (mysqli_num_rows($arrayQuery2) > 0) {

		switch ($qrBuscaComunicacao['COD_CTRLENV']) {

			case '6':

				$date = date("Y-m-d", strtotime($today . "-6 months"));

				break;

			default:

				$date = date("Y-m-d", strtotime($today . "-1 year"));

				break;

				if ($dat_atualiza >= $date && $dat_atualiza <= $today) {
					$mostraMsgCad = 'block';
				}
		}
	}

	$today = date("Y-m-d");
	$date = date("Y-m-d", strtotime($today . "+6 months"));

	// echo $today."<br/>";
	// echo $date;


	$sql = "SELECT A.DES_COMUNICACAO, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
	LEFT JOIN  COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
	where COMUNICACAO_MODELO.cod_empresa = $cod_empresa 
	AND COD_TIPCOMU = '4' 
	AND COMUNICACAO_MODELO.COD_COMUNICACAO = '99' 
	AND COMUNICACAO_MODELO.LOG_HOTSITE = 'S'
	AND COD_EXCLUSA = 0 
	ORDER BY COD_COMUNIC DESC LIMIT 1
	";
	// echo($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

	$count = 0;

	$qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery);

	if (mysqli_num_rows($arrayQuery) > 0) {

		$msg = $qrBuscaComunicacao['DES_TEXTO_SMS'];

		$NOM_CLIENTE = explode(" ", ucfirst(strtolower(fnAcentos($qrBuscaCliente['NOM_CLIENTE']))));
		$TEXTOENVIO = str_replace('<#NOME>', $NOM_CLIENTE[0], $msg);
		$TEXTOENVIO = str_replace('<#SALDO>', fnValor($qrBuscaCliente['CREDITO_DISPONIVEL'], $casasDec), $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#NOMELOJA>',  $qrBuscaCliente['NOM_FANTASI'], $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#ANIVERSARIO>', $qrBuscaCliente['DAT_NASCIME'], $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#DATAEXPIRA>', fnDataShort($qrBuscaCliente['DAT_EXPIRA']), $TEXTOENVIO);
		$TEXTOENVIO = str_replace('<#EMAIL>', $qrBuscaCliente['DES_EMAILUS'], $TEXTOENVIO);
		$msgsbtr = nl2br($TEXTOENVIO, true);
		$msgsbtr = str_replace('<br />', ' \n ', $msgsbtr);
		$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);


		switch ($qrBuscaComunicacao['COD_CTRLENV']) {

			case '7':

				if ($dia_hoje == $dia_nascime) {
					$mostraMsgAniv = 'block';
				}

				break;

			case '30':

				if ($mes_hoje == $mes_nascime) {
					$mostraMsgAniv = 'block';
				}

				break;

			default:

				$firstDate = strtotime($ano_hoje . '-' . $mes_nascime . '-' . $dia_nascime);
				$secondDate = strtotime($ano_hoje . '-' . $mes_hoje . '-' . $dia_hoje);

				$result = date('oW', $firstDate) === date('oW', $secondDate) && date('Y', $firstDate) === date('Y', $secondDate);

				if ($result) {
					$mostraMsgAniv = 'block';
				}

				break;
		}
	}
}

//fnMostraForm();
//fnEscreve($cod_cliente);

?>

<link href="css/main.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">

<!-- SISTEMA -->
<script src="js/jquery-1.8.3.min.js"></script>

<link href="css/jquery-confirm.min.css" rel="stylesheet" />
<link href="css/jquery.webui-popover.min.css" rel="stylesheet" />
<link href="css/chosen-bootstrap.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://adm.bunker.mk/css/fa5all.css" />

<!-- complement -->
<link href="css/default.css" rel="stylesheet" />

<style>
	.widget .widget-title {
		font-size: 14px;
	}

	.widget .widget-int {
		font-size: 18px;
		padding: 0 0 10px 0;
	}

	.widget .widget-item-left .fa,
	.widget .widget-item-right .fa,
	.widget .widget-item-left .glyphicon,
	.widget .widget-item-right .glyphicon {
		font-size: 35px;
	}

	.badge {
		display: table;
		border-radius: 30px 30px 30px 30px;
		width: 26px;
		height: 26px;
		text-align: center;
		color: white;
		font-size: 11px;
		margin-right: auto;
		margin-left: auto;
	}

	.txtExp {
		display: table-cell;
		vertical-align: middle;
	}

	@media only screen and (device-width: 320px) and (orientation: portrait) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
	@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}

	/* (320x480) Smartphone, Portrait */
	@media only screen and (device-width: 320px) and (orientation: portrait) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}

	/* (320x480) Smartphone, Landscape */
	@media only screen and (device-width: 480px) and (orientation: landscape) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}

	/* (1024x768) iPad 1 & 2, Landscape */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}

	/* (1280x800) Tablets, Portrait */
	@media only screen and (max-width: 800px) and (orientation : portrait) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}

	/* (768x1024) iPad 1 & 2, Portrait */
	@media only screen and (max-width: 768px) and (orientation : portrait) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}

	/* (2048x1536) iPad 3 and Desktops*/
	@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}

	@media only screen and (min-device-width: 1100px) and (orientation : portrait) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}

	@media (max-height: 824px) and (max-width: 416px) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
	@media (max-device-width: 737px) and (max-height: 416px) {

		h1,
		h2,
		h3,
		h4,
		h5 {
			font-size: 18px;
		}
	}
</style>
<?php
/* $cartaogeradolist="SELECT * from geracupom  WHERE COD_EMPRESA=$cod_empresa AND 
                                                     COD_VENDA > 0 AND 
                                                     COD_CLIENTE > 0 AND                                                     
                                                     cod_cliente=".$cod_cliente." group by NUM_CUPOM";
      
           $rscartaogeradolist= mysqli_query(connTemp($cod_empresa,''), $cartaogeradolist);
           while ($rwlist = mysqli_fetch_assoc($rscartaogeradolist)) {
                @$cupom.=$rwlist['NUM_CUPOM'].',';               
           }
           //fim do acumulo de cupons do clientes
            $cupom=trim ($cupom, ",");  */

?>
<div class="row">

	<div class="col-md-12">

		<?php
		$abasCadastroHotsite = 1;
		include "abasCadastroHotsite.php";
		?>

	</div>

	<div class="col-md12 margin-bottom-30">

		<div class="portlet-body" style="max-width: 1000px; margin: auto;">

			<?php if ($msgRetorno <> '') { ?>
				<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php echo $msgRetorno; ?>
				</div>
			<?php } ?>

			<div class="push20"></div>

			<div class="col-xs-12" style="display: <?= $mostraMsgAniv ?>">

				<div class="col-md-12 alert-warning top30 bottom30" role="alert" id="msgRetorno">
					<div class="push20"></div>
					<span style="font-size: 26px; padding: 0 30px;"><?php echo $msgsbtr; ?></span>
					<div class="push20"></div>
				</div>

			</div>

			<div class="col-xs-12" style="display: <?= $mostraMsgCad ?>">

				<div class="alert-warning top30 bottom30" role="alert" id="msgRetorno">
					<div class="push20"></div>
					<span style="font-size: 26px; padding: 0 30px;"><?php echo $msgsbtr; ?></span>
					<div class="push20"></div>
				</div>

			</div>

			<div class="push30"></div>

			<div class="login-form col-xs-12">

				<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

					<div class="row">

						<div class="col-md-4">
							<label for="inputName" class="control-label">Nome do Usuário</label>
							<div class="push5"></div>
							<h4><?php echo $nom_cliente; ?></h4>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="inputName" class="control-label">Número do Cartão</label>
								<div class="push5"></div>
								<h4><?php echo $num_cartao; ?></h4>
							</div>
						</div>

					</div>

					<div class="push20"></div>

					<?php

					//busca dados do cliente
					// $sql = "SELECT                                                               

					//                                                          (SELECT Sum(val_credito) 
					// 		FROM   creditosdebitos 
					// 		WHERE  cod_cliente = A.cod_cliente
					// 			   AND cod_statuscred <> 6												
					// 			   AND tip_credito = 'C')  AS TOTAL_CREDITOS,

					// 		(SELECT Sum(val_credito) 
					// 		FROM   creditosdebitos 
					// 		WHERE  cod_cliente = A.cod_cliente 
					// 			   AND tip_credito = 'D')  AS TOTAL_DEBITOS,

					// 		(SELECT Sum(val_saldo) 
					// 		FROM   creditosdebitos 
					// 		WHERE  cod_cliente = A.cod_cliente 
					// 			   AND tip_credito = 'C' 
					// 			   AND COD_STATUSCRED = 1 
					// 			   AND ((log_expira='S' and DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d'))or(log_expira='N'))) AS CREDITO_DISPONIVEL, 

					// 		(SELECT Sum(val_credito) 
					// 		FROM   creditosdebitos 
					// 		WHERE  cod_cliente = A.cod_cliente 
					// 			   AND tip_credito = 'C' 
					// 			   AND COD_STATUSCRED = 2 
					// 			   AND dat_expira > Now()) AS CREDITO_ALIBERAR

					// FROM CREDITOSDEBITOS A
					// WHERE COD_CLIENTE=$cod_cliente
					// AND COD_EMPRESA = $cod_empresa
					// GROUP BY COD_CLIENTE
					// ";

					// //fnEscreve($sql);
					// //AND ((log_expira='S' and dat_expira > Now())or(log_expira='N'))) AS CREDITO_DISPONIVEL, 


					// $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
					// $qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);


					// if (isset($arrayQuery)){

					// 	$total_creditos = $qrBuscaTotais['TOTAL_CREDITOS'];
					// 	$total_debitos = $qrBuscaTotais['TOTAL_DEBITOS'];
					// 	$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
					// 	$credito_aliberar = $qrBuscaTotais['CREDITO_ALIBERAR'];

					// }else{

					// 	$total_creditos = 0;
					// 	$total_debitos = 0;
					// 	$credito_disponivel = 0;
					// 	$credito_aliberar = 0;
					// }

					// $sql = "CALL `SP_CONSULTA_SALDO_CLIENTE`('$cod_cliente')";

					// $arrayQuerySaldo = mysqli_query(connTemp($cod_empresa,''),$sql);
					// $qrBuscaTotais = mysqli_fetch_assoc($arrayQuerySaldo);

					// if (isset($arrayQuerySaldo)){

					// 	$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
					// }

					//busca dados do cliente
					$sql = "CALL total_wallet('$cod_cliente', '$cod_empresa')";

					//fnEscreve($sql);

					$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
					$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);


					if (isset($arrayQuery)) {

						$total_creditos = $qrBuscaTotais['TOTAL_CREDITOS'];
						$total_debitos = $qrBuscaTotais['TOTAL_DEBITOS'];
						$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
						$credito_aliberar = $qrBuscaTotais['CREDITO_ALIBERAR'];
						$credito_bloqueado = $qrBuscaTotais['CREDITO_BLOQUEADO'];
						$credito_bloqueadoLGPD = $qrBuscaTotais['CREDITO_BLOQUEADO_LGPD'];
						$credito_expirados = $qrBuscaTotais['CREDITO_EXPIRADOS'];
					} else {

						$total_creditos = 0;
						$total_debitos = 0;
						$credito_disponivel = 0;
						$credito_aliberar = 0;
						$credito_expirados = 0;
						$credito_bloqueado = 0;
					}

					if ($log_estatus == 'N') {
						$credito_disponivel = 0;
					}

					?>


					<div class="row">

						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

							<div class="widget widget-default widget-item-icon">
								<div class="widget-item-left">
									<span class="fal fa-cart-plus fa-2x"></span>
								</div>
								<div class="widget-data">
									<div class="widget-int">
										<div class="push10"></div>
										<?php echo fnValor($total_creditos, $casasDec); ?>
									</div>
									<div class="widget-title">Total Ganho</div>
									<div class="widget-subtitle">
										<div class="push5"></div>
									</div>
								</div>
							</div>

						</div>

						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

							<div class="widget widget-default widget-item-icon">
								<div class="widget-item-left">
									<span class="fal fa-cart-arrow-down fa-2x"></span>
								</div>
								<div class="widget-data">
									<div class="widget-int">
										<div class="push10"></div>
										<?php echo fnValor($total_debitos, $casasDec); ?>
									</div>
									<div class="widget-title">Total Resgatado</div>
									<div class="widget-subtitle">
										<div class="push5"></div>
									</div>
								</div>
							</div>

						</div>

						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

							<div class="widget widget-default widget-item-icon">
								<div class="widget-item-left">
									<span class="fal fa-shopping-bag fa-2x"></span>
								</div>
								<div class="widget-data">
									<div class="widget-int">
										<div class="push10"></div>
										<?php echo fnValor($credito_disponivel, $casasDec); ?>
									</div>
									<div class="widget-title">Saldo Disponível</div>
									<div class="widget-subtitle">
										<div class="push5"></div>
									</div>
								</div>
							</div>

						</div>

						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">

							<div class="widget widget-default widget-item-icon">
								<div class="widget-item-left">
									<span class="fal fa-clock fa-2x"></span>
								</div>
								<div class="widget-data">
									<div class="widget-int">
										<div class="push10"></div>
										<?php echo fnValor($credito_aliberar, $casasDec); ?>
									</div>
									<div class="widget-title">Saldo à Liberar</div>
									<div class="widget-subtitle">
										<div class="push5"></div>
									</div>
								</div>
							</div>

						</div>

					</div>

					<div class="push20"></div>

					<div class="row">

						<div class="col-md-12" id="div_Produtos">

							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<!--<th></th>-->
										<th>Data</th>
										<th>Crédito</th>
										<th>Resgate</th>
										<th>Expira</th>
										<th>Cód. Venda</th>
										<th>Origem</th>
										<th>Loja</th>
										<th>Nros. da Sorte</th>
									</tr>
								</thead>
								<tbody>

									<?php

									$sql = "SELECT * FROM (
											SELECT 
											A.COD_CREDITO, 
											A.COD_CAMPAPROD,
											A.COD_ITEMVEN, 
											A.COD_CLIENTE,
											A.COD_VENDA,
											A.TIP_CREDITO, 
											A.DAT_REPROCE, 
											A.DAT_LIBERA,
											A.LOG_EXPIRA,
											A.DAT_EXPIRA,
											A.TIP_PONTUACAO,
											A.VAL_PONTUACAO,
											A.VAL_CREDITO,
											A.VAL_SALDO,											
											A.COD_STATUSCRED,
											H.DES_STATUSCRED,
											A.COD_CAMPANHA,
											A.TIP_CAMPANHA,
											A.COD_PERSONA, 
											A.DES_OPERACA ,
											B.ABV_TPCAMPA,
											C.ABR_CAMPANHA,
											D.DES_PERSONA,
											E.DES_ABREVIA,
											G.NOM_FANTASI,
											F.COD_VENDAPDV,

											(SELECT DES_PRODUTO FROM PRODUTOPROMOCAO
												WHERE  
												COD_PRODUTO=A.COD_PRODUTO AND
												COD_EMPRESA= $cod_empresa AND
												COD_CREDITO=A.COD_CREDITO ) AS DES_PRODUTO,

											(SELECT NOM_FANTASI FROM WEBTOOLS.UNIDADEVENDA 
													WHERE COD_UNIVEND = A.COD_UNIVEND) AS UNIVEND_RESGATE,
                                                                                                        
 											COD_FANTASI AS COD_FANTASIA,
                                                                                                        
											(SELECT COUNT(*) from geracupom  WHERE COD_EMPRESA=$cod_empresa AND 
											COD_VENDA = F.COD_VENDA AND                                                                                                                                      
											cod_cliente=" . $cod_cliente . " group by cod_cliente) as TEM_CUPOM             

											FROM CREDITOSDEBITOS A
											LEFT JOIN WEBTOOLS.TIPOCAMPANHA B ON A.TIP_CAMPANHA=B.COD_TPCAMPA 
											LEFT JOIN CAMPANHA C ON C.COD_CAMPANHA=A.COD_CAMPANHA
											LEFT JOIN PERSONA  D  ON  D.COD_PERSONA=A.COD_PERSONA
											LEFT JOIN STATUSMARKA E ON E.COD_STATUS=A.COD_STATUS
											LEFT JOIN VENDAS F ON F.COD_VENDA=A.COD_VENDA
											LEFT JOIN WEBTOOLS.UNIDADEVENDA G ON G.COD_UNIVEND=A.COD_UNIVEND
											LEFT JOIN STATUSCREDITO H ON H.COD_STATUSCRED=A.COD_STATUSCRED

											WHERE A.COD_CLIENTE = $cod_cliente
											AND A.COD_STATUSCRED <> 6
											AND A.COD_STATUS <> 15  
											AND A.COD_EMPRESA = $cod_empresa 											
											
											UNION 
											SELECT 
											A.COD_CREDITO, 
											A.COD_CAMPAPROD,
											A.COD_ITEMVEN, 
											A.COD_CLIENTE,
											A.COD_VENDA,
											A.TIP_CREDITO, 
											A.DAT_REPROCE, 
											A.DAT_LIBERA,
											A.LOG_EXPIRA,
											A.DAT_EXPIRA,
											A.TIP_PONTUACAO,
											A.VAL_PONTUACAO,
											A.VAL_CREDITO,
											A.VAL_SALDO,											
											A.COD_STATUSCRED,
											H.DES_STATUSCRED,
											A.COD_CAMPANHA,
											A.TIP_CAMPANHA,
											A.COD_PERSONA, 
											A.DES_OPERACA ,
											B.ABV_TPCAMPA,
											C.ABR_CAMPANHA,
											D.DES_PERSONA,
											E.DES_ABREVIA,
											G.NOM_FANTASI,
											F.COD_VENDAPDV,

											(SELECT DES_PRODUTO FROM PRODUTOPROMOCAO
												WHERE  
												COD_PRODUTO=A.COD_PRODUTO AND
												COD_EMPRESA= $cod_empresa AND
												COD_CREDITO=A.COD_CREDITO ) AS DES_PRODUTO,

											(SELECT NOM_FANTASI FROM WEBTOOLS.UNIDADEVENDA 
													WHERE COD_UNIVEND = A.COD_UNIVEND) AS UNIVEND_RESGATE,
                                                                                                        
 											COD_FANTASI AS COD_FANTASIA,
                                                                                                        
											(SELECT COUNT(*) from geracupom  WHERE COD_EMPRESA=$cod_empresa AND 
											COD_VENDA = F.COD_VENDA AND                                                                                                                                      
											cod_cliente=" . $cod_cliente . " group by cod_cliente) as TEM_CUPOM             

											FROM CREDITOSDEBITOS_BKP A
											LEFT JOIN WEBTOOLS.TIPOCAMPANHA B ON A.TIP_CAMPANHA=B.COD_TPCAMPA 
											LEFT JOIN CAMPANHA C ON C.COD_CAMPANHA=A.COD_CAMPANHA
											LEFT JOIN PERSONA  D  ON  D.COD_PERSONA=A.COD_PERSONA
											LEFT JOIN STATUSMARKA E ON E.COD_STATUS=A.COD_STATUS
											LEFT JOIN VENDAS F ON F.COD_VENDA=A.COD_VENDA
											LEFT JOIN WEBTOOLS.UNIDADEVENDA G ON G.COD_UNIVEND=A.COD_UNIVEND
											LEFT JOIN STATUSCREDITO H ON H.COD_STATUSCRED=A.COD_STATUSCRED

											WHERE A.COD_CLIENTE = $cod_cliente
											AND A.COD_STATUSCRED <> 6
											AND A.COD_STATUS <> 15  
											AND A.COD_EMPRESA = $cod_empresa
											  )TMPCRED

  											ORDER BY TMPCRED.DAT_REPROCE DESC					
											";

									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$count = 0;
									$valorTTotal = 0;
									$valorTRegaste = 0;
									$valorTDesconto = 0;
									$valorTvenda = 0;

									while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
										$count++;
										if ($qrBuscaProdutos['TIP_CREDITO'] == "D") {
											$textRed = "text-danger";
											$valorCred = 0;
											$valorDeb = $qrBuscaProdutos['VAL_CREDITO'];
											$tag_campanha = "";
											$tag_persona =  "";
											$diff_dias =  "";
											$mostra_expira = 0;
											$cor = "background:red; color:white;";
											$opcaoExpandir =  "";
											$unidade = $qrBuscaProdutos['UNIVEND_RESGATE'];
										} else {

											$diff_dias = fnDateDif($qrBuscaProdutos['atual'], $qrBuscaProdutos['DAT_EXPIRA']);

											$unidade = $qrBuscaProdutos['NOM_FANTASI'];

											if ($diff_dias > 0) {
												$mostra_expira = $diff_dias;
												$cor = "background:#18bc9c;";
											} else {
												$mostra_expira = 0;
												$cor = "background:red; color:white;";
											}

											$textRed = "";
											$valorCred = $qrBuscaProdutos['VAL_CREDITO'];
											$valorDeb = 0;
											if ($qrBuscaProdutos['COD_VENDA'] != 0) {
												$tag_campanha = "<li class='tag'><span class='label label-info'>● &nbsp; " . $qrBuscaProdutos['ABR_CAMPANHA'] . "</span></li>";
												$tag_persona =  "<li class='tag'><span class='label label-warning'>● &nbsp; " . $qrBuscaProdutos['DES_PERSONA'] . "</span></li>";
												$opcaoExpandir =  "<a href='javascript:void(0);' onclick='abreDetail(" . $qrBuscaProdutos['COD_CREDITO'] . ")'><i class='fa fa-plus' aria-hidden='true'></i></a>";
											} else {
												$tag_campanha = "";
												$tag_persona =  "";
												$opcaoExpandir =  "";
											}

											//$diff_dias = fnDateDif($qrBuscaProdutos['DAT_CADASTR'],$qrBuscaProdutos['DAT_EXPIRA'])." dia(s)";	
											//<!--<td>".$diff_dias."</td>-->
										}
										//empresas com cupom	

										if ($qrBuscaProdutos['TEM_CUPOM'] > 0) {

											$codVenda = $qrBuscaProdutos['COD_VENDA'];

											$sql2 = "SELECT NUM_CUPOM from geracupom WHERE COD_EMPRESA= $cod_empresa AND 
														   COD_VENDA = $codVenda AND cod_cliente= $cod_cliente ";
											$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);

											$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];
											$meusCupons = "";
											while ($qrBuscaCupom = mysqli_fetch_assoc($arrayQuery2)) {

												$meusCupons .= $qrBuscaCupom['NUM_CUPOM'] . "<br/>";
											}

											//$cupomdepyl=" <td><small>".str_pad($qrBuscaProdutos['COD_FANTASIA'], 3, '0', STR_PAD_LEFT).".".str_pad($qrBuscaProdutos['TEM_CUPOM'], 5, '0', STR_PAD_LEFT)."</small></td>"  ;   	

											$cupomdepyl = " <td><small>" . $meusCupons . "</small></td>";
										} else {
											$cupomdepyl = " <td><small></small></td>";
										}

										echo "
											<tr id=" . "cod_credito_" . $qrBuscaProdutos['COD_CREDITO'] . ">															
											  <!--<td class='text-center'>" . $opcaoExpandir . "</td>-->
											  <td><small>" . fnDataFull($qrBuscaProdutos['DAT_REPROCE']) . "</small></td>
											  <td class= text-right '" . $textRed . " " . $textRed . "'><small>" . fnValor($valorCred, $casasDec) . "</small></td>
											  <td class='text-right " . $textRed . " '><small>" . fnValor($valorDeb, $casasDec) . "</small></td>
											  <td><small>" . fnDataFull($qrBuscaProdutos['DAT_EXPIRA']) . "</small></td>												
											  <td><small>" . $qrBuscaProdutos['COD_VENDA'] . "</small></td>												
											  <td><small>" . $qrBuscaProdutos['DES_ABREVIA'] . "<br><span class='" . $textRed . "'><b>" . $qrBuscaProdutos['DES_PRODUTO'] . "</b></span></small></td>												
											  <td><small>" . $unidade . "</small></td>
                                              $cupomdepyl";
										echo "											
											</tr>";
										echo "
											<tr style='display:none; background-color: #fff;' id='abreDetail_" . $qrBuscaProdutos['COD_CREDITO'] . "' idvenda='" . $qrBuscaProdutos['COD_VENDA'] . "'>
												<td></td>
												<td colspan='11'>
												<div id='mostraDetail_" . $qrBuscaProdutos['COD_CREDITO'] . "'>
												</div>
												</td>
											</tr>														  
											";
									}

									?>

							</table>

						</div>

					</div>

				</form>
			</div>
		</div>
	</div>
	<!-- fim Portlet -->
</div>

</div>
<?php if ($cod_empresa != 7) { ?>
	<script type="text/javascript">
		parent.$("#conteudoAba").css("height", ($(document).height() + 50) + "px");

		$('.addBox').click(function() {
			var src = $(this).attr("data-url"),
				title = $(this).attr("data-title");

			parent.setIframe(src, title);
			parent.$('#popModal').modal('show');
			parent.$('#popModal').find('.modal-content').css({
				'height': '900px',
				'marginLeft': 'auto',
				'marginRight': 'auto'
			});
			parent.$('#popModal').find('iframe').css({
				'height': '850px'
			});
		});
	</script>
<?php } else { ?>
	<script>
		$('.addBox').click(function() {
			var src = $(this).attr("data-url"),
				title = $(this).attr("data-title");

			parent.setIframe(src, title);
			parent.$('#popModal').modal('show');
		});
	</script>
<?php } ?>