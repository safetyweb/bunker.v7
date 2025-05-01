<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$msg = "";
$url_whatsapp = "";
$hHabilitado = "";
$hashForm = "";
$sqlQrWhats = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaWhats = "";
$cod_usucada = "";
$qrBuscaUsu = "";
$lojasUsuario = "";
$abaEmpresa = "";
$andUnv = "";
$retorno = "";
$totalitens_por_pagina = 0;
$inicio = "";
$qrBuscaModulos = "";
$tipo = "";
$univend = "";
$numero = "";
$sessao = "";
$connection = "";
$conexao = "";
$proxy = "";
$cod_template = "";
$des_base64 = "";


$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
        $msg = fnLimpaCampo(@$_REQUEST['MSG']);
        $url_whatsapp = fnLimpaCampo(@$_REQUEST['URL_WHATSAPP']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];
    }
}

if (@$opcao == "CAD") {

    $sqlQrWhats = "INSERT INTO QRCODWHATSAPP (
        COD_EMPRESA,
        MSG,
        URL_WHATSAPP,
        DAT_CADASTR
        )VALUES(
        $cod_empresa,
        '$msg',
        '$url_whatsapp',
        NOW()
    )";
    mysqli_query(connTemp($cod_empresa, ''), $sqlQrWhats);
    //fnEscreve($sqlQrWhats);
    //fnTesteSql(connTemp($cod_empresa,''),$sqlQrWhats);


} else if (@$opcao == "ALT") {

    $sqlQrWhats = "UPDATE QRCODWHATSAPP SET
        MSG = '$msg',
        URL_WHATSAPP = '$url_whatsapp'
        WHERE COD_EMPRESA = $cod_empresa";

    mysqli_query(connTemp($cod_empresa, ''), $sqlQrWhats);


    switch ($opcao) {
        case 'CAD':
            $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
            break;
        case 'ALT':
            $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
            break;
            break;
    }
    $msgTipo = 'alert-success';
}


//busca dados da url    
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode(@$_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($adm, $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    $nom_empresa = "";
    //fnEscreve('entrou else');
}


$sql = "SELECT * FROM QRCODWHATSAPP WHERE COD_EMPRESA = '" . $cod_empresa . "' ";

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaWhats = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {

    $msg = $qrBuscaWhats['MSG'];
    //fnEscreve($msg);
    $url_whatsapp = $qrBuscaWhats['URL_WHATSAPP'];
} else {

    $msg = "";
    $url_whatsapp = "";
}

$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

$sql = "SELECT COD_UNIVEND FROM USUARIOS WHERE COD_USUARIO = $cod_usucada AND COD_EMPRESA = $cod_empresa";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
if ($qrBuscaUsu = mysqli_fetch_assoc($arrayQuery)) {
    $lojasUsuario = $qrBuscaUsu['COD_UNIVEND'];
}

?>

<style>
    #blocker {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: .8;
        background-color: #fff;
        z-index: 1000;
    }

    #blocker div {
        position: absolute;
        top: 30%;
        left: 48%;
        width: 200px;
        height: 2em;
        margin: -1em 0 0 -2.5em;
        color: #000;
        font-weight: bold;
    }

    .table-container td {
        padding: 8px;
    }

    .table-container tbody tr:last-child td {
        border-bottom: 1px solid #dddddd;
    }

    ul.summary-list {
        display: inline-block;
        padding-left: 0;
        width: 100%;
        margin-bottom: 0;
    }

    ul.summary-list>li {
        display: inline-block;
        width: 19.5%;
        text-align: center;
    }

    ul.summary-list>li>a>i {
        display: block;
        font-size: 18px;
        padding-bottom: 5px;
    }

    ul.summary-list>li>a {
        padding: 10px 0;
        display: inline-block;
        color: #818181;
    }

    ul.summary-list>li {
        border-right: 1px solid #eaeaea;
    }

    ul.summary-list>li:last-child {
        border-right: none;
    }
</style>

<div id="blocker">
    <div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)</div>
</div>

<div class="push30"></div>

