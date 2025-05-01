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
$cod_saldo = "";
$des_logo = "";
$des_alinham = "";
$des_imgback = "";
$cor_backbar = "";
$cor_backpag = "";
$cor_textos = "";
$log_totganho = "";
$cor_totganho = "";
$log_totresga = "";
$cor_totresga = "";
$log_liberar = "";
$cor_liberar = "";
$log_expirar = "";
$cor_expirar = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaSiteTotem = "";
$check_TOTGANHO = "";
$check_TOTRESGA = "";
$check_LIBERAR = "";
$check_EXPIRAR = "";
$sqlDom = "";
$arrayQueryDom = [];
$qrBuscaDom = "";
$des_dominio = "";
$qrBuscaUsuTeste = "";
$log_usuario = "";
$des_senhaus = "";
$idlojaKey = "";
$idmaquinaKey = "";
$codvendedorKey = "";
$nomevendedorKey = "";
$urlSaldo = "";
$formBack = "";
$abaEmpresa = "";
$abaSaldo = "";


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

        $cod_saldo = fnLimpaCampoZero(@$_REQUEST['COD_SALDO']);
        $cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
        $des_logo = fnLimpaCampo(@$_REQUEST['DES_LOGO']);
        $des_alinham = fnLimpaCampo(@$_REQUEST['DES_ALINHAM']);
        $des_imgback = fnLimpaCampo(@$_REQUEST['DES_IMGBACK']);
        $cor_backbar = fnLimpaCampo(@$_REQUEST['COR_BACKBAR']);
        $cor_backpag = fnLimpaCampo(@$_REQUEST['COR_BACKPAG']);
        $cor_textos = fnLimpaCampo(@$_REQUEST['COR_TEXTOS']);

        if (empty(@$_REQUEST['LOG_TOTGANHO'])) {
            $log_totganho = 'N';
        } else {
            $log_totganho = @$_REQUEST['LOG_TOTGANHO'];
        }
        $cor_totganho = fnLimpaCampo(@$_REQUEST['COR_TOTGANHO']);

        if (empty(@$_REQUEST['LOG_TOTRESGA'])) {
            $log_totresga = 'N';
        } else {
            $log_totresga = @$_REQUEST['LOG_TOTRESGA'];
        }
        $cor_totresga = fnLimpaCampo(@$_REQUEST['COR_TOTRESGA']);

        if (empty(@$_REQUEST['LOG_LIBERAR'])) {
            $log_liberar = 'N';
        } else {
            $log_liberar = @$_REQUEST['LOG_LIBERAR'];
        }
        $cor_liberar = fnLimpaCampo(@$_REQUEST['COR_LIBERAR']);

        if (empty(@$_REQUEST['LOG_EXPIRAR'])) {
            $log_expirar = 'N';
        } else {
            $log_expirar = @$_REQUEST['LOG_EXPIRAR'];
        }
        $cor_expirar = fnLimpaCampo(@$_REQUEST['COR_EXPIRAR']);

        $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = @$_GET['mod'];
        $COD_MODULO = fndecode(@$_GET['mod']);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        if ($opcao != '') {

            $sql = "CALL SP_ALTERA_SITE_SALDO (         
				 '" . $cod_saldo . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_logo . "', 
				 '" . $des_alinham . "', 
				 '" . $des_imgback . "', 
				 '" . $cor_backbar . "', 
				 '" . $cor_backpag . "', 
				 '" . $cor_textos . "', 
				 '" . $log_totganho . "', 
				 '" . $cor_totganho . "', 
				 '" . $log_totresga . "', 
				 '" . $cor_totresga . "', 
				 '" . $log_liberar . "', 
				 '" . $cor_liberar . "', 
				 '" . $log_expirar . "', 
                 '" . $cor_expirar . "' 
				) ";

            //fnEscreve($cod_empresa);			
            //echo $sql;
            $arrayProc = mysqli_query($conn, $sql);

            if (!$arrayProc) {

                $cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
            }

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                    }
                    break;
                case 'ALT':
                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível alterar o registro : $cod_erro";
                    }
                    break;
                case 'EXC':
                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível excluir o registro : $cod_erro";
                    }
                    break;
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
$sql = "SELECT * FROM SITE_SALDO WHERE COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($conn, $sql);
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
    //fnEscreve("entrou if");

    $cod_saldo = $qrBuscaSiteTotem['COD_SALDO'];
    $des_logo = $qrBuscaSiteTotem['DES_LOGO'];
    $des_alinham = $qrBuscaSiteTotem['DES_ALINHAM'];
    $des_imgback = $qrBuscaSiteTotem['DES_IMGBACK'];
    $cor_backbar = $qrBuscaSiteTotem['COR_BACKBAR'];
    $cor_backpag = $qrBuscaSiteTotem['COR_BACKPAG'];
    $cor_textos = $qrBuscaSiteTotem['COR_TEXTOS'];

    if ($qrBuscaSiteTotem['LOG_TOTGANHO'] == "N") {
        $check_TOTGANHO = '';
    } else {
        $check_TOTGANHO = "checked";
    }
    $cor_totganho = $qrBuscaSiteTotem['COR_TOTGANHO'];

    if ($qrBuscaSiteTotem['LOG_TOTRESGA'] == "N") {
        $check_TOTRESGA = '';
    } else {
        $check_TOTRESGA = "checked";
    }
    $cor_totresga = $qrBuscaSiteTotem['COR_TOTRESGA'];

    if ($qrBuscaSiteTotem['LOG_LIBERAR'] == "N") {
        $check_LIBERAR = '';
    } else {
        $check_LIBERAR = "checked";
    }
    $cor_liberar = $qrBuscaSiteTotem['COR_LIBERAR'];

    if ($qrBuscaSiteTotem['LOG_EXPIRAR'] == "N") {
        $check_EXPIRAR = '';
    } else {
        $check_EXPIRAR = "checked";
    }
    $cor_liberar = $qrBuscaSiteTotem['COR_LIBERAR'];
} else {
    //default se vazio
    //fnEscreve("entrou else");

    $cod_saldo = 0;
    $des_logo = "";
    $des_alinham = "left";
    $des_imgback = "";

    $cor_backbar = "";
    $cor_backpag = "#f2f3f4";
    $cor_textos = "#34495e";

    $check_TOTGANHO = "checked";
    $cor_totganho = "#1a4e95";

    $check_TOTRESGA = "checked";
    $cor_totresga = "#35aadc";

    $check_LIBERAR = "checked";
    $cor_liberar = "#cc324b";

    $check_EXPIRAR = "checked";
    $cor_expirar = "#193042";
}

