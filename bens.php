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
        $cod_tipobem = fnLimpaCampoZero($_REQUEST['COD_TIPO']);
        $des_nomebem = fnLimpaCampo($_REQUEST['DES_NOMEBEM']);
        $qtd_areatot = fnLimpaCampoZero($_REQUEST['QTD_AREATOT']);
        $qtd_produti = fnLimpaCampoZero($_REQUEST['QTD_PRODUTI']);
        //$val_atualbe = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['VAL_ATUALBE']));
        $val_informado = fnLimpaCampoZero(fnValor($qrBuscaBem['VAL_INFORMADO'], 2));
        $num_cepbemu = fnLimpaCampo($_REQUEST['NUM_CEPBEMU']);
        $des_endereco = fnLimpaCampo($_REQUEST['DES_ENDERECO']);
        $num_endereco = fnLimpaCampo($_REQUEST['NUM_ENDERECO']);
        $des_complem = fnLimpaCampo($_REQUEST['DES_COMPLEM']);
        $des_bairroc = fnLimpaCampo($_REQUEST['DES_BAIRROC']);
        $cod_municipio = fnLimpaCampoZero($_REQUEST['COD_MUNICIPIO']);
        $des_roteiro = fnLimpaCampo($_REQUEST['DES_ROTEIRO']);
        $cod_estado = fnLimpaCampoZero($_REQUEST['COD_ESTADO']);
        $val_efetivo = fnLimpaCampoZero($_REQUEST['VAL_EFETIVO']);
        $des_cartorio = fnLimpaCampo($_REQUEST['DES_CARTORIO']);
        $num_matricu = fnLimpaCampo($_REQUEST['NUM_MATRICU']);
        $num_folhama = fnLimpaCampo($_REQUEST['NUM_FOLHAMA']);
        $num_livroma = fnLimpaCampo($_REQUEST['NUM_LIVROMA']);
        $cod_municar = fnLimpaCampoZero($_REQUEST['COD_MUNICAR']);
        $cod_estadocar = fnLimpaCampoZero($_REQUEST['COD_ESTADOCAR']);
        $val_informado = fnLimpaCampoZero($_REQUEST['VAL_INFORMADO']);
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
    }
}