<div class="row">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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

                <?php
                $abaEmpresa = 1950;
                include "abasEmpresas.php";
                ?>

                <div class="push30"></div>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>

                            <legend>Dados Gerais</legend>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Mensagem: </label>
                                    <input type="text" class="form-control input-sm" name="MSG" id="MSG" value="<?php echo $msg; ?>" maxlength="600">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-top: 10px;">
                                    <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn" <?php echo empty($qrBuscaWhats) ? '' : 'disabled'; ?>></i>&nbsp; Cadastrar</button>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                                </div>
                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <div class="form-group text-right col-md-12">

                            <?php if ($_SESSION["SYS_COD_EMPRESA"] == 2 || $_SESSION["SYS_COD_EMPRESA"] == 3 || $_SESSION["SYS_COD_EMPRESA"] == 274) { ?>
                                <a href="javascript:void(0)" class="btn btn-danger pull-left whats" data-url="action.do?mod=<?php echo fnEncode(2044) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastro de WhatsApp - <?= $nom_empresa ?>"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Cadastrar Número</a>
                            <?php } ?>

                        </div>

                        <div class="push10"></div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                        <input type="hidden" name="REFRESH_PAGINA" id="REFRESH_PAGINA" value="N">

                        <div class="push5"></div>

                    </form>

                    <div class="push30"></div>

                    <div class="col-lg-12">

                        <div class="no-more-tables">

                            <form name="formLista">

                                <table class="table table-bordered table-striped table-hover tableSorter buscavel">
                                    <thead>
                                        <tr>
                                            <th class="{ sorter: false }" width="40"></th>
                                            <th>Unidade</th>
                                            <th width="15%">Número</th>
                                            <th>Sessão</th>
                                            <th class="text-center { sorter: false }">Status</th>
                                            <th class="tab { sorter: false }" width="40"></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        include "_system/whatsapp/wstAdorai.php";

                                        if ($_SESSION["SYS_COD_EMPRESA"] != 3 && $_SESSION["SYS_COD_EMPRESA"] != 2 && $_SESSION["SYS_COD_EMPRESA"] != 274) {
                                            $andUnv = "AND SENHAS_WHATSAPP.COD_UNIVEND IN ($lojasUsuario)";
                                        } else {
                                            $andUnv = "";
                                        }

                                        $sql = "SELECT 1 from SENHAS_WHATSAPP WHERE EMPRESAS.COD_EMPRESA = $cod_empresa $andUnv";

                                        $retorno = mysqli_query($connAdm->connAdm(), $sql);
                                        @$totalitens_por_pagina = mysqli_num_rows($retorno);
                                        $numPaginas = @$itens_por_pagina != 0 ? ceil(@$totalitens_por_pagina / @$itens_por_pagina) : 0;

                                        // fnEscreve($numPaginas);

                                        $inicio = (@$itens_por_pagina * @$pagina) - @$itens_por_pagina;
                                        //echo $inicio;


                                        $sql = "SELECT SENHAS_WHATSAPP.*,
                                            EMPRESAS.NOM_FANTASI NOM_EMPRESA,
                                            UNIDADEVENDA.NOM_FANTASI NOM_UNIVEND 
                                            from SENHAS_WHATSAPP
                                            left join EMPRESAS ON SENHAS_WHATSAPP.COD_EMPRESA = EMPRESAS.COD_EMPRESA
                                            left join UNIDADEVENDA ON SENHAS_WHATSAPP.COD_UNIVEND = UNIDADEVENDA.COD_UNIVEND
                                            WHERE EMPRESAS.COD_EMPRESA = $cod_empresa
                                            $andUnv";

                                        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                        $count = 0;
                                        while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

                                            //fnEscreveArray($qrBuscaModulos);

                                            $count++;
                                            $tipo = "";

                                            if ($qrBuscaModulos['COD_UNIVEND'] != 9999) {
                                                $univend = $qrBuscaModulos['NOM_UNIVEND'];
                                            } else {
                                                $univend = "Todas Unidades";
                                            }

                                            // está crashando a página
                                            // $numero = fnSTATUSDEVICES($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $qrBuscaModulos['PORT_SERVICAO']);

                                            // echo "<pre>";
                                            // print_r($numero);
                                            // echo "</pre>";

                                            // $numero = $numero['instance'][integration][number];

                                            $numero = $qrBuscaModulos['CELULAR'];
                                            $sessao = $qrBuscaModulos['NOM_SESSAO'];

                                            $connection = fnconnectionState("$qrBuscaModulos[NOM_SESSAO]", "$qrBuscaModulos[DES_AUTHKEY]", "$qrBuscaModulos[PORT_SERVICAO]");

                                            // fnEscreve($connection['instance']['state']);


                                            switch (@$connection['instance']['state']) {
                                                case 'open':
                                                    $conexao = '<div class="push5"></div>
                                                    <p class="label f14" style="background-color: #18BC9C "> <span class="fal fa-check" style="color: #FFF;"></span>
                                                    &nbsp;Conectado
                                                    </p>';
                                                    break;
                                                case 'close':
                                                    $conexao = '<div class="push5"></div>
                                                    <p class="label f14" style="background-color: #FF5252 "> <span class="fal fa-times" style="color: #FFF;"></span>
                                                    &nbsp;Conexão encerrada
                                                    </p>';
                                                    break;

                                                default:
                                                    $conexao = '<div class="push5"></div>
                                                    <p class="label f14 bg-danger" > <span class="fal fa-clock" style="color: #FFF;"></span>
                                                    &nbsp;Aguardando conexão
                                                    </p>';
                                                    break;
                                            }

                                            if (@$connection['instance']['state'] == 'open') {

                                                if ($qrBuscaModulos['PROXY_HOST'] == "") {

                                                    $proxy = fnPROXY("$qrBuscaModulos[NOM_SESSAO]", "p.webshare.io", "80", "http", "ihakjfrv-BR-rotate", "haqrvntza9gf", "$qrBuscaModulos[DES_AUTHKEY]", "$qrBuscaModulos[PORT_SERVICAO]");

                                                    if ($proxy['proxy']['proxy']['enabled'] == true) {
                                                        $sql = "UPDATE SENHAS_WHATSAPP SET
                                                            PROXY_HOST='p.webshare.io',
                                                            PROXY_PORT='80',
                                                            PROXY_PROTOCOL='http',
                                                            PROXY_USER='ihakjfrv-BR-rotate',
                                                            PROXY_PASS='haqrvntza9gf'
                                                            WHERE COD_SENHAPARC = " . $qrBuscaModulos['COD_SENHAPARC'];
                                                        mysqli_query($connAdm->connAdm(), $sql);
                                                    }
                                                }
                                            }

                                        ?>
                                            <tr>
                                                <td></td>
                                                <td><?= $univend ?></td>
                                                <td>
                                                    <a href='#' class='editable'
                                                        data-type='text'
                                                        data-title='Editar Celular'
                                                        data-pk='<?= $numero ?>'
                                                        data-name='NUM_CELULAR'
                                                        data-senhaparc='<?= $qrBuscaModulos['COD_SENHAPARC'] ?>'>
                                                        <?= $numero ?>
                                                    </a>
                                                </td>
                                                <td><?= $sessao ?></td>
                                                <td class="text-center"><?= $conexao ?></td>
                                                <td>
                                                    <small>
                                                        <div class="btn-group dropdown dropleft">
                                                            <button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                ações &nbsp;
                                                                <span class="fas fa-caret-down"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                                                <li><a href='javascript:void(0)' id="enviarTesteSimples" onclick="modalEnvioTeste('<?= $qrBuscaModulos['COD_SENHAPARC'] ?>')"><span class="fal fa-paper-plane"></span>&nbsp;&nbsp;Quicktest &nbsp;</a></li>
                                                                <li class="divider"></li>

                                                                <li><a href="javascript:void(0)" onclick='sessao("<?= $qrBuscaModulos['COD_SENHAPARC'] ?>","qrcode")'>Gerar QrCode </a></li>

                                                                <!-- <li><a href="javascript:void(0)" onclick='geraQRCode("<?= $numero['0'] ?>")'><span class="fal fa-qrcode"></span>&nbsp;&nbsp;Obter QRCode &nbsp;</a></li> -->
                                                                <!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
                                                            </ul>
                                                        </div>
                                                    </small>
                                                </td>
                                                <!-- <td class="text-center">
                                                        <small>
                                                            <div class="btn-group dropdown dropleft">
                                                                <button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" aria-haspopup="true" aria-expanded="false" data-num-sele="<?= $qrBuscaModulos['NUM_CELULAR'] ?>" onclick="gerarQRCode('<?= $count ?>')">
                                                                    Obter QRCode &nbsp;
                                                                </button>
                                                            </div>
                                                        </small>
                                                    </td> -->
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </form>

                        </div>

                    </div>

                    <div class="push50"></div>

                    <div class="push"></div>

                </div>
            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>

