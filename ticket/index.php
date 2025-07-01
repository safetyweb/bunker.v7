<?php
sleep(1);
?>
<!DOCTYPE html>
<html>

<head>

	<meta name="viewport" content="width=device-width" />
	<title>Ticket de Ofertas</title>
	<link rel="ícone de atalho" href="images/favicontkt.ico" type="image/x-icon">
	<link rel="icon" href="images/favicontkt.ico" type="image/x-icon">
</head>

<body>

	<link href='https://fonts.googleapis.com/css?family=Lato:700,900' rel='stylesheet' type='text/css'>
	<script src="https://bunker.mk/js/jquery.min.js"></script>

	<style type="text/css">
		*,
		* {
			margin: 0 auto;
		}

		body {
			font-family: 'Lato', sans-serif;
			font-weight: 700;
			color: #000;
			-webkit-print-color-adjust: exact;
		}

		@page {
			size: auto;
			margin: 0mm;
		}

		.bloco {
			position: relative;
			clear: both;
			margin-top: 5px;
			text-align: center;
			margin-right: auto;
			margin-left: auto;
			width: 360px;
		}

		.bloco-pai {
			background: #fff;
		}

		.lista {
			list-style-type: circle;
			text-align: left;
			font-size: 13px;
			font-weight: 400;
		}

		.upload-image {
			max-width: 100%;
			max-height: 100%;
		}

		.image-container {
			width: 100%;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.push {
			clear: both;
		}

		.push1 {
			height: 1px;
			clear: both;
		}

		.push2 {
			height: 2px;
			clear: both;
		}

		.push3 {
			height: 3px;
			clear: both;
		}

		.push5 {
			height: 5px;
			clear: both;
		}

		.push10 {
			height: 10px;
			clear: both;
		}

		.push20 {
			height: 20px;
			clear: both;
		}

		.push30 {
			height: 30px;
			clear: both;
		}

		.push50 {
			height: 50px;
			clear: both;
		}

		.push100 {
			height: 100px;
			clear: both;
		}

		.borda {
			border: 1px solid #000;
		}

		@page {
			size: auto;
			/* auto is the initial value */

			/* this affects the margin in the printer settings */
			margin: 0mm 0mm 0mm 0mm;
		}

		@media print {
			body {
				-webkit-print-color-adjust: exact;
			}
		}
	</style>

	<?php
	include "../_system/_functionsMain.php";

	//$_SESSION["tkt"]=2;	

	//echo fnDebug('true');
	//TESTE URL - 0dZNjqJqwg740eZxaPjrBP9sAIdp£Kcp£h

	$parametros = fnDecode($_GET['tkt']);

	if (isset($_GET['nome'])) {
		$nomeSimul = $_GET['nome'];
		$arrayNomeSimul = explode(" ", $nomeSimul);
		$nomeSimulLimpo = $arrayNomeSimul[0];
	}

	$arrayCampos = explode(";", $parametros);

	$cod_empresa = $arrayCampos[0];
	$num_cartao = $arrayCampos[1];
	$cod_loja = $arrayCampos[2];

	//fnEscreve($cod_loja);

	//busca dados da configuração	
	if (is_numeric($cod_empresa)) {
		//busca dados da empresa
		$sql = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA = '" . $cod_empresa . "'  and LOG_ATIVO_TKT = 'S' ";

		//fnTesteSql(connTemptkt($connAdm->connAdm(),$cod_empresa,""),$sql);
		$arrayQuery = mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), trim($sql));
		$qrBuscaConfiguracao = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaConfiguracao)) {
			$cod_configu = $qrBuscaConfiguracao['COD_CONFIGU'];
			$log_ativo_tkt = $qrBuscaConfiguracao['LOG_ATIVO_TKT'];
			$cod_template_tkt = $qrBuscaConfiguracao['COD_TEMPLATE_TKT'];
			$qtd_compras_tkt = $qrBuscaConfiguracao['QTD_COMPRAS_TKT'];
			$qtd_ofertas_tkt = $qrBuscaConfiguracao['QTD_OFERTAS_TKT'];
			$qtd_produtos_tkt = $qrBuscaConfiguracao['QTD_PRODUTOS_TKT'];
			$qtd_categor_tkt = $qrBuscaConfiguracao['QTD_CATEGOR_TKT'];
			$num_historico_tkt = $qrBuscaConfiguracao['NUM_HISTORICO_TKT'];
			$min_historico_tkt = $qrBuscaConfiguracao['MIN_HISTORICO_TKT'];
			$max_historico_tkt = $qrBuscaConfiguracao['MAX_HISTORICO_TKT'];
			$cod_blklist = $qrBuscaConfiguracao['COD_BLKLIST'];
			$log_emisdia = $qrBuscaConfiguracao['LOG_EMISDIA'];
		} else {
			echo (";| Ticket desabilitado");
		}
	} else {

		echo (";( Ticket inválido");
	}

	//busca nome do cliente
	$sql1 = "SELECT CL.*, UV.NOM_FANTASI, B.NOM_FAIXACAT FROM CLIENTES CL
			 LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = CL.COD_UNIVEND 
			 LEFT JOIN categoria_cliente AS B ON B.COD_CATEGORIA=CL.COD_CATEGORIA
			 where (CL.NUM_CARTAO = '" . $num_cartao . "' or CL.NUM_CGCECPF = '" . $num_cartao . "')  AND CL.COD_EMPRESA = $cod_empresa";
	// fnEscreve($sql1);
	//fnTesteSql(connTemptkt($connAdm->connAdm(),$cod_empresa,""),$sql);
	$arrayQuery1 = mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), trim($sql1));
	$qrBuscaNomeCli = mysqli_fetch_assoc($arrayQuery1);

	if (isset($qrBuscaNomeCli)) {
		$nom_cliente = $qrBuscaNomeCli['NOM_CLIENTE'];
		$cod_cliente = $qrBuscaNomeCli['COD_CLIENTE'];
		$num_cgcecpf = $qrBuscaNomeCli['NUM_CGCECPF'];
		$dat_ultcompr = $qrBuscaNomeCli['DAT_ULTCOMPR'];
		$arrayNome = explode(" ", $nom_cliente);
		$nome = $arrayNome[0];
		$dia_nascime = $qrBuscaNomeCli['DIA'];
		$mes_nascime = $qrBuscaNomeCli['MES'];
		$ano_nascime = $qrBuscaNomeCli['ANO'];
		$dia_hoje = date('d');
		$mes_hoje = date('m');
		$ano_hoje = date('Y');
		$dat_atualiza = $qrBuscaNomeCli['DAT_ALTERAC'];
		$log_estatus = $qrBuscaNomeCli['LOG_ESTATUS'];
		$nom_faixacat = $qrBuscaNomeCli['NOM_FAIXACAT'];
	}
	//verifica se nome cliente está preenchido
	if (!empty($nom_cliente)) {
		$arrayNome = explode(" ", $nom_cliente);
		$nomeLimpo = $arrayNome[0];
	} else {
		$nomeLimpo = "Cliente";
		$nome = "Cliente";
	}

	if (!empty($nomeSimul)) {
		$nomeLimpo = $nomeSimulLimpo;
		$nom_cliente = $nomeSimul;
		$nome = $nomeLimpo;
	}

	//select cod_persona from personaclientes where cod_cliente = 777751 and cod_empresa = 3

	//monta ticket 
	//fnEscreve($sql1);
	//fnEscreve($num_cartao);
	//fnEscreve($cod_cliente);
	//fnEscreve($num_cartao);
	//fnEscreve($cod_empresa);

	?>

	<div class="bloco bloco-pai">
		<?php

		//montagem do array - código da template
		//fnEscreve($cod_template_tkt);

		$sql = "SELECT MODELOTEMPLATETKT.COD_REGISTR,
					   MODELOTEMPLATETKT.COD_EMPRESA,
					   MODELOTEMPLATETKT.COD_TEMPLATE,
					   MODELOTEMPLATETKT.COD_BLTEMPL,
					   MODELOTEMPLATETKT.DES_IMAGEM,
					   MODELOTEMPLATETKT.DES_TEXTO
				FROM   MODELOTEMPLATETKT
				WHERE  MODELOTEMPLATETKT.COD_EMPRESA = $cod_empresa 
				AND    MODELOTEMPLATETKT.COD_TEMPLATE = $cod_template_tkt
				AND    MODELOTEMPLATETKT.COD_EXCLUSA is null
				ORDER BY NUM_ORDENAC";

		// echo($sql);

		$arrayQuery = mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ''), $sql);

		$temTemplate = mysqli_num_rows($arrayQuery);

		if ($temTemplate > 0) {

			while ($qrListaModelos = mysqli_fetch_assoc($arrayQuery)) {


				switch ($qrListaModelos['COD_BLTEMPL']) {
					case 1: //nome do cliente		
		?>
						<div class="bloco">
							<h3 style="margin: 5px; font-size:26px;"><b> <?php echo $nomeLimpo; ?></b></h3>
							<?php if ($nom_faixacat != "") { ?>
								<h5 style="margin: 5px; font-weight: 600"><small><b>Cliente <?= $nom_faixacat ?></b></small></h5>
							<?php } ?>
							<!-- <h5 style="margin: 5px 5px 15px 5px; font-size:15px;"><b>LEVE TAMBÉM...</b></h5> -->
							<h5 style="margin: 5px 5px 15px 5px; font-size:15px;"><b>Está esquecendo de algo?</b></h5>
						</div>
					<?php
						break;
					case 2: //lista de produtos modelo 1
					case 18: //lista de produtos modelo 2
					case 20: //lista de produtos modelo 3

					?>
						<div class="bloco">
							<center>
								<!--<h5 style="margin: 20px 0 10px 0; font-size:18px">Veja <strong><?php echo $qtd_produtos_tkt; ?> ofertas personalizadas</strong> para voc&ecirc;!</h5>-->
								<h5 style="margin: 20px 0 10px 0; font-size:18px">Veja <strong>ofertas personalizadas</strong> para voc&ecirc;!</h5>
							</center>
							<div style="display: flow-root;">
								<div class="bloco" style="font-size: 14px;">
									<?php
									if ($log_emisdia != 'N') {
										$param = "and LOG_EMISDIA='S' and DAT_VALIDADE >= '" . date('Y-m-d') . "'";
									} else {
										$param = "and LOG_EMISDIA='N'";
									}
									$sql2 = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_cliente and COD_EMPRESA=" . $cod_empresa . " $param ORDER by COD_GERAL DESC limit 1;";
									$misdiatkt = mysqli_fetch_assoc(mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), $sql2));
									//gravar se o tkt foi visualizado
									$sqlupdatevisualizacao = "UPDATE ticket SET LOG_VISUALIZACAO='1' WHERE  COD_TICKET=" . $misdiatkt['COD_GERAL'];
									// echo $sqlupdatevisualizacao;
									$rwupdatevisualizacao = mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), $sqlupdatevisualizacao);

									$OFERTASTKT = unserialize($misdiatkt['DES_TICKET']);

									$BLTEMPL = $qrListaModelos['COD_BLTEMPL'];

									for ($i = 0; $i < count($OFERTASTKT); $i++) {

										if ($OFERTASTKT[$i]['codigoexterno'] != '') {
											//produtos modelo 1
											if ($qrListaModelos['COD_BLTEMPL'] == 2) {

												echo "
																   <div class='' style='width: 65%; float: left; text-align: left;'>
																		   <span style='font-weight: 700;'>" . $OFERTASTKT[$i]['descricao'] . "</span>";

												//sem espaços para retiro
												if ($cod_empresa != 69) {
													echo "				   <div class='push1'></div>
																		   <span style='font-weight: 700; font-size: 13px;'>Código: " . $OFERTASTKT[$i]['codigoexterno'] . "</span>";
												}

												echo "			   </div>
																   <div class='' style='text-align: right;'>";
												//valor zerado
												if (fnValor($OFERTASTKT[$i]['preco'], 2) != "0,00") {
													echo "<span style='font-weight: 700; margin: 0;'>de: R$ " . fnValor($OFERTASTKT[$i]['preco'], 2) . "</span><br/>";
												}
												echo "					   
																	<span style='font-weight: 900; font-size: 17px; margin: 0;'>&nbsp; por: R$ " . fnValor($OFERTASTKT[$i]['valorcomdesconto'], 2) . "</span>
																   </div>
																   <div class='push'></div>
																   <hr style='border: none; height: 1px; color: #000; background-color: #000;  margin-top: 10px; margin-bottom: 10px;'/>
																   <div class='push'></div>
																   ";
												//montagem do array
												$produtoLista .= $OFERTASTKT[$i]['codigointerno'] . "," . $produtoLista;
												$produtoListaCod .= $OFERTASTKT[$i]['codigointerno'];
												$produtoListaVAL .= fnValor($OFERTASTKT[$i]['preco'], 2);
												$produtoListaPROM .= $OFERTASTKT[$i]['valorcomdesconto'];
											}
											//produtos modelo 2
											else if ($qrListaModelos['COD_BLTEMPL'] == 18) {

												if ($OFERTASTKT[$i]['grupodesc'] == 0) {
													unset($pctFinal);
													$preco = $OFERTASTKT[$i]['preco'];
													$valorcomdesconto = $OFERTASTKT[$i]['valorcomdesconto'];
													$descontopctgeral = $OFERTASTKT[$i]['descontopctgeral'];
													$pctFinal = (($preco - $valorcomdesconto) * 100) / $preco;
													if ($descontopctgeral > 0) {
														$pctFinal = $descontopctgeral;
													}
													// $pctFinal = 100-(($valorcomdesconto*100)/$preco);
													//fnEscreve(100-$pctFinal);
													// fnEscreve($preco);
													// fnEscreve($valorcomdesconto);
													// fnEscreve(fnValor($pctFinal, 0));

													echo "
																		<div class='' style='width: 70%; float: left; text-align: left;'>
																		   <span style='font-weight: 900;'><small>" . mb_strtoupper($OFERTASTKT[$i]['categoria'], 'UTF-8') . "</small></span>
																		   <div class='push1'></div>
																		   <span style='font-weight: 700;'>" . $OFERTASTKT[$i]['descricao'] . "</span>
																				<div class='push1'></div>
																	";

													//critica valor zerado - de
													if ($pctFinal > 0 && $preco > 0) {
														echo "					
																			<span style='font-weight: 700; margin: 0;'>de: R$ " . fnValor($OFERTASTKT[$i]['preco'], 2) . "</span> &nbsp;
																			";
													}

													//critica valor zerado - de
													if ($pctFinal > 0 && ($preco > 0 || $valorcomdesconto > 0)) {
														echo "					
																			<span style='font-weight: 900; font-size: 17px; margin: 0;'>por: R$ " . fnValor($OFERTASTKT[$i]['valorcomdesconto'], 2) . "</span>
																				<div class='push2'></div>
																			";
													}

													echo "											
																			<span style='font-weight: 700; font-size: 13px;'>Código: " . $OFERTASTKT[$i]['codigoexterno'] . "</span>
																		</div>
																	";

													if ($pctFinal > 0) {
														echo "		
																					<div class='' style='text-align: right;'>
																					<span style='font-weight: 900; font-size: 40px; letter-spacing: -1px; margin: 0;'>" . fnValor($pctFinal, 0) . "%</span>
																					<br/>
																					<span style='font-weight: 500; font-size: 14px; letter-spacing: -1px; margin: 0;'>DESCONTO&nbsp;&nbsp;</span>
																					</div>
																				";
													}

													echo "		
																		<div class='push'></div>
																		<hr style='border: none; height: 1px; color: #000; background-color: #000; margin-top: 10px; margin-bottom: 10px; '/>
																		<div class='push'></div>
																   ";
													//montagem do array
													$produtoLista .= $OFERTASTKT[$i]['codigointerno'] . "," . $produtoLista;
													$produtoListaCod .= $OFERTASTKT[$i]['codigointerno'];
													$produtoListaVAL .= fnValor($OFERTASTKT[$i]['preco'], 2);
													$produtoListaPROM .= $OFERTASTKT[$i]['valorcomdesconto'];
												}
											} else if ($qrListaModelos['COD_BLTEMPL'] == 20) {

												$preco = $OFERTASTKT[$i]['preco'];
												$valorcomdesconto = $OFERTASTKT[$i]['valorcomdesconto'];
												$descontopctgeral = $OFERTASTKT[$i]['descontopctgeral'];
												$pctFinal = 100 - (($valorcomdesconto * 100) / $preco);
												if ($descontopctgeral > 0) {
													$pctFinal = $descontopctgeral;
												}

												//fnEscreve(100-$pctFinal);
												//echo (100-$pctFinal);

												//echo ($pctFinal);													
												//echo ($preco);
												//echo ($valorcomdesconto);
												//echo ($descontopctgeral);

												/*
													echo "<pre>";
													print_r($OFERTASTKT);
													echo "</pre>";
													*/

												//Elina - alterado em 14/03

												echo "
																	
																<div style='background: #000; display: flex; color: #fff; padding: 5px 0 5px 2px; margin: 0 0 5px 0;'>
																	<div class='' style='width: 70%; float: left; text-align: left;'>
																		   <span style='font-weight: 900;'>" . mb_strtoupper($OFERTASTKT[$i]['categoria'], 'UTF-8') . "</span>
																		   <div class='push1'></div>
																		   <span style='font-weight: 700;'>" . $OFERTASTKT[$i]['descricao'] . "</span>
																			<div class='push1'></div>
													";
												//critica valor zerado - de
												if ($pctFinal > 0 && $preco > 0) {
													echo "	
																			<span style='font-weight: 700; margin: 0;'> de: R$ " . fnValor($OFERTASTKT[$i]['preco'], 2) . "</span> &nbsp; 
																		";
												}

												//critica valor zerado - por
												if ($pctFinal > 0 && ($preco > 0 || $valorcomdesconto > 0)) {
													echo "	
																			<span style='font-weight: 900; font-size: 17px; margin: 0;'>por: R$ " . fnValor($OFERTASTKT[$i]['valorcomdesconto'], 2) . "</span>
																			<div class='push2'></div>
																		";
												}

												echo "	
																			
																		   <span style='font-weight: 700; font-size: 13px;'>Código: " . $OFERTASTKT[$i]['codigoexterno'] . "</span>
																	</div>
																	<div class='' style='text-align: right;'>
													";
												if ($pctFinal > 0) {
													echo "
																		<span style='font-weight: 900; font-size: 40px; letter-spacing: -1px; margin: 0;'>" . fnValor($pctFinal, 0) . "%</span>
																		";
												}

												echo "
																	</div>
																	<div class='push'></div>
																</div>
																<div class='push'></div>
																
																   ";
												//montagem do array
												$produtoLista .= $OFERTASTKT[$i]['codigointerno'] . "," . $produtoLista;
												$produtoListaCod .= $OFERTASTKT[$i]['codigointerno'];
												$produtoListaVAL .= fnValor($OFERTASTKT[$i]['preco'], 2);
												$produtoListaPROM .= $OFERTASTKT[$i]['valorcomdesconto'];
											}
										}
									}

									?>
								</div>

							</div>

						</div>
					<?php
						break;
					case 3: //lista de promoções black
					case 10: //lista de promoções black com imagem

					case 7: //lista de promoções white
					case 9: //lista de promoções white com imagem
					case 19: //lista de promoções white com imagem e percentual

						//bloco black
						if ($qrListaModelos['COD_BLTEMPL'] == 3 || $qrListaModelos['COD_BLTEMPL'] == 10) {
							$cor_fundo = "#161616";
							$cor_texto = "#fff";
						} else {
							$cor_fundo = "#fff";
							$cor_texto = "#000";
						}

						//fnEscreve($qrListaModelos['COD_BLTEMPL']);

					?>

						<div class="bloco">
							<center style="margin-bottom: 5px; padding: 0 5px 5px 5px; background-color: <?php echo $cor_fundo; ?>; color: <?php echo $cor_texto; ?>; ">
								<?php
								if ($qtd_ofertas_tkt > 1) {
									$txtOferta = "OFERTAS";
								} else {
									$txtOferta = "OFERTA";
								}
								?>
								<div class="bloco" style="text-align: left; font-size: 13px">
									<center>
										<h5 style="font-weight: 900; margin-bottom: 2px; font-size: 20px;"><?php echo $txtOferta; ?> EM DESTAQUE</h5>
										<div class="push10"></div>

										<?php
										if ($log_emisdia != 'N') {
											$param = "and LOG_EMISDIA='S' and DAT_VALIDADE >= '" . date('Y-m-d') . "'";
										} else {
											$param = "and LOG_EMISDIA='N'";
										}
										//inicializa array geral 
										$BLTEMPL = $qrListaModelos['COD_BLTEMPL'];
										$sql10 = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_cliente and COD_EMPRESA=" . $cod_empresa . " $param ORDER by COD_GERAL DESC limit 1;";
										$misdiatkt = mysqli_fetch_assoc(mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), $sql10));
										$OFERTADESTAQUE = unserialize($misdiatkt['DES_PROMOCAO']);
										//print_r($OFERTADESTAQUE);
										//Array ( [0] => Array ( [msgerro] => Não há produtos em promoção! [coderro] => 88 ) )  
										// fnEscreveArray($OFERTADESTAQUE);

										for ($i = 0; $i < count($OFERTADESTAQUE); $i++) {

											//se é bloco com imagem
											if ($qrListaModelos['COD_BLTEMPL'] != 3 && $qrListaModelos['COD_BLTEMPL'] != 7) {
												echo "
																	<img style='cursor: pointer; max-width:100%; max-height: 100%'>
																			<img src='" . $OFERTADESTAQUE[$i]['imagem'] . "' style='max-width:250px; max-height: 250px;'>
																	</img>
															";
											}

											echo "
																	<h4 style='font-size: 21px; margin-top: 2px; margin-bottom: 2px'>" . $OFERTADESTAQUE[$i]['descricao'] . "</h4>
																	<h5 style='font-size: 12px; margin-top: 2px; margin-bottom: 2px; font-weight:500;'>" . $OFERTADESTAQUE[$i]['codigoexterno'] . "</h5>
																	<span style='font-weight: 900; font-size: 23px; margin-top: 2px'> ";
											//valor zerado
											if (fnValor($OFERTADESTAQUE[$i]['preco'], 2) != "0,00") {
												echo "<span style='font-weight: normal; font-size: 19px;'>De: R$ " . fnValor($OFERTADESTAQUE[$i]['preco'], 2) . "&nbsp; </span> ";
											}

											//fnEscreve($OFERTADESTAQUE[$i]['cod_erro']);

											//verifica se oferta não está zerada
											if ($OFERTADESTAQUE[$i]['coderro'] == 88) {
												echo "
																		<div style='padding:0; margin:10px 0 0 0; font-size: 12px;'>
																			<center>
																				Ainda não há ofertas para este perfil 
																			</center>
																		</div>
																		<div class='push'></div>
																		";
											} else {
												//fnEscreve("sem oferta");

												if (fnValor($OFERTADESTAQUE[$i]['valorcomdesconto'], 2) != "0,00") {
													echo "
																			 Por: R$ " . number_format($OFERTADESTAQUE[$i]['valorcomdesconto'], 2, ',', '.') . "</span>
																			<div class='push'></div>
																			";
												}

												$precoDestaque = $OFERTADESTAQUE[$i]['preco'];
												$valorDescDestaque = $OFERTADESTAQUE[$i]['valorcomdesconto'];
												$descontoPctGeral = $OFERTADESTAQUE[$i]['descontopctgeral'];

												//$pctFinalDestaque = 0;


												if ($descontoPctGeral == "0" && $precoDestaque != '0.00' && $valorDescDestaque != '0.00') {
													//fnEscreve("if");
													$pctFinalDestaque = 100 - (($valorDescDestaque * 100) / $precoDestaque);
												} else {
													//fnEscreve("else");

													$pctFinalDestaque = $descontoPctGeral;
												}

												//fnEscreve($precoDestaque);
												//fnEscreve($valorDescDestaque);
												//fnEscreve($OFERTADESTAQUE[$i]['descontosobrepercentual']);
												//fnEscreve(100-$pctFinal);
												//fnEscreve($pctFinalDestaque);
												//fnEscreve($descontoPctGeral);
												if ($pctFinalDestaque != '0') {
													if ($qrListaModelos['COD_BLTEMPL'] == 19) {
														if ($pctFinalDestaque > 0) {
															echo " <span style='font-weight: 900; font-size: 40px;'><strong>" . fnValor($pctFinalDestaque, 0) . "%</strong></span> ";
														}
													}
												}
											}


											$produtoOferta .= $OFERTADESTAQUE[$i]['codigointerno'] . "," . $produtoOferta;

											echo "<div class='push20'></div>";
										}

										?>

										<h4 style="font-size: 21px">Aproveite!</h4>

									</center>
								</div>
							</center>
						</div>
					<?php
						break;
					case 4: //destaque

						$procsaldo = "CALL SP_CONSULTA_SALDO_CLIENTE ($cod_cliente)";
						$SALDO_CLIENTE = mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), trim($procsaldo));
						$rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
					?>
						<div class="bloco">
							<center>
								<div style="font-size: 16px; line-height: 22px;"><span style="font-weight:900;"><?php echo fnMascaraCampo($nom_cliente); ?></span> <br />
									Seu saldo total é: R$ <?php echo fnValor($rowSALDO_CLIENTE['TOTAL_CREDITO'], 2); ?> <br />
									<span style="font-size: 12px; line-height: 18px;">Disponível imediatamente é: R$ <?php echo fnValor($rowSALDO_CLIENTE['CREDITO_DISPONIVEL'], 2); ?> <span /> <br />
										<?php echo date("d/m/Y"); ?> às <?php echo date("H:i"); ?> <br />
								</div>
							</center>
						</div>
					<?php


						break;
					case 5: //rodape
					?>
						<div class="bloco">
							<center>
								<h6 style="margin-right: 20px; margin-bottom:10px; font-size: 13px;font-weight: 400">Ofertas válidas até o término da campanha ou enquanto durar o estoque.</h6>
								<div class="div-imagem">
									<?php
									if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
									?>
										<div class="image-container">
											sem imagem cadastrada
										</div>
									<?php
									} else {
									?>
										<div class="image-container">
											<img src='https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $qrListaModelos['DES_IMAGEM']; ?>' style='max-width:100%; max-height: 100%'>
										</div>
									<?php


									}

									$ArrayRodaPe = array($qrListaModelos['COD_BLTEMPL'] => array("DES_IMAGEM" => $qrListaModelos['DES_IMAGEM']));

									?>
								</div>
								<h6 style="font-size: 11px;font-weight: 400;margin-top:10px;">Ticket de Ofertas | Marka Sistemas</h6>
							</center>
						</div>
					<?php
						break;
					case 6: //imagem
					?>
						<div class="bloco">
							<?php
							if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
							?>
								<div class="image-container">
									sem imagem cadastrada
								</div>
							<?php
							} else {
							?>
								<div class="image-container">
									<img src='https://img.bunker.mk/media/clientes/<?php echo $cod_empresa ?>/<?php echo $qrListaModelos['DES_IMAGEM']; ?>' style='max-width:100%; max-height: 100%'>
								</div>
							<?php
							}

							$ArrayImagem = array($qrListaModelos['COD_BLTEMPL'] => array("DES_IMAGEM" => $qrListaModelos['DES_IMAGEM']));

							?>
						</div>
					<?php
						break;
					case 8: //habito de compras
					?>

						<!-- Produtos abaixo da logotipo da empresa -->
						<div class="bloco" style="text-align: left; font-size: 14px">
							<?php
							if ($log_emisdia != 'N') {
								$param = "and LOG_EMISDIA='S' and DAT_VALIDADE >= '" . date('Y-m-d') . "'";
							} else {
								$param = "and LOG_EMISDIA='N'";
							}
							$sql8 = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_cliente and COD_EMPRESA=" . $cod_empresa . " $param ORDER by COD_GERAL DESC limit 1;";
							$misdiatkt = mysqli_fetch_assoc(mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), $sql8));
							$DES_HABITOS = unserialize($misdiatkt['DES_HABITOS']);

							$BLTEMPL = $qrListaModelos['COD_BLTEMPL'];

							for ($i = 0; $i < count($DES_HABITOS); $i++) {
								if ($DES_HABITOS[$i]['codigoexterno'] != '') {
									echo "
											  <div class='col-md-12'>&emsp; &bull; &nbsp; " . $DES_HABITOS[$i]['descricao'] . "  
											  <div class='push'></div>";

									if ($cod_empresa != 69) {
										echo "
											  <span style='font-weight: 700; margin: 0 0 0 34px; font-size: 13px;'>Código: " . $DES_HABITOS[$i]['codigoexterno'] . "</span></div>
											  <div class='push3'></div>
											";
									}
									$produtoHabito .= $DES_HABITOS[$i]['codigointerno'] . "," . $produtoHabito;
								}
							}


							?>
						</div>
	</div>
	</div>
