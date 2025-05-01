<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
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

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));
//$cod_univend = "9999"; //todas revendas - default

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
        $cod_univend = @$_POST['COD_UNIVEND'];
        $dat_ini = fnDataSql(@$_POST['DAT_INI']);
        $dat_fim = fnDataSql(@$_POST['DAT_FIM']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '') {
        }
    }
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
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

                <div style="display:none;" id="msgBox" class="msgBox">
                </div>

                <div class="login-form">
                    <div class="row">
                        <div class="col-md-12">

                            <table class="table table-bordered table-hover tablesorter buscavel">

                                <thead>
                                    <tr>
                                        <th class="{sorter:false}" width="40"></th>
                                        <th class="{sorter:false}" width="40"></th>
                                        <th>Código</th>
                                        <th>Nome Fantasia</th>
                                        <th>Razão Social</th>
                                        <th>Data Inicio</th>
                                        <th>Data Exclusão</th>
                                        <th>Data Base</th>
                                    </tr>
                                </thead>

                                <tbody id="relatorioConteudo">

                                    <?php

                                    $sql = "SELECT 
                                              1
                                        FROM empresas E
                                        LEFT JOIN tab_database B ON B.cod_empresa=E.COD_EMPRESA
                                        WHERE E.LOG_ATIVO = 'N' AND E.COD_MASTER = 3 AND B.NOM_DATABASE  IS not NULL	
                                        ORDER BY E.NOM_FANTASI asc";

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
                                            E.COD_EMPRESA,
                                            E.NOM_FANTASI, 
                                            E.NOM_EMPRESA,
                                            E.DAT_CADASTR, 
                                            E.DAT_EXCLUSA,
                                            B.NOM_DATABASE
                                    FROM empresas E
                                    LEFT JOIN tab_database B ON B.cod_empresa=E.COD_EMPRESA
                                    WHERE E.LOG_ATIVO = 'N' AND E.COD_MASTER = 3 AND B.NOM_DATABASE  IS not NULL	
                                    ORDER BY E.NOM_FANTASI asc
                                    LIMIT $inicio, $itens_por_pagina
                                    ";

                                    // fnEscreve($sql);
                                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                    $count = 0;
                                    while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

                                    ?>
                                        <tr id="bloco_<?php echo $qrListaVendas['COD_EMPRESA']; ?>">
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="CHECK_ALL_<?= $qrListaVendas['COD_EMPRESA'] ?>" onclick="checkAll(<?= $qrListaVendas['COD_EMPRESA'] ?>)">
                                                </div>
                                            </th>
                                            <th width="5%" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaVendas['COD_EMPRESA']; ?>, '<?= $qrListaVendas['NOM_DATABASE']; ?>')" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
                                            <th><?= $qrListaVendas['COD_EMPRESA']; ?></th>
                                            <th><?= $qrListaVendas['NOM_FANTASI']; ?></th>
                                            <th><?= $qrListaVendas['NOM_EMPRESA']; ?></th>
                                            <th><?= fnDataFull($qrListaVendas['DAT_CADASTR']); ?></th>
                                            <th><?= fnDataFull($qrListaVendas['DAT_EXCLUSA']); ?></th>
                                            <th><?= $qrListaVendas['NOM_DATABASE']; ?></th>
                                        </tr>
                                        <tr style="background-color: #fff; display: none;" class="abreDetail_<?php echo $qrListaVendas['COD_EMPRESA']; ?>">
                                            <td colspan="7">
                                                <div class="detail-content_<?php echo $qrListaVendas['COD_EMPRESA']; ?>"></div>
                                            </td>
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

                            <div class="row" style="margin-bottom: 8px;">
                                <div class="col-md-4 text-rigth">
                                    <a href="javascript:void(0)" id="btExec" class="btn btn-danger pull-left whats"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Excluir Selecionados</a>
                                </div>
                            </div>

                            <input type="hidden" name="VALIDA_TOKEN" id="VALIDA_TOKEN" value="">
                            <input type="hidden" id="modalAcao">

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

