<?php

include '../_system/_functionsMain.php';
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$opcao = "";
$dat_ini = "";
$dat_fim = "";
$numCartao = "";
$nomCliente = "";
$lojasSelecionadas = "";
$casasDec = "";
$autoriza = "";
$tipoRel = "";
$condRel = "";
$dias30 = "";
$hoje = "";
$nom_cliente = "";
$andNome = "";
$andCartao = "";
$andUnivend = "";
$nomeRel = "";
$arquivoCaminho = "";
$arrayQuery = [];
$arquivo = "";
$headers = "";
$row = "";
$canal = "";
$limpandostring = "";
$textolimpo = "";
$array = [];
$newRow = "";
$objeto = "";
$arrayColumnsNames = [];
$writer = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$qrListaEmpresas = "";
$colCliente = "";
$mostraCracha = "";
$mostraPlaca = "";


//fnDebug('true');

$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];
$cod_empresa = fnDecode(@$_GET['id']);

$cod_univend = fnLimpaCampoZero(fnDecode(@$_POST['COD_UNIVEND_FILTRO']));
$cod_grupotr = fnLimpaCampoZero(@$_POST['COD_GRUPOTR']);
$cod_tiporeg = fnLimpaCampoZero(@$_POST['COD_TIPOREG']);
$dat_ini = fnDataSql(@$_POST['DAT_INI']);
$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
$numCartao = fnLimpaCampo(@$_POST['NUM_CARTAO']);
$nomCliente = fnLimpaCampo(@$_POST['NOM_CLIENTE']);
$lojasSelecionadas = fnLimpaCampo(@$_POST['LOJAS']);
$casasDec = fnLimpaCampo(@$_POST['CASAS_DEC']);
$autoriza = fnLimpaCampoZero(@$_POST['AUTORIZA']);

// fnEscreve($cod_univend);


$tipoRel = @$_GET['tipoRel'];

$condRel = "";


switch ($tipoRel) {

    case 'aceite':
        $condRel = " AND CL.log_termo='S' ";
        break;

    case 'semAceite':
        $condRel = " AND CL.log_termo='N' ";
        break;

    default:
        $condRel = "";
        break;
}

//inicializaÃƒÆ’Ã‚Â§ÃƒÆ’Ã‚Â£o das variÃƒÆ’Ã‚Â¡veis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

if ($nom_cliente != '' && $nom_cliente != 0) {
    $andNome = 'AND CL.NOM_CLIENTE LIKE "' . $nom_cliente . '%"';
} else {
    $andNome = ' ';
}

if ($numCartao != '' && $numCartao != 0) {
    $andCartao = 'AND CL.NUM_CARTAO=' . $numCartao;
} else {
    $andCartao = ' ';
}

if ($cod_univend != '9999') {
    $andUnivend = "AND CL.COD_UNIVEND IN ($lojasSelecionadas)";
} else {
    $andUnivend = "AND (CL.COD_UNIVEND IN ($lojasSelecionadas) OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
}


