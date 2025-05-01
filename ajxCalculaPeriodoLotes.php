<?php 

	include '_system/_functionsMain.php';

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$dat_iniagendamento = $_REQUEST['DAT_INIAGENDAMENTO'];
	$dat_fimagendamento = $_REQUEST['DAT_FIMAGENDAMENTO'];
	$des_interval = $_REQUEST['DES_INTERVAL'];
	$periodo_hrs = $_REQUEST['PERIODO_HRS'];

	$projecao = date("Y-m-d H:i:s",strtotime($dat_iniagendamento." + ".($periodo_hrs-$des_interval)." hours"));

	if($projecao <= $dat_fimagendamento){
		$cor = "";
	}else{
		$cor = "text-danger";
	}


?>

<div class="form-group">
	<label for="inputName" class="control-label">Data Final <small>(projetada)</small></label>
	<input type="text" class="form-control input-sm leituraOff <?=$cor?>" readonly="readonly" name="DES_PERIODOREF" id="DES_PERIODOREF" value="<?=fnDataFull($projecao)?>"> 
</div>