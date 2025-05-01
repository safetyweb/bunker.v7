<?php
//echo fnDebug('true');

$cod_conface = 0;
$cod_empresa = 0;
$des_emailus = "";
$des_senhaus = "";
$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_parcomu = fnLimpaCampoZero($_REQUEST['COD_PARCOMU']);
        $des_parcomu = fnLimpaCampo($_REQUEST['DES_PARCOMU']);
        $cod_tpcom = fnLimpaCampoZero($_REQUEST['COD_TPCOM']);
        $log_temapi = fnLimpaCampo($_REQUEST['LOG_TEMAPI']);
        if (empty($_REQUEST['LOG_TEMAPI'])) {
            $log_temapi = 'N';
        } else {
            $log_temapi = $_REQUEST['LOG_TEMAPI'];
        }

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $array = array("");

        if ($opcao != '') {

            $sql = "CALL SP_ALTERA_PARCEIRO_COMUNICACAO (
                     '" . $cod_parcomu . "', 
					 '" . $des_parcomu . "', 
                     '" . $log_temapi . "', 
					 '" . $cod_tpcom . "', 
					 '" . $opcao . "'    
						);";

            //fnEscreve($sql);
            mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

            //fnMostraForm();

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':
                    $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    break;
                case 'ALT':
                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    break;
                case 'EXC':
                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                    break;
                    break;
            }
            $msgTipo = 'alert-success';
        }
    }
}

//fnMostraForm();
//fnEscreve($cod_empresa);
?>

<div class="push30"></div> 

<div class="row">				

    <div class="col-md12 margin-bottom-30">
        <!-- Portlet -->
        <div class="portlet portlet-bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="glyphicon glyphicon-calendar"></i>
                    <span class="text-primary"><?php echo $NomePg; ?></span>
                </div>
                <?php include "atalhosPortlet.php"; ?>
            </div>
            <div class="portlet-body">

                <?php if ($msgRetorno <> '') { ?>	
                    <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo $msgRetorno; ?>
                    </div>
                <?php } ?>

                <?php
                //menu senhas comunicação
                $abaComunica = 1317;
                include "abasSenhasComunicacao.php";
                ?>

                <div class="push30"></div> 			

                <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                    <fieldset>
                        <legend>Dados Gerais</legend> 

                        <div class="row">

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="COD_PARCOMU" class="control-label">Código</label>
                                    <input type="text" class="form-control input-sm" readonly name="COD_PARCOMU" id="COD_PARCOMU" maxlength="100" value="<?php echo $cod_parcomu; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div> 

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="DES_PARCOMU" class="control-label">Descrição</label>
                                    <input type="text" class="form-control input-sm" name="DES_PARCOMU" id="DES_PARCOMU" maxlength="100" value="<?php echo $des_parcomu; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div> 

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Tipo da Comunicação</label>
                                        <div id="relatorioSis">
                                            <select data-placeholder="Selecione um Tipo" name="COD_TPCOM" id="COD_TPCOM" class="chosen-select-deselect requiredChk" required>
                                                <?php 

                                                    $sql = "SELECT * FROM TIPO_COMUNICACAO";

                                                    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);

                                                    while($qrCom = mysqli_fetch_assoc($arrayQuery)){
                                                        ?>

                                                        <option value="<?php echo $qrCom['COD_TPCOM']; ?>"><?php echo $qrCom['DES_TPCOM']; ?></option>

                                                        <?php 
                                                    }

                                                ?>
                                            </select>
                                        </div>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-2">   
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Possui API própria?</label> 
                                    <div class="push5"></div>
                                    <label class="switch">
                                        <input type="checkbox" name="LOG_TEMAPI" id="LOG_TEMAPI" class="switch" value="N">
                                        <span></span>
                                    </label>
                                </div>
                            </div> 

                        </div>

                        <div class="push10"></div>
                    </fieldset>


                    <div class="push10"></div>
                    <hr>	
                    <div class="form-group text-right col-lg-12">
                        <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
                        <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
                        <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
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
                                        <th width="40"></th>
                                        <th>Código</th>
                                        <th>Descrição</th>
                                        <th>Tipo</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $sql = "SELECT * FROM PARCEIRO_COMUNICACAO PC 
                                    LEFT JOIN TIPO_COMUNICACAO TC ON PC.COD_TPCOM = TC.COD_TPCOM
                                    ORDER BY DES_PARCOMU";

                                    //fnEscreve($sql);
                                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

                                    $count = 0;
                                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                        $count++;
                                        echo"
                                                <tr>
                                                  <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                                  <td>" . $qrBuscaModulos['COD_PARCOMU'] . "</td>
                                                  <td>" . $qrBuscaModulos['DES_PARCOMU'] . "</td>
                                                  <td>" . $qrBuscaModulos['DES_TPCOM'] . "</td>
                                                </tr>

                                                <input type='hidden' id='ret_COD_PARCOMU_" . $count . "' value='" . $qrBuscaModulos['COD_PARCOMU'] . "'>
                                                <input type='hidden' id='ret_DES_PARCOMU_" . $count . "' value='" . $qrBuscaModulos['DES_PARCOMU'] . "'>
                                                <input type='hidden' id='ret_LOG_TEMAPI_".$count."' value='".$qrBuscaModulos['LOG_TEMAPI']."'>
                                                <input type='hidden' id='ret_COD_TPCOM_".$count."' value='".$qrBuscaModulos['COD_TPCOM']."'>
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
        $("#formulario #COD_PARCOMU").val($("#ret_COD_PARCOMU_" + index).val());
        $("#formulario #DES_PARCOMU").val($("#ret_DES_PARCOMU_" + index).val());
        $("#formulario #COD_TPCOM").val($("#ret_COD_TPCOM_" + index).val()).trigger("chosen:updated");

        if ($("#ret_LOG_TEMAPI_" + index).val() == 'S') {
            $('#formulario #LOG_TEMAPI').prop('checked', true);
        } else {
            $('#formulario #LOG_TEMAPI').prop('checked', false);
        }
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }

</script>	