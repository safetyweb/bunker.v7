<?php

//echo "<h5>_".$opcao."</h5>";
echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$hashLocal = mt_rand();

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$des_destinatario = "";
$numPaginas = 0;
// Página default
$pagina = "1";


//FUNÇÃO PARA GERAR SENHA RANDON
function gerarSenha($comprimento = 12)
{
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $senha = '';

    for ($i = 0; $i < $comprimento; $i++) {
        $senha .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }

    return $senha;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
        $nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

        $log_emailenv = "N";
        if (isset($_REQUEST['LOG_EMAILENV'])) {
            $log_emailenv = 'S';
        }

        $des_corpoemail = "";
        if (isset($_REQUEST['DES_CORPOEMAIL'])) {
            $des_corpoemail = fnLimpaCampo($_REQUEST['DES_CORPOEMAIL']);
        }

        if (isset($_REQUEST['DES_DESTINATARIO'])) {
            $des_destinatario = fnLimpaCampo($_REQUEST['DES_DESTINATARIO']);
        }

        $des_assuntoemail = "";
        if (isset($_REQUEST['DES_ASSUNTOEMAIL'])) {
            $des_assuntoemail = fnLimpaCampo($_REQUEST['DES_ASSUNTOEMAIL']);
        }

        if (isset($_REQUEST['DES_CONTRATO'])) {
            $des_contrato = fnLimpaCampo($_REQUEST['DES_CONTRATO']);
        }

        if (isset($_REQUEST['CONTRATO'])) {
            $nom_contrato = fnLimpaCampo($_REQUEST['CONTRATO']);
        }

        $cod_consultor = fnLimpaCampoZero($_REQUEST['COD_CONSULTOR']);
        // fnEscreve($cod_consultor);
        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':

                    //BUSCA USUARIO
                    $sql = "SELECT COD_USUARIO FROM USUARIOS WHERE COD_EMPRESA = $cod_empresa AND COD_TPUSUARIO = 10";
                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
                    if (mysqli_num_rows($arrayQuery) == 0) {

                        //BUSCA SUFIXO
                        $sqlEmp = "SELECT DES_SUFIXO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
                        $arrayQueryEmp = mysqli_query($connAdm->connAdm(), $sqlEmp);
                        $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQueryEmp);
                        $sufixo = $qrBuscaEmpresa['DES_SUFIXO'];
                        $usuarioWs = 'ws.' . $sufixo;
                        //FIM SUFIXO

                        //BUSCA COD PERFIL
                        $sqlPerfil = "SELECT COD_PERFILS FROM PERFIL WHERE COD_EMPRESA = $cod_empresa AND DES_PERFILS = 'Marka'";
                        $arrayQueryPerfil = mysqli_query($connAdm->connAdm(), $sqlPerfil);
                        $qrBuscaPerfil = mysqli_fetch_assoc($arrayQueryPerfil);
                        $codPerfil = $qrBuscaPerfil['COD_PERFILS'];
                        //FIM COD PERFIL

                        //BUSCA COD UNIVEND
                        $sqlUnivend = "SELECT COD_UNIVEND FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa";
                        $arrayQueryUnivend = mysqli_query($connAdm->connAdm(), $sqlUnivend);
                        $codUnivend = "";
                        while ($qrBuscaUnivend = mysqli_fetch_assoc($arrayQueryUnivend)) {
                            $codUnivend .= $qrBuscaUnivend['COD_UNIVEND'] . ',';
                        }
                        $codUnivend = substr($codUnivend, 0, -1);
                        //FIM COD UNIVEND   

                        $sql = "CALL SP_ALTERA_USUARIOS ( 
                                '0',
                                '$cod_empresa',
                                'ws',
                                '$usuarioWs',
                                '',
                                'N',
                                '0',
                                '0',
                                '0',
                                '',
                                '$cod_usucada',
                                '',
                                'S',
                                '',
                                '',
                                '0',
                                '0',
                                '',
                                '',
                                '',
                                '10',
                                '$codPerfil',
                                '0',
                                '4',
                                '$cod_empresa',
                                '$codUnivend',
                                '0',
                                '',
                                'CAD');";

                        // fnEscreve($sql)

                        $arrayProc = mysqli_query($connAdm->connAdm(), $sql);


                        if (!$arrayProc) {
                            $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                        } else {

                            $sql = "SELECT COD_USUARIO FROM USUARIOS WHERE COD_EMPRESA = $cod_empresa AND COD_TPUSUARIO = 10";
                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
                            $qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
                            $cod_usuario = $qrBuscaUsuario['COD_USUARIO'];
                            $senha = gerarSenha(15);
                            $des_senhaus = fnEncode($senha);

                            $sql = "UPDATE USUARIOS SET 
							   DES_SENHAUS = '$des_senhaus',
							   NUM_TENTATI = 0 
                            WHERE COD_USUARIO = $cod_usuario 
                            AND COD_EMPRESA = $cod_empresa";
                            mysqli_query($connAdm->connAdm(), trim($sql));

                            $sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
                            COD_USUWS = '$cod_usuario',
                            FASE5 = 'S'
                            WHERE COD_EMPRESA = $cod_empresa";

                            $arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));


                            if ($log_emailenv == "S") {

                                if ($des_destinatario != "") {

                                    $sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
                                    DES_CONTRATO = '$des_contrato',
                                    DES_DESTINATARIO = '$des_destinatario',
                                    DES_CORPOEMAIL = '$des_corpoemail',
                                    DES_ASSUNTOEMAIL = '$des_assuntoemail',
                                    LOG_EMAILENV = 'S'
                                    WHERE COD_EMPRESA = $cod_empresa";

                                    $arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));

                                    // require_once 'envioEmailAutomacao.php';

                                    // $destinatario = explode(',', $des_destinatario);

                                    // $emailDestino = array(
                                    //     'email1' => $destinatario[0],
                                    //     'email2' => isset($destinatario[1]) ? $destinatario[1] : '',
                                    //     'email3' => isset($destinatario[2]) ? $destinatario[2] : '',
                                    //     'email4' => isset($destinatario[3]) ? $destinatario[3] : '',
                                    //     'email5' => isset($destinatario[4]) ? $destinatario[4] : ''
                                    // );

                                    // fnAutommail(
                                    //     $emailDestino,
                                    //     'Marka Fidelição',
                                    //     'Dados de Login',
                                    //     'Marka Fidelição',
                                    //     $connAdm->connAdm(),
                                    //     connTemp(3, ""),
                                    //     $cod_empresa,
                                    //     './media/clientes/3/',
                                    //     $nom_contrato
                                    // );
                                }
                            }
                        }
                    }

                    require_once 'envioEmailAutomacao.php';

                    $destinatario = explode(',', $des_destinatario);

                    $emailDestino = array(
                        'email1' => $destinatario[0],
                        'email2' => isset($destinatario[1]) ? $destinatario[1] : '',
                        'email3' => isset($destinatario[2]) ? $destinatario[2] : '',
                        'email4' => isset($destinatario[3]) ? $destinatario[3] : '',
                        'email5' => isset($destinatario[4]) ? $destinatario[4] : ''
                    );

                    fnAutommail(
                        $emailDestino,
                        'Marka Fidelição',
                        'Dados de Login',
                        'Marka Fidelição',
                        $connAdm->connAdm(),
                        connTemp(3, ""),
                        $cod_empresa,
                        './media/clientes/3/',
                        $des_contrato
                    );

                    //LIBERA ACESSO CONSULTORES E FINANCEIRO
                    $sqlBusca = "SELECT COD_USUARIO, NOM_USUARIO, COD_MULTEMP FROM usuarios WHERE cod_empresa = 3 AND COD_USUARIO IN(34,6145,5348,$cod_consultor)";
                    $query = mysqli_query($connAdm->connAdm(), $sqlBusca);
                    if (mysqli_num_rows($query) > 0) {
                        $cod_multemp = "";
                        while ($qrResult = mysqli_fetch_assoc($query)) {
                            $cod_multemp = $qrResult['COD_MULTEMP'] . ',' . $cod_empresa;

                            $sqlUpdate = "UPDATE USUARIOS SET COD_MULTEMP = '$cod_multemp' WHERE COD_EMPRESA = 3 AND COD_USUARIO = " . $qrResult['COD_USUARIO'];
                            mysqli_query($connAdm->connAdm(), $sqlUpdate);
                            // fnEscreve($sqlUpdate);
                        }
                    }

                    $sqlVerifica = "SELECT FASE1, FASE2, FASE3, FASE4, FASE5 FROM AUDITORIA_EMPRESA WHERE COD_EMPRESA = $cod_empresa";
                    $query = mysqli_query($connAdm->connAdm(), $sqlVerifica);
                    $result = mysqli_fetch_assoc($query);
                    if ($result['FASE1'] == 'S' && $result['FASE2'] == 'S' && $result['FASE3'] == 'S' && $result['FASE4'] == 'S' && $result['FASE5'] == 'S') {
                        $sql = "UPDATE AUDITORIA_EMPRESA SET DAT_FINALIZA = NOW() WHERE COD_EMPRESA = $cod_empresa";
                        mysqli_query($connAdm->connAdm(), $sql);
                    }

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
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
    $sql = "SELECT COD_EMPRESA, NOM_EMPRESA, COD_INTEGRADORA, COD_CONSULTOR, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
        $cod_integradora = $qrBuscaEmpresa['COD_INTEGRADORA'];
        $nom_fantasi = $qrBuscaEmpresa['NOM_FANTASI'];
        $cod_consultor = $qrBuscaEmpresa['COD_CONSULTOR'];
    }
} else {
    $cod_empresa = 0;
    //fnEscreve('entrou else');
}

