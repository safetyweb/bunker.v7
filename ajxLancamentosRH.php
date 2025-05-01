<?php 

	include '_system/_functionsMain.php'; 

	$opcao = fnLimpaCampo($_POST['OPCAO']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['COD_EMPRESA']));
	$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));
	$cod_mes = fnLimpaCampoZero(fnDecode($_POST['COD_MES']));

	// fnEscreve($opcao);

	switch ($opcao) {
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

				$sqlSal = "SELECT VAL_LANCAME FROM LANCAMENTO_AUTOMATICO
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_CLIENTE = $cod_cliente
							AND COD_TIPO = 1";															

						//fnEscreve($sql);

				$arraySal = mysqli_query(connTemp($cod_empresa,''),$sqlSal);

				$qrSal = mysqli_fetch_assoc($arraySal);

				$salario_base = fnValorSql(fnValor($qrSal[VAL_LANCAME],2));
					
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
			           	<?php }else if($qrListaCaixa[COD_TIPO] == 1){ 

			           			if($salario_base != $qrListaCaixa['VAL_CREDITO']){
			           	?>
			           				<a href="javascript:void(0)" class="btn btn-warning btn-xs transparency" onclick='refreshSalario("<?=$cod_cliente?>")'>Atualizar Salário</a>
			           	<?php
			           			}
			           	 	}
			           	 ?>
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

			$sqlSal = "SELECT VAL_LANCAME FROM LANCAMENTO_AUTOMATICO
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CLIENTE = $cod_cliente
						AND COD_TIPO = 1";															

					//fnEscreve($sql);

			$arraySal = mysqli_query(connTemp($cod_empresa,''),$sqlSal);

			$qrSal = mysqli_fetch_assoc($arraySal);

			$salario_base = fnValorSql(fnValor($qrSal[VAL_LANCAME],2));

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

		break;
	}

?>