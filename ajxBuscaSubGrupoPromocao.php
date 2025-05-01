<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
//fnEscreve($buscaAjx2);
?>
<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
	<option value="0">&nbsp;</option>
	<?php
		$sql = "select * from SUB_PROMOCAO where COD_CATEGOR = '".$buscaAjx1."' order by DES_SUBCATE ";
		$arrayQuery = mysqli_query(connTemp($buscaAjx3,''),$sql) or die(mysqli_error());
		
		while ($qrListaSubCategoria = mysqli_fetch_assoc($arrayQuery))
		  {														
			echo"
				  <option value='".$qrListaSubCategoria['COD_SUBCATE']."'>".$qrListaSubCategoria['DES_SUBCATE']."</option> 
				"; 
			  }	
	?>
</select>			
<script language=javascript> 
$(".chosen-select-deselect").chosen({allow_single_deselect:true});
$("#COD_SUBCATE").val(<?php echo $buscaAjx2 ?>).trigger("chosen:updated");
</script> 