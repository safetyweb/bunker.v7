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
$cod_classifica = "";
$qtd_diashist = 0;
$qtd_comprashist = 0;
$qtd_mesclass = 0;
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$sqlInsert = "";
$arrayInsert = [];
$cod_erro = "";
$sqlUpdate = "";
$arrayUpdate = [];
$retorno = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaClassifica = "";
$cod_categoria = "";
$log_usuario = "";
$des_senhaus = "";
$formBack = "";
$abaEmpresa = "";


//echo fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_classifica = fnLimpaCampoZero(@$_REQUEST['COD_CLASSIFICA']);
        $cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
        $qtd_diashist = fnLimpaCampoZero(@$_REQUEST['QTD_DIASHIST']);
        $qtd_comprashist = fnLimpaCampoZero(@$_REQUEST['QTD_COMPRASHIST']);
        $qtd_mesclass = fnLimpaCampoZero(@$_REQUEST['QTD_MESCLASS']);

        $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = @$_GET['mod'];
        $COD_MODULO = fndecode(@$_GET['mod']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':

                    $sqlInsert = "INSERT INTO EMPRESA_CLASSIFICA(
                                COD_EMPRESA, 
                                QTD_DIASHIST, 
                                QTD_COMPRASHIST, 
                                QTD_MESCLASS
                                )VALUES (
                                '$cod_empresa', 
                                '$qtd_diashist', 
                                '$qtd_comprashist', 
                                '$qtd_mesclass'
                                )";

                    $arrayInsert = mysqli_query($conn, $sqlInsert);

                    if (!$arrayInsert) {

                        $cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                    }

                    break;

                case 'ALT':

                    $sqlUpdate = "UPDATE EMPRESA_CLASSIFICA SET 
                                QTD_DIASHIST = '$qtd_diashist', 
                                QTD_COMPRASHIST = '$qtd_comprashist', 
                                QTD_MESCLASS = '$qtd_mesclass'
                                WHERE COD_CLASSIFICA = $cod_classifica and COD_EMPRESA = $cod_empresa ";

                    $arrayUpdate = mysqli_query($conn, $sqlUpdate);

                    if (!$arrayUpdate) {

                        $cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível alterar o registro : $cod_erro";
                    }

                    break;

                case 'BUS':

                    $sql = "CALL SP_DEFINE_UNIVEND_PREF($cod_empresa)";
                    mysqli_query($conn, $sql);

                    $sql = "SELECT CL.COD_CLIENTE FROM CLIENTES CL
                              WHERE CL.COD_EMPRESA = $cod_empresa
                              AND CL.COD_UNIVEND != CL.COD_UNIVEND_PREF";

                    $retorno = mysqli_query($conn, $sql);
                    $total_itens_por_pagina = mysqli_num_rows($retorno);

                    sleep(15);

                    if (!$retorno) {

                        $cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro == "") {
                        $msgRetorno = "Lojas reprocessadas com <strong>sucesso!</strong><br>Clientes atingidos: $total_itens_por_pagina";
                    } else {
                        $msgRetorno = "Falha no processo, nenhum cliente atingido:$cod_erro";
                    }


                    break;

                    // case 'EXC':

                    //     $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

                    // break;

            }
            if ($cod_erro == 0 || $cod_erro == "") {
                $msgTipo = 'alert-success';
            } else {
                $msgTipo = 'alert-danger';
            }
        }
    }
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
    //fnEscreve('entrou else');
}

//busca dados da tabela
$sql = "SELECT * FROM EMPRESA_CLASSIFICA WHERE COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($conn, $sql);
$qrBuscaClassifica = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaClassifica)) {
    //fnEscreve("entrou if");

    $cod_classifica = $qrBuscaClassifica['COD_CLASSIFICA'];
    $qtd_diashist = $qrBuscaClassifica['QTD_DIASHIST'];
    $qtd_comprashist = $qrBuscaClassifica['QTD_COMPRASHIST'];
    $qtd_mesclass = $qrBuscaClassifica['QTD_MESCLASS'];
} else {
    //default se vazio
    //fnEscreve("entrou else");

    $cod_categoria = 0;
    $qtd_diashist = "";
    $qtd_mesclass = "";
}

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
                    <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
                $abaEmpresa = 1530;
                include "abasEmpresaConfig.php";
                ?>

                <div class="push30"></div>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Parâmetros de Classificação Automática</legend>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Histórico para Classificação <small>(em dias)</small></label>
                                        <input type="text" class="form-control text-center input-sm int" name="QTD_DIASHIST" id="QTD_DIASHIST" maxlength="3" value="<?php echo $qtd_diashist; ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Compras Fora da Loja de Origem <small>(no período)</small></label>
                                        <input type="text" class="form-control text-center input-sm int" name="QTD_COMPRASHIST" id="QTD_COMPRASHIST" maxlength="3" value="<?php echo $qtd_comprashist; ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Reclassificação Automática <small>(em meses)</small></label>
                                        <select data-placeholder="Selecione a periodicidade de reclassificação" name="QTD_MESCLASS" id="QTD_MESCLASS" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <option value="12">Anual</option>
                                            <!--<option value="6">Semestral</option>-->
                                            <!--<option value="4">Quadrimestral</option>-->
                                            <!--<option value="3">Trimestral</option>-->
                                            <!--<option value="2">Bimestral</option>-->
                                            <option value="1">Mensal</option>
                                            <option value="0">Diário</option>
                                        </select>
                                        <script>
                                            $("#formulario #QTD_MESCLASS").val("<?php echo $qtd_mesclass; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors">início em 02/jan</div>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <?php if ($_SESSION["SYS_COD_EMPRESA"] == 2) { ?>
                                <button type="submit" name="BUS" id="BUS" class="btn btn-danger pull-left getBtn"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Processar Manualmente</button>
                                <!-- `SP_DEFINE_UNIVEND_PREF`( IN `p_COD_EMPRESA` INT )-->
                            <?php } ?>

                            <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <?php if ($cod_classifica == 0) { ?>
                                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <?php } else { ?>
                                <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <?php } ?>

                        </div>

                        <input type="hidden" name="COD_CLASSIFICA" id="COD_CLASSIFICA" value="<?php echo $cod_classifica; ?>">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

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
            $("#blocker").show();
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