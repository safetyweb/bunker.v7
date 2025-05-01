<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$opcao = '';
$cod_modulos = '';
$qrChmd = [];

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
        var_dump($msgTipo);
    } else {
        $_SESSION['last_request']  = $request;
        $cod_artigo = fnLimpaCampoZero($_REQUEST['COD_ARTIGO']);
        $cod_categor = fnLimpaCampoZero($_REQUEST['COD_CATEGOR']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_subcategor = fnLimpaCampoZero($_REQUEST['COD_SUBCATEGOR']);
        $cod_modulos = fnLimpaCampoZero($_REQUEST['COD_MODULOS']);
        $nom_artigo = fnLimpaCampo($_REQUEST['NOM_ARTIGO']);
        $des_artigo = addslashes(htmlentities($_REQUEST['DES_ARTIGO']));
        $des_chamada = fnLimpaCampo($_REQUEST['DES_CHAMADA']);
        $des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);
        $des_urlvideo = fnLimpaCampo($_REQUEST['DES_URLVIDEO']);
        $des_anexo1 = fnLimpaCampo($_REQUEST['DES_ANEXO1']);
        $des_anexo2 = fnLimpaCampo($_REQUEST['DES_ANEXO2']);
        $des_anexo3 = fnLimpaCampo($_REQUEST['DES_ANEXO3']);
        if (empty($_REQUEST['LOG_ATIVO'])) {
            $log_ativo = 'N';
        } else {
            $log_ativo = $_REQUEST['LOG_ATIVO'];
        }
        if (empty($_REQUEST['LOG_DESTAQUE'])) {
            $log_destaque = 'N';
        } else {
            $log_destaque = $_REQUEST['LOG_DESTAQUE'];
        }
        if (empty($_REQUEST['LOG_PUBLICO'])) {
            $log_publico = 'N';
        } else {
            $log_publico = $_REQUEST['LOG_PUBLICO'];
        }

        $cod_usucada = $_SESSION['SYS_COD_USUARIO'];

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {

            // $sql = "CALL SP_ALTERA_COMUNICACAO (
            //  '".$cod_canalcom."',
            //  '".$cod_tpcom."',
            //  '".$abv_canalcom."',                
            //  '".$des_canalcom."',                
            //  '".$des_icone."', 
            //  '".$des_cor."', 
            //  '".$log_personaliza."', 
            //  '".$log_preco."', 
            //  '".$opcao."'    
            // ) ";

            //echo $sql;
            //fnEscreve($cod_submenus);

            // mysqli_query($connAdm->connAdm(),trim($sql));                

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    $sql = "INSERT INTO ARTIGO_TUTORIAL(
                                            COD_CATEGOR,
                                            COD_EMPRESA,
                                            COD_SUBCATEGOR,
                                            COD_MODULOS,
                                            NOM_ARTIGO,
                                            DES_ARTIGO,
                                            DES_CHAMADA,
                                            DES_IMAGEM,
                                            DES_URLVIDEO,
                                            DES_ANEXO1,
                                            DES_ANEXO2,
                                            DES_ANEXO3,
                                            LOG_ATIVO,
                                            LOG_DESTAQUE,
                                            LOG_PUBLICO,
                                            COD_USUCADA
                                            )VALUES(
                                            $cod_categor,
                                            $cod_empresa,
                                            $cod_subcategor,
                                            $cod_modulos,
                                            '$nom_artigo',
                                            '$des_artigo',
                                            '$des_chamada',
                                            '$des_imagem',
                                            '$des_urlvideo',
                                            '$des_anexo1',
                                            '$des_anexo2',
                                            '$des_anexo3',
                                            '$log_ativo',
                                            '$log_destaque',
                                            '$log_publico',
                                            $cod_usucada
                                            )";

                    //echo $sql;
                    //fnEscreve($sql);                             

                    mysqli_query($connAdm->connAdm(), trim($sql));

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;

                case 'ALT':
                    $sql = "UPDATE ARTIGO_TUTORIAL SET 
                                        COD_CATEGOR=$cod_categor,
                                        COD_EMPRESA=$cod_empresa,
                                        COD_SUBCATEGOR=$cod_subcategor,
                                        COD_MODULOS=$cod_modulos,
                                        NOM_ARTIGO='$nom_artigo',
                                        DES_ARTIGO='$des_artigo',
                                        DES_CHAMADA='$des_chamada',
                                        DES_IMAGEM= '$des_imagem',
                                        DES_URLVIDEO='$des_urlvideo',
                                        DES_ANEXO1='$des_anexo1',
                                        DES_ANEXO2='$des_anexo2',
                                        DES_ANEXO3='$des_anexo3',
                                        LOG_ATIVO='$log_ativo',
                                        LOG_DESTAQUE='$log_destaque',                                        
                                        LOG_PUBLICO='$log_publico'                                        
                        WHERE COD_ARTIGO=$cod_artigo
                        ";
                    //fnEscreve($sql);

                    mysqli_query($connAdm->connAdm(), trim($sql));


                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;

                case 'EXC':
                    $sql = "DELETE FROM ARTIGO_TUTORIAL WHERE COD_ARTIGO = $cod_artigo;";
                    // fnEscreve($sql);
                    mysqli_query($connAdm->connAdm(), trim($sql));
                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                    break;
                    break;
            }
            $msgTipo = 'alert-success';
        }
    }
}


