<?php
//echo fnDebug('true');
//inicialização de variáveis
$dias30 = "";
$dat_ini = "";
$dat_fim = "";

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);

$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
        $cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);

        $dat_ini = fnDataSql($_POST['DAT_INI']);
        $dat_fim = fnDataSql($_POST['DAT_FIM']);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {
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

$andData = "AND DATE(ret.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'";

if ($cod_campanha != 0) {
    $andCampanha = "AND ret.COD_CAMPANHA = $cod_campanha";
    //$andData = "";
} else {
    $andCampanha = "";
}
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Unidade de Atendimento</label>
                                        <?php include "unidadesAutorizadasComboMulti.php"; ?>
                                    </div>
                                </div>

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

                                                <option value="<?= $qrCamp[COD_CAMPANHA] ?>"><?= $qrCamp['DES_CAMPANHA'] ?></option>

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

            <div class="push20"></div>

            <div class=" portlet portlet-bordered">

                <div class="portled-body">

                    <div class="login-form">

                        <div class="push30"></div>

                        <div>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="no-more-tables">

                                        <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">

                                            <thead>
                                                    <tr>
                                                        <th class="{ sorter: false }"></th>
                                                        <th>Loja</th>
                                                        <th>Data de Envio</th>
                                                        <th class="text-right">Lista de Envio</th>
                                                        <th class="text-right">Entregues</th>
                                                        <th class="text-right">Não Recebidos</th>
                                                        <th class="text-right">Opt Out</th>
                                                        <th class="text-right">Falhas </th>
                                                        <th class="text-right">Em Aguardo </th>
                                                    </tr>
                                            </thead>

                                            <tbody id="listaTemplates">

                                                <?php

                                                $sql = "SELECT 
                                                            uni.NOM_FANTASI, 
                                                            uni.COD_UNIVEND,
                                                            ret.COD_CAMPANHA,
                                                            CP.DAT_AGENDAMENTO DAT_ENVIO,
                                                            SUM(CASE WHEN ret.COD_OPTOUT_ATIVO='1' THEN '1' ELSE '0' END) COD_OPTOUT_ATIVO, 
                                                            SUM(CASE WHEN ret.BOUNCE='1' THEN '1' ELSE '0' END) BOUNCE, 
                                                            SUM(CASE WHEN ret.COD_NRECEBIDO='1' THEN '1' ELSE '0' END) COD_NRECEBIDO, 
                                                            SUM(CASE WHEN ret.COD_CCONFIRMACAO='1' THEN '1' ELSE '0' END) COD_CCONFIRMACAO, 
                                                            SUM(CASE WHEN ret.COD_CCONFIRMACAO='0' THEN '1' WHEN ret.COD_NRECEBIDO='0' THEN '1' WHEN ret.BOUNCE='0' THEN '1' WHEN ret.COD_OPTOUT_ATIVO='0' THEN '1' ELSE '1' END) SUB_TOTAL
                                                        FROM unidadevenda uni
                                                        INNER JOIN sms_lista_ret ret ON ret.COD_UNIVEND = uni.COD_UNIVEND
                                                        INNER JOIN SMS_LOTE CP ON CP.COD_CAMPANHA = ret.COD_CAMPANHA AND CP.COD_DISPARO_EXT = ret.ID_DISPARO
                                                        WHERE ret.CHAVE_CLIENTE IS NOT NULL
                                                        $andCampanha 
                                                        AND uni.COD_EMPRESA = $cod_empresa 
                                                        AND DATE(ret.dat_cadastr) 
                                                        BETWEEN '$dat_ini' AND '$dat_fim' 
                                                        AND uni.COD_UNIVEND IN($lojasSelecionadas)
                                                        GROUP BY uni.cod_univend 
                                                        ORDER BY  uni.cod_univend ASC";

                                                //fnEscreve($sql);

                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                $tot_campanhas = mysqli_num_rows($arrayQuery);

                                                $count = 0;
                                                while ($qrCampanhasEmail = mysqli_fetch_assoc($arrayQuery)) {
                                                    $count++;

                                                    $contatos_graph = $qrCampanhasEmail[SUB_TOTAL];
                                                    $sucesso_graph = $qrCampanhasEmail[COD_CCONFIRMACAO];
                                                    $nrecebidos_graph = $qrCampanhasEmail[COD_NRECEBIDO];
                                                    $optout_graph = $qrCampanhasEmail[COD_OPTOUT_ATIVO];
                                                    $falha_graph = $qrCampanhasEmail[BOUNCE];
                                                    $aguardo_graph = 0;

                                                    $perc_sucesso = fnValorSql(fnValor(($sucesso_graph / $contatos_graph) * 100, 2));
                                                    $perc_nrecebidos = fnValorSql(fnValor(($nrecebidos_graph / $contatos_graph) * 100, 2));
                                                    $perc_optout = fnValorSql(fnValor(($optout_graph / $contatos_graph) * 100, 2));
                                                    $perc_falha = fnValorSql(fnValor(($falha_graph / $contatos_graph) * 100, 2));
                                                    $perc_aguardo = fnValorSql(fnValor(($aguardo_graph / $contatos_graph) * 100, 2));

                                                    if ($qrCampanhasEmail['DAT_ENVIO'] == "") {
                                                        $dat_envio = "Em Andamento";
                                                    } else {
                                                        $dat_envio = fnDataFull($qrCampanhasEmail['DAT_ENVIO']);
                                                    }
                                                        echo "
                                                        <tr id='UNIVEND_" . $qrCampanhasEmail['COD_UNIVEND'] . "'>                              
                                                            <td class='text-center'><a href='javascript:void(0);' onclick='abreDetail(" . $qrCampanhasEmail['COD_UNIVEND'] . ")'><i class='fal fa-angle-right' aria-hidden='true'></i></a></td>
                                                            <td><small><small>" . $qrCampanhasEmail['COD_UNIVEND'] . "</small>&nbsp;" . $qrCampanhasEmail['NOM_FANTASI'] . "</small></td>
                                                            <td><small><small>" . $dat_envio . "</small></small></td>
                                                            <td class='text-right bold'><small>" . fnValor($contatos_graph, 0) . "</small></td>
                                                            <td class='text-right bold'><small>" . fnValor($sucesso_graph, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_sucesso, 2) . "%</span></small></td>
                                                            <td class='text-right'><small>" . fnValor($nrecebidos_graph, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_nrecebidos, 2) . "%</span></small></td>
                                                            <td class='text-right bold'><small>" . fnValor($optout_graph, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_optout, 2) . "%</span></small></td>
                                                            <td class='text-right bold'><small>" . fnValor($falha_graph, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_falha, 2) . "%</span></small></td>
                                                            <td class='text-right bold'><small>" . fnValor($aguardo_graph, 0) . "<br/><span class='text-muted' style='font-size: 10px; font-weight: 100;'>" . fnValor($perc_aguardo, 2) . "%</span></small></td>
                                                        </tr>                      
                                                    ";
                                                    
                                                    echo "
                                                        <thead class='no-weight' style='display:none; background-color: #fff;' id='abreDetail_" . $qrCampanhasEmail['COD_UNIVEND'] . "'>
                                                        
                                                        </thead>                             
                                                    ";

                                                    $tot_qtd += $qrCampanhasEmail['SUB_TOTAL'];
                                                    $tot_sucesso += $qrCampanhasEmail['COD_CCONFIRMACAO'];
                                                    $tot_nrecebidos += $qrCampanhasEmail['COD_NRECEBIDO'];
                                                    $tot_optout += $qrCampanhasEmail['COD_OPTOUT_ATIVO'];
                                                    $tot_falha += $qrCampanhasEmail['BOUNCE'];
                                                    $tot_aguardo += $qrCampanhasEmail['TOTAL'];
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
                                                </tr>
                                                <tr>
                                                    <th colspan="100">
                                                        <a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a> &nbsp;&nbsp;
                                                        <!-- <a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)" value="S"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar Detalhes</a> &nbsp;&nbsp; -->
                                                    </th>
                                                </tr>
                                            </tfoot>

                                        </table>


                                        <div class="push"></div>

                                        <input type="hidden" name="LOJAS" id="LOJAS" value="<?=fnEncode($lojasSelecionadas)?>" />
                                       
                                        <input type="hidden" name="AND_CAMPANHA" id="AND_CAMPANHA" value="<?=fnEncode($andCampanha)?>" />
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
                                    url: "relatorios/ajxRelComunicaSMS.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

    function abreDetail(idUnidade) {
        RefreshCampanha(<?=$cod_empresa;?>, idUnidade);
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

    function RefreshCampanha(idEmp, idUnidade) {
        var idItem = $('#abreDetail_' + idUnidade);
        console.log(idItem);

        if (!idItem.is(':visible')) {
            $.ajax({
                type: "POST",
                url: "relatorios/ajxRelComunicaSMS.do?idu="+idUnidade,
                data: $("#formulario").serialize(),
                beforeSend: function() {
                    $("#abreDetail_" + idUnidade).html('<div class="loading" style="width: 100%;"></div>');

                },
                success: function(data) {
                    $("#abreDetail_" + idUnidade).html(data);
                    console.log(data);
                },
                error: function(data) {
                    $("#abreDetail_" + idUnidade).html(data);
                    //$("#mostraDetail_"+idCampanha).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
                }
            });

            idItem.show();

            $('#CAMPANHA_' + idUnidade).find($(".fa-angle-right")).removeClass('fa-angle-right').addClass('fa-angle-down');
        } else {
            idItem.hide();
            $('#CAMPANHA_' + idUnidade).find($(".fa-angle-down")).removeClass('fa-angle-down').addClass('fa-angle-right');
        }
    }
</script>