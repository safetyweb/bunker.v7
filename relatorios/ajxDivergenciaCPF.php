<?php

include '../_system/_functionsMain.php';
//echo fnDebug('true');

$dias30 = "";
$dat_ini = "";
$dat_fim = "";


$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnDecode($_GET['id']);

$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$andFiltro = $_POST['FILTRO'];
$andFiltroInconsist = $_POST['FILTRO_INCONSIST'];
$andUnidade = $_POST['UNIDADE'];
$lojasSelecionadas = $_POST['LOJAS'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" || $dat_ini == " ") {
    $dat_ini = " ";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

if (trim($dat_ini) != "") {
    $Data = "B.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND";
} else {
    $Data = "";
}

if ($filtro != "") {
    if ($filtro == "UNIDADE") {
        $sqlUni = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
			WHERE (NOM_FANTASI LIKE '%$val_pesquisa%' 
			OR NUM_CGCECPF = '$val_pesquisa' 
			OR NOM_UNIVEND LIKE '%$val_pesquisa%')
			AND COD_EMPRESA = $cod_empresa";
        // fnEscreve($sqlUni);
        $qrUni = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUni));

        // fnEscreve($qrUni['COD_UNIVEND']);

        $andFiltro = " ";
        $andUnidade = " AND B.COD_UNIVEND IN ($qrUni[COD_UNIVEND]) ";
    } else {
        $andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
        $andUnidade = "";
    }
} else {
    $andFiltro = " ";
}

if ($andUnidade == "") {
    $orUnidade = "AND (B.COD_UNIVEND IN(0,$lojasSelecionadas) OR B.COD_UNIVEND = 0 OR B.COD_UNIVEND IS NULL)";
    if ($andFiltroInconsist == "AND ( B.COD_UNIVEND = '0'  or B.COD_UNIVEND is null)") {
        $orUnidade = "";
    }
} else {

    if ($andFiltroInconsist == "AND ( B.COD_UNIVEND = '0'  or B.COD_UNIVEND is null)") {
        $andUnidade = "";
        $orUnidade = "";
    }
}

// fnEscreve($andFiltroInconsist);
// fnEscreve($andUnidade);
// fnEscreve($orUnidade);

switch ($opcao) {
    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = '../media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT  B.COD_CLIENTE,
                        B.NUM_CARTAO,
                        B.NUM_CGCECPF,
                        B.NOM_CLIENTE,
                        B.DES_EMAILUS,
                        B.DAT_CADASTR,
                        B.DAT_NASCIME,
                        B.COD_SEXOPES,
                        B.COD_UNIVEND,
                        uni.NOM_FANTASI
                FROM clientes B
                LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=B.COD_UNIVEND
                WHERE 
                date(B.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
                AND B.COD_EMPRESA = $cod_empresa AND 
                B.NUM_CGCECPF!=B.NUM_CARTAO
                AND B.cod_univend IN($lojasSelecionadas)
                $andFiltro
                $orUnidade
                ORDER BY uni.NOM_FANTASI,B.NOM_CLIENTE ";

        //fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w', 0);

        while ($headers = mysqli_fetch_field($arrayQuery)) {
            $CABECHALHO[] = $headers->name;
        }
        fputcsv($arquivo, $CABECHALHO, ';', '"', '\n');

        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            switch ($row[COD_SEXOPES]) {
                case '1':
                    $row[COD_SEXOPES] = 'H';
                    break;
                case '2':
                    $row[COD_SEXOPES] = 'M';
                    break;
                default:
                    $row[COD_SEXOPES] = 'Indefinido';
                    break;
            }
            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            //$textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"', '\n');

            //echo "<pre>";
            //print_r($row);
            //echo "</pre>";
        }
        fclose($arquivo);
        break;
    case 'paginar':

         // Filtro por Grupo de Lojas
         include "filtroGrupoLojas.php";

         if ($andUnidade == "") {
             $orUnidade = "AND (B.COD_UNIVEND IN(0,$lojasSelecionadas) OR B.COD_UNIVEND = 0 OR B.COD_UNIVEND IS NULL)";
         } else {
             $orUnidade = "";
         }

         $sql2 = "SELECT  1  
                 FROM clientes B
                 LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=B.COD_UNIVEND
                 WHERE 
                 date(B.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
                 AND B.COD_EMPRESA = $cod_empresa AND 
                 B.NUM_CGCECPF!=B.NUM_CARTAO
                 AND B.cod_univend IN($lojasSelecionadas)
                 $andFiltro
                 $orUnidade
                 ORDER BY uni.NOM_FANTASI,B.NOM_CLIENTE
             ";
         // fnEscreve($sql2);

         $retorno = mysqli_query(connTemp($cod_empresa,''), $sql2);
         $total_itens_por_pagina = mysqli_num_rows($retorno);

         $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

         //variavel para calcular o início da visualização com base na página atual
         $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

         $sql = "SELECT  B.COD_CLIENTE,
                         B.NUM_CARTAO,
                         B.NUM_CGCECPF,
                         B.NOM_CLIENTE,
                         B.DES_EMAILUS,
                         B.DAT_CADASTR,
                         B.DAT_NASCIME,
                         B.COD_SEXOPES,
                         B.COD_UNIVEND,
                         uni.NOM_FANTASI
                 FROM clientes B
                 LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=B.COD_UNIVEND
                 WHERE 
                 date(B.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
                 AND B.COD_EMPRESA = $cod_empresa AND 
                 B.NUM_CGCECPF!=B.NUM_CARTAO
                 AND B.cod_univend IN($lojasSelecionadas)
                 $andFiltro
                 $orUnidade
                 ORDER BY uni.NOM_FANTASI,B.NOM_CLIENTE 
                 LIMIT $inicio,$itens_por_pagina";

         //fnEscreve($sql);

         $arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);

         $count = 0;
         while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {


             $count++;
             $loja = "";
             if ($qrListaPersonas['COD_UNIVEND'] != 0 && $qrListaPersonas['COD_UNIVEND'] != "") {
                 $loja = $qrListaPersonas['NOM_FANTASI'];
             }

             if ($qrListaPersonas['COD_SEXOPES'] == 1) {
                 $mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';
             }

             if ($qrListaPersonas['COD_SEXOPES'] == 2) {
                 $mostraSexo = '<i class="fa fa-female" style="color:pink" aria-hidden="true"></i>';
             }

             if ($qrListaPersonas['COD_SEXOPES'] == 3) {
                 $mostraSexo = '<i class="fa fa-venus-mars" aria-hidden="true"></i>';
             }

             echo "
                     <tr>
                         <td></td>
                         <td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaPersonas['NOM_CLIENTE']) . "&nbsp;</a></small></td>
                         <td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaPersonas['NUM_CARTAO']) . "&nbsp;</a></small></td>
                         <td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CGCECPF']) . "</small></td>
                         <td><small>" . fnMascaraCampo(strtolower($qrListaPersonas['DES_EMAILUS'])) . "</small></td>
                         <td class='text-center'><small>" . $mostraSexo . "</small></td>
                         <td><small>" . fnMascaraCampo($qrListaPersonas['DAT_NASCIME']) . "</small></td>
                         <td>" . $qrListaPersonas['IDADE'] . "</small></td>
                         <td><small>" . fnDataFull($qrListaPersonas['DAT_CADASTR']) . "</small></td>
                         <td>" . $loja . "</small></td>											
                     </tr>
                 ";
         }

        break;
}