<!-- modal -->
<div class="modal fade" id="popModalEnvio" tabindex='-1'>
    <div class="modal-dialog" style="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="envioTeste" action="">
                    <fieldset>
                        <legend>Dados do envio</legend>

                        <div class="row">

                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Mensagem</label>
                                    <input type="text" class="form-control input-sm" name="DES_TEMPLATE" id="DES_TEMPLATE" maxlength="400">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Celulares (com DDD)</label>
                                    <input type="tel" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="400">
                                    <div class="help-block with-errors">Separar múltiplos celulares com ";"</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <a href="javascript:void(0)" id="dispararTeste" data-codParceiro="" class="btn btn-primary btn-sm btn-block getBtn" style="margin-top: 2px;"><i class="fal fa-paper-plane" aria-hidden="true"></i>&nbsp; Envio de teste</a>
                            </div>
                        </div>

                        <input type="hidden" name="COD_TEMPLATE_ENVIO" id="COD_TEMPLATE_ENVIO" value="<?= $cod_template ?>">
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="push20"></div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
    <div class="modal-dialog" style="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="push100"></div>
                <div class="push100"></div>
                <center><img class="img-responsive" id="IMG_CODE" src="<?= $des_base64 ?>" width="40%" /></center>
                <div class="push100"></div>
                <div class="push100"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Segundo modal -->
