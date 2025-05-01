<?php

//echo fnDebug('true');

$cod_conface = 0;
$cod_empresa = 0;
$des_emailus = "";
$des_senhaus = "";
$itens_por_pagina = 20;
$pagina = 1;
$hashLocal = mt_rand();

require_once "_system/whatsapp/wstAdorai.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_senhaparc = fnLimpaCampoZero($_REQUEST['COD_SENHAPARC']);
        $cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
        $cod_parcomu = fnLimpaCampoZero($_REQUEST['COD_PARCOMU']);
        $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
        $port_servicao = fnLimpaCampo($_REQUEST['PORT_SERVICAO']);
        $celular = fnLimpaCampoZero($_REQUEST['CELULAR']);
        $tip_integracao = $_REQUEST['TIP_INTEGRACAO'];
        $nom_sessao = $_REQUEST['NOM_SESSAO'];
        $des_authkey = $_REQUEST['DES_AUTHKEY'];
        $des_token = $_REQUEST['DES_TOKEN'];


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

                    // fnEscreve($nom_sessao);
                    // fnEscreve($des_authkey);
                    // fnEscreve($des_token);

                    // $token = FncreateToken("$nom_sessao","$des_authkey");

                    // echo "<pre>";
                    // print_r($token);
                    // fnEscreve($token['hash']['apikey']);
                    // echo "</pre>";
                    // exit();

                    // if($token[instance][status] == "created"){
                        // fnEscreve("sessão:".$nom_sessao);
                        // fnEscreve("key:".$des_authkey);
                        // fnEscreve("celular:".$celular);
                        // fnEscreve("tip_integracao:".$tip_integracao);
                        // fnEscreve("port_servicao:".$port_servicao);
                        // exit();

                $session = Fncreate("$nom_sessao","$des_authkey",$celular,"$tip_integracao","$port_servicao");

                //Fncreate($instanceName,$apikey,$cel,$integration='WHATSAPP-BAILEYS',$port)

                        // echo "<pre>";
                        // print_r($token);
                        // fnEscreve($token['hash']['apikey']);
                        // echo "</pre>";
                        // exit();

                // echo "<pre>";
                // print_r($session);
                // echo "</pre>";

                if($session[instance][status] == "created"){

                    $des_base64 = $session[qrcode][base64];

                    $sql = "INSERT INTO SENHAS_WHATSAPP(
                        COD_EMPRESA,
                        COD_UNIVEND,
                        COD_PARCOMU,
                        NOM_SESSAO,
                        DES_AUTHKEY,
                        LOG_ATIVO,
                        DES_BASE64,
                        PORT_SERVICAO,
                        DAT_LOGIN,
                        COD_USUCADA,
                        CELULAR,
                        TIP_INTEGRACAO
                        ) VALUES(
                        '$cod_empresa',
                        '$cod_univend',
                        '$cod_parcomu',
                        '$nom_sessao',
                        '$des_authkey',
                        '$log_ativo',
                        '$des_base64',
                        '$port_servicao',    
                        NOW(),
                        $cod_usucada,
                        $celular,
                        '$tip_integracao'
                    )";

                       // fnEscreve($sql);
                        mysqli_query($connAdm->connAdm(), $sql);
                            // fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());

                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                        $msgTipo = 'alert-success';

                    }else{
                        $msgRetorno = "Falha na criação da sessão";
                        $msgTipo = 'alert-danger';
                    }

                    // }else{
                    //     $msgRetorno = "Falha na criação do token";
                    //     $msgTipo = 'alert-danger';
                    // }

                    break;
                    case 'ALT':

                    $sql = "UPDATE SENHAS_WHATSAPP SET
                    COD_EMPRESA='$cod_empresa',
                    COD_UNIVEND='$cod_univend',
                    COD_PARCOMU='$cod_parcomu',
                    NOM_SESSAO='$nom_sessao',
                    DES_AUTHKEY='$des_authkey',
                    DES_TOKEN='$des_token',
                    LOG_ATIVO='$log_ativo',
                    DES_BASE64='$des_base64',
                    PORT_SERVICAO='$port_servicao',
                    COD_USUALT=$cod_usucada,
                    CELULAR=$celular,
                    TIP_INTEGRACAO='$tip_integracao',
                    DAT_ALTERAC=NOW()
                    WHERE COD_SENHAPARC = $cod_senhaparc";

                    //fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());
                    // fnEscreve($sql);
                    mysqli_query($connAdm->connAdm(), $sql);

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    $msgTipo = 'alert-success';

                    break;
                    case 'EXC':

                    $delete = Fndelete("$nom_sessao","$des_authkey","$port_servicao");

                    if($delete[status] == 'SUCCESS'){
                        $sql = "DELETE FROM SENHAS_WHATSAPP WHERE COD_SENHAPARC = $cod_senhaparc";
                        // fnEscreve($sql);
                        mysqli_query($connAdm->connAdm(), $sql);

                        $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                        $msgTipo = 'alert-success';
                    }
                    break;
                }
            }
        }
    }

