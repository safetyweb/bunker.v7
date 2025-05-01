<?php
include '_system/_functionsMain.php';

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_emenda = fnLimpaCampoZero($_POST['COD_EMENDA']);
$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
$cod_controle = fnLimpaCampoZero($_POST['COD_CONTROLE']);
$cod_estadual = fnLimpaCampoZero($_POST['COD_ESTADUAL']);
$cod_federal = fnLimpaCampoZero($_POST['COD_CLIENTE']);
$cod_estado = fnLimpaCampoZero($_POST['COD_ESTADO']);
$cod_municipio = fnLimpaCampoZero($_POST['COD_MUNICIPIO']);
$andMunicipio = $_POST['AND_MUNICIPIO'];
$andParceiro = $_POST['AND_PARCEIRO'];
$hashLocal = mt_rand();
   

switch ($opcao) {

    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivoCaminho = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $sql = "SELECT  D.NOM_CLIENTE AS NOM_ESTADUAL,
                        C.NOM_CLIENTE AS NOM_FEDERAL,
                        M.NOM_MUNICIPIO
                    FROM dobrada_campanha A
                    LEFT JOIN clientes C ON C.COD_CLIENTE = A.COD_FEDERAL
                    LEFT JOIN clientes D ON D.COD_CLIENTE = A.COD_ESTADUAL
                    LEFT JOIN municipios M ON M.COD_MUNICIPIO = A.COD_MUNICIPIO
                    LEFT JOIN estado E ON E.COD_ESTADO = A.COD_ESTADO
                    WHERE A.COD_EMPRESA = $cod_empresa
                    AND A.COD_EXCLUSA= 0
                    $andMunicipio
                    $andParceiro
                    ORDER BY A.COD_CONTROLE
                   
        ";
        //echo($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $arquivo = fopen($arquivoCaminho, 'w',0);
                        
        while($headers=mysqli_fetch_field($arrayQuery)){
             $CABECHALHO[]=$headers->name;
        }
        fputcsv ($arquivo,$CABECHALHO,';','"','\n');
      
        while ($row=mysqli_fetch_assoc($arrayQuery)){
            
            //$limpandostring = fnAcentos(Utf8_ansi(json_encode($row)));
            //$textolimpo = json_decode($limpandostring, true);
            $array = array_map("utf8_decode", $row);
            fputcsv($arquivo, $array, ';', '"', '\n');	
        }
        fclose($arquivo);

        break;

    case 'paginar':

        $sql = "SELECT 1 FROM DOBRADA_CAMPANHA
                WHERE COD_EMPRESA = $cod_empresa
                $andParceiro
                $andMunicipio
                ";
        //fnTestesql(connTemp($cod_empresa,''),$sql);		
        //fnEscreve($sql);

        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
        $totalitens_por_pagina = mysqli_num_rows($retorno);

        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

        //variavel para calcular o início da visualização com base na página atual
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        // Filtro por Grupo de Lojas
        //include "filtroGrupoLojas.php";


        $sql = "SELECT A.*,
                C.NOM_CLIENTE,
                M.NOM_MUNICIPIO,
                E.NOME AS NOM_ESTADO 
                FROM dobrada_campanha A
                LEFT JOIN clientes C ON C.COD_CLIENTE = A.COD_FEDERAL
                LEFT JOIN municipios M ON M.COD_MUNICIPIO = A.COD_MUNICIPIO
                LEFT JOIN estado E ON E.COD_ESTADO = A.COD_ESTADO
                WHERE A.COD_EMPRESA = $cod_empresa
                AND A.COD_EXCLUSA= 0
                $andMunicipio
                $andParceiro
                ORDER BY A.COD_CONTROLE
                LIMIT $inicio, $itens_por_pagina
            ";
        //echo($sql);
        //fnTestesql(connTemp($cod_empresa,''),$sql);											
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $count = 0;
        while ($qrApoia = mysqli_fetch_assoc($arrayQuery)) {

            $count++;
        ?>
            <tr>
                <td><input type='radio' name='radio1' onclick='retornaForm(<?php echo $count; ?>)'></td>
                <td><small><?= $qrApoia['COD_CONTROLE'] ?></small></td>
                <td><small><?= $qrApoia['NOM_CLIENTE'] ?></small></td>
                <td><small><?= $qrApoia['NOM_MUNICIPIO'] ?></small></td>
                <td><small><?= $qrApoia['NOM_ESTADO'] ?></small></td>
            </tr>

            <input type="hidden" name="ret_NOM_CLIENTE_<?= $count ?>" id="ret_NOM_CLIENTE_<?= $count ?>" value="<?= $qrApoia['NOM_CLIENTE'] ?>">
            <input type="hidden" name="ret_NOM_MUNICIPIO_<?= $count ?>" id="ret_NOM_MUNICIPIO_<?= $count ?>" value="<?= $qrApoia['NOM_MUNICIPIO'] ?>">
            <input type="hidden" name="ret_COD_ESTADO_<?= $count ?>" id="ret_COD_ESTADO_<?= $count ?>" value="<?= $qrApoia['COD_ESTADO'] ?>">
            <input type="hidden" name="ret_COD_MUNICIPIO_<?= $count ?>" id="ret_COD_MUNICIPIO_<?= $count ?>" value="<?= $qrApoia['COD_MUNICIPIO'] ?>">
            <input type="hidden" name="ret_COD_FEDERAL_<?= $count ?>" id="ret_COD_FEDERAL_<?= $count ?>" value="<?= $qrApoia['COD_FEDERAL'] ?>">

        <?php
        }

                break;
}
?>

