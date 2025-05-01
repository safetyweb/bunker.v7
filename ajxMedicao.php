<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_tarefas = fnLimpaCampo($_REQUEST['COD_TAREFAS']);

	$arrayTarefas = explode(",", $cod_tarefas);

	$val_medicao = 0;


	foreach ($arrayTarefas as $tarefa) {

		$sqlValor2 = "SELECT (VAL_PROJETO-VAL_CONSUMIDO) VAL_MEDICAO,COD_SUBTAREFA FROM TAREFA WHERE COD_EMPRESA = $cod_empresa AND COD_TAREFA = $tarefa";
		 //fnEscreve($sqlValor2);
		$arrValor2 = mysqli_query(connTemp($cod_empresa,''),$sqlValor2);

		while($qrValor2 = mysqli_fetch_assoc($arrValor2)){

			$val_projeto = $qrValor2["VAL_MEDICAO"];
			$val_medicao += $val_projeto;
		}
		
	}

?>

<div class="form-group">
	<label for="inputName" class="control-label required">Valor da Medição</label>
	<input type="text" class="form-control input-sm money" name="VAL_MEDICAO" id="VAL_MEDICAO" value="<?=fnValor($val_medicao,2)?>" data-mask="##0" data-mask-reverse="true" maxlength="11" data-medicao="medicao" r_eadonly>
	<input type="hidden" class="form-control input-sm money" name="VAL_MEDICAO_TOTAL" id="VAL_MEDICAO_TOTAL" value="<?=fnValor($val_medicao,2)?>" data-mask="##0" data-mask-reverse="true" maxlength="11">
</div>
<div class="help-block with-errors">REAIS (R$)</div>

<script type="text/javascript">
	//chosen obrigatório
	$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';

	$('#formulario').validator("destroy").validator({
	    // custom: {
	    //     'evolucao': function($el) {
	    //     	val_evofis = <?=$val_evofis?>,
	    //     	val_evolucao = parseFloat($el.val().replace('.','').replace(',','.'));
	    //     	if((val_evolucao+val_evofis) > 100){
	    //     		return true;
	    //     	}
	    //     },
	    //     'medicao': function($el) {
	    // 		val_valor = <?=$val_valor?>,
	    //     	val_medac = <?=$val_medac?>,
	    //     	val_medicao = parseFloat($el.val().replace('.','').replace(',','.'));
	    //     	if((val_medicao+val_medac) > val_valor){
	    //     		return true;
	    //     	}
	    //     }
	    // }
	});
</script>