//fnMostraForm();
//fnEscreve($cod_empresa);
    ?>

    <style>
   /* body #popModal .modal-dialog { 
        margin-top: 50px;
    }*/
</style>

<div class="push30"></div> 

<div class="row">				

    <div class="col-md-12 margin-bottom-30">
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
                $abaComunica = 1955;
                include "abasSenhasComunicacao.php";
                ?>

                <div class="push30"></div> 			

                <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

                    <fieldset>
                        <legend>Dados Gerais</legend> 

                        <div class="row">

                           <div class="col-md-2">
                            <div class="form-group">
                             <label for="inputName" class="control-label">Comunicação Ativa</label> 
                             <div class="push5"></div>
                             <label class="switch">
                              <input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?php echo $checkLOG_ATIVO; ?> >
                              <span></span>
                          </label>
                      </div>
                  </div>						

              </div>

              <div class="push10"></div>

              <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">Empresa</label>
                        <select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect" required>
                            <option value=""></option>
                            <?php
                            $sql = "select COD_EMPRESA, NOM_FANTASI from EMPRESAS order by NOM_FANTASI ";
                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

                            while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
                                echo"<option value='" . $qrListaTipoEntidade['COD_EMPRESA'] . "'>" . $qrListaTipoEntidade['NOM_FANTASI'] . "</option>";
                            }
                            ?>  
                        </select>

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

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="inputName" class="control-label">Parceiro Comunicação</label>
                        <select data-placeholder="Selecione um parceiro" name="COD_PARCOMU" id="COD_PARCOMU" class="chosen-select-deselect" required>
                            <option value=""></option>
                            <?php
                            $sql = "SELECT COD_PARCOMU, DES_PARCOMU FROM PARCEIRO_COMUNICACAO WHERE COD_TPCOM = 6 ORDER BY DES_PARCOMU ";
                            $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

                            while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
                                echo"<option value='" . $qrListaTipoEntidade['COD_PARCOMU'] . "'>" . $qrListaTipoEntidade['DES_PARCOMU'] . "</option>";
                            }
                            ?>  
                        </select>
                        <div class="help-block with-errors"></div>
                        <script type="text/javascript">
                            $(function(){
                                $("#formulario #COD_UNIVEND").val("<?=$cod_univend?>").trigger("chosen:updated");
                            });
                        </script>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">Nome da Sessão</label>
                        <input type="text" class="form-control input-sm" name="NOM_SESSAO" id="NOM_SESSAO" maxlength="120" value="" required>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>

            </div>

            <div class="push10"></div>

            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">API Key</label>
                        <input type="text" class="form-control input-sm" name="DES_AUTHKEY" id="DES_AUTHKEY" maxlength="100" value="" required>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">Porta de Serviço </label>
                        <select data-placeholder="Selecione uma porta" name="PORT_SERVICAO" id="PORT_SERVICAO" class="chosen-select-deselect" required>
                            <option value=""></option>
                            <option value="https://api1.webbix.com.br">https://api1.webbix.com.br</option>
                            <option value="https://api2.webbix.com.br">https://api2.webbix.com.br</option>
                            <option value="https://api3.webbix.com.br">https://api3.webbix.com.br</option>
                            <option value="https://api4.webbix.com.br">https://api4.webbix.com.br</option>
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">Número WhatsApp</label>
                        <input type="text" class="form-control input-sm" name="CELULAR" id="CELULAR" maxlength="120" value="" required>
                    </div>
                    <div class="help-block with-errors"></div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputName" class="control-label required">Tipo de Integração </label>
                        <select data-placeholder="Selecione uma porta" name="TIP_INTEGRACAO" id="TIP_INTEGRACAO" class="chosen-select-deselect" required>
                            <option value=""></option>
                            <option value="WHATSAPP-BAILEYS">WHATSAPP-BAILEYS</option>
                            <option value="WHATSAPP-BUSINESS">WHATSAPP-BUSINESS</option>
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
        <input type="hidden" name="COD_SENHAPARC" id="COD_SENHAPARC" value="<?php echo $cod_senhaparc; ?>" />
        <input type="hidden" name="DES_BASE64" id="DES_BASE64" value="<?php echo $des_base64; ?>" />
        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" /> 
        <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		

        <div class="push5"></div> 

    </form>

    <div class="push50"></div>

    <div class="col-lg-12">

        <div class="no-more-tables">

            <table class="table table-bordered table-striped table-hover tablesorter buscavel">

                <thead>
                    <tr>
                        <th class="{sorter:false}" width="40"></th>
                        <th>Código</th>
                        <th>Empresa</th>
                        <th>Sessão</th>
                        <th>Celular</th>
                        <th>Status</th>
                        <th class="{sorter:false}"></th>
                    </tr>
                </thead>
                <tbody id="relatorioConteudo">
                    <?php

                    $sql = "SELECT 1 from SENHAS_WHATSAPP 
                    $andFiltro
                    ";

                    $retorno = mysqli_query($connAdm->connAdm(), $sql);
                    $totalitens_por_pagina = mysqli_num_rows($retorno);
                    $numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

                                // fnEscreve($numPaginas);

                    $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
                                //echo $inicio;


                    $sql = "SELECT SENHAS_WHATSAPP.*,
                    EMP.NOM_FANTASI
                    from SENHAS_WHATSAPP
                    INNER JOIN EMPRESAS EMP ON EMP.COD_EMPRESA = SENHAS_WHATSAPP.COD_EMPRESA
                    $andFiltro";

                                // fnEscreve($sql);
                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                    $count = 0;
                    while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

                        $count++;
                        $tipo = "";


                        $connection = fnconnectionState("$qrBuscaModulos[NOM_SESSAO]","$qrBuscaModulos[DES_AUTHKEY]","$qrBuscaModulos[PORT_SERVICAO]");
                                   //fnEscreve($connection['instance']['state']);

                        switch ($connection['instance']['state']) {
                            case 'connecting':
                            $conexao = '<div class="push5"></div>
                            <p class="label f14 bg-danger" > <span class="fal fa-clock" style="color: #FFF;"></span>
                            &nbsp;Aguardando conexão
                            </p>';
                            break;
                            case 'close':
                            $conexao = '<div class="push5"></div>
                            <p class="label f14" style="background-color: #FF5252 "> <span class="fal fa-times" style="color: #FFF;"></span>
                            &nbsp;Conexão encerrada
                            </p>';
                            break;
                            
                            default:
                            $conexao = '<div class="push5"></div>
                            <p class="label f14" style="background-color: #18BC9C "> <span class="fal fa-check" style="color: #FFF;"></span>
                            &nbsp;Conectado
                            </p>';
                            break;
                        }
                        ?>
                        <tr>
                            <td><input type='radio' name='radio1' onclick='retornaForm("<?=$count?>")'></td>
                            <td><?=$qrBuscaModulos['COD_SENHAPARC']?></td>
                            <td><?=$qrBuscaModulos['NOM_FANTASI']?></td>
                            <td><?=$qrBuscaModulos['NOM_SESSAO']?></td>
                            <td><?=$qrBuscaModulos['CELULAR']?></td>
                            <td><?=$conexao?></td>
                            <td class="text-center">
                                <small>
                                    <div class="btn-group dropdown dropleft">
                                        <button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            ações &nbsp;
                                            <span class="fas fa-caret-down"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                            <!-- <li><a href="javascript:void(0)" onclick='sessao("<?=$qrBuscaModulos[COD_SENHAPARC]?>","status")'>Status </a></li> -->
                                            <?php if($conexao != "Conexão encerrada"){ ?>
                                                <li><a href="javascript:void(0)" onclick='sessao("<?=$qrBuscaModulos[COD_SENHAPARC]?>","qrcode")'>Gerar QrCode </a></li>

                                            <?php }else{ ?>
                                                <li><a href="javascript:void(0)" onclick='sessao("<?=$qrBuscaModulos[COD_SENHAPARC]?>","reconect")'>Gerar novo QrCode</a></li>
                                            <?php } ?>

                                            <?php if($conexao == "Conectado"){ ?>
                                                <li><a href="javascript:void(0)" class="addProxy" data-url="action.do?mod=<?php echo fnEncode(2041)?>&CDS=<?php echo fnEncode($qrBuscaModulos['COD_SENHAPARC'])?>&pop=true" data-title="Cadastrar Proxy / <?php echo $qrBuscaModulos['NOM_FANTASI']; ?>">Cad. Proxy </a></li>
                                            <?php } ?>

                                        </ul>
                                    </div>
                                </small>
                            </td>
                        </tr>

                        <input type='hidden' id='ret_COD_SENHAPARC_<?=$count?>' value="<?=$qrBuscaModulos['COD_SENHAPARC']?>">
                        <input type='hidden' id='ret_COD_EMPRESA_<?=$count?>' value="<?=$qrBuscaModulos['COD_EMPRESA']?>">
                        <input type='hidden' id='ret_COD_PARCOMU_<?=$count?>' value="<?=$qrBuscaModulos['COD_PARCOMU']?>">
                        <input type='hidden' id='ret_LOG_ATIVO_<?=$count?>' value="<?=$qrBuscaModulos['LOG_ATIVO']?>">
                        <input type='hidden' id='ret_NOM_SESSAO_<?=$count?>' value="<?=$qrBuscaModulos['NOM_SESSAO']?>">
                        <input type='hidden' id='ret_DES_AUTHKEY_<?=$count?>' value="<?=$qrBuscaModulos['DES_AUTHKEY']?>">
                        <input type='hidden' id='ret_DES_BASE64_<?=$count?>' value="<?=$qrBuscaModulos['DES_BASE64']?>">
                        <input type='hidden' id='ret_COD_UNIVEND_<?=$count?>' value="<?=$qrBuscaModulos['COD_UNIVEND']?>">
                        <input type='hidden' id='ret_DES_TOKEN_<?=$count?>' value="<?=$qrBuscaModulos['DES_TOKEN']?>">
                        <input type='hidden' id='ret_PORT_SERVICAO_<?=$count?>' value="<?=$qrBuscaModulos['PORT_SERVICAO']?>">
                        <input type='hidden' id='ret_CELULAR_<?=$count?>' value="<?=$qrBuscaModulos['CELULAR']?>">
                        <input type='hidden' id='ret_TIP_INTEGRACAO_<?=$count?>' value="<?=$qrBuscaModulos['TIP_INTEGRACAO']?>">
                        <?php 
                    }
                    ?>

                </tbody>
                <tfoot>
                    <tr>
                        <th class="" colspan="100">
                            <center>
                                <ul id="paginacao" class="pagination-sm"></ul>
                            </center>
                        </th>
                    </tr>
                </tfoot>
            </table>

        </div>

    </div>										

    <div class="push"></div>