<?php
						break;
					case 11: //saldo com cartão

						$procsaldo = "CALL SP_CONSULTA_SALDO_CLIENTE ($cod_cliente)";
						$SALDO_CLIENTE = mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), trim($procsaldo));
						$rowSALDO_CLIENTE = mysqli_fetch_assoc($SALDO_CLIENTE);
?>
	<div class="bloco">
		<center>
			<div style="font-size: 16px; line-height: 22px;"><span style="font-size: 21px; font-weight:900;"><?php echo $nom_cliente; ?></span> <br />
				<small>cartão: <?php echo fnMascaraCampo($num_cartao); ?></small> <br />
				Seu saldo é: R$ <?php echo fnValor($rowSALDO_CLIENTE['CREDITO_DISPONIVEL'], 2); ?> <br />
				<?php echo date("d/m/Y"); ?> às <?php echo date("H:i"); ?> <br />
			</div>
		</center>
	</div>

<?php


						break;
					case 15: //cod. de barra com cpf
					case 16: //cod. de barra sem cpf

?>
	<div class="bloco">
		<center>

			<?php

						include '../_system/codebar/BarcodeGenerator.php';
						include('../_system/codebar/BarcodeGeneratorPNG.php');
						//include('../_system/codebar/BarcodeGeneratorHTML.php');

						$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
						//$generator = new \Picqer\Barcode\BarcodeGeneratorHTML;
						//echo $generator->getBarcode($num_cgcecpf, $generator::TYPE_CODE_128,3.3,60);  
						echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($num_cgcecpf, $generator::TYPE_CODE_128, 3.3, 60)) . '">';

						if ($qrListaModelos['COD_BLTEMPL'] == 15) {
							echo "<br/><span style='font-size: 18px;'>" . $num_cgcecpf . "</span>";
						}
			?>

		</center>
	</div>

