<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_persona = fnLimpaCampoZero($_REQUEST['COD_PERSONA']);
	$count_filtros = fnLimpacampo($_REQUEST['COUNT_FILTROS']);

	if($count_filtros != ""){

		$sql = "DELETE FROM FILTROS_PERSONA WHERE COD_PERSONA = $cod_persona;";
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		for ($i=0; $i < $count_filtros; $i++) {

			$cod_tpfiltro = fnLimpacampoZero($_REQUEST["COD_TPFILTRO_$i"]);

			if (isset($_REQUEST["COD_FILTRO_$i"])){

				// fnEscreve("TEM FILTRO");

				$Arr_COD_FILTRO = $_REQUEST['COD_FILTRO_$i'];

				//print_r($_REQUEST["COD_FILTRO_$i"]);	 
				 
				for ($j=0;$j<count($_REQUEST["COD_FILTRO_$i"]);$j++){

					$sql .= "INSERT INTO FILTROS_PERSONA(
									COD_EMPRESA,
									COD_TPFILTRO,
									COD_FILTRO,
									COD_PERSONA,
									COD_USUCADA
									)VALUES(
									$cod_empresa,
									$cod_tpfiltro,
									".$_REQUEST["COD_FILTRO_$i"][$j].",
									$cod_persona,
									$cod_usucada
									);
							";


				} 
					
			}

					
		}
		
		if($sql != ""){
			// fnEscreve($sql);
			mysqli_multi_query(connTemp($cod_empresa,''),$sql);
		}							

	}

	//array dos sistemas da empresas
	

?>