switch ($opcao) {
    case 'exportar':

        $nomeRel = @$_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        // Filtro por Grupo de Lojas
        include "../filtroGrupoLojas.php";

        $sql = "SELECT  CL.COD_CLIENTE 'CODIGO',
                        CL.NOM_CLIENTE 'NOME',
                        CL.DAT_CADASTR 'DT. CADASTRO',
                        CL.DAT_ULTCOMPR 'DT. ULTIMA COMPRA',
                        UNI.NOM_FANTASI 'LOJA',
                        CL.COD_VENDEDOR,
                        USU.NOM_USUARIO 'VENDEDOR',
                        CL.NUM_CARTAO 'CARTAO',
                        CL.NUM_CGCECPF 'CPF/CNPJ',
                        CL.NUM_TELEFON 'TELEFONE',
                        CL.NUM_CELULAR 'CELULAR',
                        CL.DES_EMAILUS 'EMAIL',
                        LOGC.COD_CANAL 'CANAL',
                        (SELECT ifnull(SUM(VAL_SALDO),0)
                            FROM CREDITOSDEBITOS CDB
                            WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
                            AND TIP_CREDITO='C' 
                            AND COD_STATUSCRED=1 
                            AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                            AND COD_EMPRESA = $cod_empresa ) AS SALDO,
                        (SELECT ifnull(SUM(VAL_SALDO),0)
                            FROM CREDITOSDEBITOS CDB
                            WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
                            AND TIP_CREDITO='C' 
                            AND COD_STATUSCRED IN (3,7) 
                            AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                            AND COD_EMPRESA = $cod_empresa ) AS SALDO_BLOQUEADO

                FROM CLIENTES CL
                LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
                LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=CL.COD_UNIVEND
                left JOIN LOG_CANAL LOGC ON LOGC.COD_CLIENTE = CL.COD_CLIENTE AND LOGC.cod_empresa=CL.COD_EMPRESA
                WHERE CL.COD_EMPRESA = $cod_empresa
                AND CL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00 ' AND '$dat_fim 23:59:59'
                $andUnivend
                $andNome
                $andCartao
                $condRel
                ORDER BY CL.NOM_CLIENTE";

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
        //fnEscreve($sql);

        // echo "<pre>";
        // print_r($arrayQuery);
        // echo "</pre>";

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            switch (@$row['COD_CANAL']) {

                case 2:
                    $canal = "TOTEM";
                    break;

                case 3:
                    $canal = "HOTSITE";
                    break;

                case 4:
                    $canal = "BUNKER";
                    break;

                case 5:
                    $canal = "PDV VIRTUAL";
                    break;

                case 6:
                    $canal = "MAIS CASH";
                    break;

                default:
                    $canal = "PDV SH";
                    break;
            }

            $row['CANAL'] = $canal;
            $row['SALDO'] = fnValor($row['SALDO'], 2);
            $row['SALDO_BLOQUEADO'] = fnValor($row['SALDO_BLOQUEADO'], 2);

            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            //$textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"');
        }
        fclose($arquivo);

        /*$array = array();
		while($row = mysqli_fetch_assoc($arrayQuery)){
			  $newRow = array();
			  
			  $cont = 0;
			  foreach ($row as $objeto) {
				  
				// Colunas que sÃƒÂ£o double converte com fnValor
				if($cont == 12 || $cont == 13){

					array_push($newRow, fnValor($objeto, $casasDec));

				}else if($cont == 11){

                    switch($objeto){
                                                            
                        case 2 : 
                           
                            $canal="Hotsite"; 
                        
                        break;
                        
                        case 3 : 
                           
                            $canal="Totem"; 
                        
                        break;
                        
                        case 4 : 
                           
                            $canal="SMS"; 
                        
                        break;
                        
                        case 5 : 
                           
                            $canal="Email"; 
                        
                        break;
                        
                        case 6 : 
                           
                            $canal="PDV Virtual"; 
                        
                        break;
                 
                        
                        default : 
                            
                            $canal="Bunker";
                            
                        break;
                        
                        
                    }

                    array_push($newRow, $canal);

                }else{

					array_push($newRow, $objeto);

				}
				
				$cont++;
			  }
			$array[] = $newRow;
		}
		
		$arrayColumnsNames = array();

		while($row = mysqli_fetch_field($arrayQuery))
		{
			array_push($arrayColumnsNames, $row->name);
		}			

		$writer->addRow($arrayColumnsNames);
		$writer->addRows($array);

		$writer->close();
        */

        break;

    case 'paginar':

        // Filtro por Grupo de Lojas
        include "../filtroGrupoLojas.php";

        $sql = "SELECT  count(case when log_avulso='N' then
                                 COD_CLIENTE
                                END)  QTD_TOTAL
                        
                FROM clientes CL 
                WHERE COD_EMPRESA = $cod_empresa                         
                AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00 ' AND '$dat_fim 23:59:59' 
                AND log_avulso='N'
                $andUnivend
                $andNome
                $andCartao
        ";

        //fnEscreve($sql);
        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $totalitens_por_pagina = mysqli_fetch_assoc($retorno);
        $numPaginas = ceil($totalitens_por_pagina['QTD_TOTAL'] / $itens_por_pagina);
        //fnEscreve($totalitens_por_pagina['QTD_TOTAL_NAO_ACEITE']);

        //variavel para calcular o inÃ­cio da visualizaÃ§Ã£o com base na pÃ¡gina atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        //lista de clientes
        $sql = "SELECT  CL.COD_CLIENTE,
                        CL.NOM_CLIENTE,
                        CL.DAT_CADASTR,
                        CL.DAT_ULTCOMPR,
                        UNI.NOM_FANTASI,
                        USU.NOM_USUARIO,
                        CL.NUM_CARTAO,
                        CL.NUM_CGCECPF,
                        CL.NUM_TELEFON,
                        CL.NUM_CELULAR,
                        CL.DES_EMAILUS,
                        LOGC.COD_CANAL,
                        CL.COD_VENDEDOR,
               (SELECT ifnull(SUM(VAL_SALDO),0)
                  FROM CREDITOSDEBITOS CDB
                 WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
                       AND TIP_CREDITO='C' 
                       AND COD_STATUSCRED=1 
                       AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                       AND COD_EMPRESA = $cod_empresa ) AS VAL_SALDO,
               (SELECT ifnull(SUM(VAL_SALDO),0)
                  FROM CREDITOSDEBITOS CDB
                 WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
                       AND TIP_CREDITO='C' 
                       AND COD_STATUSCRED IN (3,7) 
                       AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
                       AND COD_EMPRESA = $cod_empresa ) AS SALDO_BLOQUEADO

            FROM CLIENTES CL
            LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
            LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=CL.COD_UNIVEND
            LEFT JOIN LOG_CANAL LOGC ON LOGC.COD_CLIENTE = CL.COD_CLIENTE AND LOGC.cod_empresa=CL.COD_EMPRESA
            WHERE CL.COD_EMPRESA = $cod_empresa
            AND CL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00 ' AND '$dat_fim 23:59:59'
            $andUnivend
            $andNome
            $andCartao
            ORDER BY CL.NOM_CLIENTE 
            LIMIT $inicio,$itens_por_pagina";


        // fnEscreve($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
        //  echo "___".$sql."___";
        $count = 0;

        while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

            switch ($qrListaEmpresas['COD_CANAL']) {

                case 2:
                    $canal = "TOTEM";
                    break;

                case 3:
                    $canal = "HOTSITE";
                    break;

                case 4:
                    $canal = "BUNKER";
                    break;

                case 5:
                    $canal = "PDV VIRTUAL";
                    break;

                case 6:
                    $canal = "MAIS CASH";
                    break;

                default:
                    $canal = "PDV SH";
                    break;
            }

            $count++;


            if ($autoriza == 1) {
                $colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</a></small></td>";
            } else {
                $colCliente = "<td><small>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</small></td>";
            }

            echo "
                        <tr>
                        <td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
                        " . $colCliente . "
                        <td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR']) . "</small></td>
                        <td><small>" . $canal . "</small></td>
                        <td><small>" . fnDataFull($qrListaEmpresas['DAT_ULTCOMPR']) . "</small></td>
                        <td><small>" . $qrListaEmpresas['NOM_FANTASI'] . "</small></td>
                        <td> <small>" . $qrListaEmpresas['NOM_USUARIO'] . "</small></td>
                        <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CARTAO']) . "</small></td>
                        <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CGCECPF']) . "</small></td>
                        <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_TELEFON']) . "</small></td>
                        <td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CELULAR']) . "</small></td>
                        <td><small>" . fnMascaraCampo(strtolower($qrListaEmpresas['DES_EMAILUS'])) . "</small></td>
                        $mostraPlaca
                        <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['VAL_SALDO'], $casasDec) . "</small></td>
                        <td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['SALDO_BLOQUEADO'], $casasDec) . "</small></td>
                        </tr>
                        <input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
                        <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
                ";
        }


        break;
}
