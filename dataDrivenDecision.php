<?php

echo fnDebug('true');

$hashLocal = mt_rand();


$cod_data_driven = fnLimpacampoZero(fnDecode($_GET['cod']));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        //if (1 == 2) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_data_driven = fnLimpaCampoZero($_REQUEST['COD_DATA_DRIVEN']);
        $cod_modulos = fnLimpaCampoZero($_REQUEST['COD_MODULOS']);
        $des_fluxo = fnLimpaCampo($_REQUEST['DES_FLUXO']);
        //print_r($_REQUEST);exit;
        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        //fnEscreve($des_icones);

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    $sql = "INSERT INTO DATA_DRIVEN_DECISION (
                                COD_MODULOS,
                                DES_FLUXO
                            ) VALUES (
                                '" . $cod_modulos . "', 
                                '" . $des_fluxo . "'
                            )";

                    //echo $sql;exit;
                    mysqli_query($connAdm->connAdm(), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;
                case 'ALT':
                    $sql = "UPDATE DATA_DRIVEN_DECISION SET
                                COD_MODULOS = '" . $cod_modulos . "',
                                DES_FLUXO = '" . $des_fluxo . "'
							WHERE COD_DATA_DRIVEN = '" . $cod_data_driven . "'";

                    //echo $sql;exit;
                    mysqli_query($connAdm->connAdm(), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;
                case 'EXC':
                    $sql = "DELETE FROM DATA_DRIVEN_DECISION
							WHERE COD_DATA_DRIVEN = '" . $cod_data_driven . "'";

                    mysqli_query($connAdm->connAdm(), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

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

            <div class="push10"></div>

            <div class="portlet-body">

                <?php if ($msgRetorno != '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>


                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_DATA_DRIVEN" id="COD_DATA_DRIVEN" value="">
                                        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="COD_DATA_DRIVEN_ENCODE" id="COD_DATA_DRIVEN_ENCODE" value="">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <label for="inputName" class="control-label required">Módulo</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBuscaModulo" id="btnBuscaModulo" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1477) ?>&id=<?php echo fnEncode($cod_modulos) ?>&TIP_MODULOS=6&pop=true" data-title="Busca Módulo">
                                                <i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i>
                                            </a>
                                        </span>
                                        <input type="text" readonly="readonly" name="NOM_MODULOS" id="NOM_MODULOS" value="" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
                                        <input type="hidden" name="COD_MODULOS" id="COD_MODULOS" value="">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Descrição</label>
                                        <input type="text" class="form-control input-sm" name="DES_FLUXO" id="DES_FLUXO" maxlength="100" required>
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
                                            <th>Descrição</th>
                                            <th>Módulo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT DATA_DRIVEN_DECISION.*,MODULOS.DES_MODULOS FROM DATA_DRIVEN_DECISION
                                                LEFT JOIN MODULOS ON MODULOS.COD_MODULOS=DATA_DRIVEN_DECISION.COD_MODULOS
                                                ORDER BY DATA_DRIVEN_DECISION.DES_FLUXO";
                                        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));

                                        //$count = 0;
                                        while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                            $count = $qrBusca['COD_DATA_DRIVEN'];
                                            echo "
												<tr>
													<td class='text-center'>
														<input type='radio' name='radio1' onclick='retornaForm(" . $count . ",\"" . fnEncode($qrBusca['COD_DATA_DRIVEN']) . "\")'>
													</td>
													<td style='text-align:right'>" . $qrBusca['COD_DATA_DRIVEN'] . "</td>
													<td>" . $qrBusca['DES_FLUXO'] . "</td>
													<td>" . $qrBusca['DES_MODULOS'] . "</td>
												</tr>
												<input type='hidden' id='ret_COD_DATA_DRIVEN_" . $count . "' value='" . $qrBusca['COD_DATA_DRIVEN'] . "'>
												<input type='hidden' id='ret_COD_MODULOS_" . $count . "' value='" . $qrBusca['COD_MODULOS'] . "'>
												<input type='hidden' id='ret_DES_MODULOS_" . $count . "' value='" . $qrBusca['DES_MODULOS'] . "'>
												<input type='hidden' id='ret_DES_FLUXO_" . $count . "' value='" . $qrBusca['DES_FLUXO'] . "'>
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

<script type="text/javascript">
    $(document).ready(function() {

    });

    function retornaForm(index, cod) {
        $("#formulario #COD_DATA_DRIVEN").val($("#ret_COD_DATA_DRIVEN_" + index).val());
        $("#formulario #COD_DATA_DRIVEN_ENCODE").val(cod);
        $("#formulario #COD_MODULOS").val($("#ret_COD_MODULOS_" + index).val());
        $("#formulario #NOM_MODULOS").val($("#ret_DES_MODULOS_" + index).val());
        $("#formulario #DES_FLUXO").val($("#ret_DES_FLUXO_" + index).val());

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');

    }
</script>