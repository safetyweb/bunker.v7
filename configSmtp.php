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

        $cod_smtp = fnLimpaCampoZero($_REQUEST['COD_SMTP']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $des_smtp = fnLimpaCampo($_REQUEST['DES_SMTP']);
        $des_port = fnLimpaCampo($_REQUEST['DES_PORT']);
        $DES_CERTIFICADO = fnLimpaCampo($_REQUEST['DES_CERTIFICADO']);
        $tip_debug = fnLimpaCampo($_REQUEST['TIP_DEBUG']);
        //$des_senhaus = fnLimpaCampo($_REQUEST['DES_SENHAUS']);
        $des_email = fnLimpaCampo($_REQUEST['DES_EMAIL']);
        $des_senha = fnLimpaCampo($_REQUEST['DES_SENHA']);
        //$log_ativo = fnLimpaCampo($_REQUEST['LOG_ATIVO']);
        if (empty($_REQUEST['LOG_VPE'])) {$log_vpe='false';}else{$log_vpe=$_REQUEST['LOG_VPE'];}
        if (empty($_REQUEST['LOG_VPEN'])) {$log_vpen='false';}else{$log_vpen=$_REQUEST['LOG_VPEN'];}
        if (empty($_REQUEST['LOG_SSGN'])) {$log_ssgn='false';}else{$log_ssgn=$_REQUEST['LOG_SSGN'];}
        if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}

        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $array = array("");

        if ($opcao != '') {

                //mensagem de retorno
                switch ($opcao) {
                    case 'CAD':

                        $sql = "INSERT INTO SENHAS_SMTP(
                                            COD_EMPRESA,
                                            DES_SMTP,
                                            DES_PORT,
                                            DES_CERTIFICADO,
                                            TIP_DEBUG,
                                            DES_EMAIL,
                                            DES_SENHA,
                                            LOG_VPE,
                                            LOG_VPEN,
                                            LOG_SSGN,
                                            LOG_ATIVO,
                                            COD_USUCADA
                                            ) VALUES(
                                            $cod_empresa,
                                            '$des_smtp',
                                            '$des_port',
                                            '$DES_CERTIFICADO',    
                                            '$tip_debug',
                                            '$des_email',
                                            '$des_senha',
                                            '$log_vpe',
                                            '$log_vpen',
                                            '$log_ssgn',
                                            '$log_ativo',
                                            $cod_usucada
                                            )";

                        //fnEscreve($sql);
                        mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                        //fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());

                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

                        break;
                    case 'ALT':

                        $sql = "UPDATE SENHAS_SMTP SET
                                        COD_EMPRESA=$cod_empresa,
                                        DES_SMTP='$des_smtp',
                                        DES_PORT='$des_port',
                                        DES_CERTIFICADO='$DES_CERTIFICADO',
                                        TIP_DEBUG='$tip_debug',
                                        DES_EMAIL='$des_email',
                                        DES_SENHA='$des_senha',
                                        LOG_VPE='$log_vpe',
                                        LOG_VPEN='$log_vpen',
                                        LOG_SSGN='$log_ssgn',
                                        LOG_ATIVO='$log_ativo',
                                        COD_USUCADA=$cod_usucada
                                WHERE COD_SMTP = $cod_smtp";

                        //fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());
                        //fnEscreve($sql);
                        mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

                        break;
                    case 'EXC':

                        $sql = "DELETE FROM SENHAS_SMTP WHERE COD_SMTP = $cod_smtp";
                        mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

                        $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

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
                $abaComunica = 1410;
                include "abasSenhasComunicacao.php";
                ?>

                <div class="push30"></div> 			

                <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                    <fieldset>
                        <legend>Dados Gerais</legend> 

                        <div class="row">

                            <div class="col-md-2">   
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Ativo</label> 
                                    <div class="push5"></div>
                                        <label class="switch">
                                        <input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S">
                                        <span></span>
                                        </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Empresa</label>
                                    <select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect" required>
                                        <option value=""></option>
                                        <?php
                                        $sql = "select COD_EMPRESA, NOM_EMPRESA from EMPRESAS order by NOM_EMPRESA ";
                                        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

                                        while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
                                            echo"<option value='" . $qrListaTipoEntidade['COD_EMPRESA'] . "'>" . $qrListaTipoEntidade['NOM_EMPRESA'] . "</option>";
                                        }
                                        ?>	
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Host SMTP</label>
                                    <input type="text" class="form-control input-sm" name="DES_SMTP" id="DES_SMTP" maxlength="100" value="">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>  

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Port</label>
                                    <input type="text" class="form-control input-sm" name="DES_PORT" id="DES_PORT" maxlength="250" value="">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Tipo Certificado</label>
                                    <input type="text" class="form-control input-sm" name="DES_CERTIFICADO" id="DES_CERTIFICADO" maxlength="250" value="">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Debug</label>
                                    <select data-placeholder="Selecione um tipo" name="TIP_DEBUG" id="TIP_DEBUG" class="chosen-select-deselect" required>
                                        <option value=""></option>
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>                                        
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Usuário Email</label>
                                    <input type="text" class="form-control input-sm" name="DES_EMAIL" id="DES_EMAIL" maxlength="200" value="">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Senha Email</label>
                                    <input type="text" class="form-control input-sm" name="DES_SENHA" id="DES_SENHA" maxlength="50" value="">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-2">   
                                <div class="form-group">
                                    <label for="inputName" class="control-label">verify_peer</label> 
                                    <div class="push5"></div>
                                        <label class="switch">
                                        <input type="checkbox" name="LOG_VPE" id="LOG_VPE" class="switch" value="true">
                                        <span></span>
                                        </label>
                                </div>
                            </div>

                            <div class="col-md-2">   
                                <div class="form-group">
                                    <label for="inputName" class="control-label">verify_peer_name</label> 
                                    <div class="push5"></div>
                                        <label class="switch">
                                        <input type="checkbox" name="LOG_VPEN" id="LOG_VPEN" class="switch" value="true">
                                        <span></span>
                                        </label>
                                </div>
                            </div>

                            <div class="col-md-2">   
                                <div class="form-group">
                                    <label for="inputName" class="control-label">allow_self_signed</label> 
                                    <div class="push5"></div>
                                        <label class="switch">
                                        <input type="checkbox" name="LOG_SSGN" id="LOG_SSGN" class="switch" value="true">
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
                    <input type="hidden" class="form-control input-sm" name="COD_SMTP" id="COD_SMTP" value="">
                    <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" /> 
                    <input type="hidden" name="COUNT" id="COUNT" value="<?php echo $countForm; ?>" />	
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
                                        <th class="text-center">Ativo</th>
                                        <th>Empresa</th>
                                        <th>Host</th>
                                        <th>Port</th>
                                        <th class="text-center">Debug</th>
                                        <th>Email</th>
                                        <th class="text-center">verify_peer</th>
                                        <th class="text-center">verify_peer_name</th>
                                        <th class="text-center">allow_self_signed</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $sql = "SELECT SS.*, EM.NOM_EMPRESA FROM SENHAS_SMTP SS 
                                            LEFT JOIN EMPRESAS EM ON SS.COD_EMPRESA = EM.COD_EMPRESA";

                                    //fnEscreve($sql);
                                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());


                                    $count = 0;
                                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                        $count++;
                                        $tipo="";

                                        if($qrBuscaModulos['LOG_ATIVO'] == "S"){$ativo = "<span class='fas fa-check text-success'></span>";}else{$ativo = "<span class='fas fa-times text-danger'></span>";}
                                        if($qrBuscaModulos['LOG_VPE'] == "true"){$vpe = "<span class='fas fa-check text-success'></span>";}else{$vpe = "<span class='fas fa-times text-danger'></span>";}
                                        if($qrBuscaModulos['LOG_VPEN'] == "true"){$vpen = "<span class='fas fa-check text-success'></span>";}else{$vpen = "<span class='fas fa-times text-danger'></span>";}
                                        if($qrBuscaModulos['LOG_SSGN'] == "true"){$ssgn = "<span class='fas fa-check text-success'></span>";}else{$ssgn = "<span class='fas fa-times text-danger'></span>";}
                                        // switch($qrBuscaModulos['TIPO_URL']){
                                        //     case 1:
                                        //         $tipo = "SyncPedidoVenda";
                                        //     break;
                                        //     case 2:
                                        //         $tipo = "SyncUsuario";
                                        //     break;
                                        //     case 3:
                                        //         $tipo = "SyncPedidoStatus";
                                        //     break;
                                        //     case 4:
                                        //         $tipo = "contascorrentes";
                                        //     break;
                                        //     case 5:
                                        //         $tipo = "vtex-lista";
                                        //     break;
                                        //     case 6:
                                        //         $tipo = "vtex-pedidos";
                                        //     break;
                                        //     default:
                                        //         $tipo = "Não Definido";
                                        //     break;
                                        // }
                                        echo"
                                                <tr>
                                                  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
                                                  <td>" . $qrBuscaModulos['COD_SMTP'] . "</td>
                                                  <td CLASS='text-center'>" . $ativo . "</td>
                                                  <td>" . $qrBuscaModulos['NOM_EMPRESA'] . "</td>
                                                  <td>" . $qrBuscaModulos['DES_SMTP'] . "</td>
                                                  <td>" . $qrBuscaModulos['DES_PORT'] . "</td>
                                                  <td CLASS='text-center'>" . $qrBuscaModulos['TIP_DEBUG'] . "</td>
                                                  <td>" . $qrBuscaModulos['DES_EMAIL'] . "</td>
                                                  <td CLASS='text-center'>" . $vpe . "</td>
                                                  <td CLASS='text-center'>" . $vpen . "</td>
                                                  <td CLASS='text-center'>" . $ssgn . "</td>
                                                </tr>

                                                <input type='hidden' id='ret_COD_SMTP_" . $count . "' value='" . $qrBuscaModulos['COD_SMTP'] . "'>
                                                <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBuscaModulos['COD_EMPRESA'] . "'>
                                                <input type='hidden' id='ret_DES_SMTP_" . $count . "' value='" . $qrBuscaModulos['DES_SMTP'] . "'>                                                    
                                                <input type='hidden' id='ret_DES_PORT_" . $count . "' value='" . $qrBuscaModulos['DES_PORT'] . "'>
                                                <input type='hidden' id='ret_TIP_DEBUG_" . $count . "' value='" . $qrBuscaModulos['TIP_DEBUG'] . "'>
                                                
                                                 <input type='hidden' id='ret_DES_CERTIFICADO_" . $count . "' value='" . $qrBuscaModulos['DES_CERTIFICADO'] . "'>
                                            
                                                <input type='hidden' id='ret_DES_EMAIL_" . $count . "' value='" . $qrBuscaModulos['DES_EMAIL'] . "'>
                                                <input type='hidden' id='ret_DES_SENHA_" . $count . "' value='" . $qrBuscaModulos['DES_SENHA'] . "'>
                                                <input type='hidden' id='ret_LOG_VPE_" . $count . "' value='" . $qrBuscaModulos['LOG_VPE'] . "'>
                                                <input type='hidden' id='ret_LOG_VPEN_" . $count . "' value='" . $qrBuscaModulos['LOG_VPEN'] . "'>
                                                <input type='hidden' id='ret_LOG_SSGN_" . $count . "' value='" . $qrBuscaModulos['LOG_SSGN'] . "'>
                                                <input type='hidden' id='ret_LOG_ATIVO_" . $count . "' value='" . $qrBuscaModulos['LOG_ATIVO'] . "'>
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

