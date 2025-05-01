
<?php 

include '../_system/_functionsMain.php'; 

//echo fnDebug('true');
$cod_empresa = fnDecode($_GET['id']);
$cod_bem = fnLimpaCampoZero($_GET['codBem']);

$sql = "SELECT
US.NOM_CLIENTE,
BP.COD_PROPRIETARIO,
BP.VAL_PARTICIPACAO_PC
FROM BENS_PROPRIETARIOS AS BP 
INNER JOIN CLIENTES AS US ON
BP.COD_PROPRIETARIO = US.COD_CLIENTE
WHERE
BP.COD_EMPRESA = '$cod_empresa' AND
BP.COD_BEM = '$cod_bem' AND
BP.DAT_EXCLUSA IS NULL";

$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
$count=0;
?>
<tr>
    <th></th>
    <th class='text-right'>Código Cliente</th>
    <th class='text-right'>Nome Cliente</th>
    <th class='text-right'>Área Participação</th>
</tr>

<?php

while ($qrCampanhasEmail = mysqli_fetch_assoc($arrayQuery)){                                                      
    $count++;

    ?>
    <tr>
       <td></td>
       <td class='text-right'><small style="font-weight: normal;"><?=$qrCampanhasEmail['COD_PROPRIETARIO']?></small></td>
       <td class='text-right'><small style="font-weight: normal;"><?=$qrCampanhasEmail['NOM_CLIENTE']?></small></td>
       <td class="text-right" style="font-weight: normal;"><small><?= fnValor($qrCampanhasEmail['VAL_PARTICIPACAO_PC'],2);?></small></td>
   </tr>
   <?php
}

?>