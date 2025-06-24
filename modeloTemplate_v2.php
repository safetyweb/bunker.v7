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
$cod_categortkt = "";
$des_categor = "";
$des_abrevia = "";
$des_icones = "";
$log_destak = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_template = "";
$qrBuscaTemplate = "";
$log_ativo = "";
$mostraLog_ativo = "";
$nom_template = "";
$abv_template = "";
$des_template = "";
$formBack = "";
$abaModulo = "";
$qtdeBloco = 0;
$qrListaBlocos = "";
$qrListaModelos = "";
$sqlMsg = "";
$arrayMsg = [];
$qrBuscaComunicacao = "";
$temMsg = "";
$msg = "";
$dia_hoje = "";
$mes_hoje = "";
$ano_hoje = "";
$dia_nascime = "";
$mes_nascime = "";
$ano_nascime = "";
$NOM_CLIENTE = "";
$TEXTOENVIO = "";
$msgsbtr = "";

$min_historico_tkt = "0";
$max_historico_tkt = "30";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_categortkt = fnLimpaCampoZero(@$_REQUEST['COD_CATEGORTKT']);
        $cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
        $des_categor = fnLimpaCampo(@$_REQUEST['DES_CATEGOR']);
        $des_abrevia = fnLimpaCampo(@$_REQUEST['DES_ABREVIA']);
        $des_icones = fnLimpaCampo(@$_REQUEST['DES_ICONES']);
        if (empty(@$_REQUEST['LOG_DESTAK'])) {
            $log_destak = 'N';
        } else {
            $log_destak = @$_REQUEST['LOG_DESTAK'];
        }

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];
    }
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode(@$_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($qrBuscaEmpresa)) {
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    $nom_empresa = "";
}

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idT'])))) {

    //busca dados do convênio
    $cod_template = fnDecode(@$_GET['idT']);
    $sql = "SELECT * FROM TEMPLATE WHERE COD_TEMPLATE = " . $cod_template;

    //fnEscreve($sql);
    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
    $qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

    if (isset($qrBuscaTemplate)) {
        $cod_template = $qrBuscaTemplate['COD_TEMPLATE'];
        $log_ativo = $qrBuscaTemplate['LOG_ATIVO'];
        if ($log_ativo == "S") {
            $mostraLog_ativo = "checked";
        } else {
            $mostraLog_ativo = "";
        }
        $nom_template = $qrBuscaTemplate['NOM_TEMPLATE'];
        $abv_template = $qrBuscaTemplate['ABV_TEMPLATE'];
        $des_template = $qrBuscaTemplate['DES_TEMPLATE'];
    }
} else {
    $cod_template = "";
    $log_ativo = "";
    $nom_template = "";
    $abv_template = "";
    $des_template = "";
}

// BUSCA LINK ENCURTADO
$urlEncurtada = '';
$sqlBusca = "SELECT * FROM TAB_ENCURTADOR WHERE COD_EMPRESA = $cod_empresa AND TIP_URL = 'TKT'";
$arrayBusca = mysqli_query($connAdm->connAdm(), $sqlBusca);
if (mysqli_num_rows($arrayBusca) == 0) {
    $sql = "SELECT COD_TEMPLATE, NOM_TEMPLATE FROM TEMPLATE WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' LIMIT 1";
    $array = mysqli_query($conn, $sql);
    if (mysqli_num_rows($array) > 0) {
        $sqlProd = "SELECT * FROM PRODUTOTKT WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVOTK = 'S'";
        $arrayProd = mysqli_query($conn, $sqlProd);
        if (mysqli_num_rows($arrayProd) > 0) {
            $qrTkt = mysqli_fetch_assoc($array);
            $titulo = $qrTkt['NOM_TEMPLATE'] . ' #' . $qrTkt['COD_TEMPLATE'];
            $code = fnEncurtador($titulo, '', '', '', 'TKT', $cod_empresa, $connAdm->connAdm(), $qrTkt['COD_TEMPLATE']);
            $urlEncurtada = "https://tkt.far.br/" . $code . "/";
        }
    }
} else {
    $qrBuscaLink = mysqli_fetch_assoc($arrayBusca);
    $urlEncurtada = "https://tkt.far.br/" . short_url_encode($qrBuscaLink['id']) . "/";
}
//fnMostraForm();
//fnEscreve($_SESSION["SYS_COD_SISTEMA"]);

?>

