<?php 
//ajxestornavenda
include "_system/_functionsMain.php"; 
include './_system/_FUNCTION_WS.php';

//echo fnDebug('true');

//fnEscreve("chegou...");

$codPdv = fnLimpacampo($_GET['ajx1']);
$cod_empresa = $_GET['id'];
//busca de usuario webservices
  $bsusr= "SELECT * FROM  USUARIOS
              WHERE LOG_ESTATUS='S' AND
               COD_EMPRESA = $cod_empresa AND
                COD_UNIVEND > 0 AND
                  COD_TPUSUARIO =10 limit 1";
    //fnEscreve($bsusr); 
    $arrayQuery = mysqli_query($connAdm->connAdm(), $bsusr) or die(mysqli_error());
    $qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
    $cod_univendarray= explode(',',$qrBuscaUsuTeste['COD_UNIVEND'] );
   


$arraydadosCli=array('id_vendapdv'=>$codPdv,
                      'login'=>$qrBuscaUsuTeste['LOG_USUARIO'],
                      'senha'=> fnDecode($qrBuscaUsuTeste['DES_SENHAUS']),
                      'COD_UNIVEND'=>$cod_univendarray[0],
                      'COD_EMPRESA'=>$cod_empresa
                       );
excluivendatotal ($arraydadosCli);


/*
	$sql = "CALL SP_DESBLOQUEA_VENDA(" .$codCliente. ", " .$codVenda. ", " .$codEmpresa. ", '" .$logGeral. "', '" .$_SESSION["SYS_COD_USUARIO"]. "', '" .$opcao. "' )";
	fnEscreve($sql);
	fnEscreve($codEmpresa);
	mysqli_query(connTemp($codEmpresa,''),trim($sql)) or die(mysqli_error());			
	
*/
	
?>
<div class="help-block with-errors"></div>			