//busca dados da url    
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
    }
} else {
    $cod_empresa = 0;
    //fnEscreve('entrou else');
}


if ($popUp == 'true') {
    $cod_artigo = fnDecode($_GET['idA']);


    $sql = "SELECT * FROM ARTIGO_TUTORIAL WHERE COD_ARTIGO = $cod_artigo";
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrArtigo = mysqli_fetch_assoc($arrayQuery);


    $cod_categor = $qrArtigo['COD_CATEGOR'];
    $cod_empresa = $qrArtigo['COD_EMPRESA'];
    $cod_subcategor = $qrArtigo['COD_SUBCATEGOR'];
    $cod_modulos = $qrArtigo['COD_MODULOS'];
    $nom_artigo = $qrArtigo['NOM_ARTIGO'];
    $des_artigo = $qrArtigo['DES_ARTIGO'];
    $des_chamada = $qrArtigo['DES_CHAMADA'];
    $des_imagem = $qrArtigo['DES_IMAGEM'];
    $des_urlvideo = $qrArtigo['DES_URLVIDEO'];
    $des_anexo1 = $qrArtigo['DES_ANEXO1'];
    $des_anexo2 = $qrArtigo['DES_ANEXO2'];
    $des_anexo3 = $qrArtigo['DES_ANEXO3'];

    if ($qrArtigo['LOG_ATIVO'] == 'S') {
        $log_ativo = 'checked';
    } else {
        $log_ativo = "";
    }

    if ($qrArtigo['LOG_DESTAQUE'] == 'S') {
        $log_destaque = 'checked';
    } else {
        $log_destaque = "";
    }

    if ($qrArtigo['LOG_PUBLICO'] == 'S') {
        $log_publico = 'checked';
    } else {
        $log_publico = "";
    }
}



//fnMostraForm(); 

?>

<div class="push30"></div>

