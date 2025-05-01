<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_tpunidades = fnLimpaCampoZero($_REQUEST['COD_TPUNIDADES']);
        $des_tpunidades = fnLimpaCampo($_REQUEST['DES_TPUNIDADES']);
        $cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $dat_cadastr = "NOW()";

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao == 'CAD') {
            $sql = "INSERT INTO TIP_UNIDADES (
                DES_TPUNIDADES,
                COD_EMPRESA,
                COD_USUCADA
                ) VALUES (
                '$des_tpunidades',
                '$cod_empresa',
                '$cod_usucada'
            )";

                mysqli_query(connTemp($cod_empresa,''),$sql);

                ?>

                <script type="text/javascript">
                    parent.location.reload();
                    parent.$('#popModal').modal('toggle');
                </script>

                <?php

            }else if($opcao == "ALT"){

                $sql = "UPDATE TIP_UNIDADES SET
                DES_TPUNIDADES = '$des_tpunidades',
                COD_ALTERAC = '$cod_usucada',
                DAT_ALTERAC = NOW()
                WHERE COD_TPUNIDADES = '$cod_tpunidades'";

                mysqli_query(connTemp($cod_empresa,''),$sql);


                ?>

                <script type="text/javascript">
                    parent.location.reload();
                    parent.$('#popModal').modal('toggle');
                </script>

                <?php

            }else if($opcao == "EXC"){

                $sql = "UPDATE TIP_UNIDADES SET 
                COD_EXCLUSA = '$cod_usucada',
                DAT_EXCLUSA = NOW()
                WHERE COD_TPUNIDADES = '$cod_tpunidades'";

                mysqli_query(connTemp($cod_empresa,''),$sql);
                
                ?>

                <script type="text/javascript">
                    parent.location.reload();
                    parent.$('#popModal').modal('toggle');
                </script>

                <?php
            }

            if ($cod_erro == 0 || $cod_erro == "") {
                $msgTipo = 'alert-success';
            } else {
                $msgTipo = 'alert-danger';
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
        }
    } else {
        $cod_empresa = 0;
        //fnEscreve('entrou else');
    }

    ?>

    <div class="push30"></div>

    <div class="row">

        <div class="col-md12 margin-bottom-30">
            <!-- Portlet -->
            <?php if ($popUp != "true") {  ?>
                <div class="portlet portlet-bordered">
                <?php } else { ?>
                    <div class="portlet" style="padding: 0 20px 20px 20px;">
                    <?php } ?>

                    <?php if ($popUp != "true") {  ?>
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fal fa-terminal"></i>
                                <span class="text-primary"><?php echo $NomePg; ?></span>
                            </div>
                        </div>

                    <?php } ?>

                    <div class="portlet-body">

                        <?php if ($msgRetorno <> '') { ?>
                            <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <?php echo $msgRetorno; ?>
                            </div>
                        <?php } ?>

                        <?php
                    //manu superior - empresas
                        if ($popUp != "true") {

                        //aba default
                            $abaEmpresa = 1963;

                        //menu abas
                            include "abasEmpresas.php";

                        }
                        ?>

                        <div class="push30"></div>

                        <div class="login-form">

                            <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                                <fieldset>
                                    <legend>Dados Gerais</legend>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label required">Código</label>
                                                <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TPUNIDADES" id="COD_TPUNIDADES" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label required">Descrição da Unidade</label>
                                                <input type="text" class="form-control input-sm" name="DES_TPUNIDADES" id="DES_TPUNIDADES" required>
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
                                <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

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
                                                    <th>Benfeitoria</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php

                                                $sql = "SELECT * FROM TIP_UNIDADES WHERE COD_EMPRESA = $cod_empresa AND DAT_EXCLUSA IS NULL";
                                                $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

                                                $count = 0;
                                                while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery)) {
                                                    $count++;

                                                    echo "
                                                    <tr>
                                                    <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
                                                    <td>".$qrBuscaCampanhaExtra['COD_TPUNIDADES']."</td>
                                                    <td>".$qrBuscaCampanhaExtra['DES_TPUNIDADES']."</td>                                                     
                                                    </tr>
                                                    <input type='hidden' id='ret_COD_TPUNIDADES_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_TPUNIDADES'] . "'>
                                                    <input type='hidden' id='ret_DES_TPUNIDADES_" . $count . "' value='" . $qrBuscaCampanhaExtra['DES_TPUNIDADES'] . "'>
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
                $("#formulario #COD_TPUNIDADES").val($("#ret_COD_TPUNIDADES_" + index).val());
                $("#formulario #DES_TPUNIDADES").val($("#ret_DES_TPUNIDADES_" + index).val());
                $('#formulario').validator('validate');
                $("#formulario #hHabilitado").val('S');
            }
        </script>