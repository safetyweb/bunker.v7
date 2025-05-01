<?php

echo fnDebug('true');

$hashLocal = mt_rand();

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
        $nom_bemusos = fnLimpaCampo($_REQUEST['NOM_BEMUSOS']);
        $qtd_areatot = fnLimpaCampoZero($_REQUEST['QTD_AREATOT']);
        $qtd_produti = fnLimpaCampoZero($_REQUEST['QTD_PRODUTI']);
        $val_atualbe = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['VAL_ATUALBE']));
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

        //fnEscreve($des_icones);

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    $sql = "INSERT INTO BENS (
                                COD_CLIENTE,
                                COD_TIPOBEM,
                                NOM_BEMUSOS,
                                QTD_AREATOT,
                                QTD_PRODUTI,
                                VAL_ATUALBE,
                                NUM_CEPBEMU,
                                DES_ENDERECO,
                                NUM_ENDERECO,
                                DES_COMPLEM,
                                DES_BAIRROC,
                                COD_MUNICIPIO,
                                COD_ESTADO,
                                DES_ROTEIRO,
                                DES_CARTORIO,
                                NUM_MATRICU	,
                                NUM_FOLHAMA	,
                                NUM_LIVROMA	,
                                COD_MUNICAR,
                                COD_ESTADOCAR,
                                NUM_NIRFIMA,
                                NUM_CIRCMAT,
                                NUM_CARIMOV,
                                LOG_CARVALI,
                                LOG_STATUSC,
                                NUM_ESCRICAO,
                                LOG_AREAEMB,
                                LOG_INDIGIN,
                                LOG_CONSERV	,
                                LOG_USOSUST,
                                LOG_QUILOMB,
                                LOG_AMAZONIA,
                                LOG_FRONTEI,
                                LOG_ASSENTO,
                                LOG_MARINHA,
                                DAT_VERIFICA,
                                DAT_CADASTR,
                                COD_USUCADA,
                                COD_EMPRESA
                            ) VALUES (
                                '" . $cod_cliente . "', 
                                '" . $cod_tipobem . "', 
                                '" . $nom_bemusos . "', 
                                '" . $qtd_areatot . "', 
                                '" . $qtd_produti . "', 
                                '" . $val_atualbe . "', 
                                '" . $num_cepbemu . "', 
                                '" . $des_endereco . "', 
                                '" . $num_endereco . "', 
                                '" . $des_complem . "', 
                                '" . $des_bairroc . "', 
                                '" . $cod_municipio . "', 
                                '" . $cod_estado . "', 
                                '" . $des_roteiro . "', 
                                '" . $des_cartorio . "', 
                                '" . $num_matricu . "', 
                                '" . $num_folhama . "', 
                                '" . $num_livroma . "', 
                                '" . $cod_municar . "', 
                                '" . $cod_estadocar . "', 
                                '" . $num_nirfima . "', 
                                '" . $num_circmat . "', 
                                '" . $num_carimov . "', 
                                '" . $log_carvali . "', 
                                '" . $log_statusc . "', 
                                '" . $num_escricao . "', 
                                '" . $log_areaemb . "', 
                                '" . $log_indigin . "', 
                                '" . $log_conserv . "', 
                                '" . $log_usosust . "', 
                                '" . $log_quilomb . "', 
                                '" . $log_amazonia . "', 
                                '" . $log_frontei . "', 
                                '" . $log_assento . "', 
                                '" . $log_marinha . "', 
                                '" . $dat_verifica . "', 
                                NOW(),
                                '" . $_SESSION["SYS_COD_USUARIO"] . "',
                                '" . $cod_empresa . "'
                            )";

                    //echo $sql;exit;
                    mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;
                case 'ALT':
                    $sql = "UPDATE BENS SET
                                COD_CLIENTE = '" . $cod_cliente . "',
                                COD_TIPOBEM = '" . $cod_tipobem . "',
                                NOM_BEMUSOS = '" . $nom_bemusos . "',
                                QTD_AREATOT = '" . $qtd_areatot . "',
                                QTD_PRODUTI = '" . $qtd_produti . "',
                                VAL_ATUALBE = '" . $val_atualbe . "',
                                NUM_CEPBEMU = '" . $num_cepbemu . "',
                                DES_ENDERECO = '" . $des_endereco . "',
                                NUM_ENDERECO = '" . $num_endereco . "',
                                DES_COMPLEM = '" . $des_complem . "',
                                DES_BAIRROC = '" . $des_bairroc . "',
                                COD_MUNICIPIO = '" . $cod_municipio . "',
                                COD_ESTADO = '" . $cod_estado . "',
                                DES_ROTEIRO = '" . $des_roteiro . "',
                                DES_CARTORIO = '" . $des_cartorio . "',
                                NUM_MATRICU	 = '" . $num_matricu    . "',
                                NUM_FOLHAMA	 = '" . $num_folhama    . "',
                                NUM_LIVROMA	 = '" . $num_livroma    . "',
                                COD_MUNICAR = '" . $cod_municar . "',
                                COD_ESTADOCAR = '" . $cod_estadocar . "',
                                NUM_NIRFIMA = '" . $num_nirfima . "',
                                NUM_CIRCMAT = '" . $num_circmat . "',
                                NUM_CARIMOV = '" . $num_carimov . "',
                                LOG_CARVALI = '" . $log_carvali . "',
                                LOG_STATUSC = '" . $log_statusc . "',
                                NUM_ESCRICAO = '" . $num_escricao . "',
                                LOG_AREAEMB = '" . $log_areaemb . "',
                                LOG_INDIGIN = '" . $log_indigin . "',
                                LOG_CONSERV	 = '" . $log_conserv    . "',
                                LOG_USOSUST = '" . $log_usosust . "',
                                LOG_QUILOMB = '" . $log_quilomb . "',
                                LOG_AMAZONIA = '" . $log_amazonia . "',
                                LOG_FRONTEI = '" . $log_frontei . "',
                                LOG_ASSENTO = '" . $log_assento . "',
                                LOG_MARINHA = '" . $log_marinha . "',
                                DAT_VERIFICA = '" . $dat_verifica . "',
								COD_ALTERAC = '" . $_SESSION["SYS_COD_USUARIO"] . "',
								DAT_ALTERAC = NOW()
							WHERE COD_BEM = '" . $cod_bem . "'";

                    //echo $sql;exit;
                    mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;
                case 'EXC':
                    $sql = "UPDATE BENS SET
								COD_EXCLUSA = '" . $_SESSION["SYS_COD_USUARIO"] . "',
								DAT_EXCLUSA = NOW()
							WHERE COD_BEM = '" . $cod_bem . "'";

                    mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
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
    $cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));

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

