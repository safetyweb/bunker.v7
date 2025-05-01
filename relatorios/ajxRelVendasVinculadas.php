<?php
include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$opcao = "";
$tipo = "";
$dat_ini = "";
$dat_fim = "";
$lojasSelecionadas = "";
$vltotalperceto = 0;
$log_online = "";
$dias30 = "";
$hoje = "";
$temUnivend = "";
$nomeRel = "";
$arquivoCaminho = "";
$sqlcampos = "";
$groupOnline = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$newRow = "";
$objeto = "";
$arrayColumnsNames = [];
$writer = "";


$opcao = @$_GET['opcao'];
$cod_empresa = fnDecode(@$_GET['id']);
$tipo = fnLimpaCampo(@$_GET['tipo']);

$cod_univend = @$_POST['COD_UNIVEND'];
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$lojasSelecionadas = @$_REQUEST['LOJAS'];
$vltotalperceto = fnLimpaCampo(@$_REQUEST['VVR']);
$log_online = fnLimpaCampo(@$_REQUEST['LOG_ONLINE']);



//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

// if (strlen($cod_univend) == 0) {
//     $cod_univend = "9999";
// }

if ($cod_univend == 0) {
    $cod_univend = "9999";
}

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
    $temUnivend = "N";
} else {
    $temUnivend = "S";
}




