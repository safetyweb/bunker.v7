<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$hoje = "";
$dias30 = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_campanha = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_persona = "";
$qrCamp = "";
$andData = "";
$andCampanha = "";
$tot_campanhas = "";
$qrCampanhasEmail = "";
$contatos_graph = "";
$sucesso_graph = "";
$nrecebidos_graph = "";
$optout_graph = "";
$falha_graph = "";
$cancelado = "";
$aguardo_graph = "";
$perc_sucesso = "";
$perc_nrecebidos = "";
$perc_optout = "";
$perc_falha = "";
$perc_aguardo = "";
$perc_cancelado = "";
$dat_envio = "";
$tot_qtd = 0;
$tot_sucesso = "";
$tot_nrecebidos = "";
$tot_optout = "";
$tot_falha = "";
$tot_aguardo = "";
$tot_cancelado = "";
$content = "";


//inicialização de variáveis


//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode(@$_GET['id']);

$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
        $cod_campanha = fnLimpaCampoZero(@$_REQUEST['COD_CAMPANHA']);

        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '' && $opcao != 0) {
        }
    }
}

//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

//inicialização das variáveis - default 
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = fnDataSql($dias30);
    $dat_ini = fnmesanosql($dat_ini) . "-01";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = fnDataSql($hoje);
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnEscreve($cod_empresa); 	
//fnEscreve($cod_persona); 	
//fnMostraForm();
?>


<style>
    input[type="search"]::-webkit-search-cancel-button {
        height: 16px;
        width: 16px;
        background: url(images/close-filter.png) no-repeat right center;
        position: relative;
        cursor: pointer;
    }

    input.tableFilter {
        border: 0px;
        background-color: #fff;
    }

    table a:not(.btn),
    .table a:not(.btn) {
        text-decoration: none;
    }

    table a:not(.btn):hover,
    .table a:not(.btn):hover {
        text-decoration: underline;
    }

    .no-weight tr td {
        font-weight: normal !important;
    }

    .bold {
        font-weight: bold;
    }

    .overflown {
        overflow-x: scroll;
    }
</style>

<div class="push30"></div>

