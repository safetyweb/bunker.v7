<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
    echo fnDebug('true');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
$hashLocal = "";
$cod_conta = "";
$msgRetorno = "";
$msgTipo = "";
$cod_conveni = "";
$cod_cliente = "";
$cod_entidad = "";
$num_banco = "";
$num_agencia = "";
$num_contaco = "";
$nom_banco = "";
$num_pix = "";
$tip_pix = "";
$log_default = "";
$Arr_COD_PROPRIEDADE = "";
$Arr_COD_MULTEMP = "";
$i = "";
$cod_propriedade = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$arrayProc = [];
$cod_erro = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$nom_usuarioSESSION = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$popUp = "";
$sqlHotel = "";
$arrayHotel = [];
$qrHotel = "";
$qrBuscaModulos = "";
$pix = "";
$tem_unive = "";
$default = "";


// echo fnDebug('true');

$hashLocal = mt_rand();

$cod_conta = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(serialize($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_conta = fnLimpaCampoZero(@$_REQUEST['COD_CONTA']);
        $cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
        $cod_conveni = fnLimpaCampoZero(@$_REQUEST['COD_CONVENI']);
        $cod_cliente = fnLimpaCampoZero(@$_REQUEST['COD_CLIENTE']);
        $cod_entidad = fnLimpaCampoZero(@$_REQUEST['COD_ENTIDAD']);
        $num_banco = fnLimpaCampo(@$_REQUEST['NUM_BANCO']);
        $num_agencia = fnLimpaCampoZero(@$_REQUEST['NUM_AGENCIA']);
        $num_contaco = fnLimpaCampo(@$_REQUEST['NUM_CONTACO']);
        $nom_banco = fnLimpaCampo(@$_REQUEST['NOM_BANCO']);
        $num_pix = fnLimpaCampo(@$_REQUEST['NUM_PIX']);
        $tip_pix = fnLimpaCampoZero(@$_REQUEST['TIP_PIX']);

        if (empty(@$_REQUEST['LOG_DEFAULT'])) {
            $log_default = 'N';
        } else {
            $log_default = @$_REQUEST['LOG_DEFAULT'];
        }

        //array das unidades de venda
        if (isset($_POST['COD_PROPRIEDADE'])) {
            $Arr_COD_PROPRIEDADE = $_POST['COD_PROPRIEDADE'];
            //print_r($Arr_COD_MULTEMP);

            for ($i = 0; $i < count($Arr_COD_PROPRIEDADE); $i++) {
                $cod_propriedade = $cod_propriedade . $Arr_COD_PROPRIEDADE[$i] . ",";
            }

            $cod_propriedade = substr($cod_propriedade, 0, -1);
        } else {
            $cod_propriedade = "0";
        }

        $cod_propriedade = str_replace("Array", "", $cod_propriedade);

        $opcao = @$_REQUEST['opcao'];
        $hHabilitado = @$_REQUEST['hHabilitado'];
        $hashForm = @$_REQUEST['hashForm'];

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];

        if ($opcao != '' && $opcao != 0) {




            // mysqli_query(connTemp($cod_empresa,''),$sql);				

            //mensagem de retorno
            switch ($opcao) {
                case 'CAD':

                    $sql = "INSERT INTO CONTABANCARIA(
                    								COD_EMPRESA,
                    								COD_CONVENI,
                    								COD_ENTIDAD,
                    								NUM_BANCO,
                    								NUM_AGENCIA,
                    								NUM_CONTACO,
                    								NUM_PIX,
                    								TIP_PIX,
                                                    NOM_BANCO,
                                                    LOG_DEFAULT,
                                                    COD_PROPRIEDADE,
                    								COD_USUCADA
                    							) VALUES(
                    								$cod_empresa,
                    								$cod_conveni,
                    								$cod_entidad,
                    								'$num_banco',
                    								'$num_agencia',
                    								'$num_contaco',
                    								'$num_pix',
                    								'$tip_pix',
                    								'$nom_banco',
                    								'$log_default',
                    								'$cod_propriedade',
                    								$cod_usucada
                    						)";

                    // $sql = "CALL SP_ALTERA_CONTABANCARIA (
                    //     '" . $cod_conta . "', 
                    //     '" . $cod_empresa . "',
                    //     '" . $cod_entidad . "', 
                    //     '" . $cod_conveni . "', 
                    //     '" . $cod_cliente . "',
                    //     '" . $num_banco . "',
                    //     '" . $num_agencia . "',
                    //     '" . $num_contaco . "',
                    //     '" . $nom_banco . "',
                    //     '" . $opcao . "'    
                    //     );";

                    $arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);

                    if (!$arrayProc) {
                        $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                    }

                    break;
                case 'ALT':

                    $sql = "UPDATE CONTABANCARIA SET
										COD_ENTIDAD = $cod_entidad,
										NUM_BANCO = '$num_banco',
										NUM_AGENCIA = '$num_agencia',
										NUM_CONTACO = '$num_contaco',
										NUM_PIX = '$num_pix',
										TIP_PIX = '$tip_pix',
										NOM_BANCO = '$nom_banco',
										COD_ALTERAC = $cod_usucada,
										LOG_DEFAULT = '$log_default',
                                        COD_PROPRIEDADE = '$cod_propriedade',
										DAT_ALTERAC = NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CONTA = $cod_conta";

                    $arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);

                    if (!$arrayProc) {
                        $cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível alterar o registro : $cod_erro";
                    }

                    break;
                case 'EXC':

                    $sql = "UPDATE CONTABANCARIA
										COD_EXCLUSA = $cod_usucada,
										DAT_EXCLUSA = NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CONTA = $cod_conta";

                    // fnEscreve($sql);

                    // mysqli_query(connTemp($cod_empresa,''),$sql);

                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

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

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {

    //busca dados da empresa
    $cod_empresa = fnDecode(@$_GET['id']);
    $sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

    //fnEscreve($sql);
    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($qrBuscaEmpresa)) {
        $nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
    }
} else {
    $nom_empresa = "";
}