// echo $cod_empresa;

?>

<div class="push30"></div>

<div class="row">
    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fal fa-terminal"></i>
                    <span class="text-primary"><?php echo $NomePg; ?>
                </div>
            </div>


            <?php
            $abaBens = 1920;
            include "abasBens.php";
            ?>
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

                        <?php include "bensHeader.php"; ?>

                        <fieldset>
                            <legend>Dados do Bem</legend>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Tipo de Bem</label>
                                        <select data-placeholder="Selecione um tipo de bem" name="COD_TIPOBEM" id="COD_TIPOBEM" class="chosen-select-deselect">
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $sql = "select COD_TIPOBEM, DES_TIPOBEM from tipo_bem order by DES_TIPOBEM ";
                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                            while ($qrTipoBem = mysqli_fetch_assoc($arrayQuery)) {
                                                echo "
														<option value='" . $qrTipoBem['COD_TIPOBEM'] . "'>" . $qrTipoBem['DES_TIPOBEM'] . "</option> 
													";
                                            }
                                            ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Nome da Propriedade</label>
                                        <input type="text" class="form-control input-sm" name="NOM_BEMUSOS" id="NOM_BEMUSOS" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Qt. Área Total</label>
                                        <input type="number" class="form-control input-sm" name="QTD_AREATOT" id="QTD_AREATOT" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Qt. Área Produtiva</label>
                                        <input type="number" class="form-control input-sm" name="QTD_PRODUTI" id="QTD_PRODUTI" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Valor do Bem</label>
                                        <input type="text" class="form-control input-sm money" name="VAL_ATUALBE" id="VAL_ATUALBE" maxlength="100">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Localização</legend>

                            <div class="row">


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Endereço</label>
                                        <input type="text" class="form-control input-sm" name="DES_ENDERECO" id="DES_ENDERECO" maxlength="40">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Número</label>
                                        <input type="text" class="form-control input-sm" name="NUM_ENDERECO" id="NUM_ENDERECO" maxlength="10">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Complemento</label>
                                        <input type="text" class="form-control input-sm" name="DES_COMPLEM" id="DES_COMPLEM" maxlength="20">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Bairro</label>
                                        <input type="text" class="form-control input-sm" name="DES_BAIRROC" id="DES_BAIRROC" maxlength="20">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CEP</label>
                                        <input type="text" class="form-control input-sm cep" name="NUM_CEPBEMU" id="NUM_CEPBEMU" maxlength="9">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
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
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-4" id="listaCidades">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cidade</label>
                                        <select data-placeholder="Selecione um estado" name="COD_MUNICIPIO" id="COD_MUNICIPIO" class="chosen-select-deselect">
                                            <option value=""></option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>


                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <fieldset>
                            <legend>Cartório</legend>

                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Roteiro de Acesso ao Imóvel</label>
                                        <input type="text" class="form-control input-sm" name="DES_ROTEIRO" id="DES_ROTEIRO">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cartório</label>
                                        <input type="text" class="form-control input-sm" name="DES_CARTORIO" id="DES_CARTORIO">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Nº Registro/Matrícula</label>
                                        <input type="text" class="form-control input-sm" name="NUM_MATRICU" id="NUM_MATRICU">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Folha</label>
                                        <input type="text" class="form-control input-sm" name="NUM_FOLHAMA" id="NUM_FOLHAMA">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Livro</label>
                                        <input type="text" class="form-control input-sm" name="NUM_LIVROMA" id="NUM_LIVROMA">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Estado do Cartório</label>
                                        <select data-placeholder="Selecione um estado" name="COD_ESTADOCAR" id="COD_ESTADOCAR" class="chosen-select-deselect">
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
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-4" id="listaCidadesCar">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Município do Cartório</label>
                                        <select data-placeholder="Selecione um estado" name="COD_MUNICAR" id="COD_MUNICAR" class="chosen-select-deselect">
                                            <option value=""></option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">NIRF - Nº Imóvel na Receita Federal</label>
                                        <input type="text" class="form-control input-sm" name="NUM_NIRFIMA" id="NUM_NIRFIMA">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CCIR - Cert. Cad. Imóvel Rural</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CIRCMAT" id="NUM_CIRCMAT">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CAR - Cadastro Ambiental Rural</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CARIMOV" id="NUM_CARIMOV">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cód. CAR Válido</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CARVALI" id="LOG_CARVALI" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Status CAR</label>
                                        <select data-placeholder="Selecione um estado" name="LOG_STATUSC" id="LOG_STATUSC" class="chosen-select-deselect" required>
                                            <option value=""></option>
                                            <option value="A">Ativo</option>
                                            <option value="P">Pendente</option>
                                            <option value="C">Cancelado</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Inscrição Produtor Rural</label>
                                        <input type="text" class="form-control input-sm" name="NUM_ESCRICAO" id="NUM_ESCRICAO">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Área Embargada</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_AREAEMB" id="LOG_AREAEMB" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Terras Indígenas</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_INDIGIN" id="LOG_INDIGIN" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Unid. Conserv. Prot. Integral</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CONSERV" id="LOG_CONSERV" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Unid. Conserv. Uso Sustentável</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_USOSUST" id="LOG_USOSUST" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Comunidades Quilombolas</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_QUILOMB" id="LOG_QUILOMB" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Bioma Amazônia</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_AMAZONIA" id="LOG_AMAZONIA" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Área de Fronteira</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_FRONTEI" id="LOG_FRONTEI" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Assentamento Incra</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_ASSENTO" id="LOG_ASSENTO" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Área Marinha</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_MARINHA" id="LOG_MARINHA" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Verificação das Restrições</label>
                                        <input type="text" class="form-control input-sm data" name="DAT_VERIFICA" id="DAT_VERIFICA" data-minlength="10" data-minlength-error="O formato deve ser DD/MM/AAAA" maxlength="10" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <button type="reset" class="btn btn-default" onclick="resetForm()"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

                        </div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

                        <div class="push5"></div>

                    </form>

                    <div class="push50"></div>

                    <div class="col-lg-12">

                        <div class="no-more-tables">

                            <form name="formLista">

                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50"></th>
                                            <th>Código</th>
                                            <th>Cliente</th>
                                            <th>Propriedade</th>
                                            <th>Tipo de Bem</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cod_cliente = fnLimpacampoZero(fnDecode($_GET["idC"]));

                                        $sql = "SELECT BENS.*,TIPO_BEM.DES_TIPOBEM,CLIENTES.NOM_CLIENTE FROM BENS
                                                LEFT JOIN CLIENTES ON CLIENTES.COD_CLIENTE=BENS.COD_CLIENTE
                                                LEFT JOIN TIPO_BEM ON TIPO_BEM.COD_TIPOBEM=BENS.COD_TIPOBEM
                                                WHERE 1=1
                                                    AND CLIENTES.COD_CLIENTE = $cod_cliente
                                                    AND BENS.COD_EMPRESA = $cod_empresa
                                                    AND BENS.COD_EXCLUSA IS NULL
                                                ORDER BY BENS.NOM_BEMUSOS";
                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));

                                        //$count = 0;
                                        while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                            $count = $qrBusca['COD_BEM'];
                                            echo "
												<tr>
													<td class='text-center'>
														<input type='radio' name='radio1' onclick='retornaForm(" . $count . ",\"" . fnEncode($qrBusca['COD_BEM']) . "\")'>
													</td>
													<td style='text-align:right'>" . $qrBusca['COD_BEM'] . "</td>
													<td>" . $qrBusca['NOM_CLIENTE'] . "</td>
													<td>" . $qrBusca['NOM_BEMUSOS'] . "</td>
													<td>" . $qrBusca['DES_TIPOBEM'] . "</td>
												</tr>
												<input type='hidden' id='ret_COD_BEM_" . $count . "' value='" . $qrBusca['COD_BEM'] . "'>
												<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBusca['COD_EMPRESA'] . "'>
												<input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . $qrBusca['COD_CLIENTE'] . "'>
												<input type='hidden' id='ret_NOM_CLIENTE_" . $count . "' value='" . $qrBusca['NOM_CLIENTE'] . "'>
												<input type='hidden' id='ret_COD_TIPOBEM_" . $count . "' value='" . $qrBusca['COD_TIPOBEM'] . "'>
												<input type='hidden' id='ret_NOM_BEMUSOS_" . $count . "' value='" . $qrBusca['NOM_BEMUSOS'] . "'>
												<input type='hidden' id='ret_QTD_AREATOT_" . $count . "' value='" . $qrBusca['QTD_AREATOT'] . "'>
												<input type='hidden' id='ret_QTD_PRODUTI_" . $count . "' value='" . $qrBusca['QTD_PRODUTI'] . "'>
												<input type='hidden' id='ret_VAL_ATUALBE_" . $count . "' value='" . $qrBusca['VAL_ATUALBE'] . "'>
												<input type='hidden' id='ret_NUM_CEPBEMU_" . $count . "' value='" . $qrBusca['NUM_CEPBEMU'] . "'>
												<input type='hidden' id='ret_DES_ENDERECO_" . $count . "' value='" . $qrBusca['DES_ENDERECO'] . "'>
												<input type='hidden' id='ret_NUM_ENDERECO_" . $count . "' value='" . $qrBusca['NUM_ENDERECO'] . "'>
												<input type='hidden' id='ret_DES_COMPLEM_" . $count . "' value='" . $qrBusca['DES_COMPLEM'] . "'>
												<input type='hidden' id='ret_DES_BAIRROC_" . $count . "' value='" . $qrBusca['DES_BAIRROC'] . "'>
												<input type='hidden' id='ret_COD_MUNICIPIO_" . $count . "' value='" . $qrBusca['COD_MUNICIPIO'] . "'>
												<input type='hidden' id='ret_COD_ESTADO_" . $count . "' value='" . $qrBusca['COD_ESTADO'] . "'>
												<input type='hidden' id='ret_DES_ROTEIRO_" . $count . "' value='" . $qrBusca['DES_ROTEIRO'] . "'>
												<input type='hidden' id='ret_DES_CARTORIO_" . $count . "' value='" . $qrBusca['DES_CARTORIO'] . "'>
												<input type='hidden' id='ret_NUM_MATRICU_" . $count . "' value='" . $qrBusca['NUM_MATRICU'] . "'>
												<input type='hidden' id='ret_NUM_FOLHAMA_" . $count . "' value='" . $qrBusca['NUM_FOLHAMA'] . "'>
												<input type='hidden' id='ret_NUM_LIVROMA_" . $count . "' value='" . $qrBusca['NUM_LIVROMA'] . "'>
												<input type='hidden' id='ret_COD_ESTADOCAR_" . $count . "' value='" . $qrBusca['COD_ESTADOCAR'] . "'>
												<input type='hidden' id='ret_COD_MUNICAR_" . $count . "' value='" . $qrBusca['COD_MUNICAR'] . "'>
												<input type='hidden' id='ret_NUM_NIRFIMA_" . $count . "' value='" . $qrBusca['NUM_NIRFIMA'] . "'>
												<input type='hidden' id='ret_NUM_CIRCMAT_" . $count . "' value='" . $qrBusca['NUM_CIRCMAT'] . "'>
												<input type='hidden' id='ret_NUM_CARIMOV_" . $count . "' value='" . $qrBusca['NUM_CARIMOV'] . "'>
												<input type='hidden' id='ret_LOG_CARVALI_" . $count . "' value='" . $qrBusca['LOG_CARVALI'] . "'>
												<input type='hidden' id='ret_LOG_STATUSC_" . $count . "' value='" . $qrBusca['LOG_STATUSC'] . "'>
												<input type='hidden' id='ret_NUM_ESCRICAO_" . $count . "' value='" . $qrBusca['NUM_ESCRICAO'] . "'>
												<input type='hidden' id='ret_LOG_AREAEMB_" . $count . "' value='" . $qrBusca['LOG_AREAEMB'] . "'>
												<input type='hidden' id='ret_LOG_INDIGIN_" . $count . "' value='" . $qrBusca['LOG_INDIGIN'] . "'>
												<input type='hidden' id='ret_LOG_CONSERV_" . $count . "' value='" . $qrBusca['LOG_CONSERV'] . "'>
												<input type='hidden' id='ret_LOG_USOSUST_" . $count . "' value='" . $qrBusca['LOG_USOSUST'] . "'>
												<input type='hidden' id='ret_LOG_QUILOMB_" . $count . "' value='" . $qrBusca['LOG_QUILOMB'] . "'>
												<input type='hidden' id='ret_LOG_AMAZONIA_" . $count . "' value='" . $qrBusca['LOG_AMAZONIA'] . "'>
												<input type='hidden' id='ret_LOG_FRONTEI_" . $count . "' value='" . $qrBusca['LOG_FRONTEI'] . "'>
												<input type='hidden' id='ret_LOG_ASSENTO_" . $count . "' value='" . $qrBusca['LOG_ASSENTO'] . "'>
												<input type='hidden' id='ret_LOG_MARINHA_" . $count . "' value='" . $qrBusca['LOG_MARINHA'] . "'>
												<input type='hidden' id='ret_DAT_VERIFICA_" . $count . "' value='" . $qrBusca['DAT_VERIFICA'] . "'>
											";
                                        }
                                        ?>

                                    </tbody>
                                </table>

                            </form>

                        </div>

                    </div>

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
        $(".addBox").click(function(e) {
            if ($(this).attr("disabled")) {
                e.stopPropagation();
            }
        });

        carregaComboCidades(0);
        carregaComboCidadesCar(0);

        $("#COD_ESTADO").change(function() {
            cod_estado = $(this).val();
            carregaComboCidades(cod_estado);
        });
        $("#COD_ESTADOCAR").change(function() {
            cod_estado = $(this).val();
            carregaComboCidadesCar(cod_estado);
        });

        <?php
        if ($cod_bem > 0) {
            echo "retornaForm($cod_bem,'" . fnEncode($cod_bem) . "');";
        } else {
            echo "resetForm();";
        }
        ?>
    });

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
        $("#formulario #NOM_BEMUSOS").val($("#ret_NOM_BEMUSOS_" + index).val());
        $("#formulario #QTD_AREATOT").val($("#ret_QTD_AREATOT_" + index).val());
        $("#formulario #QTD_PRODUTI").val($("#ret_QTD_PRODUTI_" + index).val());
        $("#formulario #VAL_ATUALBE").val(Number($("#ret_VAL_ATUALBE_" + index).val()).toLocaleString('pt-BR', {
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