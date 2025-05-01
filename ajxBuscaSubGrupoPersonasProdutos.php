<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_POST['ajx1']);
$buscaAjx2 = fnLimpacampo($_POST['ajx2']);
$buscaAjx3 = fnLimpacampo($_POST['ajx3']);
//fnEscreve($buscaAjx2);
?>
<select data-placeholder="Selecione o sub grupo" name="BL2_COD_SUBCATE" id="BL2_COD_SUBCATE" class="chosen-select-deselect">
	<option value=""></option>
	<?php
		$sql = "select * from SUBCATEGORIA where COD_CATEGOR = '".$buscaAjx1."' order by DES_SUBCATE ";
		$arrayQuery = mysqli_query(connTemp($buscaAjx3,''),$sql);
		
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
$("#BL2_COD_SUBCATE").val(<?php echo $buscaAjx2 ?>).trigger("chosen:updated");
</script> 
