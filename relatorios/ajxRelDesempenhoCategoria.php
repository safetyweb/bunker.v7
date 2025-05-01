<?php

include '../_system/_functionsMain.php';

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);

$cod_univend = $_POST['COD_UNIVEND'];
$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$cod_categor = fnLimpaCampo($_POST['COD_CATEGOR']);
$cod_subcate = fnLimpaCampoZero($_POST['COD_SUBCATE']);
$log_select = fnLimpaCampo($_POST['LOG_SELECT']);

switch ($opcao) {
    case 'exportar':

    $nomeRel = $_GET['nomeRel'];
    $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

    if($log_select == ""){

        if($cod_subcate != ""){
            $andSubcate = "AND B.cod_subcate = $cod_subcate";
        }else{
            $andSubcate = " ";
        }

        $sql = "SELECT c.COD_CATEGOR,
        c.DES_CATEGOR,
        e.NOM_FANTASI,
        COUNT(distinct a.cod_venda) qtd_venda, 
        COUNT(DISTINCT a.COD_CLIENTE) qtd_clientes,
        SUM(a.qtd_produto) AS qtd_produto,
        SUM(a.val_totitem) AS valor_produto,
        SUM(a.val_resgate) AS valor_resgate,
        SUM(case when f.TIP_CREDITO='C' then
            f.val_credito
            else
                0 
            end) AS val_credito
        FROM itemvenda a
        INNER JOIN produtocliente b ON a.cod_produto=b.cod_produto AND a.cod_empresa=b.cod_empresa 
        INNER JOIN categoria c ON b.COD_CATEGOR=c.cod_categor AND b.cod_empresa=c.cod_empresa 
        INNER JOIN vendas d ON a.cod_venda=d.cod_venda AND d.COD_STATUSCRED IN(1,2,3,4,5,7,8,9) 
        INNER JOIN unidadevenda e on d.cod_univend=e.cod_univend  
        LEFT JOIN creditosdebitos f ON a.cod_itemven=f.cod_itemven AND d.cod_univend=f.cod_univend AND f.TIP_CREDITO='C'
        WHERE 
        a.cod_empresa=$cod_empresa AND 
        date(a.dat_cadastr) >='$dat_ini' AND 
        date(a.dat_cadastr) <='$dat_fim' AND 
        B.cod_categor IN ($cod_categor)
        $andSubcate";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {
            $row['DES_CATEGOR'] = $row['DES_CATEGOR'];
            $row['qtd_venda'] = fnValor($row['qtd_venda'], 0);
            $row['qtd_clientes'] = fnValor($row['qtd_clientes'], 0);
            $row['qtd_produto'] = fnValor($row['qtd_produto'], 0);
            $row['valor_produto'] = fnValor($row['valor_produto'], 2);
            $row['val_credito'] = fnValor($row['val_credito'], 2);
            $row['valor_resgate'] = fnValor($row['valor_resgate'], 2);

            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"', '\n');
        }
        fclose($arquivo);
    }else{

        $sql = "SELECT 
        UNI.NOM_FANTASI,
        COUNT(DISTINCT CRED.COD_VENDA) QTD_VENDA,
        COUNT(DISTINCT CRED.COD_CLIENTE) QTD_CLIENTE,
        CRED.COD_ITEMVEN,
        SUM(ITM.QTD_PRODUTO) QTD_PRODUTO,
        SUM(ITM.VAL_TOTITEM) VAL_TOTITEM,
        SUM(CRED.VAL_CREDITO) VAL_CREDITO
        FROM creditosdebitos CRED
        INNER JOIN ITEMVENDA ITM ON ITM.COD_ITEMVEN=CRED.COD_ITEMVEN
        INNER JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND=CRED.COD_UNIVEND
        INNER JOIN produtocliente B ON B.COD_PRODUTO=ITM.COD_PRODUTO
        WHERE 
        CRED.cod_empresa=$cod_empresa and CRED.TIP_CREDITO='C' AND
        date(CRED.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' AND
        CRED.COD_STATUSCRED IN (0,1,2,3,4,5,7,8) AND
        CRED.COD_ITEMVEN >0
        group by UNI.COD_UNIVEND ORDER BY UNI.NOM_FANTASI ASC";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {
            $row['NOM_FANTASI'] = $row['NOM_FANTASI'];
            $row['QTD_VENDA'] = fnValor($row['QTD_VENDA'], 2);
            $row['QTD_CLIENTE'] = fnValor($row['QTD_CLIENTE'], 0);
            $row['COD_ITEMVEN'] = fnValor($row['COD_ITEMVEN'], 0);
            $row['QTD_PRODUTO'] = fnValor($row['QTD_PRODUTO'], 0);
            $row['VAL_TOTITEM'] = fnValor($row['VAL_TOTITEM'], 2);
            $row['VAL_CREDITO'] = fnValor($row['VAL_CREDITO'], 2);

            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"', '\n');
        }
        fclose($arquivo);
    }

    break;

    case 'paginar':

    if($log_select == ""){

        $sql = "SELECT c.COD_CATEGOR,
        c.DES_CATEGOR,
        e.NOM_FANTASI,
        COUNT(distinct a.cod_venda) qtd_venda, 
        COUNT(DISTINCT a.COD_CLIENTE) qtd_clientes,
        SUM(a.qtd_produto) AS qtd_produto,
        SUM(a.val_totitem) AS valor_produto,
        SUM(a.val_resgate) AS valor_resgate,
        SUM(case when f.TIP_CREDITO='C' then
            f.val_credito
            else
                0 
            end) AS val_credito
        FROM itemvenda a
        INNER JOIN produtocliente b ON a.cod_produto=b.cod_produto AND a.cod_empresa=b.cod_empresa 
        INNER JOIN categoria c ON b.COD_CATEGOR=c.cod_categor AND b.cod_empresa=c.cod_empresa 
        INNER JOIN vendas d ON a.cod_venda=d.cod_venda AND d.COD_STATUSCRED IN(1,2,3,4,5,7,8,9) 
        INNER JOIN unidadevenda e on d.cod_univend=e.cod_univend  
        LEFT JOIN creditosdebitos f ON a.cod_itemven=f.cod_itemven AND d.cod_univend=f.cod_univend AND f.TIP_CREDITO='C'
        WHERE 
        a.cod_empresa=$cod_empresa AND 
        date(a.dat_cadastr) >='$dat_ini' AND 
        date(a.dat_cadastr) <='$dat_fim' AND 
        B.cod_categor IN ($cod_categor)
        $andSubcate";

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);
        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        $sql = "SELECT c.COD_CATEGOR,
        c.DES_CATEGOR,
        e.NOM_FANTASI,
        COUNT(distinct a.cod_venda) qtd_venda, 
        COUNT(DISTINCT a.COD_CLIENTE) qtd_clientes,
        SUM(a.qtd_produto) AS qtd_produto,
        SUM(a.val_totitem) AS valor_produto,
        SUM(a.val_resgate) AS valor_resgate,
        SUM(case when f.TIP_CREDITO='C' then
            f.val_credito
            else
                0 
            end) AS val_credito
        FROM itemvenda a
        INNER JOIN produtocliente b ON a.cod_produto=b.cod_produto AND a.cod_empresa=b.cod_empresa 
        INNER JOIN categoria c ON b.COD_CATEGOR=c.cod_categor AND b.cod_empresa=c.cod_empresa 
        INNER JOIN vendas d ON a.cod_venda=d.cod_venda AND d.COD_STATUSCRED IN(1,2,3,4,5,7,8,9) 
        INNER JOIN unidadevenda e on d.cod_univend=e.cod_univend  
        LEFT JOIN creditosdebitos f ON a.cod_itemven=f.cod_itemven AND d.cod_univend=f.cod_univend AND f.TIP_CREDITO='C'
        WHERE 
        a.cod_empresa=$cod_empresa AND 
        date(a.dat_cadastr) >='$dat_ini' AND 
        date(a.dat_cadastr) <='$dat_fim' AND 
        B.cod_categor IN ($cod_categor)
        $andSubcate
        LIMIT $inicio, $itens_por_pagina";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $countLinha = 1;
        while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
            ?>
            <tr>
                <td><small><?php echo $qrListaVendas['DES_CATEGOR']; ?></small></td>
                <td class="text-center"><small><?php echo $qrListaVendas['qtd_venda']; ?></small></td>
                <td class="text-center"><b><small><?php echo $qrListaVendas['qtd_clientes']; ?></small></b></td>
                <td class="text-center"><small><?php echo fnValor($qrListaVendas['qtd_produto'],0); ?></small></td>
                <td class="text-center"><b><small><?php echo fnValor($qrListaVendas['valor_produto'],2); ?></small></b></td>
                <td class="text-center"><b><small><?php echo fnValor($qrListaVendas['valor_resgate'],2); ?></small></b></td>
            </tr>
            <?php

            $countLinha++;
        }
    }else{

        $sql = "
        SELECT 
        UNI.NOM_FANTASI,
        COUNT(DISTINCT CRED.COD_VENDA) QTD_VENDA,
        COUNT(DISTINCT CRED.COD_CLIENTE) QTD_CLIENTE,
        CRED.COD_ITEMVEN,
        SUM(ITM.QTD_PRODUTO) QTD_PRODUTO,
        SUM(ITM.VAL_TOTITEM) VAL_TOTITEM,
        SUM(CRED.VAL_CREDITO) VAL_CREDITO
        FROM creditosdebitos CRED
        INNER JOIN ITEMVENDA ITM ON ITM.COD_ITEMVEN=CRED.COD_ITEMVEN
        INNER JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND=CRED.COD_UNIVEND
        INNER JOIN produtocliente B ON B.COD_PRODUTO=ITM.COD_PRODUTO
        WHERE 
        CRED.cod_empresa=$cod_empresa and CRED.TIP_CREDITO='C' AND
        date(CRED.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' AND
        CRED.COD_STATUSCRED IN (0,1,2,3,4,5,7,8) AND
        CRED.COD_ITEMVEN >0
        group by UNI.COD_UNIVEND ORDER BY UNI.NOM_FANTASI ASC
        ";

                                            //fnEscreve($sql);
        $retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);
        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);
                                                            //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


        $sql = "SELECT 
        UNI.NOM_FANTASI,
        COUNT(DISTINCT CRED.COD_VENDA) QTD_VENDA,
        COUNT(DISTINCT CRED.COD_CLIENTE) QTD_CLIENTE,
        CRED.COD_ITEMVEN,
        SUM(ITM.QTD_PRODUTO) QTD_PRODUTO,
        SUM(ITM.VAL_TOTITEM) VAL_TOTITEM,
        SUM(CRED.VAL_CREDITO) VAL_CREDITO
        FROM creditosdebitos CRED
        INNER JOIN ITEMVENDA ITM ON ITM.COD_ITEMVEN=CRED.COD_ITEMVEN
        INNER JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND=CRED.COD_UNIVEND
        INNER JOIN produtocliente B ON B.COD_PRODUTO=ITM.COD_PRODUTO
        WHERE 
        CRED.cod_empresa=$cod_empresa and CRED.TIP_CREDITO='C' AND
        date(CRED.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' AND
        CRED.COD_STATUSCRED IN (0,1,2,3,4,5,7,8) AND
        CRED.COD_ITEMVEN >0
        group by UNI.COD_UNIVEND ORDER BY UNI.NOM_FANTASI ASC
        limit $inicio,$itens_por_pagina";

                                            //fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

        ?>

            <?php

            $countLinha = 1;
            while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
            {

                ?>  
                <tr>
                    <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
                    <td class="text-center"><small><?php echo $qrListaVendas['QTD_VENDA']; ?></small></td>
                    <td class="text-center"><b><small><?php echo $qrListaVendas['QTD_CLIENTE']; ?></small></b></td>
                    <td class="text-center"><small><?php echo $qrListaVendas['COD_ITEMVEN']; ?></small></td>
                    <td class="text-center"><b><small><?php echo fnValor($qrListaVendas['QTD_PRODUTO'],0); ?></small></b></td>
                    <td class="text-center"><b><small><?php echo fnValor($qrListaVendas['VAL_TOTITEM'],2); ?></small></b></td>
                    <td class="text-center"><b><small><?php echo fnValor($qrListaVendas['VAL_CREDITO'],2); ?></small></b></td>
                </tr>
                <?php

                $countLinha++;  
            }

        }

        break;
    }
    ?>
