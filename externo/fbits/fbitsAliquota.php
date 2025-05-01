<?php
if($_GET['ID']=='')
{
  
}else{
    require '../../_system/_functionsMain.php';
    $cod_empresa=$_GET['ID'];
    $conntempo= connTemp($cod_empresa, '');
}
$xmlteste=file_get_contents("php://input");
$xmlteste1= json_decode($xmlteste,true);

      
$insertVLRESGATE=" INSERT INTO log_integration_resgate_fbits 
                    (DES_VENDA,COD_EMPRESA,CPF_CLIENTE,DAT_CADASTR,COD_INSERT) 
                    VALUES ('".addslashes(serialize($xmlteste1))."',
                               $cod_empresa,
                           '".$xmlteste1['Usuario']['Cpf']."',
                           '".date('Y-m-d')."',
                           1     
                         );";
mysqli_query($connAdm->connAdm(), $insertVLRESGATE);

$buca_arrayvenda="SELECT  * FROM log_integration_resgate_fbits where CPF_CLIENTE= '".$xmlteste1['Usuario']['Cpf']."' ORDER BY COD_ORIGEM desc  limit 1";
$rsTEMP= mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $buca_arrayvenda));
  $Arraydados=unserialize($rsTEMP['DES_VENDA']);      
  
    $UPalterresgtri="UPDATE log_integration_resgate 
                                    SET COD_EXT_USER='".$Arraydados['Usuario']['Id']."',
                                    COD_INSERT='2',   
                                    DES_VENDA='".addslashes(serialize($Arraydados))."'
                                    WHERE  CPF_CLIENTE='".$Arraydados['Usuario']['Cpf']."' and 
                                           COD_EXT_USER is null and 
                                           DAT_CADASTR='".date('Y-m-d')."' and
                                           COD_EMPRESA='$cod_empresa';";
    mysqli_query($conntempo, $UPalterresgtri);
    
$buca_saldo="SELECT  * FROM log_integration_resgate where CPF_CLIENTE= '".$xmlteste1['Usuario']['Cpf']."' order by COD_ORIGEM desc limit 1";
$rssaldo= mysqli_fetch_assoc(mysqli_query($conntempo, $buca_saldo));    
$saldovar='-'.$rssaldo['VL_RESGATE'];
echo  "[
            {
                ProdutoVarianteId: 270245,
                Valor: $saldovar,
                Nome: 'Clube de vantagens Marka'
            }
        ]";
