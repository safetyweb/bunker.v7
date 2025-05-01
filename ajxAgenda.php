<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));

	//array dos sistemas da empresas
	if (isset($_GET['idU'])){

		$Arr_COD_USUARIOS_AGE = json_decode($_GET['idU']);
		//print_r($Arr_COD_SISTEMAS);

		// echo(is_array($Arr_COD_USUARIOS_AGE));

		if(is_array($Arr_COD_USUARIOS_AGE)){		 
	 
		    for ($i=0;$i<count($Arr_COD_USUARIOS_AGE);$i++){

				$cod_usuarios_age = $cod_usuarios_age.$Arr_COD_USUARIOS_AGE[$i].",";

		    } 
		   
		    $cod_usuarios_age = ltrim(rtrim($cod_usuarios_age,","),",");

		}else{
			$cod_usuarios_age = $Arr_COD_USUARIOS_AGE;
		}
		
	}else{

		$cod_usuarios_age = "0";

	}

	// print_r(json_decode($_GET['idU']));

	$eventos = [];

	$sql = "SELECT EV.*, TE.DES_COR, UE.COD_USUARIO FROM EVENTOS_AGENDA EV 
	LEFT JOIN TIPO_EVENTO TE ON TE.COD_TPEVENT = EV.COD_TPEVENT
	LEFT JOIN USUARIO_EVENTO UE ON UE.COD_EVENT = EV.COD_EVENT
	WHERE EV.COD_EMPRESA = $cod_empresa
	AND UE.COD_USUARIO IN($cod_usuarios_age)
	AND COD_EXCLUSA = 0
	";

	// echo("_".$sql."_");

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	while($qrEvento = mysqli_fetch_assoc($arrayQuery)){

		$sqlUsu = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrEvento[COD_USUARIO]";
		$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlUsu));

		$titulo = "(".fnIniciais($qrUsu['NOM_USUARIO']).") ".$qrEvento['NOM_EVENT'];

		if(strlen($qrEvento['HOR_INI']) == 1){
			$horaIni = "0".$qrEvento['HOR_INI'];
		}else{
			$horaIni = $qrEvento['HOR_INI'];
		}
		if(strlen($qrEvento['HOR_FIM']) == 1){
			$horaFim = "0".$qrEvento['HOR_FIM'];
		}else{
			$horaFim = $qrEvento['HOR_FIM'];
		}

		if($horaIni == $horaFim){
			$horaFim++;
		}

		$inicio = $qrEvento['DAT_INI']." ".$horaIni.":00";
		$fim = $qrEvento['DAT_FIM']." ".$horaFim.":00";

		if($qrEvento['DIAS_REPETE'] != ''){

			$inicioRepete = $qrEvento['DAT_INI'];
			$fimRepete = date('Y-m-d', strtotime("+1 day", strtotime($qrEvento['DAT_FIM'])));
			$array_repete = explode(',', $qrEvento["DIAS_REPETE"]);

			$evento = [
					"title" => $titulo,
					"id" => $qrEvento['COD_EVENT'],
					"start" => $inicio,
					"end" => $fim,				
					"color" => $qrEvento['DES_COR'],
					"daysOfWeek" => json_encode($array_repete),
					"startTime" => $horaIni.":00",
					"endTime" => $horaFim.":00",
					"startRecur" => $inicioRepete,
					"endRecur" => $fimRepete

				  ];
		
		}else{

			$evento = [
					"title" => $titulo,
					"id" => $qrEvento['COD_EVENT'],
					"start" => $inicio,
					"end" => $fim,				
					"color" => $qrEvento['DES_COR']

				  ];

		}
		
		

		array_push($eventos, $evento);

	}

	echo json_encode($eventos);

?>