<div class="row">

    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

        <div class="col-md12 margin-bottom-30">
            <!-- Portlet -->
            <div class="portlet portlet-bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fal fa-terminal"></i>
                        <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?> </span>
                    </div>
                    <?php include "atalhosPortlet.php"; ?>
                </div>
                <div class="portlet-body">

                    <?php if ($msgRetorno <> '') { ?>
                        <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $msgRetorno; ?>
                        </div>
                    <?php } ?>

                    <div class="login-form">

                        <fieldset>
                            <legend>Filtros</legend>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Data Inicial do Envio</label>

                                            <div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
                                                <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= fnDataShort($dat_ini) ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Data Final do Envio</label>

                                            <div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
                                                <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?= fnDataShort($dat_fim) ?>" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">

                                        <label for="inputName" class="control-label">Campanha</label>
                                        <select data-placeholder="Selecione a campanha" name="COD_CAMPANHA" id="COD_CAMPANHA" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <?php
                                            $sql = "SELECT DISTINCT CP.COD_CAMPANHA, CP.DES_CAMPANHA FROM CAMPANHA CP
                                  INNER JOIN SMS_LOTE SL ON SL.COD_CAMPANHA = CP.COD_CAMPANHA
                                  WHERE CP.COD_EMPRESA = $cod_empresa";
                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            while ($qrCamp = mysqli_fetch_assoc($arrayQuery)) {
                                            ?>

                                                <option value="<?= $qrCamp['COD_CAMPANHA'] ?>"><?= $qrCamp['DES_CAMPANHA'] ?></option>

                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                        <script type="text/javascript">
                                            $("#formulario #COD_CAMPANHA").val('<?= $cod_campanha ?>').trigger("chosen:updated");
                                        </script>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>

                            </div>

                        </fieldset>
                    </div>
                </div>
            </div>

            <div class="push30"></div>

            <?php include 'includeBlocoSaldoComunicacao.php'; ?>

            <div class="push30"></div>

            <div class=" portlet portlet-bordered">

                <div class="portled-body">

                    <div class="login-form">

                        <div class="push30"></div>

                        <div>
                            <div class="row">
                                <div class="col-md-12 overflown">

                                    <div class="no-more-tables">

                                        <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">

                                            <thead>
                                                <tr>
                                                    <th class="{ sorter: false }"></th>
                                                    <th>Campanha</th>
                                                    <th>Data de Envio</th>
                                                    <th class="text-right">Lista de Envio</th>
                                                    <th class="text-right">Entregues</th>
                                                    <th class="text-right">Entregues S Confirm</th>
                                                    <th class="text-right">Opt Out</th>
                                                    <th class="text-right">Falhas </th>
                                                    <th class="text-right">Em Aguardo </th>
                                                    <th class="text-right">Cancelados</th>
                                                    <th class="{ sorter: false }"></th>
                                                </tr>
                                            </thead>

                                            <tbody id="listaTemplates">

                                                <?php
                                                // MAURICE PEDIU PRA TRAZER OS TESTES DIA 05/11/2021 15:00 - SALA DEV
                                                // $andData = "AND (EL.DAT_AGENDAMENTO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59')";
                                                //$andData = "AND case when EL.DAT_AGENDAMENTO BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' then '1'
                                                //                    when EL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND EL.LOG_TESTE = 'S' then '2'

                                                //                  ELSE '0' END IN (1,2)";

                                                //ERRO NA DUPLICAÇÃO DA CAMPANHA DE TOKEN 01/04/2024 Diogo
                                                $andData = "AND EL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'";

                                                if ($cod_campanha != 0 && $cod_campanha != '') {
                                                    $andCampanha = "AND EL.COD_CAMPANHA = $cod_campanha";
                                                    //$andData = "";
                                                } else {
                                                    $andCampanha = "";
                                                }

                                                $sql = "SELECT
            CEM.COD_DISPARO,
            SUM(EL.QTD_LISTA) AS QTD_LISTA,
            SUM(CEM.QTD_SUCESSO) + SUM(CEM.QTD_NRECEBIDO) AS QTD_SUCESSO,
            '0' AS QTD_NRECEBIDO,
            SUM(CEM.QTD_EXCLUSAO) AS QTD_OPTOUT,
            SUM(CEM.QTD_FALHA) AS QTD_FALHA,
            SUM(CEM.QTD_AGUARADANDO) AS QTD_AGUARDANDO,
            SUM(CEM.CANCELADO) AS CANCELADO,
            CP.COD_CAMPANHA,
            TE.NOM_TEMPLATE,
            EL.DES_PATHARQ,
            EL.COD_GERACAO,
            EL.COD_CONTROLE,
            EL.COD_LOTE,
            CP.DES_CAMPANHA,
            MAX(EL.DAT_AGENDAMENTO) AS DAT_ENVIO
          FROM SMS_LOTE EL
          LEFT JOIN CONTROLE_ENTREGA_SMS CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO 
                                            AND CEM.cod_empresa=EL.COD_EMPRESA 
                                            AND CEM.cod_campanha=EL.COD_CAMPANHA
                                            AND CEM.LOG_TESTE=EL.LOG_TESTE
          LEFT JOIN TEMPLATE_SMS TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE
          LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = EL.COD_CAMPANHA
          WHERE EL.COD_EMPRESA = $cod_empresa 
          AND EL.LOG_ENVIO = 'S'
          $andCampanha
          $andData
          GROUP BY EL.COD_CAMPANHA
          ORDER BY EL.COD_CONTROLE DESC 
            ";

                                                //fnEscreve($sql);

                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                $tot_campanhas = mysqli_num_rows($arrayQuery);

                                                $count = 0;
                                                while ($qrCampanhasEmail = mysqli_fetch_assoc($arrayQuery)) {
                                                    $count++;

                                                    $contatos_graph = $qrCampanhasEmail['QTD_LISTA'];
                                                    $sucesso_graph = $qrCampanhasEmail['QTD_SUCESSO'];
                                                    $nrecebidos_graph = $qrCampanhasEmail['QTD_NRECEBIDO'];
                                                    $optout_graph = $qrCampanhasEmail['QTD_OPTOUT'];
                                                    $falha_graph = $qrCampanhasEmail['QTD_FALHA'];
                                                    $cancelado = $qrCampanhasEmail['CANCELADO'];
                                                    $aguardo_graph = $qrCampanhasEmail['QTD_AGUARDANDO'];

                                                    $perc_sucesso = fnValorSql(fnValor(($sucesso_graph / $contatos_graph) * 100, 2));
                                                    $perc_nrecebidos = fnValorSql(fnValor(($nrecebidos_graph / $contatos_graph) * 100, 2));
                                                    $perc_optout = fnValorSql(fnValor(($optout_graph / $contatos_graph) * 100, 2));
                                                    $perc_falha = fnValorSql(fnValor(($falha_graph / $contatos_graph) * 100, 2));
                                                    $perc_aguardo = fnValorSql(fnValor(($aguardo_graph / $contatos_graph) * 100, 2));
                                                    $perc_cancelado = fnValorSql(fnValor(($cancelado / $contatos_graph) * 100, 2));

                                                    if ($qrCampanhasEmail['DAT_ENVIO'] == "") {
                                                        $dat_envio = "Em Andamento";
                                                    } else {
                                                        $dat_envio = fnDataFull($qrCampanhasEmail['DAT_ENVIO']);
                                                    }

                                                    echo "
                                  <tr id='CAMPANHA_" . $qrCampanhasEmail['COD_CAMPANHA'] . "'>                              
                                    <td class='text-center'><a href='javascript:void(0);' onclick='abreDetail(" . $qrCampanhasEmail['COD_CAMPANHA'] . ")'><i class='fal fa-angle-right' aria-hidden='true'></i></a></td>
                                    <td><small><small>" . $qrCampanhasEmail['COD_CAMPANHA'] . "</small>&nbsp;" . $qrCampanhasEmail['DES_CAMPANHA'] . "</small></td>
                                    <td><small><small>" . $dat_envio . "</small></small></td>
                                    <td class='text-right bold'><small>" . fnValor($contatos_graph, 0) . "</small></td>
                                    <td class='text-right bold'><small>" . fnValor($sucesso_graph, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_sucesso, 2) . "%</span></small></td>
                                    <td class='text-right'><small>" . fnValor($nrecebidos_graph, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_nrecebidos, 2) . "%</span></small></td>
                                    <td class='text-right bold'><small>" . fnValor($optout_graph, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_optout, 2) . "%</span></small></td>
                                    <td class='text-right bold'><small>" . fnValor($falha_graph, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_falha, 2) . "%</span></small></td>
                                    <td class='text-right bold'><small>" . fnValor($aguardo_graph, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_aguardo, 2) . "%</span></small></td>
                                    <td class='text-right bold'><small>" . fnValor($cancelado, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_cancelado, 2) . "%</span></small></td>
                                    <td></td>
                                  </tr>                      
                                    ";

                                                    echo "
                                  <thead class='no-weight' style='display:none; background-color: #fff;' id='abreDetail_" . $qrCampanhasEmail['COD_CAMPANHA'] . "'>
                                   
                                  </thead>                             
                                  ";

                                                    $tot_qtd += $qrCampanhasEmail['QTD_LISTA'];
                                                    $tot_sucesso += $qrCampanhasEmail['QTD_SUCESSO'];
                                                    $tot_nrecebidos += $qrCampanhasEmail['QTD_NRECEBIDO'];
                                                    $tot_optout += $qrCampanhasEmail['QTD_OPTOUT'];
                                                    $tot_falha += $qrCampanhasEmail['QTD_FALHA'];
                                                    $tot_aguardo += $qrCampanhasEmail['QTD_AGUARDANDO'];
                                                    $tot_cancelado += $qrCampanhasEmail['CANCELADO'];
                                                }
                                                ?>

                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    <td>Total Campanhas: <b><?= fnValor($tot_campanhas, 0) ?></b></td>
                                                    <td></td>
                                                    <td class="text-right"><b><?= fnValor($tot_qtd, 0) ?></b></td>
                                                    <td class="text-right"><b><?= fnValor($tot_sucesso, 0) ?></b></td>
                                                    <td class="text-right"><b><?= fnValor($tot_nrecebidos, 0) ?></b></td>
                                                    <td class="text-right"><b><?= fnValor($tot_optout, 0) ?></b></td>
                                                    <td class="text-right"><b><?= fnValor($tot_falha, 0) ?></b></td>
                                                    <td class="text-right"><b><?= fnValor($tot_aguardo, 0) ?></b></td>
                                                    <td class="text-right"><b><?= fnValor($tot_cancelado, 0) ?></b></td>
                                                </tr>
                                                <tr>
                                                    <th colspan="100">
                                                        <!-- <a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)" value="N"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a> &nbsp;&nbsp; -->
                                                        <!-- <a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)" value="S"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar Detalhes</a> &nbsp;&nbsp; -->
                                                    </th>
                                                </tr>
                                            </tfoot>

                                        </table>


                                        <div class="push"></div>

                                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
                                        <input type="hidden" name="opcao" id="opcao" value="">
                                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">


                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="push"></div>

                    </div>

                </div>
            </div>
            <!-- fim Portlet -->
        </div>
    </form>
