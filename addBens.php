<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

// echo $cod_empresa;

$cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        //if (1 == 2) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
        $cod_tipobem = fnLimpaCampoZero($_REQUEST['COD_TIPOBEM']);
        $des_nomebem = fnLimpaCampo($_REQUEST['DES_NOMEBEM']);
        $qtd_areatot = fnLimpaCampoZero($_REQUEST['QTD_AREATOT']);
        $qtd_produti = fnLimpaCampoZero($_REQUEST['QTD_PRODUTI']);
        $val_informado = fnValorSql($_REQUEST['VAL_INFORMADO']);
        $num_cepbemu = fnLimpaCampo($_REQUEST['NUM_CEPBEMU']);
        $des_endereco = fnLimpaCampo($_REQUEST['DES_ENDERECO']);
        $num_endereco = fnLimpaCampo($_REQUEST['NUM_ENDERECO']);
        $des_complem = fnLimpaCampo($_REQUEST['DES_COMPLEM']);
        $des_bairroc = fnLimpaCampo($_REQUEST['DES_BAIRROC']);
        $cod_municipio = fnLimpaCampoZero($_REQUEST['COD_MUNICIPIO']);
        $des_roteiro = fnLimpaCampo($_REQUEST['DES_ROTEIRO']);
        $cod_estado = fnLimpaCampoZero($_REQUEST['COD_ESTADO']);
        $des_cartorio = fnLimpaCampo($_REQUEST['DES_CARTORIO']);
        $num_matricu = fnLimpaCampo($_REQUEST['NUM_MATRICU']);
        $num_folhama = fnLimpaCampo($_REQUEST['NUM_FOLHAMA']);
        $num_livroma = fnLimpaCampo($_REQUEST['NUM_LIVROMA']);
        $cod_municar = fnLimpaCampoZero($_REQUEST['COD_MUNICAR']);
        $cod_estadocar = fnLimpaCampoZero($_REQUEST['COD_ESTADOCAR']);
        $num_nirfima = fnLimpaCampo($_REQUEST['NUM_NIRFIMA']);
        $num_circmat = fnLimpaCampo($_REQUEST['NUM_CIRCMAT']);
        $num_carimov = fnLimpaCampo($_REQUEST['NUM_CARIMOV']);
        $log_carvali = isset($_REQUEST['LOG_CARVALI']) ? fnLimpaCampo($_REQUEST['LOG_CARVALI']) : 'N';
        $log_statusc = isset($_REQUEST['LOG_STATUSC']) ? fnLimpaCampo($_REQUEST['LOG_STATUSC']) : 'P';
        $num_escricao = fnLimpaCampo($_REQUEST['NUM_ESCRICAO']);
        $log_areaemb = isset($_REQUEST['LOG_AREAEMB']) ? fnLimpaCampo($_REQUEST['LOG_AREAEMB']) : 'N';
        $log_indigin = isset($_REQUEST['LOG_INDIGIN']) ? fnLimpaCampo($_REQUEST['LOG_INDIGIN']) : 'N';
        $log_conserv = isset($_REQUEST['LOG_CONSERV']) ? fnLimpaCampo($_REQUEST['LOG_CONSERV']) : 'N';
        $log_usosust = isset($_REQUEST['LOG_USOSUST']) ? fnLimpaCampo($_REQUEST['LOG_USOSUST']) : 'N';
        $log_quilomb = isset($_REQUEST['LOG_QUILOMB']) ? fnLimpaCampo($_REQUEST['LOG_QUILOMB']) : 'N';
        $log_amazonia = isset($_REQUEST['LOG_AMAZONIA']) ? fnLimpaCampo($_REQUEST['LOG_AMAZONIA']) : 'N';
        $log_frontei = isset($_REQUEST['LOG_FRONTEI']) ? fnLimpaCampo($_REQUEST['LOG_FRONTEI']) : 'N';
        $log_assento = isset($_REQUEST['LOG_ASSENTO']) ? fnLimpaCampo($_REQUEST['LOG_ASSENTO']) : 'N';
        $log_marinha = isset($_REQUEST['LOG_MARINHA']) ? fnLimpaCampo($_REQUEST['LOG_MARINHA']) : 'N';
        $dat_verifica = date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['DAT_VERIFICA'])));
        //print_r($_REQUEST);exit;
        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];
        $cod_usucada = $_SESSION[SYS_COD_USUARIO];

        $nom_usuarioSESSION = $_SESSION["SYS_NOM_USUARIO"];
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = $_GET['mod'];
        $COD_MODULO = fndecode($_GET['mod']);

        //fnEscreve($des_icones);

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {

                case 'CAD':

                $cod_cliente = fnLimpacampoZero(fnDecode($_GET['idC']));
                $sql = "INSERT INTO BENS_CLIENTE (
                    COD_EMPRESA,
                    COD_TIPO,
                    COD_CLIENTE,
                    DES_NOMEBEM,
                    VAL_INFORMADO,
                    VAL_EFETIVO,
                    COD_USUCADA
                    ) VALUES (
                    $cod_empresa,
                    $cod_tipobem,
                    $cod_cliente,
                    '$des_nomebem',
                    $val_informado,
                    0,
                    $cod_usucada
                )";

                    $arrayBens = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));


                    if (!$arrayBens) {

                        $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                    }

                    $sql = "SELECT max(COD_BEM) COD_BEM FROM BENS_CLIENTE 
                    WHERE COD_EMPRESA = $cod_empresa 
                    AND COD_USUCADA = $cod_usucada
                    LIMIT 1";
                    $qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ""), trim($sql)));

                    $ultimo_codbem = $qrCod[COD_BEM];

                    $sqlImovel = "INSERT INTO BENS_IMOVEIS (
                        COD_EMPRESA,
                        COD_CLIENTE,
                        COD_BEM,
                        COD_TIPO,
                        QTD_AREATOT,
                        QTD_PRODUTI,
                        NUM_CEPBEMU,
                        DES_ENDERECO,
                        NUM_ENDERECO,
                        DES_COMPLEM,
                        DES_BAIRROC,
                        COD_MUNICIPIO,
                        COD_ESTADO,
                        DES_ROTEIRO,
                        DES_CARTORIO,
                        NUM_MATRICU,
                        NUM_FOLHAMA,
                        NUM_LIVROMA,
                        COD_ESTADOCAR,
                        COD_MUNICAR,
                        NUM_NIRFIMA,
                        NUM_CIRCMAT,
                        NUM_CARIMOV,
                        LOG_CARVALI,
                        LOG_STATUSC,
                        NUM_ESCRICAO,
                        LOG_AREAEMB,
                        LOG_INDIGIN,
                        LOG_CONSERV,
                        LOG_USOSUST,
                        LOG_QUILOMB,
                        LOG_AMAZONIA,
                        LOG_FRONTEI,
                        LOG_ASSENTO,
                        LOG_MARINHA,
                        DAT_VERIFICA,
                        COD_USUCADA
                        ) VALUES (
                        $cod_empresa,
                        $cod_cliente,
                        $ultimo_codbem,
                        $cod_tipobem,
                        $qtd_areatot,
                        $qtd_produti,
                        '$num_cepbemu',
                        '$des_endereco',
                        '$num_endereco',
                        '$des_complem',
                        '$des_bairroc',
                        $cod_municipio,
                        $cod_estado,
                        '$des_roteiro',
                        '$des_cartorio',
                        '$num_matricu',
                        '$num_folhama',
                        '$num_livroma',
                        $cod_estadocar,
                        $cod_municar,
                        '$num_nirfima',
                        '$num_circmat',
                        '$num_carimov',
                        '$log_carvali',
                        '$log_statusc',
                        '$num_escricao',
                        '$log_areaemb',
                        '$log_indigin',
                        '$log_conserv',
                        '$log_usosust',
                        '$log_quilomb',
                        '$log_amazonia',
                        '$log_frontei',
                        '$log_assento',
                        '$log_marinha',
                        '$dat_verifica',
                        $cod_usucada
                    )";

                        $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sqlImovel)) or die(mysqli_error(connTemp($cod_empresa, '')));

                        if (!$arrayProc) {

                            $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlImovel, $nom_usuarioSESSION);
                        }


                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                        break;
                        case 'ALT':

                        $cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));
                        $sql = "UPDATE BENS_CLIENTE SET
                        COD_TIPO = $cod_tipobem,
                        DES_NOMEBEM = '$des_nomebem',
                        VAL_INFORMADO = $val_informado,
                        VAL_EFETIVO = 0,
                        COD_ALTERAC = $cod_usucada,
                        DAT_ALTERAC = NOW()
                        WHERE COD_BEM = $cod_bem AND COD_EMPRESA = $cod_empresa
                        ";

                        $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                        if (!$arrayProc) {

                            $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                        }

                        $sql2 = "SELECT * FROM BENS_IMOVEIS WHERE COD_BEM = $cod_bem AND COD_EMPRESA = $cod_empresa";
                        $arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2) or die(mysqli_error(connTemp($cod_empresa, '')));
                        $qrBuscaBemI = mysqli_fetch_assoc($arrayQuery2);

                        $cod_imv = $qrBuscaBemI['COD_IMOVEL'];

                        $sqlImovel2 = "UPDATE BENS_IMOVEIS SET
                        COD_TIPO = $cod_tipobem,
                        QTD_AREATOT = $qtd_areatot,
                        QTD_PRODUTI = $qtd_produti,
                        NUM_CEPBEMU = '$num_cepbemu',
                        DES_ENDERECO = '$des_endereco',
                        NUM_ENDERECO = '$num_endereco',
                        DES_COMPLEM = '$des_complem',
                        DES_BAIRROC = '$des_bairroc',
                        COD_MUNICIPIO = $cod_municipio,
                        COD_ESTADO = $cod_estado,
                        DES_ROTEIRO = '$des_roteiro',
                        DES_CARTORIO = '$des_cartorio',
                        NUM_MATRICU = '$num_matricu',
                        NUM_FOLHAMA = '$num_folhama',
                        NUM_LIVROMA = '$num_livroma',
                        COD_ESTADOCAR = $cod_estadocar,
                        COD_MUNICAR = $cod_municar,
                        NUM_NIRFIMA = '$num_nirfima',
                        NUM_CIRCMAT = '$num_circmat',
                        NUM_CARIMOV = '$num_carimov',
                        LOG_CARVALI = '$log_carvali',
                        LOG_STATUSC = '$log_statusc',
                        NUM_ESCRICAO = '$num_escricao',
                        LOG_AREAEMB = '$log_areaemb',
                        LOG_INDIGIN = '$log_indigin',
                        LOG_CONSERV = '$log_conserv',
                        LOG_USOSUST = '$log_usosust',
                        LOG_QUILOMB = '$log_quilomb',
                        LOG_AMAZONIA = '$log_amazonia',
                        LOG_FRONTEI = '$log_frontei',
                        LOG_ASSENTO = '$log_assento',
                        LOG_MARINHA = '$log_marinha',
                        DAT_VERIFICA = '$dat_verifica',
                        COD_ALTERAC = $cod_usucada,
                        DAT_ALTERAC = NOW()
                        WHERE COD_IMOVEL = $cod_imv AND COD_EMPRESA = $cod_empresa AND COD_BEM = $cod_bem
                        ";
                        $arrayMovel = mysqli_query(connTemp($cod_empresa, ""), trim($sqlImovel2)) or die(mysqli_error(connTemp($cod_empresa, '')));

                        if (!$arrayMovel) {

                            $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlImovel2, $nom_usuarioSESSION);
                        }

                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                        break;

                        case 'EXC':

                        $sql = "UPDATE BENS_CLIENTE SET
                        COD_EXCLUSA = $cod_usucada,
                        DAT_EXCLUSA = NOW()
                        WHERE COD_BEM = '" . $cod_bem . "'";

                        //fnEscreve($sql);

                        $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                        if (!$arrayProc) {

                            $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                        }


                        $sql2 = "SELECT * FROM BENS_IMOVEIS WHERE COD_BEM = $cod_bem AND COD_EMPRESA = $cod_empresa";
                        $arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2) or die(mysqli_error(connTemp($cod_empresa, '')));
                        $qrBuscaBemI = mysqli_fetch_assoc($arrayQuery2);

                        if($qrBuscaBemI){
                            $sql = "UPDATE BENS_IMOVEIS SET
                            COD_EXCLUSA = $cod_usucada,
                            DAT_EXCLUSA = NOW()
                            WHERE COD_BEM = $cod_bem ";

                            $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                            if (!$arrayProc) {

                                $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                            }

                            //fnEscreve($sql);
                            //fnTesteSql(connTemp($cod_empresa, ""), trim($sql));

                        }

                        $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                        break;
                    }
                    $msgTipo = 'alert-success';

                    if($popUp == 'true'){
                        ?>
                        <script>
                            parent.location.reload();
                        </script>
                        <?php   
                    }   
                }
            }
        }