// $sqlDom = "SELECT DES_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";

// $arrayQueryDom = mysqli_query(connTemp($cod_empresa,''), $sqlDom);
// $qrBuscaDom = mysqli_fetch_assoc($arrayQueryDom);

// $des_dominio = $qrBuscaDom['DES_DOMINIO'];

// if($des_dominio == ""){
//     $msgRetorno = "<strong>Domínio</strong> não configurado. <a href='action.do?mod=".fnEncode(1165)."&id=".fnEncode($cod_empresa)."' target='_blank'>Clique aqui</a> para configurar";
//     $msgTipo = 'alert-danger';
// }

$sql = "SELECT * FROM  USUARIOS
            WHERE LOG_ESTATUS='S' AND
                  COD_EMPRESA = $cod_empresa AND
                  COD_TPUSUARIO=10  limit 1  ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($adm, $sql);
$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
    $log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
    $des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
}

$idlojaKey = 0;
$idmaquinaKey = 0;
$codvendedorKey = 0;
$nomevendedorKey = 0;

$urlSaldo = "";

$urlSaldo = fnEncode(
    $log_usuario . ';'
        . $des_senhaus . ';'
        . $idlojaKey . ';'
        . $idmaquinaKey . ';'
        . $cod_empresa . ';'
        . $codvendedorKey . ';'
        . $nomevendedorKey . ';
            0'
);

