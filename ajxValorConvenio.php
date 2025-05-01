<?php
	include '_system/_functionsMain.php';

	$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
	$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
	$cod_aditivo = fnLimpaCampoZero($_REQUEST['COD_ADITIVO']);

	if($cod_aditivo == 0){
		$sql = "SELECT VAL_CONCED AS VAL_CONVENI, 
					   VAL_CONTPAR, 
					   VAL_VALOR 
				FROM CONVENIO 
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_CONVENI = $cod_conveni";
	}else{
		$sql = "SELECT VAL_CONVENI, 
					   VAL_CONTRAP AS VAL_CONTPAR, 
					   VAL_TOTALGL AS VAL_VALOR 
				FROM TERMOADITIVO 
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_ADITIVO = $cod_aditivo";
	}

	$qrVal = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

?>

<div>

	<div id="RET_VAL_CONVENI">
		<input type="text" class="form-control input-sm text-right leituraOff money" readonly name="VAL_CONVENI" id="VAL_CONVENI" value="<?=fnValor($qrVal[VAL_CONVENI],2)?>" data-mask="##0" data-mask-reverse="true" maxlength="11" required>
	</div>
	<div id="RET_VAL_CONTPAR">
		<input type="text" class="form-control input-sm text-right leituraOff money" readonly name="VAL_CONTPAR" id="VAL_CONTPAR" value="<?=fnValor($qrVal[VAL_CONTPAR],2)?>" data-mask="##0" data-mask-reverse="true" maxlength="11" required>
	</div>
	<div id="RET_VAL_VALOR">
		<input type="text" class="form-control input-sm money text-right leituraOff" name="VAL_VALOR" id="VAL_VALOR"  value="<?=fnValor($qrVal[VAL_VALOR],2)?>" data-mask="##0" data-mask-reverse="true" maxlength="11" required>
	</div>

</div>