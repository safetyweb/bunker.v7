<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnDecode(fnLimpacampo($_GET['ajx1']));
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
//fnEscreve($buscaAjx1);
//fnEscreve($buscaAjx2);
?>
<select data-placeholder="Selecione uma máquina" name="COD_MAQUINA" id="COD_MAQUINA" class="chosen-select-deselect">
	<option value="K2xr0lE3UHI¢"></option>
	<?php
		$sql = "SELECT * FROM MAQUINAS WHERE COD_EMPRESA = $buscaAjx2 and COD_UNIVEND = $buscaAjx1 order by DES_MAQUINA ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($buscaAjx2,''),$sql) or die(mysqli_error());
		
		while ($qrListaMaquina = mysqli_fetch_assoc($arrayQuery))
		  {														
			echo"
				  <option value='".fnEncode($qrListaMaquina['COD_MAQUINA'])."'>".$qrListaMaquina['DES_MAQUINA']."</option> 
				"; 
			  }	
	?>
</select>
<div class="help-block with-errors"></div>			
<script language=javascript> 
$(".chosen-select-deselect").chosen({allow_single_deselect:true});
</script> 