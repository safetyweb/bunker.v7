<?php

include '../_system/_functionsMain.php'; 
$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];  
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);
$cod_propriedade = $_POST['COD_PROPRIEDADE'];
$cod_chale = $_POST['COD_CHALE'];
$dat_ini = $_POST['DAT_INI'];
$dat_fim = $_POST['DAT_FIM'];
$filtro_data = $_POST['FILTRO_DATA'];
$cod_statuspag = $_POST['COD_STATUSPAG'];
$cod_formapag = $_POST['COD_FORMAPAG'];


    if ($cod_propriedade == "" OR $cod_propriedade == 9999){
        $and_propriedade = " ";

    }else{
        $and_propriedade = "AND ACI.COD_PROPRIEDADE = $cod_propriedade";

    }
    if ($cod_chale != ""){
        $and_chale = "AND ACI.COD_CHALE = $cod_chale";
    }else{
        $and_chale = " ";
    }

    if($filtro_data == "ALTERACAO"){
        $andDat = "AND ACI.DAT_ALTERAC >= '$dat_alterac 00:00:00'
        AND ACI.DAT_ALTERAC >= '$dat_alterac 23:59:59'";

    }else if($filtro_data == "DEFAULT"){
        $andDat = " AND ACI.DAT_INICIAL >= '$dat_ini 00:00:00'
        AND ACI.DAT_FINAL <= '$dat_fim 23:59:59'";

    }else{
        $andDat = "AND ACI.DAT_CADASTR >= '$dat_ini 00:00:00'
        AND ACI.DAT_CADASTR <= '$dat_fim 23:59:59'";

    }

    if($cod_statuspag != ""){
        $andStatusPag = "AND AC.COD_STATUSPAG = $cod_statuspag";
    }else{
        $andStatusPag ="";
    }   

    if($cod_formapag != ""){
        $andFormaPag = "AND AC.COD_FORMAPAG = $cod_formapag";
    }else{
        $andFormaPag ="";
    }


switch($opcao) {

    case 'exportar':

    $nomeRel = $_GET['nomeRel'];
    $arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

    $sql = "
    SELECT DISTINCT 
    AC.COD_CARRINHO,
    AC.TELEFONE,
    ACI.DAT_INICIAL,
    ACI.DAT_FINAL,
    ACI.VALOR,
    UNI.NOM_FANTASI,
    ACI.COD_PROPRIEDADE,
    ACI.COD_ITEM,
    CH.NOM_QUARTO
    FROM adorai_carrinho AS AC
    INNER JOIN adorai_carrinho_items AS ACI ON ACI.COD_CARRINHO = ac.COD_CARRINHO
    LEFT JOIN unidadevenda AS UNI ON UNI.cod_externo = ACI.COD_PROPRIEDADE
    LEFT JOIN adorai_chales AS CH ON CH.cod_externo = ACI.COD_CHALE
    WHERE
    AC.COD_EMPRESA = $cod_empresa 
    $andDat
    $andStatusPag
    $andFormaPag
    $and_propriedade
    $and_chale
    ORDER BY AC.COD_CARRINHO 
    ";

 $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);			

 $arquivo = fopen($arquivoCaminho, 'w',0);

 while($headers=mysqli_fetch_field($arrayQuery)){
    $CABECHALHO[]=$headers->name;
}
fputcsv ($arquivo,$CABECHALHO,';','"','\n');

while ($row=mysqli_fetch_assoc($arrayQuery)){  	

    $row[TELEFONE] = fnmasktelefone($row['TELEFONE']);
    $row[DAT_INICIAL] = fnDataShort($row['DAT_INICIAL']);
    $row[DAT_FINAL] = fnDataShort($row['DAT_FINAL']);
    $row[VALOR] = fnValor($row['VALOR'],2);
    
    $array = array_map("utf8_decode", $row);
    fputcsv($arquivo, $array, ';', '"', '\n');	
}
fclose($arquivo);

break;
case 'paginar':

                // Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

                // contador ===================
if($modulo == 2004) {
    $sql = "SELECT 1
    FROM creditosdebitos a
    INNER JOIN clientes b ON b.COD_CLIENTE = a.COD_CLIENTE
    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND
    WHERE a.cod_cliente=b.cod_cliente 
    AND a.cod_empresa=b.cod_empresa 
    AND a.cod_empresa=$cod_empresa 
    AND a.cod_credlot>0
    $andCredlot
    AND a.COD_UNIVEND IN($lojasSelecionadas)
    GROUP BY a.cod_cliente
    ORDER BY b.NOM_CLIENTE";
}else{
    $sql = "SELECT 1
    FROM creditosdebitos a
    INNER JOIN clientes b ON b.COD_CLIENTE = a.COD_CLIENTE
    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND
    WHERE a.cod_cliente=b.cod_cliente 
    AND a.cod_empresa=b.cod_empresa 
    AND a.cod_empresa=$cod_empresa 
    AND a.cod_credlot>0
    AND a.COD_UNIVEND IN($lojasSelecionadas) 
    AND DATE(a.dat_expira) > DATE(NOW())
    GROUP BY a.cod_cliente
    ORDER BY b.NOM_CLIENTE";
}

                // fnEscreve($sql);