<?php


						break;
					case 17: //última compra
?>
	<div class="bloco">
		<div style="padding:0; margin:10px 0 0 0; font-size: 12px;">
			<center>

				<?php
						if (!empty($dat_ultcompr)) {
				?>
					Data Referência: <?php echo fnDateRetorno($dat_ultcompr); ?>
				<?php
						}
				?>

			</center>
		</div>
	</div>

<?php
						break;

					//novo bloco grupo de desconto ======================================================================

					case 12: //grupo de desconto - 2 colunas

						//fnEscreve("sghdfj hjf...");
?>

	<div class="bloco">
		<center>
			<h5 style="margin: 20px 0 10px 0; font-size:18px">Veja <strong>ofertas personalizadas</strong> para voc&ecirc;!</h5>
		</center>
		<div style="display: flow-root;">
			<div class="bloco" style="font-size: 14px;">
				<?php
						unset($OFERTASTKT);
						unset($misdiatkt);
						unset($PRDAGRUPO);
						unset($dados);
						unset($vlcategoria);
						unset($grupoDesc);
						unset($row);
						unset($catDesc);


						if ($log_emisdia != 'N') {
							$param = "and LOG_EMISDIA='S' and DAT_VALIDADE >= '" . date('Y-m-d') . "'";
						} else {
							$param = "and LOG_EMISDIA='N'";
						}
						$sql10 = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_cliente and COD_EMPRESA=" . $cod_empresa . " $param ORDER by COD_GERAL DESC limit 1;";
						$misdiatkt = mysqli_fetch_assoc(mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), $sql10));

						$OFERTASTKT = unserialize($misdiatkt['DES_TICKET']);

						$alinha == "right";


						foreach ($OFERTASTKT as $row) {
							$PRDAGRUPO[$row['desconto']][$row['categoria']][] = array(
								'categoria' => $row['categoria'],
								'codigoexterno' => $row['codigointerno'],
								'codigointerno' => $row['codigointerno'],
								'descricao' => $row['descricao'],
								'preco' => $row['preco'],
								'valorcomdesconto' => $row['valorcomdesconto'],
								'desconto' => $row['desconto'] . '%',
								'imagem' => $row['imagem'],
								'grupodesc' => $row['grupodesc']
							);
						}

						foreach ($PRDAGRUPO as $key => $dados) {
							//ficou bonito? não!
							//funcionou? sim
							//caguei!
							foreach ($dados as $keylimp => $valuelimpa) {
								foreach ($valuelimpa as $keylimp1 => $value1) {
									if ($value1['grupodesc'] >= 1) {
										$vla = $value1['grupodesc'];
									}
								}
							}
							if ($alinha == "left") {
								$alinha = "right";
							} else {
								$alinha = "left";
							}

							if ($vla >= '1') {
								echo "
											<div class='' style='width: 44%; float: " . $alinha . "; padding: 5px 5px 10px 10px; text-align: left; border: 2px dashed #000; min-height: 200px; margin: 0 0 5px 0;'> ";
							}
							foreach ($dados as $categoria => $vlcategoria) {

								for ($i = 0; $i < count($vlcategoria); $i++) {

									if ($vlcategoria[$i]['grupodesc'] >= 1) {
										if ($grupoDesc != $vlcategoria[$i]['desconto']) {
											echo "
	                                                                                                            <span style='font-weight: 900; font-size: 50px; letter-spacing: -1px; margin: 0;'>" . $vlcategoria[$i]['desconto'] . "</span>
	                                                                                                            <br/><span style='background-color: #000; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span><br/><br/>";
										}

										echo "													
	                                                                                                                    <div class='' style='width: 100%; float: left; text-align: left;'> ";

										if ($catDesc != $vlcategoria[$i]['categoria']) {
											echo "
	                                                                                                                            <span style='font-weight: 900;'><small>" . mb_strtoupper($vlcategoria[$i]['categoria'], 'UTF-8') . "</small></span> ";
										} else {
											echo "
	                                                                                                                            <span style='font-weight: 900;'></span> ";
										}

										echo "		   
	                                                                                                               <div class='push1'></div>
	                                                                                                               <span style='font-weight: 700;'><small>" . $vlcategoria[$i]['descricao'] . "</small></span>
	                                                                                                                            <div class='push2'></div>
	                                                                                                               <span style='font-weight: 700; font-size: 13px;'>Código: " . $vlcategoria[$i]['codigoexterno'] . "</span>
	                                                                                                            </div>
	                                                                                                            <div class='' style='text-align: right;'>
	                                                                                                            ";

										echo "
	                                                                                                            </div>
	                                                                                                            <div class='push'></div>
	                                                                                                            <div style='border: none; height: 1px; margin-top: 5px; margin-bottom: 5px;></div> 
	                                                                                                            <div class='push'></div>
	                                                                                                            ";
										$grupoDesc = $vlcategoria[$i]['desconto'];
										$catDesc = $vlcategoria[$i]['categoria'];
									}
								}
							}

							echo "
											</div> ";
						}


				?>
			</div>

		</div>

	</div>