?>

<?php if ($popUp != "true") {  ?>
    <div class="push30"></div>
<?php } ?>

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
                            <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                        </div>
                        <?php include "atalhosPortlet.php"; ?>
                    </div>
                <?php } ?>
                <div class="portlet-body">

                    <?php if ($msgRetorno <> '') { ?>
                        <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $msgRetorno; ?>
                        </div>
                    <?php } ?>


                    <div class="push30"></div>

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CONTA" id="COD_CONTA">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Conta Padrão</label>
                                        <div class="push5"></div>
                                        <label class="switch">
                                            <input type="checkbox" name="LOG_DEFAULT" id="LOG_DEFAULT" class="switch" value="S">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Número Banco</label>
                                        <input type="text" class="form-control input-sm int" name="NUM_BANCO" id="NUM_BANCO" value="" maxlength="6" data-mask-reverse="true" maxlength="11" required>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Banco</label>
                                        <input type="text" class="form-control input-sm " name="NOM_BANCO" id="NOM_BANCO" value="" maxlength="60">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Agência</label>
                                        <input type="text" class="form-control input-sm" name="NUM_AGENCIA" id="NUM_AGENCIA" value="" maxlength="5">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Conta Corrente</label>
                                        <input type="text" class="form-control input-sm" name="NUM_CONTACO" id="NUM_CONTACO" value="" maxlength="10">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">PIX</label>
                                        <input type="text" class="form-control input-sm" name="NUM_PIX" id="NUM_PIX" value="" maxlength="100">
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Tipo de Pix</label>
                                        <select data-placeholder="Selecione o tipo do PIX" name="TIP_PIX" id="TIP_PIX" class="chosen-select-deselect">
                                            <option></option>
                                            <option value="3">CPF/CNPJ</option>
                                            <option value="1">Celular</option>
                                            <option value="2">Email</option>
                                            <option value="4">Chave Aleatória</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Propriedades</label>
                                        <select data-placeholder="Selecione a Propriedade" name="COD_PROPRIEDADE[]" id="COD_PROPRIEDADE" class="chosen-select-deselect" multiple required>
                                            <option value="9999">Todas Propriedades</option>
                                            <?php
                                            $sqlHotel = "SELECT COD_EXTERNO, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
                                            $arrayHotel = mysqli_query(connTemp($cod_empresa, ''), $sqlHotel);

                                            while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
                                            ?>
                                                <option value="<?= $qrHotel['COD_EXTERNO'] ?>"><?= $qrHotel['NOM_FANTASI'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
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
                            <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

                        </div>

                        <input type="hidden" name="opcao" id="opcao" value="">
                        <input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?= $cod_conveni ?>">
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
                        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
                        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

                        <div class="push5"></div>

                    </form>
                </div>
                </div>
            </div>

            <div class="push5"></div>

            <div class="portlet portlet-bordered">

                <div class="portlet-body">

                    <div class="login-form">

                        <div class="col-lg-12">

                            <div class="no-more-tables">

                                <form name="formLista">

                                    <table class="table table-bordered table-striped table-hover tablesorter buscavel">
                                        <thead>
                                            <tr>
                                                <th width="40"></th>
                                                <th>Código</th>
                                                <th>Conta Padrão</th>
                                                <th>Banco</th>
                                                <th>Agência</th>
                                                <th>Conta Corrente</th>
                                                <th>Chave Pix</th>
                                                <th>Pix</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $sql = "SELECT CONTABANCARIA.COD_CONTA,
												 CONTABANCARIA.COD_EMPRESA,
												 CONTABANCARIA.COD_CLIENTE,
												 CONTABANCARIA.NOM_BANCO,
												 CONTABANCARIA.NUM_BANCO,
												 CONTABANCARIA.NUM_AGENCIA,
												 CONTABANCARIA.NUM_CONTACO,
                                                 CONTABANCARIA.COD_PROPRIEDADE,
												 CONTABANCARIA.NUM_PIX,
                                                 CONTABANCARIA.LOG_DEFAULT,
												 CONTABANCARIA.TIP_PIX
											from CONTABANCARIA 
										    where CONTABANCARIA.COD_EMPRESA =  $cod_empresa";


                                            $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

                                            $count = 0;
                                            while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                                $count++;

                                                $tip_pix = $qrBuscaModulos['TIP_PIX'];

                                                switch ($tip_pix) {
                                                    case "1":
                                                        $pix = "Celular";
                                                        break;
                                                    case "2":
                                                        $pix = "Email";
                                                        break;
                                                    case "3":
                                                        $pix = "CPF/CNPJ";
                                                        break;
                                                    case "4":
                                                        $pix = "Chave Aleatória";
                                                        break;
                                                    default:
                                                        $pix = "";
                                                }

                                                if (!empty($qrBuscaModulos['COD_PROPRIEDADE'])) {
                                                    $tem_unive = "sim";
                                                } else {
                                                    $tem_unive = "nao";
                                                }

                                                if ($qrBuscaModulos['LOG_DEFAULT'] == 'S') {
                                                    $default = "<span class='fal fa-check text-success'></span>";
                                                } else {
                                                    $default = "";
                                                }

                                                fnEscreveArray($qrBuscaModulos);

                                                echo "
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
											  <td>" . $qrBuscaModulos['COD_CONTA'] . "</td>
											  <td>" . $default . "</td>
											  <td>" . $qrBuscaModulos['NOM_BANCO'] . "</td>
											  <td>" . $qrBuscaModulos['NUM_AGENCIA'] . "</td>
											  <td>" . $qrBuscaModulos['NUM_CONTACO'] . "</td>
											  <td>" . $pix . "</td>
											  <td>" . $qrBuscaModulos['NUM_PIX'] . "</td>
											</tr>
											
											<input type='hidden' id='ret_COD_CONTA_" . $count . "' value='" . $qrBuscaModulos['COD_CONTA'] . "'>
											<input type='hidden' id='ret_COD_ENTIDAD_" . $count . "' value='" . $qrBuscaModulos['COD_ENTIDAD'] . "'>
											<input type='hidden' id='ret_NUM_BANCO_" . $count . "' value='" . $qrBuscaModulos['NUM_BANCO'] . "'>
											<input type='hidden' id='ret_NUM_AGENCIA_" . $count . "' value='" . $qrBuscaModulos['NUM_AGENCIA'] . "'>
											<input type='hidden' id='ret_NUM_CONTACO_" . $count . "' value='" . $qrBuscaModulos['NUM_CONTACO'] . "'>
											<input type='hidden' id='ret_NUM_PIX_" . $count . "' value='" . $qrBuscaModulos['NUM_PIX'] . "'>
											<input type='hidden' id='ret_TIP_PIX_" . $count . "' value='" . $qrBuscaModulos['TIP_PIX'] . "'>
                                            <input type='hidden' id='ret_TEM_UNIVE_" . $count . "' value='" . $tem_unive . "'>
											<input type='hidden' id='ret_NOM_BANCO_" . $count . "' value='" . $qrBuscaModulos['NOM_BANCO'] . "'>
											<input type='hidden' id='ret_COD_PROPRIEDADE_" . $count . "' value='" . $qrBuscaModulos['COD_PROPRIEDADE'] . "'>
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

        $("#formulario #COD_CONTA").val($("#ret_COD_CONTA_" + index).val());
        $("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_" + index).val()).trigger('chosen:updated');
        $("#formulario #NUM_BANCO").val($("#ret_NUM_BANCO_" + index).val());
        $("#formulario #NUM_AGENCIA").val($("#ret_NUM_AGENCIA_" + index).val());
        $("#formulario #NUM_CONTACO").val($("#ret_NUM_CONTACO_" + index).val());
        $("#formulario #NOM_BANCO").val($("#ret_NOM_BANCO_" + index).val());
        $("#formulario #NUM_PIX").val($("#ret_NUM_PIX_" + index).val());
        $("#formulario #TIP_PIX").val($("#ret_TIP_PIX_" + index).val()).trigger('chosen:updated');

        //retorno combo multiplo - lojas
        $("#formulario #COD_PROPRIEDADE").val('').trigger("chosen:updated");
        if ($("#ret_TEM_UNIVE_" + index).val() == "sim") {
            var sistemasUni = $("#ret_COD_PROPRIEDADE_" + index).val();
            var sistemasUniArr = sistemasUni.split(',');
            //opções multiplas
            for (var i = 0; i < sistemasUniArr.length; i++) {
                $("#formulario #COD_PROPRIEDADE option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
            }
            $("#formulario #COD_PROPRIEDADE").trigger("chosen:updated");
        } else {
            $("#formulario #COD_PROPRIEDADE").val('').trigger("chosen:updated");
        }

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');

    }
</script>