<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));

	$sqlUnivend = "SELECT COD_UNIVEND FROM CLIENTES 
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_CLIENTE = $cod_cliente";

	$arrayUnivend = mysqli_query(connTemp($cod_empresa,''), $sqlUnivend);
	$qrUnivend = mysqli_fetch_assoc($arrayUnivend);

	$cod_univend = $qrUnivend[COD_UNIVEND];

	$sqlCont = "SELECT COD_CONTRAT FROM CONTRATO_ELEITORAL
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_UNIVEND = $cod_univend
				AND TIP_CONTRAT NOT IN(4,5)";

	$arrayCont = mysqli_query(connTemp($cod_empresa,''), $sqlCont);

	$cod_contrats = "";

	while($qrCont = mysqli_fetch_assoc($arrayCont)){
		$cod_contrats .= $qrCont[COD_CONTRAT].",";
	}

	$cod_contrats = rtrim(ltrim($cod_contrats,","),",");

	if($cod_contrats != ""){

		$sqlVal = "SELECT VAL_CREDITO, TIP_LANCAME FROM CAIXA 
					WHERE COD_EMPRESA = $cod_empresa
					AND COD_CONTRAT IN($cod_contrats)
					AND COD_EXCLUSA = 0";

		// fnEscreve($sqlVal);

		$arrayVal = mysqli_query(connTemp($cod_empresa,''), $sqlVal);

		$tot_contrat = 0;
		$tot_pago = 0;
		$tot_receber = 0;

		while($qrVal = mysqli_fetch_assoc($arrayVal)){

			if($qrVal[TIP_LANCAME] == 'C'){
				$tot_contrat += $qrVal[VAL_CREDITO];
			}else{
				$tot_pago += $qrVal[VAL_CREDITO];
			}

		}

		$tot_receber = $tot_contrat - $tot_pago;

	}else{

		$tot_contrat = 0;
		$tot_pago = 0;
		$tot_receber = 0;

	}
	///////////////////////////////////////////////////////////////////////////////

	$sql = "SELECT * FROM CONTRATO_ELEITORAL 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_UNIVEND = $cod_univend
			AND COD_CLIENTE = $cod_cliente
			AND COD_EXCLUSA = 0
			AND TIP_CONTRAT NOT IN(4,5)";
	$arrayCont = mysqli_query(connTemp($cod_empresa,''), $sql);

	$count = 0;
	while ($qrBuscaModulos = mysqli_fetch_assoc($arrayCont)) {
		$count++;

		$tipoContrato = "Cabo Eleitoral";
		$formaPag = "Dinheiro";
		

		switch ($qrBuscaModulos[COD_FORMAPA]) {
			case '2':
				$formaPag = "Pix";
			break;

			case '3':
				$formaPag = "TED/DOC";
			break;
			
			default:
				$formaPag = "Dinheiro";
			break;
		}

		switch ($qrBuscaModulos[TIP_CONTRAT]) {
			case '2':
				$tipoContrato = "Cabo Eleitoral";
			break;

			case '3':
				$tipoContrato = "Coordenador Cabo Eleitoral";
			break;

			case '4':
				$tipoContrato = "Cessão Serviços";
			break;
			
			case '5':
				$tipoContrato = "Cessão Gratuita de Veículos";
			break;

			default:
				$tipoContrato = "Genérico";
			break;
		}

		switch ($qrBuscaModulos[TIP_PAGAMEN]) {
			case '1':
				$tipoPag = "Diário";
			break;

			case '7':
				$tipoPag = "Semanal";
			break;

			case '15':
				$tipoPag = "Quinzenal";
			break;

			case '30':
				$tipoPag = "Mensal";
			break;
			
			default:
				$tipoPag = "Pagamento Único";
			break;
		}

?>

<div>

		<div id="div_cliente">

			<h3><?=$qrBuscaModulos['COD_CONTRAT']?> / <?=$tipoContrato?> / <?=fnDataShort($qrBuscaModulos['DAT_INI'])?> / <small>R$</small><?=fnValor($qrBuscaModulos['VAL_CONTRAT'],2)?></h3>

			<table class="table" style="width: auto;">
				<tr>
					<td colspan="4"><small><a href="javascript:void(0)" id="btnNovo" class="btn btn-info btn-xs addBox" data-url="action.php?mod=<?php echo fnEncode(1827)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idCT=<?=fnEncode($qrBuscaModulos[COD_CONTRAT])?>&idT=<?=fnEncode($qrBuscaModulos[TIP_PAGAMEN])?>&pop=true" data-title="Cadastro de Lançamento" onclick='$("#CLIENTE_DETALHE").val("<?=$cod_cliente?>")'><span class="fal fa-plus"></span>&nbsp; Cadastrar novo lançamento</a></small></td>
					<td colspan="2"></td>
				</tr>
			
				<tr>
					<th><small>Dt. Lança.</small></th>
					<th><small>Op.</small></th>
					<th class="text-center"><small>Forma de pagamento</small></th>
					<th class="text-right"><small>Vl.</small></th>
				</tr>
				
<?php 																	
			$sql = "SELECT 	CAIXA.VAL_CREDITO,
							CAIXA.COD_CAIXA,
							CAIXA.DAT_LANCAME,
							CAIXA.NUM_DIA,
							CAIXA.COD_PAGAMENTO	
					FROM CAIXA
					where CAIXA.COD_CONTRAT=$qrBuscaModulos[COD_CONTRAT] 
					AND CAIXA.COD_CLIENTE=$cod_cliente
					AND CAIXA.COD_EMPRESA=$cod_empresa
					AND CAIXA.DAT_EXCLUSA IS NULL
					AND CAIXA.TIP_LANCAME = 'D'
					AND CAIXA.COD_EXCLUSA = 0
					ORDER BY CAIXA.DAT_LANCAME DESC";
			
			// fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			
			$count=0;
			$val_total = 0;
			$dat_ref = "";
			while ($qrListaCaixa = mysqli_fetch_assoc($arrayQuery)){														  

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

				switch ($qrListaCaixa['COD_PAGAMENTO']){
					case 1:
						$des_pagamento = "Dinheiro";
					break;
					case 2:
						$des_pagamento = "Pix";
					break;
					case 3:
						$des_pagamento = "TED/DOC";
					break;
					case 4:
						$des_pagamento = "Transferência";
					break;
				}

				// $sqlSal = "SELECT VAL_LANCAME AS VAL_SALBASE FROM LANCAMENTO_AUTOMATICO LA 
				// 			WHERE LA.COD_EMPRESA = $cod_empresa 
				// 			AND LA.COD_CLIENTE = $cod_cliente
				// 			AND LA.COD_TIPO = 1";															

				// 		//fnEscreve($sql);

				// $arraySal = mysqli_query(connTemp($cod_empresa,''),$sqlSal);

				// $qrSal = mysqli_fetch_assoc($arraySal);

				// $salario_base = fnValorSql(fnValor($qrSal[VAL_SALBASE],2));

?>																			  
				<tr codItemVenda="<?php echo $qrListaCaixa['COD_ITEMVEN'];?>">
					<td><small><?=fnDataShort($qrListaCaixa['DAT_LANCAME'])?></small></td>
					<td><small><div>Pagamento</div></small></td>
					<td class="text-center"><small><?=$des_pagamento?></small></td>
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
										<li><a class="addBox" data-url="action.php?mod=<?php echo fnEncode(1827)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idx=<?=fnEncode($qrListaCaixa[COD_CAIXA])?>&m=true&pop=true" data-title="Cadastro de Lançamento" onclick='$("#CLIENTE_DETALHE").val("<?=$cod_cliente?>")'>Editar</a></li>
										<li><a target="_blank" href="action.php?mod=<?php echo fnEncode(1828)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_cliente)?>&idx=<?=fnEncode($qrListaCaixa[COD_CAIXA])?>&pop=true">Imprimir Recibo</a></li>
										<!-- <li class="divider"></li> -->
										<!-- <li><a href="javascript:void(0)" onclick='excTemplate("")'>Excluir</a></li> -->
									</ul>
								</div>
			           		</small>
			           	<?php 
			           		}else if($qrListaCaixa[COD_TIPO] == 1){ 

			           			// if($salario_base != fnValorSql(fnValor($qrListaCaixa[VAL_CREDITO],2))){
			           	?>
			           				<!-- <a href="javascript:void(0)" class="btn btn-warning btn-xs transparency" onclick='refreshSalario("<?=$cod_cliente?>")'>Atualizar Salário</a> -->
			           	<?php
			           			// }
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

																							
			</table>

		


<?php

}

?>
	</div>

	<div id="div_total">
		
		<div class="col-md-2">
			<div class="form-group">
				<label for="inputName" class="control-label required">Total de Contratos (R$)</label>
				<input type="text" class="form-control input-sm text-center leitura" name="VAL_CONTRAT" id="VAL_CONTRAT" value="<?php echo fnValor($tot_contrat, 2); ?>">
				<div class="help-block with-errors"></div>
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label for="inputName" class="control-label required">Total Recebido (R$)</label>
				<input type="text" class="form-control input-sm text-center leitura" name="VAL_SALBASE" id="VAL_SALBASE" value="<?php echo fnValor($tot_pago, 2); ?>">
				<div class="help-block with-errors"></div>
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label for="inputName" class="control-label required">Total a Receber (R$)</label>
				<input type="text" class="form-control input-sm text-center leitura" name="VAL_SALBASE" id="VAL_SALBASE" value="<?php echo fnValor($tot_receber, 2); ?>">
				<div class="help-block with-errors"></div>
			</div>
		</div>

	</div>

</div>