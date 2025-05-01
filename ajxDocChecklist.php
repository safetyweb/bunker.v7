<?php 

	include '_system/_functionsMain.php'; 

	//echo fnDebug('true');

	//fnMostraForm();
	
	$cod_checkli = $_POST['COD_CHECKLI'];
	$cod_empresa = $_POST['COD_EMPRESA'];

	//fnEscreve($cod_checkli);

	$sql = "SELECT DOCUMENTOSCHECKLIST.COD_DOCCHEC,
					DOCUMENTOSCHECKLIST.COD_EMPRESA,
					DOCUMENTOSCHECKLIST.COD_CHECKLI,
					DOCUMENTOSCHECKLIST.COD_DOCUMEN,
					DOCUMENTOSCHECKLIST.LOG_OBRIGAT,
					DOCUMENTOSCHECKLIST.QTD_VALIDAD,
					EMPRESAS.NOM_EMPRESA,
					CHECKLIST.DES_DESCRIC,
					DOCUMENTOS.NOM_DOCUMEN
		FROM DOCUMENTOSCHECKLIST
			LEFT JOIN $connAdm->DB.empresas ON DOCUMENTOSCHECKLIST.COD_EMPRESA = empresas.COD_EMPRESA
			LEFT JOIN CHECKLIST ON DOCUMENTOSCHECKLIST.COD_CHECKLI = CHECKLIST.COD_CHECKLI
			LEFT JOIN DOCUMENTOS ON DOCUMENTOSCHECKLIST.COD_DOCUMEN = DOCUMENTOS.COD_DOCUMEN
		WHERE empresas.COD_EMPRESA = $cod_empresa AND DOCUMENTOSCHECKLIST.COD_CHECKLI = $cod_checkli";


			
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

	$count=0;
	while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
	  {														  
		$count++;	
		echo"
			<tr>
			  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
			  <td>".$qrBuscaModulos['COD_DOCCHEC']."</td>
			  <td>".$qrBuscaModulos['NOM_EMPRESA']."</td>
			  <td>".$qrBuscaModulos['DES_DESCRIC']."</td>
			  <td>".$qrBuscaModulos['NOM_DOCUMEN']."</td>
			  <td>".$qrBuscaModulos['LOG_OBRIGAT']."</td>
			  <td>".$qrBuscaModulos['QTD_VALIDAD']." dias</td>
			</tr>
			
			<input type='hidden' id='ret_COD_DOCCHEC_".$count."' value='".$qrBuscaModulos['COD_DOCCHEC']."'>
			<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaModulos['COD_EMPRESA']."'>
			<input type='hidden' id='ret_COD_CHECKLI_".$count."' value='".$qrBuscaModulos['COD_CHECKLI']."'>
			<input type='hidden' id='ret_COD_DOCUMEN_".$count."' value='".$qrBuscaModulos['COD_DOCUMEN']."'>
			<input type='hidden' id='ret_LOG_OBRIGAT_".$count."' value='".$qrBuscaModulos['LOG_OBRIGAT']."'>
			<input type='hidden' id='ret_QTD_VALIDAD_".$count."' value='".$qrBuscaModulos['QTD_VALIDAD']."'>
			"; 
		  }
	
	
?>



