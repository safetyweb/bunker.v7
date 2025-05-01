<?php

include '../_system/_functionsMain.php';

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];	
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);

$conn = connTemp($cod_empresa,'');
switch($opcao){
    
    case 'exportar':
            
        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';


        // Filtro por Grupo de Lojas
        include "filtroGrupoLojas.php";

        $sql = "SELECT 	PRODUTOCLIENTE.DES_PRODUTO,
                        PRODUTOCLIENTE.COD_EXTERNO,
                        PRODUTOCLIENTE.COD_PRODUTO AS PRODUTO,
                        DESCONTOTKT.ABV_DESCTKT,
                        IF(PRODUTOCLIENTE.DES_IMAGEM <> '','S','N') AS TEM_IMAGEM,
                        produtotkt.COD_PRODUTO, produtotkt.DAT_INIPTKT,
                        produtotkt.DAT_FIMPTKT, produtotkt.PCT_DESCTKT,
                        produtotkt.VAL_PRODTKT, produtotkt.VAL_PROMTKT, 
                        GROUP_CONCAT(DISTINCT CASE WHEN produtotkt.COD_UNIVEND_AUT = 0 THEN 'TODAS AS UNIDADES AUTORIZADAS' ELSE UNI.NOM_FANTASI END SEPARATOR ',') COD_UNIVEND_AUT,
                        GROUP_CONCAT(DISTINCT CASE WHEN produtotkt.COD_UNIVEND_BLK = 0 THEN 'TODAS AS UNIDADES AUTORIZADAS' ELSE UNI.NOM_FANTASI END SEPARATOR ',') COD_UNIVEND_BLK,
                        produtotkt.LOG_PRODTKT,
                        produtotkt.COD_DESCTKT,
                        categoriatkt.COD_CATEGORTKT,
                        categoriatkt.DES_ABREVIA, categoriatkt.COD_CATEGORTKT,
                        categoriatkt.DES_CATEGOR, produtotkt.COD_PERSONA_TKT,
                        produtotkt.LOG_ATIVOTK, GROUP_CONCAT(DISTINCT P.DES_PERSONA SEPARATOR ',') DES_PERSONA
                FROM PRODUTOTKT
                LEFT JOIN categoriatkt ON categoriatkt.COD_CATEGORTKT = PRODUTOTKT.COD_CATEGORTKT
                LEFT JOIN DESCONTOTKT ON DESCONTOTKT.COD_DESCTKT = PRODUTOTKT.COD_DESCTKT
                INNER JOIN PRODUTOCLIENTE ON PRODUTOCLIENTE.COD_PRODUTO = PRODUTOTKT.COD_PRODUTO
                INNER JOIN persona P ON P.COD_PERSONA = produtotkt.COD_PERSONA_TKT
                LEFT JOIN unidadevenda UNI ON  find_in_set(UNI.COD_UNIVEND,produtotkt.COD_UNIVEND_AUT)
                WHERE PRODUTOTKT.COD_PRODUTO = PRODUTOCLIENTE.COD_PRODUTO AND PRODUTOTKT.COD_EMPRESA = 85 AND produtotkt.LOG_ATIVOTK = 'S'
                GROUP BY PRODUTOCLIENTE.COD_PRODUTO
                ORDER BY DES_CATEGOR, NOM_PRODTKT
        ";
                
        //fnEscreve($sql);
                
        $arrayQuery = mysqli_query($conn,$sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $row[VAL_PRODTKT]=fnvalor($row[VAL_PRODTKT],2);
            $row[VAL_PROMTKT]=fnvalor($row[VAL_PROMTKT],2);
            $row[PCT_DESCTKT]=fnvalor($row[PCT_DESCTKT],2);
            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
           // $textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"', '\n');

            //echo "<pre>";
            //print_r($row);
            //echo "</pre>";
        }
        fclose($arquivo);
        break;
    }