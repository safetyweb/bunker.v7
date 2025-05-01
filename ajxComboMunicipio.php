<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));
	$cod_estado = fnLimpaCampoZero($_REQUEST['COD_ESTADO']);
//	$cod_municipio = fnLimpaCampoZero($_REQUEST['COD_MUNICIPIO']);
?>

<div class="form-group">
	<label for="inputName" class="control-label required">Cidade</label>

	<select data-placeholder="Selecione um município" name="COD_MUNICIPIO" id="COD_MUNICIPIO" class="chosen-select-deselect" required>
		<option value=""></option>
		<?php

			$sql = "SELECT COD_MUNICIPIO, NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_ESTADO = $cod_estado ORDER BY NOM_MUNICIPIO";
			$arrayCidade = mysqli_query(connTemp($cod_empresa,''),$sql);
			while($qrCidade = mysqli_fetch_assoc($arrayCidade)){
		?>
				<option value="<?=$qrCidade['COD_MUNICIPIO']?>"><?=$qrCidade['NOM_MUNICIPIO']?></option>
		<?php
			}

		?>								
	</select>
	<div class="help-block with-errors"></div>
	
	<script type="text/javascript">
		$("#COD_MUNICIPIO").chosen({allow_single_deselect:true});
		//$('#formulario').validator('destroy').validator();
		$("#COD_MUNICIPIO").change(function(){
			cidade = $("#COD_MUNICIPIO option:selected").text();
			$('#NOM_CIDADEC').val(cidade);
			// alert($('#NOM_CIDADEC').val());
		});
	</script>
<!--	<script>$("#formulario #COD_ESTACIV").val("<?php echo $cod_estaciv; ?>").trigger("chosen:updated"); </script>-->
	<?php //fnEscreve($sql); ?>
</div>