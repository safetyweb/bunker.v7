<?php 									

	if (!empty($cod_grupotr)) {
		
		$sql = "select COD_UNIVEND from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' and COD_GRUPOTR in (". fnArrayToString($cod_grupotr) .") and cod_exclusa =0 order by trim(NOM_FANTASI)";
		$arrayQuery = mysqli_query($adm, $sql);
		while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery)) {
			$grupoTrabalho .= $qrListaUniVendas['COD_UNIVEND'] . ",";
		}
		//substitui lojas selecionadas
		$lojasSelecionadas = substr($grupoTrabalho, 0, -1);
	}


	}
	
?>	