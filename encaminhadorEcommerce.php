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

        $cod_encaminha = fnLimpaCampoZero($_REQUEST['COD_ENCAMINHA']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $cod_parcomu = fnLimpaCampoZero($_REQUEST['COD_PARCOMU']);
        $des_authkey = fnLimpaCampo($_REQUEST['DES_AUTHKEY']);
        $debug_ativo = fnLimpaCampoZero($_REQUEST['DEBUG_ATIVO']);
        $url_wsdl_integra = fnLimpaCampo($_REQUEST['URL_WSDL_INTEGRA']);
        $url_integra = fnLimpaCampo($_REQUEST['URL_INTEGRA']);
        $tipo_url_integra = fnLimpaCampoZero($_REQUEST['TIPO_URL_INTEGRA']);

        $url_wsdl = fnLimpaCampo($_REQUEST['URL_WSDL']);
        $url = fnLimpaCampo($_REQUEST['URL']);
        $tipo_url = fnLimpaCampoZero($_REQUEST['TIPO_URL']);
        $des_login = fnLimpaCampo($_REQUEST['DES_LOGIN']);
        $des_senha = fnLimpaCampo($_REQUEST['DES_SENHA']);
        $cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
        $cod_pdv = fnLimpaCampoZero($_REQUEST['COD_PDV']);
        $cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
        $cod_vendedor = fnLimpaCampoZero($_REQUEST['COD_VENDEDOR']);
        $nom_vendedor = fnLimpaCampo($_REQUEST['NOM_VENDEDOR']);

        if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}

        $countForm = $_REQUEST['COUNT'];
        $opcao = $_REQUEST['opcao'];
        $hHabilitado = $_REQUEST['hHabilitado'];
        $hashForm = $_REQUEST['hashForm'];

        $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
        $array = array("");

        if ($opcao != '') {

                //mensagem de retorno
                switch ($opcao) {
                    case 'CAD':

                        $sql = "INSERT INTO ENCAMINHA_ECOMMERCE(
                                            COD_EMPRESA,
                                            COD_PARCOMU,
                                            DES_AUTHKEY,
                                            DEBUG_ATIVO,
                                            URL_WSDL_INTEGRA,
                                            URL_INTEGRA,
                                            TIPO_URL_INTEGRA,
                                            URL_WSDL,
                                            URL,
                                            TIPO_URL,
                                            DES_LOGIN,
                                            DES_SENHA,
                                            COD_UNIVEND,
                                            COD_PDV,
                                            COD_CLIENTE,
                                            COD_VENDEDOR,
                                            NOM_VENDEDOR,
                                            LOG_ATIVO
                                            ) VALUES(
                                            $cod_empresa,
                                            $cod_parcomu,
                                            '$des_authkey',
                                            $debug_ativo,
                                            '$url_wsdl_integra',
                                            '$url_integra',
                                            $tipo_url_integra,
                                            '$url_wsdl',
                                            '$url',
                                            $tipo_url,
                                            '$des_login',
                                            '$des_senha',
                                            $cod_univend,
                                            $cod_pdv,
                                            $cod_cliente,
                                            $cod_vendedor,
                                            '$nom_vendedor',
                                            '$log_ativo' 
                                            )";

                        //fnEscreve($sql);
                        mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                        //fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());

                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

                        break;
                    case 'ALT':

                        $sql = "UPDATE ENCAMINHA_ECOMMERCE SET
                                        COD_EMPRESA='$cod_empresa',
                                        COD_PARCOMU='$cod_parcomu',
                                        DES_AUTHKEY='$des_authkey',
                                        DEBUG_ATIVO='$debug_ativo',
                                        URL_WSDL_INTEGRA='$url_wsdl_integra',
                                        URL_INTEGRA='$url_integra',
                                        TIPO_URL_INTEGRA='$tipo_url_integra',
                                        URL_WSDL='$url_wsdl',
                                        URL='$url',
                                        TIPO_URL='$tipo_url',
                                        DES_LOGIN='$des_login',
                                        DES_SENHA='$des_senha',
                                        COD_UNIVEND='$cod_univend',
                                        COD_PDV='$cod_pdv',
                                        COD_CLIENTE='$cod_cliente',
                                        COD_VENDEDOR='$cod_vendedor',
                                        NOM_VENDEDOR='$nom_vendedor',
                                        LOG_ATIVO='$log_ativo' 
                                WHERE COD_ENCAMINHA = $cod_encaminha";

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
                $abaComunica = 1389;
                include "abasSenhasComunicacao.php";
                ?>

                <div class="push30"></div> 			

                <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                    <div class="row">
                        
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

                    </div>

                    <fieldset>
                        <legend>Dados Integradora</legend> 

                        <div class="row">
                            <input type="hidden" class="form-control input-sm" name="COD_ENCAMINHA" id="COD_ENCAMINHA" value="<?php echo $cod_encaminha ?>">

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">Empresa</label>
                                    <?php if($cod_chamado == 0){ ?>
                                        <select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect requiredChk" style="width:100%;" required>
                                            <option value=""></option>
                                               <?php 
                                                $sql = "SELECT COD_EMPRESA, NOM_FANTASI from EMPRESAS ORDER BY NOM_FANTASI";
                                                $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                                            
                                                while ($qrLista = mysqli_fetch_assoc($arrayQuery))
                                                  {                                                     
                                                    echo"
                                                          <option value='".$qrLista['COD_EMPRESA']."'>".$qrLista['NOM_FANTASI']."</option> 
                                                        "; 
                                                      }                                         
                                            ?>
                                        </select>
                                        <?php }else{ 
                                            $sql = "SELECT COD_EMPRESA, NOM_FANTASI from EMPRESAS
                                                    WHERE COD_EMPRESA = $cod_empresa";
                                            $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                                            $qrEmpresa = mysqli_fetch_assoc($arrayQuery);
                                        ?>
                                      <!--   <input type="text" class="form-control input-sm leitura2" readonly="readonly" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $qrEmpresa['NOM_FANTASI']; ?>"> -->
                                            <?php } ?>
                                    <div class="help-block with-errors"></div>
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Senha Revenda</label>
                                    <input type="text" class="form-control input-sm" name="DES_AUTHKEY" id="DES_AUTHKEY" maxlength="250" value="<?php echo $des_authkey; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-3">
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

                        <div class="row">
                            
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">URL WSDL</label>
                                    <input type="text" class="form-control input-sm" name="URL_WSDL_INTEGRA" id="URL_WSDL_INTEGRA" maxlength="200" value="<?php echo $url_wsdl; ?>" required>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">URL</label>
                                    <input type="text" class="form-control input-sm" name="URL_INTEGRA" id="URL_INTEGRA" maxlength="200" value="<?php echo $url; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div> 

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Tipo de URL</label>
                                    <select data-placeholder="Selecione um tipo" name="TIPO_URL_INTEGRA" id="TIPO_URL_INTEGRA" class="chosen-select-deselect">
                                        <option value="0">...</option>
                                        <option value="1">pedidos</option>
                                        <option value="2">situacoesPedido</option> 
                                        <option value="3">USUARIO-clientes</option> 
                                        <option value="4">formasPagamento</option> 
                                    </select>   
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                        </div>

                    </fieldset>

                    <div class="push20"></div>

                    <fieldset>
                        <legend>Dados Encaminhadora</legend>

                        <div class="row">

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">URL WSDL</label>
                                    <input type="text" class="form-control input-sm" name="URL_WSDL" id="URL_WSDL" maxlength="200" value="<?php echo $url_wsdl; ?>" required>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-5">
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
                                        <option value="1">AtualizaCadastro</option>
                                        <option value="2">InserirVenda</option> 
                                        <option value="3">ExtornaVenda</option> 
                                    </select>   
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Login</label>
                                    <input type="text" class="form-control input-sm" name="DES_LOGIN" id="DES_LOGIN" maxlength="200" value="<?php echo ''; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Senha</label>
                                    <input type="text" class="form-control input-sm" name="DES_SENHA" id="DES_SENHA" maxlength="200" value="<?php echo ''; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">idLoja</label>
                                    <input type="text" class="form-control input-sm" name="COD_UNIVEND" id="COD_UNIVEND" maxlength="200" value="<?php echo ''; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">idMaquina</label>
                                    <input type="text" class="form-control input-sm" name="COD_PDV" id="COD_PDV" maxlength="200" value="<?php echo ''; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">idCliente</label>
                                    <input type="text" class="form-control input-sm" name="COD_CLIENTE" id="COD_CLIENTE" maxlength="200" value="<?php echo ''; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Cod. Vendedor</label>
                                    <input type="text" class="form-control input-sm" name="COD_VENDEDOR" id="COD_VENDEDOR" maxlength="200" value="<?php echo ''; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Nom. Vendedor</label>
                                    <input type="text" class="form-control input-sm" name="NOM_VENDEDOR" id="NOM_VENDEDOR" maxlength="200" value="<?php echo ''; ?>">
                                </div>
                                <div class="help-block with-errors"></div>
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
                                        <th>URL Integradora</th>
                                        <th>Tipo de URL Integradora</th>
                                        <th>URL Encaminhadora</th>
                                        <th>Tipo de URL Encaminhadora</th>
                                        <th>Ativo</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $sql = "SELECT * FROM ENCAMINHA_ECOMMERCE";

                                    //fnEscreve($sql);
                                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());


                                    $count = 0;
                                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
                                        $count++;
                                        $tipo="";
                                        $tipoIntegra="";
                                        $ativo="";

                                        switch($qrBuscaModulos['TIPO_URL_INTEGRA']){
                                            case 1:
                                                $tipoIntegra = "pedidos";
                                            break;
                                            case 2:
                                                $tipoIntegra = "situacoesPedido";
                                            break;
                                            case 3:
                                                $tipoIntegra = "USUARIO-clientes";
                                            break;
                                            case 4:
                                                $tipoIntegra = "formasPagamento";
                                            break;
                                            default:
                                                $tipoIntegra = "Não Definido";
                                            break;
                                        }

                                        switch($qrBuscaModulos['TIPO_URL']){
                                            case 1:
                                                $tipo = "AtualizaCadastro";
                                            break;
                                            case 2:
                                                $tipo = "InserirVenda";
                                            break;
                                            case 3:
                                                $tipo = "EstornaVenda";
                                            break;
                                            default:
                                                $tipo = "Não Definido";
                                            break;
                                        }

                                        if($qrBuscaModulos['LOG_ATIVO'] == 'S'){
                                            $ativo = "<span class='fas fa-check' style='color: #5CBC9C'></span>";
                                        }else{
                                            $ativo = "<span class='fas fa-times' style='color: red'></span>";
                                        }

                                        ?>
                                                <tr>
                                                  <td><input type='radio' name='radio1' onclick='retornaForm(<?=$count?>)'></th>
                                                  <td><?=$qrBuscaModulos['COD_ENCAMINHA']?></td>
                                                  <td><?=$qrBuscaModulos['URL_INTEGRA']?></td>
                                                  <td><?=$tipoIntegra?></td>
                                                  <td><?=$qrBuscaModulos['URL']?></td>
                                                  <td><?=$tipo?></td>
                                                  <td class="text-center"><?=$ativo?></td>
                                                </tr>

                                                <input type='hidden' id='ret_COD_EMPRESA_<?=$count?>' value='<?=$qrBuscaModulos['COD_EMPRESA']?>'>
                                                <input type='hidden' id='ret_COD_ENCAMINHA_<?=$count?>' value='<?=$qrBuscaModulos['COD_ENCAMINHA']?>'>
                                                <input type='hidden' id='ret_COD_PARCOMU_<?=$count?>' value='<?=$qrBuscaModulos['COD_PARCOMU']?>'>
                                                <input type='hidden' id='ret_DES_AUTHKEY_<?=$count?>' value='<?=$qrBuscaModulos['DES_AUTHKEY']?>'>
                                                <input type='hidden' id='ret_DEBUG_ATIVO_<?=$count?>' value='<?=$qrBuscaModulos['DEBUG_ATIVO']?>'>
                                                <input type='hidden' id='ret_URL_WSDL_INTEGRA_<?=$count?>' value='<?=$qrBuscaModulos['URL_WSDL_INTEGRA']?>'>
                                                <input type='hidden' id='ret_URL_INTEGRA_<?=$count?>' value='<?=$qrBuscaModulos['URL_INTEGRA']?>'>
                                                <input type='hidden' id='ret_TIPO_URL_INTEGRA_<?=$count?>' value='<?=$qrBuscaModulos['TIPO_URL_INTEGRA']?>'>

                                                <input type='hidden' id='ret_URL_WSDL_<?=$count?>' value='<?=$qrBuscaModulos['URL_WSDL']?>'>
                                                <input type='hidden' id='ret_URL_<?=$count?>' value='<?=$qrBuscaModulos['URL']?>'>
                                                <input type='hidden' id='ret_TIPO_URL_<?=$count?>' value='<?=$qrBuscaModulos['TIPO_URL']?>'>
                                                    
                                                <input type='hidden' id='ret_DES_LOGIN_<?=$count?>' value='<?=$qrBuscaModulos['DES_LOGIN']?>'>
                                                <input type='hidden' id='ret_DES_SENHA_<?=$count?>' value='<?=$qrBuscaModulos['DES_SENHA']?>'>
                                                <input type='hidden' id='ret_COD_UNIVEND_<?=$count?>' value='<?=$qrBuscaModulos['COD_UNIVEND']?>'>
                                                <input type='hidden' id='ret_COD_PDV_<?=$count?>' value='<?=$qrBuscaModulos['COD_PDV']?>'>
                                                <input type='hidden' id='ret_COD_CLIENTE_<?=$count?>' value='<?=$qrBuscaModulos['COD_CLIENTE']?>'>
                                                <input type='hidden' id='ret_COD_VENDEDOR_<?=$count?>' value='<?=$qrBuscaModulos['COD_VENDEDOR']?>'>
                                                <input type='hidden' id='ret_NOM_VENDEDOR_<?=$count?>' value='<?=$qrBuscaModulos['NOM_VENDEDOR']?>'>
                                            
                                                <input type='hidden' id='ret_LOG_ATIVO_<?=$count?>' value='<?=$qrBuscaModulos['LOG_ATIVO']?>'>
                                        <?php
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
        $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
        $('#formulario').validator();
    });

    function retornaForm(index){
            $("#formulario #COD_ENCAMINHA").val($("#ret_COD_ENCAMINHA_"+index).val());
            $("#formulario #DES_AUTHKEY").val($("#ret_DES_AUTHKEY_"+index).val());
            $("#formulario #URL_WSDL_INTEGRA").val($("#ret_URL_WSDL_INTEGRA_"+index).val());
            $("#formulario #URL_INTEGRA").val($("#ret_URL_INTEGRA_"+index).val());

            $("#formulario #URL_WSDL").val($("#ret_URL_WSDL_"+index).val());
            $("#formulario #URL").val($("#ret_URL_"+index).val());

            $("#formulario #DES_LOGIN").val($("#ret_DES_LOGIN_"+index).val());
            $("#formulario #DES_SENHA").val($("#ret_DES_SENHA_"+index).val());              
            $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_"+index).val());              
            $("#formulario #COD_PDV").val($("#ret_COD_PDV_"+index).val());
            $("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_"+index).val());
            $("#formulario #COD_VENDEDOR").val($("#ret_COD_VENDEDOR_"+index).val());
            $("#formulario #NOM_VENDEDOR").val($("#ret_NOM_VENDEDOR_"+index).val());

            $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val()).trigger("chosen:updated");
            $("#formulario #COD_PARCOMU").val($("#ret_COD_PARCOMU_"+index).val()).trigger("chosen:updated");
            $("#formulario #DEBUG_ATIVO").val($("#ret_DEBUG_ATIVO_"+index).val()).trigger("chosen:updated");
            $("#formulario #TIPO_URL_INTEGRA").val($("#ret_TIPO_URL_INTEGRA_"+index).val()).trigger("chosen:updated");
            $("#formulario #TIPO_URL").val($("#ret_TIPO_URL_"+index).val()).trigger("chosen:updated");

            if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
            else {$('#formulario #LOG_ATIVO').prop('checked', false);}

            $('#formulario').validator('validate');         
            $("#formulario #hHabilitado").val('S'); 
    }

</script>	