<?php
	
	include '_system/_functionsMain.php';

	$uf = fnLimpaCampo($_POST['ESTADO']);

?>

	<select data-placeholder="Selecione o município" name="NOM_CIDADE" id="NOM_CIDADE" class="chosen-select-deselect">
		<?php

			$sql = "SELECT * FROM CIDADES WHERE UF = '$uf' ORDER BY NOM_CIDADE";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(),trim($sql));

			while($qrCidade = mysqli_fetch_assoc($arrayQuery)){
				echo "
					<option value='".$qrCidade['NOM_CIDADE']."'>".$qrCidade['NOM_CIDADE']."</option>
				";
			}

		?> 							
	</select>
	<script>
		$('#NOM_CIDADE').chosen();
	</script>