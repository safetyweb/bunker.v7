<?php include "_system/_functionsMain.php"; 

$cod_empresa = fnLimpacampozero(fnDecode($_POST['COD_EMPRESA']));
$log_separa = fnLimpacampo($_POST['LOG_SEPARA']);

$sqlUpdt = "UPDATE CONTROLE_TERMO SET LOG_SEPARA = '$log_separa' WHERE COD_EMPRESA = $cod_empresa";

mysqli_query(connTemp($cod_empresa,''),$sqlUpdt);

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

if($log_separa == 'S'){

	$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' AND TIP_TERMO != 'COM' ORDER BY NUM_ORDENAC";
	// fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

	$count=0;
	$tipo = "";
	while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)){

		if($qrBuscaFAQ[LOG_OBRIGA] == "S"){
			$obrigaChk = "required";
		}else{
			$obrigaChk = "";
		}

		$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
					  WHERE COD_EMPRESA = $cod_empresa
					  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

		// fnEscreve($sqlTermos);

		$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

		$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

		while ($qrTermos = mysqli_fetch_assoc($arrayTermos)){
			// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

			$des_bloco = str_replace("<#".strtoupper($qrTermos['ABV_TERMO']).">", 
									'
										</label>
											
												<a class="addBox f16" 
												   data-url="action.php?mod='.fnEncode(1677).'&id='.fnEncode($cod_empresa).'&idt='.fnEncode($qrTermos[COD_TERMO]).'&pop=true&rnd='.rand().'" 
												   data-title="Template do Email"
												   style="cursor:pointer;">
												   '.$qrTermos['ABV_TERMO'].'
												</a>
											
									  	<label class="f16" for="TERMOS_'.$count.'">
									', 
									$des_bloco);
		}

?>

		<div class="form-group">
			<div class="col-xs-12">
				<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
					<input type="checkbox" name="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" id="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" style="width: 18px; height: 18px;" <?=$obrigaChk?> <?=$chkTermo?>>
					<label class="<?=$obrigaChk?>"></label>
				</div>
				<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
					<label class="f16" for="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>">
						&nbsp;<?=$des_bloco?>
					</label>
				</div>
			</div>
			<div class="help-block with-errors"></div>
			<div class="push10"></div>
			<div class="push5"></div>
		</div>

<?php

		$count++;

	}

	?>

	<div class="col-xs-10 col-xs-offset-1">
		<h5 data-toggle='tooltip' data-placement='bottom' data-original-title='Clique para editar'>
			<b>
				<a href="#" class="editable" 
				  	data-type='text' 
				  	data-title='Editar Texto' data-pk="<?=$cod_empresa?>" 
				  	data-name="TXT_COMUNICA"><?=$qrControle['TXT_COMUNICA']?>
			  		
			  	</a>
			</b>
		</h5>
	</div>
	<div class="push10"></div>

	<?php 

	$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' AND TIP_TERMO = 'COM' ORDER BY NUM_ORDENAC";
	// fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

	// $count=0;
	$tipo = "";
	while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)){

		if($qrBuscaFAQ[LOG_OBRIGA] == "S"){
			$obrigaChk = "required";
		}else{
			$obrigaChk = "";
		}

		$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
					  WHERE COD_EMPRESA = $cod_empresa
					  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

		// fnEscreve($sqlTermos);

		$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

		$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

		while ($qrTermos = mysqli_fetch_assoc($arrayTermos)){
			// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

			$des_bloco = str_replace("<#".strtoupper($qrTermos['ABV_TERMO']).">", 
									'
										</label>
											
												<a class="addBox f16" 
												   data-url="action.php?mod='.fnEncode(1677).'&id='.fnEncode($cod_empresa).'&idt='.fnEncode($qrTermos[COD_TERMO]).'&pop=true&rnd='.rand().'" 
												   data-title="Template do Email"
												   style="cursor:pointer;">
												   '.$qrTermos['ABV_TERMO'].'
												</a>
											
									  	<label class="f16" for="TERMOS_'.$count.'">
									', 
									$des_bloco);
		}

?>

		<div class="form-group">
			<div class="col-xs-12">
				<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
					<input type="checkbox" name="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" id="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" style="width: 18px; height: 18px;" <?=$obrigaChk?> <?=$chkTermo?>>
					<label class="<?=$obrigaChk?>"></label>
				</div>
				<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
					<label class="f16" for="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>">
						&nbsp;<?=$des_bloco?>
					</label>
				</div>
			</div>
			<div class="help-block with-errors"></div>
			<div class="push10"></div>
			<div class="push5"></div>
		</div>

<?php

		$count++;

	}

}else{
	
	$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' ORDER BY NUM_ORDENAC";
	// fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

	$count=0;
	$tipo = "";
	while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)){

		if($qrBuscaFAQ[LOG_OBRIGA] == "S"){
			$obrigaChk = "required";
		}else{
			$obrigaChk = "";
		}

		$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
					  WHERE COD_EMPRESA = $cod_empresa
					  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

		// fnEscreve($sqlTermos);

		$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

		$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

		while ($qrTermos = mysqli_fetch_assoc($arrayTermos)){
			// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

			$des_bloco = str_replace("<#".strtoupper($qrTermos['ABV_TERMO']).">", 
									'
										</label>
											
												<a class="addBox f16" 
												   data-url="action.php?mod='.fnEncode(1677).'&id='.fnEncode($cod_empresa).'&idt='.fnEncode($qrTermos[COD_TERMO]).'&pop=true&rnd='.rand().'" 
												   data-title="Template do Email"
												   style="cursor:pointer;">
												   '.$qrTermos['ABV_TERMO'].'
												</a>
											
									  	<label class="f16" for="TERMOS_'.$count.'">
									', 
									$des_bloco);
		}

	?>

		<div class="form-group">
			<div class="col-xs-12">
				<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
					<input type="checkbox" name="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" id="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>" style="width: 18px; height: 18px;" <?=$obrigaChk?> <?=$chkTermo?>>
					<label class="<?=$obrigaChk?>"></label>
				</div>
				<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
					<label class="f16" for="TERMOS_<?=$qrBuscaFAQ[COD_BLOCO]?>">
						&nbsp;<?=$des_bloco?>
					</label>
				</div>
			</div>
			<div class="help-block with-errors"></div>
			<div class="push10"></div>
			<div class="push5"></div>
		</div>

	<?php

		$count++;

	}


}
	// fnEscreve($sql);
	
?>

<script type="text/javascript">
	$('.editable').editable({ 
    	emptytext: '_______________',
        url: 'ajxTextoTermos.php',
		ajaxOptions:{type:'post'},
		success:function(data){
			console.log(data);
		}
    });
</script>

