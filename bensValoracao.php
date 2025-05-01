<?php

// echo fnDebug('true');

$hashLocal = mt_rand();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        //if (1 == 2) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_bem_valoracao = fnLimpaCampoZero($_REQUEST['COD_BEM_VALORACAO']);
        $cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $des_atividade_agricola = fnLimpaCampo($_REQUEST['DES_ATIVIDADE_AGRICOLA']);
        $num_area_atividade = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['NUM_AREA_ATIVIDADE']));
        $val_ha_area_atividade = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['VAL_HA_AREA_ATIVIDADE']));
        $val_area_atividade = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['VAL_AREA_ATIVIDADE']));
        $num_area_total = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['NUM_AREA_TOTAL']));
        $val_ha_area_total = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['VAL_HA_AREA_TOTAL']));
        $val_area_total = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['VAL_AREA_TOTAL']));

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        //fnEscreve($des_icones);

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                $sql = "INSERT INTO BENS_VALORACAO (
                    COD_BEM,
                    DES_ATIVIDADE_AGRICOLA,
                    NUM_AREA_ATIVIDADE,
                    VAL_HA_AREA_ATIVIDADE,
                    VAL_AREA_ATIVIDADE,
                    NUM_AREA_TOTAL,
                    VAL_HA_AREA_TOTAL,
                    VAL_AREA_TOTAL,
                    DAT_CADASTR,
                    COD_USUCADA,
                    COD_EMPRESA
                    ) VALUES (
                    '" . $cod_bem . "', 
                    '" . $des_atividade_agricola . "', 
                    '" . $num_area_atividade . "', 
                    '" . $val_ha_area_atividade . "', 
                    '" . $val_area_atividade . "', 
                    '" . $num_area_total . "', 
                    '" . $val_ha_area_total . "', 
                    '" . $val_area_total . "',
                    NOW(),
                    '" . $_SESSION["SYS_COD_USUARIO"] . "',
                    '" . $cod_empresa . "'
                )";

                    //echo $sql;exit;
                    //fnEscreve($sql);
                $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                if (!$arrayProc) {

                    $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                }

                $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                break;

                case 'ALT':
                $sql = "UPDATE BENS_VALORACAO SET
                COD_BEM = '" . $cod_bem . "',
                COD_EMPRESA = '" . $cod_empresa . "',
                DES_ATIVIDADE_AGRICOLA = '" . $des_atividade_agricola . "',
                NUM_AREA_ATIVIDADE = '" . $num_area_atividade . "',
                VAL_HA_AREA_ATIVIDADE = '" . $val_ha_area_atividade . "',
                VAL_AREA_ATIVIDADE = '" . $val_area_atividade . "',
                NUM_AREA_TOTAL = '" . $num_area_total . "',
                VAL_HA_AREA_TOTAL = '" . $val_ha_area_total . "',
                VAL_AREA_TOTAL = '" . $val_area_total . "',
                COD_ALTERAC = '" . $_SESSION["SYS_COD_USUARIO"] . "',
                DAT_ALTERAC = NOW()
                WHERE COD_BEM = '" . $cod_bem . "'
                AND COD_BEM_VALORACAO = '" . $cod_bem_valoracao . "'";

                    //echo $sql;exit;
                $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                if (!$arrayProc) {

                    $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                }

                $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                break;
                
                case 'EXC':
                $sql = "UPDATE BENS_VALORACAO SET
                COD_EXCLUSA = '" . $_SESSION["SYS_COD_USUARIO"] . "',
                DAT_EXCLUSA = NOW()
                WHERE COD_BEM = '" . $cod_bem . "'
                AND COD_BEM_VALORACAO = '" . $cod_bem_valoracao . "'";

                $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                if (!$arrayProc) {

                    $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                }

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
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
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

            <div class="portlet-body">

                <?php if ($msgRetorno != '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>

                <?php
                $abaBens = 1927;
                include "abasBens.php";
                ?>
                <div class="push10"></div>
                <?php 
                $abaAvaliaBens = 1928;
                include "abasAvaliaBens.php";
                ?>
                <div class="push20"></div>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>" onsubmit="return validateForm()">

                        <?php include "bensHeader.php"; ?>

                        <fieldset>
                            <legend>Atividade de Valoração do Bem</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_BEM_VALORACAO" id="COD_BEM_VALORACAO" value="">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Atividade Agrícola</label>
                                        <input type="text" class="form-control input-sm" name="DES_ATIVIDADE_AGRICOLA" id="DES_ATIVIDADE_AGRICOLA" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Área por Atividade</label>
                                        <input type="text" class="form-control input-sm money" name="NUM_AREA_ATIVIDADE" id="NUM_AREA_ATIVIDADE" maxlength="100" onchange="calcArea('ATIVIDADE')" onkeyup="calcArea('ATIVIDADE')">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Valor (R$/HA) por Atividade</label>
                                        <input type="text" class="form-control input-sm money" name="VAL_HA_AREA_ATIVIDADE" id="VAL_HA_AREA_ATIVIDADE" maxlength="100" onchange="calcArea('ATIVIDADE')" onkeyup="calcArea('ATIVIDADE')">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Valor por Atividade</label>
                                        <input type="text" class="form-control input-sm money" name="VAL_AREA_ATIVIDADE" id="VAL_AREA_ATIVIDADE" maxlength="100" readonly>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Área Total</label>
                                        <input type="text" class="form-control input-sm money" name="NUM_AREA_TOTAL" id="NUM_AREA_TOTAL" maxlength="100" onchange="calcArea('TOTAL')" onkeyup="calcArea('TOTAL')">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Valor (R$/HA) Total</label>
                                        <input type="text" class="form-control input-sm money" name="VAL_HA_AREA_TOTAL" id="VAL_HA_AREA_TOTAL" maxlength="100" onchange="calcArea('TOTAL')" onkeyup="calcArea('TOTAL')">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Valor Total</label>
                                        <input type="text" class="form-control input-sm money" name="VAL_AREA_TOTAL" id="VAL_AREA_TOTAL" maxlength="100" readonly>
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

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
                        <input type="hidden" name="COD_BEM" id="COD_BEM" value="<?php echo $cod_bem; ?>" />
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
                                            <th>Atividade Agrícola</th>
                                            <th style='text-align:right'>Área por Atividade</th>
                                            <th style='text-align:right'>Valor por Atividade</th>
                                            <th style='text-align:right'>Área Total</th>
                                            <th style='text-align:right'>Valor Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM BENS_VALORACAO
                                        WHERE BENS_VALORACAO.COD_BEM = $cod_bem AND BENS_VALORACAO.COD_EMPRESA = $cod_empresa AND BENS_VALORACAO.COD_EXCLUSA IS NULL";
                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));

                                        $count = 0;
                                        while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                            echo "
                                            <tr>
                                            <td class='text-center'>
                                            <input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>
                                            </td>
                                            <td >" . $qrBusca['COD_BEM_VALORACAO'] . "</td>
                                            <td>" . $qrBusca['DES_ATIVIDADE_AGRICOLA'] . "</td>
                                            <td style='text-align:right'>" . number_format($qrBusca['NUM_AREA_ATIVIDADE'], 2, ",", ".") . "</td>
                                            <td style='text-align:right'>" . number_format($qrBusca['VAL_AREA_ATIVIDADE'], 2, ",", ".") . "</td>
                                            <td style='text-align:right'>" . number_format($qrBusca['NUM_AREA_TOTAL'], 2, ",", ".") . "</td>
                                            <td style='text-align:right'>" . number_format($qrBusca['VAL_AREA_TOTAL'], 2, ",", ".") . "</td>
                                            </tr>
                                            <input type='hidden' id='ret_COD_BEM_VALORACAO_" . $count . "' value='" . $qrBusca['COD_BEM_VALORACAO'] . "'>
                                            <input type='hidden' id='ret_DES_ATIVIDADE_AGRICOLA_" . $count . "' value='" . $qrBusca['DES_ATIVIDADE_AGRICOLA'] . "'>
                                            <input type='hidden' id='ret_NUM_AREA_ATIVIDADE_" . $count . "' value='" . $qrBusca['NUM_AREA_ATIVIDADE'] . "'>
                                            <input type='hidden' id='ret_VAL_HA_AREA_ATIVIDADE_" . $count . "' value='" . $qrBusca['VAL_HA_AREA_ATIVIDADE'] . "'>
                                            <input type='hidden' id='ret_VAL_AREA_ATIVIDADE_" . $count . "' value='" . $qrBusca['VAL_AREA_ATIVIDADE'] . "'>
                                            <input type='hidden' id='ret_NUM_AREA_TOTAL_" . $count . "' value='" . $qrBusca['NUM_AREA_TOTAL'] . "'>
                                            <input type='hidden' id='ret_VAL_HA_AREA_TOTAL_" . $count . "' value='" . $qrBusca['VAL_HA_AREA_TOTAL'] . "'>
                                            <input type='hidden' id='ret_VAL_AREA_TOTAL_" . $count . "' value='" . $qrBusca['VAL_AREA_TOTAL'] . "'>
                                            ";
                                            $count++;
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


