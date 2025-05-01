<?php
include_once '../_system/_functionsMain.php';
$connprod=$prod_continuo->connUser();

$sql1 ='SELECT  NOM_PRODUTO,COD_PATOLOGIA FROM produtos_base';
$rw1= mysqli_query($connprod, $sql1);
$contador=1;
while ($rs1= mysqli_fetch_assoc($rw1)) {
    
    $nome=explode(' ', $rs1['NOM_PRODUTO']);
    $lista="SELECT  * FROM import_prod WHERE SUBSTANCIA LIKE '".$nome['0']."%'";
    $rw2=mysqli_query($connprod, $lista);
    while ($rs2= mysqli_fetch_assoc($rw2)) {
        
     $in=   "INSERT INTO produtocontinuo (
                                        SUBSTANCIA, 
                                        CNPJ, 
                                        LABORATORIO, 
                                        CODIGO_GGREM,
                                        REGISTRO, 
                                        EAN1, 
                                        EAN2, 
                                        EAN3, 
                                        PRODUTO, 
                                        APRESENTACAO, 
                                        CLASSE_TERAPEUTICA,
                                        TIPO_PRODUTO,
                                        REGIME_PRECO,
                                        COD_PATOLOGICO
                                        )
                                        VALUES 
                                        (
                                        '".$rs2[SUBSTANCIA]."', 
                                        '".$rs2[CNPJ]."', 
                                        '".$rs2[LABORATORIO]."', 
                                        '".$rs2[CODIGO_GGREM]."', 
                                        '".$rs2[REGISTRO]."', 
                                        '".$rs2[EAN1]."', 
                                        '".$rs2[EAN2]."', 
                                        '".$rs2[EAN3]."', 
                                        '".$rs2[PRODUTO]."', 
                                        '".$rs2[APRESENTACAO]."', 
                                        '".$rs2[CLASSE_TERAPEUTICA]."', 
                                        '".$rs2[TIPO_PRODUTO]."', 
                                        '".$rs2[REGIME_PRECO]."', 
                                        '".$rs1[COD_PATOLOGIA]."'
                                        );";
      mysqli_query($connprod, $in);

     $contador++;
    }
}

echo $contador;