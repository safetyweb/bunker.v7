<?php

include '../_system/_functionsMain.php';


$hoje = fnFormatDate(date("Y-m-d"));

// echo fnDebug('true');

$opcao = $_GET['opcao'];
$tipo = $_GET['tipo'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);
$casasDec = $_REQUEST['CASAS_DEC'];
$dat_ini = fnDataSql($_POST['DAT_INI']);
$tip_calculo = fnLimpaCampo($_POST['TIP_CALCULO']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$val_ini = fnValorSql(fnLimpaCampo($_POST['VAL_INI']));
$val_fim = fnValorSql(fnLimpaCampo($_POST['VAL_FIM']));

fnEscreve($tip_calculo);
$autoriza = fnLimpaCampoZero($_POST['AUTORIZA']);
// fnEscreve($dat_fim);
//inicialização das variáveis - default	
if (strlen(trim($dat_ini)) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen(trim($dat_fim)) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

/*
    	if($val_fim == fnValorSql(0)){
    		$val_fim = fnValorSql(9999999999);
    	}
    	*/
$campo = "CREDITO_EXPIRAR";

if ($tip_calculo == 1) {
    $andTipCalculo = "AND val_saldo >= '0.01'";
} else {
    $andTipCalculo = "AND EXISTS (SELECT 1
            FROM CREDITOSDEBITOS D
            WHERE D.COD_CLIENTE = A.COD_CLIENTE
            AND D.COD_STATUSCRED = 1
            HAVING SUM(VAL_SALDO) >= $val_ini)";
}

if ($tip_calculo == 3) {
    $campo = "VAL_TOTAL_SALDO";
}

if ($val_ini > 0 && $val_fim > 0) {
    $filtroSaldo = "$campo BETWEEN $val_ini AND $val_fim ";
} else {
    $filtroSaldo = "$campo BETWEEN 0.01 AND 9999999999	";
}


// fnEscreve($dat_fim);
$lojasSelecionadas = $_POST['LOJAS'];

/*$ARRAY_UNIDADE1=array(
    				   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
    				   'cod_empresa'=>$cod_empresa,
    				   'conntadm'=>$connAdm->connAdm(),
    				   'IN'=>'N',
    				   'nomecampo'=>'',
    				   'conntemp'=>'',
    				   'SQLIN'=> ""   
    				   );
    				   
    	$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);
             * 
             */

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

switch ($opcao) {
    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT 
            tmpcreditos.COD_CLIENTE,
            NOM_CLIENTE, 
            NUM_CARTAO,
            DAT_NASCIME, 
            DES_EMAILUS, 
            NUM_CELULAR, 
            tmpcreditos.COD_UNIVEND, 
            NOM_FANTASI,
            tmpcreditos.COD_VENDA, 
            tmpcreditos.DAT_CADASTR, 
            IFNULL(SUM(C.VAL_TOTPRODU), 0) AS VAL_COMPRA,
            round(SUM(CREDITO_GERADO),2) CREDITO_GERADO,
            round(SUM(CREDITO_EXPIRAR),2) CREDITO_EXPIRAR, 
            DAT_EXPIRA, 
            VENDEDOR_ULTIMA_VENDA_PERIODO,
            tmpcreditos.DAT_ULTCOMPR,
            SUM(VAL_TOTAL_SALDO) VAL_TOTAL_SALDO
            FROM (
                SELECT  A.COD_CLIENTE, 
                B.NOM_CLIENTE,
                B.DAT_NASCIME, 
                B.NUM_CARTAO, 
                B.DES_EMAILUS, 
                B.NUM_CELULAR, 
                A.COD_UNIVEND,
                uni.NOM_FANTASI,
                A.COD_VENDA, 
                B.DAT_CADASTR, 
                '' VAL_COMPRA, 
                SUM(VAL_CREDITO) AS CREDITO_GERADO, 
                SUM(VAL_SALDO) AS CREDITO_EXPIRAR, 
                A.DAT_EXPIRA,
                (SELECT NOM_USUARIO FROM USUARIOS WHERE
                    COD_USUARIO = (SELECT COD_VENDEDOR FROM VENDAS WHERE
                        COD_VENDA = (SELECT MAX(COD_VENDA) FROM VENDAS 
                            WHERE COD_CLIENTE = A.COD_CLIENTE))) VENDEDOR_ULTIMA_VENDA_PERIODO,
                DATE_FORMAT(B.DAT_ULTCOMPR, '%d-%m-%Y') AS DAT_ULTCOMPR, (
                    SELECT SUM(VAL_SALDO)
                    FROM CREDITOSDEBITOS D
                    WHERE D.COD_CLIENTE = A.COD_CLIENTE AND D.COD_STATUSCRED = 1) VAL_TOTAL_SALDO

                FROM CREDITOSDEBITOS A
                INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE
                LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
                WHERE A.COD_EMPRESA = $cod_empresa AND
                A.TIP_CREDITO='C' AND  
                A.COD_STATUSCRED=1 AND
                A.DAT_EXPIRA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
                $andTipCalculo
                AND A.COD_UNIVEND IN($lojasSelecionadas)
                GROUP BY A.COD_CLIENTE
                ORDER BY DAT_EXPIRA
                )tmpcreditos
            LEFT JOIN VENDAS C ON tmpcreditos.COD_VENDA = C.COD_VENDA
            WHERE $filtroSaldo
            GROUP BY tmpcreditos.COD_CLIENTE ";

        fnEscreve($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {
            $row['CREDITO_GERADO'] = fnValor($row['CREDITO_GERADO'], 2);
            // $row[DAT_NASCIME] = fnDataShort($row[DAT_NASCIME]);
            $row['CREDITO_EXPIRAR'] = fnValor($row['CREDITO_EXPIRAR'], 2);
            $row['SALDO_TOTAL_DISP'] = fnValor($row['SALDO_TOTAL_DISP'], 2);
            $row['VAL_COMPRA'] = fnValor($row['VAL_COMPRA'], 2);
            $row['VAL_TOTAL_SALDO'] = fnValor($row['VAL_TOTAL_SALDO'], 2);
            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            //$textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"', '\n');

            //echo "<pre>";
            // print_r($row);
            //echo "</pre>";
        }
        fclose($arquivo);
        /*
            $array = array();
            while ($row = mysqli_fetch_assoc($arrayQuery)) {

                //$NOM_ARRAY_UNIDADE=(array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

                $newRow = array();

                $cont = 0;
                foreach ($row as $objeto) {

                    // Colunas que são double converte com fnValor
                    if ($cont == 9) {

                        array_push($newRow, fnValor($objeto, 2));
                    } else if (($cont > 9 && $cont <= 11) || $cont == 14) {

                        array_push($newRow, fnvalor($objeto, $casasDec));
                    } else {

                        array_push($newRow, $objeto);
                    }

                    $cont++;
                }

                $array[] = $newRow;
            }

            $arrayColumnsNames = array();
            while ($row = mysqli_fetch_field($arrayQuery)) {
                array_push($arrayColumnsNames, $row->name);
            }

            $writer->addRow($arrayColumnsNames);
            $writer->addRows($array);

            $writer->close();

            //echo $sql;*/

        break;

    case 'paginar':

        $sql = "SELECT 
            tmpcreditos.COD_CLIENTE,
            NOM_CLIENTE, 
            NUM_CARTAO, 
            DES_EMAILUS, 
            NUM_CELULAR, 
            tmpcreditos.COD_UNIVEND, 
            NOM_FANTASI,
            tmpcreditos.COD_VENDA, 
            tmpcreditos.DAT_CADASTR, 
            IFNULL(SUM(C.VAL_TOTPRODU), 0) AS VAL_COMPRA,
            round(SUM(CREDITO_GERADO),2) CREDITO_GERADO,
            round(SUM(CREDITO_EXPIRAR),2) CREDITO_EXPIRAR, 
            DAT_EXPIRA, 
            VENDEDOR_ULTIMA_VENDA_PERIODO,
            tmpcreditos.DAT_ULTCOMPR,
            SUM(VAL_TOTAL_SALDO) VAL_TOTAL_SALDO
            FROM (
                SELECT  A.COD_CLIENTE, 
                B.NOM_CLIENTE, 
                B.NUM_CARTAO, 
                B.DES_EMAILUS, 
                B.NUM_CELULAR, 
                A.COD_UNIVEND,
                uni.NOM_FANTASI,
                A.COD_VENDA, 
                B.DAT_CADASTR, 
                '' VAL_COMPRA, 
                SUM(VAL_CREDITO) AS CREDITO_GERADO, 
                SUM(VAL_SALDO) AS CREDITO_EXPIRAR, 
                A.DAT_EXPIRA,
                (SELECT NOM_USUARIO FROM USUARIOS WHERE
                    COD_USUARIO = (SELECT COD_VENDEDOR FROM VENDAS WHERE
                        COD_VENDA = (SELECT MAX(COD_VENDA) FROM VENDAS 
                            WHERE COD_CLIENTE = A.COD_CLIENTE))) VENDEDOR_ULTIMA_VENDA_PERIODO,
                DATE_FORMAT(B.DAT_ULTCOMPR, '%d-%m-%Y') AS DAT_ULTCOMPR, (
                    SELECT SUM(VAL_SALDO)
                    FROM CREDITOSDEBITOS D
                    WHERE D.COD_CLIENTE = A.COD_CLIENTE AND D.COD_STATUSCRED = 1) VAL_TOTAL_SALDO

                FROM CREDITOSDEBITOS A
                INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE
                LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
                WHERE A.COD_EMPRESA = $cod_empresa AND
                A.TIP_CREDITO='C' AND  
                A.COD_STATUSCRED=1 AND
                A.DAT_EXPIRA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
                $andTipCalculo
                AND A.COD_UNIVEND IN($lojasSelecionadas)
                GROUP BY A.COD_CLIENTE
                ORDER BY DAT_EXPIRA
                )tmpcreditos
            LEFT JOIN VENDAS C ON tmpcreditos.COD_VENDA = C.COD_VENDA
            WHERE $filtroSaldo
            GROUP BY tmpcreditos.COD_CLIENTE
            ";
        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $total_itens_por_pagina = mysqli_num_rows($retorno);

        $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


        $sql = "SELECT 
            tmpcreditos.COD_CLIENTE,
            NOM_CLIENTE, 
            NUM_CARTAO, 
            DES_EMAILUS, 
            NUM_CELULAR, 
            tmpcreditos.COD_UNIVEND, 
            NOM_FANTASI,
            tmpcreditos.COD_VENDA, 
            tmpcreditos.DAT_CADASTR, 
            IFNULL(SUM(C.VAL_TOTPRODU), 0) AS VAL_COMPRA,
            round(SUM(CREDITO_GERADO),2) CREDITO_GERADO,
            round(SUM(CREDITO_EXPIRAR),2) CREDITO_EXPIRAR, 
            DAT_EXPIRA, 
            VENDEDOR_ULTIMA_VENDA_PERIODO,
            tmpcreditos.DAT_ULTCOMPR,
            SUM(VAL_TOTAL_SALDO) VAL_TOTAL_SALDO
            FROM (
                SELECT  A.COD_CLIENTE, 
                B.NOM_CLIENTE, 
                B.NUM_CARTAO, 
                B.DES_EMAILUS, 
                B.NUM_CELULAR, 
                A.COD_UNIVEND,
                uni.NOM_FANTASI,
                A.COD_VENDA, 
                B.DAT_CADASTR, 
                '' VAL_COMPRA, 
                SUM(VAL_CREDITO) AS CREDITO_GERADO, 
                SUM(VAL_SALDO) AS CREDITO_EXPIRAR, 
                A.DAT_EXPIRA,
                (SELECT NOM_USUARIO FROM USUARIOS WHERE
                 COD_USUARIO = (SELECT COD_VENDEDOR FROM VENDAS WHERE
                    COD_VENDA = (SELECT MAX(COD_VENDA) FROM VENDAS 
                     WHERE COD_CLIENTE = A.COD_CLIENTE))) VENDEDOR_ULTIMA_VENDA_PERIODO,
                DATE_FORMAT(B.DAT_ULTCOMPR, '%d-%m-%Y') AS DAT_ULTCOMPR, (
                 SELECT SUM(VAL_SALDO)
                 FROM CREDITOSDEBITOS D
                 WHERE D.COD_CLIENTE = A.COD_CLIENTE AND D.COD_STATUSCRED = 1) VAL_TOTAL_SALDO

                FROM CREDITOSDEBITOS A
                INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE
                LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
                WHERE A.COD_EMPRESA = $cod_empresa AND
                A.TIP_CREDITO='C' AND  
                A.COD_STATUSCRED=1 AND
                A.DAT_EXPIRA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' $andTipCalculo AND 
                A.COD_UNIVEND IN($lojasSelecionadas)
                GROUP BY A.COD_CLIENTE
                ORDER BY DAT_EXPIRA
                )tmpcreditos
            LEFT JOIN VENDAS C ON tmpcreditos.COD_VENDA = C.COD_VENDA
            WHERE $filtroSaldo
            GROUP BY tmpcreditos.COD_CLIENTE
            LIMIT $inicio, $itens_por_pagina
            ";

        // fnEscreve($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
        //fnEscreve(mysqli_num_rows($arrayQuery));

        if (mysqli_num_rows($arrayQuery) != 0) {

            // fnEscreve("if");

            $countLinha = 1;
            while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

                if ($qrListaVendas['DES_EMAILUS'] == "") {
                    $email = "e-mail não cadastrado!";
                } else {
                    $email = fnmascaracampo($qrListaVendas['DES_EMAILUS']);
                }
?>
                <tr>
                    <?php
                    if ($autoriza == 1) {
                    ?>
                        <td><a href="action.do?mod=<?php echo fnEncode(1081); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
                    <?php
                    } else {
                    ?>
                        <td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
                    <?php
                    }
                    ?>
                    <td><small><?php echo $qrListaVendas['NUM_CARTAO']; ?></small></td>
                    <td><small><?php echo $email; ?></small></td>
                    <td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CELULAR']); ?></small></td>
                    <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
                    <td class="text-center"><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
                    <td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['VAL_COMPRA'], 2); ?></small></td>
                    <td class="text-center"><small><?php echo fnValor($qrListaVendas['CREDITO_GERADO'], $casasDec); ?></small></td>
                    <td class="text-center"><small><?php echo fnValor($qrListaVendas['CREDITO_EXPIRAR'], $casasDec); ?></small></td>
                    <td class="text-center"><small><?php echo fnDataShort($qrListaVendas['DAT_EXPIRA']); ?></small></td>
                    <td class="text-center"><small><?php echo fnDataShort($qrListaVendas['DAT_ULTCOMPR']); ?></small></td>
                    <td class="text-center"><small><?php echo fnValor($qrListaVendas['VAL_TOTAL_SALDO'], $casasDec); ?></small></td>
                </tr>
<?php

                $countLinha++;
            }
        }

        break;
}
?>