<div class="modal fade z-3" id="iframeModal" tabindex='-1'>
    <div class="modal-dialog" style="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe id="iframeContent" frameborder="0" style="width: 100%; height: 80%"></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
<script type="text/javascript" src="js/jquery-qrcode-master/src/jquery.qrcode.js"></script>
<script type="text/javascript" src="js/jquery-qrcode-master/src/qrcode.js"></script>

<script>
    $(function() {

        $('#popModal').on('hidden.bs.modal', function() {
            window.location.reload();
        });

        $('#iframeModal').on('hidden.bs.modal', function() {
            let reload = $('#REFRESH_PAGINA').val();

            if (reload === 'S') {
                window.location.reload();
            }
        });


        $('.editable').each(function() {
            $(this).editable({
                emptytext: '_______________',
                url: 'ajxEmpresaWhats.do?id=<?= fnEncode($cod_empresa) ?>',
                ajaxOptions: {
                    type: 'POST'
                },
                params: function(params) {
                    params.senhaparc = $(this).data('senhaparc');
                    params.name = $(this).data('name');
                    return params;
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.status === 'success') {
                        window.location.reload();
                    } else {
                        if (data.mensagem) {
                            $.alert({
                                title: "Aviso",
                                content: data.mensagem,
                                type: 'danger',
                                buttons: {
                                    "OK": {
                                        btnClass: 'btn-blue',
                                        action: function() {

                                        }
                                    }
                                },
                                backgroundDismiss: true
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    $.alert({
                        title: "Aviso",
                        content: 'Ocorreu um erro ao atualizar o número de celular. Por favor, tente novamente. <br> Se o erro persistir, entre em contato com o suporte.',
                        type: 'danger',
                        buttons: {
                            "OK": {
                                btnClass: 'btn-blue',
                                action: function() {

                                }
                            }
                        },
                        backgroundDismiss: true
                    });
                }
            });
        });
    });

    function sessao(cod_senhaparc, tipo) {

        let msg1 = "Encerrar sessão?",
            msg2 = "Sessão encerrada.";

        if (tipo == "qrcode") {
            msg1 = "Gerar novo QrCode?";
        } else {
            msg1 = 'Reconectar WhatsApp?';
        }

        $.alert({
            title: "Aviso",
            type: 'orange',
            content: msg1,
            buttons: {
                "Sim": {
                    btnClass: 'btn-danger',
                    action: function() {
                        $("#blocker").show();
                        $.ajax({
                            type: "POST",
                            url: "ajxGerenciadorSenhaWhatsAppAdorai.do?id=<?= fnEncode($cod_empresa) ?>&opcao=" + tipo,
                            data: {
                                COD_SENHAPARC: cod_senhaparc
                            },
                            success: function(data) {

                                console.log(data);

                                $("#blocker").hide();

                                if (tipo == "qrcode") {
                                    if (data == "0") {
                                        msg2 = "QrCode não disponível";
                                    } else {
                                        msg2 = "";
                                    }
                                } else if (tipo == "reconect") {
                                    if (data == "0") {
                                        msg2 = "QrCode não disponível";
                                    } else {
                                        msg2 = "";
                                    }
                                } else {
                                    if (data) {
                                        msg2 = "Sessão encerrada.";
                                    } else {
                                        msg2 = "Erro ao encerrar sessão.";
                                    }
                                }

                                if (msg2 != "") {
                                    $.alert({
                                        title: "Aviso",
                                        content: msg2
                                    });
                                } else {
                                    $('#IMG_CODE').attr('src', data);
                                    $('#popModal .modal-title').text('QrCode');
                                    $('#popModal').not('#popModalNotifica').appendTo("body").modal('show');
                                }

                            },
                            error: function() {
                                $.alert({
                                    title: "Aviso",
                                    type: 'red',
                                    content: "Erro na solicitação."
                                });
                            }
                        });
                    }
                },
                "Não": {
                    action: function() {

                    }
                }
            }
        });
    }

    $('.whats').on('click', function(e) {
        e.preventDefault();

        var url = $(this).data('url');
        var title = $(this).data('title');

        $('#iframeModal .modal-title').text(title);
        $('#iframeContent').attr('src', url);
        $('#iframeModal').modal('show').appendTo('body');
    });

    function modalEnvioTeste(cod_parceiro) {

        $("#dispararTeste").attr("data-codParceiro", cod_parceiro);

        $("#popModalEnvio").modal().appendTo('body');

    }

    $("#dispararTeste").click(function() {
        $("#envioTeste #DES_TEMPLATE_ENVIO").val($("#DES_TEMPLATE").val());
        if ($("#NUM_CELULAR").val().trim() != "") {

            envioTeste($(this).attr("data-codParceiro"));

        } else {

            $.alert({
                title: "Aviso",
                content: "O campo de celulares não pode ser vazio",
                type: 'orange',
                buttons: {
                    "OK": {
                        btnClass: 'btn-blue',
                        action: function() {

                        }
                    }
                },
                backgroundDismiss: true
            });

        }
    });

    function envioTeste(cod_parceiro) {
        $.ajax({
            method: 'POST',
            url: 'ajxEnvioTesteSimplesWhats.do?id=<?= fnEncode($cod_empresa) ?>',
            data: {
                DES_TEMPLATE: $("#DES_TEMPLATE").val(),
                NUM_CELULAR: $("#NUM_CELULAR").val(),
                COD_PARCEIRO: cod_parceiro
            },
            beforeSend: function() {
                $("#dispararTeste").html("<center><div class='loading' style='width:50%'></div></center>");
            },
            success: function(data) {

                $("#dispararTeste").html("<span class='fas fa-check'></span>&nbsp;Teste enviado")
                    .removeClass("btn-primary")
                    .addClass("btn-success")
                    .attr('disabled', true)
                    .attr('id', 'disparadoTeste');

                setInterval(function() {
                    $("#disparadoTeste").fadeOut('fast')
                        .html("<span class='fal fa-paper-plane'></span>&nbsp;Envio de teste")
                        .removeClass("btn-success")
                        .addClass("btn-primary")
                        .attr('disabled', false)
                        .attr('id', 'dispararTeste')
                        .fadeIn('fast');
                }, 15000);

                $.alert({
                    title: "Sucesso",
                    content: "O seu teste foi enviado! Verifique o WhatsApp (essa operação pode levar alguns segundos).",
                    type: 'green',
                    buttons: {
                        "OK": {
                            btnClass: 'btn-blue',
                            action: function() {

                            }
                        }
                    },
                    backgroundDismiss: true
                });

                console.log(data);

            },
            error: function() {

                console.log("erro 500");

            }
        });
    }

    // function geraQRCode(num){
    //     $("#qrcodeCanvas").html("");
    //     var celLimpo = num.replace(/[^0-9]/g, ''),
    //         mensagem = $('#MSG').val(),
    //         encodedMsg = encodeURIComponent(mensagem),
    //         linkCode = "https://wa.me/" + celLimpo + '?text=' + encodedMsg;
    //     jQuery('#qrcodeCanvas').qrcode({
    //         text: linkCode,
    //         width: 400,
    //         height: 400
    //     });
    //     $('#qrCodeModal').modal('show').appendTo("body");
    // }

    $("#saveQr").click(function() {
        this.href = $('#qrcodeCanvas canvas')[0].toDataURL(); // Change here
        this.download = 'qrCode.jpg';
    });

    // function gerarQRCode(num){
    //     var mensagem = encodeURIComponent($('#MSG').val());

    //     var celLimpo = num.replace(/[^0-9]/g, '');
    //     var encodedMsg = encodeURIComponent(mensagem);
    //     var linkFinal = "https://wa.me/" + celLimpo + '?text=' + encodedMsg;

    //     $('#qrCodeImage').attr('src', linkFinal);
    //     $('#qrCodeModal').modal('show').appendTo("body");
    // }

    // document.getElementById('imprimirQr').onclick = function() {
    //     if ($('#qrCodeImage').attr('src')) {
    //         var printWindow = window.open('', '_blank');
    //         printWindow.document.open();
    //         printWindow.document.write('<html><body><img src="' + $('#qrCodeImage').attr('src') + '"></body></html>');
    //         printWindow.document.close();
    //         printWindow.print();
    //     } else {
    //         alert('Nenhum link do QR Code disponível.');
    //     }
    // };
</script>