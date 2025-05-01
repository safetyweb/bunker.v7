<?php

//echo fnDebug('true');

$cod_conface = 0;
$cod_empresa = 0;
$des_emailus = "";
$des_senhaus = "";
$itens_por_pagina = 20;
$pagina = 1;
$hashLocal = mt_rand();

include "_system/whatsapp/wstAdorai.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request = md5(implode($_POST));

    if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
        $msgRetorno = 'Essa página já foi utilizada';
        $msgTipo = 'alert-warning';
    } else {
        $_SESSION['last_request'] = $request;

        $cod_senhaparc = fnLimpaCampoZero($_REQUEST['COD_SENHAPARC']);
        $cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
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

                        $session = Fncreate("$nom_sessao","$des_authkey");

                        // echo "<pre>";
                        // print_r($session);
                        // // fnEscreve($token['hash']['apikey']);
                        // echo "</pre>";
                        // exit();

                        if($session[instance][status] == "created"){

                            $des_base64 = $session[qrcode][base64];

                            $sql = "INSERT INTO SENHAS_WHATSAPP(
                                                    COD_EMPRESA,
                                                    COD_UNIVEND,
                                                    NOM_SESSAO,
                                                    DES_AUTHKEY,
                                                    LOG_ATIVO,
                                                    DES_BASE64,
                                                    DAT_LOGIN,
                                                    COD_USUCADA
                                                ) VALUES(
                                                    274,
                                                    '$cod_univend',
                                                    '$nom_sessao',
                                                    '$des_authkey',
                                                    '$log_ativo',
                                                    '$des_base64',
                                                    NOW(),
                                                    $cod_usucada
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
                                        COD_UNIVEND='$cod_univend',
                                        NOM_SESSAO='$nom_sessao',
                                        DES_AUTHKEY='$des_authkey',
                                        DES_TOKEN='$des_token',
                                        LOG_ATIVO='$log_ativo',
                                        DES_BASE64='$des_base64',
                                        COD_USUALT=$cod_usucada,
                                        DAT_ALTERAC=NOW()
                                WHERE COD_SENHAPARC = $cod_senhaparc";

                    //fnTestesql($connAdm->connAdm(),$sql) or die(mysqli_error());
                    // fnEscreve($sql);
                    mysqli_query($connAdm->connAdm(), $sql);

                    $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
                    $msgTipo = 'alert-success';

                    break;
                case 'EXC':

                    $sql = "DELETE FROM SENHAS_WHATSAPP WHERE COD_SENHAPARC = $cod_senhaparc";
                    // fnEscreve($sql);
                    mysqli_query($connAdm->connAdm(), $sql);

                    $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
                    $msgTipo = 'alert-success';

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
                                    <label for="inputName" class="control-label required">Nome da Sessão</label>
                                    <input type="text" class="form-control input-sm" name="NOM_SESSAO" id="NOM_SESSAO" maxlength="120" value="" required>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label required">API Key</label>
                                    <input type="text" class="form-control input-sm" name="DES_AUTHKEY" id="DES_AUTHKEY" maxlength="100" value="" required>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Canal</label>
                                        <select data-placeholder="Selecione um Canal" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" tabindex="1" required>
                                            <option value=""></option>
                                            <?php 

                                                $sql = "SELECT * FROM CANAL_ADORAI WHERE COD_EMPRESA = 274 AND LOG_PREF = 'S'";

                                                $arrCanal = mysqli_query(conntemp(274,""), $sql);

                                                $count = 0;

                                                while($qrCanal = mysqli_fetch_assoc($arrCanal)){
                                            ?>
                                                <option value="<?=$qrCanal[COD_CANAL]?>"><?=$qrCanal[DES_CANAL]?></option>
                                            <?php 
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

                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Token</label>
                                    <input type="text" class="form-control input-sm" name="DES_TOKEN" id="DES_TOKEN" maxlength="100" value="">
                                </div>
                                <div class="help-block with-errors"></div>
                            </div> -->

                        </div>

                        <!-- <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">Base 64</label>
                                    <textarea name="DES_BASE64" id="DES_BASE64" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>

                        </div> -->

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
                                    <th>Sessão</th>
                                    <th>Status</th>
                                    <th class="{sorter:false}"></th>
                                </tr>
                            </thead>
                            <tbody id="relatorioConteudo">
<?php

                                $sql = "SELECT SENHAS_WHATSAPP.*
                                        from SENHAS_WHATSAPP
                                        WHERE COD_EMPRESA = 274";

                                // fnEscreve($sql);
                                $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

                                $count = 0;
                                while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

                                    $count++;
                                    $tipo = "";

                                    $connection = fnconnectionState($qrBuscaModulos['NOM_SESSAO'],$qrBuscaModulos['DES_AUTHKEY']);

                                    // echo "<pre>";
                                    // print_r($connection);
                                    // echo "</pre>";

                                    // fnEscreve($connection['instance']['state']);

                                    switch ($connection['instance']['state']) {
                                        case 'connecting':
                                            $conexao = "Aguardando conexão";
                                        break;
                                        case 'close':
                                            $conexao = "Conexão encerrada";
                                        break;
                                        
                                        default:
                                            $conexao = "Conectado";
                                        break;
                                    }
?>
                                    <tr>
                                        <td><input type='radio' name='radio1' onclick='retornaForm("<?=$count?>")'></td>
                                        <td><?=$qrBuscaModulos['COD_SENHAPARC']?></td>
                                        <td><?=$qrBuscaModulos['NOM_SESSAO']?></td>
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
                                                        <li><a href="javascript:void(0)" onclick='sessao("<?=$qrBuscaModulos[COD_SENHAPARC]?>","qrcode")'>Gerar novo QrCode </a></li>
                                                        <!-- <li class="divider"></li> -->
                                                        <!-- <li><a href="javascript:void(0)" onclick='sessao("<?=$qrBuscaModulos[COD_SENHAPARC]?>","encerrar")'>Encerrar Sessão </a></li> -->
                                                        
                                                    </ul>
                                                </div>
                                            </small>
                                        </td>
                                    </tr>

                                    <input type='hidden' id='ret_COD_SENHAPARC_<?=$count?>' value="<?=$qrBuscaModulos['COD_SENHAPARC']?>">
                                    <input type='hidden' id='ret_LOG_ATIVO_<?=$count?>' value="<?=$qrBuscaModulos['LOG_ATIVO']?>">
                                    <input type='hidden' id='ret_NOM_SESSAO_<?=$count?>' value="<?=$qrBuscaModulos['NOM_SESSAO']?>">
                                    <input type='hidden' id='ret_DES_AUTHKEY_<?=$count?>' value="<?=$qrBuscaModulos['DES_AUTHKEY']?>">
                                    <input type='hidden' id='ret_DES_BASE64_<?=$count?>' value="<?=$qrBuscaModulos['DES_BASE64']?>">
                                    <input type='hidden' id='ret_COD_UNIVEND_<?=$count?>' value="<?=$qrBuscaModulos['COD_UNIVEND']?>">
                                    <input type='hidden' id='ret_DES_TOKEN_<?=$count?>' value="<?=$qrBuscaModulos['DES_TOKEN']?>">
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

<div class="push20"></div>


<script type="text/javascript">

    $(function(){

        
        <?php if($des_base64 != ""){ ?>
            $('#popModal .modal-title').text('QrCode');
            // $('#popModal').modal();
            $('.modal').not('#popModalNotifica').appendTo("body").modal('show');
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
        $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_PARCOMU").val($("#ret_COD_PARCOMU_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
        $("#formulario #COD_SENHAPARC").val($("#ret_COD_SENHAPARC_" + index).val());
        $("#formulario #DES_TOKEN").val($("#ret_DES_TOKEN_" + index).val());
        $("#formulario #NOM_SESSAO").val($("#ret_NOM_SESSAO_" + index).val());
        $("#formulario #DES_AUTHKEY").val($("#ret_DES_AUTHKEY_" + index).val());
        $("#formulario #DES_BASE64").val($("#ret_DES_BASE64_" + index).val());
		
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
                                $('.modal').not('#popModalNotifica').appendTo("body").modal('show');
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

</script>	