<style type="text/css">
    .template {
        margin: 0 auto;
        height: auto;
        /* width: 600px; */
        margin-top: 50px;
    }

    .connectedSortable {
        list-style-type: none;
        padding: 0;
    }

    .connectedSortable li:not(.normal) {
        min-height: 60px;
        text-align: center;
        width: auto !important;
        height: auto !important;
        overflow: hidden;
    }

    .sortableUi {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    #sortable2 {
        float: left;
        margin: 4px;
        height: auto !important;
        border: 3px dashed #cecece;
        padding: 10px;
        border-radius: 5px;
        width: 100%;
        background-color: #f0eef7;
        padding: 10px;
    }

    #sortable2 li {
        width: auto;
        background-color: #ffffff;
        border: 1px solid #cecece;
        border-radius: 5px;
        padding: 16px;
    }

    #sortable2 li.active {
        border-color: #ff4a4a !important;
    }

    #sortable2 li:not(:last-child) {
        margin-bottom: 10px;
    }

    .ui-state-default {
        border: 1px solid #c5c5c5;
        background: #f6f6f6;
        font-weight: normal;
        color: #454545;
        margin-top: 24px;
    }

    .ui-sortable-handle {
        touch-action: none;
    }

    .ui-state-default a {
        color: #454545;
        text-decoration: none;
    }

    .descricaobloco {
        font-size: 11px;
    }

    .template i {
        margin-top: 10px;
    }

    hr {
        width: 100%;
        border-top: 2px solid #161616;
    }

    hr.divisao {
        width: 100%;
        border-top: 1px dashed #cecece;
        margin: 15px 0;
    }

    .excluirBloco {
        background: #ff5c5c;
        color: #ffffff !important;
        border: none;
        border-radius: 6px;
        padding: 6px 18px;
        font-size: 16px;
        position: absolute;
        margin-top: -53px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 2;
    }

    .ofertas-apagar {
        background: #ff5c5c;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 6px 18px;
        font-size: 16px;
        position: absolute;
        left: 16px;
        top: -18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .excluirBloco.active {
        display: block;

    }

    /* .excluirBloco.black {
        margin-right: 10px;
        margin-top: 10px;
        color: #404040 !important;
    }

    .excluirBloco:hover {
        color: #ff4a4a !important;
        cursor: pointer;
    } */

    .addImagem {
        position: absolute;
        top: 20px;
        right: 0px;
        font-size: 16px;
        margin-right: 5px;
        color: #cccccc !important;
    }

    .addImagem:hover {
        color: #18bc9c !important;
        cursor: pointer;
    }

    .imagemTicket {
        height: auto;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        padding-right: 20px;
    }

    .submenu-tabs {
        display: flex;
        overflow: hidden;
    }

    .tab-button {
        flex: 1;
        text-align: center;
        padding: 10px 0;
        border: none;
        background-color: #ffffff;
        color: #6b6b7b;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .tab-button:last-child {
        border-right: none;
    }

    .tab-button.active {
        background-color: #f0eef7;
        border-bottom: 1px solid transparent;
        font-weight: 600;
        border-bottom: 1px solid #cecece;
        border-right: 1px solid #cecece;
    }

    .submenu-tabs2 {
        display: flex;
        overflow: hidden;
    }

    .tab-button2 {
        flex: 1;
        text-align: center;
        padding: 10px 0;
        border: none;
        background-color: #ffffff;
        color: #6b6b7b;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .tab-button2:last-child {
        border-right: none;
    }

    .tab-button2.active {
        background-color: #f0eef7;
        border-bottom: 1px solid transparent;
        font-weight: 600;
        border-bottom: 1px solid #cecece;
        border-right: 1px solid #cecece;
    }

    .hidden {
        display: none;
    }

    .change-icon .fa+.fa,
    .change-icon:hover .fa:not(.fa-edit) {
        display: none;
    }

    .change-icon:hover .fa+.fa:not(.fa-edit) {
        display: inherit;
    }

    .fa-edit:hover {
        color: #18bc9c;
        cursor: pointer;
    }

    .item {
        padding-top: 0;
    }

    .jqte {
        border: #dce4ec 2px solid !important;
        border-radius: 3px !important;
        -webkit-border-radius: 3px !important;
        box-shadow: 0 0 2px #dce4ec !important;
        -webkit-box-shadow: 0 0 0px #dce4ec !important;
        -moz-box-shadow: 0 0 3px #dce4ec !important;
        transition: box-shadow 0.4s, border 0.4s;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
    }

    .jqte_toolbar {
        background: #fff !important;
        border-bottom: none !important;
    }

    .jqte_focused {
        /*border: none!important;*/
        box-shadow: 0 0 3px #00BDFF;
        -webkit-box-shadow: 0 0 3px #00BDFF;
        -moz-box-shadow: 0 0 3px #00BDFF;
    }

    .jqte_titleText {
        border: none !important;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        word-wrap: break-word;
        -ms-word-wrap: break-word
    }

    .jqte_tool,
    .jqte_tool_icon,
    .jqte_tool_label {
        border: none !important;
    }

    .jqte_tool_icon:hover {
        border: none !important;
        box-shadow: 1px 5px #EEE;
    }
</style>
<link rel="stylesheet" href="css/widgets.css" />

<div class="push30"></div>

<div class="row">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="glyphicon glyphicon-calendar"></i>
                    <span class="text-primary"><?php echo $NomePg; ?></span>
                </div>

                <?php
                $formBack = "1108";
                include "atalhosPortlet.php";
                ?>

            </div>
            <div class="portlet-body">

                <?php $abaModulo = 1111;
                include "abasTicketConfig.php"; ?>

                <div class="push30"></div>

                <div class="login-form">


                    <!-- <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CATEGORTKT" id="COD_CATEGORTKT" value="<?php echo $cod_template ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Empresa</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
                                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Nome da Template</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_TEMPLATE" id="NOM_TEMPLATE" value="<?php echo $nom_template ?>" maxlength="20" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Abreviação</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="ABV_TEMPLATE" id="ABV_TEMPLATE" value="<?php echo $abv_template ?>">
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="disabledBlock"></div>
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Ativo</label>
                                        <div class="push5"></div>
                                        <label class="switch">
                                            <input type="checkbox" name="LOG_DESTAK" id="LOG_DESTAK" class="switch" value="S" <?php echo $mostraLog_ativo; ?>>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <input type="text" id="linkPesquisa" class="form-control input-md pull-right text-center" value='<?= $urlEncurtada ?>' readonly>
                                    <input type="hidden" id="LINK_SEMCLI" value='<?= $urlEncurtada ?>'>
                                </div>


                                <div class="col-md-2">
                                    <button type="button" class="btn btn-default" id="btnPesquisa" <?= $disableBtn ?>><i class="fas fa-copy" aria-hidden="true"></i>&nbsp; Copiar Link</button>
                                    <script type="text/javascript">
                                        $("#btnPesquisa").click(function() {
                                            if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
                                                var el = $("#linkPesquisa").get(0);
                                                var editable = el.contentEditable;
                                                var readOnly = el.readOnly;
                                                el.contentEditable = true;
                                                el.readOnly = false;
                                                var range = document.createRange();
                                                range.selectNodeContents(el);
                                                var sel = window.getSelection();
                                                sel.removeAllRanges();
                                                sel.addRange(range);
                                                el.setSelectionRange(0, 999999);
                                                el.contentEditable = editable;
                                                el.readOnly = readOnly;
                                            } else {
                                                $("#linkPesquisa").select();
                                            }
                                            document.execCommand('copy');
                                            $("#linkPesquisa").blur();
                                            $("#btnPesquisa").text("Link Copiado");
                                            setTimeout(function() {
                                                $("#btnPesquisa").html("<i class='fas fa-copy' aria-hidden='true'></i>&nbsp; Copiar Link");
                                            }, 2000);
                                        });
                                    </script>
                                </div>
                            </div>
                        </fieldset> -->

                    <div class="row">
                        <div class="col-md-3" style="margin-top: 50px;">
                            <div style="border: 1px solid #cecece; border-radius: 5px;">
                                <div class="submenu-tabs">
                                    <button class="tab-button active" data-target="#assets" onclick="toggleTab(this)">ASSETS</button>
                                    <button class="tab-button" data-target="#templates" onclick="toggleTab(this)">TEMPLATES</button>
                                </div>
                                <div class="push20"></div>
                                <div class="tab-content" id="assets">
                                    <?php
                                    $sql = "SELECT * FROM CATEGORIA_BLOCOTEMPLATE ORDER BY NUM_ORDENAC";
                                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
                                    $countBlocos = 3;
                                    while ($qrListaCategor = mysqli_fetch_assoc($arrayQuery)) {
                                        $countBlocos++;
                                    ?>
                                        <div class="form-group">
                                            <h4 style="margin-left: 10px;"><?= $qrListaCategor['NOM_CATEGORIA'] ?></h4>
                                            <div class="push20"></div>
                                            <ul id="sortable<?= $countBlocos; ?>" class="connectedSortable sortableUi">
                                                <?php
                                                $sql = "SELECT * FROM BLOCOTEMPLATE WHERE COD_CATEGORIA = " . $qrListaCategor['COD_CATEGORIA'] . " ORDER BY NUM_ORDENAC";
                                                $arrayQueryBlocos = mysqli_query($connAdm->connAdm(), $sql);
                                                while ($qrListaBlocos = mysqli_fetch_assoc($arrayQueryBlocos)) {
                                                ?>
                                                    <li class="shadow grabbable text-center" style="padding: 12px" cod-bloco="<?= $qrListaBlocos['COD_BLTEMPL'] ?>">
                                                        <div class="text-center" style="border: 1px solid #cecece; border-radius: 5px; cursor: pointer; font-size: 26px; padding: 16px; width: 70px; display: block; margin: 0 auto;">
                                                            <span class="<?= $qrListaBlocos['DES_ICONE'] ?>"></span>
                                                        </div>
                                                        <div class="push10"></div>
                                                        <span><?= $qrListaBlocos['ABV_BLTEMPL'] ?></span>
                                                    </li>

                                                <?php } ?>
                                            </ul>
                                        </div>

                                    <?php } ?>
                                </div>
                                <div class="tab-content hidden" id="templates">
                                    <div id="listaTemplates" class="row" style="padding: 10px;">
                                        <?php
                                        $sql = "SELECT  * FROM TEMPLATE WHERE cod_empresa = $cod_empresa ORDER BY NOM_TEMPLATE";

                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                        $count = 0;
                                        while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                            $count++;
                                        ?>
                                            <div class="col-md-4">
                                                <div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
                                                    <a data-url="action.php?mod=<?php echo fnEncode(1113) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE']); ?>&tipo=<?php echo fnEncode('ALT') ?>&pop=true" data-title="Template" class="informer informer-default addBox" style="color: #2c3e50;">
                                                        <span class="fal fa-edit"></span>
                                                    </a>
                                                    <a href='action.php?mod=<?php echo fnEncode(1114) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE']) ?>' style='color: #2c3e50; border: none; text-decoration: none;'>
                                                        <div class="push30"></div>
                                                        <i class="fal fa-file-check fa-lg" style="font-size: 26px"></i>
                                                        <div class="push20"></div>
                                                        <p class="folder"><?php echo $qrBuscaModulos['NOM_TEMPLATE']; ?></p>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="template">
                                <ul id="sortable2" class="connectedSortable">
                                    <?php
                                    $sql = "SELECT MODELOTEMPLATETKT.COD_REGISTR,
															   MODELOTEMPLATETKT.COD_EMPRESA,
															   MODELOTEMPLATETKT.COD_TEMPLATE,
															   MODELOTEMPLATETKT.COD_BLTEMPL,
															   MODELOTEMPLATETKT.DES_IMAGEM,
															   MODELOTEMPLATETKT.DES_TEXTO
													    FROM   MODELOTEMPLATETKT
														WHERE  MODELOTEMPLATETKT.COD_EMPRESA = $cod_empresa 
														AND    MODELOTEMPLATETKT.COD_TEMPLATE = $cod_template
														AND    MODELOTEMPLATETKT.COD_EXCLUSA is null
														ORDER BY NUM_ORDENAC";

                                    //fnEscreve($sql);
                                    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                    while ($qrListaModelos = mysqli_fetch_assoc($arrayQuery)) {

                                        //fnEscreve($qrListaModelos['COD_BLTEMPL']); 

                                    ?>
                                        <li class="ui-state-default grabbable" cod-registr="<?php echo $qrListaModelos['COD_REGISTR'] ?>" cod-bloco="<?php echo $qrListaModelos['COD_BLTEMPL'] ?>">
                                            <?php
                                            switch ($qrListaModelos['COD_BLTEMPL']) {
                                                case 1: //nome do cliente
                                            ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">
                                                        <center>
                                                            <h3 style="margin: 5px; font-weight: 900">ISABEL,</h3>
                                                            <h5 style="margin: 5px; font-weight: 600"><small><b>Cliente Gold</b></small></h5>
                                                            <h5 style="margin-top: 5px">Está <b>esquecendo</b> de algo?</h5>
                                                            <!-- <hr class="divisao" /> -->
                                                            <center>

                                                    </div>
                                                <?php
                                                    break;
                                                case 2: //Produtos modelo 1
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">
                                                        <center>
                                                            <h5 style="margin-top: 5px">Veja <b>ofertas personalizadas</b> para você!</h5>
                                                        </center>
                                                        <div style="width: 100%;">
                                                            <div style="width: 55%; float: left; text-align: left;">
                                                                <h5 style="font-weight: 900">[Nome do produto]</h5>
                                                                <h5>[código]</h5>
                                                            </div>
                                                        </div>

                                                        <div style="width: 100%;">
                                                            <div style="width: 40%; float: right; text-align: left;">
                                                                <h5 style="font-weight: 900">de: R$ [de1]</h5>
                                                                <h5 style="font-weight: 900">por: R$ [por1]</h5>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <div style="width: 100%;">
                                                            <div style="width: 55%; float: left; text-align: left;">
                                                                <h5 style="font-weight: 900">[Nome do produto]</h5>
                                                                <h5>[código]</h5>
                                                            </div>
                                                        </div>

                                                        <div style="width: 100%;">
                                                            <div style="width: 40%; float: right; text-align: left;">
                                                                <h5 style="font-weight: 900">de: R$ [de2]</h5>
                                                                <h5 style="font-weight: 900">por: R$ [por2]</h5>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <div style="width: 100%;">
                                                            <div style="width: 55%; float: left; text-align: left;">
                                                                <h5 style="font-weight: 900">[Nome do produto]</h5>
                                                                <h5>[código]</h5>
                                                            </div>
                                                        </div>

                                                        <div style="width: 100%;">
                                                            <div style="width: 40%; float: right; text-align: left;">
                                                                <h5 style="font-weight: 900">de: R$ [de3]</h5>
                                                                <h5 style="font-weight: 900">por: R$ [por3]</h5>
                                                            </div>
                                                        </div>
                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 3: //lista de promoções black
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <center style="margin-bottom: 20px; padding: 5px; background-color: #161616; color: #fff">
                                                            <h5 style="font-weight: 900; margin-bottom: 20px; font-size: 17px;">OFERTA EM DESTAQUE</h5>
                                                            <h4 style="font-size: 21px; margin-top: 2px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
                                                            <h5 style="font-weight: 500; font-size:12px;">998765</h5>
                                                            <h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
                                                            <h4 style="margin-top: 20px;">Aproveite!</h4>
                                                        </center>

                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 4: //destaque
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <center>
                                                            <h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
                                                            <h6>Saldo: R$ 0,18</h6>
                                                            <h6>31/05/2017 às 10:00</h6>
                                                        </center>

                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 5: //rodape
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <center>
                                                            <h6 style="margin-right: 20px">Ofertas válidas até o término da campanha ou enquanto durar o estoque.</h6>
                                                            <div class="div-imagem">
                                                                <?php
                                                                if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
                                                                ?>
                                                                    <div class="imagemTicket">
                                                                        <button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
                                                                        <input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
                                                                    </div>
                                                                <?php
                                                                } else {
                                                                ?>
                                                                    <div class="imagemTicket">
                                                                        <img src='../media/clientes/<?php echo $cod_empresa ?>/<?php echo $qrListaModelos['DES_IMAGEM']; ?>' class='upload-image' style='cursor: pointer; max-width:100%; max-height: 100%'>
                                                                        <input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
                                                                    </div>

                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                            <h6 style="font-size: 11px">Ticket de Ofertas | Marka Sistemas</h6>
                                                        </center>
                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 6: //imagem
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <div class="div-imagem">
                                                            <?php
                                                            if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
                                                            ?>
                                                                <div class="imagemTicket">
                                                                    <button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
                                                                    <input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
                                                                </div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="imagemTicket">
                                                                    <img src='../media/clientes/<?php echo $cod_empresa ?>/<?php echo $qrListaModelos['DES_IMAGEM']; ?>' class='upload-image' style='cursor: pointer; max-width:100%; max-height: 100%'>
                                                                    <input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>

                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 7: //lista de promoções white
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <center style="margin-bottom: 15px; padding: 5px; background-color: #fff; color: #000">
                                                            <h5 style="font-weight: 900; margin-bottom: 20px; font-size: 17px;">OFERTA EM DESTAQUE</h5>
                                                            <h4 style="font-size: 21px; margin-top: 2px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
                                                            <h5 style="font-weight: 500; font-size:12px;">998765</h5>
                                                            <h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
                                                            <h4 style="margin-top: 20px;margin-bottom: 0">Aproveite!</h4>
                                                        </center>

                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 8: //habito de compras
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative; text-align: left; font-size: 13px">

                                                        <div>&emsp;•&emsp; LISADOR GTS 15ML</div>
                                                        <div>&emsp;•&emsp; VOLTAREN 100MG RET 10'S</div>
                                                        <div>&emsp;•&emsp; TORAGESIC 10MG 10'S</div>

                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 9: //promoções logo white
                                                case 19: //promoções logo white com percentual
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <center style="margin-bottom: 15px; padding: 5px; background-color: #fff; color: #000">
                                                            <h5 style="font-weight: 900; font-size: 17px;">OFERTA EM DESTAQUE</h5>
                                                            <img style='cursor: pointer; max-width:100%; max-height: 100%;'>
                                                            <i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
                                                            </img>
                                                            <h4 style="font-size: 21px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
                                                            <h5 style="font-weight: 500; font-size:12px;">998765</h5>
                                                            <h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
                                                            <?php if ($qrListaModelos['COD_BLTEMPL'] == 19) { ?>
                                                                <h4 style="font-weight: 900; font-size: 40px; margin-top: 2px"><strong>35%</strong></h4>
                                                            <?php } ?>
                                                            <h4 style="margin-top: 20px;margin-bottom: 0">Aproveite!</h4>
                                                        </center>

                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 10: //promoções logo black
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <center style="margin-bottom: 15px; padding: 5px; background-color: #161616; color: #fff">
                                                            <h5 style="font-weight: 900; font-size: 17px;">OFERTA EM DESTAQUE</h5>
                                                            <img style='cursor: pointer; max-width:100%; max-height: 100%;'>
                                                            <i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
                                                            </img>
                                                            <h4 style="font-size: 21px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
                                                            <h5 style="font-weight: 500; font-size:12px;">998765</h5>
                                                            <h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
                                                            <h4 style="margin-top: 20px;margin-bottom: 0">Aproveite!</h4>
                                                        </center>

                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 11: // saldo cartão
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <center>
                                                            <h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
                                                            <h6>Número Cartão: 1234 5678 9012 3456</h6>
                                                            <h6>Saldo: R$ 0,18</h6>
                                                            <h6>31/05/2017 às 10:00</h6>
                                                        </center>
                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 12: // grupo de desconto - 2 colunas
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative;">

                                                        <div class="row">
                                                            <div class="col-md-6" style="text-align: left; border-right: 1px dashed gray; padding-bottom: 20px;">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <h5 style="font-weight: 900; font-size: 50px; letter-spacing: -3px;">15%
                                                                            <br /><span style='background-color: #454545; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span>
                                                                            <div class="push10"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div style="font-size: 11px; text-transform: uppercase; margin-top: -10px; margin-left: 5px"><b>[Nome da Categoria]</b></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 1</b><br /><small>[código]</small></div>
                                                                        <div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 2</b><br /><small>[código]</small></div>
                                                                        <div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 3</b><br /><small>[código]</small></div>
                                                                        <div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 4</b><br /><small>[código]</small></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6" style="text-align: left; border-right: 1px dashed gray; padding-bottom: 20px;">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <h5 style="font-weight: 900; font-size: 50px; letter-spacing: -3px;">25%
                                                                            <br /><span style='background-color: #454545; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span>
                                                                            <div class="push10"></div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div style="font-size: 11px; text-transform: uppercase; margin-top: -10px; margin-left: 5px"><b>[Nome da Categoria]</b></div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 1</b><br /><small>[código]</small></div>
                                                                        <div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 2</b><br /><small>[código]</small></div>
                                                                        <div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 3</b><br /><small>[código]</small></div>
                                                                        <div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 4</b><br /><small>[código]</small></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;

                                                case 21: //grupo de desconto - 1 coluna
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">
                                                        <center>
                                                            <h5 style="margin-top: 5px">Veja <b>ofertas personalizadas </b> para você!</h5>
                                                        </center>

                                                        <div style="width: 70%;  float:left;">

                                                            <div style="width: 100%; height: auto; text-align: left; margin: 0 0 20px 0;">
                                                                <h5 style="font-weight: 900">[Nome da Categoria]</h5>
                                                                <h5 style="font-weight: 900">[Nome do produto]</h5>
                                                                <h5>[código]</h5>
                                                            </div>

                                                            <div style="width: 100%; height: auto; text-align: left; margin: 0 0 20px 0;">
                                                                <h5 style="font-weight: 900">[Nome do produto]</h5>
                                                                <h5>[código]</h5>
                                                            </div>

                                                            <div style="width: 100%; height: auto; text-align: left; margin: 0 0 20px 0;">
                                                                <h5 style="font-weight: 900">[Nome do produto]</h5>
                                                                <h5>[código]</h5>
                                                            </div>

                                                        </div>

                                                        <div style="width: 29%; float:right;">
                                                            <div style="width: 100%; text-align: center;">
                                                                <h5 style="font-weight: 900; font-size: 50px; letter-spacing: -3px;">15%
                                                                    <br /><span style='background-color: #454545; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span>
                                                            </div>
                                                        </div>
                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;

                                                case 15: //Código com Número
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <center>
                                                            <img style='cursor: pointer; max-width:100%; max-height: 100%' src="/images/codigo-barras.png" width="320" height="70"></img>
                                                            <h5 style="margin-bottom: 0px">1234567890123</h5>
                                                        </center>
                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 16: //Código sem Número
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <center>
                                                            <img style='cursor: pointer; max-width:100%; max-height: 100%' src="/images/codigo-barras.png" width="320" height="70"></img>
                                                        </center>

                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 17: //Última Compra
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin-top: -10px;" aria-hidden="true"></i>
                                                    </a>
                                                    <div style="position: relative">

                                                        <center><small>Dat. Referência: 01/01/2017</small></center>
                                                    </div>
                                                <?php
                                                    break;
                                                case 18: //Produtos modelo 2 (desconto white)
                                                case 20: //Produtos modelo 3 (desconto black)
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">

                                                        <center>
                                                            <h5 style="margin-top: 5px">Veja <b>ofertas personalizadas </b> para você!</h5>
                                                        </center>

                                                        <?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
                                                            <div style="background: #000; display: flex; color: #fff; padding: 0 0 0 5px; margin: 0 0 10px 0;">
                                                            <?php } ?>

                                                            <div style="width: 70%;  float:left;">
                                                                <div style="width: 100%; height: auto; text-align: left;">
                                                                    <h5 style="font-weight: 900">[Nome da Categoria 3]</h5>
                                                                    <h5 style="font-weight: 900">[Nome do produto]</h5>
                                                                    <h5 style="font-weight: 900">de: R$ [de1] por: R$ [por1]</h5>
                                                                    <h5>[código]</h5>
                                                                </div>
                                                            </div>

                                                            <div style="width: 29%; float:right;">
                                                                <div style="width: 100%; text-align: center;">
                                                                    <h5 style="font-weight: 900; font-size: 45px; letter-spacing: -3px;">35%</h5>
                                                                </div>
                                                            </div>

                                                            <?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
                                                            </div>
                                                        <?php } else {
                                                        ?>
                                                            <hr />
                                                        <?php } ?>

                                                        <?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
                                                            <div style="background: #000; display: flex; color: #fff; padding: 0 0 0 5px; margin: 0 0 10px 0;">
                                                            <?php } ?>

                                                            <div style="width: 70%;  float:left;">
                                                                <div style="width: 100%; height: auto; text-align: left;">
                                                                    <h5 style="font-weight: 900">[Nome da Categoria]</h5>
                                                                    <h5 style="font-weight: 900">[Nome do produto]</h5>
                                                                    <h5 style="font-weight: 900">de: R$ [de1] por: R$ [por1]</h5>
                                                                    <h5>[código]</h5>
                                                                </div>
                                                            </div>

                                                            <div style="width: 29%; float:right;">
                                                                <div style="width: 100%; text-align: center;">
                                                                    <h5 style="font-weight: 900; font-size: 45px; letter-spacing: -3px;">35%</h5>
                                                                </div>
                                                            </div>

                                                            <?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
                                                            </div>
                                                        <?php } else {
                                                        ?>
                                                            <hr />
                                                        <?php } ?>

                                                        <?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
                                                            <div style="background: #000; display: flex; color: #fff; padding: 0 0 0 5px; margin: 0 0 10px 0;">
                                                            <?php } ?>

                                                            <div style="width: 70%;  float:left;">
                                                                <div style="width: 100%; height: auto; text-align: left;">
                                                                    <h5 style="font-weight: 900">[Nome da Categoria]</h5>
                                                                    <h5 style="font-weight: 900">[Nome do produto]</h5>
                                                                    <h5 style="font-weight: 900">de: R$ [de1] por: R$ [por1]</h5>
                                                                    <h5>[código]</h5>
                                                                </div>
                                                            </div>

                                                            <div style="width: 29%; float:right;">
                                                                <div style="width: 100%; text-align: center;">
                                                                    <h5 style="font-weight: 900; font-size: 45px; letter-spacing: -3px;">35%</h5>
                                                                </div>
                                                            </div>

                                                            <?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
                                                            </div>
                                                        <?php } else {
                                                        ?>
                                                            <hr />
                                                        <?php } ?>
                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 22: //texto livre
                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">
                                                        <div class="div-texto">

                                                            <?php
                                                            if ($qrListaModelos['DES_TEXTO'] != "") {
                                                                $qrListaModelos['DES_TEXTO'] = html_entity_decode($qrListaModelos['DES_TEXTO']);
                                                                $qrListaModelos['DES_TEXTO'] = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $qrListaModelos['DES_TEXTO']);
                                                            ?>
                                                                <a href="javascript:void(0)" onclick="buscaTexto(<?= $qrListaModelos['COD_REGISTR'] ?>)" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' style="text-decoration: none;">
                                                                    <?= $qrListaModelos['DES_TEXTO'] ?>
                                                                </a>

                                                            <?php
                                                            } else {
                                                            ?>

                                                                <div style="height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;">
                                                                    <a href="javascript:void(0)" class="btn btn-block btn-success" onclick="buscaTexto(<?= $qrListaModelos['COD_REGISTR'] ?>)" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' style="color: white; text-decoration: none;"><span class="far fa-text-height"></span>&nbsp;&nbsp; Insira aqui seu texto</a>
                                                                    <input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
                                                                </div>

                                                            <?php
                                                            }
                                                            ?>

                                                        </div>
                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                                <?php
                                                    break;
                                                case 23: //Aniversariante

                                                    $sqlMsg = "SELECT * FROM COMUNICACAO_MODELO_TKT WHERE COD_EMPRESA = $cod_empresa LIMIT 1";
                                                    // echo($sql);
                                                    $arrayMsg = mysqli_query(connTemp($cod_empresa, ""), $sqlMsg);

                                                    $qrBuscaComunicacao = mysqli_fetch_assoc($arrayMsg);

                                                    $temMsg = mysqli_num_rows($arrayMsg);

                                                    $msg = $qrBuscaComunicacao['DES_TEXTO_SMS'];

                                                    $dia_hoje = date('d');
                                                    $mes_hoje = date('m');
                                                    $ano_hoje = date('Y');
                                                    $dia_nascime = $dia_hoje;
                                                    $mes_nascime = $mes_hoje;
                                                    $ano_nascime = '2000';

                                                    $NOM_CLIENTE = explode(" ", ucfirst(strtolower("Fulano")));
                                                    $TEXTOENVIO = str_replace('<#NOME>', $NOM_CLIENTE['0'], $msg);
                                                    $TEXTOENVIO = str_replace('<#SALDO>', fnValor('9.99', 2), $TEXTOENVIO);
                                                    $TEXTOENVIO = str_replace('<#NOMELOJA>',  'Unidade Tal', $TEXTOENVIO);
                                                    $TEXTOENVIO = str_replace('<#ANIVERSARIO>', '01/01/2000', $TEXTOENVIO);
                                                    $TEXTOENVIO = str_replace('<#DATAEXPIRA>', '01/01/2999', $TEXTOENVIO);
                                                    $TEXTOENVIO = str_replace('<#EMAIL>', 'exemplo@email.com', $TEXTOENVIO);
                                                    $msgsbtr = nl2br($TEXTOENVIO, true);
                                                    $msgsbtr = str_replace('<br />', ' \n ', $msgsbtr);
                                                    $msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);


                                                ?>
                                                    <a class="excluirBloco">
                                                        <i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i> Apagar
                                                    </a>
                                                    <div style="position: relative">
                                                        <div class="div-aniv">
                                                            <?php
                                                            if ($msgsbtr != '') {
                                                            ?>
                                                                <div class="imagemTicket text-center f18">
                                                                    <a href="javascript:void(0)" class="addBox" data-url="action.do?mod=<?php echo fnEncode(1916) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?= fnEncode($qrBuscaComunicacao['COD_COMUNIC']) ?>&pop=true" data-title="Editar Texto"><?= $msgsbtr ?></a>
                                                                </div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="imagemTicket">
                                                                    <a href="javascript:void(0)" class="btn btn-block btn-xs btn-info addBox" data-url="action.do?mod=<?php echo fnEncode(1916) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?= fnEncode($qrBuscaComunicacao['COD_COMUNIC']) ?>&pop=true" data-title="Editar Texto"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp; Configurar</a>
                                                                    <input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <!-- <hr class="divisao" /> -->
                                                    </div>
                                            <?php
                                                    break;
                                            }

                                            ?>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-top: 50px;">
                            <div class="row" style="border: 1px solid #cecece; border-radius: 5px;">
                                <div class="submenu-tabs2">
                                    <button class="tab-button2 active" data-target="#simulador" onclick="toggleTab(this)">SIMULADOR</button>
                                    <button class="tab-button2" data-target="#configuracoes" onclick="toggleTab(this)">CONFIGURAÇÕES</button>
                                </div>
                                <div class="push20"></div>
                                <div class="row tab-content" id="simulador" style="padding: 10px;">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">CNPJ/CPF</label>
                                            <input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="" maxlength="18" data-error="Campo obrigatório" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Unidade de Atendimento </label>
                                            <?php $showAll = "no";
                                            include "unidadesAutorizadasCombo.php"; ?>
                                        </div>
                                    </div>

                                    <div class="push10"></div>

                                    <div class="form-group text-right col-lg-12">

                                        <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                                        <a href='javascript:void(0)' id="BTN_CONSULTACLI" name="BTN_CONSULTACLI" class="btn btn-primary getBtn">Consultar Cliente</a>

                                    </div>

                                    <div class="push20"></div>

                                    <div class="col-md-12" id="divIframe" style="display: none;">

                                        <span><b>Preview do ticket</b></span>
                                        <div class="push20"></div>
                                        <iframe src="" width="100%" height="800px" frameborder="0"></iframe>
                                    </div>

                                </div>

                                <div id="configuracoes" class="tab-content hidden" style="padding: 10px;">
                                    <div id="formulario">

                                        <?php
                                        $sql = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA = '" . $cod_empresa . "' ";

                                        //fnEscreve($sql);
                                        //fnTesteSql(connTemp($cod_empresa,""),trim($sql));

                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));
                                        $qrBuscaConfiguracao = mysqli_fetch_assoc($arrayQuery);

                                        //print_r($arrayQuery);	

                                        if (isset($qrBuscaConfiguracao)) {
                                            $cod_configu = $qrBuscaConfiguracao['COD_CONFIGU'];
                                            $log_ativo_tkt = $qrBuscaConfiguracao['LOG_ATIVO_TKT'];
                                            if ($log_ativo_tkt == "S") {
                                                $mostraLOG_ATIVO_TKT = "checked";
                                            } else {
                                                $mostraLOG_ATIVO_TKT = "";
                                            }
                                            $log_emisdia = $qrBuscaConfiguracao['LOG_EMISDIA'];
                                            if ($log_emisdia == "S") {
                                                $mostraLOG_EMISDIA = "checked";
                                            } else {
                                                $mostraLOG_EMISDIA = "";
                                            }
                                            $cod_template_tkt = $qrBuscaConfiguracao['COD_TEMPLATE_TKT'];
                                            $qtd_compras_tkt = $qrBuscaConfiguracao['QTD_COMPRAS_TKT'];
                                            $qtd_ofertas_tkt = $qrBuscaConfiguracao['QTD_OFERTAS_TKT'];
                                            $qtd_ofertws_tkt = $qrBuscaConfiguracao['QTD_OFERTWS_TKT'];
                                            $qtd_ofertas_lst = $qrBuscaConfiguracao['QTD_OFERTAS_LST'];
                                            $qtd_categor_tkt = $qrBuscaConfiguracao['QTD_CATEGOR_TKT'];
                                            $qtd_produtos_tkt = $qrBuscaConfiguracao['QTD_PRODUTOS_TKT'];
                                            $qtd_produtos_cat = $qrBuscaConfiguracao['QTD_PRODUTOS_CAT'];
                                            $num_historico_tkt = $qrBuscaConfiguracao['NUM_HISTORICO_TKT'];
                                            $min_historico_tkt = $qrBuscaConfiguracao['MIN_HISTORICO_TKT'];
                                            $max_historico_tkt = $qrBuscaConfiguracao['MAX_HISTORICO_TKT'];
                                            $cod_blklist = $qrBuscaConfiguracao['COD_BLKLIST'];
                                            $des_pratprc = $qrBuscaConfiguracao['DES_PRATPRC'];
                                            $des_validade = $qrBuscaConfiguracao['DES_VALIDADE'];
                                            $log_listaws = $qrBuscaConfiguracao['LOG_LISTAWS'];
                                            if ($log_listaws == "S") {
                                                $mostraLOG_LISTAWS = "checked";
                                            } else {
                                                $mostraLOG_LISTAWS = "";
                                            }
                                        } else {
                                            $cod_configu = 0;
                                            $log_ativo_tkt = "";
                                            $log_emisdia = "";
                                            $cod_template_tkt = 0;
                                            $qtd_compras_tkt = "";
                                            $qtd_ofertas_tkt = "";
                                            $qtd_ofertws_tkt = "";
                                            $qtd_ofertas_lst = "";
                                            $qtd_categor_tkt = "";
                                            $qtd_produtos_tkt = "1";
                                            $qtd_produtos_cat = "1";
                                            $num_historico_tkt = "";
                                            $min_historico_tkt = "0";
                                            $max_historico_tkt = "30";
                                            $cod_blklist = "0";
                                            $des_validade = "0";
                                            $mostraLOG_EMISDIA = '';
                                            $mostraLOG_ATIVO_TKT = '';
                                            $mostraLOG_LISTAWS = '';
                                        }

                                        ?>


                                        <div class="push20"></div>
                                        <span><b>Dados da Template</b></span>
                                        <div class="push10"></div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label">Empresa</label>
                                                    <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
                                                    <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label">Código Template</label>
                                                    <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CATEGORTKT" id="COD_CATEGORTKT" value="<?php echo $cod_template ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label">Nome da Template</label>
                                                    <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_TEMPLATE" id="NOM_TEMPLATE" value="<?php echo $nom_template ?>" maxlength="20">
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label">Abreviação</label>
                                                    <input type="text" class="form-control input-sm leitura" readonly="readonly" name="ABV_TEMPLATE" id="ABV_TEMPLATE" value="<?php echo $abv_template ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="disabledBlock"></div>
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label">Ativo</label>
                                                    <div class="push5"></div>
                                                    <label class="switch">
                                                        <input type="checkbox" name="LOG_DESTAK" id="LOG_DESTAK" class="switch" value="S" <?php echo $mostraLog_ativo; ?>>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="push20"></div>
                                        <span><b>Configurações do Ticket</b></span>
                                        <div class="push10"></div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label LBL_EMISDIA">Validade do Ticket</label>
                                                    <select data-placeholder="Selecione uma prática de preço" name="DES_VALIDADE" id="DES_VALIDADE" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1">
                                                        <option></option>
                                                        <option value="1">1 dia</option>
                                                        <option value="2">2 dias</option>
                                                        <option value="3">3 dias</option>
                                                        <option value="4">4 dias</option>
                                                        <option value="5">5 dias</option>
                                                        <option value="6">6 dias</option>
                                                        <option value="7">7 dias</option>
                                                    </select>
                                                    <script>
                                                        $("#formulario #DES_VALIDADE").val("<?php echo $des_validade; ?>").trigger("chosen:updated");
                                                    </script>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label required">Prática de preço</label>
                                                    <select data-placeholder="Selecione uma prática de preço" name="DES_PRATPRC" id="DES_PRATPRC" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
                                                        <option></option>
                                                        <option value="Gestão ofertas" disabled>Gestão de ofertas MARKA</option>
                                                        <option value="Sistema PDV">Sistema de PDV/ERP</option>
                                                        <option value="Menor" disabled>Menor preço</option>
                                                    </select>
                                                    <script>
                                                        $("#formulario #DES_PRATPRC").val("<?php echo $des_pratprc; ?>").trigger("chosen:updated");
                                                    </script>
                                                    <div class="help-block with-errors"></div>
                                                </div>

                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label required">Modelo do template do ticket</label>

                                                    <select data-placeholder="Selecione um modelo de ticket" name="COD_TEMPLATE_TKT" id="COD_TEMPLATE_TKT" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
                                                        <option></option>
                                                        <?php
                                                        $sql = "SELECT  * FROM TEMPLATE WHERE cod_empresa = $cod_empresa ORDER BY NOM_TEMPLATE ";
                                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                                        while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

                                                            if ($qrListaPersonas['LOG_ATIVO'] != "S") {
                                                                $desabilitado = "disabled";
                                                            } else {
                                                                $desabilitado = "";
                                                            }

                                                            echo "
															  <option value='" . $qrListaPersonas['COD_TEMPLATE'] . "' " . $desabilitado . ">" . ucfirst($qrListaPersonas['NOM_TEMPLATE']) . "</option> 
															";
                                                        }
                                                        ?>
                                                    </select>
                                                    <script>
                                                        $("#formulario #COD_TEMPLATE_TKT").val("<?php echo $cod_template_tkt; ?>").trigger("chosen:updated");
                                                    </script>
                                                    <div class="help-block with-errors"></div>
                                                </div>

                                            </div>


                                        </div>

                                        <div class="push20"></div>
                                        <span><b>Quantidades</b></span>
                                        <div class="push10"></div>
                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label ">Quantidade de produtos <br /> no hábito de compra</label>
                                                    <input type="text" class="form-control input-sm text-center int" name="QTD_COMPRAS_TKT" id="QTD_COMPRAS_TKT" maxlength="2" value="<?php echo $qtd_compras_tkt; ?>">
                                                    <div class="help-block with-errors"></div>
                                                    <span class="help-block"></span>
                                                    <!--<span class="help-block">Leve também</span>-->
                                                </div>
                                            </div>

                                            <?php
                                            $sql = "select COUNT(COD_CATEGORTKT) AS QTD_CATEGORIAS from CATEGORIATKT where COD_EMPRESA = '" . $cod_empresa . "' AND DAT_EXCLUSA IS NULL";
                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            $qrListaPersonas = mysqli_fetch_assoc($arrayQuery);
                                            $qtd_categorias = $qrListaPersonas['QTD_CATEGORIAS'];

                                            ?>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label" style="margin-bottom: 4px;">Categorias Ativas</label>
                                                    <div class="push15"></div>
                                                    <input type="text" class="form-control input-sm text-center int" readonly="readonly" name="QTD_CATEGORIAS" id="QTD_CATEGORIAS" maxlength="2" value="<?php echo $qtd_categorias; ?>">
                                                    <div class="help-block with-errors"></div>
                                                    <!--<span class="help-block">Lista de ofertas</span>-->
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label ">Quantidade <b>máxima</b> de produtos<br />em cada categoria</label>
                                                    <input type="text" class="form-control input-sm text-center int" name="QTD_PRODUTOS_CAT" id="QTD_PRODUTOS_CAT" maxlength="2" value="<?php echo $qtd_produtos_cat; ?>">
                                                    <div class="help-block with-errors"></div>
                                                    <span class="help-block"></span>
                                                    <!--<span class="help-block">Lista de ofertas</span>-->
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label ">Quantidade <b>máxima</b> de<br />produtos apresentados</label>
                                                    <input type="text" class="form-control input-sm text-center int" name="QTD_PRODUTOS_TKT" id="QTD_PRODUTOS_TKT" maxlength="2" value="<?php echo $qtd_produtos_tkt; ?>">
                                                    <div class="help-block with-errors"></div>
                                                    <span class="help-block">Necessário produtos ativos e na validade</span>
                                                    <!--<span class="help-block">Lista de ofertas</span>-->
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label ">Quantidade de <br />ofertas em destaque</label>
                                                    <input type="text" class="form-control input-sm text-center int" name="QTD_OFERTAS_TKT" id="QTD_OFERTAS_TKT" maxlength="2" value="<?php echo $qtd_ofertas_tkt; ?>">
                                                    <div class="help-block with-errors"></div>
                                                    <span class="help-block"></span>
                                                    <!--<span class="help-block">Oferta em destaque</span>-->
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label ">Quantidade da lista <br />adicional de ofertas</label>
                                                    <input type="text" class="form-control input-sm text-center int" name="QTD_OFERTWS_TKT" id="QTD_OFERTWS_TKT" maxlength="2" value="<?php echo $qtd_ofertws_tkt; ?>">
                                                    <div class="help-block with-errors"></div>
                                                    <span class="help-block">Web service</span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="push20"></div>
                                        <span><b>Hábito</b></span>
                                        <div class="push10"></div>
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label">Hábitos de exclusão</label>

                                                    <select data-placeholder="Selecione um ou mais hábitos de consumo para exclusão" name="COD_BLKLIST[]" id="COD_BLKLIST" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
                                                        <?php
                                                        $sql = "SELECT COD_BLKLIST," .
                                                            "ABV_BLKLIST " .
                                                            "FROM blacklisttkt where COD_EMPRESA = $cod_empresa and COD_EXCLUSA = 0 ";

                                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                                        while ($qrListaBlkList = mysqli_fetch_assoc($arrayQuery)) {
                                                            echo "
															  <option value='" . $qrListaBlkList['COD_BLKLIST'] . "'>" . ucfirst($qrListaBlkList['ABV_BLKLIST']) . "</option> 
															";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="help-block with-errors"></div>
                                                    <script>
                                                        //retorno combo multiplo
                                                        if ("<?php echo $cod_blklist; ?>" != "0") {
                                                            var sistemasHab = "<?php echo $cod_blklist; ?>";
                                                            var sistemasHabArr = sistemasHab.split(',');
                                                            //opções multiplas
                                                            for (var i = 0; i < sistemasHabArr.length; i++) {
                                                                $("#formulario #COD_BLKLIST option[value=" + sistemasHabArr[i] + "]").prop("selected", "true");
                                                            }
                                                            $("#formulario #COD_BLKLIST").trigger("chosen:updated");
                                                        } else {
                                                            $("#formulario #COD_BLKLIST").val('').trigger("chosen:updated");
                                                        }
                                                    </script>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="push20"></div>
                                        <span><b>Período de Busca</b></span>
                                        <div class="push10"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label required">Período de busca no histórico de compras do cliente</label>
                                                    <div class="push10"></div>
                                                    <input type="text" name="NUM_HISTORICO_TKT[]" id="NUM_HISTORICO_TKT" value="" />
                                                    <div class="push30"></div>
                                                    <span class="help-block">Intervalo de dias</span>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="push20"></div>
                                        <span><b>Outros</b></span>
                                        <div class="push10"></div>
                                        <div class="row">
                                            <div class="col-md-3 text-center">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label">Ticket ativo</label>
                                                    <div class="push5"></div>
                                                    <label class="switch">
                                                        <input type="checkbox" name="LOG_ATIVO_TKT" id="LOG_ATIVO_TKT" class="switch" value="S" <?php echo $mostraLOG_ATIVO_TKT; ?>>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-3 text-center">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label">Retorna Lista WS</label>
                                                    <div class="push5"></div>
                                                    <label class="switch">
                                                        <input type="checkbox" name="LOG_LISTAWS" id="LOG_LISTAWS" class="switch" value="S" <?php echo $mostraLOG_LISTAWS; ?>>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-3 text-center">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label">Emissão Diária</label>
                                                    <div class="push5"></div>
                                                    <label class="switch">
                                                        <input type="checkbox" name="LOG_EMISDIA" id="LOG_EMISDIA" class="switch" value="S" <?php echo $mostraLOG_EMISDIA; ?>>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="push20"></div>

                                        <input type="hidden" name="COD_CONFIGU" id="COD_CONFIGU" value="<?php echo $cod_configu; ?>">
                                        <input type="hidden" name="QTD_OFERTAS_LST" id="QTD_OFERTAS_LST" value="0">
                                        <input type="hidden" name="QTD_CATEGOR_TKT" id="QTD_CATEGOR_TKT" value="0">

                                        <div class="alt-alert" id="alt-alert"></div>

                                        <div class="form-group text-right col-lg-12">

                                            <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                                            <?php
                                            if ($cod_configu == "0") { ?>
                                                <a href='javascript:void(0)' name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</a>
                                            <?php } else { ?>
                                                <a href='javascript:void(0)' name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Atualizar Configuração</a>
                                            <?php } ?>

                                        </div>
                                        <div class="push20"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="push20"></div>

                            <div class="row" id="DIV_COMENTARIO" style="border: 1px solid #cecece; border-radius: 5px; display: none;">
                                <div class="push10"></div>
                                <div class="col-md-12 clearfix">
                                    <h4 class="pull-left">Editar Texto</h4>
                                    <i class="fal fa-times pull-right" style="font-size: 24px; margin-top: 12px;"></i>
                                </div>
                                <div class="push10"></div>
                                <div id="alert-comentario" style="padding: 12px;"></div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea class="editor form-control input-sm" rows="6" name="DES_COMENTARIO" id="DES_COMENTARIO"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="push20"></div>

                                <div class="form-group text-right col-lg-12">
                                    <a href='javascript:void(0)' name="CAD_COMENTARIO" id="CAD_COMENTARIO" class="btn btn-primary getBtn">Salvar</a>
                                </div>
                                <div class="push20"></div>

                                <input type="hidden" name="COD_REGISTR" id="COD_REGISTR" value="">

                            </div>

                            <div class="push20"></div>
                        </div>

                    </div>


                    <div class="100"></div>
                    <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                    <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

                    <div class="push5"></div>


                    <div class="push100"></div>



                </div>

            </div>
        </div>
        <!-- fim Portlet -->
    </div>



</div>


<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1' style="margin: auto;">
    <div class="modal-dialog" style="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe frameborder="0" style="width: 100%; height: 86%"></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="push20"></div>

<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css" />
<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/plugins/ion.rangeSlider.js"></script>
<link rel="stylesheet" href="css/ion.rangeSlider.css" />
<link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.css" />

<script type="text/javascript">
    $(document).ready(function() {

        $('#CAD, #ALT').on('click', function() {
            let opcao = $(this).attr('id');
            let msg = "";
            if (opcao === 'CAD') {
                msg = 'Registro gravado com <strong>sucesso!</strong>';
            } else if (opcao === 'ALT') {
                msg = 'Registro alterado com <strong>sucesso!</strong>';
            }
            $.ajax({
                type: "POST",
                url: "ajxModeloTemplate.do?opcao=" + opcao + "&id=<?php echo fnEncode($cod_empresa) ?>",
                data: $("#formulario").serialize(),
                success: function(data) {
                    let alert = "<div class='alert alert-dismissible alert-success' role='alert' id='msgRetorno'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + msg + "</div><div class='push20'></div>";
                    $("#alt-alert").html(alert);
                    setInterval(function() {
                        $("#alt-alert").hide();
                    }, 5000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    msg = 'Erro ao processar a requisição: ' + jqXHR.responseText + '. Se persistir, entre em contato com o suporte.';
                    let alert = "<div class='alert alert-dismissible alert-danger' role='alert' id='msgRetorno'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + msg + "</div><div class='push20'></div>";
                    $("#alt-alert").html(alert);
                    setInterval(function() {
                        $("#alt-alert").hide();
                    }, 5000);
                }
            });
        });

        $('body').on('click', '.upload-image', function() {
            $(this).siblings().click();
        });

        $('body').on('change', '.image-file', function() {
            var formData = new FormData();
            formData.append('arquivo', $(this)[0].files[0]);
            formData.append('id', "<?= fnEncode($cod_empresa) ?>");
            formData.append('cod_registr', $(this).attr('cod_registr'));

            var div_imagem = $(this).parent().parent();

            $.ajax({
                url: 'uploads/uploadpro.php',
                type: 'POST',
                data: formData,
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType
                success: function(data) {
                    div_imagem.html(data);
                }
            });
        });

        //icon picker
        $('.btnSearchIcon').iconpicker({
            cols: 8,
            iconset: 'fontawesome',
            rows: 6,
            searchText: 'Procurar  &iacute;cone'
        });

        $('.btnSearchIcon').on('change', function(e) {
            //console.log(e.icon);
            $("#DES_ICONES").val(e.icon);
        });

        $("[id^='sortable']:not(#sortable2)").sortable({
            connectWith: ".connectedSortable",
            remove: function(event, ui) {
                var idTem = <?php echo $cod_template ?>;
                var idEmp = <?php echo $cod_empresa ?>;
                var codBloco = ui.item.attr('cod-bloco');
                var cod_registr = "";

                $.ajax({
                    type: "GET",
                    url: "ajxBlocoTkt.do",
                    data: {
                        ajx1: idEmp,
                        ajx2: 0,
                        ajx3: idTem,
                        ajx4: codBloco
                    },
                    success: function(data) {
                        cod_registr = data.trim();
                        var indice = ui.item.index();
                        ui.item.clone().attr('cod-registr', data.trim()).removeClass('shadow').insertBefore($('#sortable2 li').eq(indice));
                        $(event.target).sortable('cancel');

                        $.ajax({
                            type: "GET",
                            url: "ajxBlocoTkt.do",
                            data: {
                                ajx1: idEmp,
                                ajx2: codBloco,
                                ajx3: idTem,
                                ajx4: cod_registr
                            },
                            beforeSend: function() {
                                $('#sortable2 li[cod-registr=' + cod_registr + ']').html('<div class="loading" style="width: 100%;"></div>');
                            },
                            success: function(data) {
                                console.log(data);
                                $('#sortable2 li[cod-registr=' + cod_registr + ']').html(data);
                                ordenar();
                            },
                            error: function() {
                                $('#sortable2 li[cod-registr=' + cod_registr + ']').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
                            }
                        });
                    }
                });
            }
        }).disableSelection();


        $("#sortable2").sortable({
            connectWith: ".connectedSortable",
            stop: function(event, ui) {
                ordenar();
            }
        }).disableSelection();

        $('body').on('click', '.excluirBloco', function() {
            var cod_registr = $(this).parents('.ui-state-default').attr('cod-registr');
            var _this = $(this).parents('.ui-state-default');
            var idEmp = <?php echo $cod_empresa ?>;

            $.ajax({
                type: "GET",
                url: "ajxBlocoTkt.do",
                data: {
                    ajx1: idEmp,
                    ajx2: 99,
                    ajx3: cod_registr
                },
                beforeSend: function() {
                    $('#sortable2 li[cod-registr=' + cod_registr + ']').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    _this.remove();
                    $('#sortable2 li[cod-registr=' + cod_registr + ']').html(data);
                },
                error: function() {
                    $('#sortable2 li[cod-registr=' + cod_registr + ']').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
                }
            });
        });

        $('#CAD_COMENTARIO').on('click', function() {
            let codRegistro = $("#COD_REGISTR").val();
            let texto = $('#DES_COMENTARIO').val();
            if (texto.trim() === "") {
                alert("O campo de texto não pode estar vazio.");
                return;
            }

            $.ajax({
                type: "POST",
                url: "ajxModeloTemplate.do?opcao=salvaTexto&id=<?php echo fnEncode($cod_empresa) ?>",
                data: {
                    idr: codRegistro,
                    DES_COMENTARIO: texto
                },
                success: function(data) {
                    let alert = "<div class='alert alert-dismissible alert-success' role='alert' id='msgRetorno'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Texto inserido com <strong>sucesso!</strong></div><div class='push20'></div>";
                    $("#alert-comentario").html(alert);
                    setInterval(function() {
                        window.location.reload();
                    }, 3000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Erro ao salvar o texto: " + jqXHR.responseText);
                }
            });
        });
    });

    function buscaTexto(cod_registro) {
        let codRegistro = cod_registro;
        $.ajax({
            type: "POST",
            url: "ajxModeloTemplate.do?opcao=buscaTexto&id=<?php echo fnEncode($cod_empresa) ?>",
            data: {
                idr: codRegistro,
            },
            success: function(data) {
                $('#DES_COMENTARIO').jqteVal(data);
                $("#COD_REGISTR").val(codRegistro);
                $('#DIV_COMENTARIO').show();
                $('.jqte_editor').focus();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // console.log(jqXHR.responseText);
            }
        });
    };



    function toggleTab(element) {
        var container = element.parentElement;

        // Ativar botão no grupo
        var buttons = container.querySelectorAll("button");
        buttons.forEach(function(btn) {
            btn.classList.remove("active");
        });
        element.classList.add("active");

        // Esconder conteúdos do grupo
        var parent = container.parentElement;
        var contents = parent.querySelectorAll(".tab-content");
        contents.forEach(function(content) {
            content.classList.add("hidden");
        });

        // Mostrar conteúdo do botão clicado
        var targetSelector = element.getAttribute("data-target");
        var targetContent = parent.querySelector(targetSelector);
        if (targetContent) {
            targetContent.classList.remove("hidden");
        }
    }


    function ordenar() {
        var ids = "";
        $('#sortable2 li.ui-state-default').each(function(index) {
            ids += $(this).attr('cod-registr') + ",";
        });

        var arrayOrdem = ids.substring(0, (ids.length - 1));
        execOrdenacao(arrayOrdem, 2);

        function execOrdenacao(p1, p2) {
            var codEmpresa = <?php echo $cod_empresa ?>;
            $.ajax({
                type: "GET",
                url: "ajxOrdenacaoEmp.do",
                data: {
                    ajx1: p1,
                    ajx2: p2,
                    ajx3: codEmpresa
                },
                beforeSend: function() {
                    //$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
                },
                success: function(data) {
                    //$("#divId_sub").html(data); 
                },
                error: function() {
                    //$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
                }
            });
        }
    }

    $(function() {

        $("#NUM_HISTORICO_TKT").ionRangeSlider({
            hide_min_max: true,
            keyboard: true,
            min: 0,
            max: 120,
            from: <?php echo $min_historico_tkt; ?>,
            to: <?php echo $max_historico_tkt; ?>,
            type: 'int',
            step: 5,
            //prettify_enabled: true,
            //prettify_separator: "."
            //prefix: "Idade ",
            postfix: " dias",
            max_postfix: ""
            //grid: true
        });
        /*
        $("#range").ionRangeSlider();
        */

    });

    $(function() {
        var totalChars = 1000;
        // TextArea
        $(".editor").jqte({
            sup: false,
            sub: false,
            outdent: false,
            indent: false,
            left: true,
            center: true,
            color: false,
            right: true,
            strike: true,
            source: false,
            link: true,
            unlink: false,
            remove: false,
            rule: false,
            fsize: false,
            format: true,
        });

        $(document).on("keydown", ".jqte_editor", function(e) {
            el = $(this);
            if ((el.text().length > totalChars - 1) && (e.keyCode != 8)) {
                e.preventDefault();
            }
        });
    });

    function retornaForm(index) {
        $("#formulario #COD_CATEGORTKT").val($("#ret_COD_CATEGORTKT_" + index).val());
        $("#formulario #DES_CATEGOR").val($("#ret_DES_CATEGOR_" + index).val());
        $("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_" + index).val());
        $("#formulario #DES_ICONES").val($("#ret_DES_ICONES_" + index).val());
        $('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONES_" + index).val());
        if ($("#ret_LOG_DESTAK_" + index).val() == 'S') {
            $('#formulario #LOG_DESTAK').prop('checked', true);
        } else {
            $('#formulario #LOG_LOG_DESTAK').prop('checked', false);
        }
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }
</script>