//fnEscreve($log_usuario);
//fnEscreve($des_senhaus);
//fnMostraForm();

?>

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

                <?php
                $abaEmpresa = 1193;
                include "abasEmpresaConfig.php";
                ?>

                <div class="push30"></div>

                <?php
                $abaSaldo = 1193;
                include "abasSaldo.php";
                ?>

                <div class="push20"></div>

                <div class="login-form">

                    <div class="col-md-2 col-md-offset-10 text-right">
                        <div class="form-group">
                            <a href="http://extrato.bunker.mk/?key=<?= $urlSaldo ?>" class="btn btn-default btn-sm btn-block" target="_blank">
                                <span class="fal fa-eye"></span> Preview Saldo
                            </a>
                        </div>
                    </div>

                    <div class="push20"></div>

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <div class="row">

                                <div class="col-md-3">
                                    <label for="inputName" class="control-label required">Logotipo</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_LOGO" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="text" name="DES_LOGO" id="DES_LOGO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_logo; ?>">
                                    </div>
                                    <span class="help-block">(.png 300px X 80px)</span>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Alinhamento do Logo</label>
                                        <select data-placeholder="Selecione um alinhamento" name="DES_ALINHAM" id="DES_ALINHAM" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <option value="left">Esquerda</option>
                                            <option value="center">Centro</option>
                                            <option value="right">Direita</option>
                                        </select>
                                        <script>
                                            $("#formulario #DES_ALINHAM").val("<?php echo $des_alinham; ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="inputName" class="control-label">Imagem de Fundo</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMGBACK" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="text" name="DES_IMGBACK" id="DES_IMGBACK" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_imgback; ?>">
                                    </div>
                                    <span class="help-block">(.jpg 1400px X 600px)</span>
                                </div>

                                <!--  <div class="col-md-2">
                                    <div class="push15"></div>
                                    <a href="#" class="btn btn-info btn-sm btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1748) ?>&id=<?php echo fnEncode($cod_empresa); ?>&pop=true" data-title="Complementos do urlSaldo / <?php echo $nom_empresa; ?>"><i class="fa fa-play-circle" aria-hidden="true"></i>&nbsp; Complementos &nbsp; </a>
                                </div> -->

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Cores Personalizadas</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cor da Barra Superior</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_BACKBAR" id="COR_BACKBAR" maxlength="100" value="<?php echo $cor_backbar; ?>">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Cor do Fundo da Página</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_BACKPAG" id="COR_BACKPAG" maxlength="100" value="<?php echo $cor_backpag; ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Textos</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_TEXTOS" id="COR_TEXTOS" maxlength="100" value="<?php echo $cor_textos; ?>" required>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Blocos de Informações</legend>

                            <div class="row">

                                <div class="col-md-2">

                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Total ganho</label>
                                        <div class="push5"></div>
                                        <label class="switch">
                                            <input type="checkbox" name="LOG_TOTGANHO" id="LOG_TOTGANHO" class="switch" value="S" <?php echo $check_TOTGANHO; ?>>
                                            <span></span>
                                        </label>
                                    </div>

                                    <div class="push10"></div>

                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cor do Bloco</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_TOTGANHO" id="COR_TOTGANHO" maxlength="100" value="<?php echo $cor_totganho; ?>">
                                    </div>

                                </div>

                                <div class="col-md-1"></div>

                                <div class="col-md-2">

                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Total Resgatado</label>
                                        <div class="push5"></div>
                                        <label class="switch">
                                            <input type="checkbox" name="LOG_TOTRESGA" id="LOG_TOTRESGA" class="switch" value="S" <?php echo $check_TOTRESGA; ?>>
                                            <span></span>
                                        </label>
                                    </div>

                                    <div class="push10"></div>

                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cor do Bloco</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_TOTRESGA" id="COR_TOTRESGA" maxlength="100" value="<?php echo $cor_totresga; ?>">
                                    </div>

                                </div>

                                <div class="col-md-1"></div>

                                <div class="col-md-2">

                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Próxima Compra</label>
                                        <div class="push5"></div>
                                        <label class="switch">
                                            <input type="checkbox" name="LOG_LIBERAR" id="LOG_LIBERAR" class="switch" value="S" <?php echo $check_LIBERAR; ?>>
                                            <span></span>
                                        </label>
                                    </div>

                                    <div class="push10"></div>

                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cor do Bloco</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_LIBERAR" id="COR_LIBERAR" maxlength="100" value="<?php echo $cor_liberar; ?>">
                                    </div>

                                </div>

                                <div class="col-md-1"></div>

                                <div class="col-md-2">

                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Expirar 30 dias</label>
                                        <div class="push5"></div>
                                        <label class="switch">
                                            <input type="checkbox" name="LOG_EXPIRAR" id="LOG_EXPIRAR" class="switch" value="S" <?php echo $check_EXPIRAR; ?>>
                                            <span></span>
                                        </label>
                                    </div>

                                    <div class="push10"></div>

                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cor do Bloco</label>
                                        <input type="text" class="form-control input-sm pickColor" name="COR_EXPIRAR" id="COR_EXPIRAR" maxlength="100" value="<?php echo $cor_expirar; ?>">
                                    </div>

                                </div>


                            </div>

                        </fieldset>

                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <?php if ($cod_saldo == 0) { ?>
                                <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <?php } else { ?>
                                <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <?php } ?>

                        </div>

                        <input type="hidden" name="COD_SALDO" id="COD_SALDO" value="<?php echo $cod_saldo; ?>">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

                        <div class="push5"></div>

                    </form>

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

        //color picker
        $('.pickColor').minicolors({
            control: $(this).attr('data-control') || 'hue',
            theme: 'bootstrap'
        });

    });

    function retornaForm(index) {
        $("#formulario #COD_MAQUINA").val($("#ret_COD_MAQUINA_" + index).val());
        $("#formulario #DES_MAQUINA").val($("#ret_DES_MAQUINA_" + index).val());
        $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }

    $('.upload').on('click', function(e) {
        var idField = 'arqUpload_' + $(this).attr('idinput');
        var typeFile = $(this).attr('extensao');

        $.dialog({
            title: 'Arquivo',
            content: '' +
                '<form method = "POST" enctype = "multipart/form-data">' +
                '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
                '<div class="progress" style="display: none">' +
                '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
                '   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
                '</div>' +
                '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
                '</form>'
        });
    });

    function upload_check(size) {
        var max = 1048576 / 5;

        if (size > max) {
            return 0;
        } else {
            return 1;
        }
    }

    function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        if (!upload_check($('#' + idField)[0].files[0]['size'])) {

            $('#' + idField).val("");

            $.alert({
                title: "Mensagem",
                content: "O arquivo que você está tentando enviar é muito grande! Tente novamente com um arquivo de 200 KB ou menor.",
                type: 'yellow'
            });

            return false;
        }

        $('.progress').show();
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        if (percentComplete !== 100) {
                            $('.progress-bar').css('width', percentComplete + "%");
                            $('.progress-bar > span').html(percentComplete + "%");
                        }
                    }
                }, false);
                return xhr;
            },
            url: '../uploads/uploaddoc.php',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function(data) {
                $('.jconfirm-open').fadeOut(300, function() {
                    $(this).remove();
                });
                if (!data.trim()) {
                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                    $.alert({
                        title: "Mensagem",
                        content: "Upload feito com sucesso",
                        type: 'green'
                    });

                } else {
                    $.alert({
                        title: "Erro ao efetuar o upload",
                        content: data,
                        type: 'red'
                    });
                }
            }
        });
    }
</script>