if ($cod_integradora == 34 && $des_destinatario == "") {
    $des_destinatario = "andreza.silva@triersistemas.com.br";
}

?>

<div class="push30"></div>

<div class="row">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="glyphicon glyphicon-calendar"></i>
                    <span class="text-primary"><?php echo $NomePg . " - " . $nom_fantasi; ?></span>
                </div>

                <?php
                $formBack = "1019";
                ?>

            </div>
            <div class="portlet-body">

                <?php if ($msgRetorno <> '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>

                <?php //if ($msgRetorno <> '') { 
                ?>
                <div class="alert alert-warning alert-dismissible top30 bottom30" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php //echo $msgRetorno; 
                    ?>
                    Para gerar os dados, clique em <strong><i class="fas fa-cogs"></i>&nbsp;&nbsp; Processar</strong>, e depois em <strong>Próximo&nbsp;<i class="fas fa-arrow-right"></i></strong>
                </div>
                <?php //} 
                ?>

                <?php $abaEmpresa = 1025; ?>

                <div class="push30"></div>

                <style>
                    .leitura2 {
                        border: none transparent !important;
                        outline: none !important;
                        background: #fff !important;
                        font-size: 18px;
                        padding: 0;
                    }

                    .container-fluid .passo:not(:first-of-type) {
                        display: none;
                    }

                    .wizard .col-md-2 {
                        padding: 0;
                    }

                    .btn-circle {
                        background-color: #DDD;
                        opacity: 1 !important;
                        border: 2px solid #efefef;
                        height: 55px;
                        width: 55px;
                        margin-top: -23px;
                        padding-top: 11px;
                        border-radius: 50%;
                        -moz-border-radius: 50%;
                        -webkit-border-radius: 50%;
                        color: #fff;
                        font-size: 20px;
                    }

                    .fa-2x {
                        font-size: 19px;
                        margin-top: 5px;
                    }

                    .collapse-chevron .fa {
                        transition: .3s transform ease-in-out;
                    }

                    .collapse-chevron .collapsed .fa {
                        transform: rotate(-90deg);
                    }

                    .pull-right,
                    .pull-left {
                        margin-top: 3.5px;
                    }

                    .fundo {
                        background: #D3D3D3;
                        height: 10px;
                        width: 100%;
                    }

                    .fundoAtivo {
                        background: #2ed4e0;
                    }

                    .inicio {
                        background: #2ed4e0;
                        border-bottom-left-radius: 10px 7px;
                        border-top-left-radius: 10px 7px;
                    }

                    .final {
                        border-bottom-right-radius: 10px 7px;
                        border-top-right-radius: 10px 7px;
                    }

                    .notify-badge {
                        position: absolute;
                        display: flex;
                        align-items: center;
                        right: 36%;
                        top: 10px;
                        border-radius: 30px 30px 30px 30px;
                        text-align: center;
                        color: white;
                        font-size: 11px;
                    }

                    .notify-badge span {
                        margin: 0 auto;
                    }

                    .bg-success {
                        background-color: #18bc9c;
                    }

                    .bg-warning {
                        background-color: #f39c12;
                    }

                    .center-content {
                        display: flex;
                        flex-direction: row;
                        align-items: center;
                        justify-content: center;
                        height: 80px;
                        text-align: center;
                        border-radius: 6px;
                        box-shadow: 1px 2px 2px 1px rgba(0, 0, 0, 0.2);
                        background-color: #fff;
                        padding: 4px;
                    }

                    #icon-copy {
                        cursor: pointer;
                        opacity: 0.5;
                    }

                    #icon-copy:hover {
                        opacity: 1;
                    }
                </style>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">



                        <div class="container-fluid">

                            <div class="passo" id="passo1">


                                <div class="row">

                                    <div class="col-sm-12" style="padding-left: 0;">

                                        <?php
                                        $abaAtivo = 2102;
                                        include 'menuAutomacao.php';
                                        ?>

                                        <div class="col-xs-10">
                                            <!-- conteudo abas -->
                                            <div class="tab-content">


                                                <!-- aba produtos-->
                                                <div class="tab-pane active"">
													<h4 style=" margin: 0 0 5px 0;"><span class="bolder">Dados de Login</span></h4>
                                                    <small style="font-size: 12px;"></small>

                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                                                                <div class="push20"></div>

                                                                <fieldset>
                                                                    <legend>Dados Gerais</legend>

                                                                    <div class="row">

                                                                        <?php
                                                                        // CAMPANHAS

                                                                        $temUsuario = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
                                                                        $check1 = "";
                                                                        $temEmail = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
                                                                        $check2 = "";
                                                                        $desativatoogle = "";
                                                                        $des_contrato = "";
                                                                        $des_corpoemail  = "";
                                                                        $des_assuntoemail  = "";
                                                                        $log_emailenv = "";
                                                                        $desabilitado = "";
                                                                        $check = "";

                                                                        $buscaTemplate = "SELECT * FROM usuarios WHERE COD_EMPRESA = $cod_empresa AND COD_TPUSUARIO = 10";
                                                                        $query = mysqli_query($connAdm->connAdm(), $buscaTemplate);
                                                                        if ($result = mysqli_fetch_assoc($query)) {
                                                                            $temUsuario = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
                                                                            $check1 = "checked";
                                                                        }

                                                                        $buscaEmail = "SELECT DES_CONTRATO, DES_DESTINATARIO, DES_CORPOEMAIL, DES_ASSUNTOEMAIL, LOG_EMAILENV FROM AUDITORIA_EMPRESA WHERE COD_EMPRESA = $cod_empresa";
                                                                        $queryEmail = mysqli_query($connAdm->connAdm(), $buscaEmail);
                                                                        if ($qrResult = mysqli_fetch_assoc($queryEmail)) {
                                                                            if ($qrResult['LOG_EMAILENV'] == 'S') {
                                                                                $temEmail = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
                                                                                $check2 = "checked";
                                                                                $des_contrato = $qrResult['DES_CONTRATO'];
                                                                                $des_destinatario = $qrResult['DES_DESTINATARIO'];
                                                                                $des_corpoemail = $qrResult['DES_CORPOEMAIL'];
                                                                                $des_assuntoemail = $qrResult['DES_ASSUNTOEMAIL'];
                                                                                $log_emailenv = $qrResult['LOG_EMAILENV'];
                                                                                $desativatoogle = "disabled";
                                                                                $desabilitado = "disabled";
                                                                                $check = "checked";
                                                                            }
                                                                        }

                                                                        ?>

                                                                        <div class="col-md-3">
                                                                            <div class="center-content" style="margin-top: 12px;">
                                                                                <div style="flex-direction: column;">
                                                                                    <div>
                                                                                        <span>Usuário WS</span>
                                                                                        <?= $temUsuario ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <div class="center-content" style="margin-top: 12px;">
                                                                                <div style="flex-direction: column;">
                                                                                    <div>
                                                                                        <span>Envio por Email</span>
                                                                                        <?= $temEmail ?>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label class="switch switch-small">
                                                                                            <input type="checkbox" name="LOG_EMAILENV" id="LOG_EMAILENV" class="switch" value="S" <?= $desativatoogle ?> <?= $check ?> />
                                                                                            <span></span>
                                                                                        </label>
                                                                                        <div class="help-block with-errors"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <div class="push10"></div>

                                                                    <div class="row" id="templateRow">
                                                                        <div class="col-md-12">
                                                                            <div class="alert alert-warning alert-dismissible top30 bottom30" role="alert" id="msg">
                                                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                Uma planilha contendo os dados de login de cada unidade será anexada automaticamente ao e-mail.
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <label for="inputName" class="control-label">E-mails Destinatários</label>
                                                                            <div class="form-group">
                                                                                <input type="text" placeholder="Copiar para" class="form-control input-sm" name="DES_DESTINATARIO" id="DES_DESTINATARIO" <?= $desabilitado ?> value="<?= $des_destinatario ?>" maxlength="255">
                                                                                <div class="help-block with-errors">Separar os emails por <b>Vírgula</b></div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- <div class="col-md-3">
                                                                            <label for="inputName" class="control-label">Assunto</label>
                                                                            <div class="form-group">
                                                                                <input type="text" placeholder="Assunto" class="form-control input-sm" name="DES_ASSUNTOEMAIL" id="DES_ASSUNTOEMAIL" <?= $desabilitado ?> value="<?= $des_assuntoemail ?>" maxlength="50">
                                                                                <div class="help-block with-errors"></div>
                                                                            </div>
                                                                        </div> -->

                                                                        <div class="col-md-3">
                                                                            <label for="inputName" class="control-label">Contrato empresa</label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-btn">
                                                                                    <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_CONTRATO" extensao="all"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                                                                </span>
                                                                                <input type="hidden" name="DES_CONTRATO" id="DES_CONTRATO" maxlength="100" <?= $desabilitado ?>value="<?= $des_contrato ?>">
                                                                                <input type="text" placeholder="Contrato" name="CONTRATO" id="CONTRATO" <?= $desabilitado ?> class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?= fnBase64DecodeImg($des_contrato) ?>">
                                                                            </div>
                                                                            <span class="help-block">Informar somente se for necessário enviar para SH</span>
                                                                        </div>

                                                                    </div>

                                                                    <!-- <div class="push10"></div>

                                                                    <div class="row" id="uploadRow">
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label for="inputName" class="control-label">Corpo do Email</label>
                                                                                <textarea type="text" class="form-control input-sm" rows="3" name="DES_CORPOEMAIL" id="DES_CORPOEMAIL" <?= $desabilitado ?> maxlength="200"><?= $des_corpoemail ?></textarea>
                                                                            </div>
                                                                            <div class="help-block with-errors"></div>
                                                                        </div>
                                                                    </div> -->

                                                                </fieldset>

                                                            </form>

                                                            <div class="push20"></div>

                                                            <?php

                                                            // BUSCA USUARIO WS
                                                            $sql = "SELECT LOG_USUARIO, DES_SENHAUS FROM usuarios WHERE COD_EMPRESA = $cod_empresa AND COD_TPUSUARIO = 10";
                                                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                                            if (mysqli_num_rows($arrayQuery) > 0) {
                                                                $qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
                                                                $log_usuario = $qrBuscaUsuario['LOG_USUARIO'];
                                                                $des_senhaus = $qrBuscaUsuario['DES_SENHAUS'];

                                                            ?>

                                                                <div class="row">
                                                                    <div class="col-md-12">

                                                                        <table class="table table-bordered table-hover tablesorter buscavel">

                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Cód. Unidade</th>
                                                                                    <th>CNPJ</th>
                                                                                    <th>Unidade</th>
                                                                                    <th>Cód. Empresa</th>
                                                                                    <?php if ($cod_integradora != 13) { ?>
                                                                                        <th>Login</th>
                                                                                        <th>Senha</th>
                                                                                    <?php } else { ?>
                                                                                        <th class="{ sorter: false }">Token</th>
                                                                                        <th class="{ sorter: false }"></th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody id="relatorioConteudo">

                                                                                <?php

                                                                                $sql = "SELECT 1
                                                                                FROM UNIDADEVENDA
                                                                                WHERE COD_EMPRESA = $cod_empresa";

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
                                                                                    COD_UNIVEND,
                                                                                    NOM_FANTASI,
                                                                                    NUM_CGCECPF
                                                                                        FROM UNIDADEVENDA
                                                                                        WHERE COD_EMPRESA = $cod_empresa
                                                                                    LIMIT $inicio, $itens_por_pagina
                                                                                    ";

                                                                                //fnEscreve($sql);
                                                                                $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                                                                $count = 0;
                                                                                $webhook = 'webhook';
                                                                                while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

                                                                                    //MONTA BASE64
                                                                                    $usuarioEncode = $log_usuario . ';' . fnDecode($des_senhaus) . ';' . $qrListaVendas['COD_UNIVEND'] . ';' . $webhook . ';' . $cod_empresa;
                                                                                    $autoriz = base64_encode(fnEncode($usuarioEncode));

                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?= $qrListaVendas['COD_UNIVEND']; ?></td>
                                                                                        <td><?= fnformatCnpjCpf($qrListaVendas['NUM_CGCECPF']); ?></td>
                                                                                        <td><?= $qrListaVendas['NOM_FANTASI']; ?></td>
                                                                                        <td><?= $cod_empresa; ?></td>
                                                                                        <?php if ($cod_integradora != 13) { ?>
                                                                                            <td><?= $log_usuario; ?></td>
                                                                                            <td><?= fnDecode($des_senhaus); ?></td>
                                                                                        <?php } else { ?>
                                                                                            <td><?= (strlen($autoriz) > 55) ? substr($autoriz, 0, 55) . '...' : $autoriz; ?></td>
                                                                                            <td><i id="icon-copy" class="fal fa-copy" onclick="copy(<?= $count ?>)"></i></td>
                                                                                        <?php } ?>

                                                                                    </tr>
                                                                                    <?php if ($cod_integradora == 13) { ?>
                                                                                        <input type="hidden" id="CLIP_<?= $count ?>" name="CLIP_<?= $count ?>" value="<?= $autoriz; ?>" />
                                                                                <?php
                                                                                    }
                                                                                    $count++;
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

                                                            <?php } ?>

                                                        </div>

                                                    </div>

                                                </div>


                                            </div>

                                        </div>

                                        <div class="clearfix"></div>

                                    </div>



                                    <hr>

                                    <div class="form-group text-right col-lg-12">
                                        <button type="submit" name="CAD" id="CAD" class="btn btn-success getBtn"><i class="fas fa-cogs"></i>&nbsp;&nbsp;Processar</button>
                                    </div>

                                    <div class="push10"></div>

                                </div>



                            </div>


                            <div class="push10"></div>

                            <input type="hidden" name="opcao" id="opcao" value="">
                            <input type="hidden" name="COD_CONSULTOR" id="COD_CONSULTOR" value="<?= $cod_consultor ?>">
                            <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                            <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
                            <input type="hidden" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?= $nom_empresa ?>">
                            <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

                            <div class="push5"></div>

                    </form>

                    <div class="push50"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>

<div class="push20"></div>

<script type="text/javascript">
    function retornaForm(index) {
        $("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
        $("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }

    $(document).ready(function() {

        var numPaginas = <?php echo $numPaginas; ?>;
        if (numPaginas != 0) {
            carregarPaginacao(numPaginas);
        }

        // Verifica o estado do checkbox ao carregar a página
        if ($('#LOG_EMAILENV').prop('checked')) {
            $('#templateRow').show();
            $('#uploadRow').show();
        } else {
            $('#templateRow').hide();
            $('#uploadRow').hide();
        }

        // Monitora as mudanças no checkbox
        $('#LOG_EMAILENV').change(function() {
            if ($(this).prop('checked')) {
                $('#templateRow').show();
                $('#uploadRow').show();
            } else {
                $('#templateRow').hide();
                $('#uploadRow').hide();
            }
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
                                icon: 'fal fa-check-square-o',
                                content: function() {
                                    var self = this;
                                    return $.ajax({
                                        url: "ajxAutomacao.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
                                        data: $('#formulario').serialize(),
                                        method: 'POST'
                                    }).done(function(response) {
                                        console.log(response);
                                        self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
                                        var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
                                        SaveToDisk('media/excel/' + fileName, fileName);
                                        //console.log(response);
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
        });

    });

    function copy(index) {
        // Obtém o valor do input com id "CLIP_" + index
        var copyText = document.getElementById("CLIP_" + index).value;

        // Usando a API de Clipboard para copiar o texto
        navigator.clipboard.writeText(copyText);
    }

    function copiar(index) {



    }

    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "relatorios/ajxAutomacao.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
            data: $('#formulario').serialize(),
            beforeSend: function() {
                $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#relatorioConteudo").html(data);
            },
            error: function() {
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }

    function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('id', 3);
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
            processData: false,
            contentType: false,
            success: function(data) {

                data = JSON.parse(data);

                $('.jconfirm-open').fadeOut(300, function() {
                    $(this).remove();
                });
                if (data.success) {
                    $('#' + idField.replace("arqUpload_DES_", "")).val(nomeArquivo);
                    $('#' + idField.replace("arqUpload_", "")).val(data.nome_arquivo);

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



            },
            error: function() {
                alert('Erro ao carregar...');
            }
        });
    }
</script>