//busca dados da url
        if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
            $cod_empresa = fnDecode($_GET['id']);
            $cod_cliente = fnDecode($_GET['idC']);

            $sql = "SELECT EMPRESAS.NOM_FANTASI,CATEGORIA.* FROM $connAdm->DB.EMPRESAS
            left JOIN CATEGORIA ON CATEGORIA.COD_EMPRESA=EMPRESAS.COD_EMPRESA
            where EMPRESAS.COD_EMPRESA = $cod_empresa ";

            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
            $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

            if (isset($qrBuscaEmpresa)) {
                $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
            }
        } else {
            $cod_empresa = 0;
        }

        //busca dados do ben
        if (is_numeric(fnLimpaCampoZero(fnDecode($_GET['idBem'])))) {

            $cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));
            $sql = "SELECT * FROM BENS_CLIENTE WHERE COD_BEM = $cod_bem";

            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
            $qrBuscaBem = mysqli_fetch_assoc($arrayQuery);

            $cod_tipobem = $qrBuscaBem['COD_TIPO'];
            $cod_cliente = $qrBuscaBem['COD_CLIENTE'];
            $des_nomebem = $qrBuscaBem['DES_NOMEBEM'];
            $val_informado = fnValor($qrBuscaBem['VAL_INFORMADO'],2);
            $val_efetivo = fnValor($qrBuscaBem['VAL_EFETIVO'],2);

            $sql2 = "SELECT * FROM BENS_IMOVEIS WHERE COD_BEM = $cod_bem AND COD_EMPRESA = $cod_empresa";
            $arrayQuery2 = mysqli_query(connTemp($cod_empresa, ''), $sql2) or die(mysqli_error(connTemp($cod_empresa, '')));
            $qrBuscaBemImoveis = mysqli_fetch_assoc($arrayQuery2);

            $qtd_areatot = $qrBuscaBemImoveis['QTD_AREATOT'];
            $qtd_produti = $qrBuscaBemImoveis['QTD_PRODUTI'];
            $num_cepbemu = $qrBuscaBemImoveis['NUM_CEPBEMU'];
            $des_endereco = $qrBuscaBemImoveis['DES_ENDERECO'];
            $num_endereco = $qrBuscaBemImoveis['NUM_ENDERECO'];
            $des_complem = $qrBuscaBemImoveis['DES_COMPLEM'];
            $des_bairroc = $qrBuscaBemImoveis['DES_BAIRROC'];
            $cod_municipio = $qrBuscaBemImoveis['COD_MUNICIPIO'];
            $cod_estado = $qrBuscaBemImoveis['COD_ESTADO'];
            $des_roteiro = $qrBuscaBemImoveis['DES_ROTEIRO'];
            $des_cartorio = $qrBuscaBemImoveis['DES_CARTORIO'];
            $num_matricu = $qrBuscaBemImoveis['NUM_MATRICU'];
            $num_folhama = $qrBuscaBemImoveis['NUM_FOLHAMA'];
            $num_livroma = $qrBuscaBemImoveis['NUM_LIVROMA'];
            $cod_estadocar = $qrBuscaBemImoveis['COD_ESTADOCAR'];
            $cod_municar = $qrBuscaBemImoveis['COD_MUNICAR'];
            $num_nirfima = $qrBuscaBemImoveis['NUM_NIRFIMA'];
            $num_circmat = $qrBuscaBemImoveis['NUM_CIRCMAT'];
            $num_carimov = $qrBuscaBemImoveis['NUM_CARIMOV'];
            $num_escricao = $qrBuscaBemImoveis['NUM_ESCRICAO'];
            $dat_verifica = $qrBuscaBemImoveis['DAT_VERIFICA'];
            $log_amazonia = $qrBuscaBemImoveis['LOG_AMAZONIA'];
            $log_carvali = $qrBuscaBemImoveis['LOG_CARVALI'];
            $log_statusc = $qrBuscaBemImoveis['LOG_STATUSC'];
            $log_areaemb = $qrBuscaBemImoveis['LOG_AREAEMB'];
            $log_indigin = $qrBuscaBemImoveis['LOG_INDIGIN'];
            $log_conserv = $qrBuscaBemImoveis['LOG_CONSERV'];
            $log_usosust = $qrBuscaBemImoveis['LOG_USOSUST'];
            $log_quilomb = $qrBuscaBemImoveis['LOG_QUILOMB'];
            $log_frontei = $qrBuscaBemImoveis['LOG_FRONTEI'];
            $log_assento = $qrBuscaBemImoveis['LOG_ASSENTO'];
            $log_marinha = $qrBuscaBemImoveis['LOG_MARINHA'];

            // fnEscreve2($cod_municipio);
        }

        ?>

        <?php if ($popUp != "true"){  ?>                            
            <div class="push30"></div> 
        <?php } ?>

        <div class="row">               

            <div class="col-md12 margin-bottom-30">
                <!-- Portlet -->
                <?php if ($popUp != "true"){  ?>                            
                    <div class="portlet portlet-bordered">
                    <?php } else { ?>
                        <div class="portlet" style="padding: 0 20px 20px 20px;" >
                        <?php } ?>

                        <?php if ($popUp != "true"){  ?>
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="glyphicon glyphicon-calendar"></i>
                                    <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                                </div>
                                <?php include "atalhosPortlet.php"; ?>
                            </div>
                        <?php } ?>

                        <div class="push10"></div>

                        <div class="portlet-body">

                            <?php if ($msgRetorno != '') { ?>
                                <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo $msgRetorno; ?>
                                </div>
                            <?php } ?>


                            <div class="push30"></div>


                            <div class="login-form">

                                <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                                    <?php 
                            // include "bensHeader.php"; 
                                    //fnEscreve($cod_bem);
                                    ?>

                                    <fieldset>
                                        <legend>Dados Gerais</legend>

                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label required">Nome do Bem</label>
                                                    <input type="text" class="form-control input-sm" name="DES_NOMEBEM" id="DES_NOMEBEM" maxlength="100" value="<?= $des_nomebem ?>" required>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label required">Tipo de Bem</label>
                                                    <select data-placeholder="Selecione um tipo de bem" name="COD_TIPOBEM" id="COD_TIPOBEM" class="chosen-select-deselect">
                                                        <option value=""></option>
                                                        <?php
                                                        $sql = "select COD_TIPOBEM, DES_TIPOBEM from tipo_bem order by DES_TIPOBEM ";
                                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                        while ($qrTipoBem = mysqli_fetch_assoc($arrayQuery)) {
                                                            $selected = ($qrTipoBem['COD_TIPOBEM'] == $cod_tipobem) ? 'selected' : '';
                                                            echo "<option value='" . $qrTipoBem['COD_TIPOBEM'] . "' $selected>" . $qrTipoBem['DES_TIPOBEM'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="QTD_AREATOT" class="control-label required">Qt. Área Total</label>
                                                    <input type="number" class="form-control input-sm" name="QTD_AREATOT" id="QTD_AREATOT" maxlength="100" value="<?= $qtd_areatot ?>" required>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-3" id="oculta_produti">
                                                <div class="form-group">
                                                    <label for="QTD_PRODUTI" class="control-label required">Qt. Área Produtiva</label>
                                                    <input type="number" class="form-control input-sm" name="QTD_PRODUTI" id="QTD_PRODUTI" maxlength="100" value="<?= $qtd_produti ?>">
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="inputName" class="control-label required">Valor do Bem</label>
                                                    <input type="text" class="form-control input-sm money" name="VAL_INFORMADO" id="VAL_INFORMADO" maxlength="100" value="<?= $val_informado ?>" required>
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>

                                    <div class="push10"></div>

                                    <div id="propriedade">

                                        <fieldset>
                                            <legend>Localização</legend>

                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Endereço</label>
                                                        <input type="text" class="form-control input-sm" name="DES_ENDERECO" id="DES_ENDERECO" maxlength="40" value="<?= $des_endereco ?>">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Número</label>
                                                        <input type="text" class="form-control input-sm" name="NUM_ENDERECO" id="NUM_ENDERECO" maxlength="10" value="<?= $num_endereco ?>">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Complemento</label>
                                                        <input type="text" class="form-control input-sm" name="DES_COMPLEM" id="DES_COMPLEM" maxlength="20" value="<?= $des_complem ?>">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Bairro</label>
                                                        <input type="text" class="form-control input-sm" name="DES_BAIRROC" id="DES_BAIRROC" maxlength="20" value="<?= $des_bairroc ?>">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">CEP</label>
                                                        <input type="text" class="form-control input-sm cep" name="NUM_CEPBEMU" id="NUM_CEPBEMU" maxlength="9" value="<?= $num_cepbemu ?>">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Estado</label>
                                                        <select data-placeholder="Selecione um estado" name="COD_ESTADO" id="COD_ESTADO" class="chosen-select-deselect" required>
                                                            <option value=""></option>
                                                            <?php
                                                            $sql = "SELECT COD_ESTADO, UF FROM ESTADO ORDER BY UF";
                                                            $arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                            while ($qrEstado = mysqli_fetch_assoc($arrayEstado)) {
                                                                $selected = ($qrEstado['COD_ESTADO'] == $cod_estado) ? 'selected' : '';
                                                                ?>
                                                                <option value="<?= $qrEstado['COD_ESTADO'] ?>" <?= $selected ?>><?= $qrEstado['UF'] ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="listaCidades">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Cidade</label>
                                                        <select data-placeholder="Selecione um estado" name="COD_MUNICIPIO" id="COD_MUNICIPIO" class="chosen-select-deselect" required>
                                                            <option value=""></option>
                                                        </select>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>


                                            </div>

                                        </fieldset>

                                        <fieldset>
                                            <legend>Cartório</legend>

                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Roteiro de Acesso ao Imóvel</label>
                                                        <input type="text" class="form-control input-sm" name="DES_ROTEIRO" id="DES_ROTEIRO" value="<?= $des_roteiro ?>">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Cartório</label>
                                                        <input type="text" class="form-control input-sm" name="DES_CARTORIO" id="DES_CARTORIO" value="<?= $des_cartorio ?>" required>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Nº Registro/Matrícula</label>
                                                        <input type="text" class="form-control input-sm" name="NUM_MATRICU" id="NUM_MATRICU" value="<?= $num_matricu ?>" required>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Folha</label>
                                                        <input type="text" class="form-control input-sm" name="NUM_FOLHAMA" id="NUM_FOLHAMA" value="<?= $num_folhama ?>" required>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Livro</label>
                                                        <input type="text" class="form-control input-sm" name="NUM_LIVROMA" id="NUM_LIVROMA" value="<?= $num_livroma ?>" required>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Estado do Cartório</label>
                                                        <select data-placeholder="Selecione um estado" name="COD_ESTADOCAR" id="COD_ESTADOCAR" class="chosen-select-deselect" required>
                                                            <option value=""></option>
                                                            <?php
                                                            $sql = "SELECT COD_ESTADO, UF FROM ESTADO ORDER BY UF";
                                                            $arrayEstado = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                                            while ($qrEstado = mysqli_fetch_assoc($arrayEstado)) {
                                                                $selected = ($qrEstado['COD_ESTADO'] == $cod_estadocar) ? 'selected' : '';
                                                                ?>
                                                                <option value="<?= $qrEstado['COD_ESTADO'] ?>" <?= $selected ?>><?= $qrEstado['UF'] ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4" id="listaCidadesCar">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Município do Cartório</label>
                                                        <select data-placeholder="Selecione um estado" name="COD_MUNICAR" id="COD_MUNICAR" class="chosen-select-deselect" required>
                                                            <option value=""></option>
                                                        </select>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row" id="div_rural1">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">NIRF - Nº Imóvel na Receita Federal</label>
                                                        <input type="text" class="form-control input-sm" name="NUM_NIRFIMA" id="NUM_NIRFIMA" value="<?= $num_nirfima ?>">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">CCIR - Cert. Cad. Imóvel Rural</label>
                                                        <input type="text" class="form-control input-sm" name="NUM_CIRCMAT" id="NUM_CIRCMAT" value="<?= $num_circmat ?>">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">CAR - Cadastro Ambiental Rural</label>
                                                        <input type="text" class="form-control input-sm" name="NUM_CARIMOV" id="NUM_CARIMOV" value="<?= $num_carimov ?>">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Cód. CAR Válido</label><br />
                                                        <label class="switch switch-small">
                                                            <input type="checkbox" name="LOG_CARVALI" id="LOG_CARVALI" class="switch" value="S" <?php echo ($log_carvali == 'S') ? 'checked' : ''; ?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row" id="div_rural2">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Status CAR</label>
                                                        <select data-placeholder="Selecione um estado" name="LOG_STATUSC" id="LOG_STATUSC" class="chosen-select-deselect">
                                                            <option value=""></option>
                                                            <option value="A" <?php echo ($log_statusc == 'A') ? 'selected' : ''; ?>>Ativo</option>
                                                            <option value="P" <?php echo ($log_statusc == 'P') ? 'selected' : ''; ?>>Pendente</option>
                                                            <option value="C" <?php echo ($log_statusc == 'C') ? 'selected' : ''; ?>>Cancelado</option>
                                                        </select>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Inscrição Produtor Rural</label>
                                                        <input type="text" class="form-control input-sm" name="NUM_ESCRICAO" id="NUM_ESCRICAO" value="<?= $num_escricao ?>">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Área Embargada</label><br />
                                                        <label class="switch switch-small">
                                                            <input type="checkbox" name="LOG_AREAEMB" id="LOG_AREAEMB" class="switch" value="S" <?php echo ($log_areaemb == 'S') ? 'checked' : ''; ?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Terras Indígenas</label><br />
                                                        <label class="switch switch-small">
                                                            <input type="checkbox" name="LOG_INDIGIN" id="LOG_INDIGIN" class="switch" value="S" <?php echo ($log_indigin == 'S') ? 'checked' : ''; ?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row" id="div_rural3">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Unid. Conserv. Prot. Integral</label><br />
                                                        <label class="switch switch-small">
                                                            <input type="checkbox" name="LOG_CONSERV" id="LOG_CONSERV" class="switch" value="S" <?php echo ($log_conserv == 'S') ? 'checked' : ''; ?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Unid. Conserv. Uso Sustentável</label><br />
                                                        <label class="switch switch-small">
                                                            <input type="checkbox" name="LOG_USOSUST" id="LOG_USOSUST" class="switch" value="S" <?php echo ($log_usosust == 'S') ? 'checked' : ''; ?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Comunidades Quilombolas</label><br />
                                                        <label class="switch switch-small">
                                                            <input type="checkbox" name="LOG_QUILOMB" id="LOG_QUILOMB" class="switch" value="S" <?php echo ($log_quilomb == 'S') ? 'checked' : ''; ?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Bioma Amazônia</label><br />
                                                        <label class="switch switch-small">
                                                            <input type="checkbox" name="LOG_AMAZONIA" id="LOG_AMAZONIA" class="switch" value="S" <?php echo ($log_amazonia == 'S') ? 'checked' : ''; ?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row" id="div_rural4">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Área de Fronteira</label><br />
                                                        <label class="switch switch-small">
                                                            <input type="checkbox" name="LOG_FRONTEI" id="LOG_FRONTEI" class="switch" value="S" <?php echo ($log_frontei == 'S') ? 'checked' : ''; ?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Assentamento Incra</label><br />
                                                        <label class="switch switch-small">
                                                            <input type="checkbox" name="LOG_ASSENTO" id="LOG_ASSENTO" class="switch" value="S" <?php echo ($log_assento == 'S') ? 'checked' : ''; ?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label">Área Marinha</label><br />
                                                        <label class="switch switch-small">
                                                            <input type="checkbox" name="LOG_MARINHA" id="LOG_MARINHA" class="switch" value="S" <?php echo ($log_marinha == 'S') ? 'checked' : ''; ?>/>
                                                            <span></span>
                                                        </label>
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputName" class="control-label required">Data Verificação das Restrições</label>
                                                        <input type="text" class="form-control input-sm data" name="DAT_VERIFICA" id="DAT_VERIFICA" data-minlength="10" data-minlength-error="O formato deve ser DD/MM/AAAA" maxlength="10" value="<?= fnDataFull($dat_verifica); ?>" >
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>

                                            </div>

                                        </fieldset>

                                    </div>

                                    <div class="push10"></div>

                                    <div class="push10"></div>
                                    <hr>
                                    <div class="form-group text-right col-lg-12">

                                        <button type="reset" class="btn btn-default" onclick="resetForm()"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                                        <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                                        <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                                        <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

                                    </div>

                                    <input type="hidden" name="opcao" id="opcao" value="">
                                    <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
                                    <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
                                    <input type="hidden" name="COD_BEM" id="COD_BEM" value="<?=$cod_bem?>">
                                    <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                                    <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

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

            <!-- modal -->
            <div class="modal fade" id="popModal" tabindex='-1'>
                <div class="modal-dialog">
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


            <link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/leaflet.css" integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ==" crossorigin="" />
            <script src="js/plugins/leaflet.markercluster-master/dist/leaflet-src.js" integrity="sha512-WXoSHqw/t26DszhdMhOXOkI7qCiv5QWXhH9R7CgvgZMHz1ImlkVQ3uNsiQKu5wwbbxtPzFXd1hK4tzno2VqhpA==" crossorigin=""></script>

            <link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/MarkerCluster.css" />
            <link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/MarkerCluster.Default.css" />
            <script src="js/plugins/leaflet.markercluster-master/dist/leaflet.markercluster-src.js"></script>


            <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
            <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

            <script type="text/javascript">
                $(document).ready(function() {

                    verificarTipoBem();

                    $('#COD_TIPOBEM').change(function(){
                        verificarTipoBem();
                    });

                    $(".addBox").click(function(e) {
                        if ($(this).attr("disabled")) {
                            e.stopPropagation();
                        }
                    });

                    <?php         

                    if (!$cod_bem){
                        $cod_estado = 0;
                        $cod_municipio = 0;
                        $cod_estadocar = 0;
                        $cod_municar = 0;
                    }
                    ?>

                    $("#COD_ESTADO").change(function() {
                        cod_estado = $(this).val();
                        carregaComboCidades(cod_estado);
                    });
                    $("#COD_ESTADOCAR").change(function() {
                        cod_estado = $(this).val();
                        carregaComboCidadesCar(cod_estado);
                    });

                    carregaComboCidades("<?=$cod_estado?>", "<?=$cod_municipio?>");
                    carregaComboCidadesCar("<?=$cod_estadocar?>", "<?=$cod_municar?>");

                });

                function verificarTipoBem() {
                    var selectedOption = $('#COD_TIPOBEM').val();
                    if(selectedOption != 3) {

                        if(selectedOption == 6){
                            $('label[for="QTD_AREATOT"]').text('Qt. Área Total HA');
                            $('label[for="QTD_PRODUTI"]').text('Qt. Área Produtiva HA');
                            $('#oculta_produti').show();
                        }
                        $('#div_rural1').show();
                        $('#div_rural2').show();
                        $('#div_rural3').show();
                        $('#div_rural4').show();
                        $('#QTD_PRODUTI').prop('required', true);
                        $('#LOG_STATUSC').prop('required', true);
                    } else {
                        $('label[for="QTD_AREATOT"]').text('Qt. Área Total M²');
                        $('#oculta_produti').hide();
                        $('#div_rural1').hide();
                        $('#div_rural2').hide();
                        $('#div_rural3').hide();
                        $('#div_rural4').hide();
                        $('#LOG_STATUSC').prop('required', false);
                        $('#LOG_STATUSC').val('P');
                        $('#QTD_PRODUTI').prop('required', false);
                        $('#QTD_PRODUTI').val("");
                        $('#oculta_produti').val("0")
                    }
                }

                function resetForm() {
                    window.history.pushState({}, '', '/action.do?mod=<?= $_GET["mod"] ?>&id=<?= $_GET["id"] ?>&idC=<?= $_GET["idC"] ?>');
                }

                function retornaForm(index, idBem) {
                    window.history.pushState({}, "", "/action.do?mod=<?= $_GET["mod"] ?>&id=<?= $_GET["id"] ?>&idC=<?= $_GET["idC"] ?>&idBem=" + idBem);

                    $("#formulario #COD_BEM").val($("#ret_COD_BEM_" + index).val());
                    $("#formulario #COD_BEM_ENCODE").val(idBem);
                    $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val());
                    $("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_" + index).val());
                    $("#formulario #NOM_CLIENTE").val($("#ret_NOM_CLIENTE_" + index).val());
                    $("#formulario #COD_TIPOBEM").val($("#ret_COD_TIPOBEM_" + index).val()).trigger("chosen:updated");;
                    $("#formulario #DES_NOMEBEM").val($("#ret_DES_NOMEBEM_" + index).val());
                    $("#formulario #QTD_AREATOT").val($("#ret_QTD_AREATOT_" + index).val());
                    $("#formulario #QTD_PRODUTI").val($("#ret_QTD_PRODUTI_" + index).val());
                    $("#formulario #VAL_INFORMADO").val(Number($("#ret_VAL_INFORMADO_" + index).val()).toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                    $("#formulario #NUM_CEPBEMU").val($("#ret_NUM_CEPBEMU_" + index).val());
                    $("#formulario #DES_ENDERECO").val($("#ret_DES_ENDERECO_" + index).val());
                    $("#formulario #NUM_ENDERECO").val($("#ret_NUM_ENDERECO_" + index).val());
                    $("#formulario #DES_COMPLEM").val($("#ret_DES_COMPLEM_" + index).val());
                    $("#formulario #DES_BAIRROC").val($("#ret_DES_BAIRROC_" + index).val());

                    $("#formulario #COD_ESTADO").val($("#ret_COD_ESTADO_" + index).val()).trigger("chosen:updated");
                    cod_estado = $("#formulario #COD_ESTADO").val();
                    carregaComboCidades(cod_estado, $("#ret_COD_MUNICIPIO_" + index).val());

                    $("#formulario #DES_ROTEIRO").val($("#ret_DES_ROTEIRO_" + index).val());
                    $("#formulario #DES_CARTORIO").val($("#ret_DES_CARTORIO_" + index).val());
                    $("#formulario #NUM_MATRICU").val($("#ret_NUM_MATRICU_" + index).val());
                    $("#formulario #NUM_FOLHAMA").val($("#ret_NUM_FOLHAMA_" + index).val());
                    $("#formulario #NUM_LIVROMA").val($("#ret_NUM_LIVROMA_" + index).val());

                    $("#formulario #COD_ESTADOCAR").val($("#ret_COD_ESTADOCAR_" + index).val()).trigger("chosen:updated");;
                    cod_estado = $("#formulario #COD_ESTADOCAR").val();
                    carregaComboCidadesCar(cod_estado, $("#ret_COD_MUNICAR_" + index).val());

                    $("#formulario #NUM_NIRFIMA").val($("#ret_NUM_NIRFIMA_" + index).val());
                    $("#formulario #NUM_CIRCMAT").val($("#ret_NUM_CIRCMAT_" + index).val());
                    $("#formulario #NUM_CARIMOV").val($("#ret_NUM_CARIMOV_" + index).val());
                    $("#formulario #NUM_ESCRICAO").val($("#ret_NUM_ESCRICAO_" + index).val());
                    $("#formulario #LOG_STATUSC").val($("#ret_LOG_STATUSC_" + index).val()).trigger("chosen:updated");;

                    $("#formulario #LOG_CARVALI").prop("checked", $("#ret_LOG_CARVALI_" + index).val() == "S");
                    $("#formulario #LOG_AREAEMB").prop("checked", $("#ret_LOG_AREAEMB_" + index).val() == "S");
                    $("#formulario #LOG_INDIGIN").prop("checked", $("#ret_LOG_INDIGIN_" + index).val() == "S");
                    $("#formulario #LOG_CONSERV").prop("checked", $("#ret_LOG_CONSERV_" + index).val() == "S");
                    $("#formulario #LOG_USOSUST").prop("checked", $("#ret_LOG_USOSUST_" + index).val() == "S");
                    $("#formulario #LOG_QUILOMB").prop("checked", $("#ret_LOG_QUILOMB_" + index).val() == "S");
                    $("#formulario #LOG_AMAZONIA").prop("checked", $("#ret_LOG_AMAZONIA_" + index).val() == "S");
                    $("#formulario #LOG_FRONTEI").prop("checked", $("#ret_LOG_FRONTEI_" + index).val() == "S");
                    $("#formulario #LOG_ASSENTO").prop("checked", $("#ret_LOG_ASSENTO_" + index).val() == "S");
                    $("#formulario #LOG_MARINHA").prop("checked", $("#ret_LOG_MARINHA_" + index).val() == "S");

                    $("#formulario #DAT_VERIFICA").val($("#ret_DAT_VERIFICA_" + index).val().split('-').reverse().join('/'));

                    $('#formulario').validator('validate');
                    $("#formulario #hHabilitado").val('S');

                }

                function carregaComboCidades(cod_estado, cod_municipio) {
                    if (cod_municipio == undefined) {
                        cod_municipio = 0;
                    }

                    $.ajax({
                        method: 'POST',
                        url: 'ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>',
                        data: {
                            COD_ESTADO: cod_estado
                        },
                        beforeSend: function() {
                            $('#listaCidades').html('<div class="loading" style="width: 100%;"></div>');
                        },
                        success: function(data) {
                            $("#listaCidades").html(data);
                            $("#formulario #COD_MUNICIPIO").val(cod_municipio).trigger("chosen:updated");
                        }
                    });
                }

                function carregaComboCidadesCar(cod_estado, cod_municipio) {
                    if (cod_municipio == undefined) {
                        cod_municipio = 0;
                    }

                    $.ajax({
                        method: 'POST',
                        url: 'ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>',
                        data: {
                            COD_ESTADO: cod_estado
                        },
                        beforeSend: function() {
                            $('#listaCidadesCar').html('<div class="loading" style="width: 100%;"></div>');
                        },
                        success: function(data) {
                            data = data.replaceAll("COD_MUNICIPIO", "COD_MUNICAR")
                            $("#listaCidadesCar").html(data);
                            $("#formulario #COD_MUNICAR").val(cod_municipio).trigger("chosen:updated");
                        }
                    });
                }
            </script>