switch ($opcao) {
    case 'exportar':

        $nomeRel = @$_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sqlcampos = 'NOM_USUARIO,';
        if ($log_online == 'S') {
            $groupOnline = "GROUP BY TMPVENDASRT.COD_VENDEDOR,TMPVENDASRT.COD_UNIVEND";
            if ($tipo == "univend") {
                $groupOnline = "GROUP BY TMPVENDASRT.COD_UNIVEND";
                $sqlcampos = '';
            }

            $sql = "SELECT
                        NOM_FANTASI,
                        $sqlcampos
                        SUM(QTD_TOTAVULSA) QTD_TOTAVULSA, 
                        SUM(QTD_TOTFIDELIZ) QTD_TOTFIDELIZ,
                        truncate((SUM(QTD_TOTFIDELIZ)/ (SUM(QTD_TOTFIDELIZ)+ SUM(QTD_TOTAVULSA)))*100 ,2) AS PCT_FIDELIZADO, 
                        IFNULL(SUM(VAL_TOTVENDA), 0) AS VAL_TOTVENDA,
                        SUM(VAL_TOTFIDELIZ) VAL_TOTFIDELIZ,
                        SUM(VAL_RESGATE) VAL_RESGATE,
                        SUM(CASE WHEN D.TIP_CREDITO = 'D' THEN 1 ELSE 0 END) QTD_RESGATE,
                        truncate(IFNULL(SUM(D.VAL_VINCULADO), 0),2)    AS VAL_VINCULADO,                       
                        (((IFNULL(SUM(VAL_VINCULADO),0)/SUM(VAL_RESGATE))-1)*100) AS VVR,
                        truncate(IFNULL(SUM(VAL_VINCULADO)-SUM(VAL_RESGATE) ,0),2) AS INCREMENTO_VENDA,
                        CAST((CASE WHEN D.TIP_CREDITO = 'C' THEN D.VAL_CREDITO ELSE '0.00' END) AS DECIMAL(15,2)) VAL_CREDITOGERADO
                      --  truncate(SUM(VAL_TOTAVULSO),2) VAL_TOTAVULSO
                        FROM ( 
                            SELECT
                            A.COD_EMPRESA,
                            A.COD_VENDA,
                            B.COD_USUARIO,
                            B.NOM_USUARIO,
                            A.COD_UNIVEND,
                            C.NOM_FANTASI,
                            A.COD_VENDEDOR,
                            CASE WHEN COD_AVULSO = 1 THEN A.QTD_VENDA ELSE '0' END  QTD_TOTAVULSA,
                            CASE WHEN COD_AVULSO = 2 THEN A.QTD_VENDA ELSE '0'  END QTD_TOTFIDELIZ,
                            '0.00' PCT_FIDELIZADO,
                            CASE WHEN COD_AVULSO = 2 THEN A.VAL_TOTVENDA ELSE '0' END VAL_TOTFIDELIZ,
                             CASE WHEN COD_AVULSO = 1 THEN A.VAL_TOTVENDA ELSE '0' END VAL_TOTAVULSO,
                            A.VAL_TOTVENDA,
                            A.VAL_RESGATE,
                           '0.00' VAL_CREDITOGERADO,
                            '' INCREMENTO_VENDA  ,
                           '0.00' QTD_RESGATE
                            FROM VENDAS A
                            LEFT JOIN USUARIOS B ON B.COD_USUARIO = A.COD_VENDEDOR
                            LEFT JOIN UNIDADEVENDA C ON C.COD_UNIVEND = A.COD_UNIVEND
                            WHERE date(A.DAT_CADASTR_WS) BETWEEN CURDATE() AND CURDATE()
                            AND A.COD_EMPRESA = $cod_empresa
                            AND A.COD_UNIVEND IN($lojasSelecionadas)
                            ) TMPVENDASRT            
                             LEFT JOIN CREDITOSDEBITOS D  ON D.COD_EMPRESA = TMPVENDASRT.COD_EMPRESA AND D.COD_VENDA =TMPVENDASRT.COD_VENDA 
                             $groupOnline
                            ORDER  BY NOM_FANTASI";
        } else if ($tipo == "univend" && $log_online == 'S') {

            $sql = "SELECT 
                       NOM_FANTASI,
                        $sqlcampos
                        SUM(QTD_TOTAVULSA) QTD_TOTAVULSA, 
                        SUM(QTD_TOTFIDELIZ) QTD_TOTFIDELIZ,
                        truncate((SUM(QTD_TOTFIDELIZ)/ (SUM(QTD_TOTFIDELIZ)+ SUM(QTD_TOTAVULSA)))*100 ,2) AS PCT_FIDELIZADO, 
                        IFNULL(SUM(VAL_TOTVENDA), 0) AS VAL_TOTVENDA,
                        SUM(VAL_TOTFIDELIZ) VAL_TOTFIDELIZ,
                        SUM(VAL_RESGATE) VAL_RESGATE,
                        SUM(CASE WHEN D.TIP_CREDITO = 'D' THEN 1 ELSE 0 END) QTD_RESGATE,
                        truncate(IFNULL(SUM(D.VAL_VINCULADO), 0),2)    AS VAL_VINCULADO,                       
                        (((IFNULL(SUM(VAL_VINCULADO),0)/SUM(VAL_RESGATE))-1)*100) AS VVR,
                        truncate(IFNULL(SUM(VAL_VINCULADO)-SUM(VAL_RESGATE) ,0),2) AS INCREMENTO_VENDA,
                        CAST((CASE WHEN D.TIP_CREDITO = 'C' THEN D.VAL_CREDITO ELSE '0.00' END) AS DECIMAL(15,2)) VAL_CREDITOGERADO
                       --  truncate(SUM(VAL_TOTAVULSO),2) VAL_TOTAVULSO
                        FROM ( 
                            SELECT
                            B.COD_USUARIO,
                            B.NOM_USUARIO,
                            A.COD_UNIVEND,
                            C.NOM_FANTASI,
                            A.COD_VENDEDOR,
                            D.VAL_VINCULADO,
                            CASE WHEN COD_AVULSO = 1 THEN '1' ELSE '0' END  QTD_TOTAVULSA,
                            CASE WHEN COD_AVULSO = 2 THEN '1' ELSE '0'  END  QTD_TOTFIDELIZ,
                            '0.00' PCT_FIDELIZADO,
                            CASE WHEN COD_AVULSO = 2 THEN A.VAL_TOTVENDA ELSE '0' END VAL_TOTFIDELIZ,
                            A.VAL_TOTVENDA,
                            CASE WHEN D.TIP_CREDITO = 'D' THEN VAL_RESGATE ELSE '0.00' END VAL_RESGATE,
                            CASE WHEN D.TIP_CREDITO = 'C' THEN VAL_CREDITO ELSE '0.00' END VAL_CREDITOGERADO,
                            '' INCREMENTO_VENDA,
                            CASE WHEN D.TIP_CREDITO = 'D' THEN 1 ELSE 0 END QTD_RESGATE
                            FROM VENDAS A
                            LEFT JOIN USUARIOS B ON B.COD_USUARIO = A.COD_VENDEDOR
                            LEFT JOIN UNIDADEVENDA C ON C.COD_UNIVEND = A.COD_UNIVEND
                            LEFT JOIN CREDITOSDEBITOS D  ON D.COD_EMPRESA = A.COD_EMPRESA AND D.COD_VENDA =A.COD_VENDA 
                            WHERE date(A.DAT_CADASTR_WS) BETWEEN CURDATE() AND CURDATE()
                            AND A.COD_EMPRESA =$cod_empresa 
                            AND A.COD_UNIVEND IN($lojasSelecionadas)
                            ) TMPVENDASRT            
                            GROUP  BY COD_UNIVEND
                            ORDER  BY NOM_FANTASI;";
        } else {

            $sqlcampos = 'B.NOM_USUARIO,';
            $groupOnline = "GROUP BY A.COD_VENDEDOR,A.COD_UNIVEND ";
            if ($tipo == "univend") {
                $groupOnline = "GROUP BY A.COD_UNIVEND ";
                $sqlcampos = '';
            }

            $sql = "SELECT  
                           $sqlcampos
                            C.NOM_FANTASI, 
                            SUM(A.QTD_TOTAVULSA) AS QTD_TOTAVULSA,
                            SUM(A.QTD_TOTFIDELIZ) AS QTD_TOTFIDELIZ,
                            ROUND(((SUM(A.QTD_TOTFIDELIZ)/SUM(A.QTD_TOTVENDA))*100),2) AS PCT_FIDELIZADO, 
                            (IFNULL(SUM(A.VAL_TOTVENDA),0)) AS VAL_TOTVENDA,
                            (IFNULL(SUM(A.VAL_TOTFIDELIZ),0)) AS VAL_TOTFIDELIZ,                           
                            SUM(D.VAL_RESGATE) AS VAL_RESGATE,                                                                                                                                                                        
                            IFNULL(SUM(D.QTD_RESGATE),0)  QTD_RESGATE  ,
                            IFNULL(SUM(D.VAL_VINCULADO1),0) AS VAL_VINCULADO,
                            (((IFNULL(SUM(D.VAL_VINCULADO),0)/SUM(D.VAL_RESGATE))-1)*100) AS VVR,
                            IFNULL(SUM(D.VAL_VINCULADO1),0)-SUM(D.VAL_RESGATE) AS INCREMENTO_VENDA,
                            SUM(D.VAL_CREDITO_GERADO) AS VAL_CREDITOGERADO                           
                            FROM VENDAS_DIARIAS A 
                            LEFT JOIN USUARIOS B ON B.COD_USUARIO = A.COD_VENDEDOR 
                            LEFT JOIN UNIDADEVENDA C ON C.COD_UNIVEND = A.COD_UNIVEND 
                            LEFT JOIN CREDITOSDEBITOS_DIARIAS D ON D.COD_EMPRESA=A.COD_EMPRESA 
                            AND D.COD_UNIVEND=A.COD_UNIVEND 
                            AND D.COD_VENDEDOR=A.COD_VENDEDOR 
                            AND D.DAT_MOVIMENTO=A.DAT_MOVIMENTO
                            WHERE DATE_FORMAT(A.DAT_MOVIMENTO, '%Y-%m-%d') >= '$dat_ini' 
                            AND DATE_FORMAT(A.DAT_MOVIMENTO, '%Y-%m-%d') <= '$dat_fim'  
                            AND A.COD_EMPRESA = $cod_empresa
                            AND A.COD_UNIVEND IN($lojasSelecionadas) 
                           $groupOnline 
                            ORDER BY C.NOM_FANTASI ";
        }

        $arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

        // fnEscreve($log_online);
        // fnEscreve($sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            // echo "<pre>";
            // print_r($row);
            // echo "</pre>";
            $row['VAL_TOTVENDA'] = fnValor($row['VAL_TOTVENDA'], 2);
            $row['VAL_RESGATE'] = fnValor($row['VAL_RESGATE'], 2);
            $row['PCT_FIDELIZADO'] = fnValor($row['PCT_FIDELIZADO'], 2) . '%';
            $row['VAL_VINCULADO'] = fnValor($row['VAL_VINCULADO'], 2);
            $row['VAL_TOTFIDELIZ'] = fnValor($row['VAL_TOTFIDELIZ'], 2);
            $row['QTD_TOTFIDELIZ'] = fnValor($row['QTD_TOTFIDELIZ'], 2);
            $row['VAL_CREDITOGERADO'] = fnValor($row['VAL_CREDITOGERADO'], 2);
            $row['INCREMENTO_VENDA'] = fnValor($row['INCREMENTO_VENDA'], 2);

            $row['VVR'] = fnValor($row['VVR'], 2) . '%';

            // fnEscreve($row['VAL_VINCULADO']);
            // fnEscreve($row['VAL_RESGATE']);
            // fnEscreve("VVR: ".$row['VVR']."_____");

            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            //$textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);


        /* $array = array();
		
        while ($row = mysqli_fetch_assoc($arrayQuery)) {
            $newRow = array();
			$vltotalperceto=fnValor(((($row['VAL_VINCULADO']/$row['VAL_RESGATE'])-1)*100),2);
                        if($vltotalperceto <'0,00')
                        {$row['VVR']='0,00%';}else{$row['VVR']=$vltotalperceto.'%';}  
			

            $cont = 0;
            foreach ($row as $objeto) {

                // Colunas que são double converte com fnValor
                if ($cont == 5 || ($cont >= 7 && $cont <= 12) ) {
                    array_push($newRow, fnValor($objeto, 2));
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
		
		array_push($arrayColumnsNames, 'VVR');

        $writer->addRow($arrayColumnsNames);
        $writer->addRows($array);

        $writer->close();
        */

        break;
    case 'paginar':

        break;
}
