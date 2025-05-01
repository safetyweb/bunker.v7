<?php
//echo fnDebug('true');

$itens_por_pagina = 50;
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = mt_rand();

$log_externo = 'N';

$mod = fnDecode($_GET['mod']);
// 1757 - emendas apoiador
// 1731 - emendas geral

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;
        $filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);
        $val_pesquisa = fnLimpaCampo($_POST['INPUT']);

        // fnEscreve($filtro);
        // fnEscreve($val_pesquisa);

        $cod_emenda = fnLimpaCampoZero($_POST['COD_EMENDA']);
        $cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
        $cod_objeto = fnLimpaCampoZero($_POST['COD_OBJETO']);
        $des_emenda = fnLimpaCampo($_POST['DES_EMENDA']);
        $cod_tipo = fnLimpaCampoZero($_POST['COD_TIPO']);
        $cod_orgao = fnLimpaCampoZero($_POST['COD_ORGAO']);
        $cod_status = fnLimpaCampoZero($_POST['COD_STATUS']);
        $cod_estado = fnLimpaCampoZero($_POST['COD_ESTADO']);
        $cod_municipio = fnLimpaCampoZero($_POST['COD_MUNICIPIO']);
        $cod_beneficiario = fnLimpaCampoZero($_POST['COD_BENEFICIARIO']);
        $cod_responsavel = fnLimpaCampoZero($_POST['COD_RESPONSAVEL']);
        $num_lote = fnLimpaCampo($_POST['NUM_LOTE']);
        $num_sequencia = fnLimpaCampo($_POST['NUM_SEQUENCIA']);
        $cod_alesp = fnLimpaCampo($_POST['COD_ALESP']);
        $num_emedapal = fnLimpaCampo($_POST['NUM_EMEDAPAL']);
        $val_emenda = fnValorSql($_POST['VAL_EMENDA']);
        $dat_ini = fnDataSql($_POST['DAT_INI']);

        if ($dat_ini == "") {
            $filtrodata = "NULL";
        } else {
            $filtrodata = "'{$dat_ini}'";
        }

        $cod_usucada = $_SESSION[SYS_COD_USUARIO];

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {

            switch ($opcao) {
                case 'CAD':

                    $sql = "INSERT INTO EMENDA(
                                                COD_EMPRESA,
                                                DES_EMENDA,
                                                COD_TIPO,
                                                COD_ORGAO,
                                                COD_STATUS,
                                                COD_ESTADO,
                                                COD_MUNICIPIO,
                                                COD_BENEFICIARIO,
                                                COD_RESPONSAVEL,
                                                NUM_LOTE,
                                                NUM_SEQUENCIA,
                                                COD_ALESP,
                                                NUM_EMEDAPAL,
                                                VAL_EMENDA,
                                                DAT_INI,
                                                COD_USUCADA
                                        ) VALUES(
                                                '$cod_empresa',
                                                '$des_emenda',
                                                '$cod_tipo',
                                                '$cod_orgao',
                                                '$cod_status',
                                                '$cod_estado',
                                                '$cod_municipio',
                                                '$cod_beneficiario',
                                                '$cod_responsavel',
                                                '$num_lote',
                                                '$num_sequencia',
                                                '$cod_alesp',
                                                '$num_emedapal',
                                                '$val_emenda',
                                                 $filtrodata,
                                                '$cod_usucada'
                                        )";

                    // fnEscreve($sql);
                    mysqli_query(connTemp($cod_empresa, ''), $sql);

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;
                case 'ALT':

                    $sql = "UPDATE EMENDA SET
                                    DES_EMENDA = '$des_emenda',
                                    COD_TIPO = '$cod_tipo',
                                    COD_ORGAO = '$cod_orgao',
                                    COD_STATUS = '$cod_status',
                                    COD_ESTADO = '$cod_estado',
                                    COD_MUNICIPIO = '$cod_municipio',
                                    COD_BENEFICIARIO = '$cod_beneficiario',
                                    COD_RESPONSAVEL = '$cod_responsavel',
                                    NUM_LOTE = '$num_lote',
                                    NUM_SEQUENCIA = '$num_sequencia',
                                    COD_ALESP = '$cod_alesp',
                                    NUM_EMEDAPAL = '$num_emedapal',
                                    VAL_EMENDA = '$val_emenda',
                                    DAT_INI = $filtrodata,
                                    COD_ALTERAC = '$cod_usucada',
                                    DAT_ALTERAC = NOW()
                            WHERE COD_EMENDA = $cod_emenda
                            AND COD_EMPRESA = $cod_empresa";

                    //fnEscreve($sql);
                    mysqli_query(connTemp($cod_empresa, ''), $sql);

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;
                case 'EXC':

                    $sql = "UPDATE EMENDA SET
                            COD_EXCLUSA = '$cod_usucada',
                            DAT_EXCLUSA = NOW()
                            WHERE COD_EMENDA = $cod_emenda
                            AND COD_EMPRESA = $cod_empresa";

                    mysqli_query(connTemp($cod_empresa, ''), $sql);

                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                    break;
            }
            $msgTipo = 'alert-success';
        }
    }
}
if ($val_pesquisa != "") {
    $esconde = " ";
} else {
    $esconde = "display: none;";
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $cod_campanha = fnDecode($_GET['idc']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
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

if ($log_externo == 'S') {
    $check_externo = 'checked';
} else {
    $check_externo = '';
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

if ($filtro != "") {

    if ($filtro == "COD_MUNICIPIO") {

        $sqlMunicipio = "SELECT DISTINCT MN.COD_MUNICIPIO FROM MUNICIPIOS MN, EMENDA EM
                            WHERE MN.NOM_MUNICIPIO LIKE '%$val_pesquisa%' 
                            and MN.COD_MUNICIPIO = EM.COD_MUNICIPIO";

        $arrayCidade = mysqli_query(connTemp($cod_empresa, ''), $sqlMunicipio);

        while($qrMunicipio = mysqli_fetch_assoc($arrayCidade)){
            $cod_municipio .= fnLimpaCampoZero($qrMunicipio[COD_MUNICIPIO]).",";
        }

        $cod_municipio = ltrim(rtrim($cod_municipio,","),",");

        if ($cod_municipio == 0) {
            $andMunicipio = "";
        } else {
            $andMunicipio = "AND EM.COD_MUNICIPIO IN($cod_municipio)";
        }
    } else if ($filtro == "COD_STATUS") {

        $sqlStatus = "SELECT COD_STATUS FROM STATUS_EMENDA 
                            WHERE COD_EMPRESA = $cod_empresa 
                            AND DES_STATUS = '$val_pesquisa' 
                            ORDER BY 1 DESC LIMIT 1";

        $arrayStatus = mysqli_query(connTemp($cod_empresa, ''), $sqlStatus);

        $qrStatus = mysqli_fetch_assoc($arrayStatus);

        $cod_status = fnLimpaCampoZero($qrStatus[COD_STATUS]);

        if ($cod_status == 0) {
            $andStatus = "";
        } else {
            $andStatus = "AND EM.COD_STATUS = $cod_status";
        }
    } else if ($filtro == "COD_EMENDA") {

        if ($val_pesquisa == 0) {
            $andEmenda = "";
        } else {
            $andEmenda = "AND EM.COD_EMENDA = $val_pesquisa";
        }
    } else {
        $andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
    }
} else {
    $andFiltro = " ";
}

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($lojasReportAdm);
?>

<style>
    table a:not(.btn),
    .table a:not(.btn) {
        text-decoration: none;
    }

    table a:not(.btn):hover,
    .table a:not(.btn):hover {
        text-decoration: underline;
    }

    #COD_OBJETO .chosen-drop .chosen-results li:last-child,
    #COD_TIPO .chosen-drop .chosen-results li:last-child,
    #COD_ORGAO .chosen-drop .chosen-results li:last-child,
    #COD_STATUS .chosen-drop .chosen-results li:last-child {
        font-weight: bolder;
        font-size: 11px;
        color: #000;
    }

    #COD_OBJETO .chosen-drop .chosen-results li:last-child:before,
    #COD_TIPO .chosen-drop .chosen-results li:last-child:before,
    #COD_ORGAO .chosen-drop .chosen-results li:last-child:before,
    #COD_STATUS .chosen-drop .chosen-results li:last-child:before {
        content: '\002795';
        font-weight: bolder;
        font-size: 9px;
    }

    td{
        width:10px;
    }
</style>

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
                //$formBack = "1015";
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
                //menu superior - cliente
                $abaCli = 1757;
                if ($popUp != "true" && $mod == 1757) {

                    include "abasClienteConfig.php";

                    $cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idC']));

                    $sqlMunicipio = "SELECT COD_MUNICIPIO FROM CLIENTES 
                                        WHERE COD_EMPRESA = $cod_empresa 
                                        AND COD_CLIENTE = $cod_cliente";

                    $arrayMunicipio = mysqli_query(connTemp($cod_empresa, ''), $sqlMunicipio);

                    $qrCodMunicipio = mysqli_fetch_assoc($arrayMunicipio);

                    if ($cod_municipio == "") {
                        $cod_municipio = fnLimpaCampoZero($qrCodMunicipio[COD_MUNICIPIO]);
                    }

                ?>
                    <div class="push30"></div>
                <?php
                }
                ?>


                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">

                                <input type="hidden" name="COD_EMENDA" id="COD_EMENDA" value="">

                                <div class="col-md-4">
                                    <label for="inputName" class="control-label required">Responsável</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBuscaResp" id="btnBuscaResp" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($cod_cliente) ?>&pop=true&op=REM" data-title="Busca Indicador"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
                                        </span>
                                        <input type="text" name="NOM_RESPONSAVEL" id="NOM_RESPONSAVEL" value="" maxlength="50" readonly="" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
                                        <input type="hidden" name="COD_RESPONSAVEL" id="COD_RESPONSAVEL" value="" >
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label <?= $rqrCOD_ESTACIV ?>">Tipo de Emenda</label>
                                        <div id="relatorioTipo">
                                            <select data-placeholder="Selecione um tipo" name="COD_TIPO" id="COD_TIPO" class="chosen-select-deselect">
                                                <option value=""></option>
                                                <?php
                                                $sql = "SELECT * FROM TIPO_EMENDA WHERE COD_EMPRESA = $cod_empresa";
                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                                ?>

                                                    <option value="<?= $qrBusca[COD_TIPO] ?>"><?= $qrBusca[DES_TIPO] ?></option>

                                                <?php
                                                }
                                                ?>
                                                <!-- <option value=""></option>					
                                                <option value="1">Impositivas</option>					
                                                <option value="2">Voluntárias</option> -->
                                                <option class="fas fa-plus" value="add">&nbsp;Adicionar Novo</option>
                                            </select>
                                            <script type="text/javascript">
                                                $('#COD_TIPO').change(function() {
                                                    valor = $(this).val();
                                                    if (valor == "add") {
                                                        $(this).val('').trigger("chosen:updated");
                                                        $('#btnCad_COD_TIPO').click();
                                                    }
                                                });
                                            </script>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                        <a type="hidden" name="btnCad_COD_TIPO" id="btnCad_COD_TIPO" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1750) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Tipo"></a>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Secretaria / Orgão</label>
                                        <div id="relatorioOrgao">
                                            <select data-placeholder="Selecione um orgão" name="COD_ORGAO" id="COD_ORGAO" class="chosen-select-deselect">
                                                <option value=""></option>
                                                <?php
                                                $sql = "SELECT * FROM ORGAO_EMENDA WHERE COD_EMPRESA = $cod_empresa";
                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                                ?>

                                                    <option value="<?= $qrBusca[COD_ORGAO] ?>"><?= $qrBusca[DES_ORGAO] ?></option>

                                                <?php
                                                }
                                                ?>
                                                <!-- <option value=""></option>										
                                                <option value="">Saúde</option>										
                                                <option value="">Desenvolvimento Social</option>										
                                                <option value="">Desenvolvimento Regional</option>										
                                                <option value="">Cultura</option>										
                                                <option value="">Econômia Criativa</option>										
                                                <option value="">Econômia Criativa</option>	 -->
                                                <option class="fas fa-plus" value="add">&nbsp;Adicionar Novo</option>
                                            </select>
                                            <script type="text/javascript">
                                                $('#COD_ORGAO').change(function() {
                                                    valor = $(this).val();
                                                    if (valor == "add") {
                                                        $(this).val('').trigger("chosen:updated");
                                                        $('#btnCad_COD_ORGAO').click();
                                                    }
                                                });
                                            </script>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                        <a type="hidden" name="btnCad_COD_ORGAO" id="btnCad_COD_ORGAO" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1751) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Orgão/Secretaria"></a>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Lote</label>
                                        <input type="text" class="form-control input-sm" name="NUM_LOTE" id="NUM_LOTE" maxlength="50">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Sequência</label>
                                        <input type="text" class="form-control input-sm" name="NUM_SEQUENCIA" id="NUM_SEQUENCIA" maxlength="50">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cod. Alesp</label>
                                        <input type="text" class="form-control input-sm" name="COD_ALESP" id="COD_ALESP" maxlength="50">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-4">
                                    <label for="inputName" class="control-label required">Beneficiário</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBuscaBen" id="btnBuscaBen" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($cod_cliente) ?>&pop=true&op=BEM" data-title="Busca Beneficiário"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
                                        </span>
                                        <input type="text" name="NOM_BENEFICIARIO" id="NOM_BENEFICIARIO" value="" maxlength="50" readonly="" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
                                        <input type="hidden" name="COD_BENEFICIARIO" id="COD_BENEFICIARIO" value="" >
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Nº Emenda Palácio</label>
                                        <input type="text" class="form-control input-sm" name="NUM_EMEDAPAL" id="NUM_EMEDAPAL" maxlength="50">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Estado</label>
                                        <select data-placeholder="Selecione um estado" name="COD_ESTADO" id="COD_ESTADO" class="chosen-select-deselect">
                                            <option value=""></option>
                                            <?php
                                            $sql = "SELECT COD_ESTADO, UF FROM ESTADO ORDER BY UF";
                                            $arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            while ($qrEstado = mysqli_fetch_assoc($arrayEstado)) {
                                            ?>
                                                <option value="<?= $qrEstado['COD_ESTADO'] ?>"><?= $qrEstado['UF'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <script type="text/javascript">
                                            $('#COD_ESTADO').val("<?= $cod_estado ?>").trigger("chosen:updated");
                                        </script>

                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2" id="relatorioCidade">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Cidade</label>
                                        <select data-placeholder="Selecione uma cidade" name="COD_MUNICIPIO" id="COD_MUNICIPIO" class="chosen-select-deselect">
                                            <option value=""></option>
                                        </select>
                                        <script type="text/javascript">
                                            $('#COD_MUNICIPIO').val("<?= $cod_municipio ?>").trigger("chosen:updated");
                                        </script>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Status Atual</label>
                                        <div id="relatorioStatus">
                                            <select data-placeholder="Selecione um status" name="COD_STATUS" id="COD_STATUS" class="chosen-select-deselect" required>
                                                <option value=""></option>
                                                <?php
                                                $sql = "SELECT * FROM STATUS_EMENDA WHERE COD_EMPRESA = $cod_empresa";
                                                $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                                ?>

                                                    <option value="<?= $qrBusca[COD_STATUS] ?>"><?= $qrBusca[DES_STATUS] ?></option>

                                                <?php
                                                }
                                                ?>

                                                <option class="fas fa-plus" value="add">&nbsp;Adicionar Novo</option>
                                            </select>
                                            <script type="text/javascript">
                                                $('#COD_STATUS').change(function() {
                                                    valor = $(this).val();
                                                    if (valor == "add") {
                                                        $(this).val('').trigger("chosen:updated");
                                                        $('#btnCad_COD_STATUS').click();
                                                    }
                                                });
                                            </script>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                        <a type="hidden" name="btnCad_COD_STATUS" id="btnCad_COD_STATUS" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1752) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Status"></a>
                                    </div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Valor</label>
                                        <input type="text" class="form-control input-sm money" name="VAL_EMENDA" id="VAL_EMENDA" maxlength="50" value="">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Data Inicial</label>

                                        <div class="input-group date datePicker" id="DAT_INI_GRP">
                                            <input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="" />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="push10"></div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Descrição da Emenda</label>
                                        <textarea class="editor form-control input-sm" rows="6" name="DES_EMENDA" id="DES_EMENDA" maxlength="5000"><?php echo $des_emenda; ?></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

                        </div>
                        <!-- <input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />					 -->
                        <!-- <input type="hidden" name="COD_INDICAD" id="COD_INDICAD" value="<?= $cod_indicad ?>"> -->
                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="ANDSTATUS" id="ANDSTATUS" value="<?= $andStatus ?>">
                        <input type="hidden" name="ANDMUNICIPIO" id="ANDMUNICIPIO" value="<?= $andMunicipio ?>">
                        <input type="hidden" name="ANDEMENDA" id="ANDEMENDA" value="<?= $andEmenda ?>">
                        <input type="hidden" name="COD_MUNICIPIO_AUX" id="COD_MUNICIPIO_AUX" value="">
                        <input type="hidden" name="REFRESH_COMBO" id="REFRESH_COMBO" value="N">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

                        <div class="push30"></div>
                    </form>
                    <div class="row">
                        <form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

                            <div class="col-xs-4 col-xs-offset-4">
                                <div class="input-group activeItem">
                                    <div class="input-group-btn search-panel">
                                        <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
                                            <span id="search_concept">Sem filtro</span>&nbsp;
                                            <span class="fal fa-angle-down"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="divisor"><a href="#">Sem filtro</a></li>
                                            <!-- <li class="divider"></li> -->
                                            <li><a href="#COD_EMENDA">Código</a></li>
                                            <li><a href="#COD_MUNICIPIO">Cidade</a></li>
                                            <li><a href="#COD_STATUS">Status</a></li>
                                        </ul>
                                    </div>
                                    <input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">
                                    <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
                                    <div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
                                        <button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
                                    </div>
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="push20"></div>

        <div class="portlet portlet-bordered">

            <div class="portlet-body">

                <div class="login-form">

                    <div class="row">
                        <div class="col-md-12">

                            <div class="no-more-tables">

                                <form name="formLista">

                                    <table class="table table-bordered table-striped table-hover tablesorter buscavel">

                                        <thead>
                                            <tr>
                                                <th width="10" class="{sorter:false}"></th>
                                                <th>Nº.Emenda</th>
                                                <th>Cidade</th>
                                                <th>Descrição</th>
                                                <th>Tipo</th>
                                                <th>Orgão</th>
                                                <th>Status</th>
                                                <th>Beneficiário</th>
                                                <!--<th>Dt. Inicial</th>-->
                                                <th class="text-left">Valor</th>
                                            </tr>

                                        </thead>

                                        <tbody id="relatorioConteudo">

                                            <?php

                                            // FNeSCREVE($filtro);
                                            // FNeSCREVE($val_pesquisa);
                                            // FNeSCREVE($sqlMunicipio);


                                            $sql = "SELECT * FROM EMENDA EM
                                                WHERE COD_EMPRESA = $cod_empresa
                                                $andStatus
                                                $andMunicipio
                                                $andEmenda
                                                ";
                                            //fnTestesql(connTemp($cod_empresa,''),$sql);		
                                            //fnEscreve($sql);

                                            $retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
                                            $totalitens_por_pagina = mysqli_num_rows($retorno);

                                            $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                            //variavel para calcular o início da visualização com base na página atual
                                            $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                            // Filtro por Grupo de Lojas
                                            //include "filtroGrupoLojas.php";


                                            $sql = "SELECT EM.*,
                                                                OBE.DES_OBJETO,
                                                                ORE.DES_ORGAO,
                                                                STE.DES_STATUS,
                                                                TPE.DES_TIPO,
                                                                CL1.NOM_CLIENTE AS NOM_RESPONSAVEL,
                                                                CL2.NOM_CLIENTE AS NOM_BENEFICIARIO,
                                                                NM.NOM_MUNICIPIO
                                                FROM EMENDA EM 
                                                LEFT JOIN OBJETO_EMENDA OBE ON OBE.COD_OBJETO = EM.COD_OBJETO
                                                LEFT JOIN ORGAO_EMENDA ORE ON ORE.COD_ORGAO = EM.COD_ORGAO
                                                LEFT JOIN STATUS_EMENDA STE ON STE.COD_STATUS = EM.COD_STATUS
                                                LEFT JOIN TIPO_EMENDA TPE ON TPE.COD_TIPO = EM.COD_TIPO
                                                LEFT JOIN CLIENTES CL1 ON CL1.COD_CLIENTE = EM.COD_RESPONSAVEL
                                                LEFT JOIN CLIENTES CL2 ON CL2.COD_CLIENTE = EM.COD_BENEFICIARIO
                                                LEFT JOIN municipios NM ON NM.COD_MUNICIPIO = EM.COD_MUNICIPIO 
                                                WHERE EM.COD_EMPRESA = $cod_empresa
                                                AND EM.COD_EXCLUSA = 0
                                                $andStatus
                                                $andMunicipio
                                                $andEmenda
                                                LIMIT $inicio,$itens_por_pagina
                                                ";

                                            // echo($sql);
                                            //fnTestesql(connTemp($cod_empresa,''),$sql);											
                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                            $count = 0;
                                            while ($qrApoia = mysqli_fetch_assoc($arrayQuery)) {

                                                $count++;
                                            ?>
                                                <tr class="emenda">
                                                    <td><input type='radio' name='radio1' onclick='retornaForm(<?php echo $count; ?>)'></td>
                                                    <td class="text-left"><small><?= $qrApoia['NUM_EMEDAPAL'] ?></small></td>
                                                    <td class="text-left"><small><?= $qrApoia['NOM_MUNICIPIO'] ?></small></td>
                                                    <td><small><?= $qrApoia['DES_EMENDA'] ?></small></td>
                                                    <td><small><?= $qrApoia['DES_TIPO'] ?></small></td>
                                                    <td><small><?= $qrApoia['DES_ORGAO'] ?></small></td>
                                                    <td><small><?= $qrApoia['DES_STATUS'] ?></small></td>
                                                    <td class="text-left"><small><?= $qrApoia['NOM_BENEFICIARIO'] ?></small></td>
                                                    <td class="text-left"><small><?= fnValor($qrApoia['VAL_EMENDA'], 2) ?></small></td>
                                                </tr>

                                                <input type="hidden" name="ret_COD_EMENDA_<?= $count ?>" id="ret_COD_EMENDA_<?= $count ?>" value="<?= $qrApoia[COD_EMENDA] ?>">
                                                <input type="hidden" name="ret_NOM_MUNICIPIO_<?= $count ?>" id="ret_NOM_MUNICIPIO_<?= $count ?>" value="<?= $qrApoia[NOM_MUNICIPIO] ?>">
                                                <input type="hidden" name="ret_COD_OBJETO_<?= $count ?>" id="ret_COD_OBJETO_<?= $count ?>" value="<?= $qrApoia[COD_OBJETO] ?>">
                                                <input type="hidden" name="ret_DES_EMENDA_<?= $count ?>" id="ret_DES_EMENDA_<?= $count ?>" value="<?= $qrApoia[DES_EMENDA] ?>">
                                                <input type="hidden" name="ret_COD_TIPO_<?= $count ?>" id="ret_COD_TIPO_<?= $count ?>" value="<?= $qrApoia[COD_TIPO] ?>">
                                                <input type="hidden" name="ret_COD_ORGAO_<?= $count ?>" id="ret_COD_ORGAO_<?= $count ?>" value="<?= $qrApoia[COD_ORGAO] ?>">
                                                <input type="hidden" name="ret_COD_STATUS_<?= $count ?>" id="ret_COD_STATUS_<?= $count ?>" value="<?= $qrApoia[COD_STATUS] ?>">
                                                <input type="hidden" name="ret_COD_ESTADO_<?= $count ?>" id="ret_COD_ESTADO_<?= $count ?>" value="<?= $qrApoia[COD_ESTADO] ?>">
                                                <input type="hidden" name="ret_COD_MUNICIPIO_<?= $count ?>" id="ret_COD_MUNICIPIO_<?= $count ?>" value="<?= $qrApoia[COD_MUNICIPIO] ?>">
                                                <input type="hidden" name="ret_COD_BENEFICIARIO_<?= $count ?>" id="ret_COD_BENEFICIARIO_<?= $count ?>" value="<?= $qrApoia[COD_BENEFICIARIO] ?>">
                                                <input type="hidden" name="ret_NOM_BENEFICIARIO_<?= $count ?>" id="ret_NOM_BENEFICIARIO_<?= $count ?>" value="<?= $qrApoia[NOM_BENEFICIARIO] ?>">
                                                <input type="hidden" name="ret_COD_RESPONSAVEL_<?= $count ?>" id="ret_COD_RESPONSAVEL_<?= $count ?>" value="<?= $qrApoia[COD_RESPONSAVEL] ?>">
                                                <input type="hidden" name="ret_NOM_RESPONSAVEL_<?= $count ?>" id="ret_NOM_RESPONSAVEL_<?= $count ?>" value="<?= $qrApoia[NOM_RESPONSAVEL] ?>">
                                                <input type="hidden" name="ret_NUM_LOTE_<?= $count ?>" id="ret_NUM_LOTE_<?= $count ?>" value="<?= $qrApoia[NUM_LOTE] ?>">
                                                <input type="hidden" name="ret_NUM_SEQUENCIA_<?= $count ?>" id="ret_NUM_SEQUENCIA_<?= $count ?>" value="<?= $qrApoia[NUM_SEQUENCIA] ?>">
                                                <input type="hidden" name="ret_COD_ALESP_<?= $count ?>" id="ret_COD_ALESP_<?= $count ?>" value="<?= $qrApoia[COD_ALESP] ?>">
                                                <input type="hidden" name="ret_NUM_EMEDAPAL_<?= $count ?>" id="ret_NUM_EMEDAPAL_<?= $count ?>" value="<?= $qrApoia[NUM_EMEDAPAL] ?>">
                                                <input type="hidden" name="ret_VAL_EMENDA_<?= $count ?>" id="ret_VAL_EMENDA_<?= $count ?>" value="<?= fnValor($qrApoia[VAL_EMENDA], 2) ?>">
                                                <input type="hidden" name="ret_DAT_INI_<?= $count ?>" id="ret_DAT_INI_<?= $count ?>" value="<?= fnDataShort($qrApoia[DAT_INI]) ?>">
                                            <?php
                                            }
                                            ?>
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th>
                                                    <div class="col-md-1">
                                                        <a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
                                                    </div>
                                                    <div class="push10"></div>
                                                    <a href="#" class="btn btn-info addBox pull-left" id="print" data-url="action.php?mod=<?= fnEncode(1804) ?>&id=<?= fnEncode($cod_empresa); ?>&pop=true" data-title="Impressão de Emendas"><i class="fal fa-print" aria-hidden="true"></i>&nbsp; Impressão de Emendas </a>
                                                <th>
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
                                </form>
                            </div>


                        </div>
                    </div>



                    <div class="push5"></div>



                    <div class="push50"></div>

                    <div class="push"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
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
                <iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<div id="retornaCombo"></div>

