<?php


	include './_system/_functionsMain.php'; 
	// require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	// use Box\Spout\Writer\WriterFactory;
	// use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
	$num_cartao = fnLimpaCampoZero($_POST['c10']);			
	


        $sql = "SELECT COD_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND NUM_CARTAO = $num_cartao";

		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

		$cont = mysqli_num_rows($arrayQuery);

		if($cont == 1){
			echo 1;
		}else{
			echo "Cartão não encontrado. Favor consultar por CPF.";
		}
				

?>