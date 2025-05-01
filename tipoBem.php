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
    }
} else {
    $cod_empresa = 0;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_tipobem = fnLimpaCampoZero($_REQUEST['COD_TIPOBEM']);
        $des_tipobem = fnLimpaCampo($_REQUEST['DES_TIPOBEM']);

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        //fnEscreve($des_icones);

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    $sql = "INSERT INTO TIPO_BEM (DES_TIPOBEM, DAT_CADASTR, COD_USUCADA, COD_EMPRESA) VALUES
							('" . $des_tipobem . "', NOW(), '" . $_SESSION["SYS_COD_USUARIO"] . "', '" . $cod_empresa . "')";

                    mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;
                case 'ALT':
                    $sql = "UPDATE TIPO_BEM SET
								DES_TIPOBEM = '" . $des_tipobem . "',
								COD_ALTERAC = '" . $_SESSION["SYS_COD_USUARIO"] . "',
								DAT_ALTERAC = NOW()
							WHERE COD_TIPOBEM = '" . $cod_tipobem . "'";

                    mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;
                case 'EXC':
                    $sql = "UPDATE TIPO_BEM SET
								COD_EXCLUSA = '" . $_SESSION["SYS_COD_USUARIO"] . "',
								DAT_EXCLUSA = NOW()
							WHERE COD_TIPOBEM = '" . $cod_tipobem . "'";

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
        <div <?= ($popUp != "true" ? 'class="portlet portlet-bordered"' : 'class="portlet" style="padding: 0 20px 20px 20px;"') ?>>

            <?php if ($popUp != "true") { ?>
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fal fa-terminal"></i>
                        <span class="text-primary"><?php echo $NomePg; ?>
                    </div>
                </div>
            <?php } ?>

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
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TIPOBEM" id="COD_TIPOBEM" value="">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Empresa</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
                                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Descrição</label>
                                        <input type="text" class="form-control input-sm" name="DES_TIPOBEM" id="DES_TIPOBEM" maxlength="100" required>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "select * from TIPO_BEM where COD_EMPRESA = $cod_empresa  AND COD_EXCLUSA is null order by DES_TIPOBEM";
                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));

                                        $count = 0;
                                        while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                            $count++;
                                            echo "
												<tr>
													<td class='text-center'>
														<input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>
													</td>
													<td>" . $qrBusca['COD_TIPOBEM'] . "</td>
													<td>" . $qrBusca['DES_TIPOBEM'] . "</td>
												</tr>
												<input type='hidden' id='ret_COD_TIPOBEM_" . $count . "' value='" . $qrBusca['COD_TIPOBEM'] . "'>
												<input type='hidden' id='ret_DES_TIPOBEM_" . $count . "' value='" . $qrBusca['DES_TIPOBEM'] . "'>
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

<script type="text/javascript">
    function retornaForm(index) {
        $("#formulario #COD_TIPOBEM").val($("#ret_COD_TIPOBEM_" + index).val());
        $("#formulario #DES_TIPOBEM").val($("#ret_DES_TIPOBEM_" + index).val());
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }
</script>