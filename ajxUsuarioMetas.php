<?php 

	include '_system/_functionsMain.php'; 	

	// //echo fnDebug('true');

	$tipo = $_GET['tipo'];
	if($tipo == 'edit') @$count = fnLimpaCampo($_POST['count']); else @$count = fnLimpaCampo($_GET['count']);
	
	if (empty($_POST['LOG_ATIVO_'.$count])) {$log_ativo='N';}else{$log_ativo=$_POST['LOG_ATIVO_'.$count];}
	@$cod_usuario = fnLimpaCampoZero(@$_POST['COD_USUARIO_'.$count]);
	@$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA_'.$count]);
	@$cod_univend = fnLimpaCampoZero(@$_POST['COD_UNIVEND_'.$count]);

	switch($tipo){

		case 'radio':
		
			if ($cod_usuario <= 0){
					$sql = "UPDATE UNIDADEVENDA SET 
							LOG_ATIVOMETA = '$log_ativo'
							WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND=$cod_univend";
							echo "UPDATE UNIDADEVENDA SET 
							LOG_ATIVOMETA = '$log_ativo'
							WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND=$cod_univend";
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
					mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
			}else{
				$sql = "SELECT COD_REGISTRO FROM CONTROLE_METAS WHERE COD_ATENDENTE = $cod_usuario AND COD_EMPRESA = $cod_empresa";
				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
				$cod_registro = mysqli_fetch_assoc($arrayQuery);

				if(count($cod_registro['COD_REGISTRO']) > 0){
					//echo "Tem registro desse usuário";
					$sql = "UPDATE CONTROLE_METAS SET 
							LOG_ATIVO = '$log_ativo'
							WHERE COD_EMPRESA = $cod_empresa AND COD_ATENDENTE = $cod_usuario AND COD_REGISTRO = ".$cod_registro['COD_REGISTRO'];
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
				}else{
					//echo "Não tem registro desse usuário";
					$sql = "INSERT INTO CONTROLE_METAS(
							LOG_ATIVO, 
							COD_EMPRESA, 
							COD_UNIDADE, 
							COD_ATENDENTE
							) VALUES(
							'$log_ativo',
							$cod_empresa,
							$cod_univend,
							$cod_usuario
							)";
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
				}
			}

		break;

		case 'edit':

			$cod_usuario = fnLimpaCampoZero($_POST['pk']);
			$campo = fnLimpaCampo($_POST['name']);
			$valor = fnLimpaCampo($_POST['value']);
			$cod_empresa = fnLimpaCampoZero($_POST['empresa']);
			$cod_univend = fnLimpaCampoZero($_POST['univend']);
			$action = fnLimpaCampo($_POST['action']);

			// fnEscreve($action);

			if ($action == "tr"){

				$sql = "SELECT COUNT(0) QTD FROM CONTROLE_METAS_DESC WHERE COD_DESCRICAO = '$campo' AND COD_EMPRESA = $cod_empresa";
				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
				$rs = mysqli_fetch_assoc($arrayQuery);
				
				if($rs["QTD"] > 0){
					//echo "Tem registro desse usuário";
					$sql = "UPDATE CONTROLE_METAS_DESC SET 
							NOM_DESCRICAO = '$valor'
							WHERE COD_EMPRESA = $cod_empresa AND COD_DESCRICAO = '$campo'";
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
				}else{
					$sql = "INSERT INTO CONTROLE_METAS_DESC( 
							COD_EMPRESA, 
							COD_DESCRICAO, 
							NOM_DESCRICAO
							) VALUES(
							$cod_empresa,
							'$campo',
							'$valor'
							)";

					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
				}

			}elseif ($campo == "COD_TURNO"){
				$sql = "UPDATE usuarios SET COD_TURNO = ".$valor."
				where COD_EMPRESA = $cod_empresa AND COD_USUARIO = $cod_usuario";
				$arrayTurno = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
				$turnos = array();
				$turnos[0] = "Turno não definido";
				while ($qrTurno = mysqli_fetch_assoc($arrayTurno)){
					$turnos[$qrTurno["COD_TURNO"]] = $qrTurno["NOM_TURNO"];
				}

			}elseif ($action == "combo"){

				$campo = str_replace("_COMBO", "", $campo);

				$sql = "UPDATE CONTROLE_METAS_DESC SET 
								DES_COMBO = '$valor'
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_DESCRICAO = '$campo'";

				// fnEscreve($sql);
				
				mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());

			}else{

				$sql = "SELECT COD_REGISTRO FROM CONTROLE_METAS WHERE COD_ATENDENTE = $cod_usuario AND COD_EMPRESA = $cod_empresa";
				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
				$cod_registro = mysqli_fetch_assoc($arrayQuery);

				if(count($cod_registro['COD_REGISTRO']) > 0){
					//echo "Tem registro desse usuário";
					$sql = "UPDATE CONTROLE_METAS SET 
							$campo = ".fnValorSql($valor)."
							WHERE COD_EMPRESA = $cod_empresa AND COD_ATENDENTE = $cod_usuario AND COD_REGISTRO = ".$cod_registro['COD_REGISTRO'];
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
				}else{
					//echo "Não tem registro desse usuário";
					$sql = "INSERT INTO CONTROLE_METAS( 
							COD_EMPRESA, 
							COD_UNIDADE, 
							COD_ATENDENTE,
							$campo
							) VALUES(
							$cod_empresa,
							$cod_univend,
							$cod_usuario,
							".fnValorSql($valor)."
							)";
					mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
				}
			}
		break;

		case 'combo':

			$campo = fnLimpaCampo($_POST['name']);
			$campo = str_replace("_COMBO", "", $campo);
			$valor = fnLimpaCampo($_POST['value']);
			$cod_empresa = fnLimpaCampoZero($_POST['empresa']);

			$sql = "UPDATE CONTROLE_METAS_DESC SET 
							DES_COMBO = '$valor'
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_DESCRICAO = '$campo'";
			mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());

		break;

		case 'coluna':

			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
			$coluna = fnLimpaCampo($_POST['COLUNA']);
			$status = fnLimpaCampo($_POST['STATUS']);

			$sql = "UPDATE CONTROLE_METAS_DESC SET 
							LOG_STATUS = '$status'
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_DESCRICAO = '$coluna'";
			mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());

		break;
	}



						
?>