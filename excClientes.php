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
$cod_cliente = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaCliente = "";
$nom_cliente = "";
$num_cartao = "";
$num_cgcecpf = "";
$log_usuario = "";
$des_senhaus = "";
$formBack = "";
$abaCli = "";


//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
        $cod_cliente = fnLimpaCampoZero(@$_REQUEST['COD_CLIENTE']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '') {

            $sql = "CALL `SP_EXCLUI_CLIENTES`($cod_cliente, $cod_empresa, $_SESSION[SYS_COD_USUARIO], 'exc', 1)";
            // fnEscreve($sql);
            mysqli_query(connTemp($cod_empresa, ''), $sql);

            //mensagem de retorno
            switch ($opcao) {

                case 'BUS':

                    $msgRetorno = "Cliente excluído com <strong>sucesso!</strong>";

                    break;
            }
            $msgTipo = 'alert-success';
        }
    }
}



//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode(@$_GET['id']);

    if (isset($_GET['tp'])) {
        $cod_cliente = fnLimpaCampoZero(@$_GET['idC']);
    } else {
        $cod_cliente = fnLimpaCampoZero(fnDecode(@$_GET['idC']));
    }

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


//busca dados do cliente
$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE FROM CLIENTES where COD_CLIENTE = '" . $cod_cliente . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {

    $nom_cliente = @$qrBuscaCliente['NOM_CLIENTE'];
    $cod_cliente = @$qrBuscaCliente['COD_CLIENTE'];
    $num_cartao = @$qrBuscaCliente['NUM_CARTAO'];
    $num_cgcecpf = @$qrBuscaCliente['NUM_CGCECPF'];
} else {

    $nom_cliente = "";
    $cod_cliente = "";
    $num_cartao = "";
    $num_cgcecpf = "";
}

//busca dados da tabela

//fnEscreve($log_usuario);
//fnEscreve($des_senhaus);
//fnMostraForm();

?>

<style type="text/css">
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
</style>

<div id="blocker">
    <div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)<br /><small>(este processo pode demorar vários minutos)</small></div>
</div>

<div class="push30"></div>

<div class="row">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"><?php echo $NomePg; ?></span>
                </div>

                <?php
                $formBack = "1019";
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

                <div class="push20"></div>

                <?php
                $abaCli = 1665;
                switch ($_SESSION["SYS_COD_SISTEMA"]) {
                    case 14: //rede duque
                        include "abasClienteDuque.php";
                        break;
                    case 13: //sh manager
                        include "abasIntegradoraCli.php";
                        break;
                    case 18: //mais cash
                        include "abasMaisCashCli.php";
                        break;
                    default;
                        include "abasClienteConfig.php";
                        break;
                }
                ?>

                <div class="push30"></div>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">


                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código do Cliente</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Empresa</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
                                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="inputName" class="control-label required">Nome do Usuário</label>
                                    <div class="input-group">
                                        <div class="push5"></div>
                                        <span class="f18"><?php echo $nom_cliente; ?></span>
                                        <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Número do Cartão</label>
                                        <input type="text" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" data-error="Campo obrigatório" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Número do CPF</label>
                                        <input type="text" class="form-control input-sm leitura doc" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo fnCompletaDoc($num_cgcecpf, 'F'); ?>" maxlength="50" data-error="Campo obrigatório" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push30"></div>

                        <div class="col-md-2">

                            <a href="javascript:void(0)" name="BUS" id="BUS" class="btn btn-danger pull-left getBtn"><i class="fal fa-trash" aria-hidden="true"></i>&nbsp; Excluir cliente <b>permanentemente</b>?</a>

                        </div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                        <div class="push5"></div>

                    </form>

                    <div class="push50"></div>



                    <div class="push50"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
    <div class="modal-dialog" style="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="push20"></div>

<link rel="stylesheet" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css" />

<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script type="text/javascript">
    $(document).ready(function() {

        //chosen
        $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        $('#formulario').validator();

        $("#BUS").click(function() {

            $.alert({
                title: "Confirmação",
                content: "Deseja excluir este cliente de forma <b>definitiva</b>?",
                type: 'red',
                buttons: {
                    "EXCLUIR": {
                        btnClass: 'btn-danger',
                        action: function() {

                            $.alert({
                                title: "Aviso!",
                                content: "<b>Todos</b> os dados do cliente serão excluídos <b>permanentemente</b>. Deseja <b>realmente</b> continuar?",
                                type: 'red',
                                buttons: {
                                    "EXCLUIR PERMANENTEMENTE": {
                                        btnClass: 'btn-danger',
                                        action: function() {
                                            $("#blocker").show();
                                            $("#formulario").submit();
                                        }
                                    },
                                    "CANCELAR": {
                                        btnClass: 'btn-default',
                                        action: function() {

                                        }
                                    }
                                },
                                backgroundDismiss: function() {
                                    return 'CANCELAR';
                                }
                            });

                        }
                    },
                    "CANCELAR": {
                        btnClass: 'btn-default',
                        action: function() {

                        }
                    }
                },
                backgroundDismiss: function() {
                    return 'CANCELAR';
                }
            });

        });
    });

    function retornaForm(index) {
        $("#formulario #COD_MAQUINA").val($("#ret_COD_MAQUINA_" + index).val());
        $("#formulario #DES_MAQUINA").val($("#ret_DES_MAQUINA_" + index).val());
        $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }
</script>