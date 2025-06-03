<?php
echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = 1;
$cod_campanha = "";
$hashLocal = mt_rand();

$dat_ini = "";
$dat_fim = "";

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);
        $cod_univend = @$_POST['COD_UNIVEND'];
        $cod_registr = @$_POST['COD_REGISTR'];
        if (isset($_POST['COD_CAMPANHA']) && $_POST['COD_CAMPANHA'] != "") {
            $cod_campanha = fnLimpaArray($_POST['COD_CAMPANHA']);
        } else {
            $cod_campanha = "";
        }

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {
        }
    }
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    $nom_empresa = "";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";


if (isset($cod_registr) && $cod_registr != "") {
    $lojasSelecionadas = fnLimpaArray($cod_registr);
}

$desabilita = "disabled";
$campanha = "";
if ($cod_campanha != "") {
    $desabilita = "";
    $campanha = "AND COD_CAMPANHA IN ($cod_campanha)";
}

?>

<style>
    table a:not(.btn),
    .table a:not(.btn) {
        text-decoration: none;
    }

    table a:not(.btn):hover,
    .table a:not(.btn):hover {
        text-decoration: underline;
    }
</style>

<div class="push30"></div>

<div class="row" id="div_Report">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="glyphicon glyphicon-calendar"></i>
                    <span class="text-primary"> <?php echo $NomePg . " / " . $nom_empresa; ?></span>
                </div>

                <?php
                //$formBack = "1015";
                include "atalhosPortlet.php";
                ?>

            </div>
            <div class="portlet-body">

                <?php if ($msgRetorno <> '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>


                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Filtros</legend>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Unidade de Atendimento</label>
                                        <?php include "unidadesAutorizadasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Campanha</label>
                                        <select data-placeholder="Selecione uma campanha" name="COD_CAMPANHA[]" id="COD_CAMPANHA" multiple="multiple" class="chosen-select-deselect">
                                            <?php
                                            $selecionado = "";
                                            if ($cod_campanha == "") {
                                                $selecionado = "selected";
                                            }
                                            ?>
                                            <option value="" <?= $selecionado; ?>>Todas Campanhas</option>
                                            <?php
                                            $sql = "SELECT * FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND TIP_CAMPANHA = 23";
                                            //fnEscreve($sql);
                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            while ($qrListaGrupoWork = mysqli_fetch_assoc($arrayQuery)) {

                                                if (!empty($_REQUEST['COD_CAMPANHA']) && is_array($_REQUEST['COD_CAMPANHA'])) {
                                                    if (recursive_array_search($qrListaGrupoWork['COD_CAMPANHA'], array_filter($_REQUEST['COD_CAMPANHA'])) !== false) {
                                                        $selecionado = "selected";
                                                    } else {
                                                        $selecionado = "";
                                                    }
                                                } else {
                                                    $selecionado = "";
                                                }

                                                echo "<option value='" . $qrListaGrupoWork['COD_CAMPANHA'] . "' " . $selecionado . " >" . $qrListaGrupoWork['DES_CAMPANHA'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>

                                        <a class="btn btn-default btn-sm" id="iAll_COD_CAMPANHA" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square-o" aria-hidden="true"></i> selecionar todos</a>&nbsp;
                                        <a class="btn btn-default btn-sm" id="iNone_COD_CAMPANHA" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

                                        <script type="text/javascript">
                                            $('#iAll_COD_CAMPANHA').on('click', function(e) {
                                                e.preventDefault();
                                                $('#COD_CAMPANHA option').prop('selected', true).trigger('chosen:updated');
                                            });

                                            $('#iNone_COD_CAMPANHA').on('click', function(e) {
                                                e.preventDefault();
                                                $("#COD_CAMPANHA option:selected").removeAttr("selected").trigger('chosen:updated');
                                            });
                                        </script>
                                    </div>
                                </div>

                                <div class="col-md-3" id="chavecampanha">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Chave Campanha</label>
                                        <select data-placeholder="Selecione a chave de campanha" name="COD_REGISTR[]" id="COD_REGISTR" multiple="multiple" class="chosen-select-deselect" <?= $desabilita ?>>
                                            <?php
                                            $selecionado = "";
                                            if (isset($cod_registr) && $cod_registr == "") {
                                                $selecionado = "selected";
                                            }

                                            ?>
                                            <option value="" <?= $selecionado; ?>>Todas Chaves</option>
                                            <?php
                                            $sql = "SELECT * FROM CAMPANHA_HOTSITE WHERE COD_EMPRESA = $cod_empresa $campanha";
                                            //fnEscreve($sql);
                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            while ($qrListaGrupoWork = mysqli_fetch_assoc($arrayQuery)) {

                                                if (!empty($_REQUEST['COD_REGISTR']) && is_array($_REQUEST['COD_REGISTR'])) {
                                                    if (recursive_array_search($qrListaGrupoWork['COD_UNIVEND_PREF'], array_filter($_REQUEST['COD_REGISTR'])) !== false) {
                                                        $selecionado = "selected";
                                                    } else {
                                                        $selecionado = "";
                                                    }
                                                } else {
                                                    $selecionado = "";
                                                }

                                                echo "<option value='" . $qrListaGrupoWork['COD_UNIVEND_PREF'] . "' " . $selecionado . " >" . $qrListaGrupoWork['DES_CHAVECAMP'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors">Seleciona uma campanha para habilitar o campo.</div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Data Inicial</label>

                                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Data Final</label>

                                        <div class="input-group date datePicker" id="DAT_FIM_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>
                            </div>

                            <input type="hidden" name="opcao" id="opcao" value="">
                            <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
                            <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
                            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                            <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                            <div class="push10"></div>

                        </fieldset>

                        <div class="push20"></div>
                    </form>
                </div>
            </div>
        </div>

        <div class="push30"></div>

        <?php

        // Filtro por Grupo de Lojas
        include "filtroGrupoLojas.php";

        $andCampanha = "";
        if ($cod_campanha != "") {
            $andCampanha = "AND CP.COD_CAMPANHA IN ($cod_campanha)";
        }

        $andData = "";
        if (isset($dat_ini) && $dat_ini != "") {
            $andData = "AND CL.DAT_CADASTR >= '$dat_ini 00:00:00' AND CL.DAT_CADASTR <= '$dat_fim 23:59:59'";
        }

        $sql = "SELECT 
                COUNT(DISTINCT CL.COD_CLIENTE) AS QTD_CLIENTES,
                SUM(CASE
                        WHEN CD.TIP_CREDITO = 'C'
                                AND CD.VAL_CREDITO >0 THEN CD.VAL_CREDITO
                        ELSE 0
                    END) AS TOT_CREDITOS,
                SUM(CASE
                        WHEN CD.TIP_CREDITO = 'D'
                                AND CD.VAL_CREDITO >0 THEN CD.VAL_CREDITO
                        ELSE 0
                    END) AS TOT_DEBITOS,
                SUM(V.VAL_TOTVENDA) AS TOT_VENDAS
            FROM creditosdebitos AS CD
            INNER JOIN clientes AS CL ON CL.COD_CLIENTE = CD.COD_CLIENTE
            LEFT JOIN vendas AS V ON V.COD_CLIENTE = CD.COD_CLIENTE
            AND V.COD_VENDA=CD.COD_VENDA
            WHERE CD.COD_CLIENTE IN
                (SELECT CD.COD_CLIENTE
                FROM campanha AS CP
                INNER JOIN creditosdebitos AS CD ON CD.COD_CAMPANHA = CP.COD_CAMPANHA
                WHERE CP.TIP_CAMPANHA = 23
                AND CD.COD_STATUSCRED != 6
                AND CD.COD_EMPRESA=$cod_empresa
                $andCampanha
                GROUP BY CD.Cod_cliente)
            AND CL.COD_UNIVEND IN ($lojasSelecionadas)
            $andData";

        //fnEscreve($sql);

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

        $qtd_cadastros = 0;
        $tot_saldo = '0,00';
        $tot_debitos = '0,00';
        $tot_vendas = '0,00';
        $vvr = '0,00';

        if ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
            $qtd_cadastros = fnValor($qrLista['QTD_CLIENTES'], 0);
            $tot_saldo = fnValor($qrLista['TOT_CREDITOS'], 2);
            $tot_debitos = fnValor($qrLista['TOT_DEBITOS'], 2);
            $tot_vendas = fnValor($qrLista['TOT_VENDAS'], 2);

            if (($qrLista['TOT_VENDAS'] != 0 && $qrLista['TOT_VENDAS'] != "") && ($qrLista['TOT_CREDITOS'] != 0 && $qrLista['TOT_CREDITOS'] != "")) {
                $vvr = fnValor((($qrLista['TOT_VENDAS'] / $qrLista['TOT_CREDITOS']) - 1) * 100, 2);
            } else {
                $vvr = "0";
            }
        }

        ?>

        <!-- Portlet -->
        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="row text-center">

                    <div class="form-group text-center col-md-1 col-lg-1"></div>

                    <div class="form-group text-center col-md-2 col-lg-2">

                        <div class="push20"></div>

                        <p><span id="QTD_SALDO_EMAIL"><?= $qtd_cadastros ?></span></p>
                        <p><b>Qtd. Cadastros</b></p>

                        <div class="push20"></div>

                    </div>

                    <div class="form-group text-center col-md-2 col-lg-2">

                        <div class="push20"></div>

                        <p><span id="QTD_SALDO_SMS"><?= $tot_saldo ?></span></p>
                        <p><b>Tot. Créditos Concedidos</b></p>

                        <div class="push20"></div>

                    </div>

                    <div class="form-group text-center col-md-2 col-lg-2">

                        <div class="push20"></div>

                        <p><span id="QTD_SALDO_WPP"><?= $tot_debitos ?></span></p>
                        <p><b>Tot. Créditos Resgatados</b></p>

                        <div class="push20"></div>

                    </div>

                    <div class="form-group text-center col-md-2 col-lg-2">

                        <div class="push20"></div>

                        <p><span id="QTD_SALDO_WPP"><?= $tot_vendas ?></span></p>
                        <p><b>Tot. Vendas</b></p>

                        <div class="push20"></div>

                    </div>

                    <div class="form-group text-center col-md-2 col-lg-2">

                        <div class="push20"></div>

                        <p><span id="QTD_SALDO_WPP"><?= $vvr ?>%</span></p>
                        <p><b>VVR %</b></p>

                        <div class="push20"></div>

                    </div>

                    <div class="form-group text-center col-md-1 col-lg-1"></div>

                </div>


            </div>

        </div>

        <div class="push30"></div>

        <div class="portlet portlet-bordered">

            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12" id="div_Produtos">

                        <div class="push20"></div>

                        <table class="table table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th><small>Código</small></th>
                                    <th><small>Cliente</small></th>
                                    <th class="text-right"><small>Créditos Ganhos</small></th>
                                    <th class="text-right"><small>Créditos Resgatados</small></th>
                                    <th class="text-right"><small>Total de Compras</small></th>
                                    <th><small>Campanha</small></th>
                                    <th><small>Chave Campanha</small></th>
                                    <th><small>Data de Cadastro</small></th>
                                </tr>
                            </thead>

                            <tbody id="relatorioConteudo">

                                <?php
                                $sql = "SELECT 
                                                1
                                            FROM creditosdebitos AS CD
                                            INNER JOIN clientes AS CL ON CL.COD_CLIENTE = CD.COD_CLIENTE
                                            LEFT JOIN vendas AS V ON V.COD_CLIENTE = CD.COD_CLIENTE  AND  V.COD_VENDA=CD.COD_VENDA
                                            WHERE 
                                            CD.COD_CLIENTE IN (SELECT 
                                                                CD.COD_CLIENTE   
                                                            FROM campanha AS CP
                                                            INNER JOIN creditosdebitos AS CD ON CD.COD_CAMPANHA = CP.COD_CAMPANHA
                                                            WHERE CP.TIP_CAMPANHA = 23
                                                            AND CD.COD_STATUSCRED != 6
                                                            AND CD.COD_EMPRESA=$cod_empresa
                                                            $andCampanha
                                                            GROUP BY CD.Cod_cliente)
                                            AND CL.COD_UNIVEND IN ($lojasSelecionadas)
                                            $andData
                                            GROUP BY CD.COD_CLIENTE";

                                $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                $totalitens_por_pagina = mysqli_num_rows($retorno);

                                // fnescreve($sql);
                                $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                // fnEscreve($numPaginas);
                                //variavel para calcular o início da visualização com base na página atual
                                $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                $sql = "SELECT 
                                                CL.COD_CLIENTE,
                                                CL.NOM_CLIENTE,
                                                CL.DAT_CADASTR,
                                                CD.COD_CAMPANHA,
                                                CL.COD_UNIVEND,
                                                SUM(CASE WHEN CD.TIP_CREDITO = 'C' AND CD.VAL_CREDITO >0 THEN CD.VAL_CREDITO ELSE 0 END) AS TOT_CREDITOS,
                                                SUM(CASE WHEN CD.TIP_CREDITO = 'D' AND CD.VAL_CREDITO >0 THEN CD.VAL_CREDITO ELSE 0 END) AS TOT_DEBITOS,
                                                SUM(V.VAL_TOTVENDA) AS TOT_VENDAS
                                            FROM creditosdebitos AS CD
                                            INNER JOIN clientes AS CL ON CL.COD_CLIENTE = CD.COD_CLIENTE
                                            LEFT JOIN vendas AS V ON V.COD_CLIENTE = CD.COD_CLIENTE  AND  V.COD_VENDA=CD.COD_VENDA
                                            WHERE 
                                            CD.COD_CLIENTE IN (SELECT 
                                                                CD.COD_CLIENTE   
                                                            FROM campanha AS CP
                                                            INNER JOIN creditosdebitos AS CD ON CD.COD_CAMPANHA = CP.COD_CAMPANHA
                                                            WHERE CP.TIP_CAMPANHA = 23
                                                            AND CD.COD_STATUSCRED != 6
                                                            AND CD.COD_EMPRESA=$cod_empresa
                                                            $andCampanha
                                                            GROUP BY CD.Cod_cliente)
                                            AND CL.COD_UNIVEND IN ($lojasSelecionadas)
                                            $andData
                                            GROUP BY CD.COD_CLIENTE limit $inicio,$itens_por_pagina ";

                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

                                    $sqlCampanha = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_CAMPANHA = " . $qrListaVendas['COD_CAMPANHA'] . " AND COD_EMPRESA = $cod_empresa";
                                    $queryCamp = mysqli_query(connTemp($cod_empresa, ''), $sqlCampanha);

                                    $nom_camp = "";
                                    if ($qrResult = mysqli_fetch_assoc($queryCamp)) {
                                        $nom_camp = $qrResult['DES_CAMPANHA'];
                                    }

                                    $sqlChaveCamp = "SELECT DES_CHAVECAMP FROM CAMPANHA_HOTSITE WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = " . $qrListaVendas['COD_CAMPANHA'] . " AND COD_UNIVEND_PREF = " . $qrListaVendas['COD_UNIVEND'];
                                    $queryChave = mysqli_query(connTemp($cod_empresa, ''), $sqlChaveCamp);

                                    $des_chave = "";
                                    if ($qrChave = mysqli_fetch_assoc($queryChave)) {
                                        $des_chave = $qrChave['DES_CHAVECAMP'];
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $qrListaVendas['COD_CLIENTE']; ?></td>
                                        <td><a href='action.do?mod=<?= fnEncode(1024) ?>&id=<?= fnEncode($cod_empresa) ?>&idC=<?= fnEncode($qrListaVendas['COD_CLIENTE']) ?>' target='_blank'><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
                                        <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['TOT_CREDITOS'], 2); ?></small></td>
                                        <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['TOT_DEBITOS'], 2); ?></small></td>
                                        <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['TOT_VENDAS'], 2); ?></small></td>
                                        <td><?php echo $nom_camp; ?></td>
                                        <td><?php echo $des_chave; ?></td>
                                        <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
                                    </tr>
                                <?php
                                }

                                ?>

                            </tbody>
                            <tfoot>

                                <tr>
                                    <th colspan="100">
                                        <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="" colspan="100">
                                        <center>
                                            <ul id="paginacao" class="pagination-sm"></ul>
                                        </center>
                                    </th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="push5"></div>

        <div class="push50"></div>

        <div class="push"></div>

    </div>
</div>

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
    $(document).ready(function() {
        var numPaginas = <?php echo $numPaginas; ?>;
        if (numPaginas != 0) {
            carregarPaginacao(numPaginas);
        }

        $('#DAT_INI_GRP, #DAT_FIM_GRP').datetimepicker({
            format: 'DD/MM/YYYY'
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
        });

        $("#DAT_INI_GRP").on("dp.change", function(e) {
            $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
        });

        $("#DAT_FIM_GRP").on("dp.change", function(e) {
            $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
        });

        $('#COD_CAMPANHA').change(function() {
            let campanha = $(this).val();
            if (campanha != "" && campanha != null) {
                $.ajax({
                    type: "POST",
                    url: "relatorios/ajxRelCadastrosqrCode.do?opcao=comboChave&id=<?php echo fnEncode($cod_empresa); ?>&idc=" + campanha, // Corrigido o erro de '?id=' para '&idc='
                    data: $('#formulario').serialize(),
                    beforeSend: function() {
                        $('#chavecampanha').html('<div class="loading" style="width: 100%;"></div>');
                    },
                    success: function(data) {
                        console.log(data);
                        $("#chavecampanha").html(data);
                    },
                    error: function() {
                        $('#chavecampanha').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> nenhum registro encontrado...</p>');
                    }
                });
            }
        });


        $(".exportarCSV").click(function() {
            $.confirm({
                title: 'Exportação',
                content: '' +
                    '<form action="" class="formName">' +
                    '<div class="form-group">' +
                    '<label>Insira o nome do arquivo:</label>' +
                    '<input type="text" placeholder="Nome" class="nome form-control" required />' +
                    '</div>' +
                    '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Gerar',
                        btnClass: 'btn-blue',
                        action: function() {
                            var nome = this.$content.find('.nome').val();
                            if (!nome) {
                                $.alert('Por favor, insira um nome');
                                return false;
                            }

                            $.confirm({
                                title: 'Mensagem',
                                type: 'green',
                                icon: 'fal fa-check-square-o',
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        url: "relatorios/ajxRelCadastrosqrCode.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        console.log(response);
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        //console.log(response);
                                    }).fail(function() {
                                        self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                                    });
                                },
                                buttons: {
                                    fechar: function() {
                                        //close
                                    }
                                }
                            });
                        }
                    },
                    cancelar: function() {
                        //close
                    },
                }
            });
        });
    });

    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxRelCadastrosqrCode.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
            data: $('#formulario').serialize(),
            beforeSend: function() {
                $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                console.log(data);
                $("#relatorioConteudo").html(data);
            },
            error: function() {
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }
</script>