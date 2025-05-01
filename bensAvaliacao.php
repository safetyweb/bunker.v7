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

        $cod_bem_avaliacao = fnLimpaCampoZero($_REQUEST['COD_BEM_AVALIACAO']);
        $cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $dat_avaliacao = date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['DAT_AVALIACAO'])));
        $dat_val_avaliacao = date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['DAT_VAL_AVALIACAO'])));
        $des_cnpj_credenc = fnLimpaCampo($_REQUEST['DES_CNPJ_CREDENC']);
        $nom_avaliador = fnLimpaCampo($_REQUEST['NOM_AVALIADOR']);
        $nom_tec_resp = fnLimpaCampo($_REQUEST['NOM_TEC_RESP']);
        $num_crea = fnLimpaCampo($_REQUEST['NUM_CREA']);
        $val_avaliado = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['VAL_AVALIADO']));
        $val_venda_forcada = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['VAL_VENDA_FORCADA']));
        $des_observacao = fnLimpaCampo($_REQUEST['DES_OBSERVACAO']);
        $log_laudo_apto = isset($_REQUEST['LOG_LAUDO_APTO']) ? fnLimpaCampo($_REQUEST['LOG_LAUDO_APTO']) : 'N';
        $log_laudo_nao_recomend = isset($_REQUEST['LOG_LAUDO_NAO_RECOMEND']) ? fnLimpaCampo($_REQUEST['LOG_LAUDO_NAO_RECOMEND']) : 'N';

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        //fnEscreve($des_icones);

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                $sql = "INSERT INTO BENS_AVALIACAO (
                    COD_BEM,
                    DAT_AVALIACAO,
                    DAT_VAL_AVALIACAO,
                    DES_CNPJ_CREDENC,
                    NOM_AVALIADOR,
                    NOM_TEC_RESP,
                    NUM_CREA,
                    VAL_AVALIADO,
                    VAL_VENDA_FORCADA,
                    DES_OBSERVACAO,
                    LOG_LAUDO_APTO,
                    LOG_LAUDO_NAO_RECOMEND,
                    DAT_CADASTR,
                    COD_USUCADA,
                    COD_EMPRESA
                    ) VALUES (
                    '" . $cod_bem . "', 
                    '" . $dat_avaliacao . "', 
                    '" . $dat_val_avaliacao . "', 
                    '" . $des_cnpj_credenc . "', 
                    '" . $nom_avaliador . "', 
                    '" . $nom_tec_resp . "', 
                    '" . $num_crea . "', 
                    '" . $val_avaliado . "', 
                    '" . $val_venda_forcada . "', 
                    '" . $des_observacao . "', 
                    '" . $log_laudo_apto . "', 
                    '" . $log_laudo_nao_recomend . "', 
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
                
                $sql = "UPDATE BENS_AVALIACAO SET
                COD_BEM = '" . $cod_bem . "',
                COD_EMPRESA = '" . $cod_empresa . "',
                DAT_AVALIACAO = '" . $dat_avaliacao . "',
                DAT_VAL_AVALIACAO = '" . $dat_val_avaliacao . "',
                DES_CNPJ_CREDENC = '" . $des_cnpj_credenc . "',
                NOM_AVALIADOR = '" . $nom_avaliador . "',
                NOM_TEC_RESP = '" . $nom_tec_resp . "',
                NUM_CREA = '" . $num_crea . "',
                VAL_AVALIADO = '" . $val_avaliado . "',
                VAL_VENDA_FORCADA = '" . $val_venda_forcada . "',
                DES_OBSERVACAO = '" . $des_observacao . "',
                LOG_LAUDO_APTO = '" . $log_laudo_apto . "',
                LOG_LAUDO_NAO_RECOMEND = '" . $log_laudo_nao_recomend . "',
                COD_ALTERAC = '" . $_SESSION["SYS_COD_USUARIO"] . "',
                DAT_ALTERAC = NOW()
                WHERE COD_BEM = '" . $cod_bem . "'
                AND COD_BEM_AVALIACAO = '" . $cod_bem_avaliacao . "'";

                    //echo $sql;exit;
                $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                if (!$arrayProc) {

                    $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                }

                $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                break;

                case 'EXC':

                $sql = "UPDATE BENS_AVALIACAO SET
                COD_EXCLUSA = '" . $_SESSION["SYS_COD_USUARIO"] . "',
                DAT_EXCLUSA = NOW()
                WHERE COD_BEM = '" . $cod_bem . "'
                AND COD_BEM_AVALIACAO = '" . $cod_bem_avaliacao . "'";

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

            <?php
            $abaBens = 1927;
            include "abasBens.php";
            ?>
            <div class="push10"></div>
            <?php 
            $abaAvaliaBens = 1927;
            include "abasAvaliaBens.php";
            ?>
            <div class="push20"></div>

            <div class="portlet-body">

                <?php if ($msgRetorno != '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>


                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>" onsubmit="return validateForm()">

                        <?php include "bensHeader.php"; ?>

                        <fieldset>
                            <legend>Avaliação do Bem</legend>

                            <div class="row">

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_BEM_AVALIACAO" id="COD_BEM_AVALIACAO" value="">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data da Avaliação</label>
                                        <input type="text" class="form-control input-sm data" name="DAT_AVALIACAO" id="DAT_AVALIACAO" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Data da Validade da Avaliação</label>
                                        <input type="text" class="form-control input-sm data" name="DAT_VAL_AVALIACAO" id="DAT_VAL_AVALIACAO" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">CNPJ Empresa Credenciada</label>
                                        <input type="text" class="form-control input-sm" name="DES_CNPJ_CREDENC" id="DES_CNPJ_CREDENC" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Avaliador</label>
                                        <input type="text" class="form-control input-sm" name="NOM_AVALIADOR" id="NOM_AVALIADOR" maxlength="100">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Técnico Responsável pela Avaliação</label>
                                        <input type="text" class="form-control input-sm" name="NOM_TEC_RESP" id="NOM_TEC_RESP" maxlength="100">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CREA</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CREA" id="NUM_CREA" maxlength="100">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Valor Avaliado</label>
                                        <input type="text" class="form-control input-sm money" name="VAL_AVALIADO" id="VAL_AVALIADO" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Valor de Venda Forçada</label>
                                        <input type="text" class="form-control input-sm money" name="VAL_VENDA_FORCADA" id="VAL_VENDA_FORCADA" maxlength="100">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Observação</label>
                                        <textarea class="form-control" name="DES_OBSERVACAO" id="DES_OBSERVACAO"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Laudo Apto</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_LAUDO_APTO" id="LOG_LAUDO_APTO" class="switch" value="S" />
                                            <span></span>
                                        </label>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Laudo Não Recomendado</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_LAUDO_NAO_RECOMEND" id="LOG_LAUDO_NAO_RECOMEND" class="switch" value="S" />
                                            <span></span>
                                        </label>
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
                        <input type="hidden" name="COD_BEM" id="COD_BEM" value="<?php echo $cod_bem; ?>">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
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
                                            <th>Dt. Avaliação</th>
                                            <th>Dt. Val. Avaliação</th>
                                            <th>CNPJ Emp. Credenciada</th>
                                            <th>Avaliador</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM BENS_AVALIACAO
                                        WHERE BENS_AVALIACAO.COD_BEM = $cod_bem AND BENS_AVALIACAO.COD_EMPRESA = $cod_empresa AND BENS_AVALIACAO.COD_EXCLUSA IS NULL ORDER BY BENS_AVALIACAO.DAT_AVALIACAO DESC";
                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));

                                        $count = 0;
                                        while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                            echo "
                                            <tr>
                                            <td class='text-center'>
                                            <input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>
                                            </td>
                                            <td>" . $qrBusca['COD_BEM_AVALIACAO'] . "</td>
                                            <td>" . date('d/m/Y', strtotime($qrBusca['DAT_AVALIACAO'])) . "</td>
                                            <td>" . date('d/m/Y', strtotime($qrBusca['DAT_VAL_AVALIACAO'])) . "</td>
                                            <td>" . $qrBusca['DES_CNPJ_CREDENC'] . "</td>
                                            <td>" . $qrBusca['NOM_AVALIADOR'] . "</td>
                                            </tr>
                                            <input type='hidden' id='ret_COD_BEM_AVALIACAO_" . $count . "' value='" . $qrBusca['COD_BEM_AVALIACAO'] . "'>
                                            <input type='hidden' id='ret_DAT_AVALIACAO_" . $count . "' value='" . $qrBusca['DAT_AVALIACAO'] . "'>
                                            <input type='hidden' id='ret_DAT_VAL_AVALIACAO_" . $count . "' value='" . $qrBusca['DAT_VAL_AVALIACAO'] . "'>
                                            <input type='hidden' id='ret_DES_CNPJ_CREDENC_" . $count . "' value='" . fnformatCnpjCpf($qrBusca['DES_CNPJ_CREDENC']) . "'>
                                            <input type='hidden' id='ret_NOM_AVALIADOR_" . $count . "' value='" . $qrBusca['NOM_AVALIADOR'] . "'>
                                            <input type='hidden' id='ret_NOM_TEC_RESP_" . $count . "' value='" . $qrBusca['NOM_TEC_RESP'] . "'>
                                            <input type='hidden' id='ret_NUM_CREA_" . $count . "' value='" . $qrBusca['NUM_CREA'] . "'>
                                            <input type='hidden' id='ret_VAL_AVALIADO_" . $count . "' value='" . $qrBusca['VAL_AVALIADO'] . "'>
                                            <input type='hidden' id='ret_VAL_VENDA_FORCADA_" . $count . "' value='" . $qrBusca['VAL_VENDA_FORCADA'] . "'>
                                            <input type='hidden' id='ret_DES_OBSERVACAO_" . $count . "' value='" . $qrBusca['DES_OBSERVACAO'] . "'>
                                            <input type='hidden' id='ret_LOG_LAUDO_APTO_" . $count . "' value='" . $qrBusca['LOG_LAUDO_APTO'] . "'>
                                            <input type='hidden' id='ret_LOG_LAUDO_NAO_RECOMEND_" . $count . "' value='" . $qrBusca['LOG_LAUDO_NAO_RECOMEND'] . "'>
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

        $("#formulario #COD_BEM_AVALIACAO").val($("#ret_COD_BEM_AVALIACAO_" + index).val());
        $("#formulario #DAT_AVALIACAO").val($("#ret_DAT_AVALIACAO_" + index).val().split('-').reverse().join('/'));
        $("#formulario #DAT_VAL_AVALIACAO").val($("#ret_DAT_VAL_AVALIACAO_" + index).val().split('-').reverse().join('/'));
        $("#formulario #DES_CNPJ_CREDENC").val($("#ret_DES_CNPJ_CREDENC_" + index).val()).trigger("chosen:updated");;
        $("#formulario #NOM_AVALIADOR").val($("#ret_NOM_AVALIADOR_" + index).val());
        $("#formulario #NOM_TEC_RESP").val($("#ret_NOM_TEC_RESP_" + index).val());
        $("#formulario #NUM_CREA").val($("#ret_NUM_CREA_" + index).val());
        $("#formulario #VAL_AVALIADO").val(Number($("#ret_VAL_AVALIADO_" + index).val()).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $("#formulario #VAL_VENDA_FORCADA").val(Number($("#ret_VAL_VENDA_FORCADA_" + index).val()).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $("#formulario #DES_OBSERVACAO").val($("#ret_DES_OBSERVACAO_" + index).val());

        $("#formulario #LOG_LAUDO_APTO").prop("checked", $("#ret_LOG_LAUDO_APTO_" + index).val() == "S");
        $("#formulario #LOG_LAUDO_NAO_RECOMEND").prop("checked", $("#ret_LOG_LAUDO_NAO_RECOMEND_" + index).val() == "S");

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');

    }
</script>