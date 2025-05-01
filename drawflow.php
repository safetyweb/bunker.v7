<?php

//echo fnDebug('true');

$hashLocal = mt_rand();


$cod_fluxo = fnLimpacampoZero(fnDecode($_GET['idFluxo']));


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        //if (1 == 2) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_fluxo = fnLimpaCampoZero($_REQUEST['COD_FLUXO']);
        $des_fluxo = fnLimpaCampo($_REQUEST['DES_FLUXO']);
        $cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);

        //print_r($_REQUEST);exit;
        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        //fnEscreve($des_icones);

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    $sql = "INSERT INTO FLUXO_DADOS (
                                DES_FLUXO,
                                COD_EMPRESA
                            ) VALUES (
                                '" . $des_fluxo . "',
                                '" . $cod_empresa . "'
                            )";

                    //echo $sql;exit;
                    mysqli_query(conntemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;
                case 'ALT':
                    $sql = "UPDATE FLUXO_DADOS SET
                                DES_FLUXO = '" . $des_fluxo . "'
							WHERE COD_FLUXO = '" . $cod_fluxo . "'";

                    //echo $sql;exit;
                    mysqli_query(conntemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;
                case 'EXC':
                    $sql = "DELETE FROM FLUXO_DADOS
							WHERE COD_FLUXO = '" . $cod_fluxo . "'";

                    mysqli_query(conntemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                    break;
            }
            $msgTipo = 'alert-success';
        }
    }
}


//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
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
            $abaBens = 1935;
            include "abasDataFlow.php";
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

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_FLUXO" id="COD_FLUXO" value="">
                                        <input type="hidden" class="form-control input-sm leitura" readonly="readonly" name="COD_FLUXO_ENCODE" id="COD_FLUXO_ENCODE" value="">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Empresa</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
                                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
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
                                            <th>Descrição</th>
                                            <th>Módulo Inicial</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM FLUXO_DADOS F"
                                            . " LEFT JOIN webtools.MODULOS M ON M.COD_MODULOS = F.COD_MODULOS"
                                            . " WHERE F.COD_EMPRESA = 0$cod_empresa"
                                            . " ORDER BY F.DES_FLUXO";
                                        $arrayQuery = mysqli_query(conntemp($cod_empresa, ""), $sql) or die(mysqli_error($connAdm->connAdm()));

                                        //$count = 0;
                                        while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                            $count = $qrBusca['COD_FLUXO'];
                                            echo ""
                                                . "<tr>"
                                                . "<td class='text-center'>"
                                                . "<input type='radio' name='radio1' onclick='retornaForm(" . $count . ",\"" . fnEncode($qrBusca['COD_FLUXO']) . "\")'>"
                                                . "</td>"
                                                . "<td style='text-align:right'>" . $qrBusca['COD_FLUXO'] . "</td>"
                                                . "<td>" . $qrBusca['DES_FLUXO'] . "</td>"
                                                . "<td>" . $qrBusca['COD_MODULOS'] . " - " . $qrBusca['NOM_MODULOS'] . "</td>"
                                                . "</tr>"
                                                . "<input type='hidden' id='ret_COD_FLUXO_" . $count . "' value='" . $qrBusca['COD_FLUXO'] . "'>"
                                                . "<input type='hidden' id='ret_DES_FLUXO_" . $count . "' value='" . $qrBusca['DES_FLUXO'] . "'>"
                                                . "";
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
        <?php
        if ($cod_fluxo > 0) {
            echo "retornaForm($cod_fluxo,'" . fnEncode($cod_fluxo) . "');";
        } else {
            echo "resetForm();";
        }
        ?>
    });

    function resetForm() {
        window.history.pushState({}, '', '/action.do?mod=<?= $_GET["mod"] ?>&id=<?= $_GET["id"] ?>');
    }

    function retornaForm(index, idFluxo) {
        window.history.pushState({}, "", "/action.do?mod=<?= $_GET["mod"] ?>&id=<?= $_GET["id"] ?>&idFluxo=" + idFluxo);

        $("#formulario #COD_FLUXO").val($("#ret_COD_FLUXO_" + index).val());
        $("#formulario #COD_FLUXO_ENCODE").val(idFluxo);
        $("#formulario #DES_FLUXO").val($("#ret_DES_FLUXO_" + index).val());

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');

    }
</script>