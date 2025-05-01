<?php

//mais cash										
if (fnLimpacampo(fnDecode($_GET['mod'])) == 1698) {
	$abaEmpresa = 1698;
}

//rh									
if (fnLimpacampo(fnDecode($_GET['mod'])) == 1701) {
	$abaEmpresa = 1701;
}

//echo $mod;

//fnEscreve2($_SESSION["SYS_COD_SISTEMA"]);

if(@$_GET['popUp'] != "true"){
					
		switch ($_SESSION["SYS_COD_SISTEMA"]) {
			case 14: //rede duque
				include "abasEmpresaDuque.php";
				break;
			case 15: //quiz
				include "abasEmpresaQuiz.php";
				break;
			case 16: //gabinete
				include "abasGabinete.php";
				break;
			case 18: //mais cash
				include "abasMaisCash.php";
				break;
			case 19: //rh
				include "abasRH.php";
				break;
			case 20: //controle de campanha
				include "abasCampanha.php";
				break;
			case 21: //garantias blockchain
				include "abasGarantias.php";
				break;
			default;
				include "abasEmpresaConfig.php";
				break;
		
		}

	}else{
		$abaAdorai = 1833;
		include "abasAdorai.php";

		$abaManutencaoAdorai = fnDecode($_GET['mod']);
		//echo $abaUsuario;

		//se não for sistema de campanhas

		echo ('<div class="push20"></div>');
		include "abasManutencaoAdorai.php";
	}


?>

