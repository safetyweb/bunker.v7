<?php 
include "_system/_functionsMain.php";

//echo fnDebug('true');

$opcao = $_GET['opcao'];
$cod_univend = $_GET['cod_univend'];
$cod_empresa = $_GET['cod_empresa'];

	$sql = "SELECT * FROM USUARIOS  
	WHERE USUARIOS.COD_EMPRESA = $cod_empresa 
	AND  FIND_IN_SET($cod_univend,USUARIOS.COD_UNIVEND)
	AND USUARIOS.DAT_EXCLUSA IS NULL
	AND USUARIOS.LOG_ESTATUS='S' 
	AND USUARIOS.COD_TPUSUARIO IN (2,7,11,8)";

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);

	switch ($opcao) {
		case 'vendedores':
		?>
		<option value="">&nbsp;</option>
		<?php
		while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {	
			echo "<option value='".$qrListaUnive['COD_USUARIO']."'>".ucfirst($qrListaUnive['NOM_USUARIO']). "</option>"; 
		}			
		break;    
	}		
?>



