<?php 

	include '_system/_functionsMain.php'; 

	$opcao = fnLimpaCampo($_GET['OPCAO']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['COD_EMPRESA']));
	$cod_mes = fnLimpaCampoZero(fnDecode($_POST['COD_MES']));
	

	// fnEscreve($opcao);

	switch ($opcao) {

		case 'salario':

			$cod_cliente = fnLimpaCampoZero($_POST['COD_CLIENTE']);

			$sqlSal = "SELECT VAL_LANCAME AS VAL_SALBASE FROM LANCAMENTO_AUTOMATICO LA 
						WHERE LA.COD_EMPRESA = $cod_empresa 
						AND LA.COD_CLIENTE = $cod_cliente
						AND LA.COD_TIPO = 1";														

					//fnEscreve($sql);

			$arraySal = mysqli_query(connTemp($cod_empresa,''),$sqlSal);

			$qrSal = mysqli_fetch_assoc($arraySal);

			$salario_base = $qrSal[VAL_SALBASE];

			$sql = "UPDATE CAIXA 
					SET VAL_CREDITO = '$salario_base'
					where CAIXA.COD_CONTRAT=$cod_cliente 
					AND CAIXA.COD_EMPRESA=$cod_empresa 
					AND CAIXA.COD_MES = $cod_mes
					AND CAIXA.DAT_EXCLUSA IS NULL
					AND CAIXA.COD_EXCLUSA = 0
					AND CAIXA.TIP_LANCAME = 'F'
					AND CAIXA.COD_TIPO = 1";
			
			// fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa,''),$sql);

			echo $cod_cliente;

		break;

		case 'expandir':

			$cod_cliente = fnLimpaCampoZero($_POST['COD_CLIENTE']);
			

		?>

			<table class="table" style="width: auto;">
				<tr>
					<td colspan="4"><small><a href="javascript:void(0)" id="btnNovo" class="btn btn-info btn-xs addBox" data-url="action.php?mod=<?php echo fnEncode(1705)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idm=<?=fnEncode($cod_mes)?>&pop=true" data-title="Cadastro de Lançamento" onclick='$("#CLIENTE_DETALHE").val("<?=$cod_cliente?>")'><span class="fal fa-plus"></span>&nbsp; Cadastrar novo lançamento</a></small></td>
					<td colspan="2"></td>
				</tr>
			
				<tr>
				<th><small>Dt. Lança.</small></th>
				<th><small>Op.</small></th>
				<th class="text-right"><small>Tipo</small></th>
				<th class="text-center"><small>Dias</small></th>
				<th class="text-right"><small>Vl.</small></th>
				</tr>
				
				<?php 																	
					$sql = "SELECT 	CAIXA.VAL_CREDITO,
									CAIXA.COD_CAIXA,
									CAIXA.PCT_EXTRA,
									TIP_CREDITO.COD_TIPO,
									TIP_CREDITO.DES_TIPO,
									TIP_CREDITO.TIP_OPERACAO,
									CAIXA.DAT_LANCAME,
									CAIXA.NUM_DIA
							FROM CAIXA
							left join TIP_CREDITO on caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
							where CAIXA.COD_CONTRAT=$cod_cliente 
							AND CAIXA.COD_EMPRESA=$cod_empresa 
							AND CAIXA.COD_MES = $cod_mes
							AND CAIXA.DAT_EXCLUSA IS NULL
							AND CAIXA.COD_EXCLUSA = 0
							AND CAIXA.TIP_LANCAME = 'F'
							ORDER BY CAIXA.DAT_LANCAME DESC";
							
							// fnEscreve($sql);
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
							
							$count=0;
							$val_total = 0;
							$dat_ref = "";
							while ($qrListaCaixa = mysqli_fetch_assoc($arrayQuery))
							  {														  

								if ($dat_ref !=  $qrListaCaixa['DAT_LANCAME'] || $count == 0){

									$dat_ref = $qrListaCaixa['DAT_LANCAME'];
									$dat_lancame = $dat_ref;	

								} else {

									$dat_lancame = "";	

								}
								
								$tip_operacao = $qrListaCaixa['TIP_OPERACAO'];
								
								if ($tip_operacao == "D") {
									$corTexto = "text-danger";
									$val_total -= $qrListaCaixa['VAL_CREDITO'];
								} else { 
									$corTexto = ""; 
									$val_total += $qrListaCaixa['VAL_CREDITO'];
								} 

								if($qrListaCaixa[COD_TIPO] == 4){
									$dias = fnValor($qrListaCaixa['PCT_EXTRA'],0)."%";
								}else{
									$dias = fnValor($qrListaCaixa['NUM_DIA'],0);
								}

								$sqlSal = "SELECT VAL_LANCAME AS VAL_SALBASE FROM LANCAMENTO_AUTOMATICO LA 
											WHERE LA.COD_EMPRESA = $cod_empresa 
											AND LA.COD_CLIENTE = $cod_cliente
											AND LA.COD_TIPO = 1";															

										//fnEscreve($sql);

								$arraySal = mysqli_query(connTemp($cod_empresa,''),$sqlSal);

								$qrSal = mysqli_fetch_assoc($arraySal);

								$salario_base = fnValorSql(fnValor($qrSal[VAL_SALBASE],2));

								?>																			  
									<tr codItemVenda="<?php echo $qrListaCaixa['COD_ITEMVEN'];?>">
										<td><small><?=fnDataShort($qrListaCaixa['DAT_LANCAME'])?></small></td>
										<td><small><div><?=$qrListaCaixa['DES_TIPO']?></div></small></td>
										<td class="text-right <?=$corTexto?>"><small><div><?=$tip_operacao?></div></small></td>
										<td class="text-center"><small><?=$dias?></small></td>
										<td class="text-right <?=$corTexto?>"><small><div><?=fnValor($qrListaCaixa['VAL_CREDITO'],2)?></div></small></td>
										<td class="text-center">
										  	<?php 
										  		if($qrListaCaixa[COD_TIPO] != 1 && $qrListaCaixa[COD_TIPO] != 2){ 
										  	?>
								           		<small>
								           			<div class="btn-group dropdown dropleft">
														<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															ações &nbsp;
															<span class="fas fa-caret-down"></span>
													    </button>
														<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
															<li><a class="addBox" data-url="action.php?mod=<?php echo fnEncode(1705)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idx=<?=fnEncode($qrListaCaixa[COD_CAIXA])?>&idm=<?=fnEncode($cod_mes)?>&pop=true" data-title="Cadastro de Lançamento" onclick='$("#CLIENTE_DETALHE").val("<?=$cod_cliente?>")'>Editar</a></li>
															<!-- <li class="divider"></li> -->
															<!-- <li><a href="javascript:void(0)" onclick='excTemplate("")'>Excluir</a></li> -->
														</ul>
													</div>
								           		</small>
								           	<?php 
								           		}else if($qrListaCaixa[COD_TIPO] == 1){ 

								           			if($salario_base != fnValorSql(fnValor($qrListaCaixa[VAL_CREDITO],2))){
								           	?>
								           				<a href="javascript:void(0)" class="btn btn-warning btn-xs transparency" onclick='refreshSalario("<?=$cod_cliente?>")'>Atualizar Salário</a>
								           	<?php
								           			}
								           	 	} 
								           	?>
						           	   </td>
									</tr>
								
											
								<?php 																				
										  }											
								?>																	
									<tr>
									<td><small><b>Vl. Líquido</b></small></td>
									<td class="text-right" colspan="3"><small><b><div class="subtotalProd"><?=fnValor($val_total,2);?></div></b></small></td>
									</tr>

									<tr>
										<td colspan="4">
										
											<small><a target="_blank" class="btn btn-info btn-xs" href="action.php?mod=<?php echo fnEncode(1711)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idm=<?=fnEncode($cod_mes)?>&pop=true"><span class="fal fa-file"></span> Impressão de holerite</a></small>
											&nbsp;
											<small>
												<div class="btn-group dropdown dropleft">
													<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<span class="fal fa-file"></span>&nbsp; Impressão avulsa &nbsp;
														<span class="fas fa-caret-down"></span>
													</button>
													<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">			
													<?php 																				
														$sql = "SELECT COD_TIPO,DES_TIPO 
																FROM TIP_CREDITO
																WHERE LOG_AVULSO='S' AND 
																	  COD_EMPRESA = $cod_empresa
														
																ORDER BY DES_TIPO";
																//fnEscreve($sql);
																$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
																
																while ($qrListaTipoAvulso = mysqli_fetch_assoc($arrayQuery))
																  {
																	?>																		  
																	<li><a target="_blank" href="action.php?mod=<?php echo fnEncode(1742)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idx=<?=fnEncode($qrListaCaixa[COD_CAIXA])?>&idm=<?=fnEncode($cod_mes)?>&idt=<?=fnEncode($qrListaTipoAvulso['COD_TIPO'])?>&pop=true"><?=ucfirst(mb_strtolower($qrListaTipoAvulso['DES_TIPO'],"utf-8"));?></a></li>
																	<?php 
																  }
														?>																	
														
													</ul>
												</div>
											</small>
										
										</td>
										<?php

											$sqlSal = "SELECT 	1
													FROM CAIXA
													where CAIXA.COD_CONTRAT=$cod_cliente 
													AND CAIXA.COD_EMPRESA=$cod_empresa 
													AND CAIXA.COD_TIPO=1
													AND CAIXA.COD_MES = $cod_mes";

											$arraySal = mysqli_query(connTemp($cod_empresa,''),$sqlSal);

											$temSal = mysqli_num_rows($arraySal);

											if($temSal == 0){

										?>
												<td colspan="2"><small><a class="btn btn-primary btn-xs" onclick='lancarMes("<?=$cod_cliente?>")'><span class="fal fa-cog"></span> Lançar Mês</a></small></td>
										<?php }else{ ?>
												<td colspan="2"></td>
										<?php } ?>
																									
							</table>

		<?php

		break;

		case 'paginar':
			$sql = "SELECT 	CAIXA.VAL_CREDITO,
							CAIXA.COD_CAIXA,
							TIP_CREDITO.COD_TIPO,
							TIP_CREDITO.DES_TIPO,
							TIP_CREDITO.TIP_OPERACAO,
							DATE_FORMAT(CAIXA.DAT_LANCAME, '%d/%m/%Y') DAT_LANCAME
			FROM CAIXA
			left join TIP_CREDITO on caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
			where CAIXA.COD_CONTRAT=$cod_cliente 
			AND CAIXA.COD_EMPRESA=$cod_empresa 
			AND CAIXA.COD_MES = $cod_mes
			AND CAIXA.DAT_EXCLUSA IS NULL
			AND CAIXA.COD_EXCLUSA = 0
			AND CAIXA.TIP_LANCAME = 'F'
			ORDER BY CAIXA.DAT_LANCAME DESC";
			
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			
			$count=0;
			$val_total = 0;
			$dat_ref = "";
			while ($qrListaCaixa = mysqli_fetch_assoc($arrayQuery))
			  {														  

				if ($dat_ref !=  $qrListaCaixa['DAT_LANCAME'] || $count == 0){

					$dat_ref = $qrListaCaixa['DAT_LANCAME'];
					$dat_lancame = $dat_ref;	

				} else {

					$dat_lancame = "";	

				}
				
				$tip_operacao = $qrListaCaixa['TIP_OPERACAO'];
				
				if ($tip_operacao == "D") {
					$corTexto = "text-danger";
					$val_total -= $qrListaCaixa['VAL_CREDITO'];
				} else { 
					$corTexto = ""; 
					$val_total += $qrListaCaixa['VAL_CREDITO'];
				} 

					
				?>
					<tr>
					  <td class="f14"><b><?php echo $dat_lancame; ?></b></td>
					  <td class="f12"><?php echo $qrListaCaixa['DES_TIPO']; ?></td>
					  <td class='text-center <?php echo $corTexto; ?> f12'><?php echo $qrListaCaixa['TIP_OPERACAO']; ?></td>
					  <td class='text-right <?php echo $corTexto; ?> f14'><?php echo fnValor($qrListaCaixa['VAL_CREDITO'],2); ?></td>
					  <td class="text-center">
					  	<?php if($qrListaCaixa[COD_TIPO] != 1 && $qrListaCaixa[COD_TIPO] != 2){ ?>
			           		<small>
			           			<div class="btn-group dropdown dropleft">
									<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										ações &nbsp;
										<span class="fas fa-caret-down"></span>
								    </button>
									<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
										<li><a class="addBox" data-url="action.php?mod=<?php echo fnEncode(1705)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idx=<?=fnEncode($qrListaCaixa[COD_CAIXA])?>&idm=<?=fnEncode($cod_mes)?>&pop=true" data-title="Cadastro de Lançamento">Editar</a></li>
										<!-- <li class="divider"></li> -->
										<!-- <li><a href="javascript:void(0)" onclick='excTemplate("")'>Excluir</a></li> -->
									</ul>
								</div>
			           		</small>
			           	<?php } ?>
		           	   </td>
					</tr>	

				<?php
					$count++;
				}
				?>
				<script type="text/javascript">$("#ret_VAL_TOTAL").text("<?=fnValor($val_total,2)?>");</script>
				<?php 
		break;
		
		default:

			$cod_cliente = fnLimpaCampoZero($_POST[COD_CLIENTE]);
			$cod_usucada = $_SESSION[SYS_COD_USUARIO];

			$sqlVer = "SELECT DAT_INI FROM MES_CAIXA 
						WHERE COD_EMPRESA = $cod_empresa
						AND COD_MES = $cod_mes
						LIMIT 1";

			// fnEscreve($sqlVer);

			$arrayVer = mysqli_query(connTemp($cod_empresa,''),$sqlVer);

			$qrDat = mysqli_fetch_assoc($arrayVer);

			$dat_ini = $qrDat[DAT_INI];

			$newDate = explode('/', fnDataShort($dat_ini));
			$dia = $newDate[0];
			$mes   = $newDate[1];
			$ano  = $newDate[2];
			$mesano = $newDate[1]."/".$newDate[2];
			
			$sqlCli = "SELECT LA.COD_CLIENTE, LA.VAL_LANCAME, LA.COD_TIPO, LA.TIP_LANCAME, LA.LOG_JURIDICO
						FROM LANCAMENTO_AUTOMATICO LA 
						INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = LA.COD_CLIENTE 
												AND CL.LOG_ESTATUS = 'S' 
												AND CL.LOG_TITULAR = 'S'
						WHERE LA.COD_EMPRESA = $cod_empresa
						AND LA.COD_CLIENTE = $cod_cliente";

			$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

			$val_juridico = 0;

			$insertClientes = "";

			while($qrCli = mysqli_fetch_assoc($arrayCli)){

				$val_lancame = $qrCli[VAL_LANCAME];

				if($val_lancame > 0 && $val_lancame != ""){

					if($qrCli[COD_TIPO] != 2){

						$insertClientes .= "(
											$cod_empresa,
											$cod_mes,
											$qrCli[COD_CLIENTE],
											'$dat_ini',
											$mes,
											$ano,
											$qrCli[COD_TIPO],
											'$val_lancame',
											'$qrCli[TIP_LANCAME]',
											$cod_usucada
										),";

					}else{

						if($qrCli[LOG_JURIDICO] == "S"){

							$sqlCliSal = "SELECT LA.VAL_LANCAME AS SALARIO
										FROM LANCAMENTO_AUTOMATICO LA 
										WHERE LA.COD_EMPRESA = $cod_empresa
										AND LA.COD_CLIENTE = $qrCli[COD_CLIENTE]
										AND LA.COD_TIPO = 1";

							$arrayCliSal = mysqli_query(connTemp($cod_empresa,''),$sqlCliSal);

							$qrSal = mysqli_fetch_assoc($arrayCliSal);


							$val_juridico = ($qrCli[VAL_LANCAME]/100) * $qrSal[SALARIO];

						}else{

							$val_juridico = $qrCli[VAL_LANCAME];

						}

						$insertClientes .= "(
									$cod_empresa,
									$cod_mes,
									$qrCli[COD_CLIENTE],
									'$dat_ini',
									$mes,
									$ano,
									2,
									'$val_juridico',
									'$qrCli[TIP_LANCAME]',
									$cod_usucada
								),";

					}

				}

			}


			$insertClientes = rtrim(trim($insertClientes),',');

			$sqlMes = "INSERT INTO CAIXA(
								COD_EMPRESA,
								COD_MES,
								COD_CONTRAT,
								DAT_LANCAME,
								MES,
								ANO,
								COD_TIPO,
								VAL_CREDITO,
								TIP_LANCAME,
								COD_USUCADA
							) VALUES $insertClientes";

			

			if(trim($insertClientes) != ""){

				mysqli_query(connTemp($cod_empresa,''),$sqlMes);

			}

			// fnEscreve($sqlMes);

			echo $cod_cliente;

		break;
	}

	if($opcao != "expandir" && $opcao != "paginar"){
?>
		<script>
			parent.location.reload();
		</script>
<?php 
	}

?>