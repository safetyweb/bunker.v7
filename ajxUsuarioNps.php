<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_usuario = fnLimpaCampoZero($_POST['COD_USUARIO']);
	$cod_usucada = $_SESSION[SYS_COD_USUARIO];
	$opcao = fnLimpaCampo($_GET['opcao']);

	switch($opcao){

		case 'exc':

			$sql = "DELETE FROM USUARIOS_NPS 
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_USUARIO = $cod_usuario";

			mysqli_query($connAdm->connAdm(),$sql);

			$sqlcount = "SELECT 1 FROM USUARIOS_NPS WHERE COD_EMPRESA = $cod_empresa";
			$arrayCount = mysqli_query($connAdm->connAdm(),$sqlcount);

			if(mysqli_num_rows($arrayCount) == 0){

				$cod_campanha = fnDecode($_GET['idc']);

				$sqlAlerta = "DELETE FROM ALERTA_EMAIL WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";
				mysqli_query($connAdm->connAdm(),$sqlAlerta);

				FNeSCREVE($sqlAlerta);

			}

		break;

		default:

			$sql = "INSERT INTO USUARIOS_NPS(
									COD_EMPRESA,
									COD_USUARIO,
									COD_USUCADA
								) VALUES(
									$cod_empresa,
									$cod_usuario,
									$cod_usucada
								)";

			mysqli_query($connAdm->connAdm(),$sql);

			$cod_campanha = fnDecode($_GET['idc']);	
							
			$sqlcount = "SELECT 1 FROM ALERTA_EMAIL WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha ";
			$arrayCount = mysqli_query($connAdm->connAdm(),$sqlcount);

			if(mysqli_num_rows($arrayCount) == 0){

				$sql3 = "INSERT INTO alerta_email(
									COD_EMPRESA,
									COD_CAMPANHA,
									COD_TIPO
									) VALUES(
									$cod_empresa,
									$cod_campanha,
									2
									)";
				//fnEscreve($sql);
				
				mysqli_query($connAdm->connAdm(),$sql3);

			}

		break;

	}

?>