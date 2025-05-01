<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
        $cod_benefic = fnLimpaCampoZero($_REQUEST['COD_BENEFICIOS']);
        $des_benefic = fnLimpaCampo($_REQUEST['DES_BENEFICIOS']);
        $cod_usucada = $_SESSION[SYS_COD_USUARIO];

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':

                    $sql = "INSERT INTO BENEFICIOS  (
                                                        COD_EMPRESA,
                                                        DES_BENEFICIOS,
                                                        COD_CADASTR,
                                                        DAT_CADASTR
                                                    )VALUES(
                                                        $cod_empresa,
                                                        '$des_benefic',
                                                        $cod_usucada,
                                                        NOW()
                                                    )";

                    //echo $sql;

                    mysqli_query(connTemp($cod_empresa, ''), trim($sql));

                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    ?>
                    <script>
                        try {
                            parent.$('#LOG_BENEFICIO').val("S");
                        } catch (err) {
                        }
                    </script>
                    <?php
                    break;

                case 'ALT':

                    $sql = "UPDATE BENEFICIOS SET
												COD_EMPRESA = $cod_empresa,
												DES_BENEFICIOS = '$des_benefic',
												COD_ALTERAC = $cod_usucada,
												DAT_ALTERAC = NOW()
								WHERE COD_BENEFICIOS = $cod_benefic
								AND COD_EMPRESA = $cod_empresa";

                    //echo $sql;

                    mysqli_query(connTemp($cod_empresa, ''), trim($sql));

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    ?>
                    <script>
                        try {
                            parent.$('#LOG_BENEFICIO').val("S");
                        } catch (err) {
                        }
                    </script>
                    <?php
                    break;

                /*case 'EXC':

                    //echo $sql;

                    mysqli_query(connTemp($cod_empresa, ''), trim($sql));

                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

                    break;
                    break;*/
            }
            $msgTipo = 'alert-success';
        }
    }
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    //fnEscreve('entrou else');
}


//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-body">

                <?php if ($msgRetorno <> '') { ?>
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>

                <div class="push30"></div>

                <div class="login-form">

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Código</label>
                                    <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_BENEFICIOS" id="COD_BENEFICIOS" value="<?= $cod_benefic ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Benefício</label>
                                    <input type="text" class="form-control input-sm" name="DES_BENEFICIOS" id="DES_BENEFICIOS" maxlength="20" value="<?= $des_benefic ?>" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                        </div>

                        <div class="push10"></div>
                        <hr>
                        <div class="form-group text-right col-lg-12">

                            <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
                            <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                            <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
                            <!--<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>-->

                        </div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
                        <input type="hidden" name="LOG_BENEFICIO" id="LOG_BENEFICIO" value="S">

                        <div class="push5"></div>
                        <div class="push50"></div>

                        <div class="col-lg-12">

                            <div class="no-more-tables">

                                <form name="formLista">

                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="40"></th>
                                                <th>Código</th>
                                                <th>Benefício</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php

                                            $sql = "SELECT * FROM BENEFICIOS WHERE COD_EMPRESA = $cod_empresa";
                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                            $count = 0;
                                            while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                                $count++;
                                                echo "
                                                    <tr>
                                                        <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                                        <td>" . $qrBuscaModulos['COD_BENEFICIOS'] . "</td>
                                                        <td>" . $qrBuscaModulos['DES_BENEFICIOS'] . "</td>
                                                    </tr>
                                                    <input type='hidden' id='ret_COD_BENEFICIOS_" . $count . "' value='" . $qrBuscaModulos['COD_BENEFICIOS'] . "'>
                                                    <input type='hidden' id='ret_DES_BENEFICIOS_" . $count . "' value='" . $qrBuscaModulos['DES_BENEFICIOS'] . "'>
                                                    ";
                                            }

                                            ?>

                                        </tbody>
                                    </table>


                                    <div class="push50"></div>


                                </form>

                            </div>

                        </div>

                        <div class="push"></div>

                </div>

            </div>
        </div>
        <!-- fim Portlet -->
        <div class="push20"></div>
    </div>
</div>
<script type="text/javascript">
  
    console.log($("#LOG_BENEFICIO").val());

    function retornaForm(index) {
        $("#formulario #COD_BENEFICIOS").val($("#ret_COD_BENEFICIOS_" + index).val());
        $("#formulario #DES_BENEFICIOS").val($("#ret_DES_BENEFICIOS_" + index).val());
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }
</script>