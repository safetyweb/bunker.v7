<?php
$IP = $_GET['ip'];
include '../../_system/_functionsMain.php';
$conadmf = $connAdm->connAdm();
$cliente = "SELECT bs.COD_EMPRESA FROM tab_database bs
INNER JOIN empresas emp ON emp.COD_EMPRESA=bs.COD_EMPRESA AND emp.LOG_ATIVO='S' and emp.cod_empresa NOT IN (136,514)
WHERE IP='" . $IP . "'
GROUP BY bs.NOM_DATABASE";
echo $cliente;
//WHERE bs.NOM_DATABASE='db_host1'
$rs = mysqli_query($conadmf, $cliente);
while ($row = mysqli_fetch_assoc($rs)) {
    $contemporaria = connTemp($row['COD_EMPRESA'], '');

    $sql1 = "UPDATE EVENTOS SET
         DAT_EVENTO =NOW()
          WHERE COD_EVENTO=5;";
    mysqli_query($contemporaria, $sql1);

    $persona = "CALL SP_ATUALIZA_PERSONA('cad');";
    mysqli_query($contemporaria, $persona);

    $sql2 = "UPDATE EVENTOS SET DAT_FINAL =NOW() WHERE COD_EVENTO=5;";
    mysqli_query($contemporaria, $sql2);

    mysqli_close($contemporaria);
}
echo 'CONCLUIDO';
