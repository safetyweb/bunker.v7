<?php

include '../_system/_functionsMain.php'; 
$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];  
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);
$lojasSelecionadas = $_POST['LOJAS'];
$modulo = $_POST['mod'];
$creditoSelecionados = $_GET['credlot'];
$cod_grupotr = $_REQUEST['COD_GRUPOTR'];    
$cod_tiporeg = $_REQUEST['COD_TIPOREG'];

$andCredlot = "AND a.COD_CREDLOT IN ($creditoSelecionados)";

switch($opcao) {

    case 'exportar':

    $nomeRel = $_GET['nomeRel'];
    $arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';

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
        ORDER BY b.NOM_CLIENTE";
    }else{


     $sql = "SELECT b.COD_CLIENTE, 
     b.NOM_CLIENTE, 
     a.COD_UNIVEND, 
     uni.NOM_FANTASI, 
     b.NUM_CELULAR,
     b.DAT_NASCIME, 
     DATE_FORMAT (a.dat_expira, '%d/%m/%Y') DATA_VENCIMENTO, 
     SUM(a.val_credito) AS val_credito, 
     SUM(a.val_saldo)SALDO_CAMPANHA,
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
     INNER JOIN creditos_lot d ON d.cod_credlot = a.cod_credlot AND ((d.cod_empresa=219 AND d.cod_personas IN(104,105,106)) OR (d.cod_empresa=306 AND d.cod_personas IN(282)))
     WHERE a.cod_cliente=b.cod_cliente 
     AND a.cod_empresa=b.cod_empresa 
     AND a.cod_empresa=$cod_empresa 
     AND a.cod_credlot>0
     AND a.COD_UNIVEND IN($lojasSelecionadas) 
     AND DATE(a.dat_expira) > DATE(NOW())
     GROUP BY a.cod_cliente
     ORDER BY b.NOM_CLIENTE";
 }
        fnEscreve($sql);
 $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);			

 $arquivo = fopen($arquivoCaminho, 'w',0);

 while($headers=mysqli_fetch_field($arrayQuery)){
    $CABECHALHO[]=$headers->name;
}
fputcsv ($arquivo,$CABECHALHO,';','"','\n');

while ($row=mysqli_fetch_assoc($arrayQuery)){  	

    $row[val_credito] = fnValor($row['val_credito'],2);
    $row[SALDO_CAMPANHA] = fnValor($row['SALDO_CAMPANHA'],2);
    $row[CREDITO_DISPONIVEL_GERAL] = fnValor($row['CREDITO_DISPONIVEL_GERAL'],2);
            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
           // $textolimpo = json_decode($limpandostring, true);
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
    INNER JOIN creditos_lot d ON d.cod_credlot = a.cod_credlot AND ((d.cod_empresa=219 AND d.cod_personas IN(104,105,106)) OR (d.cod_empresa=306 AND d.cod_personas IN(282)))
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
   SUM(a.val_credito) AS val_credito, 
   SUM(a.val_saldo)SALDO_CAMPANHA,
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
   INNER JOIN creditos_lot d ON d.cod_credlot = a.cod_credlot AND ((d.cod_empresa=219 AND d.cod_personas IN(104,105,106)) OR (d.cod_empresa=306 AND d.cod_personas IN(282)))
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