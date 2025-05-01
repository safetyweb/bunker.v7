<?php

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];	
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);
switch($opcao){
    
    case 'exportar':
            
        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/'.$cod_empresa.'_'.$nomeRel.'.csv';


        // Filtro por Grupo de Lojas
        include "filtroGrupoLojas.php";

        $sql = "SELECT 
                    PRODUTOCLIENTE.DES_PRODUTO, 
                    PRODUTOCLIENTE.COD_EXTERNO, 
                    PRODUTOCLIENTE.COD_PRODUTO AS PRODUTO, 
                    DESCONTOTKT.ABV_DESCTKT, 
                    IF(PRODUTOCLIENTE.DES_IMAGEM <> '','S','N') AS TEM_IMAGEM,
                    produtotkt.*, 
                    categoriatkt.*, 
                GROUP_CONCAT(DISTINCT P.DES_PERSONA SEPARATOR ',')	DES_PERSONA
                FROM PRODUTOTKT
                LEFT JOIN categoriatkt ON categoriatkt.COD_CATEGORTKT = PRODUTOTKT.COD_CATEGORTKT
                LEFT JOIN DESCONTOTKT ON DESCONTOTKT.COD_DESCTKT = PRODUTOTKT.COD_DESCTKT
                INNER JOIN PRODUTOCLIENTE ON PRODUTOCLIENTE.COD_PRODUTO = PRODUTOTKT.COD_PRODUTO
                INNER JOIN persona P ON P.COD_PERSONA = produtotkt.COD_PERSONA_TKT
                WHERE PRODUTOTKT.COD_PRODUTO = PRODUTOCLIENTE.COD_PRODUTO AND PRODUTOTKT.COD_EMPRESA = 85
                GROUP BY PRODUTOCLIENTE.COD_PRODUTO
                ORDER BY DES_CATEGOR, NOM_PRODTKT
                ";
                
        //fnEscreve($sql);
                
        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {
           // $limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
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