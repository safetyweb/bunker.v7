<?php 
	include "../_system/_functionsMain.php";

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$tipo = fnLimpaCampo($_GET['tp']);

	if($cod_empresa == "" || $cod_empresa == 0){
		exit();
		$cod_empresa = 219;
	}
	
	$connboard = $Cdashboard->connUser();
	$adm = $connAdm->connAdm();
	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	$dat_ini = date('Y-m');
	// $dat_ini = date('Y-m', strtotime('-1 month'));

	// echo "$dat_ini";

	$dat_fim = date('Y-m', strtotime('-1 month', strtotime($dat_ini)));
	$dat_inicial = date('Y-m-01', strtotime('-1 month', strtotime($dat_ini)));
	$dat_final = date('Y-m-t', strtotime('-1 month', strtotime($dat_ini)));

	$mes = date('M', strtotime($dat_fim));

	$arrayImagens = array(
		'Jan' => array('info1.jpg','Janeiro'),
		'Feb' => array('info2.jpg','Fevereiro'),
		'Mar' => array('info3.jpg','Março'),
		'Apr' => array('info4.jpg','Abril'),
		'May' => array('info5.jpg','Maio'),
		'Jun' => array('info6.jpg','Junho'),
		'Jul' => array('info7.jpg','Julho'),
		'Aug' => array('info8.jpg','Agosto'),
		'Sep' => array('info9.jpg','Setembro'),
		'Oct' => array('info10.jpg','Outubro'),
		'Nov' => array('info11.jpg','Novembro'),
		'Dec' => array('info12.jpg','Dezembro')
	);

	$imagem = $arrayImagens[$mes];

	// $sql = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
	// 		WHERE COD_EMPRESA = $cod_empresa 
	// 		AND (COD_EXCLUSA=0 
	// 				OR COD_EXCLUSA IS NULL) 
	// 		AND LOG_ESTATUS = 'S'";

	// $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	// //fnEscreve($sql);
	// while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery))
	// {
	// 	$lojasReportAdm = $lojasReportAdm.$qrListaUnidades['COD_UNIVEND'].",";  
	// }
	// $lojasReportAdm = rtrim(ltrim($lojasReportAdm,','),',');			
	
	// $lojasSelecionadas = $lojasReportAdm ;



	
	// -------------------------------------------------------------------------------
	$sql = "SELECT SUM(VAL_RESGATE) AS VAL_RESGATE , 
			SUM(VAL_VINCULADO) AS VVR ,
			(SUM(VAL_VINCULADO)-SUM(VAL_RESGATE)) AS VVR_LIMPO ,
			SUM(QTD_RESGATE) AS RESGATE , 
			SUM(QTD_CLIENTE_RESGATE) QTD_CLIENTE_RESGATE ,
			(SUM(VAL_VINCULADO)-SUM(VAL_RESGATE)) / SUM(QTD_RESGATE) TM_RESGATE
	FROM CREDITOSDEBITOS_DIARIAS CD
	INNER JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = CD.COD_UNIVEND
	WHERE CD.COD_EMPRESA = $cod_empresa
	AND UV.LOG_ESTATUS = 'S'
	AND CD.DAT_MOVIMENTO BETWEEN '$dat_inicial'  AND  '$dat_final'";

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);

	$qrTask = mysqli_fetch_assoc($arrayQuery);

	$val_resgate = $qrTask['VAL_RESGATE'];
	$vvr = $qrTask['VVR'];
	$vvr_limpo = $qrTask['VVR_LIMPO'];
	$resgate = $qrTask['RESGATE'];
	$qtd_cliente_resgate = $qrTask['QTD_CLIENTE_RESGATE'];
	$tmResgate = $qrTask['TM_RESGATE'];




	// -------------------------------------------------------------------------------
	$sql3 = "SELECT 
		           ROUND ( Sum(A.val_totfideliz) /  Sum(A.qtd_totfideliz) , 2 ) TICKET_MEDIO_FIDELIZADO,
		           ROUND ( ( Sum(A.val_totvenda) - Sum(A.val_totfideliz) ) / ( Sum(A.qtd_totvenda) - Sum(A.qtd_totfideliz) ) , 2 ) TICKET_MEDIO_AVULSO,
		           Sum(A.qtd_totvenda) AS QTD_TRANSACOES, 
		           Sum(A.qtd_totfideliz) AS QTD_TRANSACOES_FIDELIZADO, 
				   Sum(A.val_totvenda) AS TOTAL_FATURAMENTO, 
				   Sum(A.val_totfideliz) AS TOTAL_FATURAMENTO_FIDELIZADO,
				   Sum(A.qtd_totvenda) - Sum(A.qtd_totfideliz) QTD_TRANSACOES_AVULSO,
				   SUM(QTD_CLIENTE_FIDELIZ) QTD_CLIENTE_FIDELIZADO

		    FROM vendas_diarias A 
		    INNER JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = A.COD_UNIVEND
		    WHERE A.dat_movimento BETWEEN '$dat_inicial'  AND  '$dat_final'
		    AND A.cod_empresa = $cod_empresa
		    AND UV.LOG_ESTATUS = 'S'";

	$arrayQuery3 = mysqli_query(connTemp($cod_empresa,''), $sql3);

	$qrTask3 = mysqli_fetch_assoc($arrayQuery3);

	$ticket_medio_fidelizado = $qrTask3['TICKET_MEDIO_FIDELIZADO'];
	$ticket_medio_avulso = $qrTask3['TICKET_MEDIO_AVULSO'];
	$qtd_transacoes = $qrTask3['QTD_TRANSACOES'];
	$qtd_transacoes_fidelizado = $qrTask3['QTD_TRANSACOES_FIDELIZADO'];
	$total_faturamento = $qrTask3['TOTAL_FATURAMENTO'];
	$total_faturamento_fidelizado = $qrTask3['TOTAL_FATURAMENTO_FIDELIZADO'];
	$qtd_transacoes_avulso = $qrTask3['QTD_TRANSACOES_AVULSO'];
	$qtd_cliente_fidelizado = $qrTask3['QTD_CLIENTE_FIDELIZADO'];
	$totalFidelizado = ($qtd_transacoes_fidelizado / $qtd_transacoes) * 100;



	// -------------------------------------------------------------------------------
	$sql2 = "SELECT SUM(QTD_CLIENTES) TOT_CLIENTES, 
					SUM(QTD_CLIENTES_NOVOS) CLI_NOVOS, 
					AVG(PERC_FATURAMENTO_FIDELIZADO_MES_ANT) IND_FIDELIZ, 
					AVG(VL_INDICE_FREQUENCIA) IND_FREQ
			FROM tb_fechamento_cliente FC
			INNER JOIN WEBTOOLS.UNIDADEVENDA UV ON UV.COD_UNIVEND = FC.COD_UNIVEND
			WHERE FC.cod_empresa = $cod_empresa 
			AND UV.LOG_ESTATUS = 'S'
			AND FC.MESANO = '$dat_fim'";

			// echo($sql2);

	$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''), $sql2);

	$qrTask2 = mysqli_fetch_assoc($arrayQuery2);

	$tmFid = round(($total_faturamento_fidelizado -  $vvr) / ($qtd_transacoes_fidelizado - $resgate), 2);
	$tmAv = round(($total_faturamento - ($total_faturamento_fidelizado -  $vvr) -  $vvr) / $qtd_transacoes_avulso, 2);

	$variacaoPercRegsAv = (($tmResgate / $tmAv) - 1) * 100;
	$variacaoPercFidAv = (($tmFid / $tmAv) - 1) * 100;

	$totalClientes = $qrTask2['TOT_CLIENTES'];
	$totalNovosClientes = $qrTask2['CLI_NOVOS'];
	// $totalFidelizado = $qrTask2['IND_FIDELIZ'];
	$totalIndiceFideliz = $qrTask2['IND_FREQ'];
	$totVariacaoTM = $variacaoPercRegsAv;
	$totVariacaoTMFid = $variacaoPercFidAv;


	// -------------------------------------------------------------------------------
	$sqlBusca = "SELECT COD_TEMPLATE FROM EMAIL_MARKA 
				 WHERE COD_EMPRESA = $cod_empresa 
				 AND ANO_MES = '$dat_fim'";

	$arrayBus = mysqli_query($connboard, $sqlBusca);

	$temGerado = mysqli_num_rows($arrayBus);

	if($temGerado == 0){
		$sqlInsert = "INSERT INTO EMAIL_MARKA(
									COD_EMPRESA,
									ANO_MES,
									TOTAL_CLIENTES,
									TOTAL_NOVOS_CLIENTES,
									TOTAL_FIDELIZADO,
									TOTAL_INDICE_FIDELIZ,
									TOT_VARIACAO_TM,
									TOT_VARIACAO_TMFID
								  ) VALUES(
								  	$cod_empresa,
									'$dat_fim',
									'$totalClientes',
									'$totalNovosClientes',
									'$totalFidelizado',
									'$totalIndiceFideliz',
									'$totVariacaoTM',
									'$totVariacaoTMFid'
							      )";

		mysqli_query($connboard, $sqlInsert);
	}else{
		// $qrTask = mysqli_fetch_assoc($arrayBus);
	}

	if ($cod_empresa != 0) {
		//busca dados da empresa
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = $cod_empresa";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($adm, $sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		if (isset($arrayQuery)) {
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
	} else {
		$cod_empresa = 0;
		//fnEscreve('entrou else');
	}