</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
    $(document).ready(function() {

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

        //chosen obrigatório
        $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        $('#formulario').validator();

    });

    //table sorter
    $(function() {
        var tabelaFiltro = $('table.tablesorter')
        tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function() {
            $(this).prev().find(":checkbox").click()
        });
        $("#filter").keyup(function() {
            $.uiTableFilter(tabelaFiltro, this.value);
        })
        $('#formLista').submit(function() {
            tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
            return false;
        }).focus();
    });

    //pesquisa table sorter
    $('.filter-all').on('input', function(e) {
        if ('' == this.value) {
            var lista = $("#filter").find("ul").find("li");
            filtrar(lista, "");
        }
    });

    function exportarCSV(btn) {
        log_detalhes = $(btn).attr('value');
        // alert(id);
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
                            icon: 'fa fa-check-square',
                            content: function() {
                                var self = this;
                                return $.ajax({
                                    url: "relatorios/ajxRelComunicaSms_V2.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&detalhes=" + log_detalhes,
                                    data: $('#formulario').serialize(),
                                    method: 'POST'
                                }).done(function(response) {
                                    self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                    var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                    SaveToDisk('media/excel/' + fileName, fileName);
                                    console.log(response);
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
    }

    function reprocessaDisparo(idCampanha, idDisparo, btn) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxReprocessaNexux.do",
            data: {
                COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
                COD_CAMPANHA: idCampanha,
                COD_DISPARO: idDisparo
            },
            beforeSend: function() {
                $(btn).html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                console.log(data);
                $(btn).html('<span class="fal fa-cogs"></div>');
                $.alert({
                    title: "Aviso",
                    content: "Disparo reprocessado com sucesso.",
                    type: 'green'
                });
            },
            error: function(data) {
                $("#abreDetail_" + idCampanha).html(data);
                // $("#mostraDetail_"+idCampanha).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
            }
        });
    }

    function abreDetail(idCampanha) {
        RefreshCampanha(<?php echo $cod_empresa; ?>, idCampanha);
    }

    function reprocessaDisparo(idCampanha, idDisparo, btn) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxReprocessaNexux.do",
            data: {
                COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
                COD_CAMPANHA: idCampanha,
                COD_DISPARO: idDisparo
            },
            beforeSend: function() {
                $(btn).html('Processando...');
            },
            success: function(data) {
                console.log(data);
                $(btn).html('<span class="fal fa-cogs"></div>');
                RefreshCampanha("<?= $cod_empresa ?>", idCampanha);
                $.alert({
                    title: "Aviso",
                    content: "Disparo reprocessado com sucesso.",
                    type: 'green'
                });
            },
            error: function(data) {
                $("#abreDetail_" + idCampanha).html(data);
                // $("#mostraDetail_"+idCampanha).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
            }
        });
    }

    function RefreshCampanha(idEmp, idCampanha) {
        var idItem = $('#abreDetail_' + idCampanha);
        //console.log(idItem);

        if (!idItem.is(':visible')) {
            $.ajax({
                type: "POST",
                url: "relatorios/ajxRelComunicaSms_V2.do",
                data: {
                    COD_EMPRESA: idEmp,
                    COD_CAMPANHA: idCampanha,
                    DATA: "<?= fnEncode($andData) ?>"
                },
                beforeSend: function() {
                    $("#abreDetail_" + idCampanha).html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    $("#abreDetail_" + idCampanha).html(data);
                    console.log(data);
                },
                error: function(data) {
                    $("#abreDetail_" + idCampanha).html(data);
                    // $("#mostraDetail_"+idCampanha).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
                }
            });

            idItem.show();

            $('#CAMPANHA_' + idCampanha).find($(".fa-angle-right")).removeClass('fa-angle-right').addClass('fa-angle-down');
        } else {
            idItem.hide();
            $('#CAMPANHA_' + idCampanha).find($(".fa-angle-down")).removeClass('fa-angle-down').addClass('fa-angle-right');
        }
    }
</script>