<div class="row">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <?php if ($popUp != "true") {  ?>
            <div class="portlet portlet-bordered">
            <?php } else { ?>
                <div class="portlet" style="padding: 0 20px 20px 20px;">
                <?php } ?>

                <?php if ($popUp != "true") {  ?>
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fal fa-terminal"></i>
                            <span class="text-primary"><?php echo $NomePg; ?></span>
                        </div>
                        <?php include "atalhosPortlet.php"; ?>
                    </div>
                <?php } ?>
                <div class="portlet-body">

                    <?php if ($msgRetorno <> '') { ?>
                        <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $msgRetorno; ?>
                        </div>
                    <?php } ?>

                    <div class="push30"></div>

                    <style>
                        li {
                            list-style: none;
                        }

                        .chec-radio .radio-inline .clab {
                            cursor: pointer;
                            background: #e7e7e7;
                            padding: 7px 20px;
                            text-align: center;
                            text-transform: uppercase;
                            color: #2c3e50;
                            position: relative;
                            height: 34px;
                            float: left;
                            margin: 0;
                            margin-bottom: 5px;
                        }

                        .chec-radio label.radio-inline input[type="radio"] {
                            display: none;
                        }

                        .chec-radio label.radio-inline input[type="radio"]:checked+div {
                            color: #fff;
                            background-color: #2c3e50;
                        }

                        .chec-radio label.radio-inline input[type="radio"]:checked+div:before {
                            content: "\e013";
                            margin-right: 5px;
                            font-family: 'Glyphicons Halflings';
                        }

                        .collapse-chevron .fa {
                            transition: .3s transform ease-in-out;
                        }

                        .collapse-chevron .collapsed .fa {
                            transform: rotate(-90deg);
                        }

                        .collapse-plus .fas {
                            transition: .2s transform ease-in-out;
                        }

                        .collapse-plus .collapsed .fas {
                            transform: rotate(45deg);
                        }

                        .area {
                            width: 100%;
                            padding: 7px;
                        }

                        #dropZone {
                            display: block;
                            border: 2px dashed #bbb;
                            -webkit-border-radius: 5px;
                            border-radius: 5px;
                            margin-left: -7px;
                        }

                        #dropZone p {
                            font-size: 10pt;
                            letter-spacing: -0.3pt;
                            margin-bottom: 0px;
                        }

                        #dropzone .fa {
                            font-size: 15pt;
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

                        .chosen-container {
                            width: 100% !important;
                        }

                        #APAGA_MODULO {
                            position: absolute;
                            top: -3px;
                            right: 3px;
                            z-index: 5;
                        }
                    </style>

                    <div class="login-form">

                        <?php
                        if ($popUp != "true") {
                            $abaTutorial = 1470;
                            include "abasTutorial.php";
                        }
                        ?>

                        <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
                            <div class="push30"></div>
                            <fieldset>

                                <legend>Dados do Artigo</legend>

                                <div class="row">

                                    <div class="col-md-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label required">Código</label>
                                                <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_ARTIGO" id="COD_ARTIGO" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Ativo</label>
                                            <div class="push5"></div>
                                            <label class="switch switch-small">
                                                <input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S">
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Destaque</label>
                                            <div class="push5"></div>
                                            <label class="switch switch-small">
                                                <input type="checkbox" name="LOG_DESTAQUE" id="LOG_DESTAQUE" class="switch" value="S">
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Título</label>
                                            <input type="text" class="form-control input-sm" name="NOM_ARTIGO" id="NOM_ARTIGO" value="">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Categoria</label>
                                            <select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a categoria" name="COD_CATEGOR" id="COD_CATEGOR" required>
                                                <option value=""></option>
                                                <?php

                                                $sql = "SELECT COD_CATEGOR, DES_CATEGOR FROM CATEGORIA_TUTORIAL order by DES_CATEGOR ";
                                                $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                                while ($qrListaComunicacao = mysqli_fetch_assoc($arrayQuery)) {
                                                ?>
                                                    <option value="<?php echo $qrListaComunicacao['COD_CATEGOR']; ?>"><?php echo $qrListaComunicacao['DES_CATEGOR']; ?></option>

                                                <?php } ?>
                                            </select>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div id="subcatConteudo">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label">Subcategoria</label>
                                                <select class="chosen-select-deselect" data-placeholder="Selecione a subcategoria" name="COD_SUBCATEGOR" id="COD_SUBCATEGOR">

                                                </select>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="push20"></div>

                                <div class="row">

                                    <div class="col-md-3">
                                        <label for="inputName" class="control-label">Módulo</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <a type="button" name="btnBusca" id="btnBuscamodulo" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1477) ?>&id=<?php echo fnEncode($cod_modulos) ?>&pop=true" data-title="Busca Categoria"><i class="fal fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
                                            </span>
                                            <input type="text" name="NOM_MODULOS" id="NOM_MODULOS" value="" maxlength="50" class="form-control input-sm" readonly style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
                                            <span class="input-group-btn">
                                                <a href="javascript:void(0)" name="APAGA_MODULO" id="APAGA_MODULO" style="height:35px; color:black;display:none" class="btn bg-transparent"><i class="fas fa-times" aria-hidden="true" style="padding-top: 3px;"></i></a>
                                            </span>
                                            <input type="hidden" name="COD_MODULOS" id="COD_MODULOS" value="">
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Chamada</label>
                                            <input type="text" class="form-control input-sm" name="DES_CHAMADA" id="DES_CHAMADA" maxlength="400" value="">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="inputName" class="control-label">Imagem</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                            </span>
                                            <input type="hidden" name="DES_IMAGEM" id="DES_IMAGEM" value="">
                                            <input type="text" name="IMAGEM" id="IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="250" value="">
                                        </div>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">URL Video</label>
                                            <input type="text" class="form-control input-sm" name="DES_URLVIDEO" id="DES_URLVIDEO" value="">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label">Público</label>
                                            <div class="push5"></div>
                                            <label class="switch switch-small">
                                                <input type="checkbox" name="LOG_PUBLICO" id="LOG_PUBLICO" class="switch" value="S" checked>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-2" id="empresas" style="display: none;">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Empresa</label>

                                            <select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect requiredChk">
                                                <option value=""></option>
                                                <?php

                                                $sql = "SELECT empresas.COD_EMPRESA, empresas.NOM_FANTASI
                                                                                FROM empresas  
                                                                                WHERE empresas.COD_EMPRESA <> 1 
                                                                                ORDER by NOM_FANTASI";

                                                $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                                while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
                                                    echo "
                                                        <option value='" . $qrLista['COD_EMPRESA'] . "'>" . $qrLista['NOM_FANTASI'] . "</option> 
                                                    ";
                                                }
                                                ?>
                                            </select>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="push20"></div>

                                <div class="row">

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="inputName" class="control-label required">Artigo: </label>
                                            <textarea class="editor form-control input-sm" rows="6" name="DES_ARTIGO" id="DES_ARTIGO"></textarea>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="push20"></div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <label for="inputName" class="control-label">Anexo 1</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_ANEXO1" extensao="all"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                            </span>
                                            <input type="hidden" name="DES_ANEXO1" id="DES_ANEXO1" value="">
                                            <input type="text" name="ANEXO1" id="ANEXO1" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="250" value="">
                                        </div>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="inputName" class="control-label">Anexo 2</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_ANEXO2" extensao="all"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                            </span>
                                            <input type="hidden" name="DES_ANEXO2" id="DES_ANEXO2" value="">
                                            <input type="text" name="ANEXO2" id="ANEXO2" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="250" value="">
                                        </div>
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="inputName" class="control-label">Anexo 3</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_ANEXO3" extensao="all"><i class="fal fa-cloud-upload" aria-hidden="true"></i></a>
                                            </span>
                                            <input type="hidden" name="DES_ANEXO3" id="DES_ANEXO3" value="">
                                            <input type="text" name="ANEXO3" id="ANEXO3" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="250" value="">
                                        </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="push20"></div>

                            </fieldset>

                            <div class="push10"></div>
                            <hr>
                            <div class="form-group text-right col-lg-12">
                                <?php if ($popUp != 'true') { ?>
                                    <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                                    <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                                    <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                                    <button type="cl" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
                                <?php } else { ?>

                                    <!-- <button type="button" id="mymodal" class="btn btn-default" data-dismiss="modal">Close</button> -->

                                <?php } ?>
                            </div>

                            <div class="push5"></div>
                            <input type="hidden" name="opcao" id="opcao" value="">
                            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                            <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                        </form>

                        <div class="push20"></div>

                    </div>
                </div>
                </div>

                <div class="push20"></div>

                <div class="portlet portlet-bordered">

                    <div class="portlet-body">

                        <div class="login-form">

                            <?php
                            if ($popUp != 'true') {
                            ?>

                                <div class="no-more-tables">

                                    <form name="formLista">


                                        <table class="table table-bordered table-striped table-hover table-sortable">
                                            <thead>
                                                <tr>
                                                    <th width="40"></th>
                                                    <th>Código</th>
                                                    <th>Título</th>
                                                    <th>Categoria</th>
                                                    <th>Subcategoria</th>
                                                    <th>Módulo</th>
                                                    <th>Ativo</th>
                                                    <th>Destaque</th>
                                                    <th>Publico</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php

                                                // $sql = "SELECT AT.*, CT.DES_CATEGOR FROM ARTIGO_TUTORIAL AT
                                                //         LEFT JOIN CATEGORIA_TUTORIAL CT ON CT.COD_CATEGOR = AT.COD_CATEGOR";
                                                $sql = "SELECT AT.*, CT.DES_CATEGOR, ST.DES_SUBCATEGOR, M.NOM_MODULOS FROM ARTIGO_TUTORIAL AT
                                                                LEFT JOIN CATEGORIA_TUTORIAL CT ON CT.COD_CATEGOR = AT.COD_CATEGOR
                                                                LEFT JOIN SUBCATEGORIA_TUTORIAL ST ON ST.COD_SUBCATEGOR = AT.COD_SUBCATEGOR
                                                                LEFT JOIN MODULOS M ON M.COD_MODULOS = AT.COD_MODULOS
                                                                ";



                                                $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                                $count = 0;
                                                while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

                                                    $ativo = "";
                                                    $destaque = "";
                                                    $categoria = "";
                                                    $publico = "";

                                                    if ($qrBuscaModulos['LOG_ATIVO'] == 'S') {
                                                        $ativo = "<span class='fal fa-check text-success'></span>";
                                                    } else {
                                                        $ativo = "<span class='fal fa-times text-danger'></span>";
                                                    }

                                                    if ($qrBuscaModulos['LOG_DESTAQUE'] == 'S') {
                                                        $destaque = "<span class='fal fa-check text-success'></span>";
                                                    } else {
                                                        $destaque = "<span class='fal fa-times text-danger'></span>";
                                                    }

                                                    if ($qrBuscaModulos['LOG_PUBLICO'] == 'S') {
                                                        $publico = "<span class='fal fa-check text-success'></span>";
                                                    } else {
                                                        $publico = "<span class='fal fa-times text-danger'></span>";
                                                    }

                                                    $count++;
                                                    echo "
                                                            <tr>
                                                              <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                                              <td>" . $qrBuscaModulos['COD_ARTIGO'] . "</td>
                                                              <td><a href='action.do?mod=" . fnEncode(1481) . "&id=" . fnEncode($cod_empresa) . "&idA=" . fnEncode($qrBuscaModulos['COD_ARTIGO']) . "' target='_blank'>" . $qrBuscaModulos['NOM_ARTIGO'] . "&nbsp;</a></td>
                                                              <td>" . $qrBuscaModulos['DES_CATEGOR'] . "</td>
                                                              <td>" . $qrBuscaModulos['DES_SUBCATEGOR'] . "</td>
                                                              <td>" . $qrBuscaModulos['NOM_MODULOS'] . "</td>                                                              
                                                              <td class='text-center'>" . $ativo . "</td>
                                                              <td class='text-center'>" . $destaque . "</td>                                                                              
                                                              <td class='text-center'>" . $publico . "</td>                                                                              
                                                            </tr>
                                                            <input type='hidden' id='ret_COD_ARTIGO_" . $count . "' value='" . $qrBuscaModulos['COD_ARTIGO'] . "'>
                                                            <input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrBuscaModulos['COD_CATEGOR'] . "'>
                                                            <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBuscaModulos['COD_EMPRESA'] . "'>
                                                            <input type='hidden' id='ret_COD_SUBCATEGOR_" . $count . "' value='" . $qrBuscaModulos['COD_SUBCATEGOR'] . "'>
                                                            <input type='hidden' id='ret_COD_MODULOS_" . $count . "' value='" . $qrBuscaModulos['COD_MODULOS'] . "'>
                                                            <input type='hidden' id='ret_NOM_MODULOS_" . $count . "' value='" . $qrBuscaModulos['NOM_MODULOS'] . "'>                                                            
                                                            <input type='hidden' id='ret_NOM_ARTIGO_" . $count . "' value='" . $qrBuscaModulos['NOM_ARTIGO'] . "'>
                                                            <input type='hidden' id='ret_DES_ARTIGO_" . $count . "' value='" . $qrBuscaModulos['DES_ARTIGO'] . "'>
                                                            <input type='hidden' id='ret_DES_CHAMADA_" . $count . "' value='" . $qrBuscaModulos['DES_CHAMADA'] . "'>
                                                            <input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . $qrBuscaModulos['DES_IMAGEM'] . "'>
                                                            <input type='hidden' id='ret_IMAGEM_" . $count . "' value='" . fnBase64DecodeImg($qrBuscaModulos['DES_IMAGEM']) . "'>
                                                            <input type='hidden' id='ret_DES_URLVIDEO_" . $count . "' value='" . $qrBuscaModulos['DES_URLVIDEO'] . "'>
                                                            <input type='hidden' id='ret_DES_ANEXO1_" . $count . "' value='" . $qrBuscaModulos['DES_ANEXO1'] . "'>
                                                            <input type='hidden' id='ret_ANEXO1_" . $count . "' value='" . fnBase64DecodeImg($qrBuscaModulos['DES_ANEXO1']) . "'>
                                                            <input type='hidden' id='ret_DES_ANEXO2_" . $count . "' value='" . $qrBuscaModulos['DES_ANEXO2'] . "'>
                                                            <input type='hidden' id='ret_ANEXO2_" . $count . "' value='" . fnBase64DecodeImg($qrBuscaModulos['DES_ANEXO2']) . "'>
                                                            <input type='hidden' id='ret_DES_ANEXO3_" . $count . "' value='" . $qrBuscaModulos['DES_ANEXO3'] . "'>
                                                            <input type='hidden' id='ret_ANEXO3_" . $count . "' value='" . fnBase64DecodeImg($qrBuscaModulos['DES_ANEXO3']) . "'>
                                                            <input type='hidden' id='ret_LOG_ATIVO_" . $count . "' value='" . $qrBuscaModulos['LOG_ATIVO'] . "'>
                                                            <input type='hidden' id='ret_LOG_DESTAQUE_" . $count . "' value='" . $qrBuscaModulos['LOG_DESTAQUE'] . "'>
                                                            <input type='hidden' id='ret_LOG_PUBLICO_" . $count . "' value='" . $qrBuscaModulos['LOG_PUBLICO'] . "'>
                                                            ";
                                                }

                                                ?>

                                            </tbody>
                                        </table>

                                    </form>

                                </div>

                            <?php } ?>

                        </div>

                        <div class="push"></div>

                    </div>

                </div><!-- fim Portlet -->
            </div>

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
            <div class="modal-footer">
                <button type="button" id="mymodal" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>


