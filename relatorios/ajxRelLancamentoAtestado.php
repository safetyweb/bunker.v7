<?php

include '../_system/_functionsMain.php'; 

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];  
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);

$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);


//inicialização das variáveis - default 
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
    $dat_ini = fnDataSql($dias30); 
} 
if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
    $dat_fim = fnDataSql($hoje); 
}


switch($opcao) {

    case 'exportar':

    $nomeRel = $_GET['nomeRel'];
    $arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

    $sql = $sql = "SELECT AC.COD_ATESTADO, AC.DAT_INI, AC.DAT_FIM, AC.DES_IMG_ATESTADO, CL.NOM_CLIENTE FROM ATESTADOS_COLABORADOR AS AC
                                                                INNER JOIN CLIENTES AS CL ON AC.COD_CLIENTE = CL.COD_CLIENTE
                                                                WHERE AC.COD_EMPRESA = $cod_empresa
                                                                AND AC.COD_EXCLUSA IS NULL";

    //fnEscreve($sql);

    $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);			
    
    $arquivo = fopen($arquivoCaminho, 'w',0);
    
    while($headers=mysqli_fetch_field($arrayQuery)){
        $CABECHALHO[]=$headers->name;
    }
    fputcsv ($arquivo,$CABECHALHO,';','"','\n');

    while ($row=mysqli_fetch_assoc($arrayQuery)){  	

        $row[DAT_INI] = fnDataShort($row['DAT_INI']);
        $row[DAT_FIM] = fnDataShort($row['DAT_FIM']);
        $row[DES_IMG_ATESTADO] = fnBase64DecodeImg($row['DES_IMG_ATESTADO']);

        $array = array_map("utf8_decode", $row);
        fputcsv($arquivo, $array, ';', '"', '\n');	
    }
    fclose($arquivo);

    break;
    case 'paginar':



    $sql = "SELECT * FROM ATESTADOS_COLABORADOR AS AC
    INNER JOIN CLIENTES AS CL ON AC.COD_CLIENTE = CL.COD_CLIENTE
    WHERE AC.COD_EMPRESA = $cod_empresa
    AND AC.COD_EXCLUSA IS NULL";

                                    // fnEscreve($sql);
    $retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
    $totalitens_por_pagina = mysqli_num_rows($retorno);

                                    // fnescreve($sql);
    $numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

                                    // fnEscreve($numPaginas);
                                    //variavel para calcular o início da visualização com base na página atual
    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                    // ================================================================================
    $sql = "SELECT AC.*, CL.NOM_CLIENTE FROM ATESTADOS_COLABORADOR AS AC
    INNER JOIN CLIENTES AS CL ON AC.COD_CLIENTE = CL.COD_CLIENTE
    WHERE AC.COD_EMPRESA = $cod_empresa
    AND AC.COD_EXCLUSA IS NULL
    LIMIT $inicio, $itens_por_pagina";

                                    //fnEscreve($sql);
    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

    $count = 0;
    while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

        ?>  
        <tr>
            <td></td>
            <td><?= $qrListaVendas['COD_ATESTADO']; ?></td>
            <td><a href="action.do?mod=<?php echo fnEncode(1688); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?=fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?=$qrListaVendas['NOM_CLIENTE']; ?></a></td>
            <td><?= fnDataShort($qrListaVendas['DAT_INI']); ?></td>
            <td><?= fnDataShort($qrListaVendas['DAT_FIM']); ?></td>
            <td><?= fnBase64DecodeImg($qrListaVendas['DES_IMG_ATESTADO']); ?></td>
        </tr>
        <?php

        $count++;    
    }
    break;

}
?>