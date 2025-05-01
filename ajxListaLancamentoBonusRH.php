<?php 

	include '_system/_functionsMain.php'; 

	$opcao = fnLimpaCampo($_GET['OPCAO']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['COD_EMPRESA']));
	

	// fnEscreve($opcao);

	switch ($opcao) {

		case 'expandir':

			$cod_cliente = fnLimpaCampoZero($_POST['COD_CLIENTE']);
			$cod_mes = fnLimpaCampoZero(fnDecode($_POST['COD_MES']));
							
?>				
		
			<table class="table" style="width: auto;">
				<tr>
					<td colspan="4"><small><a href="javascript:void(0)" id="btnNovo" class="btn btn-info btn-xs addBox" data-url="action.php?mod=<?php echo fnEncode(1720)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idm=<?=fnEncode($cod_mes)?>&pop=true" data-title="Cadastro de Lançamento" onclick='$("#CLIENTE_DETALHE").val("<?=$cod_cliente?>")'><span class="fal fa-plus"></span>&nbsp; Cadastrar novo lançamento</a></small></td>
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
									TIP_CREDITO.COD_TIPO,
									TIP_CREDITO.DES_TIPO,
									TIP_CREDITO.TIP_OPERACAO,
									TIP_CREDITO.LOG_AVULSO,
									TIP_CREDITO.LOG_CONTABILIZA,
									CAIXA.DAT_LANCAME,
									CAIXA.NUM_DIA
							FROM CAIXA
							left join TIP_CREDITO on caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
							where CAIXA.COD_CONTRAT=$cod_cliente 
							AND CAIXA.COD_EMPRESA=$cod_empresa 
							AND CAIXA.COD_MES = $cod_mes
							AND CAIXA.DAT_EXCLUSA IS NULL
							AND CAIXA.COD_EXCLUSA = 0
							AND CAIXA.TIP_LANCAME = 'B'
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
									<tr codItemVenda="<?php echo $qrListaCaixa['COD_ITEMVEN'];?>">
										<td><small><?=fnDataShort($qrListaCaixa['DAT_LANCAME'])?></small></td>
										<td><small><div><?=$qrListaCaixa['DES_TIPO']?></div></small></td>
										<td class="text-right <?=$corTexto?>"><small><div><?=$tip_operacao?></div></small></td>
										<td class="text-center"><small><?=fnValor($qrListaCaixa['NUM_DIA'],0)?></small></td>
										<td class="text-right <?=$corTexto?>"><small><div><?=fnValor($qrListaCaixa['VAL_CREDITO'],2)?></div></small></td>
										<td class="text-center">
										  	<?php if($qrListaCaixa[COD_TIPO] != 1 && $qrListaCaixa[COD_TIPO] != 2){ ?>
								           		<small>
								           			<div class="btn-group dropdown dropleft">
														<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															ações &nbsp;
															<span class="fas fa-caret-down"></span>
													    </button>
														<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
															<li><a class="addBox" data-url="action.php?mod=<?php echo fnEncode(1720)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idx=<?=fnEncode($qrListaCaixa[COD_CAIXA])?>&idm=<?=fnEncode($cod_mes)?>&pop=true" data-title="Cadastro de Lançamento" onclick='$("#CLIENTE_DETALHE").val("<?=$cod_cliente?>")'>Editar</a></li>
															<!-- <li class="divider"></li> -->
															<!-- <li><a href="javascript:void(0)" onclick='excTemplate("")'>Excluir</a></li> -->
														</ul>
													</div>
								           		</small>
								           	<?php } ?>
						           	   </td>
									</tr>
								
											
								<?php 																				
										  }											
								?>																	
									<tr>
									<td><small><b>Vl. Líquido</b></small></td>
									<td class="text-right" colspan="3"><small><b><div class="subtotalProd"><?=fnValor($val_total,2);?></div></b></small></td>
									<td class="text-right" colspan="2"></td>
									</tr>

									<tr>
										<td colspan="5">
											<small><a target="_blank" class="btn btn-info btn-xs" href="action.php?mod=<?php echo fnEncode(1721)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idm=<?=fnEncode($cod_mes)?>&pop=true"><span class="fal fa-file"></span>&nbsp; Impressão de holerite</a></small>
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
																	<li><a target="_blank" href="action.php?mod=<?php echo fnEncode(1741)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idx=<?=fnEncode($qrListaCaixa[COD_CAIXA])?>&idm=<?=fnEncode($cod_mes)?>&idt=<?=fnEncode($qrListaTipoAvulso['COD_TIPO'])?>&pop=true"><?=ucfirst(mb_strtolower($qrListaTipoAvulso['DES_TIPO'],"utf-8"));?></a></li>
																	<?php 
																  }
														?>																	
														
													</ul>
												</div>
											</small>
										</td>
									</tr>	
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
			AND CAIXA.TIP_LANCAME = 'B'
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
			# code...
		break;
	}

?>