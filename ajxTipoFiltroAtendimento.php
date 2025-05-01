<?php 

	include '_system/_functionsMain.php'; 
	// require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	// use Box\Spout\Writer\WriterFactory;
	// use Box\Spout\Common\Type;

	$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
	$cod_tpfiltro = fnLimpaCampoZero($_POST['COD_TPFILTRO']);
	$count = fnLimpaCampo($_GET['idS']);

	// fnEscreve($count);
	// fnEscreve($cod_tpfiltro);
?>	
	<input type="hidden" name="COD_TPFILTRO_<?=$count?>" id="COD_TPFILTRO_<?=$count?>" value="<?=$cod_tpfiltro?>">
	<select data-placeholder="Selecione o filtro" name="COD_FILTRO_<?=$count?>" id="COD_FILTRO_<?=$cod_tpfiltro?>" class="chosen-select-deselect">
		<option value=""></option>
<?php
		$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_ATENDIMENTO
					  WHERE COD_TPFILTRO = $cod_tpfiltro";

		$arrayFiltros = mysqli_query(connTemp($cod_empresa,''),trim($sqlFiltro));
		$conta = 0;
		while($qrFiltros = mysqli_fetch_assoc($arrayFiltros)){
?>

			<option value="<?=$qrFiltros['COD_FILTRO']?>"><?=$qrFiltros['DES_FILTRO']?></option>

<?php
			do{
				$ultimoCod = $qrFiltros['COD_FILTRO'];
				$conta++;
				$ultimoCod = 0;
			}while($conta == 0);

			if($qrFiltros['COD_FILTRO'] > $ultimoCod){
				$ultimoCod = $qrFiltros['COD_FILTRO'];
			}
		}
?>						
		<option value="add">&nbsp;ADICIONAR NOVO</option>
	</select>
	<script type="text/javascript">
    	$('#COD_FILTRO_<?=$cod_tpfiltro?>').change(function(){
			valor = $(this).val();
			if(valor=="add"){
				$(this).val('').trigger("chosen:updated");
				$('#btnCad_<?=$count?>').click();
			}
		});
		$('#COD_FILTRO_<?=$cod_tpfiltro?>').chosen({allow_single_deselect: true});
		$('#COD_FILTRO_<?=$cod_tpfiltro?>').val(<?=$ultimoCod?>).trigger('chosen:updated');
    	// $('#formulario').validator('destroy');
    	// $('#formulario').validator();
    </script>                                                         
	<div class="help-block with-errors"></div>
