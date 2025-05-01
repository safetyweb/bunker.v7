<?php

//echo fnDebug('true');

$hashLocal = mt_rand();


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
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
    }
} else {
    $cod_empresa = 0;
}

$cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));

//fnEscreve2($cod_bem);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        //if (1 == 2) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_bem_proprietario = fnLimpaCampoZero($_REQUEST['COD_BEM_PROPRIETARIO']);
        $cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_proprietario = fnLimpaCampoZero($_REQUEST['COD_USUARIO']);
        $val_participacao_pc = '0' . str_replace(array('.', ','), array('', '.'), fnLimpaCampo($_REQUEST['VAL_PARTICIPACAO_PC']));
        $log_principal = isset($_REQUEST['LOG_PRINCIPAL']) ? fnLimpaCampo($_REQUEST['LOG_PRINCIPAL']) : 'N';

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    $sql = "INSERT INTO BENS_PROPRIETARIOS (
                                COD_BEM,
                                COD_PROPRIETARIO,
                                VAL_PARTICIPACAO_PC,
                                LOG_PRINCIPAL,
                                DAT_CADASTR,
                                COD_USUCADA,
                                COD_EMPRESA
                            ) VALUES (
                                '" . $cod_bem . "', 
                                '" . $cod_proprietario . "', 
                                '" . $val_participacao_pc . "', 
                                '" . $log_principal . "', 
                                NOW(),
                                '" . $_SESSION["SYS_COD_USUARIO"] . "',
                                '" . $cod_empresa . "'
                            )";

                    //echo $sql;exit;

                   // fnEscreve($sql);

                    mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;
                case 'ALT':
                    $sql = "UPDATE BENS_PROPRIETARIOS SET
                                COD_BEM = '" . $cod_bem . "',
                                COD_EMPRESA = '" . $cod_empresa . "',
                                COD_PROPRIETARIO = '" . $cod_proprietario . "',
                                VAL_PARTICIPACAO_PC = '" . $val_participacao_pc . "',
                                LOG_PRINCIPAL = '" . $log_principal . "',
                                COD_ALTERAC = '" . $_SESSION["SYS_COD_USUARIO"] . "',
                                DAT_ALTERAC = NOW()
                            WHERE COD_BEM = '" . $cod_bem . "'
                                AND COD_BEM_PROPRIETARIO = '" . $cod_bem_proprietario . "'";

                    //echo $sql;exit;
                    mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;
                case 'EXC':
                    $sql = "UPDATE BENS_PROPRIETARIOS SET
								COD_EXCLUSA = '" . $_SESSION["SYS_COD_USUARIO"] . "',
								DAT_EXCLUSA = NOW()
							WHERE COD_BEM = '" . $cod_bem . "'
                                AND COD_BEM_PROPRIETARIO = '" . $cod_bem_proprietario . "'";

                    mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                    break;
            }
            $msgTipo = 'alert-success';
        }
    }
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
            $abaBens = 1930;
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


                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>" onsubmit="return validateForm()">

                        <?php include "bensHeader.php"; ?>

                        <fieldset>
                            <legend>Proprietários do Bem</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_BEM_PROPRIETARIO" id="COD_BEM_PROPRIETARIO" value="">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="inputName" class="control-label required">Nome do Proprietário</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
                                        </span>
                                        <input type="text" name="NOM_USUARIO" id="NOM_USUARIO" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required readonly>
                                        <input type="hidden" name="COD_USUARIO" id="COD_USUARIO">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">% Participação</label>
                                        <input type="text" class="form-control input-sm money" name="VAL_PARTICIPACAO_PC" id="VAL_PARTICIPACAO_PC" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Proprietário Principal?</label><br />
                                        <label class="switch switch-small">
                                            <input type="checkbox" name="LOG_PRINCIPAL" id="LOG_PRINCIPAL" class="switch" value="S" />
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
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="COD_BEM" id="COD_BEM" value="<?php echo $cod_bem; ?>" />
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
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
                                            <th>Proprietário</th>
                                            <th>% Participação</th>
                                            <th>Proprietário Principal?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT BENS_PROPRIETARIOS.*, CLIENTES.NOM_CLIENTE NOM_PROPRIETARIO FROM BENS_PROPRIETARIOS
                                                LEFT JOIN CLIENTES ON CLIENTES.COD_CLIENTE=BENS_PROPRIETARIOS.COD_PROPRIETARIO
                                                WHERE BENS_PROPRIETARIOS.COD_BEM = $cod_bem AND BENS_PROPRIETARIOS.COD_EMPRESA = $cod_empresa AND BENS_PROPRIETARIOS.COD_EXCLUSA IS NULL";

                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));

                                        $count = 0;
                                        while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                            echo "
                                                <tr>
                                                    <td class='text-center'>
                                                        <input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>
                                                    </td>
                                                    <td style=''>" . $qrBusca['COD_BEM_PROPRIETARIO'] . "</td>
                                                    <td>" . $qrBusca['NOM_PROPRIETARIO'] . "</td>
                                                    <td style=''>" . number_format($qrBusca['VAL_PARTICIPACAO_PC'], 2, ',', '.') . "</td>
                                                    <td style=''>" . ($qrBusca['LOG_PRINCIPAL'] == "S" ? "Sim" : "Não") . "</td>
                                                </tr>
                                                <input type='hidden' id='ret_COD_BEM_PROPRIETARIO_" . $count . "' value='" . $qrBusca['COD_BEM_PROPRIETARIO'] . "'>
                                                <input type='hidden' id='ret_COD_PROPRIETARIO_" . $count . "' value='" . $qrBusca['COD_PROPRIETARIO'] . "'>
                                                <input type='hidden' id='ret_NOM_PROPRIETARIO_" . $count . "' value='" . $qrBusca['NOM_PROPRIETARIO'] . "'>
                                                <input type='hidden' id='ret_VAL_PARTICIPACAO_PC_" . $count . "' value='" . $qrBusca['VAL_PARTICIPACAO_PC'] . "'>
                                                <input type='hidden' id='ret_LOG_PRINCIPAL_" . $count . "' value='" . $qrBusca['LOG_PRINCIPAL'] . "'>
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

        $("#formulario #COD_BEM_PROPRIETARIO").val($("#ret_COD_BEM_PROPRIETARIO_" + index).val());
        $("#formulario #NOM_USUARIO").val($("#ret_NOM_PROPRIETARIO_" + index).val());
        $("#formulario #COD_USUARIO").val($("#ret_COD_PROPRIETARIO_" + index).val());
        $("#formulario #VAL_PARTICIPACAO_PC").val(Number($("#ret_VAL_PARTICIPACAO_PC_" + index).val()).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
        $("#formulario #LOG_PRINCIPAL").prop("checked", $("#ret_LOG_PRINCIPAL_" + index).val() == "S");

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');

    }
</script>