<div id="relScripts"></div>

<script type="text/javascript">

    $(document).ready(function(){
        
    });

    function retornaForm(index){
        $("#formulario #COD_SMTP").val($("#ret_COD_SMTP_"+index).val());
        $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val()).trigger("chosen:updated");
        $("#formulario #DES_SMTP").val($("#ret_DES_SMTP_"+index).val());
        $("#formulario #DES_PORT").val($("#ret_DES_PORT_"+index).val());
        $("#formulario #TIP_DEBUG").val($("#ret_TIP_DEBUG_"+index).val()).trigger("chosen:updated");
        $("#formulario #DES_EMAIL").val($("#ret_DES_EMAIL_"+index).val());
        $("#formulario #DES_CERTIFICADO").val($("#ret_DES_CERTIFICADO_"+index).val());
         $("#formulario #DES_SENHA").val($("#ret_DES_SENHA_"+index).val());
        if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
        if ($("#ret_LOG_VPE_"+index).val() == 'true'){$('#formulario #LOG_VPE').prop('checked', true);} 
        if ($("#ret_LOG_VPEN_"+index).val() == 'true'){$('#formulario #LOG_VPEN').prop('checked', true);} 
        if ($("#ret_LOG_SSGN_"+index).val() == 'true'){$('#formulario #LOG_SSGN').prop('checked', true);}

        $('#formulario').validator('validate');         
        $("#formulario #hHabilitado").val('S');
    }

</script>	