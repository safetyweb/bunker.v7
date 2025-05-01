<?php
include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$hoje = "";
$dias30 = "";
$tip_roi = "";
$dat_ini = "";
$dat_fim = "";
$listaCampanhas = "";
$cod_campanhas = "";
$canais = "";
$caseCamp = "";
$caseCampInat = "";
$concatOnesCamp = "";
$andEntreguesSms = "";
$andEntregues = "";
$andEntreguesAlt = "";
$filtroVal = "";
$caseNRecebidosSms = "";
$caseNRecebidosEmail = "";
$dat_iniFiltro = "";
$dat_fimFiltro = "";
$caseExtraResgAc = "";
$caseExtraResgGc = "";
$caseExtraAc = "";
$caseExtraGc = "";
$caseCampsGc = "";
$caseComprasSemCom = "";
$caseCampFull = "";
$caseCampCont = "";
$caseCampLista = "";
$caseCampCliSms = "";
$caseCampCliEmail = "";
$caseCampCliEmailUni = "";
$msgRetorno = "";
$msgTipo = "";
$cod_tpusuario = "";
$log_estatus = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$get = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$val_mbruta = "";
$des_logo = "";
$sqlVerifica = "";
$arrayVer = [];
$verifica = "";
$anulaEmail = "";
$anulaSms = "";
$camposNRecebidos = "";
$sqlPainel = "";
$sqlPainelGC = "";
$sqlPainel2 = "";
$arrayPainel = [];
$arrayPainel2 = [];
$arrayPainelGC = [];
$total_venda = "";
$total_itens = "";
$total_resgate = "";
$qtd_compras = 0;
$totResgAc = "";
$qtdTransResgAc = 0;
$qtdCliResgAc = 0;
$valTotVendaResgAc = "";
$qrPainel = "";
$total_itens_resgate = "";
$qtd_compras_total = 0;
$qtdCliHibridoAc = 0;
$qrPainelGC = "";
$total_vendaGC = "";
$total_itensGC = "";
$total_itens_resgateGC = "";
$total_resgateGC = "";
$qtd_comprasGC = 0;
$qtd_compras_totalGC = 0;
$totResgAcGC = "";
$qtdTransResgAcGC = 0;
$qtdCliResgAcGC = 0;
$valTotVendaResgAcGC = "";
$qrPainel2 = "";
$qtd_inativo = 0;
$dias_inativo = "";
$sql1 = "";
$sql1GC = "";
$sql2 = "";
$sql2GC = "";
$sql3 = "";
$sqlCampanha = "";
$arrayCampanha = [];
$arrayQuery1 = [];
$arrayQuery1GC = [];
$arrayQuery2 = [];
$arrayQuery2GC = [];
$arrayQuery3 = [];
$qtd_publico = 0;
$canais_com = "";
$campanhas = "";
$qrCamp = "";
$qtd_publico_tot = 0;
$qrCampGC = "";
$qtd_publicoGC = 0;
$canais_comGC = "";
$qrDescamp = "";
$fimCampanha = "";
$qrTotal = "";
$qrTotalAlvo = "";
$qrTotalGC = "";
$tot_clientes_uni = "";
$tot_clientes_uniGC = "";
$tot_clientes_uni_alvo = "";
$sqlCont = "";
$sqlSmsEmail = "";
$sqlSms = "";
$sqlEmail = "";
$arrayCont = [];
$arraySmsEmail = [];
$arraySms = [];
$arrayEmail = [];
$qrSms = "";
$qrEmail = "";
$tot_ativos = "";
$tot_lista = "";
$qrCont = "";
$invest = "";
$val_totvenda = "";
$qrSmsEmail = "";
$retorno = "";
$roi = "";
$sqlContrl = "";
$arrayContrl = [];
$tot_controle = "";
$qrContrl = "";
$sqlNrecebidos = "";
$arrayNrecebidos = [];
$qrNrec = "";
$tot_nRecebeu = "";
$sqlComprasSemCom = "";
$arrayComprasSemCom = [];
$qrCompraSemCom = "";
$tot_comprasSemCom = "";
$pct_engajamentoAcGC = "";
$pct_engajamentoCtGC = "";
$tot_comprasSemComGC = "";
$tot_nRecebeuGC = "";
$TMAcGC = "";
$GMAcGC = "";
$TMAcGeralGC = "";
$GMAcGeralGC = "";
$pctTotResgAcGC = "";
$percVvrAcGC = "";
$pctVvrFaturAcGC = "";
$TMResgAcGC = "";
$GMResgAcGC = "";
$VRMTransAcGC = "";
$VRMCliAcGC = "";
$itensTransacGC = "";
$clientesTransacGC = "";
$itensTransacResgGC = "";
$clientesTransacResgGC = "";
$pctTransResgGC = "";
$pctCliResgGC = "";
$pctItensResgGC = "";
$pctInvestGC = "";
$pctCliUniGC = "";
$pctCliComprasGC = "";
$pctInativosGC = "";
$qtd_inativoGC = 0;
$retornoGC = "";
$roiGC = "";
$total_itens_srGC = "";
$fatGAGC = "";
$clientesTransac_sr = "";
$total_itens_sr = "";
$qtdCliResgAc_sr = 0;
$TMAc = "";
$GMAc = "";
$TMAcGeral = "";
$GMAcGeral = "";
$pctTotResgAc = "";
$percVvrAc = "";
$pctVvrFaturAc = "";
$TMResgAc = "";
$GMResgAc = "";
$VRMTransAc = "";
$VRMCliAc = "";
$itensTransac = "";
$clientesTransac = "";
$itensTransacResg = "";
$clientesTransacResg = "";
$pctItensResg = "";
$pctInvest = "";
$pctCliUni = "";
$pctCliCompras = "";
$pctInativos = "";
$fatGA = "";
$pctCliUniAlvo = "";
$TMCt = "";
$GMCt = "";
$vendasControle = "";
$fatGC = "";
$pctMargem = "";
$incrementoMargem = "";
$sqlExtraAC = "";
$arrayExtraAC = [];
$qrExtraAC = "";
$bonus_extrasAC = "";
$bonus_resgatadosAC = "";
$qtd_cliente_extrasAC = 0;
$qtd_clientes_resgatadosAC = 0;
$sqlExtraGC = "";
$arrayExtraGC = [];
$qrExtraGC = "";
$bonus_extrasGC = "";
$bonus_resgatadosGC = "";
$qtd_cliente_extrasGC = 0;
$qtd_clientes_resgatadosGC = 0;
$pct_engajamentoCt = "";
$pctGrupo = "";
$fatGrupoControle = "";
$resultado = "";
$percResultado = "";
$roiComparativo = "";
$alinhaAcao = "";
$colAcao = "";
$balaoAcao = "";
$displayCont = "";
$formBack = "";
$txtCamp = "";
$pct_engajamentoAc = "";
$qtd_compras_total_sr = 0;
$pctTransResg = "";
$pctCliResg = "";
$vendasGaSr = "";
$itensTransac_sr = "";
$qtdTransCli_sr = 0;
$pctCliExGa = "";
$pctCliBonGa = "";
$concedidosAc = "";
$resgatadosAc = "";
$vendasGcSr = "";
$qtdCliGcSr = 0;
$qtdTransGcSr = 0;
$pctTransGcSr = "";
$itensTransacGCSr = "";
$clientesTransacGCSr = "";
$pctCliExGC = "";
$pctCliBonGC = "";
$concedidosgc = "";
$resgatadosgc = "";
$fatGCProporcional = "";


// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = 1;
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));



