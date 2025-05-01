<?php 

	include '_system/_functionsMain.php';
	include '_system/functionwhatsapp.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$opcao = fnLimpaCampo($_GET['opcao']);
	// $des_mensagem = $_REQUEST['DES_MENSAGEM'];

	$sqlconfg = "SELECT * FROM CONFIGURACAO_ACESSO WHERE COD_EMPRESA = $cod_empresa AND COD_PARCOMU = 13 AND LOG_STATUS = 'S'";
	$rsconfig = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlconfg));
	
	$arraydados = array('conadmin'=> $connAdm->connAdm(),
		'conempresa' => connTemp($cod_empresa,''),
		'cod_empresa' => $cod_empresa,
		'url' => $rsconfig['DES_EMAILUS'],
		'Authorization' => $rsconfig['DES_AUTHKEY']
	);

	switch ($opcao) {

		case 'status':
			
			$retorno = fnstatuswhatsapp($arraydados);

			if($retorno['connected']){
				echo 'true';
			}else{
				echo 'false';
			}

		break;

		default:

			$retorno = fnqrcodwhatsapp($arraydados);

			// fnEscreve($cod_empresa);

		?>

			<img src="<?=$retorno?>" width="300px" style="margin-left: auto; margin-right: auto;">

		<?php

		break;

	}