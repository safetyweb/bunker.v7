<?php

if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = "1";

$dat_ini = "";
$dat_fim = "";
$nom_fantasi = "";
$nom_empresa = "";
$num_cgcecpf = "";

$hashLocal = mt_rand();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        if (isset($_REQUEST['NOM_FANTASI'])) {
            $nom_fantasi = fnLimpaCampo($_REQUEST['NOM_FANTASI']);
        }
        if (isset($_REQUEST['NOM_EMPRESA'])) {
            $nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
        }
        if (isset($_REQUEST['NUM_CGCECPF'])) {
            $num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CGCECPF']));
        }
        if (isset($_REQUEST['DAT_INI'])) {
            $dat_ini = fnDataSql($_POST['DAT_INI']);
        }
        if (isset($_REQUEST['DAT_FIM'])) {
            $dat_fim = fnDataSql($_POST['DAT_FIM']);
        }

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '') {
        }
    }
}

?>

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

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Nome Fantasia</label>
                                        <input type="text" class="form-control input-sm" name="NOM_FANTASI" id="NOM_FANTASI" value="<?php echo $nom_fantasi; ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Razão Social</label>
                                        <input type="text" class="form-control input-sm" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CNPJ</label>
                                        <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo fnformatCnpjCpf($num_cgcecpf); ?>">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

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

                        </fieldset>

                        <div class="push20"></div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">


                        <div class="push5"></div>

                    </form>

                </div>
            </div>
        </div>

        <div class="push20"></div>

        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">
                    <div class="row">
                        <div class="col-md-12">

                            <table class="table table-bordered table-hover tablesorter buscavel">

                                <thead>
                                    <tr>
                                        <th>Cód. Empresa</th>
                                        <th>Nome</th>
                                        <th>Nome Fantasia</th>
                                        <th>CNPJ</th>
                                        <th>Status</th>
                                        <th>Data Início</th>
                                        <th>Data Finalização</th>
                                        <th width="40"></th>
                                    </tr>
                                </thead>

                                <tbody id="relatorioConteudo">

                                    <?php
                                    $andFantasi = "";
                                    $andEmpresa = "";
                                    $andCnpj = "";
                                    $andData = "";

                                    if ($nom_fantasi != "") {
                                        $andFantasi = "AND EMP.NOM_FANTASI = '$nom_fantasi'";
                                    }

                                    if ($nom_empresa != "") {
                                        $andEmpresa = "AND EMP.NOM_EMPRESA = '$nom_empresa'";
                                    }

                                    if ($num_cgcecpf != "") {
                                        $andCnpj = "AND EMP.NUM_CGCECPF = '$num_cgcecpf'";
                                    }

                                    if ($dat_ini != "" && $dat_fim != "") {
                                        $andData = "AND AUD.DAT_CADASTR BETWEEN '$dat_ini' AND '$dat_fim'";
                                    }

                                    $sql = "SELECT 
                                            1
                                            FROM auditoria_empresa AS AUD
                                            INNER JOIN empresas AS EMP ON AUD.COD_EMPRESA = EMP.COD_EMPRESA
                                            WHERE 1=1
                                            $andFantasi
                                            $andEmpresa
                                            $andCnpj
                                            $andData
                                            ";

                                    // fnEscreve($sql);
                                    $retorno = mysqli_query($connAdm->connAdm(), $sql);
                                    $totalitens_por_pagina = mysqli_num_rows($retorno);

                                    // fnescreve($sql);
                                    $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                    // fnEscreve($numPaginas);
                                    //variavel para calcular o início da visualização com base na página atual
                                    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                    // ================================================================================
                                    $sql = "SELECT 
                                        AUD.FASE1, 
                                        AUD.FASE2, 
                                        AUD.FASE3, 
                                        AUD.FASE4, 
                                        AUD.FASE5,
                                        AUD.COD_EMPRESA,
                                        EMP.NOM_EMPRESA,
                                        EMP.NOM_FANTASI,
                                        EMP.NUM_CGCECPF,
                                        EMP.DAT_CADASTR,
                                        AUD.DAT_FINALIZA
                                        FROM auditoria_empresa AS AUD
                                        INNER JOIN empresas AS EMP ON AUD.COD_EMPRESA = EMP.COD_EMPRESA
                                        WHERE 1=1
                                            $andFantasi
                                            $andEmpresa
                                            $andCnpj
                                            $andData
                                    LIMIT $inicio, $itens_por_pagina
                                    ";

                                    //fnEscreve($sql);
                                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                    $count = 0;
                                    while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
                                        $status = '<span class="label label-danger">Em Andamento</span>';
                                        if ($qrListaVendas['FASE1'] == 'S' && $qrListaVendas['FASE2'] == 'S' && $qrListaVendas['FASE3'] == 'S' && $qrListaVendas['FASE4'] == 'S' && $qrListaVendas['FASE5'] == 'S') {
                                            $status = '<span class="label label-success">Concluído</span>';
                                        }

                                    ?>
                                        <tr>
                                            <td class="text-center"><a href="action.php?mod=<?= fnEncode(2091) ?>&id=<?= fnEncode($qrListaVendas['COD_EMPRESA']) ?>"><?= $qrListaVendas['COD_EMPRESA']; ?></a></td>
                                            <td><?= $qrListaVendas['NOM_EMPRESA']; ?></td>
                                            <td><?= $qrListaVendas['NOM_FANTASI']; ?></td>
                                            <td><?= fnformatCnpjCpf($qrListaVendas['NUM_CGCECPF']); ?></td>
                                            <td><?= $status; ?></td>
                                            <td><?= fnDataShort($qrListaVendas['DAT_CADASTR']); ?></td>
                                            <td><?= fnDataShort($qrListaVendas['DAT_FINALIZA']); ?></td>
                                            <td></td>
                                        </tr>
                                    <?php

                                        $count++;
                                    }

                                    ?>

                                </tbody>

                                <tfoot>
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

        var numPaginas = <?php echo $numPaginas; ?>;
        if (numPaginas != 0) {
            carregarPaginacao(numPaginas);
        }

        $.tablesorter.addParser({
            id: "moeda",
            is: function(s) {
                return true;
            },
            format: function(s) {
                return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9,]/g), ""));
            },
            type: "numeric"
        });

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

    });

    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxRelEmpAutom.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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