<!-- modal -->
<div class="modal fade" id="modalToken" tabindex="-1" role="dialog" aria-labelledby="modalToken">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chave de Segurança</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="inputName" class="control-label">Digite a chave de segurança,
                            <?= ($num_celular <> "" ? " enviada por SMS para o número <b>" . $num_celular . "</b>" : "") ?>
                            <?= ($des_emailus <> "" ? ($num_celular <> "" ? ", e " : "") . " enviada para o e-mail <b>" . $des_emailus . "</b>" : "") ?> para verificar os registros:</label>

                        <input class="form-control" name="TOKEN" id="TOKEN">

                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="push20"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onClick="$('#modalAcao').val('CON');" data-dismiss="modal">Confirmar</button>
            </div>
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
    });

    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxRelEmpresasInat.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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

    function geraToken(callback) {
        $("#modalAcao").val("");
        $("#TOKEN").val("");
        token = "";

        $.ajax({
            type: "POST",
            url: "ajxMultidatabase.php?acao=token",
            success: function(data) {
                if ($.trim(data) != "ok") {
                    msgBox("alert-danger", data);
                    block(false);
                } else {
                    $("#modalToken").modal("show");
                    $("#modalToken").appendTo("body");

                    var popAtivoT = setInterval(popAtivo, 100);

                    function popAtivo() {
                        $("#TOKEN").focus();
                        if (!$(".modal-backdrop").is(":visible")) {
                            clearInterval(popAtivoT);
                            if ($("#modalAcao").val() == "CON") {
                                token = $("#TOKEN").val();
                                $('#VALIDA_TOKEN').val(token);
                                console.log('token: ' + token);
                                if (typeof callback == "function") {
                                    callback.call(this);
                                }
                            } else {
                                block(false);
                            }
                        }
                    }
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                msgBox("alert-danger", errorThrown);
                block(false);
            }
        });

    }

    function validaToken(callback) {
        $.ajax({
            type: "POST",
            url: "ajxMultidatabase.php?acao=valida",
            data: "token=" + token,
            success: function(data) {
                if ($.trim(data) != "ok") {
                    msgBox("alert-danger", data);
                    block(false);
                    $('#VALIDA_TOKEN').val('');
                } else {
                    if (typeof callback == "function") {
                        callback.call(this);
                    }
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                msgBox("alert-danger", errorThrown);
                block(false);
            }
        });
    }

    function msgBox(msgTipo, msgTexto) {
        var html = "<div class='alert " + msgTipo + " alert-dismissible top30 bottom30' role='alert' id='msgRetorno'>";
        html = html + "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
        html = html + "<div class='msgTexto'>" + msgTexto + "</div>";
        html = html + "</div>";
        $(".msgBox").html(html);
        $(".msgBox").show();
    }
    // function abreDetail(idBloco, base) {
    //     let temToken = $('#VALIDA_TOKEN').val()
    //     if(temToken == 'S'){
    //         var idItem = $('.abreDetail_' + idBloco);
    //         let dataBase = base;
    //         if (!idItem.is(':visible')) {
    //             idItem.show();
    //             $('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
    //             $.ajax({
    //                 type: "POST",
    //                 url: "relatorios/ajxRelEmpresasInat.do?opcao=expandir&id=" + idBloco + "&base=" + dataBase,
    //                 beforeSend: function() {
    //                     $('.detail-content_' + idBloco).html('<div class="loading" style="width: 100%;"></div>');
    //                 },
    //                 success: function(data) {
    //                     $('.detail-content_' + idBloco).html(data);
    //                 },
    //                 error: function() {
    //                     $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
    //                 }
    //             });
    //         } else {
    //             idItem.hide();
    //             $('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
    //         }
    //     }else{
    //         geraToken(validaToken(abreDetail(idBloco, base)));
    //     }
    // }

    function block(acao) {
        if (acao == true) {
            $("#btExec").attr("disabled", true);
        } else {
            $("#btExec").removeAttr("disabled");
        }
    }

    function abreDetail(idBloco, base) {
        let temToken = $('#VALIDA_TOKEN').val();
        if (temToken != "") {
            var idItem = $('.abreDetail_' + idBloco);
            let dataBase = base;
            if (!idItem.is(':visible')) {
                console.log('abrir');
                idItem.show();
                $('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
                $.ajax({
                    type: "POST",
                    url: "relatorios/ajxRelEmpresasInat.do?opcao=expandir&id=" + idBloco + "&base=" + dataBase + "&token=" + temToken,
                    beforeSend: function() {
                        $('.detail-content_' + idBloco).html('<div class="loading" style="width: 100%;"></div>');
                    },
                    success: function(data) {
                        console.log(data);
                        $('.detail-content_' + idBloco).html(data);
                    },
                    error: function() {
                        $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Token invalido ou expirado...</p>');
                    }
                });
            } else {
                idItem.hide();
                $('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
            }
        } else {
            // Chama a função geraToken, passando uma função de callback como argumento
            geraToken(function() {
                // Quando o token for gerado, chama validaToken e, dentro dela, chama abreDetail
                validaToken(function() {
                    abreDetail(idBloco, base);
                });
            });
        }
    }


    function checkAll(idBloco) {
        let codEmpresa = idBloco;
        var idItem = $('.abreDetail_' + idBloco);

        if (idItem.is(':visible')) {
            let isChecked = $("#CHECK_ALL_" + codEmpresa).prop("checked");
            $('input[id^="CHECK_' + codEmpresa + '_"]').prop("checked", isChecked);
        }
    }
</script>