$tip_roi = fnDecode(@$_POST['TIP_ROI_PESQ']);
$dat_ini = fnDataSql(@$_POST['DAT_INI_PESQ']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM_PESQ']);
$listaCampanhas = json_decode(@$_POST['LISTA_CAMPANHAS'], true);
// echo "<pre>";
// fnEscreve(@$_POST['LISTA_CAMPANHAS']);
// print_r($listaCampanhas);
// echo "</pre>";

$cod_campanhas = "";
$canais = "";
$caseCamp = "";
$caseCampInat = "";
$concatOnesCamp = "";

$andEntreguesSms = "AND CASE
				        WHEN cli_list.cod_cconfirmacao = '1' THEN '1'
				        WHEN cli_list.cod_sconfirmacao = '1' THEN '1'
				        ELSE '0'
				        END IN ( 1, 1 )";


if ($tip_roi == 0) {

	// 26/05/2021 - Maurice pediu pra fazer em cima dos entregues
	// $andEntregues = "AND cli_list.cod_leitura IN('1','0')
	//                     and cli_list.bounce = '0'
	//                     and cli_list.SPAM = '0'";

	// $andEntreguesSms = "AND CASE
	// 										WHEN cli_list.cod_cconfirmacao = '1'
	// 				            THEN '1'
	// 											WHEN cli_list.cod_sconfirmacao = '1'
	// 				            THEN '1'
	// 				              ELSE '0'
	// 				            END IN ( 1, 1 )";

	$andEntregues = "AND cli_list.ENTREGUE = 1";

	$andEntreguesAlt = "AND cli_list_email.ENTREGUE = 1";
	// $andEntreguesSms = "";

	$filtroVal = "";
} else if ($tip_roi == 1) {

	// $andEntreguesSms = "AND CASE
	// 										WHEN cli_list.cod_cconfirmacao = '1'
	// 				            THEN '1'
	// 											WHEN cli_list.cod_sconfirmacao = '1'
	// 				            THEN '1'
	// 				              ELSE '0'
	// 				            END IN ( 1, 1 )";

	$andEntregues = "AND cli_list.CLICK IN('1')
	                     and cli_list.bounce = '0'
	                     and cli_list.SPAM = '0'";

	$andEntreguesAlt = "AND cli_list_email.CLICK IN('1')
	                     and cli_list_email.bounce = '0'
	                     and cli_list_email.SPAM = '0'";

	$filtroVal = $andEntregues;
} else {

	// $andEntreguesSms = "AND CASE
	// 										WHEN cli_list.cod_cconfirmacao = '1'
	// 				            THEN '1'
	// 											WHEN cli_list.cod_sconfirmacao = '1'
	// 				            THEN '1'
	// 				              ELSE '0'
	// 				            END IN ( 1, 1 )";

	$andEntregues = "AND cli_list.cod_optout_ativo = '0' 
						 AND cli_list.cod_leitura=1 
						 AND cli_list.bounce = '0' 
						 AND cli_list.SPAM = '0'";

	$andEntreguesAlt = "AND cli_list_email.cod_optout_ativo = '0' 
						 AND cli_list_email.cod_leitura=1 
						 AND cli_list_email.bounce = '0' 
						 AND cli_list_email.SPAM = '0'";

	$filtroVal = $andEntregues;
}

$caseNRecebidosSms = "";
$caseNRecebidosEmail = "";

for ($i = 0; $i < count($listaCampanhas); $i++) {
	$cod_campanhas .= $listaCampanhas[$i]['COD_CAMPANHA'] . ",";
	$canais .= $listaCampanhas[$i]['DES_CANAL'] . ",";
	$dat_iniFiltro .= $listaCampanhas[$i]['DAT_INI_CONSULTA'] . ",";
	$dat_fimFiltro .= $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . ",";

	if ($listaCampanhas[$i]['DES_CANAL'] == 'SMS') {

		$caseCamp .= "
						  WHEN          
						  Date(v.dat_cadastr_ws) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "' AND
						  ROW (v.cod_cliente,v.COD_EMPRESA)in (SELECT DISTINCT cli_list.cod_cliente,cli_list.cod_EMPRESA
		                                   							FROM   sms_lista_ret cli_list
								                                    WHERE  cli_list.cod_empresa = v.cod_empresa
								                                           AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
								                                           AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
								                                           AND CASE WHEN cli_list.cod_cconfirmacao = '1'
								                                               THEN '1' WHEN cli_list.cod_sconfirmacao= '1'
								                                               THEN '1' ELSE '0' end IN ( 1, 1 ) 					                                            
								                                   ) THEN '1'";


		$caseExtraResgAc .= "when 		
								    cod_credlot > 0
				                AND val_credito != val_saldo   
							 		 AND Date(dat_reproce) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
							       AND ROW(cod_cliente,COD_EMPRESA) IN (SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
														                           FROM   sms_lista_ret cli_list
														                           WHERE  cli_list.cod_empresa = $cod_empresa
														                                  AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
														                                  AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
														                                  AND CASE
														                                        WHEN cli_list.cod_cconfirmacao = '1' THEN '1'
														                                        WHEN cli_list.cod_sconfirmacao = '1' THEN '1'
														                                        ELSE '0'
														                                      END IN ( 1, 1 )) 
							      THEN '1'";

		$caseExtraResgGc .= "WHEN 
							    Date(dat_reproce) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
                          AND Row(cod_cliente, cod_empresa) IN (SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
							                                   			FROM   lista_controle_cliente cli_list
							                                  			 WHERE  cli_list.cod_empresa = $cod_empresa
							                                         	 AND cli_list.des_comunica = 'SMS'
							                                         	 AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
							                                         	 )
							                     THEN '1'";

		$caseExtraAc .= " when 		
											     cod_credlot > 0     
										 		 AND Date(dat_reproce) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
										       AND ROW(cod_cliente,COD_EMPRESA) IN (SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
																	                           FROM   sms_lista_ret cli_list
																	                           WHERE  cli_list.cod_empresa = $cod_empresa
																	                                  AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
																	                                  AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
																	                                  AND CASE
																	                                        WHEN cli_list.cod_cconfirmacao = '1' THEN '1'
																	                                        WHEN cli_list.cod_sconfirmacao = '1' THEN '1'
																	                                        ELSE '0'
																	                                      END IN ( 1, 1 )) 
										      THEN '1'";

		$caseExtraGc .= "WHEN   Date(dat_reproce) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
		                            AND Row (cod_cliente, cod_empresa) IN (SELECT DISTINCT cli_list.cod_cliente,
																                                               cli_list.cod_empresa
																                               FROM lista_controle_cliente cli_list
																										  WHERE
																                                  cli_list.cod_empresa = cod_empresa
																                                  AND cli_list.des_comunica = 'SMS'
																                                  AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
																                                  ) THEN '1'";

		$caseCampsGc .= "WHEN
						      Date(v.dat_cadastr_ws) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "' AND
							    Row (v.cod_cliente, v.cod_empresa) IN (  SELECT DISTINCT cli_list.cod_cliente,
	                                   														cli_list.cod_empresa
				                                                      FROM   lista_controle_cliente cli_list
				                                                        WHERE  cli_list.cod_empresa = v.cod_empresa
				                                                           AND cli_list.des_comunica = 'SMS'
				                                                           AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
				                                                          
																							) THEN '1'";

		$caseComprasSemCom .= "when
							          Date(v.dat_cadastr_ws) '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "' AND 
										 row(v.cod_cliente,v.COD_EMPRESA) IN (SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
												                                     FROM   sms_lista_ret cli_list
												                                     WHERE  cli_list.cod_empresa = v.cod_empresa
												                                            AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
												                                            AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
												                                            AND CASE
												                                                  WHEN cli_list.bounce = '1'   THEN '1'
												                                                  WHEN cli_list.cod_optout_ativo = '1'   THEN '1'
												                                                  ELSE '0'
												                                                END IN ( 1, 1 )
					                                   											  GROUP  BY cli_list.cod_campanha)
					              	then '1'";

		$caseCampFull .= "
						  WHEN          
						  Date(v.dat_cadastr_ws) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "' AND
						  ROW (v.cod_cliente,v.COD_EMPRESA)in (SELECT DISTINCT cli_list.cod_cliente,cli_list.cod_EMPRESA
		                                   							FROM   sms_lista_ret cli_list
								                                    WHERE  cli_list.cod_empresa = v.cod_empresa
								                                           AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
								                                           AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
								                                           					                                            
								                                   ) THEN '1'";


		$caseCampInat .= "
						 WHEN               
										 CASE
					                     WHEN cli_list.cod_cconfirmacao = '1' and 
												     Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
													  THEN '1'
					                     WHEN cli_list.cod_sconfirmacao = '1' and 
												Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
												THEN '1'
					                     ELSE '0' end IN ( 1, 1 )
					               AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
					               AND cli_list.cod_cliente NOT IN(SELECT cod_cliente
					                                                           FROM   vendas C
					                                               WHERE
																                   C.cod_cliente = cli_list.cod_cliente
																                   AND C.cod_avulso = 2
																                   AND Date(C.dat_cadastr) >= (
															                       Date(Adddate('" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "',
															                                INTERVAL - (SELECT
															                                qtd_inativo
															                                FROM
															                                frequencia_cliente
															                                     WHERE cod_empresa=
															                                     cli_list.cod_empresa)
															                                     DAY)))		
																                   AND Date(C.dat_cadastr) <= '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "')
					               AND Date(B.dat_ultcompr) = '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
					      then '1'";

		$caseCampCont .= "when  
								   date(LOT_SMS.DAT_AGENDAMENTO) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "' 
							      AND CAMP.COD_CAMPANHA = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
							      AND CAMP.LOG_PROCESSA_SMS = 'S' 
							      AND LOT_SMS.LOG_ENVIO = 'S' 
							      AND VAL.DES_CANAL = 'SMS' 
							    --	AND 1=0  
							   then '1'";

		$caseNRecebidosSms = "LEFT JOIN sms_lista_ret smslista ON  case 
							     when  
									      smslista.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
							           AND Date(smslista.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
							           AND CASE
							                 WHEN smslista.bounce = '1' THEN '1'
							                 WHEN smslista.cod_optout_ativo = '1' THEN '1'
							                 ELSE '0' END IN ( 1, 1 )
							     				then '1'   
							     		ELSE '0' END  IN (1)";
	} else if ($listaCampanhas[$i]['DES_CANAL'] == 'Email') {

		$caseCamp .= "
						  WHEN 
						  Date(v.dat_cadastr_ws) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'   AND
						  ROW (v.cod_cliente,v.COD_EMPRESA) in (SELECT DISTINCT cli_list.cod_cliente,cli_list.cod_EMPRESA
										                                    FROM   email_lista_ret cli_list
										                                    WHERE  cli_list.cod_empresa = $cod_empresa
										                                           AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
										                                           AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
										                                           $andEntregues) 
																						THEN '1' ";

		$caseExtraResgAc .= "when 		
								    cod_credlot > 0
				                AND val_credito != val_saldo   
							 		 AND Date(dat_reproce) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
							       AND ROW(cod_cliente,COD_EMPRESA) IN (SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA                     
																				        FROM   email_lista_ret cli_list
																				        WHERE  cli_list.cod_empresa = $cod_empresa
																				               AND CASE
																				                     WHEN cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
																				                          AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
																				                          AND cli_list.entregue = 1 
																											THEN '1' ELSE '0'
																				                   END IN ( 1 )) 
										      THEN '1'";

		$caseExtraResgGc .= "WHEN 
							    Date(dat_reproce) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
                          AND Row(cod_cliente, cod_empresa) IN (SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
							                                   			FROM   lista_controle_cliente cli_list
							                                  			 WHERE  cli_list.cod_empresa = $cod_empresa
							                                         	 AND cli_list.des_comunica = 'EMAIL'
							                                         	 AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
							                                         	 )
							                     THEN '1'";

		$caseExtraAc .= "when 		
							     cod_credlot > 0     
						 		 AND Date(dat_reproce) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
						       AND ROW(cod_cliente,COD_EMPRESA) IN (SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA                     
																			        FROM   email_lista_ret cli_list
																			        WHERE  cli_list.cod_empresa = $cod_empresa
																			               AND CASE
																			                     WHEN cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
																			                          AND Date(cli_list.dat_cadastr) BETWEEN	'" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
																			                          AND cli_list.entregue = 1 
																										THEN '1' ELSE '0'
																			                   END IN ( 1 )) 
						      THEN '1'";

		$caseExtraGc .= "WHEN  Date(dat_reproce) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
		                          AND Row (cod_cliente, cod_empresa) IN
		                              (SELECT DISTINCT cli_list.cod_cliente,
		                                               cli_list.cod_empresa
		                               FROM lista_controle_cliente cli_list WHERE
		                                  cli_list.cod_empresa = cod_empresa
		                                  AND cli_list.des_comunica = 'EMAIL'
		                                  AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
		                                  ) THEN '1'";

		$caseCampsGc .= "WHEN   Date(v.dat_cadastr_ws) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'   AND
									  Row (v.cod_cliente, v.cod_empresa) IN (SELECT DISTINCT cli_list.cod_cliente,
                                           														cli_list.cod_empresa
								                                    FROM   lista_controle_cliente cli_list
								                                    WHERE  cli_list.cod_empresa = v.cod_empresa									                                         
								                                           AND cli_list.des_comunica = 'EMAIL'
								                                           AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
								                                           
	                                                              ) THEN '1'";

		$caseComprasSemCom .= "when
						             Date(v.dat_cadastr_ws) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "' 
										 and row(v.cod_cliente,v.COD_EMPRESA) IN (SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
											                                       FROM   email_lista_ret cli_list
											                                       WHERE  cli_list.cod_empresa = $cod_empresa
											                                              AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
											                                              AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
											                                              AND CASE
											                                                    WHEN cli_list.bounce IN ( 1, 2 ) THEN '1'
											                                                    WHEN cli_list.spam = '1' THEN '1'
											                                                 WHEN cli_list.cod_optout_ativo =  '1' THEN '1' ELSE '0'
											                                                  END IN ( 1, 1 )
											                                       GROUP  BY cli_list.cod_campanha)
									 then '1'";

		$caseCampFull .= "
						  WHEN 
						  Date(v.dat_cadastr_ws) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'   AND
						  ROW (v.cod_cliente,v.COD_EMPRESA) in (SELECT DISTINCT cli_list.cod_cliente,cli_list.cod_EMPRESA
										                                    FROM   email_lista_ret cli_list
										                                    WHERE  cli_list.cod_empresa = $cod_empresa
										                                           AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
										                                           AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "') 
																						THEN '1' ";

		$caseCampInat .= "WHEN
							         Date(cli_list_email.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
					               AND cli_list_email.cod_empresa = $cod_empresa
					               $andEntreguesAlt
					               AND cli_list_email.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
					               AND cli_list_email.cod_cliente NOT IN(SELECT cod_cliente
					                                               FROM   vendas C
					                                               WHERE
																                   C.cod_cliente = cli_list_email.cod_cliente
																                   AND C.cod_avulso = 2
																                   AND Date(C.dat_cadastr) >= ( Date(Adddate('" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "', INTERVAL - (SELECT qtd_inativo
																																			                                FROM
																																			                                frequencia_cliente
																																			                                     WHERE cod_empresa=
																																			                                     cli_list_email.cod_empresa)
																																			                                     day)))
																                   AND Date(C.dat_cadastr) <= '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "')
					               AND Date(B.dat_ultcompr) = '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
							        
							 then '1'";

		$caseCampCont .= "when
								     DATE(LOT_EMAIL.DAT_AGENDAMENTO) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
								      AND LOT_EMAIL.LOG_ENVIO = 'S' 
								      AND CAMP.COD_CAMPANHA = " . $listaCampanhas[$i]['COD_CAMPANHA'] . " 
								      AND CAMP.LOG_PROCESSA = 'S' 
								      AND VAL.DES_CANAL = 'EMAIL' 
									--	AND 1=0 
							  then '1' ";

		$caseNRecebidosEmail = "LEFT JOIN email_lista_ret emaillista ON case
								when
						           emaillista.cod_campanha " . $listaCampanhas[$i]['COD_CAMPANHA'] . " 
						           AND Date(emaillista.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
						           AND CASE
						                 WHEN emaillista.bounce IN ( 1, 2 ) THEN '1'
						                 WHEN emaillista.spam = '1' THEN '1'
						                 WHEN emaillista.cod_optout_ativo = '1' THEN '1'
						                 ELSE '0' END IN ( 1, 1 )
											then '1'				   
								       ELSE '0' END IN (1)";
	}

	$caseCampLista .= "when
										 cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . " AND
					                Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
		                    then '1'";

	$caseCampCliSms .= "WHEN 
									cli_list.cod_cconfirmacao = '1' 
									  AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
		                        AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
									THEN '1'
		                     WHEN 
										cli_list.cod_sconfirmacao = '1'
										AND cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
			                     AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
									THEN '1'";

	$caseCampCliEmail .= "WHEN 
									      cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
			                       AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
			                        $andEntregues
										THEN '1'";

	$caseCampCliEmailUni .= "WHEN 
									      cli_list.cod_campanha = " . $listaCampanhas[$i]['COD_CAMPANHA'] . "
			                       AND Date(cli_list.dat_cadastr) BETWEEN '" . $listaCampanhas[$i]['DAT_INI_CONSULTA'] . "' AND '" . $listaCampanhas[$i]['DAT_FIM_CONSULTA'] . "'
			                        	AND cli_list.cod_optout_ativo = '0' 
											 AND cli_list.cod_leitura IN('1','0') 
											 AND cli_list.bounce = '0' 
											 AND cli_list.SPAM = '0'
										THEN '1'";


	$concatOnesCamp .= "1,";
}

$concatOnesCamp = rtrim($concatOnesCamp, ',');
$cod_campanhas = rtrim($cod_campanhas, ',');
$canais = rtrim($canais, ',');

// fnEscreve($canais);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_tpusuario = fnLimpaCampoZero(@$_POST['COD_TPUSUARIO']);
		$log_estatus = fnLimpaCampo(@$_POST['LOG_ESTATUS']);
		if (@$_POST['COD_CAMPANHAS_PESQ'] != "") {
			$dat_ini = fnDataSql(@$_POST['DAT_INI']);
			$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
			$canais = fnDecode(@$_POST['CANAIS']);
			$tip_roi = fnDecode(@$_POST['TIP_ROI_PESQ']);
			$cod_campanhas = fnDecode(@$_POST['COD_CAMPANHAS_PESQ']);
		}

		// fnEscreve(fnDecode(@$_POST['COD_CAMPANHAS_PESQ']));
		// fnEscreve($cod_campanhas);



		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);

	if ($get == 1) {

		$tip_roi = fnDecode(@$_POST['TIP_ROI']);
		$listaCampanhas = json_decode(@$_POST['LISTA_CAMPANHAS'], true);
		// echo "<pre>";
		// print_r($listaCampanhas);
		// echo "</pre>";

		$cod_campanhas = "";
		// $canais = "";

		for ($i = 0; $i < count($listaCampanhas); $i++) {
			$cod_campanhas .= $listaCampanhas[$i]['COD_CAMPANHA'] . ",";
			// $canais .= $listaCampanhas[$i][DES_CANAL].",";
		}

		$cod_campanhas = rtrim($cod_campanhas, ',');
		// $canais = rtrim($canais,',');

	}

	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, VAL_MBRUTA, DES_LOGO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$val_mbruta = $qrBuscaEmpresa['VAL_MBRUTA'] / 100;
		$des_logo = $qrBuscaEmpresa['DES_LOGO'];
	}

	$sqlVerifica = "SELECT 1 FROM campanha WHERE cod_empresa = $cod_empresa AND
						TIP_CAMPANHA IN (SELECT TIP_CAMPANHA FROM empresas WHERE cod_empresa = $cod_empresa AND TIP_CAMPANHA='13')
						AND LOG_ATIVO='S'";

	// fnEscreve($sqlVerifica);

	$arrayVer = mysqli_query(connTemp($cod_empresa, ''), $sqlVerifica);

	$verifica = mysqli_num_rows($arrayVer);
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//FILTRO POR CANAL

$anulaEmail = "";
$anulaSms = "";

if ($canais == "SMS") {
	$anulaEmail = "AND 1 = 0";
	$anulaSms = "";
	$camposNRecebidos = "Count(distinct smslista.cod_cliente)";
} else if ($canais == "Email") {
	$anulaEmail = "";
	$anulaSms = "AND 1 = 0";
	$camposNRecebidos = "Count(distinct emaillista.cod_cliente)";
} else {
	$camposNRecebidos = "Count(distinct smslista.cod_cliente) + Count(distinct emaillista.cod_cliente)";
}

// fnEscreve($verifica);

if ($verifica > 0) {

	$sqlPainel = "SELECT 	 
		 SUM(VAL_TOTVENDA)-SUM(VAL_DESCONTO) VAL_TOTVENDA,
		 SUM(VAL_RESGATE) VAL_RESGATE,
		 count(distinct cod_cliente) QTD_COMPRAS,
		 SUM(QTD_COMPRAS_TOTAL) QTD_COMPRAS_TOTAL, 	  
		 SUM(QTD_ITEM_TOTAL)   QTD_ITEM_TOTAL,
		 SUM(QTD_ITEM_COM_RESGATE) QTD_ITEM_COM_RESGATE,
		 SUM(QTD_VENDA_COM_RESGATE) QTD_VENDA_COM_RESGATE,
		 SUM(VL_TOTAL_VENDA_RESGATE) VL_TOTAL_VENDA_RESGATE,
       COUNT(distinct QTD_CLIEN_UNI_C_RESGATE) QTD_CLIENTE_UNICOS_COM_RESGATE,
       COUNT(DISTINCT QTD_CLIEN_HIBRIDO) QTD_CLIEN_HIBRIDO
			FROM (			
					SELECT v.cod_venda ,  
					       v.COD_EMPRESA,
					       val_totprodu VAL_TOTVENDA,
					       v.val_resgate VAL_RESGATE, 
					       v.VAL_DESCONTO VAL_DESCONTO, 
					       dat_cadastr_ws,
					        v.cod_cliente,
							 count(distinct v.cod_cliente) QTD_COMPRAS,	
							 Count(distinct v.cod_venda) QTD_COMPRAS_TOTAL,                        
					       Sum(qtd_produto) QTD_ITEM_TOTAL ,                                   
					    case  when v.val_resgate > '0.00'
						   then  Sum(item.qtd_produto) ELSE '0.00' end QTD_ITEM_COM_RESGATE,      
							case  when v.val_resgate > '0.00'  					                     
							       then  Count(distinct v.COD_VENDA) ELSE '0' end QTD_VENDA_COM_RESGATE,		     
					      case  when v.val_resgate > '0.00'                     
							       then   v.val_totprodu ELSE '0.00' end VL_TOTAL_VENDA_RESGATE,                  
					      case  when v.val_resgate > '0.00'
							       then  v.cod_cliente ELSE null END  QTD_CLIEN_UNI_C_RESGATE,
						  CASE WHEN  v.val_resgate  = '0.00' 
						  		   THEN  v.cod_cliente  ELSE NULL END QTD_CLIEN_HIBRIDO  
					FROM   vendas v
					inner JOIN itemvenda item ON v.cod_venda=item.COD_VENDA AND 
					                             v.COD_EMPRESA=item.COD_EMPRESA
					                            
					WHERE 
					       v.cod_empresa = $cod_empresa
					       AND v.cod_avulso = 2
					       AND v.cod_statuscred IN ( 0, 1, 2, 3, 4, 5, 7, 8, 9 )  
					       AND CASE
					            
					            $caseCamp 

					       ELSE '0' end IN ( $concatOnesCamp )
					             
					GROUP BY v.COD_VENDA																			
		) tempvendas_org
		GROUP BY COD_EMPRESA";

	// 		$sqlPainel = "SELECT 
	//        Sum(VAL_TOTPRODU)             VAL_TOTVENDA,
	//        Sum(val_resgate)              VAL_RESGATE,
	//        dat_cadastr_ws,
	//        Count(DISTINCT v.cod_cliente) QTD_COMPRAS,
	//        Count(v.cod_venda)            QTD_COMPRAS_TOTAL,

	//        (
	// 		   SELECT SUM(QTD_PRODUTO) FROM itemvenda item WHERE item.cod_venda IN(
	// 																										 SELECT RESG.COD_VENDA
	// 																								        FROM   vendas RESG
	// 																								        WHERE  Date(RESG.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
	// 																								               AND RESG.cod_empresa = v.cod_empresa
	// 																								               AND RESG.cod_avulso = 2
	// 																								               AND RESG.cod_statuscred IN ( 0, 1, 2, 3, 4, 5, 7, 8, 9 )
	// 																								               AND CASE
	// 																									               WHEN RESG.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 																									                             FROM   sms_lista_ret cli_list
	// 																									                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 																									                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 																									                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 																									                                        '$dat_ini' AND '$dat_fim'
	// 																									                                    $andEntreguesSms
	// 																									                                    $anulaSms
	// 																									                             GROUP  BY cli_list.cod_campanha)
	// 																									             THEN '1'
	// 																										           WHEN RESG.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 																														                             FROM   email_lista_ret cli_list
	// 																														                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 																														                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 																														                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 																														                                        '$dat_ini' AND '$dat_fim'
	// 																														                                    $andEntregues
	// 																														                                    $anulaEmail)  
	// 																									             THEN '1' ELSE '0' end IN ( 1, 1 ))) QTD_ITEM_TOTAL,
	// 			(
	// 		   SELECT SUM(QTD_PRODUTO) FROM itemvenda item WHERE item.cod_venda IN(
	// 																										 SELECT RESG.COD_VENDA
	// 																								        FROM   vendas RESG
	// 																								        WHERE  Date(RESG.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
	// 																								               AND RESG.cod_empresa = v.cod_empresa
	// 																								               AND RESG.cod_avulso = 2
	// 																								               AND RESG.val_resgate > '0.00'
	// 																								               AND RESG.cod_statuscred IN ( 0, 1, 2, 3, 4, 5, 7, 8, 9 )
	// 																								               AND CASE
	// 																									               WHEN RESG.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 																									                             FROM   sms_lista_ret cli_list
	// 																									                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 																									                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 																									                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 																									                                        '$dat_ini' AND '$dat_fim'
	// 																									                                    $andEntreguesSms
	// 																									                                    $anulaSms
	// 																									                             GROUP  BY cli_list.cod_campanha)
	// 																									             THEN '1'
	// 																										           WHEN RESG.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 																														                             FROM   email_lista_ret cli_list
	// 																														                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 																														                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 																														                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 																														                                        '$dat_ini' AND '$dat_fim'
	// 																														                                    $andEntregues
	// 																														                                    $anulaEmail)  
	// 																									             THEN '1' ELSE '0' end IN ( 1, 1 ))) QTD_ITEM_COM_RESGATE,																										 	
	//        (SELECT Count(RESG.val_resgate)
	//         FROM   vendas RESG
	//         WHERE  Date(RESG.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
	//                AND RESG.cod_empresa = v.cod_empresa
	//                AND RESG.cod_avulso = 2
	//                AND RESG.val_resgate > '0.00'
	//                AND RESG.cod_statuscred IN ( 0, 1, 2, 3,
	//                                             4, 5, 7, 8, 9 )
	//                AND CASE
	// 	               WHEN RESG.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 	                             FROM   sms_lista_ret cli_list
	// 	                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 	                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 	                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 	                                        '$dat_ini' AND '$dat_fim'
	// 	                                    $andEntreguesSms
	// 	                                    $anulaSms
	// 	                             GROUP  BY cli_list.cod_campanha)
	// 	             THEN '1'
	// 		           WHEN RESG.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 						                             FROM   email_lista_ret cli_list
	// 						                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 						                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 						                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 						                                        '$dat_ini' AND '$dat_fim'
	// 						                                    $andEntregues
	// 						                                    $anulaEmail)  
	// 	             THEN '1' ELSE '0' end IN ( 1, 1 )) QTD_VENDA_COM_RESGATE,
	//        (SELECT Sum(RESG.VAL_TOTPRODU)
	//         FROM   vendas RESG
	//         WHERE  Date(RESG.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
	//                AND RESG.cod_empresa = v.cod_empresa
	//                AND RESG.cod_avulso = 2
	//                AND RESG.val_resgate > '0.00'
	//                AND RESG.cod_statuscred IN ( 0, 1, 2, 3,
	//                                             4, 5, 7, 8, 9 )
	//                AND CASE
	// 	               WHEN RESG.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 	                             FROM   sms_lista_ret cli_list
	// 	                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 	                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 	                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 	                                        '$dat_ini' AND '$dat_fim'
	// 	                                    $andEntreguesSms
	// 	                                    $anulaSms
	// 	                             GROUP  BY cli_list.cod_campanha)
	// 	             THEN '1'
	// 		           WHEN RESG.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 						                             FROM   email_lista_ret cli_list
	// 						                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 						                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 						                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 						                                        '$dat_ini' AND '$dat_fim'
	// 						                                    $andEntregues
	// 						                                    $anulaEmail)  
	// 	             THEN '1' ELSE '0' end IN ( 1, 1 )) VL_TOTAL_VENDA_RESGATE,
	//        (SELECT Count(DISTINCT RESG.cod_cliente)
	//         FROM   vendas RESG
	//         WHERE  Date(RESG.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
	//                AND RESG.cod_empresa = $cod_empresa
	//                AND RESG.cod_avulso = 2
	//                AND RESG.val_resgate > '0.00'
	//                AND RESG.cod_statuscred IN ( 0, 1, 2, 3,
	//                                             4, 5, 7, 8, 9 )
	//               AND CASE
	// 	               WHEN RESG.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 	                             FROM   sms_lista_ret cli_list
	// 	                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 	                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 	                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 	                                        '$dat_ini' AND '$dat_fim'
	// 	                                    $andEntreguesSms
	// 	                                    $anulaSms
	// 	                             GROUP  BY cli_list.cod_campanha)
	// 	             THEN '1'
	// 		           WHEN RESG.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 						                             FROM   email_lista_ret cli_list
	// 						                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 						                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 						                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 						                                        '$dat_ini' AND '$dat_fim'
	// 						                                    $andEntregues
	// 						                                    $anulaEmail)  
	// 	             THEN '1' ELSE '0' end IN ( 1, 1 )) QTD_CLIENTE_UNICOS_COM_RESGATE
	// FROM   vendas v
	// WHERE  Date(v.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
	//        AND v.cod_empresa = $cod_empresa
	//        AND v.cod_avulso = 2
	//        AND v.cod_statuscred IN ( 0, 1, 2, 3, 4, 5, 7, 8, 9 )

	//          AND CASE
	//                WHEN v.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	//                              FROM   sms_lista_ret cli_list
	//                              WHERE  cli_list.cod_empresa = v.cod_empresa
	//                              		$anulaSms
	//                                     AND cli_list.cod_campanha IN ( $cod_campanhas )
	//                                     AND Date(cli_list.dat_cadastr) BETWEEN
	//                                         '$dat_ini' AND '$dat_fim'
	//                                     AND CASE
	//                                           WHEN cli_list.cod_cconfirmacao = '1'
	//                                         THEN '1'
	//                                           WHEN cli_list.cod_sconfirmacao = '1'
	//                                         THEN '1'
	//                                           ELSE '0'
	//                                         end IN ( 1, 1 )    GROUP  BY cli_list.cod_campanha)
	//              THEN '1'
	// 	           WHEN 	v.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 	                             FROM   email_lista_ret cli_list
	// 	                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 	                             		$anulaEmail
	// 	                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 	                                    AND Date(cli_list.dat_cadastr) BETWEEN  '$dat_ini' AND '$dat_fim'
	// 	                                    $andEntregues
	// 	                               GROUP  BY cli_list.cod_campanha)  
	//              THEN '1' ELSE '0' end IN ( 1, 1 )";

	$sqlPainelGC = "SELECT Sum(val_totvenda)                       VAL_TOTVENDA,
								       Sum(val_resgate)                        VAL_RESGATE,
								       Count(DISTINCT cod_cliente)             QTD_COMPRAS,
								       Sum(qtd_compras_total)                  QTD_COMPRAS_TOTAL,
								       Sum(qtd_item_total)                     QTD_ITEM_TOTAL,
								       Sum(qtd_item_com_resgate)               QTD_ITEM_COM_RESGATE,
								       Sum(qtd_venda_com_resgate)              QTD_VENDA_COM_RESGATE,
								       Sum(vl_total_venda_resgate)             VL_TOTAL_VENDA_RESGATE,
								       Count(DISTINCT qtd_clien_uni_c_resgate) QTD_CLIENTE_UNICOS_COM_RESGATE
								       
								FROM   (SELECT v.cod_venda,
								               v.cod_empresa,
								               val_totprodu                  VAL_TOTVENDA,
								               v.val_resgate                   VAL_RESGATE,
								               dat_cadastr_ws,
								               v.cod_cliente,
								               Count(DISTINCT v.cod_cliente) QTD_COMPRAS,
								               Count(DISTINCT v.cod_venda)   QTD_COMPRAS_TOTAL,
								               Sum(qtd_produto)              QTD_ITEM_TOTAL,
								               CASE  WHEN v.val_resgate > '0.00' THEN Sum(item.qtd_produto) ELSE '0.00' END  QTD_ITEM_COM_RESGATE,
								               CASE  WHEN v.val_resgate > '0.00' THEN Count(DISTINCT v.cod_venda)  ELSE '0'  END QTD_VENDA_COM_RESGATE,
								               CASE  WHEN v.val_resgate > '0.00' THEN v.val_totprodu ELSE '0.00'  END VL_TOTAL_VENDA_RESGATE,
								               CASE  WHEN v.val_resgate > '0.00' THEN v.cod_cliente ELSE NULL END  QTD_CLIEN_UNI_C_RESGATE
								        FROM   vendas v
								               INNER JOIN itemvenda item ON v.cod_venda = item.cod_venda AND v.cod_empresa = item.cod_empresa
								        WHERE  v.cod_empresa = $cod_empresa
								               AND v.cod_avulso = 2
								               AND v.cod_statuscred IN ( 0, 1, 2, 3,4, 5, 7, 8, 9 )
								             AND  CASE

								             	$caseCampsGc

										     ELSE '0'   END IN ( $concatOnesCamp )
											group	   BY v.cod_venda) tempvendas_org
								GROUP  BY cod_empresa ";
} else {

	$sqlPainel = "SELECT Sum(VAL_TOTPRODU-VAL_DESCONTO)             VAL_TOTVENDA,
						       Sum(val_resgate)              VAL_RESGATE,
						       dat_cadastr_ws,
						       Count(DISTINCT v.cod_cliente) QTD_COMPRAS,
						       Count(v.cod_venda)            QTD_COMPRAS_TOTAL,
						       ( 0 )                         QTD_VENDA_COM_RESGATE,
						       ( 0 )                         VL_TOTAL_VENDA_RESGATE,
						       ( 0 )                         QTD_CLIENTE_UNICOS_COM_RESGATE,
						       ( 0 ) 						 QTD_CLIEN_HIBRIDO
						FROM   vendas v
						WHERE  v.cod_empresa = $cod_empresa
						       AND v.cod_avulso = 2
						       AND v.cod_statuscred IN ( 0, 1, 2, 3,
						                                 4, 5, 7, 8, 9 )
						       AND CASE
					            
						            $caseCamp 

						       ELSE '0' end IN ( $concatOnesCamp )
						       
						";

	// $sqlPainel = "SELECT Sum(VAL_TOTPRODU)             VAL_TOTVENDA,
	// 	       Sum(val_resgate)              VAL_RESGATE,
	// 	       dat_cadastr_ws,
	// 	       Count(DISTINCT v.cod_cliente) QTD_COMPRAS,
	// 	       Count(v.cod_venda)            QTD_COMPRAS_TOTAL,
	// 	       ( 0 )                         QTD_VENDA_COM_RESGATE,
	// 	       ( 0 )                         VL_TOTAL_VENDA_RESGATE,
	// 	       ( 0 )                         QTD_CLIENTE_UNICOS_COM_RESGATE
	// 	FROM   vendas v
	// 	WHERE  Date(v.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
	// 	       AND v.cod_empresa = $cod_empresa
	// 	       AND v.cod_avulso = 2
	// 	       AND v.cod_statuscred IN ( 0, 1, 2, 3,
	// 	                                 4, 5, 7, 8, 9 )
	// 	       AND v.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 	                             FROM   sms_lista_ret cli_list
	// 	                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 	                             $anulaSms
	// 	                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 	                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 	                                        '$dat_ini' AND '$dat_fim'
	// 	                                    $andEntreguesSms
	// 	                             GROUP  BY cli_list.cod_campanha)
	// 	UNION
	// 	SELECT Sum(VAL_TOTPRODU)             VAL_TOTVENDA,
	// 	       Sum(val_resgate)              VAL_RESGATE,
	// 	       dat_cadastr_ws,
	// 	       Count(DISTINCT v.cod_cliente) QTD_COMPRAS,
	// 	       Count(v.cod_venda)            QTD_COMPRAS_TOTAL,
	// 	       ( 0 )                         QTD_VENDA_COM_RESGATE,
	// 	       ( 0 )                         VL_TOTAL_VENDA_RESGATE,
	// 	       ( 0 )                         QTD_CLIENTE_UNICOS_COM_RESGATE
	// 	FROM   vendas v
	// 	WHERE  Date(v.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
	// 	       AND v.cod_empresa = $cod_empresa
	// 	       AND v.cod_avulso = 2
	// 	       AND v.cod_statuscred IN ( 0, 1, 2, 3,
	// 	                                 4, 5, 7, 8, 9 )
	// 	       AND v.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
	// 	                             FROM   email_lista_ret cli_list
	// 	                             WHERE  cli_list.cod_empresa = v.cod_empresa
	// 	                             $anulaEmail
	// 	                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
	// 	                                    AND Date(cli_list.dat_cadastr) BETWEEN
	// 	                                        '$dat_ini' AND '$dat_fim'
	// 	                                    $andEntregues
	// 	                             GROUP  BY cli_list.cod_campanha)";

	$sqlPainelGC = "SELECT Sum(VAL_TOTPRODU)             VAL_TOTVENDA,
						       Sum(val_resgate)              VAL_RESGATE,
						       dat_cadastr_ws,
						       Count(DISTINCT v.cod_cliente) QTD_COMPRAS,
						       Count(v.cod_venda)            QTD_COMPRAS_TOTAL,
						       ( 0 )                         QTD_VENDA_COM_RESGATE,
						       ( 0 )                         VL_TOTAL_VENDA_RESGATE,
						       ( 0 )                         QTD_CLIENTE_UNICOS_COM_RESGATE
						FROM   vendas v
						WHERE  Date(v.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
						       AND v.cod_empresa = $cod_empresa
						       AND v.cod_avulso = 2
						       AND v.cod_statuscred IN ( 0, 1, 2, 3,
						                                 4, 5, 7, 8, 9 )
						       AND v.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
						                             FROM   lista_controle_cliente cli_list
						                             WHERE  cli_list.cod_empresa = v.cod_empresa
						                             $anulaSms
						                             		AND cli_list.DES_COMUNICA = 'SMS'
						                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
						                                    AND Date(cli_list.dat_cadastr) BETWEEN
						                                        '$dat_ini' AND '$dat_fim'
						                                    
						                             GROUP  BY cli_list.cod_campanha)
						UNION
						SELECT Sum(VAL_TOTPRODU)             VAL_TOTVENDA,
						       Sum(val_resgate)              VAL_RESGATE,
						       dat_cadastr_ws,
						       Count(DISTINCT v.cod_cliente) QTD_COMPRAS,
						       Count(v.cod_venda)            QTD_COMPRAS_TOTAL,
						       ( 0 )                         QTD_VENDA_COM_RESGATE,
						       ( 0 )                         VL_TOTAL_VENDA_RESGATE,
						       ( 0 )                         QTD_CLIENTE_UNICOS_COM_RESGATE
						FROM   vendas v
						WHERE  Date(v.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
						       AND v.cod_empresa = $cod_empresa
						       AND v.cod_avulso = 2
						       AND v.cod_statuscred IN ( 0, 1, 2, 3,
						                                 4, 5, 7, 8, 9 )
						       AND v.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
						                             FROM   lista_controle_cliente cli_list
						                             WHERE  cli_list.cod_empresa = v.cod_empresa
						                             $anulaEmail
						                             AND cli_list.DES_COMUNICA = 'EMAIL'
						                                    AND cli_list.cod_campanha IN ( $cod_campanhas )
						                                    AND Date(cli_list.dat_cadastr) BETWEEN
						                                        '$dat_ini' AND '$dat_fim'
						                                    
						                             GROUP  BY cli_list.cod_campanha)";
}

$sqlPainel2 = "SELECT 
					Count(DISTINCT cod_cliente) QTD_INATIVO,
					       cod_empresa,
					       (SELECT qtd_inativo
					        FROM   frequencia_cliente
					        WHERE  cod_empresa = $cod_empresa)   DIAS_INATIVO
					FROM   (

					SELECT 
								DISTINCT cli_list.cod_cliente,
								cli_list.cod_empresa
					FROM   clientes B
					left JOIN sms_lista_ret cli_list ON cli_list.cod_cliente = B.cod_cliente
					left JOIN email_lista_ret cli_list_email ON cli_list_email.cod_cliente = B.cod_cliente

					WHERE 
					cli_list.cod_empresa = $cod_empresa and
					 CASE

						 $caseCampInat      
							
							ELSE '0' END  IN ($concatOnesCamp)) TABLE_TMP
					GROUP  BY cod_empresa";

// $sqlPainel2 = "SELECT 
// 				  count(distinct cod_cliente) QTD_INATIVO ,
// 				  COD_EMPRESA,
// 				  (SELECT qtd_inativo  FROM frequencia_cliente  WHERE cod_empresa=$cod_empresa) DIAS_INATIVO
// 				  FROM (SELECT 
// 						DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
// 						FROM   sms_lista_ret cli_list
// 						  INNER JOIN  clientes B ON  cli_list.cod_cliente = B.cod_cliente
// 						WHERE 
// 						       Date(cli_list.dat_cadastr) between '$dat_ini' AND   '$dat_fim'
// 						       AND cli_list.COD_EMPRESA=$cod_empresa
// 						        $andEntreguesSms
// 						        $anulaSms
// 						       AND cli_list.cod_campanha IN ( $cod_campanhas )
// 						       AND cli_list.cod_cliente NOT IN(SELECT cod_cliente
// 													             FROM   vendas C
// 													             WHERE  C.cod_cliente = cli_list.cod_cliente
// 																 	AND C.cod_avulso = 2
// 													                    AND Date(C.dat_cadastr) >= ( Date(Adddate('$dat_ini',
// 													                             INTERVAL - (SELECT qtd_inativo  FROM frequencia_cliente  WHERE cod_empresa=cli_list.cod_empresa) day)
// 													                        ) )
// 													                    AND Date(C.dat_cadastr) <= '$dat_ini')
// 						       AND Date(B.dat_ultcompr) = '$dat_fim'
// 									union
// 								SELECT 
// 						DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
// 						FROM   email_lista_ret cli_list
// 						  INNER JOIN  clientes B ON  cli_list.cod_cliente = B.cod_cliente
// 						WHERE 
// 						      Date(cli_list.dat_cadastr) between '$dat_ini' AND   '$dat_fim'
// 						       AND cli_list.COD_EMPRESA=$cod_empresa
// 						        $andEntregues
// 						        $anulaEmail
// 						       AND cli_list.cod_campanha IN ( $cod_campanhas )
// 						       AND cli_list.cod_cliente NOT IN(SELECT cod_cliente
// 													             FROM   vendas C
// 													             WHERE  C.cod_cliente = cli_list.cod_cliente
// 																     and C.cod_avulso = 2
// 													                    AND Date(C.dat_cadastr) >= (
// 													                        Date(Adddate('$dat_ini',
// 													                             INTERVAL - (SELECT qtd_inativo  FROM frequencia_cliente  WHERE cod_empresa=cli_list.cod_empresa) day)
// 													                        ) )
// 													                    AND Date(C.dat_cadastr) <= '$dat_ini')
// 						       AND Date(B.dat_ultcompr) = '$dat_fim'	



// 					) TABLE_TMP
// 					GROUP BY COD_EMPRESA";

// fnEscreve($sqlPainel);
// fnEscreve($sqlPainelGC);

$arrayPainel = mysqli_query(connTemp($cod_empresa, ''), $sqlPainel);
$arrayPainel2 = mysqli_query(connTemp($cod_empresa, ''), $sqlPainel2);
$arrayPainelGC = mysqli_query(connTemp($cod_empresa, ''), $sqlPainelGC);

$total_venda = 0;
$total_itens = 0;
$total_resgate = 0;
$qtd_compras = 0;
$totResgAc = 0;
$qtdTransResgAc = 0;
$qtdCliResgAc = 0;
$valTotVendaResgAc = 0;

while ($qrPainel = mysqli_fetch_assoc($arrayPainel)) {
	$total_venda += $qrPainel['VAL_TOTVENDA'];
	$total_itens += $qrPainel['QTD_ITEM_TOTAL'];
	$total_itens_resgate += $qrPainel['QTD_ITEM_COM_RESGATE'];
	$total_resgate += $qrPainel['VAL_RESGATE'];
	$qtd_compras += $qrPainel['QTD_COMPRAS'];
	$qtd_compras_total += $qrPainel['QTD_COMPRAS_TOTAL'];
	$totResgAc += $qrPainel['VAL_RESGATE'];
	$qtdTransResgAc += $qrPainel['QTD_VENDA_COM_RESGATE'];
	$qtdCliResgAc += $qrPainel['QTD_CLIENTE_UNICOS_COM_RESGATE'];
	$qtdCliHibridoAc += $qrPainel['QTD_CLIEN_HIBRIDO'];
	$valTotVendaResgAc += $qrPainel['VL_TOTAL_VENDA_RESGATE'];
}

while ($qrPainelGC = mysqli_fetch_assoc($arrayPainelGC)) {
	$total_vendaGC += $qrPainelGC['VAL_TOTVENDA'];
	$total_itensGC += $qrPainelGC['QTD_ITEM_TOTAL'];
	$total_itens_resgateGC += $qrPainelGC['QTD_ITEM_COM_RESGATE'];
	$total_resgateGC += $qrPainelGC['VAL_RESGATE'];
	$qtd_comprasGC += $qrPainelGC['QTD_COMPRAS'];
	$qtd_compras_totalGC += $qrPainelGC['QTD_COMPRAS_TOTAL'];
	$totResgAcGC += $qrPainelGC['VAL_RESGATE'];
	$qtdTransResgAcGC += $qrPainelGC['QTD_VENDA_COM_RESGATE'];
	$qtdCliResgAcGC += $qrPainelGC['QTD_CLIENTE_UNICOS_COM_RESGATE'];
	$valTotVendaResgAcGC += $qrPainelGC['VL_TOTAL_VENDA_RESGATE'];
}

$qrPainel2 = mysqli_fetch_assoc($arrayPainel2);

$qtd_inativo = $qrPainel2['QTD_INATIVO'];
$dias_inativo = $qrPainel2['DIAS_INATIVO'];

$sql1 = "SELECT Count(DISTINCT qtd_clienteunicos)        QTD_LISTA,
					Count( qtd_clienteunicos)        QTD_LISTA_TOT,
				       Group_concat(DISTINCT des_canal ORDER BY des_canal ASC SEPARATOR ',')
				       DES_CANAL,
				       cod_empresa,
				       Sum(DISTINCT val_unitario)
				       VAL_UNITARIO
				FROM   (

						
						       SELECT  cli_list.cod_cliente QTD_CLIENTEUNICOS,
						                        cli_list.cod_empresa,
						                        'SMS'                AS DES_CANAL,
						                        VAL.val_unitario
						        FROM   sms_lista_ret cli_list
						               LEFT JOIN valores_comunicacao VAL
						                      ON VAL.cod_campanha = cli_list.cod_campanha
						                         AND VAL.des_canal = 'SMS'
						        WHERE  cli_list.cod_empresa = $cod_empresa 
						        $anulaSms
						        and
						               case
						               $caseCampLista
						               ELSE '0' END  IN ($concatOnesCamp)
				    
				   UNION ALL
						        
						        SELECT  cli_list.cod_cliente,
						                        cli_list.cod_empresa,
						                        'EMAIL' AS DES_CANAL,
						                        VAL.val_unitario
						        FROM   email_lista_ret cli_list
						               LEFT JOIN valores_comunicacao VAL
						                      ON VAL.cod_campanha = cli_list.cod_campanha
						                         AND VAL.des_canal = 'EMAIL'
						        WHERE  cli_list.cod_empresa = $cod_empresa 
						        $anulaEmail
						        and
						          case
					               $caseCampLista    
					              ELSE '0' END  IN ($concatOnesCamp)
						             
					
							
							

								  
						  ) QTD_CLIENTEUNICOS_FULL
				GROUP  BY cod_empresa";



//  $sql1 = "SELECT COUNT( DISTINCT QTD_CLIENTEUNICOS) QTD_LISTA ,
// 		 Group_concat(DISTINCT  DES_CANAL ORDER BY  DES_CANAL ASC SEPARATOR ',') DES_CANAL,
//        COD_EMPRESA,
// 		  SUM( DISTINCT VAL_UNITARIO)  VAL_UNITARIO
// FROM (
// 				SELECT DISTINCT cli_list.cod_cliente   QTD_CLIENTEUNICOS,cli_list.COD_EMPRESA, 'SMS' aS DES_CANAL,VAL.VAL_UNITARIO
// 				                 FROM   sms_lista_ret cli_list
// 				                    LEFT JOIN valores_comunicacao VAL
// 				                      ON VAL.cod_campanha = cli_list.cod_campanha
// 				                         AND VAL.des_canal = 'SMS'                         
// 				                 WHERE  cli_list.cod_empresa = $cod_empresa
// 				                 $anulaSms
// 				                        AND cli_list.cod_campanha IN ( $cod_campanhas )
// 				                        AND Date(cli_list.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'                        
// 				                 GROUP  BY cli_list.cod_cliente
// 				union	
// 				SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA,'EMAIL' AS DES_CANAL,VAL.VAL_UNITARIO
// 				                      FROM   email_lista_ret cli_list
// 				                      LEFT JOIN valores_comunicacao VAL
// 				                      ON VAL.cod_campanha = cli_list.cod_campanha
// 				                         AND VAL.des_canal = 'EMAIL'
// 				                      WHERE  cli_list.cod_empresa = $cod_empresa
// 				                      $anulaEmail
// 				                             AND cli_list.cod_campanha IN ( $cod_campanhas )
// 				                             AND Date(cli_list.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'                             
// 				                      GROUP  BY cli_list.cod_cliente
//                       ) QTD_CLIENTEUNICOS_FULL
//  GROUP BY cod_empresa";

$sql1GC = "SELECT COUNT( DISTINCT QTD_CLIENTEUNICOS) QTD_LISTA ,
		 Group_concat(DISTINCT  DES_CANAL ORDER BY  DES_CANAL ASC SEPARATOR ',') DES_CANAL,
       COD_EMPRESA,
		  SUM( DISTINCT VAL_UNITARIO)  VAL_UNITARIO
FROM (
				SELECT DISTINCT cli_list.cod_cliente   QTD_CLIENTEUNICOS,cli_list.COD_EMPRESA, 'SMS' aS DES_CANAL,VAL.VAL_UNITARIO
				                 FROM   lista_controle_cliente cli_list
				                    LEFT JOIN valores_comunicacao VAL
				                      ON VAL.cod_campanha = cli_list.cod_campanha
				                         AND VAL.des_canal = 'SMS'                         
				                 WHERE  cli_list.cod_empresa = $cod_empresa
				                 $anulaSms
				                 AND cli_list.DES_COMUNICA = 'SMS'
				                        AND cli_list.cod_campanha IN ( $cod_campanhas )
				                        AND Date(cli_list.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'                        
				                 GROUP  BY cli_list.cod_cliente
				union	
				SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA,'EMAIL' AS DES_CANAL,VAL.VAL_UNITARIO
				                      FROM   lista_controle_cliente cli_list
				                      LEFT JOIN valores_comunicacao VAL
				                      ON VAL.cod_campanha = cli_list.cod_campanha
				                      $anulaEmail
				                         AND VAL.des_canal = 'EMAIL'
				                      WHERE  cli_list.cod_empresa = $cod_empresa
				                      AND cli_list.DES_COMUNICA = 'EMAIL'
				                             AND cli_list.cod_campanha IN ( $cod_campanhas )
				                             AND Date(cli_list.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'                             
				                      GROUP  BY cli_list.cod_cliente
                      ) QTD_CLIENTEUNICOS_FULL
 GROUP BY cod_empresa";

// fnEscreve($sql1);


$sql2 = "SELECT Count(qtd_clienteunicos) TOTAL_CLIENTES_UNICOS,
			       cod_empresa
			FROM   (SELECT DISTINCT cli_list.cod_cliente QTD_CLIENTEUNICOS,
			                        cli_list.cod_empresa
			        FROM   sms_lista_ret cli_list
			        WHERE  cli_list.cod_empresa = $cod_empresa
			        $anulaSms
			               AND CASE
			                     $caseCampCliSms
			                     ELSE '0'
			                   END IN ( $concatOnesCamp )
			        GROUP  BY cli_list.cod_cliente
			        UNION
			        SELECT DISTINCT cli_list.cod_cliente,
			                        cli_list.cod_empresa TOTAL_CLIENTES_UNICOS
			        FROM   email_lista_ret cli_list
			        WHERE  cli_list.cod_empresa = $cod_empresa
			        $anulaEmail
			        			 AND CASE
			                     $caseCampCliEmail
			                     ELSE '0' END IN ( $concatOnesCamp )
			        
			             
			            
			        GROUP  BY cli_list.cod_cliente) QTD_CLIENTEUNICOS_table
			GROUP  BY cod_empresa";

//  $sql2 = "SELECT COUNT(QTD_CLIENTEUNICOS) TOTAL_CLIENTES_UNICOS ,COD_EMPRESA FROM (
// SELECT DISTINCT cli_list.cod_cliente   QTD_CLIENTEUNICOS,cli_list.COD_EMPRESA
//                  FROM   sms_lista_ret cli_list
//                  WHERE  cli_list.cod_empresa = $cod_empresa
//                         AND cli_list.cod_campanha IN ( $cod_campanhas )
//                         AND Date(cli_list.dat_cadastr) BETWEEN
//                             '$dat_ini' AND '$dat_fim'
//                         $andEntreguesSms
//                         $anulaSms
//                  GROUP  BY cli_list.cod_cliente
// union	
// SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
//                             TOTAL_CLIENTES_UNICOS
//                       FROM   email_lista_ret cli_list
//                       WHERE  cli_list.cod_empresa = $cod_empresa
//                              AND cli_list.cod_campanha IN ( $cod_campanhas )
//                              AND Date(cli_list.dat_cadastr) BETWEEN
//                                  '$dat_ini' AND '$dat_fim'
//                              $andEntregues
//                              $anulaEmail
//                       GROUP  BY cli_list.cod_cliente
//                       ) QTD_CLIENTEUNICOS_table
//  GROUP BY cod_empresa";

// fnEscreve($sql2);

$sql2GC = "SELECT COUNT(QTD_CLIENTEUNICOS) TOTAL_CLIENTES_UNICOS ,COD_EMPRESA FROM (
	SELECT DISTINCT cli_list.cod_cliente   QTD_CLIENTEUNICOS,cli_list.COD_EMPRESA
	                 FROM   lista_controle_cliente cli_list
	                 WHERE  cli_list.cod_empresa = $cod_empresa
	                 $anulaSms
	                 	AND cli_list.DES_COMUNICA = 'SMS'
	                        AND cli_list.cod_campanha IN ( $cod_campanhas )
	                        AND Date(cli_list.dat_cadastr) BETWEEN
	                            '$dat_ini' AND '$dat_fim'
	                        
	                 GROUP  BY cli_list.cod_cliente
	union	
	SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
	                            TOTAL_CLIENTES_UNICOS
	                      FROM   lista_controle_cliente cli_list
	                      WHERE  cli_list.cod_empresa = $cod_empresa
	                      $anulaEmail
	                      	AND cli_list.DES_COMUNICA = 'EMAIL'
	                             AND cli_list.cod_campanha IN ( $cod_campanhas )
	                             AND Date(cli_list.dat_cadastr) BETWEEN
	                                 '$dat_ini' AND '$dat_fim'
	                             
	                      GROUP  BY cli_list.cod_cliente
	                      ) QTD_CLIENTEUNICOS_table
	 GROUP BY cod_empresa";


$sql3 = "SELECT Count(qtd_clienteunicos) TOTAL_CLIENTES_UNICOS,
			       cod_empresa
				FROM   (SELECT DISTINCT cli_list.cod_cliente QTD_CLIENTEUNICOS,
			                        cli_list.cod_empresa
			        FROM   sms_lista_ret cli_list
			        WHERE  cli_list.cod_empresa = $cod_empresa
			        $anulaSms
			               AND CASE
			                     $caseCampCliSms
			                     ELSE '0'
			                   END IN ( $concatOnesCamp )
			        GROUP  BY cli_list.cod_cliente
			        UNION
			        SELECT DISTINCT cli_list.cod_cliente,
			                        cli_list.cod_empresa TOTAL_CLIENTES_UNICOS
			        FROM   email_lista_ret cli_list
			        WHERE  cli_list.cod_empresa = $cod_empresa
			        $anulaEmail
			        			 AND CASE
			                     $caseCampCliEmailUni
			                     ELSE '0' END IN ( $concatOnesCamp )
			        
			             
			            
			        GROUP  BY cli_list.cod_cliente) QTD_CLIENTEUNICOS_table
			GROUP  BY cod_empresa";

// $sql3 = "SELECT COUNT(QTD_CLIENTEUNICOS) TOTAL_CLIENTES_UNICOS ,COD_EMPRESA FROM (
// 			SELECT DISTINCT cli_list.cod_cliente   QTD_CLIENTEUNICOS,cli_list.COD_EMPRESA
// 			                 FROM   sms_lista_ret cli_list
// 			                 WHERE  cli_list.cod_empresa = $cod_empresa
// 			                 $anulaSms
// 			                        AND cli_list.cod_campanha IN ( $cod_campanhas )
// 			                        AND Date(cli_list.dat_cadastr) BETWEEN
// 			                            '$dat_ini' AND '$dat_fim'

// 			                 GROUP  BY cli_list.cod_cliente
// 			union	
// 			SELECT DISTINCT cli_list.cod_cliente,cli_list.COD_EMPRESA
// 			                            TOTAL_CLIENTES_UNICOS
// 			                      FROM   email_lista_ret cli_list
// 			                      WHERE  cli_list.cod_empresa = $cod_empresa
// 			                      $anulaEmail
// 			                             AND cli_list.cod_campanha IN ( $cod_campanhas )
// 			                             AND Date(cli_list.dat_cadastr) BETWEEN
// 			                                 '$dat_ini' AND '$dat_fim'
// 			                             	AND cli_list.cod_optout_ativo = '0' 
// 											 AND cli_list.cod_leitura IN('1','0') 
// 											 AND cli_list.bounce = '0' 
// 											 AND cli_list.SPAM = '0'
// 			                      GROUP  BY cli_list.cod_cliente
// 			                      ) QTD_CLIENTEUNICOS_table
// 			 GROUP BY cod_empresa";
// fnEscreve($sql1);
// fnEscreve($sql1GC);
// fnEscreve($sql2);
// fnEscreve($sql2GC);
// fnEscreve($sql3);

$sqlCampanha = "SELECT DES_CAMPANHA, DAT_INI, DAT_FIM, LOG_CONTINU FROM CAMPANHA WHERE COD_CAMPANHA IN($cod_campanhas) AND COD_EMPRESA = $cod_empresa";
$arrayCampanha = mysqli_query(connTemp($cod_empresa, ''), $sqlCampanha);

$arrayQuery1 = mysqli_query(connTemp($cod_empresa, ''), $sql1);
$arrayQuery1GC = mysqli_query(connTemp($cod_empresa, ''), $sql1GC);
$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2);
$arrayQuery2GC = mysqli_query(connTemp($cod_empresa, ''), $sql2GC);
$arrayQuery3 = mysqli_query(connTemp($cod_empresa, ''), $sql3);

$qtd_publico = 0;
$canais_com = "";
$campanhas = "";

$qrCamp = mysqli_fetch_assoc($arrayQuery1);
$qtd_publico = $qrCamp['QTD_LISTA'];
$qtd_publico_tot = $qrCamp['QTD_LISTA_TOT'];
// $canais_com = $qrCamp['DES_CANAL'];
$canais_com = $canais;

$qrCampGC = mysqli_fetch_assoc($arrayQuery1GC);
$qtd_publicoGC = $qrCampGC['QTD_LISTA'];
$canais_comGC = $qrCampGC['DES_CANAL'];

while ($qrDescamp = mysqli_fetch_assoc($arrayCampanha)) {
	if ($qrDescamp['LOG_CONTINU'] == "S") {
		$fimCampanha = "Contínua";
	} else {
		$fimCampanha = fnDataShort($qrDescamp['DAT_FIM']);
	}
	$campanhas .= $qrDescamp['DES_CAMPANHA'] . " <small>(" . fnDataShort($qrDescamp['DAT_INI']) . " - " . $fimCampanha . ")</small>" . ", <br/>";
}

$canais_com = trim(rtrim(trim($canais_com), '/'));
$campanhas = trim(rtrim(trim($campanhas), ', <br/>'));

$qrTotal = mysqli_fetch_assoc($arrayQuery2);
$qrTotalAlvo = mysqli_fetch_assoc($arrayQuery3);
$qrTotalGC = mysqli_fetch_assoc($arrayQuery2GC);

$tot_clientes_uni = $qrTotal['TOTAL_CLIENTES_UNICOS'];
$tot_clientes_uniGC = $qrTotalGC['TOTAL_CLIENTES_UNICOS'];
$tot_clientes_uni_alvo = $qrTotalAlvo['TOTAL_CLIENTES_UNICOS'];

$sqlCont = "SELECT 
      VAL.DES_CANAL,
	   CAMP.DES_CAMPANHA,    
      case 
		    when
			       SUM(LOT_SMS.QTD_LISTA) > '0' then SUM(LOT_SMS.QTD_LISTA) 
			       
			  when     
			       SUM(LOT_EMAIL.QTD_LISTA) >'0' then SUM(LOT_EMAIL.QTD_LISTA) 
			   ELSE '0' END     QTD_LISTA,
     IFNULL(VAL.VAL_UNITARIO, 0) VAL_UNITARIO

    FROM campanha CAMP
      left JOIN VALORES_COMUNICACAO VAL ON VAL.COD_CAMPANHA = CAMP.COD_CAMPANHA   
      left JOIN sms_lote LOT_SMS   ON  CAMP.COD_CAMPANHA = LOT_SMS.COD_CAMPANHA  AND VAL.DES_CANAL = 'SMS' $anulaSms 
	  left JOIN email_lote  LOT_EMAIL ON CAMP.COD_CAMPANHA = LOT_EMAIL.COD_CAMPANHA  AND VAL.DES_CANAL = 'EMAIL' $anulaEmail
	                                  
    WHERE 
      case 
               $caseCampCont 
			  ELSE '0' END IN ($concatOnesCamp)


      AND CAMP.cod_empresa = $cod_empresa 
      
 GROUP BY  VAL.DES_CANAL,CAMP.COD_CAMPANHA";


$sqlSmsEmail = "SELECT 
   	Sum(v.val_totvenda) VAL_TOTVENDA,
		Sum(v.val_totprodu) VAL_TOTPRODU,
		Sum(v.val_resgate)  VAL_RESGATE
FROM   vendas v
        WHERE  v.cod_empresa = $cod_empresa
               AND v.cod_avulso = 2            
               AND CASE
                    $caseCampFull
                   END IN ( $concatOnesCamp )
        GROUP  BY v.COD_EMPRESA";

// $sqlCont = "SELECT *
// 			FROM (SELECT CAMP.DES_CAMPANHA, CAMP.LOG_ATIVO, 'SMS' AS DES_CANAL,
// 			(SELECT COUNT(*)
// 			  FROM vendas v 
// 			 WHERE date(v.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim' 
// 			         AND v.cod_empresa=$cod_empresa 
// 			         AND v.COD_AVULSO=2 
// 			         AND v.COD_CLIENTE IN ( SELECT cli_list.COD_CLIENTE FROM sms_lista_ret cli_list
// 													  WHERE cli_list.COD_EMPRESA=v.cod_empresa 
// 													  AND cli_list.COD_CAMPANHA=CAMP.COD_CAMPANHA
// 													  AND Date(cli_list.DAT_CADASTR) BETWEEN '$dat_ini' 
// 													 AND '$dat_fim' 
// 													 $andEntreguesSms
// 													 $anulaSms
// 													 GROUP BY cli_list.COD_CAMPANHA )) AS CLI_ATIVOS, 
// 			sum(LOT.QTD_LISTA) QTD_LISTA, VAL.COD_VALOR, IFNULL(VAL.VAL_UNITARIO,0) VAL_UNITARIO, LOT.COD_CAMPANHA 
// 			FROM sms_lote LOT 
// 			INNER JOIN campanha CAMP ON CAMP.COD_CAMPANHA=LOT.COD_CAMPANHA 
// 			LEFT JOIN VALORES_COMUNICACAO VAL ON VAL.COD_CAMPANHA = CAMP.COD_CAMPANHA AND VAL.DES_CANAL = 'SMS' 
// 			WHERE date(LOT.DAT_AGENDAMENTO) BETWEEN '$dat_ini' AND '$dat_fim' 
// 			AND LOT.LOG_ENVIO = 'S' 
// 			AND LOT.cod_empresa=$cod_empresa 
// 			AND CAMP.COD_CAMPANHA IN($cod_campanhas)
// 			AND CAMP.LOG_PROCESSA_SMS = 'S'
// 			GROUP BY LOT.COD_CAMPANHA) AS SMS

// 			UNION

// 			(SELECT CAMP.DES_CAMPANHA, CAMP.LOG_ATIVO, 'EMAIL' AS DES_CANAL,
// 			(SELECT COUNT(*)
// 			  FROM vendas v 
// 			 WHERE date(v.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim' 
// 			         AND v.cod_empresa=$cod_empresa 
// 			         AND v.COD_AVULSO=2 
// 			         AND v.COD_CLIENTE IN ( SELECT cli_list.COD_CLIENTE FROM email_lista_ret cli_list
// 													  WHERE cli_list.COD_EMPRESA=v.cod_empresa 
// 													  AND cli_list.COD_CAMPANHA=CAMP.COD_CAMPANHA
// 													  AND Date(cli_list.DAT_CADASTR) BETWEEN '$dat_ini' 
// 													 AND '$dat_fim' 
// 													 $andEntregues
// 													 $anulaEmail
// 													 GROUP BY cli_list.COD_CAMPANHA )) AS CLI_ATIVOS, 
// 			sum(LOT.QTD_LISTA) QTD_LISTA, VAL.COD_VALOR, IFNULL(VAL.VAL_UNITARIO,0) VAL_UNITARIO, LOT.COD_CAMPANHA 
// 			FROM email_lote LOT 
// 			INNER JOIN campanha CAMP ON CAMP.COD_CAMPANHA=LOT.COD_CAMPANHA 
// 			LEFT JOIN VALORES_COMUNICACAO VAL ON VAL.COD_CAMPANHA = CAMP.COD_CAMPANHA AND VAL.DES_CANAL = 'EMAIL' 
// 			WHERE date(LOT.DAT_AGENDAMENTO) BETWEEN '$dat_ini' AND '$dat_fim' 
// 			AND LOT.LOG_ENVIO = 'S' 
// 			AND LOT.cod_empresa=$cod_empresa 
// 			AND CAMP.COD_CAMPANHA IN($cod_campanhas)
// 			AND CAMP.LOG_PROCESSA = 'S'
// 			GROUP BY LOT.COD_CAMPANHA) 

// 		ORDER BY COD_CAMPANHA, DES_CAMPANHA";



// $sqlSms = "SELECT    
// 			Sum(v.val_totvenda) VAL_TOTVENDA, 
// 		   Sum(v.val_totprodu) VAL_TOTPRODU, 
// 		   Sum(v.val_resgate)  VAL_RESGATE
// 		 FROM vendas v
// 		WHERE
// 		date(v.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim'
// 		AND v.cod_empresa=$cod_empresa
// 		$anulaSms
// 		AND v.COD_AVULSO=2
// 		AND v.COD_CLIENTE IN (
// 			SELECT cli_list.COD_CLIENTE FROM  sms_lista_ret cli_list WHERE
// 			 cli_list.COD_EMPRESA=v.cod_empresa
// 			AND  cli_list.COD_CAMPANHA IN ($cod_campanhas)
// 			AND  Date(cli_list.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim'
// 		GROUP BY cli_list.cod_cliente
// 		)";

// $sqlEmail = "SELECT    
// 			Sum(v.val_totvenda) VAL_TOTVENDA, 
// 		   Sum(v.val_totprodu) VAL_TOTPRODU, 
// 		   Sum(v.val_resgate)  VAL_RESGATE
// 		 FROM vendas v
// 		WHERE
// 		date(v.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim'
// 		AND v.cod_empresa=$cod_empresa
// 		$anulaEmail
// 		AND v.COD_AVULSO=2
// 		AND v.COD_CLIENTE IN (
// 			SELECT cli_list.COD_CLIENTE FROM  email_lista_ret cli_list WHERE
// 			 cli_list.COD_EMPRESA=v.cod_empresa
// 			AND  cli_list.COD_CAMPANHA IN ($cod_campanhas)
// 			AND  Date(cli_list.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim'
// 		GROUP BY cli_list.cod_cliente
// 		)";

// fnEscreve($sqlCont);
// fnEscreve($sqlSms);
// fnEscreve($sqlEmail);

$arrayCont = mysqli_query(connTemp($cod_empresa, ''), $sqlCont);
$arraySmsEmail = mysqli_query(connTemp($cod_empresa, ''), $sqlSmsEmail);
// $arraySms = mysqli_query(connTemp($cod_empresa,''),$sqlSms);
// $arrayEmail = mysqli_query(connTemp($cod_empresa,''),$sqlEmail);

$sqlSmsEmail = mysqli_fetch_assoc($arraySmsEmail);
// $qrSms = mysqli_fetch_assoc($arraySms);
// $qrEmail = mysqli_fetch_assoc($arrayEmail);

$tot_ativos = 0;
$tot_lista = 0;

while ($qrCont = mysqli_fetch_assoc($arrayCont)) {
	$tot_ativos += @$qrCont['CLI_ATIVOS'];
	$tot_lista += @$qrCont['QTD_LISTA'];
	$invest += @$qrCont['QTD_LISTA'] * @$qrCont['VAL_UNITARIO'];
}


$val_totvenda = @$qrSmsEmail['VAL_TOTVENDA'];
// $val_totvenda = $qrSms['VAL_TOTVENDA'] + $qrEmail['VAL_TOTVENDA'];

// fnEscreve($invest);
// fnEscreve($retorno);
// fnEscreve($roi);

// $sqlContrl = "SELECT MAX(CLIENTES_UNICO_PERC) AS GRUPO_CONTROLE FROM email_parametros
// 				WHERE COD_EMPRESA = $cod_empresa
// 				$anulaEmail
// 				AND COD_CAMPANHA IN ($cod_campanhas)

// 			  UNION

// 			  SELECT MAX(CLIENTES_UNICO_PERC) AS GRUPO_CONTROLE FROM sms_parametros
// 				WHERE COD_EMPRESA = $cod_empresa
// 				$anulaSms
// 				AND COD_CAMPANHA IN ($cod_campanhas)";

$sqlContrl = "SELECT COD_CAMPANHA ,
				       GRUPO_CONTROLE GRUPO_CONTROLE, 
						 COD_LISTA
						 FROM (

				SELECT CLIENTES_UNICO_PERC AS GRUPO_CONTROLE,
				       COD_LISTA,
						 COD_CAMPANHA		 
				FROM email_parametros
				WHERE COD_EMPRESA = $cod_empresa  AND COD_CAMPANHA IN ($cod_campanhas)  
				UNION  
				SELECT CLIENTES_UNICO_PERC AS GRUPO_CONTROLE,
				       COD_LISTA,
						 COD_CAMPANHA
				FROM sms_parametros
				WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA IN ($cod_campanhas) 
				ORDER BY COD_LISTA desc

				)testediogo
				GROUP BY COD_CAMPANHA";

$arrayContrl = mysqli_query(connTemp($cod_empresa, ''), $sqlContrl);

$tot_controle = 0;

while ($qrContrl = mysqli_fetch_assoc($arrayContrl)) {
	$tot_controle += $qrContrl['GRUPO_CONTROLE'];
}

// fnEscreve($sqlContrl);
$sqlNrecebidos = "SELECT 
				 $camposNRecebidos TOTAL_SEMCOM
				 FROM campanha camp

				$caseNRecebidosSms


				$caseNRecebidosEmail

				WHERE camp.COD_EMPRESA=$cod_empresa";

// $sqlNrecebidos = "SELECT (

// 					 SELECT Count(DISTINCT cli_list.cod_cliente) QTD_CLIENTEUNICOS_SMS
// 					        FROM   sms_lista_ret cli_list
// 					        WHERE  cli_list.cod_empresa = $cod_empresa
// 					        $anulaSms
// 					               AND cli_list.cod_campanha IN ($cod_campanhas)
// 					               AND Date(cli_list.dat_cadastr) BETWEEN
// 					                   '$dat_ini' AND '$dat_fim' 
// 					               AND CASE
// 					                     WHEN cli_list.bounce = '1' THEN '1'
// 					                     WHEN cli_list.cod_optout_ativo = '1' THEN '1'
// 					                     ELSE '0'
// 					                   end IN ( 1, 1 ) 
// 					        GROUP  BY cli_list.cod_empresa
// 							   )+(		  
// 							    SELECT  Count(DISTINCT cli_list.cod_cliente) QTD_CLIENTEUNICOS_EMAIL
// 					          FROM   email_lista_ret cli_list
// 					          WHERE  cli_list.cod_empresa = $cod_empresa
// 					          $anulaEmail
// 					                 AND cli_list.cod_campanha IN ($cod_campanhas)
// 					                 AND Date(cli_list.dat_cadastr) BETWEEN
// 					                     '$dat_ini' AND '$dat_fim'                 
// 					                  AND CASE
// 					                     WHEN  cli_list.bounce IN (1,2) THEN '1'
// 					                     WHEN cli_list.spam = '1' THEN '1'
// 					                     WHEN cli_list.cod_optout_ativo = '1' THEN '1'
// 					                     ELSE '0'
// 					                   end IN ( 1, 1 )               
// 					          GROUP  BY cli_list.cod_empresa) TOTAL_SEMCOM";
// fnEscreve($sqlNrecebidos);

$arrayNrecebidos = mysqli_query(connTemp($cod_empresa, ''), $sqlNrecebidos);

$qrNrec = mysqli_fetch_assoc($arrayNrecebidos);

$tot_nRecebeu = $qrNrec['TOTAL_SEMCOM'];

// fnEscreve($tot_nRecebeu);

$sqlComprasSemCom = "SELECT Count(DISTINCT v.cod_cliente) QTD_COMPRAS_SEM_COMUNICACO
        FROM   vendas v
        WHERE 
                v.cod_empresa = $cod_empresa
               AND v.cod_avulso = 2
               AND v.cod_statuscred IN ( 0, 1, 2, 3,  4, 5, 7, 8, 9 )
              and case
					    $caseComprasSemCom
							  ELSE '0' END IN ($concatOnesCamp)";

// $sqlComprasSemCom = "SELECT (
// 						 SELECT 
// 						       Count(DISTINCT v.cod_cliente) QTD_COMPRAS_SEM_COMUNICACO
// 						FROM   vendas v
// 						WHERE  Date(v.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
// 						$anulaSms
// 						       AND v.cod_empresa = $cod_empresa
// 						       AND v.cod_avulso = 2
// 						       AND v.cod_statuscred IN ( 0, 1, 2, 3,
// 						                                 4, 5, 7, 8, 9 )
// 						       AND v.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
// 													        FROM   sms_lista_ret cli_list
// 													        WHERE  cli_list.cod_empresa = $cod_empresa
// 													               AND cli_list.cod_campanha IN ( $cod_campanhas )
// 													               AND Date(cli_list.dat_cadastr) BETWEEN
// 													                   '$dat_ini' AND '$dat_fim'
// 													               AND CASE
// 													                     WHEN cli_list.bounce = '1' THEN '1'
// 													                     WHEN cli_list.cod_optout_ativo = '1' THEN '1'
// 													                     ELSE '0'
// 													                   end IN ( 1, 1 )
// 						                             GROUP  BY cli_list.cod_campanha)

// 						)+(

// 						SELECT Count(DISTINCT v.cod_cliente) QTD_COMPRAS_SEM_COMUNICACO
// 						FROM   vendas v
// 						WHERE  Date(v.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
// 						$anulaEmail
// 						       AND v.cod_empresa = $cod_empresa
// 						       AND v.cod_avulso = 2
// 						       AND v.cod_statuscred IN ( 0, 1, 2, 3,
// 						                                 4, 5, 7, 8, 9 )
// 						       AND v.cod_cliente IN (SELECT DISTINCT cli_list.cod_cliente
// 													          FROM   email_lista_ret cli_list
// 													          WHERE  cli_list.cod_empresa = $cod_empresa
// 													                 AND cli_list.cod_campanha IN ( $cod_campanhas )
// 													                 AND Date(cli_list.dat_cadastr) BETWEEN
// 													                     '$dat_ini' AND '$dat_fim'
// 													                 AND CASE
// 													                       WHEN cli_list.bounce IN ( 1, 2 ) THEN '1'
// 													                       WHEN cli_list.spam = '1' THEN '1'
// 													                       WHEN cli_list.cod_optout_ativo = '1' THEN '1'
// 													                       ELSE '0'
// 													                     end IN ( 1, 1 )
// 						                             GROUP  BY cli_list.cod_campanha)  
// 						   )   QTD_COMPRAS_SEM_COMUNICACO";

// fnEscreve($sqlComprasSemCom);
$arrayComprasSemCom = mysqli_query(connTemp($cod_empresa, ''), $sqlComprasSemCom);


@$qrCompraSemCom = mysqli_fetch_assoc(@$arrayComprasSemCom);

$tot_nRecebeu = $tot_controle;

//fnEscreve($tot_nRecebeu);

$tot_comprasSemCom = $qrCompraSemCom['QTD_COMPRAS_SEM_COMUNICACO'];

// ---------------------------------------------------

$pct_engajamentoAcGC = $tot_clientes_uniGC != 0 ? (($qtd_comprasGC) / $tot_clientes_uniGC) * 100 : 0;
$pct_engajamentoCtGC = $tot_nRecebeuGC != 0 ? (($tot_comprasSemComGC) / $tot_nRecebeuGC) * 100 : 0;

// fnEscreve($total_vendaGC);

$TMAcGC = ($qtd_compras_totalGC - $qtdTransResgAcGC) != 0 ? ($total_vendaGC - $valTotVendaResgAcGC) / ($qtd_compras_totalGC - $qtdTransResgAcGC) : 0;
$GMAcGC =  ($qtd_comprasGC - $qtdCliResgAcGC) != 0 ? ($total_vendaGC - $valTotVendaResgAcGC) / ($qtd_comprasGC - $qtdCliResgAcGC) : 0;
$TMAcGeralGC = $qtd_compras_totalGC != 0 ? ($total_vendaGC) / ($qtd_compras_totalGC) : 0;
$GMAcGeralGC = $qtd_comprasGC != 0 ? ($total_vendaGC) / ($qtd_comprasGC) : 0;

$pctTotResgAcGC = $total_vendaGC != 0 ? (($totResgAcGC) / $total_vendaGC) * 100 : 0;


$percVvrAcGC = $totResgAcGC != 0 ? (($valTotVendaResgAcGC - $totResgAcGC) / $totResgAcGC) * 100 : 0;
$pctVvrFaturAcGC = $total_vendaGC != 0 ? (($valTotVendaResgAcGC) / $total_vendaGC) * 100 : 0;

$TMResgAcGC = $qtdTransResgAcGC != 0 ?  ($valTotVendaResgAcGC - $totResgAcGC) / $qtdTransResgAcGC : 0;
$GMResgAcGC = $qtdCliResgAcGC != 0 ? ($valTotVendaResgAcGC - $totResgAcGC) / $qtdCliResgAcGC : 0;

$VRMTransAcGC = $qtdTransResgAcGC != 0 ? $totResgAcGC / $qtdTransResgAcGC : 0;
$VRMCliAcGC = $qtdCliResgAcGC != 0 ? $totResgAcGC / $qtdCliResgAcGC : 0;

$itensTransacGC = $qtd_compras_totalGC != 0 ? $total_itensGC / $qtd_compras_totalGC : 0;
$clientesTransacGC = $qtd_comprasGC != 0 ? $total_itensGC / $qtd_comprasGC : 0;

$itensTransacResgGC = $qtdTransResgAcGC != 0 ?  $total_itens_resgateGC / $qtdTransResgAcGC : 0;
$clientesTransacResgGC =  $qtdCliResgAcGC != 0 ? $total_itens_resgateGC / $qtdCliResgAcGC : 0;

$pctTransResgGC = $total_itensGC != 0 ? (($qtdTransResgAcGC) / $total_itensGC) * 100 : 0;
$pctCliResgGC = $qtd_comprasGC != 0 ? (($qtdCliResgAcGC) / $qtd_comprasGC) * 100 : 0;
$pctItensResgGC = $total_itensGC != 0 ? (($total_itens_resgateGC) / $total_itensGC) * 100 : 0;

$pctInvestGC = $total_vendaGC != 0 ? (($invest) / $total_vendaGC) * 100 : 0;
$pctCliUniGC = $qtd_publicoGC != 0 ? (($tot_clientes_uniGC) / $qtd_publicoGC) * 100 : 0;
$pctCliComprasGC = $tot_clientes_uniGC != 0 ? (($qtd_comprasGC) / $tot_clientes_uniGC) * 100 : 0;
$pctInativosGC = $qtd_comprasGC != 0 ? (($qtd_inativoGC) / $qtd_comprasGC) * 100 : 0;

$retornoGC = ($total_vendaGC - $invest);
$roiGC =  $invest != 0 ? $retornoGC / $invest : 0;

$total_itens_srGC = $total_itensGC - $total_itens_resgateGC;
$fatGAGC = $total_vendaGC - $totResgAcGC;

$clientesTransac_sr = $total_itens_sr - $qtdCliResgAc_sr;

$pctTotResgAc = $total_venda != 0 ? (($totResgAc) / $total_venda) * 100 : 0;

$percVvrAc =  $totResgAc != 0 ?  (($valTotVendaResgAc - $totResgAc) / $totResgAc) * 100 : 0;
$pctVvrFaturAc = $total_venda != 0 ? (($valTotVendaResgAc) / $total_venda) * 100 : 0;

$TMResgAc =  $qtdTransResgAc != 0 ? ($valTotVendaResgAc - $totResgAc) / $qtdTransResgAc : 0;
$GMResgAc = $qtdCliResgAc != 0 ? ($valTotVendaResgAc - $totResgAc) / $qtdCliResgAc : 0;

$VRMTransAc  =  $qtdTransResgAc !=  0 ?  $totResgAc / $qtdTransResgAc :  0;
$VRMCliAc = $qtdCliResgAc != 0 ? $totResgAc / $qtdCliResgAc : 0;

$itensTransac =  $qtd_compras_total != 0 ?  $total_itens / $qtd_compras_total : 0;
$clientesTransac = $qtd_compras !=  0 ? $total_itens / $qtd_compras : 0;

$itensTransacResg =  $qtdTransResgAc != 0 ?  $total_itens_resgate / $qtdTransResgAc : 0;
$clientesTransacResg = $qtdCliResgAc != 0 ? $total_itens_resgate / $qtdCliResgAc : 0;

$pctItensResg = $total_itens != 0 ? (($total_itens_resgate) / $total_itens) * 100 : 0;

$pctInvest = $total_venda != 0 ? (($invest) / $total_venda) * 100 : 0;
$pctCliUni = $qtd_publico != 0 ? (($tot_clientes_uni) / $qtd_publico) * 100 : 0;
$pctCliCompras = $tot_clientes_uni != 0 ? (($qtd_compras) / $tot_clientes_uni) * 100 : 0;
$pctInativos = $qtd_compras != 0 ? (($qtd_inativo) / $qtd_compras) * 100 : 0;

$retorno = ($total_venda - $invest);
$roi = $invest != 0 ?  $retorno / $invest : 0;

$fatGA = $total_venda - $invest - $totResgAc;

$pctCliUniAlvo = $qtd_publico != 0 ? (($tot_clientes_uni_alvo) / $qtd_publico) * 100 : 0;

$TMCt = 0;
$GMCt = 0;
$vendasControle = 0;


$fatGC = 0;

$TMAcGeral = ($total_venda) / ($qtd_compras_total);
$GMAcGeral = ($total_venda) / ($qtd_compras);


// fnEscreve($total_venda);
// fnEscreve($vendasControle);
// fnEscreve($val_mbruta);

// $pctMargem = $total_venda != 0 ? (($incrementoMargem) / $total_venda)* 100 : 0;
$pctMargem = $val_mbruta;

$sqlExtraAC = "SELECT Sum(val_credito) BONUS_EXTRA,
				       Sum(val_credito) - Sum(val_saldo) BONUS_RESGATADOS ,
				       Count(DISTINCT cod_cliente) QTD_CLIENTE_EXTRAS,
				        (SELECT Count(DISTINCT cod_cliente)
				        FROM creditosdebitos
				        WHERE cod_empresa = $cod_empresa
				            AND  case
								
								$caseExtraResgAc     
												
							ELSE '0' END IN ($concatOnesCamp)) QTD_CLIENTES_RESGATADOS

							FROM   creditosdebitos
							WHERE 
					       	cod_empresa = $cod_empresa
					       	AND case
								
								$caseExtraAc    
								
			 				ELSE '0' END IN ($concatOnesCamp)";

// fnEscreve($sqlExtraAC);

$arrayExtraAC = mysqli_query(connTemp($cod_empresa, ''), $sqlExtraAC);

$qrExtraAC = mysqli_fetch_assoc($arrayExtraAC);

$bonus_extrasAC = $qrExtraAC['BONUS_EXTRA'];
$bonus_resgatadosAC = $qrExtraAC['BONUS_RESGATADOS'];
$qtd_cliente_extrasAC = $qrExtraAC['QTD_CLIENTE_EXTRAS'];
$qtd_clientes_resgatadosAC = $qrExtraAC['QTD_CLIENTES_RESGATADOS'];

$sqlExtraGC = "SELECT Sum(val_credito)                  BONUS_EXTRA,
       Sum(val_credito) - Sum(val_saldo) BONUS_RESGATADOS,
       Count(DISTINCT cod_cliente)       QTD_CLIENTE_EXTRAS,
       (SELECT Count(DISTINCT cod_cliente)
        FROM   creditosdebitos
        WHERE  cod_credlot > 0
               AND val_credito != val_saldo
               AND cod_empresa = $cod_empresa
              
				    AND CASE
				    	$caseExtraResgGc
                    ELSE '0' END IN ( $concatOnesCamp ))        QTD_CLIENTES_RESGATADOS
				 FROM   creditosdebitos
				WHERE  cod_credlot > 0
				       AND cod_empresa = $cod_empresa
				       AND CASE
		                $caseExtraGc 
                       ELSE '0'
                   END IN ( $concatOnesCamp )";

$arrayExtraGC = mysqli_query(connTemp($cod_empresa, ''), $sqlExtraGC);

$qrExtraGC = mysqli_fetch_assoc($arrayExtraGC);

$bonus_extrasGC = $qrExtraGC['BONUS_EXTRA'];
$bonus_resgatadosGC = $qrExtraGC['BONUS_RESGATADOS'];
$qtd_cliente_extrasGC = $qrExtraGC['QTD_CLIENTE_EXTRAS'];
$qtd_clientes_resgatadosGC = $qrExtraGC['QTD_CLIENTES_RESGATADOS'];

// fnEscreve($sqlExtraGC);

// fnEscreve($val_mbruta);

// $verifica = 0;Incremento Margem bruta

$pct_engajamentoCt = $tot_nRecebeu != 0 ? (($qtd_comprasGC) / $tot_nRecebeu) * 100 : 0;
// CALCULO FATURAMENTO GRUPO DE CONTROLE

$pctGrupo = $pct_engajamentoCt / 100;

// fnEscreve($tot_clientes_uni);
// fnEscreve($pctGrupo);
// fnEscreve($GMAcGeralGC);

$fatGrupoControle = round($tot_clientes_uni, 4) * round($pctGrupo, 4) * round($GMAcGeralGC, 4);

$incrementoMargem = ($total_venda - $fatGrupoControle) * $val_mbruta;

$resultado = ($incrementoMargem - $invest);

$percResultado = $invest != 0 ? (($resultado) / $invest) * 100 : 0;

if ($resultado <= 0) {
	$roiComparativo = 0;
} else {
	$roiComparativo = $percResultado;
}

// $total_vendaGC = $fatGrupoControle;

$alinhaAcao = 'text-right';
$colAcao = 'col-md-6';
$balaoAcao = 'col-xs-7 col-xs-offset-5';
$displayCont = 'block';

if ($TMAcGC == 0 && $tot_nRecebeu == 0) {
	$alinhaAcao = 'text-center';
	$colAcao = 'col-md-12';
	$balaoAcao = 'col-xs-4 col-xs-offset-4';
	$displayCont = 'none';
}

?>

<style>
	.text-white p,
	.text-white h4 {
		color: #FCFCFC !important;
	}

	.info-header hr {
		margin: 5px 0px !important;
	}

	.panel:hover {
		/*pointer-events: none!important;*/
		-webkit-box-shadow: 1px 2px 2px 1px rgba(0, 0, 0, .2);
		box-shadow: 1px 2px 2px 1px rgba(0, 0, 0, .2);
	}

	.tooltip-inner {
		max-width: 70%;
		margin-left: auto;
		margin-right: auto;
		word-wrap: break-word;
	}

	.info-header p,
	.panel p,
	.panel h4 {
		margin: 5px 0px !important;
	}

	.tooltip-arrow,
	.red-tooltip+.tooltip>.tooltip-inner {
		background-color: #f9fafb;
		color: #3c3c3c;
		margin-top: -40px !important;
	}

	.tooltip.in {
		opacity: 1 !important;
		pointer-events: none !important;
	}

	.tooltip .tooltip-arrow {
		top: 15 !important;
		border-bottom-color: #f9fafb !important;
		/* black */
		background-color: transparent !important;
	}


	@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
		.totais {
			min-height: 63px !important;
		}

		.cartoes {
			min-height: 185px !important;
		}

		.destaque {
			min-height: 90px !important;
		}
	}

	/* (320x480) Smartphone, Portrait */
	@media only screen and (device-width: 320px) and (orientation: portrait) {
		.totais {
			min-height: 63px !important;
		}

		.cartoes {
			min-height: 185px !important;
		}

		.destaque {
			min-height: 90px !important;
		}
	}

	/* (320x480) Smartphone, Landscape */
	@media only screen and (device-width: 480px) and (orientation: landscape) {
		.totais {
			min-height: 63px !important;
		}

		.cartoes {
			min-height: 185px !important;
		}

		.destaque {
			min-height: 90px !important;
		}
	}

	/* (1024x768) iPad 1 & 2, Landscape */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
		.totais {
			min-height: 63px !important;
		}

		.cartoes {
			min-height: 185px !important;
		}

		.destaque {
			min-height: 90px !important;
		}
	}

	/* (1280x800) Tablets, Portrait */
	@media only screen and (max-width: 800px) and (orientation : portrait) {
		.totais {
			min-height: 63px !important;
		}

		.cartoes {
			min-height: 185px !important;
		}

		.destaque {
			min-height: 90px !important;
		}
	}

	/* (768x1024) iPad 1 & 2, Portrait */
	@media only screen and (max-width: 768px) and (orientation : portrait) {
		.totais {
			min-height: 63px !important;
		}

		.cartoes {
			min-height: 185px !important;
		}

		.destaque {
			min-height: 90px !important;
		}
	}

	/* (2048x1536) iPad 3 and Desktops*/
	@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
		.totais {
			min-height: 1px !important;
		}
	}

	@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
		.totais {
			min-height: 1px !important;
		}
	}

	@media (max-height: 824px) and (max-width: 416px) {
		.totais {
			min-height: 63px !important;
		}

		.cartoes {
			min-height: 185px !important;
		}

		.destaque {
			min-height: 90px !important;
		}
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
	@media (max-device-width: 737px) and (max-height: 416px) {
		.totais {
			min-height: 63px !important;
		}

		.cartoes {
			min-height: 185px !important;
		}

		.destaque {
			min-height: 90px !important;
		}
	}
</style>

<div class="push30"></div>

<div class="row" id="div_Report">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>

				<?php
				//$formBack = "1015";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>


				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<!-- <fieldset>
								<legend>Filtros</legend> 
							
								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data Inicial</label>
											
											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>">
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Data Final</label>
											
											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>">
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Valor do Investimento</label>
											<input type="text" class="form-control input-sm money" name="VL_INVEST" id="VL_INVEST">
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>				
									
								</div>
										
							</fieldset> -->

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type='hidden' name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>">
						<input type='hidden' name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>">
						<input type="hidden" name="CANAIS" id="CANAIS" value="<?= fnEncode($canais) ?>">
						<input type="hidden" name="COD_CAMPANHAS_PESQ" id="COD_CAMPANHAS_PESQ" value="<?= fnEncode($cod_campanhas) ?>">
						<input type="hidden" name="TIP_ROI_PESQ" id="TIP_ROI_PESQ" value="<?= fnEncode($tip_roi) ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

				</div>

			</div>

		</div>

		<div class="push30"></div>

		<!-- lalfklsklgklsdf <br/>
			lalfklsklgklsdf <br/>
			lalfklsklgklsdf <br/> -->


		<div class="portlet portlet-bordered" id="gera-pdf">

			<div class="portlet-body">

				<div class="row">

					<div class="col-xs-12 text-center">
						<h4 data-pdf="titulo">Análise Detalhada da Campanha</h4>
					</div>

					<div class="push10"></div>

				</div>

				<div class="row">

					<div class="col-md-10 col-md-offset-1">

						<div class="row">

							<div class="col-md-6">

								<div class="push20"></div>

								<div class="row">

									<div class="col-xs-12">

										<div class="panel panel-success caixa-texto">
											<div class="panel-heading">

												<div class="push20"></div>

												<?php



												?>

												<div class="col-xs-10 col-xs-offset-1">

													<!-- <p class="f14">Investimento Total</p>
														<h4><b>R$ 0,00</b></h4>

														<div class="push10"></div>
														<hr> -->
													<div class="push10"></div>
													<!-- <p class="f14">Resultado da Campanha</p> -->
													<?php if ($des_logo != '' && $des_logo != 0) { ?>
														<img class="img-responsive" src="https://img.bunker.mk/media/clientes/<?= $cod_empresa ?>/<?= $des_logo ?>" style="margin-left: auto; margin-right: auto;" width="250px">
													<?php } else { ?>
														<div class="push100"></div>
													<?php } ?>
													<div class="push5"></div>
													<p style="font-size: 48px; margin-bottom: 0px!important;"><b><span style="font-size: 32px;">R$</span> <span data-pdf="total_venda"><?= fnValor($total_venda, 2) ?></span></b></p>
													<p class="f14" style="margin-top: -10px!important;" data-pdf="total_venda_txt">em vendas incentivadas</p>
													<div class="push10"></div>
													<!-- <hr> -->
													<div class="push5"></div>
													<p class="f14" data-pdf="cli_qtd_compras_txt"><b class="f18"><?= fnValor($qtd_compras, 0) ?> clientes</b> com compras <span class="f10">(<?= fnValor($pctCliCompras, 2) ?>%)</span></p>
													<?php if ($qtd_inativo != 0 && $qtd_inativo != '') { ?>
														<!-- <p class="f14" data-pdf="cli_qtd_inativo_txt"><b class="f18"><?= fnValor($qtd_inativo, 0) ?></b> não compravam há <?= $dias_inativo ?> dias <span class="f10">(<?= fnValor($pctInativos, 2) ?>%)</span></p> -->
													<?php } ?>
													<p class="f14" data-pdf="roi_txt">ROI bruto de <b class="f18"><?= fnValor($roi, 0) ?>x</b></p>

												</div>


												<div class="push20"></div>

											</div>

										</div>

									</div>

								</div>

								<div class="push20"></div>

							</div>

							<div class="col-md-6">

								<div class="push20"></div>

								<div class="col-xs-12">

									<img data-pdf-img="img_det_camp" class="img-responsive" src="https://img.bunker.mk/media/clientes/3/roi_sms_email.jpg" style="margin-right: auto!important; margin-left: auto!important; max-height: 150px; border-radius: 6px;">

								</div>

								<div class="push10"></div>

								<div class="col-xs-12 info-header">

									<p class="f14"><b>DETALHES DA CAMPANHA</b></p>
									<hr>


									<?php

									// fnEscreve(strlen($campanhas));

									if (strlen($campanhas) <= 150) {
										$txtCamp = $campanhas;
									} else {
										$txtCamp = substr($campanhas, 0, 150) . '... <span data-html="true" data-toggle="tooltip" data-placement="bottom" data-original-title="' . "<span class='text-left'>" . $campanhas . "</span>" . '"><i class="fas fa-info-circle text-info"></i></span>';
									}



									?>

									<div class="col-md-6" data-pdf="camp_col1">
										<p class="f14"><b>Campanha</b></p>
										<p class="f14"><?= $txtCamp ?></p>
										<hr>
										<p class="f14"><b>Canal</b></p>
										<p class="f14"><?= $canais_com ?></p>
										<hr>
										<!-- <p class="f14"><b>Disparo</b></p>
											<p class="f14"><?= fnDataFull(date("Y-m-d H:i:s")) ?></p> -->
										<!--<hr>-->
									</div>
									<div class="col-md-6" data-pdf="camp_col2">
										<p class="f14"><b>Intervalo de Análise</b></p>
										<p class="f14"><?= fnDataShort($dat_ini) ?> à <?= fnDataShort($dat_fim) ?></p>
										<hr>
										<!-- <p class="f14"><b>Data de Avaliação</b></p>
											<p class="f14"><?= fnDataShort(date("Y-m-d")) ?></p>
											<hr> -->
										<p class="f14"><b>Grupo Alvo</b></p>
										<p class="f14"><?= fnValor($qtd_publico_tot, 0) ?></p>
										<hr>
										<p class="f14"><b>Clientes Únicos Grupo Alvo</b></p>
										<p class="f14"><?= fnValor($tot_clientes_uni_alvo, 0) ?> <span class="f10">(<?= fnValor($pctCliUniAlvo, 2) ?>%)</span></p>
										<hr>
										<p class="f14"><b>Clientes Únicos Alcançados</b></p>
										<p class="f14"><?= fnValor($tot_clientes_uni, 0) ?> <span class="f10">(<?= fnValor($pctCliUni, 2) ?>%)</span></p>
									</div>

								</div>

								<div class="push10"></div>

							</div>

						</div>

						<div class="push10"></div>

						<div class="row">

							<?php




							?>

							<div class="col-xs-3">

								<div class="panel panel-default red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
													<div class="row">
														<div class="col-xs-12 text-left">
															<div class="push10"></div>
															<p class="f14" style="margin: 0px!important;">Fat. Total Bruto advindo dos clientes únicos (sem repetição) que receberam a comunicação.</p>
															<div class="push10"></div>
														</div>
													</div>
													'>
									<div class="panel-heading totais"><b>Fat. Grupo Ação</b></div>
									<div class="panel-body"><span class="f18">R$ <span data-pdf="vl_grupo_acao"><?= fnValor($total_venda, 2) ?></span></span>
										<div class="push"></div>
										<div class="text-muted f14">&nbsp;</div>
									</div>
								</div>

							</div>

							<div class="col-xs-3">

								<div class="panel panel-default red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
													<div class="row">
														<div class="col-xs-12 text-left">
															<div class="push10"></div>
															<p class="f14" style="margin: 0px!important;">Fat. Total bruto proporcional advindo dos clientes que não receberam a comunicação.</p>
															<div class="push10"></div>
														</div>
													</div>
													'>
									<div class="panel-heading totais"><b>Fat. Grupo Controle</b></div>
									<div class="panel-body"><span class="f18">R$ <span data-pdf="vl_grupo_controle"><?= fnValor($fatGrupoControle, 2) ?></span><!-- <span id="fatGrupo"></span> --></span>
										<div class="push"></div>
										<div class="text-muted f14">&nbsp;</div>
									</div>
									<!-- <div class="panel-body"><span class="f18">R$ <?= fnValor($total_vendaGC, 2) ?></span> <div class="push"></div><div class="text-muted f14">&nbsp;</div></div> -->
								</div>

							</div>

							<div class="col-xs-3">

								<div class="panel panel-default red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
													<div class="row">
														<div class="col-xs-12 text-left">
															<div class="push10"></div>
															<p class="f14" style="margin: 0px!important;">Faturamento (-) Grupo de controle (x) Margem de contribuição</p>
															<div class="push10"></div>
														</div>
													</div>
													'>
									<div class="panel-heading totais"><b>Incremento <br />Margem Bruta</b></div>
									<div class="panel-body"><span class="f18">R$ <span data-pdf="vl_incremento"><?= fnValor($incrementoMargem, 2) ?></span></span>
										<div class="push"></div>
										<div class="text-muted f14"><span data-pdf="pc_incremento"><?= fnValor(($val_mbruta * 100), 2) ?></span>%</div>
									</div>
								</div>

							</div>

							<div class="col-xs-3">

								<div class="panel panel-default red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
													<div class="row">
														<div class="col-xs-12 text-left">
															<div class="push10"></div>
															<p class="f14" style="margin: 0px!important;">Valor investido com a(s) campanha(s).</p>
															<div class="push10"></div>
														</div>
													</div>
													'>
									<div class="panel-heading totais"><b>Investimento Total</b></div>
									<div class="panel-body"><span class="f18">R$ <span data-pdf="vl_invest"><?= fnValor($invest, 2) ?></span></span>
										<div class="push"></div>
										<div class="text-muted f14"><span data-pdf="pc_invest"><?= fnValor($pctInvest, 2) ?></span>%</div>
									</div>
								</div>

							</div>

						</div>

						<!-- <div class="row">
								
								<div class="col-xs-3">
									
									<div class="panel panel-default cartoes">
										<div class="panel-heading totais"><b>Clientes Impactados</b></div>
										<div class="panel-body"><span class="f18">1.234</span> <div class="push"></div><div class="text-muted f14">99%</div></div>
									</div>

								</div>

								<div class="col-xs-3">

									<div class="panel panel-default cartoes">
										<div class="panel-heading totais"><b>Clientes que Retornaram</b></div>
										<div class="panel-body"><span class="f18">999</span> <div class="push"></div><div class="text-muted f14">99%</div></div>
									</div>

								</div>

								<div class="col-xs-3">

									<div class="panel panel-default cartoes">
										<div class="panel-heading totais"><b>Compras Realizadas</b></div>
										<div class="panel-body"><span class="f18">5.432</span> <div class="push"></div><div class="text-muted f14">99%</div></div>
									</div>

								</div>

								<div class="col-xs-3">

									<div class="panel panel-default cartoes">
										<div class="panel-heading totais"><b>Itens Comprados</b></div>
										<div class="panel-body"><span class="f18">9.876</span> <div class="push"></div><div class="text-muted f14">99%</div></div>
									</div>

								</div>

							</div> -->

						<div class="row">

							<div class="col-xs-3 col-xs-offset-3">

								<div class="panel panel-defaultred-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
													<div class="row">
														<div class="col-xs-12 text-left">
															<div class="push10"></div>
															<p class="f14" style="margin: 0px!important;">Margem incremental (-) custo.</p>
															<div class="push10"></div>
														</div>
													</div>
													'>
									<div class="panel-heading totais"><b>Resultado</b></div>
									<div class="panel-body"><span class="f18">R$ <span data-pdf="vl_resultado"><?= fnValor($resultado, 2) ?></span></span>
										<div class="push"></div>
										<div class="text-muted f14"><span data-pdf="pc_resultado"><?= fnValor($percResultado, 2) ?></span>%</div>
									</div>
								</div>

							</div>

							<div class="col-xs-3">

								<div class="panel panel-default">
									<div class="panel-heading totais"><b>ROI Comparativo</b></div>
									<div class="panel-body"><span class="f18"><span data-pdf="vl_roi"><?= fnValor($roiComparativo / 100, 1) ?>x</span></span>
										<div class="push"></div>
										<div class="text-muted f14">&nbsp;</div>
									</div>
								</div>

							</div>

						</div>

						<div class="push10"></div>

						<div class="row">

							<div class="col-xs-12 info-dash">

								<div class="panel panel-default cartoes">
									<div class="panel-heading" style="font-size: 19px;"><b>Análise de Grupos</b></div>
									<div class="panel-body">

										<div class="<?= $colAcao ?>">

											<div class="row">

												<?php

												$pct_engajamentoAc = $tot_clientes_uni != 0 ? (($qtd_compras) / $tot_clientes_uni) * 100 : 0;

												?>

												<div class="col-xs-12 <?= $alinhaAcao ?>" data-pdf="txt_grupo_acao">

													<p class="f18"><b>Grupo Ação</b></p>
													<p class="f12">Receberam Comunicação</p>
													<p class="f16"><b><?= fnValor($tot_clientes_uni, 0) ?> Clientes</b></p>

												</div>

												<div class="push10"></div>

												<div class="<?= $balaoAcao ?>">
													<div class="panel panel-default">
														<div class="panel-body" data-pdf="txt_taxa_engajamento">

															<div class="push20"></div>
															<p class="f10">Taxa Engajamento</p>
															<p class="text-success" style="font-size: 32px !important;"><b><?= fnValor($pct_engajamentoAc, 2) ?>%</b></p>
															<div class="push20"></div>

														</div>

													</div>

												</div>

												<div class="push"></div>

												<div class="<?= $balaoAcao ?>">
													<span class="fas fa-caret-down fa-2x text-success"></span>
												</div>

												<div class="push10"></div>

												<div class="<?= $balaoAcao ?>">
													<div class="panel panel-default cartoes">
														<div class="panel-body" data-pdf="txt_info_tr">
															<p class="f10">Ticket Médio por Compra</p>
															<p class="f16"><b>R$ <?= fnValor($TMAcGeral, 2) ?></b></p>
															<p class="f10"><?= fnValor(($qtd_compras_total), 0) ?> Compras</p>
															<div class="push5"></div>
															<p class="f10">Gasto Médio por Cliente</p>
															<p class="f16"><b>R$ <?= fnValor($GMAcGeral, 2) ?></b></p>
															<p class="f10"><?= fnValor(($qtd_compras), 0) ?> Clientes</p>
															<div class="push5"></div>
															<p class="f10">Total de Itens</p>
															<p class="f16"><b><?= fnValor($total_itens, 0) ?></b></p>
															<div class="push5"></div>
															<p class="f10">Itens por Transação</p>
															<p class="f16"><b><?= fnValor($itensTransac, 2) ?></b></p>
															<div class="push5"></div>
															<p class="f10">Itens por Cliente</p>
															<p class="f16"><b><?= fnValor($clientesTransac, 2) ?></b></p>
															<div class="push5"></div>
															<p class="f10">Transações por Cliente</p>
															<p class="f16"><b><?= fnValor($qtd_compras != 0 ? ($qtd_compras_total / $qtd_compras) : 0, 2) ?></b></p>
														</div>
													</div>
												</div>

											</div>

										</div>

										<div class="col-xs-6" style="display: <?= $displayCont ?>">

											<div class="row">

												<?php

												$pct_engajamentoCt = $tot_nRecebeu != 0 ? (($qtd_comprasGC) / $tot_nRecebeu) * 100 : 0;

												?>

												<div class="col-xs-12 text-left">

													<p class="f18"><b>Grupo Controle</b></p>
													<p class="f12">Não Receberam Comunicação</p>
													<p class="f16"><b><?= fnValor($tot_nRecebeu, 0) ?> Clientes</b></p>

												</div>

												<div class="push10"></div>

												<div class="col-xs-7 ">
													<div class="panel panel-default">
														<div class="panel-body">

															<div class="push20"></div>
															<p class="f10">Taxa de Engajamento</p>
															<p class="text-primary" style="font-size: 32px;"><b><?= fnValor($pct_engajamentoCt, 2) ?>%</b></p>
															<div class="push20"></div>

														</div>
													</div>

												</div>

												<div class="push"></div>

												<div class="col-xs-7">
													<span class="fas fa-caret-down fa-2x text-primary"></span>
												</div>

												<div class="push10"></div>

												<div class="col-xs-7">
													<div class="panel panel-default cartoes">
														<div class="panel-body">
															<p class="f10">Ticket Médio por Compra</p>
															<p class="f16"><b>R$ <?= fnValor($TMAcGeralGC, 2) ?></b></p>
															<p class="f10"><?= fnValor(($qtd_compras_totalGC), 0) ?> Compras</p>
															<div class="push5"></div>
															<p class="f10">Gasto Médio por Cliente</p>
															<p class="f16"><b>R$ <?= fnValor($GMAcGeralGC, 2) ?></b></p>
															<p class="f10"><?= fnValor(($qtd_comprasGC), 0) ?> Clientes</p>
															<div class="push5"></div>
															<p class="f10">Total de Itens</p>
															<p class="f16"><b><?= fnValor($total_itensGC, 0) ?></b></p>
															<div class="push5"></div>
															<p class="f10">Itens por Transação</p>
															<p class="f16"><b><?= fnValor($itensTransacGC, 2) ?></b></p>
															<div class="push5"></div>
															<p class="f10">Itens por Cliente</p>
															<p class="f16"><b><?= fnValor($clientesTransacGC, 2) ?></b></p>
															<div class="push5"></div>
															<p class="f10">Transações por Cliente</p>
															<p class="f16"><b><?= fnValor(($qtd_compras_totalGC / $qtd_comprasGC), 2) ?></b></p>
														</div>
													</div>
												</div>

											</div>

										</div>

										<?php if ($verifica > 0 && $TMResgAc > 0) { ?>



											<div class="col-md-6 col-md-offset-3">

												<p class="f14"><b>RESULTADOS DO CASHBACK</b></p>
												<div class="push10"></div>
												<a href="havascript:void(0)" style="color: #fff;" class="btn btn-sm btn-success" onclick="expandeCash(this)">ver detalhes</a>
												<div class="push20"></div>

											</div>


											<div id="cashback" style="display:none;">

												<div class="<?= $colAcao ?>">

													<div class="row">
														<div class="col-xs-12 text-center">
															<p class="f18"><b>Grupo Ação</b></p>
														</div>
													</div>

													<div class="row">

														<?php

														$qtd_compras_total_sr = $qtd_compras_total - $qtdTransResgAc;
														$qtdCliResgAc_sr = $qtd_compras - $qtdCliResgAc;
														$pctTransResg = $qtd_compras_total != 0 ? (($qtdTransResgAc) / $qtd_compras_total) * 100 : 0;
														$pctCliResg = $qtd_compras != 0 ? (($qtdCliHibridoAc) / $qtd_compras) * 100 : 0;

														$vendasGaSr = ($total_venda - $valTotVendaResgAc);

														$GMAc = $vendasGaSr / $qtdCliHibridoAc;
														$TMAc = ($total_venda - $valTotVendaResgAc) / ($qtd_compras_total - $qtdTransResgAc);

														?>

														<div class="col-xs-6">
															<div class="panel panel-default cartoes" data-pdf="txt_grupo_acao_1">
																<div class="panel-body">
																	<p class="f10">Ticket Médio SR</p>
																	<p class="f16"><b>R$ <?= fnValor($TMAc, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtd_compras_total_sr, 0) ?> Compras (<?= fnValor((100 - $pctTransResg), 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">Gasto Médio SR</p>
																	<p class="f16"><b>R$ <?= fnValor($GMAc, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtdCliHibridoAc, 0) ?> Clientes (<?= fnValor((100 - $pctCliResg), 2) ?>%)</p>
																	<p class="f10"><?= fnValor($qtdCliHibridoAc - $qtdCliResgAc_sr, 0) ?> Híbridos (SR e CR)</p>
																</div>
															</div>
														</div>

														<div class="col-xs-6">
															<div class="panel panel-default cartoes" data-pdf="txt_grupo_acao_2">
																<div class="panel-body">
																	<p class="f10">Ticket Médio CR (Líquido)</p>
																	<p class="f16"><b>R$ <?= fnValor($TMResgAc, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtdTransResgAc, 0) ?> Compras (<?= fnValor($pctTransResg, 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">Gasto Médio CR (Líquido)</p>
																	<p class="f16"><b>R$ <?= fnValor($GMResgAc, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtdCliResgAc, 0) ?> Clientes (<?= fnValor($pctCliResg, 2) ?>%)</p>
																	<p class="f10">&nbsp;</p>
																</div>
															</div>
														</div>

													</div>

													<div class="row">

														<?php

														$total_itens_sr = $total_itens - $total_itens_resgate;
														$itensTransac_sr = $total_itens_sr / $qtd_compras_total_sr;
														$clientesTransac_sr = $total_itens_sr / $qtdCliResgAc_sr;
														$qtdTransCli_sr = $qtd_compras_total_sr / $qtdCliResgAc_sr;

														?>

														<div class="col-xs-6">
															<div class="panel panel-default cartoes" data-pdf="txt_grupo_acao_3">
																<div class="panel-body">
																	<p class="f10">Total de Itens SR</p>
																	<p class="f16"><b><?= fnValor($total_itens_sr, 0) ?></b></p>
																	<p class="f10">(<?= fnValor((100 - $pctItensResg), 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">Itens por Transação SR</p>
																	<p class="f16"><b><?= fnValor($itensTransac_sr, 2) ?></b></p>
																	<div class="push5"></div>
																	<p class="f10">Itens por Cliente SR</p>
																	<p class="f16"><b><?= fnValor($clientesTransac_sr, 2) ?></b></p>
																	<div class="push5"></div>
																	<p class="f10">Transações por Cliente SR</p>
																	<p class="f16"><b><?= fnValor($qtdTransCli_sr, 2) ?></b></p>
																</div>
															</div>
														</div>

														<div class="col-xs-6">
															<div class="panel panel-default cartoes" data-pdf="txt_grupo_acao_4">
																<div class="panel-body">
																	<p class="f10">Total de Itens CR</p>
																	<p class="f16"><b><?= fnValor($total_itens_resgate, 0) ?></b></p>
																	<p class="f10">(<?= fnValor($pctItensResg, 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">Itens por Transação CR</p>
																	<p class="f16"><b><?= fnValor($itensTransacResg, 2) ?></b></p>
																	<div class="push5"></div>
																	<p class="f10">Itens por Cliente CR</p>
																	<p class="f16"><b><?= fnValor($clientesTransacResg, 2) ?></b></p>
																	<div class="push5"></div>
																	<p class="f10">Transações por Cliente CR</p>
																	<p class="f16"><b><?= fnValor(($qtdTransResgAc / $qtdCliResgAc), 2) ?></b></p>
																</div>
															</div>
														</div>

													</div>

													<div class="row">

														<div class="col-xs-6">
															<div class="panel panel-default cartoes" data-pdf="txt_grupo_acao_5">
																<div class="panel-body">
																	<p class="f10">Vendas SR</p>
																	<p class="f16"><b>R$ <?= fnValor($vendasGaSr, 2) ?></b></p>
																	<p class="f10">(<?= fnValor((100 - $pctVvrFaturAc), 2) ?>% Fat.)</p>
																</div>
															</div>
														</div>

														<div class="col-xs-6">
															<div class="panel panel-default cartoes" data-pdf="txt_grupo_acao_6">
																<div class="panel-body">
																	<p class="f10">Vendas Vinculadas a Resgate</p>
																	<p class="f16"><b>R$ <?= fnValor($valTotVendaResgAc, 2) ?></b></p>
																	<p class="f10">(<?= fnValor($pctVvrFaturAc, 2) ?>% Fat.)</p>
																	<div class="push5"></div>
																	<p class="f10">VVR %</p>
																	<p class="f16"><b><?= fnValor($percVvrAc, 0) ?>%</b></p>
																</div>
															</div>
														</div>

													</div>

													<div class="row">

														<div class="col-xs-6 col-xs-offset-6">
															<div class="panel panel-default cartoes" data-pdf="txt_grupo_acao_7">
																<div class="panel-body">
																	<p class="f10">Valor Total de Resgate</p>
																	<p class="f16"><b>R$ <?= fnValor($totResgAc, 2) ?></b></p>
																	<p class="f10">(<?= fnValor($pctTotResgAc, 2) ?>% Fat.)</p>
																	<div class="push5"></div>
																	<p class="f10">Resgate Médio por Transação</p>
																	<p class="f16"><b>R$ <?= fnValor($VRMTransAc, 2) ?></b></p>
																	<div class="push5"></div>
																	<p class="f10">Resgate Médio por Cliente</p>
																	<p class="f16"><b>R$ <?= fnValor($VRMCliAc, 2) ?></b></p>
																</div>
															</div>
														</div>

													</div>

													<div class="row">

														<!-- bonus_extrasAC
bonus_resgatadosAC
qtd_cliente_extrasAC
qtd_clientes_resgatadosAC -->
														<?php

														$pctCliExGa = $qtd_publico != 0 ? (($qtd_cliente_extrasAC) / $qtd_publico) * 100 : 0;
														$pctCliBonGa = $qtd_cliente_extrasAC != 0 ? (($qtd_clientes_resgatadosAC) / $qtd_cliente_extrasAC) * 100 : 0;
														$concedidosAc = $bonus_extrasAC / $qtd_cliente_extrasAC;
														$resgatadosAc = $bonus_resgatadosAC / $qtd_clientes_resgatadosAC;

														// fnEscreve($pctCliBonGa);

														?>

														<div class="col-xs-6 col-xs-offset-6">
															<div class="panel panel-default cartoes" data-pdf="txt_grupo_acao_8">
																<div class="panel-body">
																	<p class="f10">BONUS EXTRAS</p>
																	<p class="f16"><b>R$ <?= fnValor($bonus_extrasAC, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtd_cliente_extrasAC, 0) ?> Clientes (<?= fnValor($pctCliExGa, 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">BONUS RESGATADOS</p>
																	<p class="f16"><b>R$ <?= fnValor($bonus_resgatadosAC, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtd_clientes_resgatadosAC, 0) ?> Clientes (<?= fnValor($pctCliBonGa, 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">BONUS MÉDIOS</p>

																	<div class="col-xs-6">
																		<p class="f16"><b>R$ <?= fnValor($concedidosAc, 2) ?></b></p>
																		<p class="f10">Concedidos</p>
																	</div>

																	<div class="col-xs-6">
																		<p class="f16"><b>R$ <?= fnValor($resgatadosAc, 2) ?></b></p>
																		<p class="f10">Resgatados</p>
																	</div>

																</div>
															</div>
														</div>

													</div>

												</div>

												<div class="col-xs-6" style="display: <?= $displayCont ?>">

													<div class="row">
														<div class="col-xs-12 text-center">
															<p class="f18"><b>Grupo Controle</b></p>
														</div>
													</div>

													<div class="row">

														<?php

														$vendasGcSr = $total_vendaGC - $valTotVendaResgAcGC;
														$qtdCliGcSr = ($qtd_comprasGC - $qtdCliResgAcGC);

														$qtdTransGcSr = $qtd_compras_totalGC - $qtdTransResgAcGC;

														$GMAcGC = $qtdCliGcSr != 0 ? (($vendasGcSr) / $qtdCliGcSr) : 0;

														$pctTransResgGC = $qtd_compras_totalGC != 0 ? (($qtdTransResgAcGC) / $qtd_compras_totalGC) * 100 : 0;
														$pctTransGcSr = $qtd_compras_totalGC != 0 ? (($qtdTransGcSr) / $qtd_compras_totalGC) * 100 : 0;

														$itensTransacGCSr = $total_itens_srGC / $qtdTransGcSr;
														$clientesTransacGCSr = $total_itens_srGC / $qtdCliGcSr;

														?>

														<div class="col-xs-6">
															<div class="panel panel-default cartoes">
																<div class="panel-body">
																	<p class="f10">Ticket Médio CR (Líquido)</p>
																	<p class="f16"><b>R$ <?= fnValor($TMResgAcGC, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtdTransResgAcGC, 0) ?> Compras (<?= fnValor($pctTransResgGC, 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">Gasto Médio CR (Líquido)</p>
																	<p class="f16"><b>R$ <?= fnValor($GMResgAcGC, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtdCliResgAcGC, 0) ?> Clientes (<?= fnValor($pctCliResgGC, 2) ?>%)</p>
																	<!-- <p class="f10">(<?= fnValor($pctVvrFaturAc, 2) ?>%)</p> -->
																</div>
															</div>
														</div>

														<div class="col-xs-6">
															<div class="panel panel-default cartoes">
																<div class="panel-body">
																	<p class="f10">Ticket Médio SR</p>
																	<p class="f16"><b>R$ <?= fnValor($TMAcGC, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtdTransGcSr, 0) ?> Compras (<?= fnValor($pctTransGcSr, 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">Gasto Médio SR</p>
																	<p class="f16"><b>R$ <?= fnValor($GMAcGC, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtdCliGcSr, 0) ?> Clientes (<?= fnValor((100 - $pctCliResgGC), 2) ?>%)</p>
																</div>
															</div>
														</div>

													</div>

													<div class="row">

														<div class="col-xs-6">
															<div class="panel panel-default cartoes">
																<div class="panel-body">
																	<p class="f10">Total de Itens CR</p>
																	<p class="f16"><b><?= fnValor($total_itens_resgateGC, 0) ?></b></p>
																	<p class="f10">(<?= fnValor($pctItensResgGC, 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">Itens por Transação CR</p>
																	<p class="f16"><b><?= fnValor($itensTransacResgGC, 2) ?></b></p>
																	<div class="push5"></div>
																	<p class="f10">Itens por Cliente CR</p>
																	<p class="f16"><b><?= fnValor($clientesTransacResgGC, 2) ?></b></p>
																	<div class="push5"></div>
																	<p class="f10">Transações por Cliente CR</p>
																	<p class="f16"><b><?= fnValor(($qtdTransResgAcGC / $qtdCliResgAcGC), 2) ?></b></p>
																</div>
															</div>
														</div>

														<div class="col-xs-6">
															<div class="panel panel-default cartoes">
																<div class="panel-body">
																	<p class="f10">Total de Itens SR</p>
																	<p class="f16"><b><?= fnValor($total_itens_srGC, 0) ?></b></p>
																	<p class="f10">(<?= fnValor((100 - $pctItensResgGC), 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">Itens por Transação SR</p>
																	<p class="f16"><b><?= fnValor($itensTransacGCSr, 2) ?></b></p>
																	<div class="push5"></div>
																	<p class="f10">Itens por Cliente SR</p>
																	<p class="f16"><b><?= fnValor($clientesTransacGCSr, 2) ?></b></p>
																	<div class="push5"></div>
																	<p class="f10">Transações por Cliente SR</p>
																	<p class="f16"><b><?= fnValor((($qtd_comprasGC - $qtdCliResgAcGC) / ($qtd_compras_totalGC - $qtdTransResgAcGC)), 2) ?></b></p>
																</div>
															</div>
														</div>

													</div>

													<div class="row">

														<div class="col-xs-6">
															<div class="panel panel-default cartoes">
																<div class="panel-body">
																	<p class="f10">Vendas Vinculadas a Resgate</p>
																	<p class="f16"><b>R$ <?= fnValor($valTotVendaResgAcGC, 2) ?></b></p>
																	<p class="f10">(<?= fnValor($pctVvrFaturAcGC, 2) ?>% Fat.)</p>
																	<div class="push5"></div>
																	<p class="f10">VVR %</p>
																	<p class="f16"><b><?= fnValor($percVvrAcGC, 0) ?>%</b></p>
																</div>
															</div>
														</div>

														<div class="col-xs-6">
															<div class="panel panel-default cartoes">
																<div class="panel-body">
																	<p class="f10">Vendas SR</p>
																	<p class="f16"><b>R$ <?= fnValor($vendasGcSr, 2) ?></b></p>
																	<p class="f10">(<?= fnValor((100 - $pctVvrFaturAcGC), 2) ?>% Fat.)</p>
																</div>
															</div>
														</div>

													</div>

													<div class="row">

														<div class="col-xs-6">
															<div class="panel panel-default cartoes">
																<div class="panel-body">
																	<p class="f10">Valor Total de Resgate</p>
																	<p class="f16"><b>R$ <?= fnValor($totResgAcGC, 2) ?></b></p>
																	<p class="f10">(<?= fnValor($pctTotResgAcGC, 2) ?>% Fat.)</p>
																	<div class="push5"></div>
																	<p class="f10">Resgate Médio por Transação</p>
																	<p class="f16"><b>R$ <?= fnValor($VRMTransAcGC, 2) ?></b></p>
																	<div class="push5"></div>
																	<p class="f10">Resgate Médio por Cliente</p>
																	<p class="f16"><b>R$ <?= fnValor($VRMCliAcGC, 2) ?></b></p>
																</div>
															</div>
														</div>

													</div>

													<div class="row">

														<?php

														$pctCliExGC = $qtd_publico != 0 ? (($qtd_cliente_extrasGC) / $qtd_publico) * 100 : 0;
														$pctCliBonGC = $qtd_cliente_extrasGC != 0 ? (($qtd_clientes_resgatadosGC) / $qtd_cliente_extrasGC) * 100 : 0;
														$concedidosgc = $bonus_extrasGC / $qtd_cliente_extrasGC;
														$resgatadosgc = $bonus_resgatadosGC / $qtd_clientes_resgatadosGC;

														?>

														<div class="col-xs-6">
															<div class="panel panel-default cartoes">
																<div class="panel-body">
																	<p class="f10">BONUS EXTRAS</p>
																	<p class="f16"><b>R$ <?= fnValor($bonus_extrasGC, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtd_cliente_extrasGC, 0) ?> Clientes (<?= fnValor($pctCliExGC, 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">BONUS RESGATADOS</p>
																	<p class="f16"><b>R$ <?= fnValor($bonus_resgatadosGC, 2) ?></b></p>
																	<p class="f10"><?= fnValor($qtd_clientes_resgatadosGC, 0) ?> Clientes (<?= fnValor($pctCliBonGC, 2) ?>%)</p>
																	<div class="push5"></div>
																	<p class="f10">BONUS MÉDIOS</p>

																	<div class="col-xs-6">
																		<p class="f16"><b>R$ <?= fnValor($concedidosgc, 2) ?></b></p>
																		<p class="f10">Concedidos</p>
																	</div>

																	<div class="col-xs-6">
																		<p class="f16"><b>R$ <?= fnValor($resgatadosgc, 2) ?></b></p>
																		<p class="f10">Resgatados</p>
																	</div>

																</div>
															</div>
														</div>

													</div>

												</div>

											</div>

										<?php } ?>

										<div class="push"></div>

										<div class="<?= $colAcao ?>">

											<?php

											$fatGCProporcional = $qtd_comprasGC != 0 ? ($fatGAGC / $qtd_comprasGC) : 0 * $qtd_compras;

											?>

											<div class="row">

												<div class="<?= $balaoAcao ?> red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
														<div class="row">
															<div class="col-xs-12 text-left">
																<div class="push10"></div>
																<p class="f14" style="margin: 0px!important;">Faturamento limpo, já descontado resgate total e investimento da comunicação.</p>
																<div class="push10"></div>
															</div>
														</div>
														'>
													<div class="panel panel-primary ">
														<div class="panel-heading destaque" data-pdf="txt_fat_grupo_acao">
															<div class="push5"></div>
															<p class="f10">Fat. Grupo de Ação</p>
															<p class="f10">(<?= fnValor($qtd_compras, 0) . " de " . fnValor($tot_clientes_uni, 0) ?> Clientes)</p>
															<p class="f21"><b><span class="f10">R$</span> <?= fnValor($fatGA, 2) ?></b></p>
														</div>
													</div>
												</div>

											</div>

										</div>

										<div class="col-xs-6">

											<div class="row">

												<div class="col-xs-7 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
														<div class="row">
															<div class="col-xs-12 text-left">
																<div class="push10"></div>
																<p class="f14" style="margin: 0px!important;">Faturamento limpo, já descontado resgate total.</p>
																<div class="push10"></div>
															</div>
														</div>
														' style="display: <?= $displayCont ?>">
													<div class="panel panel-primary">
														<div class="panel-heading destaque">
															<div class="push5"></div>
															<p class="f10">Fat. Grupo de Controle</p>
															<p class="f10">(<?= fnValor($qtd_comprasGC, 0) . " de " . fnValor($tot_nRecebeu, 0) ?> Clientes)</p>
															<p class="f21"><b><span class="f10">R$</span> <?= fnValor($fatGAGC, 2) ?></b></p>
														</div>
													</div>
												</div>

											</div>

										</div>

										<div class="push"></div>

										<div class="col-xs-6 col-xs-offset-3 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
													<div class="row">
														<div class="col-xs-12 text-center">
															<div class="push10"></div>
															<p class="f14" style="margin: 0px!important;"><b>Detalhes do Resultado</b></p>
															<hr style="margin: 5px 0px!important;">
															<p class="f12">Com base no comportamento de compra do Grupo de Controle (clientes que não receberam comunicação), foi gerado proporcionalmente um faturamento de <b>R$ <?= fnValor($fatGCProporcional, 2) ?></b></p>
															<div class="push10"></div>
															<p class="f12">Por impacto da comunicação, o faturamento foi de <b>R$<?= fnValor($fatGA, 2) ?></b></p>
															<div class="push10"></div>
															<!-- <p class="f12"><b>O resultado real da ação foi de: <br/><span style="font-size:28px; font-weight: bolder;">R$ <?= fnValor($fatGA - $fatGAGC, 2) ?></span></b></p> -->
															<p class="f12"><b>O resultado real da ação foi de: <br/><span style="font-size:28px; font-weight: bolder;">R$ <?= fnValor($fatGA - $fatGCProporcional, 2) ?></span></b></p>
															<div class="push10"></div>
														</div>
													</div>
													'>

											<div class="row">

												<div class="col-xs-10 col-xs-offset-1">
													<div class="panel panel-success">
														<div class="panel-heading">
															<p class="f18"><b>Resultado</b></p>
														</div>
													</div>
												</div>

											</div>

										</div>

									</div>
								</div>

							</div>

						</div>

					</div>

				</div>

				<div class="push50"></div>

				<div class="push"></div>

			</div>

		</div>

	</div>
	<!-- fim Portlet -->
</div>

</div>

<div class="row">
	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-body">
				<button class="btn btn-info" onClick="gerarPDF()">
					<i class="fa fa-file-pdf" aria-hidden="true"></i>
					&nbsp; Gerar PDF
				</button>
				<div class="push20"></div>
			</div>
		</div>
	</div>
</div>

<?php

// CALCULO FATURAMENTO GRUPO DE CONTROLE

$pctGrupo = $pct_engajamentoCt / 100;

// fnEscreve($tot_clientes_uni);
// fnEscreve($pctGrupo);
// fnEscreve($GMAcGeralGC);

$fatGrupoControle = round($tot_clientes_uni, 4) * round($pctGrupo, 4) * round($GMAcGeralGC, 4);

?>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	//datas
	$(function() {

		$("#fatGrupo").text("<?= fnValor($fatGrupoControle, 2) ?>");

		// var numPaginas = <?php echo $numPaginas; ?>;
		// if(numPaginas != 0){
		// 	carregarPaginacao(numPaginas);
		// }

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		$("#DAT_FIM_GRP").on("dp.change", function(e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});


	});

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelLogUsuarios.do?idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function(data) {
				console.log(data);
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	var expandir = 0;

	function expandeCash(btn) {

		if (expandir == 0) {
			$('#cashback').fadeIn('fast');
			$(btn).html('ver menos').removeClass('btn-success').addClass('btn-info');
			expandir = 1;
		} else {
			$('#cashback').fadeOut('fast');
			$(btn).html('ver detalhes').removeClass('btn-info').addClass('btn-success');
			expandir = 0;
		}

	}


	function gerarPDF() {
		var html = "";
		if ($("#form_pdf").length <= 0) {
			html = "<form target='_blank' style='display:none;' method='post' id='form_pdf' action='relatorios/pdfRelRoiSms.php'></form>";
			$("body").append(html);
		}

		$("#form_pdf").html("");
		$("[data-pdf]").each(function(index) {
			$("#form_pdf").append("<textarea name='" + $(this).attr("data-pdf") + "'>" + $(this).html() + "</textarea>");
		});
		$("[data-pdf-img]").each(function(index) {
			$("#form_pdf").append("<textarea name='" + $(this).attr("data-pdf-img") + "'>" + $(this).attr("src") + "</textarea>");
		});

		$("#form_pdf").submit();
	}
</script>