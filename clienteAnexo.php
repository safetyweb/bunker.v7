<?php

// echo fnDebug('true');

$hashLocal = mt_rand();

$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request']  = $request;

        $cod_anexo = fnLimpaCampoZero($_REQUEST['COD_ANEXO']);
        $cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
        $tip_doc = fnLimpaCampo($_REQUEST['TIP_DOC']);
        $des_doc = fnLimpaCampo($_REQUEST['DES_DOC']);
        $num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);

        $nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $MODULO = $_GET['mod'];
        $COD_MODULO = fndecode($_GET['mod']);

        $conn = conntemp($cod_empresa,"");

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        if ($opcao != '') {


            //mensagem de retorno
            switch ($opcao) {

                case 'CAD':

                    $sql = "INSERT INTO ANEXO_DOCUMENTO(
                                            COD_EMPRESA,
                                            COD_BEM,
                                            COD_CLIENTE,
                                            TIP_DOC,
                                            DES_DOC,
                                            COD_USUCADA
                                        ) VALUES(
                                            $cod_empresa,
                                            $cod_bem,
                                            $cod_cliente,
                                            '$tip_doc',
                                            '$des_doc',
                                            $cod_usucada    
                                        )";

                    // fnEscreve2($sql);

                    $arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

                    if (!$arrayProc) {

                        $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
                    }

                    if($cod_recebim == 0){

                        $sqlCod = "SELECT MAX(COD_ANEXO) COD_ANEXO FROM ANEXO_DOCUMENTO WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $cod_usucada";
                        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
                        $qrCod = mysqli_fetch_assoc($arrayQuery);
                        $cod_anexo = $qrCod[COD_ANEXO];

                        $sqlArquivos = "SELECT 1 FROM ANEXO_DOC WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
                        // fnEscreve($sqlArquivos);
                        $arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

                        if(mysqli_num_rows($arrayCont) > 0){
                            $sqlUpd = "UPDATE ANEXO_DOC SET COD_ANEXO = $cod_anexo, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
                            mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
                        }

                    }else{
                        // $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_LICITAC = $cod_licitac AND LOG_STATUS = 'N'";
                        // mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                    }

                break;

                case 'ALT':

                    $sql = "UPDATE ANEXO_DOCUMENTO SET
                                            TIP_DOC = '$tip_doc',
                                            DES_DOC = '$des_doc',
                                            COD_ALTERAC = $cod_usucada,
                                            DAT_ALTERAC = NOW()
                            WHERE COD_EMPRESA = $cod_empresa 
                            AND COD_ANEXO = $cod_anexo";

                    // fnEscreve($sql);

                    $arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

                    if (!$arrayProc) {

                        $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível alterar o registro : $cod_erro";
                    }

                break;

                case 'EXC':

                    $sql = "UPDATE ANEXO_DOCUMENTO SET
                                            COD_EXCLUSA = $cod_usucada,
                                            DAT_EXCLUSA = NOW()
                            WHERE COD_EMPRESA = $cod_empresa 
                            AND COD_ANEXO = $cod_anexo";

                    // fnEscreve($sql);

                    $arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

                    if (!$arrayProc) {

                        $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
                    }

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


//busca dados da url    
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
    $cod_empresa = fnDecode($_GET['id']);
    $cod_cliente = fnDecode($_GET['idC']);
    $cod_bem = fnLimpacampoZero(fnDecode($_GET['idBem']));
    $sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = $cod_empresa";
    fnEscreve($sql);
    //echo($sql);
    $arrayQuery = mysqli_query($adm, $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

    if (isset($arrayQuery)) {
        $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
        $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
    }
} else {
    $cod_empresa = 0;
    //fnEscreve('entrou else');
}
$conn = conntemp($cod_empresa,"");

//fnMostraForm();

$tp_cont = 'Anexo de Documentos';
$tp_anexo = 'COD_ANEXO';
$cod_tpanexo = 'COD_ANEXO';
$cod_busca = $cod_anexo;

$sqlUpdtCont = "DELETE FROM ANEXO_DOC WHERE COD_EMPRESA = $cod_empresa AND COD_ANEXO = 0 AND LOG_STATUS = 'N'";
mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";

// fnEscreve($sqlCont);
$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCont));
$num_contador = $qrCont['NUM_CONTADOR'];

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
                //menu superior - cliente
                $abaEmpresa = 1020; 
                $abaCli = 1976; 
                // echo $_SESSION["SYS_COD_SISTEMA"];                               
                switch ($_SESSION["SYS_COD_SISTEMA"]) {
                    case 14: //rede duque
                    include "abasClienteDuque.php";
                    break;
                    case 13: //sh manager
                    include "abasIntegradoraCli.php";
                    break;
                    case 18: //mais cash
                    include "abasMaisCashCli.php";
                    case 21: //gestão garantias
                    include "abasGestaoGarantiasCli.php";
                    break;
                    default;                                            
                    include "abasClienteConfig.php";
                    break;
                }

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

                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                        <?php include "bensHeader.php"; ?>

                        <fieldset>
                            <legend>Dados Gerais</legend>

                            <div class="row">
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Tipo de Documento</label>

                                        <select data-placeholder="Selecione um tipo" name="TIP_DOC" id="TIP_DOC" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
                                            <option value=""></option>
                                            <option value="RG">RG</option>
                                            <option value="CPF">CPF</option>
                                            <option value="CON">Contrato</option>
                                            <option value="END">Comprovante de Endereço</option>
                                            <option value="RCB">Recibo</option>
                                        </select>
                                        
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <!-- <div class="col-md-3">
                                    <label for="inputName" class="control-label required">Documento</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_DOC" extensao="all"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
                                        </span>
                                        <input type="text" name="DES_DOC" id="DES_DOC" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100">
                                    </div>
                                    <span class="help-block">Até 2mb</span>
                                </div> -->

                            </div>

                                
                            <div class="push10"></div>

                            <?php include "uploadDocumentos.php"; ?>
                            
                            <div class="push10"></div>


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
                        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
                        <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente ?>">
                        <input type="hidden" name="COD_BEM" id="COD_BEM" value="<?php echo $cod_bem ?>">
                        <input type="hidden" name="COD_ANEXO" id="COD_ANEXO" value="">
                        <input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
                        <input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />

                        <div class="push5"></div>

                    </form>

                    <div class="push50"></div>

                    <div class="col-lg-12">

                        <div class="no-more-tables">

                            <form name="formLista">

                                <table class="table table-bordered table-striped table-hover tableSorter">
                                    <thead>
                                        <tr>
                                            <th class="{ sorter: false }" width="40"></th>
                                            <th>Código</th>
                                            <th>Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php

                                        $sql = "SELECT * FROM ANEXO_DOCUMENTO 
                                                WHERE COD_EMPRESA = $cod_empresa 
                                                AND COD_CLIENTE = $cod_cliente
                                                AND COD_BEM = 0
                                                AND COD_EXCLUSA IS NULL";
                                        $arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);

                                        $count = 0;
                                        while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                            $count++;
                                            echo "
                                                    <tr>
                                                        <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
                                                        <td>" . $qrBuscaModulos['COD_ANEXO'] . "</td>
                                                        <td>" . $qrBuscaModulos['TIP_DOC'] . "</td>
                                                    </tr>
                                                    <input type='hidden' id='ret_COD_ANEXO_" . $count . "' value='" . $qrBuscaModulos['COD_ANEXO'] . "'>
                                                    <input type='hidden' id='ret_DES_DOC_" . $count . "' value='" . $qrBuscaModulos['DES_DOC'] . "'>
                                                    <input type='hidden' id='ret_TIP_DOC_" . $count . "' value='" . $qrBuscaModulos['TIP_DOC'] . "'>
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
    function retornaForm(index) {
        $("#formulario #COD_ANEXO").val($("#ret_COD_ANEXO_" + index).val());
        $("#formulario #COD_OBJETOANEXO").val($("#ret_COD_ANEXO_" + index).val());
        $("#formulario #DES_DOC").val($("#ret_DES_DOC_" + index).val());
        $("#formulario #TIP_DOC").val($("#ret_TIP_DOC_" + index).val()).trigger("chosen:updated");
        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');

        refreshUpload();
    }
</script>