
	<?php
	switch ($_SESSION["SYS_COD_SISTEMA"]) {
	case 3: //adm marka
		$formBack = "1190";
		break;
	case 14: //rede duque
		$formBack = "1213";
		break;
	default;											
		$formBack = "1195";
		break;
	}
	?>