<?php

						break;

					case 21: //grupo de desconto - 1 coluna


?>

	<div class="bloco">
		<center>
			<h5 style="margin: 20px 0 10px 0; font-size:18px">Veja <strong>ofertas personalizadas</strong> para voc&ecirc;!</h5>
		</center>
		<div style="display: flow-root;">
			<div class="bloco" style="font-size: 14px;">
				<?php
						unset($OFERTASTKT);
						unset($misdiatkt);
						unset($PRDAGRUPO);
						unset($dados);
						unset($vlcategoria);
						unset($grupoDesc);
						unset($row);
						unset($catDesc);
						if ($log_emisdia != 'N') {
							$param = "and LOG_EMISDIA='S' and DAT_VALIDADE >= '" . date('Y-m-d') . "'";
						} else {
							$param = "and LOG_EMISDIA='N'";
						}
						$sql2 = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_cliente and COD_EMPRESA=" . $cod_empresa . " $param ORDER by COD_GERAL DESC limit 1;";
						$misdiatkt = mysqli_fetch_assoc(mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), $sql2));

						$OFERTASTKT = unserialize($misdiatkt['DES_TICKET']);


						foreach ($OFERTASTKT as $row) {
							$PRDAGRUPO[$row['desconto']][$row['categoria']][] = array(
								'categoria' => $row['categoria'],
								'codigoexterno' => $row['codigointerno'],
								'codigointerno' => $row['codigointerno'],
								'descricao' => $row['descricao'],
								'preco' => $row['preco'],
								'valorcomdesconto' => $row['valorcomdesconto'],
								'desconto' => $row['desconto'],
								'imagem' => $row['imagem']
							);
						}

						foreach ($PRDAGRUPO as $key => $dados) {

							foreach ($dados as $categoria => $vlcategoria) {
								//echo '<pre>';
								//echo $categoria.'<br>';       
								//echo '<pre>';  

								for ($i = 0; $i < count($vlcategoria); $i++) {

									if ($grupoDesc != $vlcategoria[$i]['desconto']) {
										echo "
														<hr style='border: none; height: 1px; color: #000; background-color: #000; margin-top: 10px;'/>
														";
									}

									echo "
														<div style='width: 70%; float: left; text-align: left;'> ";

									if ($catDesc != $vlcategoria[$i]['categoria']) {
										echo "
														<div class='push10'></div>
													    <span style='font-weight: 900;'><small>" . mb_strtoupper($vlcategoria[$i]['categoria'], 'UTF-8') . "</small></span> 
														<div class='push5'></div> ";
									}

									echo "		   
														   <div class='push1'></div>
														   <span style='font-weight: 700;'><small>" . $vlcategoria[$i]['descricao'] . "</small></span>
																<div class='push2'></div>
														   <span style='font-weight: 700; font-size: 13px;'>Código: " . $vlcategoria[$i]['codigoexterno'] . "</span>
														</div>
														";

									if ($grupoDesc != $vlcategoria[$i]['desconto']) {
										echo "
														<div class='' style='text-align: right;'>
														<span style='font-weight: 900; font-size: 50px; letter-spacing: -1px; margin: 0;'>" . $vlcategoria[$i]['desconto'] . "%</span>
														<br/><span style='background-color: #000; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span>
														</div>";
									}

									echo "
														
														<div class='push'></div>
														<div style='border: none; height: 1px; margin: 0;></div> 
														<div class='push'></div>
														";
									$grupoDesc = $vlcategoria[$i]['desconto'];
									$catDesc = $vlcategoria[$i]['categoria'];
								}
							}
						}


				?>
			</div>

		</div>

	</div>