?>
<html><head></head><body>
	<!-- <center class="msg_preview" style="font-family: Arial,Helvetica,sans-serif; font-size: 11px;margin-bottom:10px">Caso não esteja visualizando corretamente esta mensagem, <a target="BLANK" style="font-family: Arial,Helvetica,sans-serif; font-size: 10px" href="#">acesse este link</a></center> -->




	<title>Confira os indicadores do mês de <?=$imagem[1]?>!</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG></o:AllowPNG></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet" type="text/css"/><!--<![endif]-->
	<style>
		* {
			box-sizing: border-box;
		}

		body {
			margin: 0;
			padding: 0;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: inherit !important;
		}

		#MessageViewBody a {
			color: inherit;
			text-decoration: none;
		}

		p {
			line-height: inherit
		}

		.desktop_hide,
		.desktop_hide table {
			mso-hide: all;
			display: none;
			max-height: 0px;
			overflow: hidden;
		}

		.image_block img+div {
			display: none;
		}

		.menu_block.desktop_hide .menu-links span {
			mso-hide: all;
		}

		#memu-r0c0m2:checked~.menu-links {
			background-color: transparent !important;
		}

		#memu-r0c0m2:checked~.menu-links a,
		#memu-r0c0m2:checked~.menu-links span {
			color: #2e69b2 !important;
		}

		@media (max-width:620px) {
			.social_block.desktop_hide .social-table {
				display: inline-block !important;
			}

			.image_block img.big,
			.row-content {
				width: 100% !important;
			}

			.menu-checkbox[type=checkbox]~.menu-links {
				display: none !important;
				padding: 5px 0;
			}

			.menu-checkbox[type=checkbox]:checked~.menu-trigger .menu-open {
				display: none !important;
			}

			.menu-checkbox[type=checkbox]:checked~.menu-links,
			.menu-checkbox[type=checkbox]~.menu-trigger {
				display: block !important;
				max-width: none !important;
				max-height: none !important;
				font-size: inherit !important;
			}

			.menu-checkbox[type=checkbox]~.menu-links>a,
			.menu-checkbox[type=checkbox]~.menu-links>span.label {
				display: block !important;
				text-align: center;
			}

			.menu-checkbox[type=checkbox]:checked~.menu-trigger .menu-close {
				display: block !important;
			}

			.mobile_hide {
				display: none;
			}

			.stack .column {
				width: 100%;
				display: block;
			}

			.mobile_hide {
				min-height: 0;
				max-height: 0;
				max-width: 0;
				overflow: hidden;
				font-size: 0px;
			}

			.desktop_hide,
			.desktop_hide table {
				display: table !important;
				max-height: none !important;
			}

			.row-4 .column-1 .block-3.button_block a span,
			.row-4 .column-1 .block-3.button_block div,
			.row-4 .column-1 .block-3.button_block div span,
			.row-4 .column-2 .block-3.button_block a span,
			.row-4 .column-2 .block-3.button_block div,
			.row-4 .column-2 .block-3.button_block div span,
			.row-4 .column-3 .block-3.button_block a span,
			.row-4 .column-3 .block-3.button_block div,
			.row-4 .column-3 .block-3.button_block div span {
				font-size: 17px !important;
				line-height: 2 !important;
			}

			.row-4 .column-2 .block-1.image_block td.pad,
			.row-4 .column-3 .block-1.image_block td.pad {
				padding: 10px !important;
			}
		}
	</style>

	<table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;  background-image: none; background-position: top left; background-repeat: no-repeat;">
		<tbody>
			<tr>
				<td>

					<table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;">
                        <tbody>

                            <tr> 
                                <td class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;" width="600.00">

                                	<table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;" width="600.00">
										<tbody>
											<tr>
												<td class="column column-1" width="33.33333%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tbody>
															<tr>
																<td style="width:100%; padding-right: 0; padding-left:0;">
																	<div class="alignment" align="center">
																		<img src="https://img.bunker.mk/media/mkt/headMarka4.jpg" width="100%" alt="logo" title="logo"/>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>                                	
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <table class="row row-7" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 600.00px;" width="600.00">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 0px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="text_block block-1" width="100%" border="0" cellpadding="15" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody><tr>
															<td class="pad">
																<div style="font-family: &#39;Trebuchet MS&#39;, Tahoma, sans-serif">
																	<div class="" style="font-size: 12px; font-family: &#39;Oxygen&#39;, &#39;Trebuchet MS&#39;, &#39;Lucida Grande&#39;, &#39;Lucida Sans Unicode&#39;, &#39;Lucida Sans&#39;, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #595959; line-height: 1.2;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px; letter-spacing: normal; line-height: 1.2;"><span style="font-size:22px;">Atenção <b><?=$nom_empresa?></b>, temos novidades para você!</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</tbody></table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>


					<table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;">
                        <tbody>

                            <tr> 
                                <td class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;" width="600.00">

                                	<table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;" width="600.00">
										<tbody>
											<tr>
												<td class="column column-1" width="33.33333%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tbody>
															<tr>
																<td style="width:100%; padding-right: 0; padding-left:0;">
																	<div class="alignment" align="center">
																		<img src="https://img.bunker.mk/media/mkt/<?=$imagem[0]?>" width="70%" alt="Mes de Referencia" title="Mes de Referencia"/>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>                                	
                                </td>
                            </tr>

                        </tbody>
                    </table>

					<?php if($tipo == "ADM"){ ?>

                    <br>

					<table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;">
						<tbody>
							<tr>
								<td>
									<table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;" width="600.00">
										<tbody>
											<tr>
												<td class="column column-1" width="33.33333%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #FFD54F; padding-bottom: 5px; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tbody>
															<tr>
																<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																	<div class="alignment" align="center" style="line-height:10px"><a href="https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp£0¢&id=<?=fnEncode($cod_empresa)?>" target="_blank" style="outline:none" tabindex="-1"><img src="https://img.bunker.mk/media/mkt/Icone_1.png" style="display: block; height: auto; border: 0; width: 83px; max-width: 100%;margin-left:auto; margin-right:auto;" width="83" alt="Categoria 1" title="Categoria 1"/></a>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; margin-top: 10px; font-size: 26px; text-align: center; mso-line-height-alt: 16.8px;"><strong><?=fnValor($totalClientes,0)?></strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="4" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; font-size: 10px; text-align: center; mso-line-height-alt: 16.8px;"><strong>BASE TOTAL DE CLIENTES</strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td class="column column-2" width="33.33333%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #F19507; padding-bottom: 5px; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tbody>
															<tr>
																<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																	<div class="alignment" align="center" style="line-height:10px"><a href="https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp£0¢&id=<?=fnEncode($cod_empresa)?>" target="_blank" style="outline:none" tabindex="-1"><img src="https://img.bunker.mk/media/mkt/Icone_2.png" style="display: block; height: auto; border: 0; width: 83px; max-width: 100%;margin-left:auto; margin-right:auto;" width="83" alt="Categoria 1" title="Categoria 1"/></a>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; margin-top: 10px; font-size: 26px; text-align: center; mso-line-height-alt: 16.8px;"><strong><?=fnValor($totalNovosClientes,0)?></strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="4" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; font-size: 10px; text-align: center; mso-line-height-alt: 16.8px;"><strong>CLIENTES NOVOS NO MÊS</strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td class="column column-3" width="33.33333%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #86C657; padding-bottom: 5px; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tbody>
															<tr>
																<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																	<div class="alignment" align="center" style="line-height:10px"><a href="https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp£0¢&id=<?=fnEncode($cod_empresa)?>" target="_blank" style="outline:none" tabindex="-1"><img src="https://img.bunker.mk/media/mkt/Icone_3.png" style="display: block; height: auto; border: 0; width: 83px; max-width: 100%;margin-left:auto; margin-right:auto;" width="83" alt="Categoria 1" title="Categoria 1"/></a>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; margin-top: 10px; font-size: 26px; text-align: center; mso-line-height-alt: 16.8px;"><strong><?=fnValor($totalFidelizado,2)?>%</strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="4" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; font-size: 10px; text-align: center; mso-line-height-alt: 16.8px;"><strong>ÍNDICE DE FIDELIZAÇÃO</strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</td>

											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>

					<table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;">
						<tbody>
							<tr>
								<td>
									<table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;" width="600.00">
										<tbody>
											<tr>
												<td class="column column-1" width="33.33333%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #4C8FEE; padding-bottom: 5px; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tbody>
															<tr>
																<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																	<div class="alignment" align="center" style="line-height:10px"><a href="https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp£0¢&id=<?=fnEncode($cod_empresa)?>" target="_blank" style="outline:none" tabindex="-1"><img src="https://img.bunker.mk/media/mkt/Icone_4.png" style="display: block; height: auto; border: 0; width: 83px; max-width: 100%;margin-left:auto; margin-right:auto;" width="83" alt="Categoria 1" title="Categoria 1"/></a>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; margin-top: 10px; font-size: 26px; text-align: center; mso-line-height-alt: 16.8px;"><strong><?=round($totalIndiceFideliz,2)?></strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="4" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; font-size: 10px; text-align: center; mso-line-height-alt: 16.8px;"><strong>ÍNDICE DE FREQUÊNCIA POR CLIENTE</strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td class="column column-2" width="33.33333%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #F74B30; padding-bottom: 5px; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tbody>
															<tr>
																<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																	<div class="alignment" align="center" style="line-height:10px"><a href="https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp£0¢&id=<?=fnEncode($cod_empresa)?>" target="_blank" style="outline:none" tabindex="-1"><img src="https://img.bunker.mk/media/mkt/Icone_5.png" style="display: block; height: auto; border: 0; width: 83px; max-width: 100%;margin-left:auto; margin-right:auto;" width="83" alt="Categoria 1" title="Categoria 1"/></a>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; margin-top: 10px; font-size: 26px; text-align: center; mso-line-height-alt: 16.8px;"><strong><?=fnValor($totVariacaoTM,2)?>%</strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="4" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; font-size: 10px; text-align: center; mso-line-height-alt: 16.8px;"><strong>% VARIAÇÃO DE TICKET MÉDIO DE VENDAS COM RESGATE EM RELAÇÃO A VENDAS NÃO FIDELIZADAS</strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
												<td class="column column-3" width="33.33333%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #58BED6; padding-bottom: 5px; padding-top: 10px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tbody>
															<tr>
																<td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
																	<div class="alignment" align="center" style="line-height:10px"><a href="https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp£0¢&id=<?=fnEncode($cod_empresa)?>" target="_blank" style="outline:none" tabindex="-1"><img src="https://img.bunker.mk/media/mkt/Icone_6.png" style="display: block; height: auto; border: 0; width: 83px; max-width: 100%;margin-left:auto; margin-right:auto;" width="83" alt="Categoria 1" title="Categoria 1"/></a>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; margin-top: 10px; font-size: 26px; text-align: center; mso-line-height-alt: 16.8px;"><strong><?=fnValor($totVariacaoTMFid,2)?>%</strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table class="text_block block-2" width="100%" border="0" cellpadding="4" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody>
															<tr>
																<td class="pad">
																	<div style="font-family: sans-serif">
																		<div class="" style="text-align: center; font-size: 12px; font-family: Arial, &#39;Helvetica Neue&#39;, Helvetica, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
																			<p style="margin: 0; font-size: 10px; text-align: center; mso-line-height-alt: 16.8px;"><strong>% VARIAÇÃO DE TICKET MÉDIO DE VENDAS FIDELIZADAS EM RELAÇÃO A VENDAS NÃO FIDELIZADAS</strong></p>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</td>

											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>

					<?php } ?>

					<table class="row row-7" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 600.00px;" width="600.00">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="text_block block-1" width="100%" border="0" cellpadding="15" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody><tr>
															<td class="pad">
																<div style="font-family: &#39;Trebuchet MS&#39;, Tahoma, sans-serif">
																	<div class="" style="font-size: 12px; font-family: &#39;Oxygen&#39;, &#39;Trebuchet MS&#39;, &#39;Lucida Grande&#39;, &#39;Lucida Sans Unicode&#39;, &#39;Lucida Sans&#39;, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #595959; line-height: 1.2;">
																		<p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px; letter-spacing: normal;"><span style="font-size:18px;">Confira todos os indicadores de performance clicando no link abaixo:</span></p>
																	</div>
																</div>
															</td>
														</tr>
													</tbody></table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>

					<table class="row row-7" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
						<tbody>
							<tr>
								<td>
									<table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 600.00px;" width="600.00">
										<tbody>
											<tr>
												<td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 5px; padding-top: 5px; vertical-align: middle; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="text_block block-1" width="100%" border="0" cellpadding="15" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
														<tbody><tr>
															<td class="column column-1" width="20%"></td>
															<td class="column column-2" width="60%">
																<div style="font-family: &#39;Trebuchet MS&#39;, Tahoma, sans-serif">
																	<div class="" style="width: 100%; text-align: center; background-color: #1A4A90; border-radius: 5px; padding: 10px; font-size: 12px; font-family: &#39;Oxygen&#39;, &#39;Trebuchet MS&#39;, &#39;Lucida Grande&#39;, &#39;Lucida Sans Unicode&#39;, &#39;Lucida Sans&#39;, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #595959; line-height: 1.2;">
																		<a style="font-size: 15px; font-weight: 500; color: #fff; font-family: sans-serif; text-decoration: none; color: #FFD54F; " href="https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp£0¢&id=<?=fnEncode($cod_empresa)?>" target="_blank"><strong>CLIQUE AQUI PARA VER TODOS OS INDICADORES</strong></a>
																	</div>
																</div>
															</td>
															<td class="column column-3" width="20%"></td>
														</tr>
													</tbody></table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>

					<!-- <table width="300px" height="26px" align="center" cellpadding="0" cellspacing="0" style="background-color: #1A4A90; border-radius: 5px">
                        <tbody>

                            <tr> 
                                <td align="center" style="padding:10px"></td>
                            </tr>

                        </tbody>
                    </table> -->

                    <br>
                    <br>

                    <table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;">
                        <tbody>

                            <tr> 
                                <td class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;" width="600.00">

                                	<table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-size: auto; color: #000000; width: 600.00px;" width="600.00">
										<tbody>
											<tr>
												<td class="column column-1" width="33.33333%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; background-color: #ffffff; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
													<table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
														<tbody>
															<tr>
																<td style="width:100%; padding-right: 0; padding-left:0;">
																	<div class="alignment" align="center">
																		<img src="https://img.bunker.mk/media/mkt/headMarka4.jpg" width="100%" alt="logo" title="logo"/>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>                                	
                                </td>
                            </tr>

                        </tbody>
                    </table>
					
				</td>
			</tr>
		</tbody>
	</table><!-- End -->


