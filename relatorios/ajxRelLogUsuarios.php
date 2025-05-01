<?php 

	include '../_system/_functionsMain.php'; 
	// require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	// use Box\Spout\Writer\WriterFactory;
	// use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	// $opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
	$cod_tpusuario = fnLimpaCampoZero($_POST['COD_TPUSUARIO']);			
	$log_estatus = fnLimpaCampo($_POST['LOG_ESTATUS']);			
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);

	//fnEscreve($dat_ini);

	if($cod_empresa != '' && $cod_empresa != 0){
		$andEmpresa = "AND U.COD_EMPRESA = $cod_empresa ";
	}else{
		$andEmpresa = "";
	}

	if($cod_tpusuario != '' && $cod_tpusuario != 0){
		$andTipoUsu = "AND U.COD_TPUSUARIO = $cod_tpusuario ";
	}else{
		$andTipoUsu = "";
	}

	if($log_estatus != '' && $log_estatus !='I'){
		$andEstatus = "AND U.LOG_ESTATUS = '$log_estatus' ";
	}else if($log_estatus =='I'){
		$andEstatus = "AND (U.LOG_ESTATUS = '' OR U.LOG_ESTATUS IS NULL)  ";
	}else{
		$andEstatus = "";
	}

	$sql = "SELECT U.COD_USUARIO
			FROM USUARIOS U
			INNER JOIN TIPOUSUARIO TP ON TP.COD_TPUSUARIO=U.COD_TPUSUARIO
			LEFT JOIN UNIDADEVENDA UN ON UN.COD_UNIVEND = U.COD_UNIVEND
			WHERE U.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
			$andEmpresa
			$andTipoUsu
			$andEstatus
	";

			//fnEscreve($sql);
			
			$retorno = mysqli_query($connAdm->connAdm(),$sql);
			$total_itens_por_pagina = mysqli_num_rows($retorno);
			
			$numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	
			
			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
	
		$sql = "SELECT  
		      U.COD_UNIVEND,
		      UN.NOM_FANTASI,
			  U.NOM_USUARIO,
			  U.DAT_CADASTR,
			  U.DAT_ALTERAC,
			  U.DAT_EXCLUSA,
			  (SELECT US.NOM_USUARIO FROM USUARIOS US WHERE US.COD_USUARIO = U.COD_EXCLUSA) AS USU_EXCLUSA,
			  U.LOG_ESTATUS,
			  TP.DES_TPUSUARIO
		FROM USUARIOS U
		INNER JOIN TIPOUSUARIO TP ON TP.COD_TPUSUARIO=U.COD_TPUSUARIO
		LEFT JOIN UNIDADEVENDA UN ON UN.COD_UNIVEND = U.COD_UNIVEND
		WHERE U.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
		$andEmpresa
		$andTipoUsu
		$andEstatus
		ORDER BY COD_USUARIO desc
		LIMIT $inicio,$itens_por_pagina";
		
		//fnEscreve($sql);
		//fnTestesql(connTemp($cod_empresa,''),$sql);
		
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		
		$count=0;
		
		while ($qrUsuario = mysqli_fetch_assoc($arrayQuery))
		{

			if($qrUsuario['LOG_ESTATUS'] == "S"){
				$ativo = "<span class='fas fa-check text-success'></span>";
			}else if($qrUsuario['LOG_ESTATUS'] == "N"){
				$ativo = "<span class='fas fa-times text-danger'></span>";
			}else{
				$ativo = "<span class='f14'>Indefinido</span>";
			}


			$count++;	
			
?>
			<tr>
				<td><small><?=$qrUsuario['NOM_FANTASI']?></small></td>
				<td><small><?=$qrUsuario['NOM_USUARIO']?></small></td>
				<td><small><?=$qrUsuario['DES_TPUSUARIO']?></small></td>
				<td class="text-center"><small><?=fnDataFull($qrUsuario['DAT_CADASTR'])?></small></td>
				<td class="text-center"><small><?=fnDataFull($qrUsuario['DAT_ALTERAC'])?></small></td>
				<td><small><?=$qrUsuario['USU_EXCLUSA']?></small></td>
				<td class="text-center"><small><?=fnDataFull($qrUsuario['DAT_EXCLUSA'])?></small></td>
				<td class="text-center"><?=$ativo?></td>
			</tr>


<?php											
		}

?>