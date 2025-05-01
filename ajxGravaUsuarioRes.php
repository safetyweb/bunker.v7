<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$opcao = fnLimpaCampo($_GET['opcao']);
	$cod_usuario = fnLimpaCampoZero($_POST['COD_USUARIO']);
	$cod_registro = fnLimpaCampoZero(fnDecode($_POST['COD_REGISTRO']));
	$cod_btn = fnLimpaCampoZero($_POST['COD_BTN']);
	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	switch ($opcao) {

		case 'exc':

			if ($cod_registro != 0) {

				$sqlDeleta = "DELETE FROM USUARIOS_RESTRITOS 
								WHERE COD_EMPRESA = '$cod_empresa' 
								AND COD_REGISTRO = '$cod_registro'";

				mysqli_query($connAdm->connAdm(), trim($sqlDeleta));
			}
			
		break;
		
		case 'grava':

			if($cod_btn != 0){

				$tip_restric = "RES";

				if($cod_btn == 1){
					$tip_restric = "ADM";
				}else if($cod_btn == 3){
					$tip_restric = "SLD";
				}

				$sqlGrava = "INSERT INTO USUARIOS_RESTRITOS(
												COD_EMPRESA,
												COD_USUARIO,
												TIP_RESTRIC,
												COD_USUCADA
											) VALUES(
												'$cod_empresa',
												'$cod_usuario',
												'$tip_restric',
												'$cod_usucada'
											)";

				mysqli_query($connAdm->connAdm(), trim($sqlGrava));

			}
		
		break;

		default:

			$cod_empresa = fnLimpaCampoZero($_POST['codempresa']);
			$valor = fnLimpaCampoZero($_POST['value']);

			$sql = "SELECT TIP_RESTRIC FROM USUARIOS_RESTRITOS WHERE COD_EMPRESA = $cod_empresa AND TIP_RESTRIC = 'SLD'";
			$arrayCod = mysqli_query($connAdm->connAdm(),$sql);

			if(mysqli_num_rows($arrayCod) > 0){

				$sql = "UPDATE USUARIOS_RESTRITOS SET DES_GATILHO='$valor' WHERE COD_EMPRESA = $cod_empresa AND TIP_RESTRIC = 'SLD'";
				fnEscreve($sql);
				fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());

			}
			
		break;

	}


?>