</div>								

</div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
    <div class="modal-dialog" style="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="push100"></div>
                <div class="push100"></div>
                <center><img class="img-responsive" id="IMG_CODE" src="<?=$des_base64?>" width="40%" /></center>
                <div class="push100"></div>
                <div class="push100"></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<!-- fim Portlet -->
</div>	

<!-- Segundo modal -->
<div class="modal fade z-3" id="iframeModal" tabindex='-1'>
    <div class="modal-dialog" style="">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <iframe id="iframeContent" frameborder="0" style="width: 100%; height: 80%"></iframe>
            </div>      
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  		

<div class="push20"></div>


<script type="text/javascript">

    let nom_sessao = "";

    $(function(){

        $('.addProxy').on('click', function(e){
            e.preventDefault();
            
            var url = $(this).data('url');
            var title = $(this).data('title');
            
            $('#iframeModal .modal-title').text(title);
            $('#iframeContent').attr('src', url);
            $('#iframeModal').modal('show').appendTo('body');
        });

        $("#COD_EMPRESA").change(function() {

            var idEmp = $('#COD_EMPRESA').val();
            buscaCombo(idEmp,0);
            $("#NOM_SESSAO").val('');
            
        });
        
        <?php if($des_base64 != ""){ ?>
            $('#popModal .modal-title').text('QrCode');
            // $('#popModal').modal();
            $('#popModal').not('#popModalNotifica').appendTo("body").modal('show');
        <?php } ?>
        

        var SPMaskBehavior = function(val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

        $('.sp_celphones').mask(SPMaskBehavior, spOptions);

    });

    function retornaForm(index) {
        $("#formulario #COD_PARCOMU").val($("#ret_COD_PARCOMU_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val()).trigger("chosen:updated");
        $("#formulario #PORT_SERVICAO").val($("#ret_PORT_SERVICAO_" + index).val()).trigger("chosen:updated");
        $("#formulario #TIP_INTEGRACAO").val($("#ret_TIP_INTEGRACAO_" + index).val()).trigger("chosen:updated");
        buscaCombo($("#ret_COD_EMPRESA_" + index).val(),$("#ret_COD_UNIVEND_" + index).val());
        $("#formulario #COD_SENHAPARC").val($("#ret_COD_SENHAPARC_" + index).val());
        $("#formulario #DES_TOKEN").val($("#ret_DES_TOKEN_" + index).val());
        $("#formulario #NOM_SESSAO").val($("#ret_NOM_SESSAO_" + index).val());
        $("#formulario #DES_AUTHKEY").val($("#ret_DES_AUTHKEY_" + index).val());
        $("#formulario #DES_BASE64").val($("#ret_DES_BASE64_" + index).val());
        $("#formulario #CELULAR").val($("#ret_CELULAR_" + index).val());

        if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
        else {$('#formulario #LOG_ATIVO').prop('checked', false);}			

        $('#formulario').validator('validate');
        $("#formulario #hHabilitado").val('S');
    }

    function sessao(cod_senhaparc,tipo){

        let msg1="Encerrar sessão?",
        msg2="Sessão encerrada.";

        if(tipo == "status"){
            msg1 = "Verificar status?";
        }else if(tipo == "qrcode"){
            msg1 = "Gerar novo QrCode?";
        }else{
            msg1 = 'Reconectar WhatsApp?';
        }

        $.alert({
            title: "Aviso",
            type: 'orange',
            content: msg1,
            buttons: {
                "Sim": {
                   btnClass: 'btn-danger',
                   action: function(){
                    $.ajax({
                        type: "POST",
                        url: "ajxGerenciadorSenhaWhatsAppAdorai.do?id=<?=fnEncode($cod_empresa)?>&opcao="+tipo,
                        data: {COD_SENHAPARC:cod_senhaparc},
                        success:function(data){

                            console.log(data);

                            if(tipo == "status"){
                                if(data){
                                    msg2 = "Status: Conectado.";
                                }else{
                                    msg2 = "Status: Desconectado.";
                                }
                            }else if(tipo == "qrcode"){
                                if(data == "0"){
                                    msg2 = "QrCode não disponível";
                                }else{
                                    msg2 = "";
                                }
                            }else if(tipo == "reconect"){
                                if(data == "0"){
                                    msg2 = "QrCode não disponível";
                                }else{
                                    msg2 = "";
                                }
                            }else{
                                if(data){
                                    msg2 = "Sessão encerrada.";
                                }else{
                                    msg2 = "Erro ao encerrar sessão.";
                                }
                            }

                            if(msg2 != ""){
                                $.alert({
                                    title: "Aviso",
                                    content: msg2
                                });
                            }else{
                                $('#IMG_CODE').attr('src', data);
                                $('#popModal .modal-title').text('QrCode');
                                $('#popModal').not('#popModalNotifica').appendTo("body").modal('show');
                            }

                        },
                        error:function(){
                         $.alert({
                            title: "Aviso",
                            type: 'red',
                            content: "Erro na solicitação."
                        });
                     }
                 });
                }
            },
            "Não": {
               action: function(){

               }
           }
       }
   });
    }

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
                $('#relUnivend').html(data);
                $(".chosen-select-deselect").chosen({allow_single_deselect:true});
                $("#COD_UNIVEND").change(function() {

                    nom_sessao = $("#COD_EMPRESA").val()+"_"+$(this).val();

                    $("#NOM_SESSAO").val(nom_sessao);

                    
                });
                if(index != 0){
                    $("#formulario #COD_UNIVEND").val(index).trigger("chosen:updated");
                }
            }
            // error:function(){
            //     $('#relatorioUsu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
            // }
        });

    }

</script>	