<?php

						break;

					case 22: //texto livre
?>
	<div class="push10"></div>
	<div class="bloco txt">

		<?php echo html_entity_decode($qrListaModelos['DES_TEXTO']); ?>

	</div>
	<?php
						break;
					case 23: //Aniversariante

						$mostraMsgAniv = 'none';

						$sqlMsg = "SELECT * FROM COMUNICACAO_MODELO_TKT WHERE COD_EMPRESA = $cod_empresa LIMIT 1";
						// echo($sql);
						$arrayMsg = mysqli_query(connTemp($cod_empresa, ""), $sqlMsg);

						$qrBuscaComunicacao = mysqli_fetch_assoc($arrayMsg);

						$temMsg = mysqli_num_rows($arrayMsg);

						$msg = $qrBuscaComunicacao['DES_TEXTO_SMS'];

						$TEXTOENVIO = str_replace('<#NOME>', $nome, $msg);
						$TEXTOENVIO = str_replace('<#SALDO>', fnValor($result['CREDITO_DISPONIVEL'], 2), $TEXTOENVIO);
						$TEXTOENVIO = str_replace('<#NOMELOJA>',  $result['NOM_FANTASI'], $TEXTOENVIO);
						$TEXTOENVIO = str_replace('<#ANIVERSARIO>', $result['DAT_NASCIME'], $TEXTOENVIO);
						$TEXTOENVIO = str_replace('<#DATAEXPIRA>', fnDataShort($result['DAT_EXPIRA']), $TEXTOENVIO);
						$TEXTOENVIO = str_replace('<#EMAIL>', $result['DES_EMAILUS'], $TEXTOENVIO);
						$msgsbtr = nl2br($TEXTOENVIO, true);
						$msgsbtr = str_replace('<br />', ' \n ', $msgsbtr);
						$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);


						switch ($qrBuscaComunicacao['COD_CTRLENV']) {

							case '7':

								if ($mes_hoje == $mes_nascime && $dia_hoje == $dia_nascime) {
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

						if ($mostraMsgAniv == 'block') {


	?>
		<div class="bloco">
			<div class="image-container text-center">
				<p class="f18"><?= $msgsbtr ?></p>
			</div>
		</div>
<?php

							echo "<div class='push20'></div>";
						}

						break;
				}

				//variaveis de controle
				//fnEscreve($qrListaModelos['COD_TEMPLATE']." - ".$qrListaModelos['COD_BLTEMPL']."_".$qrListaModelos['COD_REGISTR']);

?>
<script>
	$(".txt").find("a").attr("target", "_blank");
</script>
<?php
			}
		} else {

?>
<div class="push100"></div>
<center>
	<h2>Não há template configurada.</h2>
</center>
<?php

		}

		//Grava ticket
		//fnEscreve("TODOS - ".substr($produtoHabito.$produtoOferta.$produtoLista,0,-1));
		/*        
				$todosProdutos = substr($produtoHabito.$produtoOferta.$produtoLista,0,-1);	
				$opcao = "CAD";
				$cod_ticket = 0;
				$cod_maquina = 0;
				//$cod_cadastr = $_SESSION["SYS_COD_USUARIO"];
				$cod_cadastr = 4;

				$sql = "CALL SP_ALTERA_TICKET (
				'".$cod_ticket."', 
				'".$cod_cliente."', 
				'".$cod_empresa."', 
				'".$cod_loja."', 
				'".$cod_maquina."', 
				'".$cod_cadastr."', 
				'".$todosProdutos."', 
				'".$opcao."'    
				) ";

				$ROWsql= mysqli_query(connTemptkt($connAdm->connAdm(),$cod_empresa,""),trim($sql));
				$arrayretorno= mysqli_fetch_assoc($ROWsql);
				*/