//busca dados da url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);

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
    $sql = "SELECT BENS_CLIENTE.*,TIPO_BEM.DES_MEDIDA FROM BENS_CLIENTE LEFT JOIN TIPO_BEM ON TIPO_BEM.COD_TIPOBEM = BENS_CLIENTE.COD_TIPO  WHERE BENS_CLIENTE.COD_BEM = $cod_bem";

    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
    $qrBuscaBem = mysqli_fetch_assoc($arrayQuery);

    $cod_tipobem = $qrBuscaBem['COD_TIPO'];
    $cod_cliente = $qrBuscaBem['COD_CLIENTE'];
    $des_nomebem = $qrBuscaBem['DES_NOMEBEM'];
    $des_medida = $qrBuscaBem['DES_MEDIDA'];
    $val_informado = fnValor($qrBuscaBem['VAL_INFORMADO'], 2);
    $val_efetivo = fnValor($qrBuscaBem['VAL_EFETIVO'], 2);

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
}

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
                                        <select data-placeholder="Selecione um tipo de bem" name="COD_TIPO" id="COD_TIPO" class="chosen-select-deselect" disabled>
                                            <option value="">&nbsp;</option>
                                            <?php
                                            $sql = "SELECT COD_TIPOBEM, DES_TIPOBEM FROM tipo_bem ORDER BY DES_TIPOBEM";
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Nome do Bem</label>
                                        <input type="text" class="form-control input-sm" name="DES_NOMEBEM" id="DES_NOMEBEM" value="<?= $des_nomebem ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Qt. Área Total (<?= $des_medida ?>)</label>
                                        <input type="number" class="form-control input-sm" name="QTD_AREATOT" id="QTD_AREATOT" value="<?= $qtd_areatot ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2" <?php if ($cod_tipobem == 3 || $cod_tipobem == 1) {
                                                            echo "style='display: none;'";
                                                        } ?>>
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Qt. Área Produtiva (ha)</label>
                                        <input type="number" class="form-control input-sm" name="QTD_PRODUTI" id="QTD_PRODUTI" value="<?= $qtd_produti ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Valor do Bem</label>
                                        <input type="text" class="form-control input-sm money" name="VAL_INFORMADO" id="VAL_INFORMADO" value="<?= $val_informado ?>" readonly>
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
                                        <input type="text" class="form-control input-sm" name="DES_ENDERECO" id="DES_ENDERECO" value="<?= $des_endereco ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Número</label>
                                        <input type="text" class="form-control input-sm" name="NUM_ENDERECO" id="NUM_ENDERECO" value="<?= $num_endereco ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Complemento</label>
                                        <input type="text" class="form-control input-sm" name="DES_COMPLEM" id="DES_COMPLEM" value="<?= $des_complem ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Bairro</label>
                                        <input type="text" class="form-control input-sm" name="DES_BAIRROC" id="DES_BAIRROC" value="<?= $des_bairroc ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CEP</label>
                                        <input type="text" class="form-control input-sm cep" name="NUM_CEPBEMU" id="NUM_CEPBEMU" value="<?= $num_cepbemu ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Estado</label>
                                        <select data-placeholder="Selecione um estado" name="COD_ESTADO" id="COD_ESTADO" class="chosen-select-deselect" disabled>
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
                                        <select data-placeholder="Selecione um estado" name="COD_MUNICIPIO" id="COD_MUNICIPIO" class="chosen-select-deselect" disabled>
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
                                        <input type="text" class="form-control input-sm" name="DES_ROTEIRO" id="DES_ROTEIRO" value="<?= $des_roteiro ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cartório</label>
                                        <input type="text" class="form-control input-sm" name="DES_CARTORIO" id="DES_CARTORIO" value="<?= $des_cartorio ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Nº Registro/Matrícula</label>
                                        <input type="text" class="form-control input-sm" name="NUM_MATRICU" id="NUM_MATRICU" value="<?= $num_matricu ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Folha</label>
                                        <input type="text" class="form-control input-sm" name="NUM_FOLHAMA" id="NUM_FOLHAMA" value="<?= $num_folhama ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Livro</label>
                                        <input type="text" class="form-control input-sm" name="NUM_LIVROMA" id="NUM_LIVROMA" value="<?= $num_livroma ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Estado do Cartório</label>
                                        <select data-placeholder="Selecione um estado" name="COD_ESTADOCAR" id="COD_ESTADOCAR" class="chosen-select-deselect" disabled>
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
                                        <label for="inputName" class="control-label">Município do Cartório</label>
                                        <select data-placeholder="Selecione um estado" name="COD_MUNICAR" id="COD_MUNICAR" class="chosen-select-deselect" disabled>
                                            <option value=""></option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row" <?php if ($cod_tipobem == 3) {
                                                    echo "style='display: none;'";
                                                } ?>>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">NIRF - Nº Imóvel na Receita Federal</label>
                                        <input type="text" class="form-control input-sm" name="NUM_NIRFIMA" id="NUM_NIRFIMA" value="<?= $num_nirfima ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CCIR - Cert. Cad. Imóvel Rural</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CIRCMAT" id="NUM_CIRCMAT" value="<?= $num_circmat ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CAR - Cadastro Ambiental Rural</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CARIMOV" id="NUM_CARIMOV" value="<?= $num_carimov ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cód. CAR Válido</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CARVALI" id="LOG_CARVALI" class="switch" value="S" <?php echo ($log_carvali == 'S') ? 'checked' : ''; ?> />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>


                            </div>
                            <div class="row" <?php if ($cod_tipobem == 3) {
                                                    echo "style='display: none;'";
                                                } ?>>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Status CAR</label>
                                        <select data-placeholder="Selecione um estado" name="LOG_STATUSC" id="LOG_STATUSC" class="chosen-select-deselect" disabled>
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
                                        <input type="text" class="form-control input-sm" name="NUM_ESCRICAO" id="NUM_ESCRICAO" value="<?= $num_escricao ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Área Embargada</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_AREAEMB" id="LOG_AREAEMB" class="switch" value="S" <?php echo ($log_areaemb == 'S') ? 'checked' : ''; ?> />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Terras Indígenas</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_INDIGIN" id="LOG_INDIGIN" class="switch" value="S" <?php echo ($log_indigin == 'S') ? 'checked' : ''; ?> />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row" <?php if ($cod_tipobem == 3) {
                                                    echo "style='display: none;'";
                                                } ?>>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Unid. Conserv. Prot. Integral</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_CONSERV" id="LOG_CONSERV" class="switch" value="S" <?php echo ($log_conserv == 'S') ? 'checked' : ''; ?> />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Unid. Conserv. Uso Sustentável</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_USOSUST" id="LOG_USOSUST" class="switch" value="S" <?php echo ($log_usosust == 'S') ? 'checked' : ''; ?> />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Comunidades Quilombolas</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_QUILOMB" id="LOG_QUILOMB" class="switch" value="S" <?php echo ($log_quilomb == 'S') ? 'checked' : ''; ?> />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Bioma Amazônia</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_AMAZONIA" id="LOG_AMAZONIA" class="switch" value="S" <?php echo ($log_amazonia == 'S') ? 'checked' : ''; ?> />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row" <?php if ($cod_tipobem == 3) {
                                                    echo "style='display: none;'";
                                                } ?>>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Área de Fronteira</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_FRONTEI" id="LOG_FRONTEI" class="switch" value="S" <?php echo ($log_frontei == 'S') ? 'checked' : ''; ?> />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Assentamento Incra</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_ASSENTO" id="LOG_ASSENTO" class="switch" value="S" <?php echo ($log_assento == 'S') ? 'checked' : ''; ?> />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Área Marinha</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_MARINHA" id="LOG_MARINHA" class="switch" value="S" <?php echo ($log_marinha == 'S') ? 'checked' : ''; ?> />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data Verificação das Restrições</label>
                                        <input type="text" class="form-control input-sm data" name="DAT_VERIFICA" id="DAT_VERIFICA" data-minlength="10" data-minlength-error="O formato deve ser DD/MM/AAAA" value="<?= fnDataFull($dat_verifica); ?>" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <div class="push10"></div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

                        <div class="push5"></div>

                    </form>

                    <div class="push"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
    </div>

</div>

<div class="push20"></div>


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

        carregaComboCidades(<?= $cod_estado ?>, <?= $cod_municipio ?>);
        carregaComboCidadesCar(<?= $cod_estadocar ?>, <?= $cod_municar ?>);

        $("#COD_ESTADO").change(function() {
            cod_estado = $(this).val();
            carregaComboCidades(cod_estado);
        });
        $("#COD_ESTADOCAR").change(function() {
            cod_estado = $(this).val();
            carregaComboCidadesCar(cod_estado);
        });
    });

    function resetForm() {
        window.history.pushState({}, '', '/action.do?mod=<?= $_GET["mod"] ?>&id=<?= $_GET["id"] ?>&idC=<?= $_GET["idC"] ?>');
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