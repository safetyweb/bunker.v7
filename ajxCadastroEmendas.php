<?php
include '_system/_functionsMain.php';
require_once 'js/plugins/Spout/Autoloader/autoload.php';

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

$andStatus = fnLimpaCampo($_POST['ANDSTATUS']);
$andMunicipio = fnLimpaCampo($_POST['ANDMUNICIPIO']);
$andEmenda = fnLimpaCampo($_POST['ANDEMENDA']);

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$cod_status = fnLimpaCampoZero($_POST['COD_STATUS']);
$cod_municipio = fnLimpaCampoZero($_POST['COD_MUNICIPIO']);
$log_externo = 'N';
$dias30 = "";
$casasDec = $_REQUEST['CASAS_DEC'];
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

if ($log_externo == 'S') {
    $check_externo = 'checked';
} else {
    $check_externo = '';
}


switch ($opcao) {

    case 'exportar':

        $nomeRel = $_GET['nomeRel'];
        $arquivo = 'media/excel/' . $cod_empresa . '_' . $nomeRel . '.csv';

        $writer = WriterFactory::create(Type::CSV);
        $writer->setFieldDelimiter(';');
        $writer->openToFile($arquivo);

        $sql = "SELECT EM.COD_EMENDA,
                NM.NOM_MUNICIPIO,
                EM.DES_EMENDA,
                TPE.DES_TIPO,
                ORE.DES_ORGAO,
                STE.DES_STATUS,
                CL2.NOM_CLIENTE AS NOM_BENEFICIARIO,
                EM.DAT_INI,
                EM.VAL_EMENDA                                                                                    
        FROM EMENDA EM 
        LEFT JOIN OBJETO_EMENDA OBE ON OBE.COD_OBJETO = EM.COD_OBJETO
        LEFT JOIN ORGAO_EMENDA ORE ON ORE.COD_ORGAO = EM.COD_ORGAO
        LEFT JOIN STATUS_EMENDA STE ON STE.COD_STATUS = EM.COD_STATUS
        LEFT JOIN TIPO_EMENDA TPE ON TPE.COD_TIPO = EM.COD_TIPO
        LEFT JOIN CLIENTES CL1 ON CL1.COD_CLIENTE = EM.COD_RESPONSAVEL
        LEFT JOIN CLIENTES CL2 ON CL2.COD_CLIENTE = EM.COD_BENEFICIARIO
        LEFT JOIN municipios NM ON NM.COD_MUNICIPIO = EM.COD_MUNICIPIO
        WHERE EM.COD_EMPRESA = $cod_empresa
        $andStatus
        $andMunicipio
        $andEmenda
        ";
        //fnEscreve($sql);
        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

        $array = array();
        while ($row = mysqli_fetch_assoc($arrayQuery)) {

            $newRow = array();

            $cont = 0;
            foreach ($row as $objeto) {

                // Colunas que são double converte com fnValor
                if ($cont == 8) {

                    array_push($newRow, fnValor($objeto, 2));
                } else if (($cont > 8 && $cont <= 10) || $cont == 13) {

                    array_push($newRow, fnValor($objeto, $casasDec));
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

        break;

    case 'paginar':
        
        $sql = "SELECT * FROM EMENDA EM
        WHERE COD_EMPRESA = $cod_empresa
        $andStatus
        $andMunicipio
        $andEmenda
        ";
//fnTestesql(connTemp($cod_empresa,''),$sql);		
//fnEscreve($sql);

$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
$totalitens_por_pagina = mysqli_num_rows($retorno);

$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

//variavel para calcular o início da visualização com base na página atual
$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
        
        
        
$sql = "SELECT EM.*,
                        ORE.DES_ORGAO,
                        STE.DES_STATUS,
                        TPE.DES_TIPO,
                        CL1.NOM_CLIENTE AS NOM_RESPONSAVEL,
                        CL2.NOM_CLIENTE AS NOM_BENEFICIARIO,
                        NM.NOM_MUNICIPIO
        FROM EMENDA EM 
        LEFT JOIN OBJETO_EMENDA OBE ON OBE.COD_OBJETO = EM.COD_OBJETO
        LEFT JOIN ORGAO_EMENDA ORE ON ORE.COD_ORGAO = EM.COD_ORGAO
        LEFT JOIN STATUS_EMENDA STE ON STE.COD_STATUS = EM.COD_STATUS
        LEFT JOIN TIPO_EMENDA TPE ON TPE.COD_TIPO = EM.COD_TIPO
        LEFT JOIN CLIENTES CL1 ON CL1.COD_CLIENTE = EM.COD_RESPONSAVEL
        LEFT JOIN CLIENTES CL2 ON CL2.COD_CLIENTE = EM.COD_BENEFICIARIO
        LEFT JOIN municipios NM ON NM.COD_MUNICIPIO = EM.COD_MUNICIPIO
        WHERE EM.COD_EMPRESA = $cod_empresa
        $andStatus
        $andMunicipio
        $andEmenda
        LIMIT $inicio,$itens_por_pagina
        ";

        //fnEscreve($sql);
//fnTestesql(connTemp($cod_empresa,''),$sql);											
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

$count = 0;
while ($qrApoia = mysqli_fetch_assoc($arrayQuery)) {

    $count++;
    ?>
        <tr>
            <td><input type='radio' name='radio1' onclick='retornaForm(<?php echo $count; ?>)'></td>
            <td><small><?= $qrApoia['COD_EMENDA'] ?></small></td>
            <td><small><?= $qrApoia['NOM_MUNICIPIO'] ?></small></td>
            <td><small><?= $qrApoia['DES_EMENDA'] ?></small></td>
            <td><small><?= $qrApoia['DES_TIPO'] ?></small></td>
            <td><small><?= $qrApoia['DES_ORGAO'] ?></small></td>
            <td><small><?= $qrApoia['DES_STATUS'] ?></small></td>
            <td><small><?= $qrApoia['NOM_BENEFICIARIO'] ?></small></td>
            <td><small><?= fnDataShort($qrApoia['DAT_INI']) ?></small></td>
            <td class="text-left"><small><?= fnValor($qrApoia['VAL_EMENDA'], 2) ?></small></td>
        </tr>

        <input type="hidden" name="ret_COD_EMENDA_<?= $count ?>" id="ret_COD_EMENDA_<?= $count ?>" value="<?= $qrApoia[COD_EMENDA] ?>">
        <input type="hidden" name="ret_NOM_MUNICIPIO_<?= $count ?>" id="ret_NOM_MUNICIPIO_<?= $count ?>" value="<?= $qrApoia[NOM_MUNICIPIO] ?>">
        <input type="hidden" name="ret_COD_OBJETO_<?= $count ?>" id="ret_COD_OBJETO_<?= $count ?>" value="<?= $qrApoia[COD_OBJETO] ?>">
        <input type="hidden" name="ret_DES_EMENDA_<?= $count ?>" id="ret_DES_EMENDA_<?= $count ?>" value="<?= $qrApoia[DES_EMENDA] ?>">
        <input type="hidden" name="ret_COD_TIPO_<?= $count ?>" id="ret_COD_TIPO_<?= $count ?>" value="<?= $qrApoia[COD_TIPO] ?>">
        <input type="hidden" name="ret_COD_ORGAO_<?= $count ?>" id="ret_COD_ORGAO_<?= $count ?>" value="<?= $qrApoia[COD_ORGAO] ?>">
        <input type="hidden" name="ret_COD_STATUS_<?= $count ?>" id="ret_COD_STATUS_<?= $count ?>" value="<?= $qrApoia[COD_STATUS] ?>">
        <input type="hidden" name="ret_COD_ESTADO_<?= $count ?>" id="ret_COD_ESTADO_<?= $count ?>" value="<?= $qrApoia[COD_ESTADO] ?>">
        <input type="hidden" name="ret_COD_MUNICIPIO_<?= $count ?>" id="ret_COD_MUNICIPIO_<?= $count ?>" value="<?= $qrApoia[COD_MUNICIPIO] ?>">
        <input type="hidden" name="ret_COD_BENEFICIARIO_<?= $count ?>" id="ret_COD_BENEFICIARIO_<?= $count ?>" value="<?= $qrApoia[COD_BENEFICIARIO] ?>">
        <input type="hidden" name="ret_NOM_BENEFICIARIO_<?= $count ?>" id="ret_NOM_BENEFICIARIO_<?= $count ?>" value="<?= $qrApoia[NOM_BENEFICIARIO] ?>">
        <input type="hidden" name="ret_COD_RESPONSAVEL_<?= $count ?>" id="ret_COD_RESPONSAVEL_<?= $count ?>" value="<?= $qrApoia[COD_RESPONSAVEL] ?>">
        <input type="hidden" name="ret_NOM_RESPONSAVEL_<?= $count ?>" id="ret_NOM_RESPONSAVEL_<?= $count ?>" value="<?= $qrApoia[NOM_RESPONSAVEL] ?>">
        <input type="hidden" name="ret_NUM_LOTE_<?= $count ?>" id="ret_NUM_LOTE_<?= $count ?>" value="<?= $qrApoia[NUM_LOTE] ?>">
        <input type="hidden" name="ret_NUM_SEQUENCIA_<?= $count ?>" id="ret_NUM_SEQUENCIA_<?= $count ?>" value="<?= $qrApoia[NUM_SEQUENCIA] ?>">
        <input type="hidden" name="ret_COD_ALESP_<?= $count ?>" id="ret_COD_ALESP_<?= $count ?>" value="<?= $qrApoia[COD_ALESP] ?>">
        <input type="hidden" name="ret_NUM_EMEDAPAL_<?= $count ?>" id="ret_NUM_EMEDAPAL_<?= $count ?>" value="<?= $qrApoia[NUM_EMEDAPAL] ?>">
        <input type="hidden" name="ret_VAL_EMENDA_<?= $count ?>" id="ret_VAL_EMENDA_<?= $count ?>" value="<?= fnValor($qrApoia[VAL_EMENDA], 2) ?>">
        <input type="hidden" name="ret_DAT_INI_<?= $count ?>" id="ret_DAT_INI_<?= $count ?>" value="<?= fnDataShort($qrApoia[DAT_INI]) ?>">
    <?php
}

                break;
        }
?>

