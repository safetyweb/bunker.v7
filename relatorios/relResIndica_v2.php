<?php

//echo fnDebug('true');

$itens_por_pagina = 50;
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
        $cod_univend = $_POST['COD_UNIVEND'];
        $dat_ini = fnDataSql($_POST['DAT_INI']);
        $dat_fim = fnDataSql($_POST['DAT_FIM']);
        $cod_grupotr = $_REQUEST['COD_GRUPOTR'];
        $cod_tiporeg = $_REQUEST['COD_TIPOREG'];

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
    $cod_campanha = fnDecode($_GET['idc']);
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

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($lojasReportAdm);

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
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"> <?php echo $NomePg; ?></span>
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
                                        <label for="inputName" class="control-label required">Empresa</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
                                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Unidade de Atendimento</label>
                                        <?php include "unidadesAutorizadasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Grupo de Lojas</label>
                                        <?php include "grupoLojasComboMulti.php"; ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Região</label>
                                        <?php include "grupoRegiaoMulti.php"; ?>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Inicial</label>

                                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Final</label>

                                        <div class="input-group date datePicker" id="DAT_FIM_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>


                            </div>

                        </fieldset>

                        <div class="push20"></div>

                        <div>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="push20"></div>

                                    <?php
                                    // Filtro por Grupo de Lojas
                                    include "filtroGrupoLojas.php";

                                    ?>

                                    <div class="push10"></div>

                                    <table class="table table-bordered table-hover tablesorter">

                                        <thead>
                                            <tr>
                                                <th>Loja</th>
                                                <th>Data de Cadastro</th>
                                                <th>Qtd. Clientes Indicados</th>
                                            </tr>
                                        </thead>

                                        <tbody id="relatorioConteudo">

                                            <?php

                                            include "filtroGrupoLojas.php";

                                            $sql = "SELECT UNV.NOM_FANTASI,
                                                        UNV.COD_UNIVEND,
												        COUNT(CI.COD_CLIENTE) QTD_CLIENTES_IDICA 
                                                        FROM unidadevenda AS UNV
                                                        INNER JOIN clientes_indicados AS CI ON UNV.COD_UNIVEND = CI.COD_UNIVEND
                                                        WHERE
                                                        CI.DAT_CADASTR >='$dat_ini 00:00:00' 
                                                        AND CI.DAT_CADASTR <='$dat_fim 23:59:59'
                                                        AND UNV.cod_empresa=$cod_empresa
                                                        AND UNV.COD_UNIVEND IN($lojasSelecionadas)
                                                        GROUP BY UNV.COD_UNIVEND
                                                        ORDER BY UNV.NOM_FANTASI ASC
												";

                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                            $count = 0;
                                            while ($qrCupom = mysqli_fetch_assoc($arrayQuery)) {

                                                $count++;
                                                echo "
												<tr>
                                                  <td colspan='1'><b>" . $qrCupom['NOM_FANTASI'] . "</b></td>
												  <td></td>
												  <td>" . $qrCupom['QTD_CLIENTES_IDICA'] . "</td>
												</tr>
												";

                                                $sqlIndica = "SELECT COUNT(CI.COD_INDICAD) AS QTD_INDICA, CL.NOM_CLIENTE, CL.COD_CLIENTE, CI.COD_INDICAD, CL.DAT_CADASTR FROM clientes_indicados AS CI
                                                LEFT JOIN CLIENTES AS CL ON CI.COD_INDICAD = CL.COD_CLIENTE
                                                WHERE CI.COD_UNIVEND = " . $qrCupom['COD_UNIVEND'] . " 
                                                AND CI.COD_EMPRESA = $cod_empresa
                                                AND CI.DAT_CADASTR >= '$dat_ini 00:00:00' 
                                                AND CI.DAT_CADASTR <= '$dat_fim 23:59:59' 
                                                GROUP BY CI.COD_INDICAD";

                                                $queryIndica = mysqli_query(connTemp($cod_empresa, ''), $sqlIndica);

                                                while ($qrIndica = mysqli_fetch_assoc($queryIndica)) {
                                                    if ($qrIndica['NOM_CLIENTE'] != '') {
                                                        echo "
                                                            <tr>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style='color: inherit;' href='action.php?mod=" . fnEncode(1081) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrIndica['COD_CLIENTE']) . "' target='_blank'><small>&bull; <b>" . $qrIndica['COD_CLIENTE'] . "</small> " . $qrIndica['NOM_CLIENTE'] . "</b></a></td>
                                                            <td><small> " . fnDataShort($qrIndica['DAT_CADASTR']) . "</small></td>
                                                            <td><b>" . $qrIndica['QTD_INDICA'] . "</b></td>
                                                            </tr>
                                                        ";

                                                        $sqlIndicado = "SELECT CL.NOM_CLIENTE, CL.COD_CLIENTE, CL.DAT_CADASTR FROM clientes_indicados AS CI
                                                        INNER JOIN CLIENTES AS CL ON CI.COD_CLIENTE = CL.COD_CLIENTE
                                                        WHERE CI.COD_UNIVEND = " . $qrCupom['COD_UNIVEND'] . " 
                                                        AND CI.COD_EMPRESA = $cod_empresa
                                                        AND CI.COD_INDICAD = " . $qrIndica['COD_CLIENTE'] . "
                                                        AND CI.DAT_CADASTR >= '$dat_ini 00:00:00' 
                                                        AND CI.DAT_CADASTR <= '$dat_fim 23:59:59' 
                                                        GROUP BY CI.COD_INDICAD";
                                                        // fnEscreve($sqlIndicado);
                                                        $queryIndicado = mysqli_query(connTemp($cod_empresa, ''), $sqlIndicado);

                                                        while ($qrIndicado = mysqli_fetch_assoc($queryIndicado)) {
                                                            echo "
                                                                <tr>
                                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style='color: inherit;' href='action.php?mod=" . fnEncode(1081) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrIndicado['COD_CLIENTE']) . "' target='_blank'><small><b>" . $qrIndicado['COD_CLIENTE'] . "</small> " . $qrIndicado['NOM_CLIENTE'] . "</b></a></td>
                                                                <td><small> " . fnDataShort($qrIndicado['DAT_CADASTR']) . "</small></td>
                                                                <td></td>
                                                                </tr>
                                                            ";
                                                        }
                                                    } else {

                                                        echo "
                                                        <tr>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><small>" . $qrIndica['COD_INDICAD'] . "</small> Cliente Invalido</b> <small><span class='label' style='background-color: #d98880'><span style='color: #fff'>Invalido</span></span></small></td>
                                                        <td></td>
                                                        <td>" . $qrIndica['QTD_INDICA'] . "</td>
                                                        </tr>
                                                        ";

                                                        $sqlIndicado = "SELECT CL.NOM_CLIENTE, CL.COD_CLIENTE, CL.DAT_CADASTR FROM clientes_indicados AS CI
                                                        INNER JOIN CLIENTES AS CL ON CI.COD_CLIENTE = CL.COD_CLIENTE
                                                        WHERE CI.COD_UNIVEND = " . $qrCupom['COD_UNIVEND'] . " 
                                                        AND CI.COD_EMPRESA = $cod_empresa
                                                        AND CI.COD_INDICAD = " . $qrIndica['COD_INDICAD'] . "
                                                        AND CI.DAT_CADASTR >= '$dat_ini 00:00:00' 
                                                        AND CI.DAT_CADASTR <= '$dat_fim 23:59:59' 
                                                        GROUP BY CI.COD_INDICAD";
                                                        // fnEscreve($sqlIndicado);
                                                        $queryIndicado = mysqli_query(connTemp($cod_empresa, ''), $sqlIndicado);

                                                        while ($qrIndicado = mysqli_fetch_assoc($queryIndicado)) {
                                                            echo "
                                                                <tr>
                                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style='color: inherit;' href='action.php?mod=" . fnEncode(1081) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrIndicado['COD_CLIENTE']) . "' target='_blank'><small><b>" . $qrIndicado['COD_CLIENTE'] . "</small> " . $qrIndicado['NOM_CLIENTE'] . "</b></a></td>
                                                                <td><small> " . fnDataShort($qrIndicado['DAT_CADASTR']) . "</small></td>
                                                                <td></td>
                                                                </tr>
                                                            ";
                                                        }
                                                    }
                                                }
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

                        <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                        <div class="push5"></div>

                    </form>

                    <div class="push50"></div>

                    <div class="push"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
    //datas
    $(function() {

        //var numPaginas = <?php echo $numPaginas; ?>;
        //if (numPaginas != 0) {
        //    carregarPaginacao(numPaginas);
        //}

        $('.datePicker').datetimepicker({
            format: 'DD/MM/YYYY',
            maxDate: 'now',
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
        });

        $("#DAT_INI_GRP").on("dp.change", function(e) {
            $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
        });

        $("#DAT_FIM_GRP").on("dp.change", function(e) {
            $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
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
                                        url: "relatorios/ajxRelResIndica_v2.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
</script>