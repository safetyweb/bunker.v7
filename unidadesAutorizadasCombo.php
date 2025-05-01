	<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
		<option value=""></option>					
		<?php

			// alterado dia 07/12/2023 o esquema de verificação de unidades autorizadas por Ricardinho
			$arrUnidadesUsu = explode(",", $cod_univendUsu);

			//mostra todas
			if ($showAll != "no"){
				if ($cod_univend == "9999"){
				echo "<option value='9999' selected>Todas Unidades</option>";
				} else {
				echo "<option value='9999'>Todas Unidades</option>";
				}
			}	
			$sql = "select COD_UNIVEND, NOM_FANTASI, NOM_UNIVEND from unidadevenda where COD_EMPRESA = '".$cod_empresa."' AND LOG_ESTATUS = 'S' order by trim(NOM_FANTASI) "; 
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
			//fnEscreve($sql);
			
			while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery))
			  {
				if ($cod_univend == $qrListaUnidades['COD_UNIVEND']){ $selecionado = "selected";}else{$selecionado = "";}	
				
				//verifica acesso master
				if ($usuReportAdm == "N"){
					// alterado dia 07/12/2023 o esquema de verificação de unidades autorizadas por Ricardinho
					// if (strlen(strstr($cod_univendUsu,$qrListaUnidades['COD_UNIVEND']))>0){ $lojaAtiva = "";}else{$lojaAtiva = "disabled";}
					if (in_array($qrListaUnidades['COD_UNIVEND'], $arrUnidadesUsu)){ $lojaAtiva = "";}else{$lojaAtiva = "disabled";}
				} else 	{$lojaAtiva = " ";}
				echo"
					  <option value='".$qrListaUnidades['COD_UNIVEND']."' ".$selecionado." ".$lojaAtiva.">".$qrListaUnidades['NOM_FANTASI']."</option> 
					"; 
				  }											
		?>	
	</select>	
	<div class="help-block with-errors"></div>