<script>
    $(document).ready(function(e) {
        var value = $('#INPUT').val().toLowerCase().trim();
        if (value) {
            $('#CLEARDIV').show();
        } else {
            $('#CLEARDIV').hide();
        }
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#", "");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #VAL_PESQUISA').val(param);
            $('#INPUT').focus();
        });

        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
        });

        $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
            $("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
        });

        $('#CLEAR').click(function() {
            $('#INPUT').val('');
            $('#INPUT').focus();
            $('#CLEARDIV').hide();
            if ("<?= $filtro ?>" != "") {
                location.reload();
            } else {
                var value = $('#INPUT').val().toLowerCase().trim();
                if (value) {
                    $('#CLEARDIV').show();
                } else {
                    $('#CLEARDIV').hide();
                }
                $(".buscavel tr").each(function(index) {
                    if (!index) return;
                    $(this).find("td").each(function() {
                        var id = $(this).text().toLowerCase().trim();
                        var sem_registro = (id.indexOf(value) == -1);
                        $(this).closest('tr').toggle(!sem_registro);
                        return sem_registro;
                    });
                });
            }
        });

        $('#SEARCH').click(function() {
            buscaRegistro($('#INPUT'));
        });
    });

    function buscaRegistro(el) {
        var filtro = $('#search_concept').text().toLowerCase();

        if (filtro == "sem filtro") {
            var value = $(el).val().toLowerCase().trim();
            if (value) {
                $('#CLEARDIV').show();
            } else {
                $('#CLEARDIV').hide();
            }
            $(".buscavel tr").each(function(index) {
                if (!index) return;
                $(this).find("td").each(function() {
                    var id = $(this).text().toLowerCase().trim();
                    var sem_registro = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!sem_registro);
                    return sem_registro;
                });
            });
        }
    }
    var listaClientes = [],
        current_page = 1;

    //datas
    $(function() {

        //chosen
        $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        $('#formulario').validator();

        var codEstado = "<?php echo $cod_estado; ?>";

        var numPaginas = <?php echo $numPaginas; ?>;
        if (numPaginas != 0) {
            carregarPaginacao(numPaginas);
        }

        if (codEstado != 0 && codEstado != "") {
            carregaComboCidades(codEstado, "<?php echo $cod_municipio; ?>");
        }

        jQuery('#paginacao').on('page', function(event, page) {
            current_page = page;
        });



        $('.datePicker').datetimepicker({
            format: 'DD/MM/YYYY',
            maxDate: 'now',
        }).on('changeDate', function(e) {
            $(this).datetimepicker('hide');
        });

        $("#DAT_INI_GRP").on("dp.change", function(e) {
            $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
        });

        $("#DAT_FIM_GRP").on("dp.change", function(e) {
            $('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
        });

        $("#addCadImport").click(function() {
            if (!$(this).is("[disabled]")) {
                importaCliente(JSON.stringify(listaClientes), "multiplo")
            }
        });

        //modal close
        $('.modal').on('hidden.bs.modal', function() {

            if ($('#REFRESH_COMBO').val() == "S") {
                refreshCombo("<?php echo fnEncode($cod_empresa); ?>");
                $('#REFRESH_COMBO').val("N");
                $(".chosen-select-deselect").chosen({
                    allow_single_deselect: true
                });
            }

        });

    });


    $("#COD_ESTADO").change(function() {
        cod_estado = $(this).val();
        carregaComboCidades(cod_estado, "");
        estado = $("#COD_ESTADO option:selected").text();
        $('#COD_ESTADOF').val(estado);
        $('#NOM_CIDADEC').val('');
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
                            icon: 'fa fa-check-square-o',
                            content: function() {
                                var self = this;
                                return $.ajax({
                                    url: "ajxCadastroEmendas.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
    });

    function carregaComboCidades(cod_estado, cod_municipio) {
        $.ajax({
            method: 'POST',
            url: 'ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>',
            data: {
                COD_ESTADO: cod_estado
            },
            beforeSend: function() {
                $('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#relatorioCidade").html(data);
                if(cod_municipio != "" && cod_municipio != 0){
                    $("#formulario #COD_MUNICIPIO").val(cod_municipio).trigger("chosen:updated");
                }else if ("<?php echo $cod_municipio; ?>" != 0 && "<?php echo $cod_municipio; ?>" != "" ) {
                    $("#formulario #COD_MUNICIPIO").val("<?php echo $cod_municipio; ?>").trigger("chosen:updated");
                }

                // $('#formulario').validator('validate');
                //alert(cod_municipio);
                //alert("<?php echo $cod_municipio; ?>");
            }
        });
    }

    function reloadPage(idPage) {
        $.ajax({
            type: "POST",
            url: "ajxCadastroEmendas.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
            data: $('#formulario').serialize(),
            beforeSend: function() {
                $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $("#relatorioConteudo").html(data);
                $(".tablesorter").trigger("updateAll");
                // console.log(data);										
            },
            error: function() {
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }

    function importaCliente(cod_cliente, opcao) {
        $.ajax({
            type: "POST",
            url: "ajxListaApoiadorExterno.do?opcao=" + opcao,
            data: {
                COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
                COD_CLIENTE: cod_cliente
            },
            beforeSend: function() {
                $('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                console.log(data);
                reloadPage(current_page);
            },
            error: function() {
                $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }

    function refreshCombo(cod_empresa) {
        $.ajax({
            type: "POST",
            url: "ajxCombosEmenda.do?id=<?= fnEncode($cod_empresa) ?>",
            beforeSend: function() {
                $('#relatorioObjeto').html('<div class="loading" style="width: 100%;"></div>');
                $('#relatorioOrgao').html('<div class="loading" style="width: 100%;"></div>');
                $('#relatorioTipo').html('<div class="loading" style="width: 100%;"></div>');
                $('#relatorioStatus').html('<div class="loading" style="width: 100%;"></div>');
            },
            success: function(data) {
                $('#relatorioObjeto').html($('#relatorioObjetoAjx', data));
                $('#relatorioOrgao').html($('#relatorioOrgaoAjx', data));
                $('#relatorioTipo').html($('#relatorioTipoAjx', data));
                $('#relatorioStatus').html($('#relatorioStatusAjx', data));
                $('#retornaCombo').html($('#scripts', data));
                $('#formulario').validator('validate');
            },
            error: function() {
                // $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
            }
        });
    }

    function retornaForm(index) {
        $("#formulario #COD_EMENDA").val($("#ret_COD_EMENDA_" + index).val());
        $("#formulario #COD_OBJETO").val($("#ret_COD_OBJETO_" + index).val()).trigger("chosen:updated");
        $("#formulario #DES_EMENDA").val($("#ret_DES_EMENDA_" + index).val());
        $("#formulario #COD_TIPO").val($("#ret_COD_TIPO_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_ORGAO").val($("#ret_COD_ORGAO_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_STATUS").val($("#ret_COD_STATUS_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_ESTADO").val($("#ret_COD_ESTADO_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_MUNICIPIO_AUX").val($("#ret_COD_MUNICIPIO_" + index).val());
        carregaComboCidades($("#ret_COD_ESTADO_" + index).val(), $("#ret_COD_MUNICIPIO_" + index).val());
        // alert($("#ret_COD_ESTADO_" + index).val());
        // alert($("#ret_COD_MUNICIPIO_" + index).val());
        $("#formulario #COD_BENEFICIARIO").val($("#ret_COD_BENEFICIARIO_" + index).val());
        $("#formulario #NOM_BENEFICIARIO").val($("#ret_NOM_BENEFICIARIO_" + index).val());
        $("#formulario #COD_RESPONSAVEL").val($("#ret_COD_RESPONSAVEL_" + index).val());
        $("#formulario #NOM_RESPONSAVEL").val($("#ret_NOM_RESPONSAVEL_" + index).val());
        $("#formulario #NUM_LOTE").val($("#ret_NUM_LOTE_" + index).val());
        $("#formulario #NUM_SEQUENCIA").val($("#ret_NUM_SEQUENCIA_" + index).val());
        $("#formulario #COD_ALESP").val($("#ret_COD_ALESP_" + index).val());
        $("#formulario #NUM_EMEDAPAL").val($("#ret_NUM_EMEDAPAL_" + index).val());
        $("#formulario #VAL_EMENDA").val($("#ret_VAL_EMENDA_" + index).val());
        $("#formulario #DAT_INI").val($("#ret_DAT_INI_" + index).val())
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }
</script>