<script type="text/javascript">
    $(document).ready(function() {

    });

    function retornaForm(index) {

        $("#formulario #COD_BEM_VALORACAO").val($("#ret_COD_BEM_VALORACAO_" + index).val());
        $("#formulario #DES_ATIVIDADE_AGRICOLA").val($("#ret_DES_ATIVIDADE_AGRICOLA_" + index).val());
        $("#formulario #NUM_AREA_ATIVIDADE").val(Number($("#ret_NUM_AREA_ATIVIDADE_" + index).val()).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $("#formulario #VAL_HA_AREA_ATIVIDADE").val(Number($("#ret_VAL_HA_AREA_ATIVIDADE_" + index).val()).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $("#formulario #VAL_AREA_ATIVIDADE").val(Number($("#ret_VAL_AREA_ATIVIDADE_" + index).val()).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $("#formulario #NUM_AREA_TOTAL").val(Number($("#ret_NUM_AREA_TOTAL_" + index).val()).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $("#formulario #VAL_HA_AREA_TOTAL").val(Number($("#ret_VAL_HA_AREA_TOTAL_" + index).val()).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $("#formulario #VAL_AREA_TOTAL").val(Number($("#ret_VAL_AREA_TOTAL_" + index).val()).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');

    }

    function calcArea(campo) {
        let num = $("#formulario #NUM_AREA_" + campo).val().replace(/\./g, '').replace(',', '.') || 0;
        let val = $("#formulario #VAL_HA_AREA_" + campo).val().replace(/\./g, '').replace(',', '.') || 0;

        $("#formulario #VAL_AREA_" + campo).val(Number(+num * +val).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    }
</script>