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

        $cod_ecommerce = fnLimpaCampoZero($_REQUEST['COD_ECOMMERCE']);
        $cod_conface = fnLimpaCampoZero($_REQUEST['COD_CONFACE']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
        $cod_usuintegra = fnLimpaCampoZero($_REQUEST['COD_USUINTEGRA']);
        $cod_parcomu = fnLimpaCampoZero($_REQUEST['COD_PARCOMU']);
        $des_authkey = fnLimpaCampo($_REQUEST['DES_AUTHKEY']);
        $des_emailus = fnLimpaCampo($_REQUEST['DES_EMAILUS']);
        $des_usuario = fnLimpaCampo($_REQUEST['DES_USUARIO']);
        //$des_senhaus = fnLimpaCampo($_REQUEST['DES_SENHAUS']);
        $url_wsdl = fnLimpaCampo($_REQUEST['URL_WSDL']);
        $url = fnLimpaCampo($_REQUEST['URL']);
        //$log_ativo = fnLimpaCampo($_REQUEST['LOG_ATIVO']);
        if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
        $debug_ativo = fnLimpaCampo($_REQUEST['DEBUG_ATIVO']);
        $tipo_url = fnLimpaCampo($_REQUEST['TIPO_URL']);

        $countForm = $_REQUEST['COUNT'];
        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $array = array("");

        if ($opcao != '') {

                /*$sql = "CALL SP_ALTERA_CONFIGURACAO_ACESSO (
					 '" . $cod_conface . "', 
					 '" . $cod_empresa . "', 
                                         '" . $cod_parcomu . "', 
					 '',
					 '" . $des_emailus . "', 
					 '" . $des_senhaus . "', 
					 '', 
					 '', 
					 'SMS', 
					 0,
					 0,
					 '" . $opcao . "'    
						);";

                //fnEscreve($sql);
                mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());*/

                //mensagem de retorno
                switch ($opcao) {
                    case 'CAD':

                        $sql = "INSERT INTO SENHAS_ECOMMERCE(
                        COD_EMPRESA,
                        COD_USUINTEGRA,
                        COD_CONFACE,
                        COD_PARCOMU,
                        COD_UNIVEND,
                        DES_EMAILUS,
                        DES_USUARIO,
                        DES_AUTHKEY,
                        URL_WSDL,
                        URL,
                        LOG_ATIVO,
                        DEBUG_ATIVO,
                        TIPO_URL,
                        USU_CADASTR
                        ) VALUES(
                        $cod_empresa,
                        $cod_usuintegra,
                        $cod_conface,
                        $cod_parcomu,
                        $cod_univend,
                        '$des_emailus',
                        '$des_usuario',
                        '$des_authkey',
                        '$url_wsdl',
                        '$url',
                        '$log_ativo',
                        $debug_ativo,
                        $tipo_url,
                        $cod_usucada
                        )";

                        //fnEscreve($sql);
                        mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                        //fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());

                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

                        break;
                    case 'ALT':

                        $sql = "UPDATE SENHAS_ECOMMERCE SET
                        COD_EMPRESA=$cod_empresa,
                        COD_USUINTEGRA=$cod_usuintegra,
                        COD_CONFACE=$cod_conface,
                        COD_PARCOMU=$cod_parcomu,
                        COD_UNIVEND=$cod_univend,
                        DES_EMAILUS='$des_emailus',
                        DES_USUARIO='$des_usuario',
                        DES_AUTHKEY='$des_authkey',
                        URL_WSDL='$url_wsdl',
                        URL='$url',
                        LOG_ATIVO='$log_ativo',
                        DEBUG_ATIVO=$debug_ativo,
                        TIPO_URL=$tipo_url,
                        USU_CADASTR=$cod_usucada 
                        WHERE COD_ECOMMERCE = $cod_ecommerce";

                        //fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());
                        //fnEscreve($sql);
                        mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

                        break;
                    case 'EXC':

                        $sql = "";
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
                $abaComunica = 1319;
                include "abasSenhasComunicacao.php";
                ?>

                <div class="push30"></div> 			

                <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                    <fieldset>
                        <legend>Dados Gerais</legend> 

                        <div class="row">
                            <input type="hidden" class="form-control input-sm" name="COD_CONFACE" id="COD_CONFACE" value="<?php echo $cod_conface; ?>">
                            <input type="hidden" class="form-control input-sm" name="COD_ECOMMERCE" id="COD_ECOMMERCE" value="">

                            <div class="col-md-3">   
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
                                    <script>$("#formulario #COD_EMPRESA").val("<?php echo $cod_empresa; ?>").trigger("chosen:updated");</script>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Unidade</label>
                                    <div id="relUnivend">
                                        <select data-placeholder="Selecione uma unidade" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Parceiro Comunicação</label>
                                    <select data-placeholder="Selecione um parceiro" name="COD_PARCOMU" id="COD_PARCOMU" class="chosen-select-deselect" required>
                                        <option value=""></option>
                                        <?php
                                        $sql = "SELECT COD_PARCOMU, DES_PARCOMU FROM PARCEIRO_COMUNICACAO WHERE COD_TPCOM = 3 ORDER BY DES_PARCOMU ";
                                        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

                                        while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
                                            echo"<option value='" . $qrListaTipoEntidade['COD_PARCOMU'] . "'>" . $qrListaTipoEntidade['DES_PARCOMU'] . "</option>";
                                        }
                                        ?>  
                                    </select>   
                                    <script>$("#formulario #COD_PARCOMU").val("<?php echo $cod_parcomu; ?>").trigger("chosen:updated");</script>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Usuário</label>
                                    <div id="relUsuarios">
                                        <select data-placeholder="Selecione um usuário" name="COD_USUINTEGRA" id="COD_USUINTEGRA" class="chosen-select-deselect">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">E-mail Revenda</label>
                                    <input type="email" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" maxlength="100" value="<?php echo $des_emailus; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>  

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Senha Revenda</label>
                                    <input type="text" class="form-control input-sm" name="DES_AUTHKEY" id="DES_AUTHKEY" maxlength="250" value="<?php echo $des_authkey; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Usuário</label>
                                    <input type="text" class="form-control input-sm" name="DES_USUARIO" id="DES_USUARIO" maxlength="250" value="<?php echo $des_authkey; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div> 

                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">URL WSDL</label>
                                    <input type="text" class="form-control input-sm" name="URL_WSDL" id="URL_WSDL" maxlength="200" value="<?php echo $url_wsdl; ?>" required>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">URL</label>
                                    <input type="text" class="form-control input-sm" name="URL" id="URL" maxlength="200" value="<?php echo $url; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div> 

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Tipo de URL</label>
                                    <select data-placeholder="Selecione um tipo" name="TIPO_URL" id="TIPO_URL" class="chosen-select-deselect">
                                        <option value="0">...</option>
                                        <option value="1">SyncPedidoVenda</option>
                                        <option value="2">SyncUsuario</option> 
                                        <option value="3">SyncPedidoStatus</option> 
                                        <option value="4">contascorrentes</option> 
                                        <option value="5">vtex-lista</option> 
                                        <option value="6">vtex-pedidos</option> 
                                    </select>   
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Debug</label>
                                    <select data-placeholder="Selecione um parceiro" name="DEBUG_ATIVO" id="DEBUG_ATIVO" class="chosen-select-deselect">
                                        <option value="0">...</option>
                                        <option value="1">REQUEST HEADERS</option>
                                        <option value="2">REQUEST</option>
                                        <option value="3">RESPONSE</option>
                                        <option value="4">ALL</option>
                                        <option value="5">GET FUNCTIONS & GET TYPES</option> 
                                    </select>   
                                    <div class="help-block with-errors"></div>
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
                                        <th>Empresa</th>
                                        <th>E-mail</th>
                                        <th>Tipo de URL</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $sql = "SELECT SENHAS_ECOMMERCE.*,
                                                    EMPRESAS.NOM_EMPRESA 
                                           from SENHAS_ECOMMERCE
                                                   left join empresas ON SENHAS_ECOMMERCE.COD_EMPRESA = empresas.COD_EMPRESA
                                           order by COD_CONFACE";

                                    //fnEscreve($sql);
                                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());


                                    $count = 0;
                                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                        $count++;
                                        $tipo="";
                                        switch($qrBuscaModulos['TIPO_URL']){
                                            case 1:
                                                $tipo = "SyncPedidoVenda";
                                            break;
                                            case 2:
                                                $tipo = "SyncUsuario";
                                            break;
                                            case 3:
                                                $tipo = "SyncPedidoStatus";
                                            break;
                                            case 4:
                                                $tipo = "contascorrentes";
                                            break;
                                            case 5:
                                                $tipo = "vtex-lista";
                                            break;
                                            case 6:
                                                $tipo = "vtex-pedidos";
                                            break;
                                            default:
                                                $tipo = "Não Definido";
                                            break;
                                        }
                                        echo"
                                                <tr>
                                                  <td><input type='radio' name='radio1' onclick='buscaCombo(" . $qrBuscaModulos['COD_EMPRESA'] . "," . $count . ")'></th>
                                                  <td>" . $qrBuscaModulos['COD_ECOMMERCE'] . "</td>
                                                  <td>" . $qrBuscaModulos['NOM_EMPRESA'] . "</td>
                                                  <td>" . $qrBuscaModulos['DES_EMAILUS'] . "</td>
                                                  <td>" . $tipo . "</td>
                                                </tr>

                                                <input type='hidden' id='ret_COD_ECOMMERCE_" . $count . "' value='" . $qrBuscaModulos['COD_ECOMMERCE'] . "'>
                                                <input type='hidden' id='ret_COD_CONFACE_" . $count . "' value='" . $qrBuscaModulos['COD_CONFACE'] . "'>
                                                <input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $qrBuscaModulos['COD_EMPRESA'] . "'>
                                                <input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . $qrBuscaModulos['COD_UNIVEND'] . "'>
                                                <input type='hidden' id='ret_COD_PARCOMU_" . $count . "' value='" . $qrBuscaModulos['COD_PARCOMU'] . "'>
                                                    
                                                <input type='hidden' id='ret_DES_EMAILUS_" . $count . "' value='" . $qrBuscaModulos['DES_EMAILUS'] . "'>
                                                <input type='hidden' id='ret_DES_USUARIO_" . $count . "' value='" . $qrBuscaModulos['DES_USUARIO'] . "'>
                                            
                                                <input type='hidden' id='ret_DES_AUTHKEY_" . $count . "' value='" . $qrBuscaModulos['DES_AUTHKEY'] . "'>
                                                <input type='hidden' id='ret_URL_WSDL_" . $count . "' value='" . $qrBuscaModulos['URL_WSDL'] . "'>
                                                <input type='hidden' id='ret_URL_" . $count . "' value='" . $qrBuscaModulos['URL'] . "'>
                                                <input type='hidden' id='ret_DEBUG_ATIVO_" . $count . "' value='" . $qrBuscaModulos['DEBUG_ATIVO'] . "'>
                                                <input type='hidden' id='ret_TIPO_URL_" . $count . "' value='" . $qrBuscaModulos['TIPO_URL'] . "'>
                                                <input type='hidden' id='ret_COD_USUINTEGRA_" . $count . "' value='" . $qrBuscaModulos['COD_USUINTEGRA'] . "'>
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
        var idEmp = '<?php echo $cod_empresa; ?>';
        buscaCombo(idEmp,$('#COUNT').val());
    });

    $("#COD_EMPRESA").change(function() {

        var idEmp = $('#COD_EMPRESA').val();
        buscaCombo(idEmp,0);
        
    });

    function buscaCombo(idEmp,index){
        $.ajax({
            type: "POST",
            url: "ajxGerenciadorSenha.php",
            data: { ajxEmp:idEmp},
            beforeSend:function(){
                $('#relUsuarios').html('<div class="loading" style="width: 100%;"></div>');
                $('#relUnivend').html('<div class="loading" style="width: 100%;"></div>');
            },
            success:function(data){
                console.log(data);  
                $('#relUsuarios').html($('#relatorioUsu',data));
                $('#relScripts').html($('#relatorioScripts',data));
                $('#relUnivend').html($('#relatorioUni',data));
                if(index != 0){
                    retornaForm(index);
                }
                $(".chosen-select-deselect").chosen({allow_single_deselect:true});
            }
            // error:function(){
            //     $('#relatorioUsu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
            // }
        });
    }

</script>	