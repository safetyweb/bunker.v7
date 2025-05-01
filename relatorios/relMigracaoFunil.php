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
$request = "";
$msgRetorno = "";
$msgTipo = "";
$nom_cliente = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente_av = "";
$tip_retorno = "";
$casasDec = "";
$andCliente = "";
$cliente = "";
$retorno = "";
$inicio = "";
$qrListaEmpresas = "";
$content = "";

//echo fnDebug('true');

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
        // $cod_univend = @$_POST['COD_UNIVEND'];

        $nom_cliente = fnLimpacampo(@$_REQUEST['NOM_CLIENTE']);
        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);


        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '' && $opcao != 0) {
        }
    }
}

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode(@$_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
        $cod_cliente_av = $qrBuscaEmpresa['COD_CLIENTE_AV'];
        $tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);

        if ($tip_retorno == 1) {
            $casasDec = 0;
        } else {
            $casasDec = 2;
        }
    }
} else {
    $cod_empresa = 0;
    $nom_empresa = "";
}

if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
    $dat_ini = "";
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
    $dat_fim = "";
}

if ($nom_cliente != "") {
    $andCliente = "AND c.NOM_CLIENTE LIKE '%$nom_cliente%'";
} else {
    $andCliente = "";
}

//fnEscreve($cliente);

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
</style>

<div class="push30"></div>

<div class="row">

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

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Filtros</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cliente</label>
                                        <input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40" value="<?= $nom_cliente ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Data Inicial</label>

                                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= fnFormatDate($dat_ini); ?>" required />
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
                                            <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?= fnFormatDate($dat_fim); ?>" required />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="push20"></div>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
                                </div>

                            </div>

                            <input type="hidden" name="opcao" id="opcao" value="">
                            <input type="hidden" name="AND_CLIENTE" id="AND_CLIENTE" value="<?= $andCliente ?>">
                            <input type="hidden" name="hashForm" id="hashForm" value="<?= $hashLocal; ?>" />
                            <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                            <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa; ?>">

                        </fieldset>

                    </form>

                </div>
            </div>

        </div>

        <div class="push20"></div>

        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">

                    <div class="push20"></div>

                    <div class="row">

                        <div class="col-lg-12">

                            <div class="no-more-tables">

                                <table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th class="text-left">Frequência</th>
                                        </tr>
                                    </thead>
                                    <tbody id="relatorioConteudo">

                                        <?php

                                        //paginação
                                        $sql = "SELECT 
		
                                        1
                                        FROM hitorico_cliente_frequencia a,FECHAMENTO_CLIENTES b,clientes c
                                        WHERE a.cod_controle=b.cod_controle AND 
                                        a.COD_CLIENTE=c.cod_cliente AND 
                                        b.cod_empresa=$cod_empresa
                                        $andCliente
                                        AND b.dat_fim >= '$dat_ini'
                                        AND b.dat_fim <= '$dat_fim'
                                        GROUP BY a.cod_cliente
                                        HAVING LENGTH(GROUP_CONCAT(DISTINCT a.cod_frequencia SEPARATOR ' -> ')) >1";

                                        $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                        $total_itens_por_pagina = mysqli_num_rows($retorno);
                                        //fnEscreve($total_itens_por_pagina);
                                        $numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);
                                        //variavel para calcular o início da visualização com base na página atual
                                        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


                                        $sql = "SELECT distinct c.NOM_CLIENTE,

                                        GROUP_CONCAT( distinct case when a.cod_frequencia=1 then
                                              'Casual'
                                                when a.cod_frequencia=2 then
                                                'Frequente'
                                                when a.cod_frequencia=3 then
                                                'Fiel'
                                                when a.cod_frequencia=4 then
                                                'Fã'
                                                
                                        END SEPARATOR ' -> ') AS frequencia,
                                        length(GROUP_CONCAT( DISTINCT a.cod_frequencia SEPARATOR ' -> '))frequencia1
                                        from hitorico_cliente_frequencia a,FECHAMENTO_CLIENTES b,clientes c
                                        WHERE a.cod_controle=b.cod_controle 
                                        AND a.COD_CLIENTE=c.cod_cliente 
                                        AND b.cod_empresa=$cod_empresa 
                                        AND b.dat_fim >= '$dat_ini' 
                                        AND b.dat_fim <= '$dat_fim'
                                        $andCliente  
                                        GROUP BY a.cod_cliente
                                        HAVING frequencia1>1
                                        ORDER BY frequencia DESC
                                        LIMIT $inicio,$itens_por_pagina";

                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                        //fnEscreve($sql);
                                        $count = 0;
                                        while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

                                            $count++;

                                            echo "
                                                <tr>
                                                    <td><small>" . $qrListaEmpresas['NOM_CLIENTE'] . "</small></td>     
                                                    <td class='text-left'><small>" . $qrListaEmpresas['frequencia']  . "</small></td>
                                                </tr>";
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

<script type="text/javascript">
    $(document).ready(function() {

        var numPaginas = <?php echo $numPaginas; ?>;
        if (numPaginas != 0) {
            carregarPaginacao(numPaginas);
        }

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
                                icon: 'fa fa-check-square',
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        url: "relatorios/ajxMigracaoFunil.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        console.log(response);
                                    }).fail(function(response) {
                                        self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
                                        console.log(response.responseText);
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

        $("body").delegate('input.placa', 'paste', function(e) {
            $(this).unmask();
        });
        $("body").delegate('input.placa', 'input', function(e) {
            $('input.placa').mask(MercoSulMaskBehavior, mercoSulOptions);
        });

    });



    // $(document).on('change', '#COD_EMPRESA', function () {
    //     $("#dKey").val($("#COD_EMPRESA").val());
    // });


    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxMigracaoFunil.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
            data: $('#formulario').serialize(),
            beforeSend: function() {
                $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#relatorioConteudo").html(data);
                console.log(data);
            },
            error: function() {
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }
</script>