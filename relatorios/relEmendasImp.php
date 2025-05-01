<?php
$itens_por_pagina = 50;
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {

        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {

        $_SESSION['last_request'] = $request;


        $cod_status = fnLimpaCampoZero($_POST['COD_STATUS']);
        $cod_municipio = $_POST['COD_MUNICIPIO'];
        $dat_ini = fnDataSql($_POST['DAT_INI']);
        $dat_fim = fnDataSql($_POST['DAT_FIM']);

        $Arr_COD_MUNICIPIO = $cod_municipio;

        // fnEscreve($_POST['COD_MUNICIPIO']);

        if (isset($Arr_COD_MUNICIPIO)) {
            //array das unidades de venda
            $countMunicipio = 0;
            if (isset($Arr_COD_MUNICIPIO)) {
                for ($i = 0; $i < count($Arr_COD_MUNICIPIO); $i++) {
                    $str_municipio .= $Arr_COD_MUNICIPIO[$i] . ',';
                    $countMunicipio++;
                }
                $str_municipio = rtrim($str_municipio, ',');
            }
            $cod_municipio = ltrim($str_municipio, ',');
        } else {
            $cod_municipio = "0";
        }

        // fnEscreve($cod_municipio);

        $cod_usucada = $_SESSION[SYS_COD_USUARIO];

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];
    }
}


//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $cod_campanha = fnDecode($_GET['idc']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    $nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = "";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = "";
}

if ($dat_ini != "") {
    $dat_ini_exibe = fnDataShort($dat_ini);
}

if ($dat_fim != "") {
    $dat_fim_exibe = fnDataShort($dat_fim);
}
?>

<style>
    @media print {

        a[href]:after {
            content: none !important;
        }

        @page {
            size: A4;
            /* DIN A4 standard, Europe */
            margin: 0;
        }

        html,
        body {
            width: 210mm;
            /* height: 297mm; */
            height: 282mm;
            font-size: 11px;
            background: #FFF;
            overflow: visible;
        }

        body {
            padding-top: 7mm;
            padding-right: 4mm;
        }

        .hidden-print {
            display: none;
        }
    }
</style>
<div class="row">
    <div class="col-md-12">

        <div class="no-more-tables">

            <form name="formLista">

                <table class="table table-bordered table-striped table-hover tablesorter buscavel">

                    <thead>
                        <tr>
                            <th>Cod.</th>
                            <th>Cidade</th>
                            <th>Descrição</th>
                            <th>Tipo</th>
                            <th>Orgão</th>
                            <th>Status</th>
                            <th>Beneficiário</th>
                            <th>Dt. Inicial</th>
                            <th class="text-left">Valor</th>
                        </tr>

                    </thead>

                    <tbody id="relatorioConteudo">

                        <?php

                        $andStatus = "";
                        $andMunicipio = "";

                        if ($cod_status != 0) {
                            $andStatus = "AND EM.COD_STATUS = $cod_status";
                        }

                        if ($cod_municipio != 0) {
                            $andMunicipio = "AND EM.COD_MUNICIPIO IN($cod_municipio)";
                        }

                        if ($dat_ini != "" && $dat_fim != "") {
                            $andData = "AND EM.DAT_INI BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";
                        } else if ($dat_ini != "") {
                            $andData = "AND EM.DAT_INI >= '$dat_ini 00:00:00'";
                        } else if ($dat_fim != "") {
                            $andData = "AND EM.DAT_INI <= '$dat_fim 23:59:59'";
                        } else {
                            $andData = "";
                        }


                        $sql = "SELECT * FROM EMENDA EM
                                                    WHERE COD_EMPRESA = $cod_empresa
                                                    $andData
                                                    $andStatus
                                                    $andMunicipio
                                                    ";

                        //fnEscreve($sql);

                        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                        $totalitens_por_pagina = mysqli_num_rows($retorno);

                        $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                        //variavel para calcular o início da visualização com base na página atual
                        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                        // Filtro por Grupo de Lojas
                        //include "filtroGrupoLojas.php";


                        $sql = "SELECT              EM.COD_EMENDA,
                                                    EM.NUM_EMEDAPAL,
                                                    NM.NOM_MUNICIPIO,
                                                    TPE.DES_TIPO,
                                                    EM.DES_EMENDA,
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
                                                    LEFT JOIN CLIENTES CL2 ON CL2.COD_CLIENTE = EM.COD_BENEFICIARIO
                                                    LEFT JOIN municipios NM ON NM.COD_MUNICIPIO = EM.COD_MUNICIPIO 
                                                    WHERE EM.COD_EMPRESA = $cod_empresa
                                                    AND EM.COD_EXCLUSA = 0
                                                    $andData
                                                    $andStatus
                                                    $andMunicipio
                                                    LIMIT $inicio,$itens_por_pagina
                                                    ";

                        // fnEscreve($sql);
                        //fnTestesql(connTemp($cod_empresa,''),$sql);											
                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                        $count = 0;
                        while ($qrApoia = mysqli_fetch_assoc($arrayQuery)) {

                            $count++;
                        ?>
                            <tr>
                                <td><small><?= $qrApoia['NUM_EMEDAPAL'] ?></small></td>
                                <td><small><?= $qrApoia['NOM_MUNICIPIO'] ?></small></td>
                                <td><small><?= $qrApoia['DES_EMENDA'] ?></small></td>
                                <td><small><?= $qrApoia['DES_TIPO'] ?></small></td>
                                <td><small><?= $qrApoia['DES_ORGAO'] ?></small></td>
                                <td><small><?= $qrApoia['DES_STATUS'] ?></small></td>
                                <td><small><?= $qrApoia['NOM_BENEFICIARIO'] ?></small></td>
                                <td><small><?= fnDataShort($qrApoia['DAT_INI']) ?></small></td>
                                <td class="text-left"><small><?= fnValor($qrApoia['VAL_EMENDA'], 2) ?></small></td>
                            </tr>
                        <?php
                        }
                        ?>
                    <tfoot>
                        <div class="hidden-print">

                            <div class="push10"></div>
                            <hr>
                            <div class="form-group text-right col-lg-12">

                                <a href="javascript:window.print();" class="btn btn-info"><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Imprimir Cadastro </a>

                            </div>

                        </div>
                    </tfoot>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>

<script type="text/javascript">
    $(".fal fa-print").click(function() {
        console.log("teste")
    })
</script>