?>
</ul>
</div>

<script type="text/javascript">
	<?php
	if ($_GET['print'] != 'no' && $temTemplate > 0) {
	?>

		$(document).ready(function() {
			$(window).load(function() {
				window.print();
			});
		});

	<?php
	}

	?>
</script>


<?php
/*exemplo de canal
  1- PDV virtual
  2- APP
  3- TOTEM
  4- frente de caixa
  
 LOG_VISUPDV
LOG_VISUAPP
LOG_VISUTOTEM
LOG_VISUPDVVITUAL 
*/
$Canal = $_GET['ch'];
switch ($Canal) {
	case 1: //PDV virtual
		$update = ",LOG_VISUPDVVIRTUAL='1'";
		break;
	case 2: //app
		$update = ",LOG_VISUAPP='1'";
		break;
	case 3: //totem
		$update = ",LOG_VISUTOTEM='1'";
		break;
	default:
		$update = ",LOG_VISUPDV='1'";
		break;
}
//gravar se o tkt foi visualizado
$sqlupdatevisualizacao = "UPDATE ticket SET LOG_VISUALIZACAO='1' $update WHERE  COD_TICKET='" . $misdiatkt['COD_TICKET'] . "'";
// echo $sqlupdatevisualizacao;
//echo $sqlupdatevisualizacao;
$rwupdatevisualizacao = mysqli_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), $sqlupdatevisualizacao);

//fnEscreve($OFERTADESTAQUE[$i]['imagem']);
LOG_DB(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), connTemptkt($connAdm->connAdm(), $cod_empresa, ""));
process_kill(connTemptkt($connAdm->connAdm(), $cod_empresa, ""));
cache_query(connTemptkt($connAdm->connAdm(), $cod_empresa, ""), 1);
?>

</body>

</html>