$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
$totalitens_por_pagina = mysqli_num_rows($retorno);

                // fnescreve($sql);

$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

                // fnEscreve($numPaginas);
                //variavel para calcular o início da visualização com base na página atual
$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                // fnEscreve($inicio);
                // fnEscreve($totalitens_por_pagina);
                // ================================================================================
if($modulo == 2004){

    $sql = "SELECT b.COD_CLIENTE, 
    b.NOM_CLIENTE, 
    a.COD_UNIVEND, 
    uni.NOM_FANTASI, 
    b.NUM_CELULAR,
    b.DAT_NASCIME,
    DATE_FORMAT (a.dat_expira, '%d/%m/%Y') DATA_VENCIMENTO, 
    SUM(val_credito) AS val_credito, 
    SUM(val_saldo)SALDO_CAMPANHA,
    (SELECT ifnull(SUM(AA.VAL_SALDO),0)
        FROM CREDITOSDEBITOS AA,empresas c
        WHERE AA.COD_CLIENTE=A.COD_CLIENTE 
        AND C.COD_EMPRESA=AA.COD_EMPRESA 
        AND AA.TIP_CREDITO='C' 
        AND AA.COD_STATUSCRED=1
        AND AA.tip_campanha = c.TIP_CAMPANHA 
        AND (DATE_FORMAT(AA.DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(AA.LOG_EXPIRA='N'))
        )AS CREDITO_DISPONIVEL_GERAL
    FROM creditosdebitos a
    INNER JOIN clientes b ON b.COD_CLIENTE = a.COD_CLIENTE
    INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND
    WHERE a.cod_cliente=b.cod_cliente 
    AND a.cod_empresa=b.cod_empresa 
    AND a.cod_empresa=$cod_empresa 
    $andCredlot
    AND a.COD_UNIVEND IN($lojasSelecionadas)
    GROUP BY a.cod_cliente
    ORDER BY b.NOM_CLIENTE
    LIMIT $inicio, $itens_por_pagina";
}else{


   $sql = "SELECT b.COD_CLIENTE, 
   b.NOM_CLIENTE, 
   a.COD_UNIVEND, 
   uni.NOM_FANTASI, 
   b.NUM_CELULAR,
   b.DAT_NASCIME, 
   DATE_FORMAT (a.dat_expira, '%d/%m/%Y') DATA_VENCIMENTO, 
   SUM(val_credito) AS val_credito, 
   SUM(val_saldo)SALDO_CAMPANHA,
   (SELECT ifnull(SUM(AA.VAL_SALDO),0)
    FROM CREDITOSDEBITOS AA,empresas c
    WHERE AA.COD_CLIENTE=A.COD_CLIENTE 
    AND C.COD_EMPRESA=AA.COD_EMPRESA 
    AND AA.TIP_CREDITO='C' 
    AND AA.COD_STATUSCRED=1
    AND AA.tip_campanha = c.TIP_CAMPANHA 
    AND (DATE_FORMAT(AA.DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(AA.LOG_EXPIRA='N'))
    )AS CREDITO_DISPONIVEL_GERAL
   FROM creditosdebitos a
   INNER JOIN clientes b ON b.COD_CLIENTE = a.COD_CLIENTE
   INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND
   WHERE a.cod_cliente=b.cod_cliente 
   AND a.cod_empresa=b.cod_empresa 
   AND a.cod_empresa=$cod_empresa 
   AND a.cod_credlot>0
   AND a.COD_UNIVEND IN($lojasSelecionadas) 
   AND DATE(a.dat_expira) > DATE(NOW())
   GROUP BY a.cod_cliente
   ORDER BY b.NOM_CLIENTE
   LIMIT $inicio, $itens_por_pagina";
}

                // fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

$countLinha = 0;
while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

    ?>  
    <tr>
        <td><a href="action.do?mod=<?php echo fnEncode(1081); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?=fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?=$qrListaVendas['NOM_CLIENTE']; ?></a></td>
        <td><?= $qrListaVendas['DAT_NASCIME']?></td>
        <td><?= fnmasktelefone($qrListaVendas['NUM_CELULAR'])?></td>
        <td><?= $qrListaVendas['NOM_FANTASI'] ?></td>
        <td><?= $qrListaVendas['DATA_VENCIMENTO']?></td>                                            
        <td class ="text-right"><b><?= fnValor($qrListaVendas['val_credito'],2) ?></b></td>
        <td class ="text-right"><b><?= fnValor($qrListaVendas['SALDO_CAMPANHA'],2) ?></b></td>
        <td class ="text-right"><b><?= fnValor($qrListaVendas['CREDITO_DISPONIVEL_GERAL'],2) ?></b></td>
    </tr>
    <?php
    $countLinha++;

}
break;
}
?>