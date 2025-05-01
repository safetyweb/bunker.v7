<?php

//echo fnDebug('true');

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = "1";

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-01'));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
        $dat_ini = fnDataSql($_POST['DAT_INI']);
        $dat_fim = fnDataSql($_POST['DAT_FIM']);
        $cod_propriedade = fnLimpaCampo($_POST['COD_PROPRIEDADE']);
        $cod_chale = fnLimpaCampo($_POST['COD_CHALE']);

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
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
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

//fnMostraForm();	
//fnEscreve($lojasSelecionadas);


?>

<div class="push30"></div>

<div class="row" id="div_Report">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                </div>

                <?php
                include "backReport.php";
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

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label ">Propriedades</label>
                                        <select data-placeholder="Selecione os hotéis" name="COD_PROPRIEDADE" id="COD_PROPRIEDADE" class="chosen-select-deselect">
                                            <option value="9999">Todas</option>
                                            <?php
                                            $sqlHotel = "SELECT COD_EXTERNO, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
                                            $arrayHotel = mysqli_query(connTemp($cod_empresa, ''), $sqlHotel);

                                            while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
                                            ?>
                                                <option value="<?= $qrHotel[COD_EXTERNO] ?>"><?= $qrHotel[NOM_FANTASI] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <script>
                                            $("#COD_PROPRIEDADE").val("<?php echo $cod_propriedade; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Chalés</label>
                                        <div id="divId_sub">
                                            <select data-placeholder="Selecione o sub grupo" name="COD_CHALE" id="COD_CHALE" class="chosen-select-deselect">
                                                <option value="">&nbsp;</option>
                                            </select>
                                        </div>
                                        <script>

                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Inicial</label>

                                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= fnFormatDate($dat_ini) ?>" required />
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
                                            <input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?= fnFormatDate($dat_fim) ?>" required />
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

                        </fieldset>

                        <div class="push20"></div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">


                        <div class="push5"></div>

                    </form>

                </div>
            </div>
        </div>

        <div class="push30"></div>

        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">
                    <div class="row">
                        <div class="col-md-12">

                            <table class="table table-bordered table-hover tablesorter buscavel">

                                <thead>
                                    <tr>
                                        <th class="{sorter:false}"></th>
                                        <th>Cód. Contrato</th>
                                        <th>Chalé</th>
                                        <th>Propriedade</th>
                                        <th class="text-right">Créditos</th>
                                        <th class="text-right">Débitos</th>
                                        <th class="text-right">Saldo</th>
                                    </tr>
                                </thead>

                                <tbody id="relatorioConteudo">

                                    <?php

                                    if ($cod_propriedade == "" or $cod_propriedade == 9999) {
                                        $and_propriedade = " ";
                                    } else {
                                        $and_propriedade = "AND UNV.COD_EXTERNO = $cod_propriedade";
                                    }
                                    if ($cod_chale != "") {
                                        $and_chale = "AND AC.COD_EXTERNO = $cod_chale";
                                    } else {
                                        $and_chale = " ";
                                    }

                                    $sql = "SELECT 
                                                            (
                                        SELECT SUM(C.val_credito)
                                        FROM caixa AS c 
                                        INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
                                        INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO
                                        WHERE p.COD_EMPRESA = 274 
                                        AND p.COD_PEDIDO =  AP.COD_PEDIDO
                                        AND c.cod_contrat = AP.COD_PEDIDO
                                        AND TC.TIP_OPERACAO = 'C'
                                        ) AS VAL_CREDITOS,
                                    (
                                        SELECT SUM(val_credito) 
                                        FROM caixa AS c 
                                        INNER JOIN adorai_pedido AS p ON c.cod_contrat = p.COD_PEDIDO
                                        INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = c.COD_TIPO 
                                        WHERE p.COD_EMPRESA = 274 
                                        AND p.COD_PEDIDO =  AP.COD_PEDIDO
                                        AND c.cod_contrat = AP.COD_PEDIDO
                                        AND TC.TIP_OPERACAO = 'D'
                                        ) AS VAL_DEBITOS,
                                    AC.NOM_QUARTO,
                                    UNV.NOM_UNIVEND,
                                    CX.COD_CONTRAT
                                    FROM caixa AS CX
                                    INNER JOIN adorai_pedido_items AS AP ON  AP.COD_PEDIDO = CX.COD_CONTRAT
                                    INNER JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AP.COD_CHALE
                                    INNER JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AP.COD_PROPRIEDADE
                                    WHERE AP.COD_EMPRESA = 274
                                    AND CX.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
                                    $and_propriedade
                                    $and_chale
                                    GROUP BY CX.COD_CONTRAT";

                                    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                    $count = 0;
                                    while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
                                    ?>
                                        <tr>
                                            <td></td>
                                            <td><?= $qrListaVendas['COD_CONTRAT']; ?></td>
                                            <td><?= $qrListaVendas['NOM_QUARTO']; ?></td>
                                            <td><?= $qrListaVendas['NOM_UNIVEND']; ?></td>
                                            <td class="text-right">R$ <?= fnValor($qrListaVendas['VAL_CREDITOS'], 2); ?></td>
                                            <td class="text-right" style="color: red;">- R$ <?= fnValor($qrListaVendas['VAL_DEBITOS'], 2); ?></td>
                                            <td class="text-right">R$ <?= fnValor($qrListaVendas['SALDO_TOTAL'], 2); ?></td>
                                        </tr>
                                    <?php

                                        $count++;
                                    }
                                    ?>

                                </tbody>

                                <tfoot>
                                    <!-- 
                                    <tr>
                                        <th colspan="100">
                                            <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                        </th>
                                    </tr> -->
                                    <tr>
                                        <th class="" colspan="100">
                                            <center>
                                                <ul id="paginacao" class="pagination-sm"></ul>
                                            </center>
                                            <a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this, <?= $dat_ini ?>, <?= $dat_ini ?>)"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a> &nbsp;&nbsp;

                                        </th>
                                    </tr>

                                </tfoot>

                            </table>

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

<script>
    $(document).ready(function() {

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

        // ajax
        $("#COD_PROPRIEDADE").change(function() {
            var codBusca = $("#COD_PROPRIEDADE").val();
            var codBusca3 = $("#COD_EMPRESA").val();
            buscaSubCat(codBusca, codBusca3);
        });

        function buscaSubCat(codprop, idEmp) {
            $.ajax({
                type: "GET",
                url: "ajxCheckoutAdorai.do?opcao=SubBusca",
                data: {
                    COD_PROPRIEDADE: codprop,
                    COD_EMPRESA: idEmp
                },

                beforeSend: function() {
                    $('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    $("#divId_sub").html(data);
                },
                error: function() {
                    $('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
                }
            });
        }

    });


    function exportarCSV(btn, dat_ini, dat_fim) {
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
                                    url: "relatorios/ajxRelPagAdorai.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
</script>