<script type="text/javascript">
    $(function() {

        $('#LOG_PUBLICO').change(function() {
            if ($('#LOG_PUBLICO').prop("checked")) {
                $('#empresas').fadeOut('fast');
                $('#COD_EMPRESA').val('').trigger('chosen:updated').prop('required', false);
            } else {
                $('#empresas').fadeIn('fast');
                $('#COD_EMPRESA').prop('required', true);
            }
            $('#formulario').validator('validate');
        });

        var idEmp = "<?= fnLimpaCampoZero($cod_empresa) ?>";

        $("#COD_EMPRESA_COMBO").change(function() {
            idEmp = $('#COD_EMPRESA_COMBO').val();
            $("#COD_EMPRESA").val(idEmp);
            buscaCombo(idEmp);
        });

        $('#COD_CATEGOR').change(function() {
            var cod_categor = $(this).val();

            $.ajax({
                method: "POST",
                url: "ajxSubcatTutorial.php",
                data: {
                    COD_CATEGOR: cod_categor
                },
                beforeSend: function() {
                    $('#subcatConteudo').html("<div class='loading' style='width:100%'></div>");
                },
                success: function(data) {
                    $('#subcatConteudo').html(data);
                    // console.log(data);
                }
            });

        });

        // TextArea
        $(".editor").jqte({
            sup: false,
            sub: false,
            outdent: false,
            indent: false,
            left: false,
            center: false,
            color: false,
            right: false,
            strike: false,
            source: false,
            link: false,
            unlink: false,
            remove: false,
            rule: false,
            fsize: false,
            format: false,
        });

        $('#COD_USURES').val("<?= @$qrChmd['COD_USURES'] ?>").trigger("chosen:updated");
        $('#COD_STATUS').val("<?= @$qrChmd['COD_STATUS'] ?>").trigger("chosen:updated");

        $('.datePicker').datetimepicker({
            format: 'DD/MM/YYYY'
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
        });

        $('#btnBuscamodulo').click(function() {
            $('#APAGA_MODULO').fadeIn(3000);

        })

        $('#APAGA_MODULO').click(function() {
            $('#NOM_MODULOS').val("");
            $('#COD_MODULOS').val(0);
            $('#APAGA_MODULO').fadeOut(0);
        })


    });

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

    function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('diretorioAdicional', 'artigo');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

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

                var data = JSON.parse(data);


                if (data.success) {
                    // $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                    $('#' + idField.replace("arqUpload_", "")).val(data.nome_arquivo);
                    $('#' + idField.replace("arqUpload_DES_", "")).val(nomeArquivo);
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

    function retornaForm(index) {
        $("#formulario #COD_ARTIGO").val($("#ret_COD_ARTIGO_" + index).val());
        $("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger('chosen:updated');
        $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val()).trigger('chosen:updated');

        var cod_categor = $("#ret_COD_CATEGOR_" + index).val();
        $.ajax({
            method: "POST",
            url: "ajxSubcatTutorial.php",
            data: {
                COD_CATEGOR: cod_categor
            },
            beforeSend: function() {
                $('#subcatConteudo').html("<div class='loading' style='width:100%'></div>");
            },
            success: function(data) {
                $('#subcatConteudo').html(data);
                $("#formulario #COD_SUBCATEGOR").val($("#ret_COD_SUBCATEGOR_" + index).val()).trigger('chosen:updated');
                // console.log(data);
            }
        });
        $("#formulario #COD_MODULOS").val($("#ret_COD_MODULOS_" + index).val());
        $("#formulario #NOM_MODULOS").val($("#ret_NOM_MODULOS_" + index).val());
        $("#formulario #NOM_ARTIGO").val($("#ret_NOM_ARTIGO_" + index).val());
        $("#formulario .editor").jqteVal($("#ret_DES_ARTIGO_" + index).val());
        $("#formulario #DES_CHAMADA").val($("#ret_DES_CHAMADA_" + index).val());
        $("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
        $("#formulario #IMAGEM").val($("#ret_IMAGEM_" + index).val());
        $("#formulario #DES_URLVIDEO").val($("#ret_DES_URLVIDEO_" + index).val());
        $("#formulario #DES_ANEXO1").val($("#ret_DES_ANEXO1_" + index).val());
        $("#formulario #ANEXO1").val($("#ret_ANEXO1_" + index).val());
        $("#formulario #DES_ANEXO2").val($("#ret_DES_ANEXO2_" + index).val());
        $("#formulario #ANEXO2").val($("#ret_ANEXO2_" + index).val());
        $("#formulario #DES_ANEXO3").val($("#ret_DES_ANEXO3_" + index).val());
        $("#formulario #ANEXO3").val($("#ret_ANEXO3_" + index).val());
        if ($("#ret_LOG_ATIVO_" + index).val() == 'S') {
            $('#formulario #LOG_ATIVO').prop('checked', true);
        } else {
            $('#formulario #LOG_ATIVO').prop('checked', false);
        }
        if ($("#ret_LOG_DESTAQUE_" + index).val() == 'S') {
            $('#formulario #LOG_DESTAQUE').prop('checked', true);
        } else {
            $('#formulario #LOG_DESTAQUE').prop('checked', false);
        }

        if ($("#ret_LOG_PUBLICO_" + index).val() == 'S') {

            $('#formulario #LOG_PUBLICO').prop('checked', true);
            $('#empresas').fadeOut('fast');
            $('#COD_EMPRESA').val('').trigger('chosen:updated').prop('required', false);

        } else {

            $('#formulario #LOG_PUBLICO').prop('checked', false);
            $('#empresas').fadeIn('fast');
            $('#COD_EMPRESA').prop('required', true);

        }

        if ($("#ret_NOM_MODULOS_" + index).val() != "" || ($("#ret_COD_MODULOS_" + index).val()) != 0) {
            $('#APAGA_MODULO').fadeIn('fast');
        } else {
            $('#APAGA_MODULO').fadeOut(0);
        }

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }
</script>