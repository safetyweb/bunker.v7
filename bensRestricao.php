<?php

//echo fnDebug('true');

$hashLocal = mt_rand();


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

        $cod_bem_restricao = fnLimpaCampoZero($_REQUEST['COD_BEM_RESTRICAO']);
        $cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $des_tipo = fnLimpaCampo($_REQUEST['DES_TIPO']);
        $des_restricao = fnLimpaCampo($_REQUEST['DES_RESTRICAO']);
        $dat_fim = date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['DAT_FIM'])));

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                $sql = "INSERT INTO BENS_RESTRICAO (
                    COD_BEM,
                    DES_TIPO,
                    DES_RESTRICAO,
                    DAT_FIM,
                    DAT_CADASTR,
                    COD_USUCADA,
                    COD_EMPRESA
                    ) VALUES (
                    '" . $cod_bem . "', 
                    '" . $des_tipo . "', 
                    '" . $des_restricao . "', 
                    '" . $dat_fim . "', 
                    NOW(),
                    '" . $_SESSION["SYS_COD_USUARIO"] . "',
                    '" . $cod_empresa . "'
                )";

                $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                break;
                case 'ALT':
                $sql = "UPDATE BENS_RESTRICAO SET
                COD_BEM = '" . $cod_bem . "',
                COD_EMPRESA = '" . $cod_empresa . "',
                DES_TIPO = '" . $des_tipo . "',
                DES_RESTRICAO = '" . $des_restricao . "',
                DAT_FIM = '" . $dat_fim . "',
                COD_ALTERAC = '" . $_SESSION["SYS_COD_USUARIO"] . "',
                DAT_ALTERAC = NOW()
                WHERE COD_BEM = '" . $cod_bem . "'
                AND COD_BEM_RESTRICAO = '" . $cod_bem_restricao . "'";

                    //echo $sql;exit;
                $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                break;
                case 'EXC':
                $sql = "UPDATE BENS_RESTRICAO SET
                COD_EXCLUSA = '" . $_SESSION["SYS_COD_USUARIO"] . "',
                DAT_EXCLUSA = NOW()
                WHERE COD_BEM = '" . $cod_bem . "'
                AND COD_BEM_RESTRICAO = '" . $cod_bem_restricao . "'";

                $arrayProc = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));

                break;
            }

            if (!$arrayProc) {

                $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
            }
            
            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                if ($cod_erro == 0 || $cod_erro ==  "") {
                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                } else {
                    $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                }
                break;
                case 'ALT':
                if ($cod_erro == 0 || $cod_erro ==  "") {
                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                } else {
                    $msgRetorno = "Não foi possível alterar o registro : $cod_erro";
                }
                break;
                case 'EXC':
                if ($cod_erro == 0 || $cod_erro ==  "") {
                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                } else {
                    $msgRetorno = "Não foi possível excluir o registro : $cod_erro";
                }
                break;                  
            }
            if ($cod_erro == 0 || $cod_erro == "") {
                $msgTipo = 'alert-success';
            } else {
                $msgTipo = 'alert-danger';
            }

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
            $abaBens = 1931;
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
                            <legend>Restrição/Ônus de Garantia</legend>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_BEM_RESTRICAO" id="COD_BEM_RESTRICAO" value="">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Tipo de Restrição</label>
                                        <input type="text" class="form-control input-sm" name="DES_TIPO" id="DES_TIPO" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Descrição</label>
                                        <input type="text" class="form-control input-sm" name="DES_RESTRICAO" id="DES_RESTRICAO" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Data Fim</label>
                                        <input type="text" class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" maxlength="100">
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
                                            <th>Tipo de Restrição</th>
                                            <th>Descrição</th>
                                            <th>Data Fim</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM BENS_RESTRICAO
                                        WHERE BENS_RESTRICAO.COD_BEM = $cod_bem AND BENS_RESTRICAO.COD_EMPRESA = $cod_empresa AND BENS_RESTRICAO.COD_EXCLUSA IS NULL";
                                        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));

                                        $count = 0;
                                        while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
                                            echo "
                                            <tr>
                                            <td class='text-center'>
                                            <input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>
                                            </td>
                                            <td >" . $qrBusca['COD_BEM_RESTRICAO'] . "</td>
                                            <td>" . $qrBusca['DES_TIPO'] . "</td>
                                            <td>" . $qrBusca['DES_RESTRICAO'] . "</td>
                                            <td>" . date('d/m/Y', strtotime($qrBusca['DAT_FIM'])) . "</td>
                                            </tr>
                                            <input type='hidden' id='ret_COD_BEM_RESTRICAO_" . $count . "' value='" . $qrBusca['COD_BEM_RESTRICAO'] . "'>
                                            <input type='hidden' id='ret_DES_TIPO_" . $count . "' value='" . $qrBusca['DES_TIPO'] . "'>
                                            <input type='hidden' id='ret_DES_RESTRICAO_" . $count . "' value='" . $qrBusca['DES_RESTRICAO'] . "'>
                                            <input type='hidden' id='ret_DAT_FIM_" . $count . "' value='" . $qrBusca['DAT_FIM'] . "'>
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

        $("#formulario #COD_BEM_RESTRICAO").val($("#ret_COD_BEM_RESTRICAO_" + index).val());
        $("#formulario #DES_TIPO").val($("#ret_DES_TIPO_" + index).val());
        $("#formulario #DES_RESTRICAO").val($("#ret_DES_RESTRICAO_" + index).val());
        $("#formulario #DAT_FIM").val($("#ret_DAT_FIM_" + index).val().split('-').reverse().join('/'));

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');

    }
</script>