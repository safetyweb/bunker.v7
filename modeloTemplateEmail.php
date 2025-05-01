<?php

if (isset($_GET['idc'])) {
  $cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
}else{
  $cod_campanha = "";
}

if (isset($_GET['pop'])) {
  $popUp = fnLimpaCampo($_GET['pop']);
} else {
  $popUp = '';
}

$hashLocal = mt_rand();

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
  $request = md5( implode( $_POST ) );
  
  if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
  {
    $msgRetorno = 'Essa página já foi utilizada';
    $msgTipo = 'alert-warning';
  }
  else
  {
    $_SESSION['last_request']  = $request;
    
    $cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
    $cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
    if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
    $nom_template = fnLimpaCampo($_REQUEST['NOM_TEMPLATE']);
    $abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
    $des_template = fnLimpaCampo($_REQUEST['DES_TEMPLATE']);
    
    $opcao = $_REQUEST['opcao'];
    $hHabilitado = $_REQUEST['hHabilitado'];
    $hashForm = $_REQUEST['hashForm'];
    
    $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                    
    if ($opcao != ''){        
      
      //mensagem de retorno
      switch ($opcao)
      {
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

if($_GET['msg'] == 'success'){
  $msgRetorno = "Template gerada e sincronizada com <b>sucesso</b>!";
  $msgTipo = 'alert-success';
}else if($_GET['msg'] == 'sync_err'){
  $msgRetorno = "Template gerada com <b>sucesso</b>. <br/>Sincronização <b>falhou</b> Revise sua template/configurações e tente novamente.";
  $msgTipo = 'alert-warning';
}

if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
  //busca dados da empresa
  $cod_empresa = fnDecode($_GET['id']);
  $cod_template = fnDecode($_GET['idT']);

  // fnEscreve($cod_template);

  // fnEscreve($cod_empresa);

  if ($cod_template != "" && $cod_template != 0) {

    $sqlModelo = "SELECT  DES_ASSUNTO,
                          DES_REMET,
                          END_REMET,
                          EMAIL_RESPOSTA,
                          LOG_OPT,
                          TXT_LINKOPT,
                          TAG_LINKOPT,
                          TXT_OPT,
                          TAG_OPT
                  FROM TEMPLATE_EMAIL 
                  WHERE COD_TEMPLATE = $cod_template";

    $arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sqlModelo));

    while ($qrModelo = mysqli_fetch_assoc($arrayQuery)) {

      $des_assunto = $qrModelo['DES_ASSUNTO'];
      $des_remet = $qrModelo['DES_REMET'];
      $end_remet = $qrModelo['END_REMET'];
      $email_resposta = $qrModelo['EMAIL_RESPOSTA'];
      $log_opt = $qrModelo['LOG_OPT'];
      $txt_linkopt = $qrModelo['TXT_LINKOPT'];
      $tag_linkopt = $qrModelo['TAG_LINKOPT'];
      $txt_opt = $qrModelo['TXT_OPT'];
      $tag_opt = $qrModelo['TAG_OPT'];

    }

    if ($cod_template != "" && $cod_template != 0) {
      $sqlModelo = "SELECT COD_MODELO, DES_TEMPLATE FROM MODELO_EMAIL WHERE COD_TEMPLATE = $cod_template";

      $arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sqlModelo));

      while ($qrModelo = mysqli_fetch_assoc($arrayQuery)) {
        $isCad = false;

        $cod_modelo = $qrModelo['COD_MODELO'];
        $des_template = $qrModelo['DES_TEMPLATE'];
        $nom_pagina = $qrModelo['NOM_PAGINA'];
      }
      // fnEscreve($sqlModelo);
    }

  }else{
    $des_assunto = "";
    $des_remet = "";
    $end_remet = "N";
    $email_resposta = "";
    $log_opt = "";
    $txt_linkopt = "";
    $tag_linkopt = "";
    $txt_opt = "";
    $tag_opt = "";
    $cod_modelo = "";
    $des_template = "";
    $nom_pagina = "";
    $isCad = true;
  }

}else{
  $cod_empresa = 0;
  $cod_template = 0;
}

// fnEscreve($cod_campanha);

if($cod_modelo == 0 || $cod_modelo == ""){
  $opcao = "CAD";
}else{
  $opcao = "ALT";
}

// $html = highlight_file('emailComponenteTeste/template.html',true);


// echo($html);


?>

<!-- <div class="push30"></div> -->

<style>
  
  #blocker
  {
      display:none; 
    position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: .8;
      background-color: #fff;
      z-index: 1000;
      cursor: wait;
  }
      
  #blocker div
  {
    position: absolute;
    top: 30%;
    left: 48%;
    width: 200px;
    height: 2em;
    margin: -1em 0 0 -2.5em;
    color: #000;
    font-weight: bold;
  }

  .colpick{
    z-index: 26000!important;
  }
  
  #elements .disabled{
	  background:#EEE !important;
  }

  #previewOverlay{
      display:none; 
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255,255,255,0.8);
      z-index: 1000;
      overflow: hidden; /* Hide scrollbars */
  }

 .iphone_bg{
  width:420px;
  height:740px;
  background:url(../images/phone_bg.png) no-repeat center;
  margin: auto;
  }
  .mobile_wrap{
  width:360px!important; 
  height:640px!important;
  margin:70px 0 0 38px;
  overflow: hidden;
  }
  .container-wrap{
    overflow-y: auto;
    height: 590px;
    width: 340px;
    background-color: white;
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
      scrollbar-width: none;  /* Firefox */
  }
  .container-wrap::-webkit-scrollbar { 
      

</style>

<div id="blocker">
    <div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)<br/><small>(este processo pode demorar vários minutos)</small></div>
</div>

<div id="previewOverlay">

    <div class="row">

      <div class="col-md-7" style="background-color: #fff;">
        <iframe id="preview_ifr" frameborder=0 style="width:100%;height:100%;"></iframe>
      </div>

      <div class="col-md-4">

        <div class="push50"></div>

        <div class="iphone_bg">

          <div class="row">

            <div class="col-md-12">

              <div class="mobile_wrap">

                <div class="container-wrap">

                  <iframe id="preview_ifr_mob" frameborder=0 style="width:100%;height:100%;"></iframe>

                </div>

              </div>
              
            </div>

          </div>
                        
        </div>

      </div>

      <div class="col-md-1">
        <div class="push50"></div>
        <div class="push10"></div>
        <a href="javascript:void(0)" class="previewOverlay" style="text-decoration: none; color: #aaa;"><span class="fas fa-times fa-2x text-right"></span></a>
      </div>

    </div>

</div>

<div class="row">				

  <div class="col-md12 margin-bottom-30">
    <!-- Portlet -->
    <?php if ($popUp != "true") { ?>              
      <div class="portlet portlet-bordered">
      <?php } else { ?>
        <div class="portlet" style="padding: 0 20px 20px 20px;" >
        <?php } ?>

        <?php if ($popUp != "true") { ?>
          <div class="portlet-title">
            <div class="caption">
              <i class="glyphicon glyphicon-calendar"></i>
              <span class="text-primary"><?php echo $NomePg; ?></span>
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

          <!-- <div class="push30"></div>  -->

          <div class="login-form">

            <?php            
            // include "abasTemplateEmailDrag.php";
            ?>	            

            <div class="push20"></div>

              <div class="row">

                <div class="col-md-10">
              
                  <fieldset>
                    <legend>Banco de Variáveis <small>(<b>Clique e arraste</b> a tag desejada ou <b>copie</b> na área desejada)</small> </legend> 
                
                    <?php
                    
                          //fnEscreve($cod_campanha);
                    
                          //busca dados da campanha
                    $cod_campanha = fnDecode($_GET['idc']);  
                    $sql = "SELECT TIP_CAMPANHA FROM CAMPANHA where COD_CAMPANHA = '".$cod_campanha."' ";
                          //fnEscreve($sql);
                    $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
                    $qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);
                    
                    if (isset($qrBuscaCampanha)){
                      $tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
                    } 
                          //fnEscreve($tip_campanha);
                    
                    $sql = "select * from VARIAVEIS where COD_BANCOVAR in (3,23,39,41,44,45) order by NUM_ORDENAC";
                    $arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

                    $count = 0;
                    
                    while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
                     ?>
                     <a href="javascript:void(0)" class="btn btn-default btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
                       dragTagName="<?=$qrBuscaFases[KEY_BANCOVAR]?>" 
                       onclick='copy2clipboard("<?=$qrBuscaFases[KEY_BANCOVAR]?>")'>
                       <span><?=$qrBuscaFases['ABV_BANCOVAR']?></span>
                     </a>
                   
                       <?php
                       $count++;
                     }
                     
                     if ($tip_campanha == 20) {
                      
                      $sql2 = "select * from VARIAVEIS where COD_BANCOVAR in (33,34) order by NUM_ORDENAC";
                      $arrayQuery = mysqli_query($connAdm->connAdm(), $sql2) or die(mysqli_error());
                      while ($qrBuscaFasesCupom = mysqli_fetch_assoc($arrayQuery)) {
                        ?>
                        <a href="javascript:void(0)" class="btn btn-default btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
                          dragTagName="<?=$qrBuscaFasesCupom[KEY_BANCOVAR]?>" 
                          onclick='copy2clipboard("<?=$qrBuscaFases[KEY_BANCOVAR]?>")'>
                          <span><?=$qrBuscaFasesCupom['ABV_BANCOVAR']?></span>
                        </a>
                      
                        <?php
                        $count++;
                      }
                      
                    }
                    
                    
                    ?>

                  </fieldset>

                </div>

                <div class="col-md-2">
                  <fieldset>
                    <legend>Ferramentas</legend>

                    <div class="row">
                      
                      <div class="col-md-12">
                        <a href="javascript:void(0)" class="btn btn-xs btn-info previewOverlay" style="padding: 2px 5px;" data-toggle='tooltip' data-placement='top' data-original-title='visualizar resoluções'><span class="fal fa-desktop"></span>&nbsp;&nbsp;<span class="fal fa-mobile"></span></a>
                      &nbsp;&nbsp;
                        <a href="javascript:void(0)" class="btn btn-xs btn-info" style="padding: 2px 5px;" id="enviarTesteSimples" data-toggle='tooltip' data-placement='top' data-original-title='quick test'><span class="fal fa-paper-plane"></span>&nbsp;</a>
                      </div>

                    </div>

                  </fieldset>
                </div>

              </div>
                 
              
            <div class="col-md-12">
              <?php

                $path = "emailComponenteTeste/";

                include "emailComponenteTeste/index.php";

              ?>
            </div>

            <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

              <fieldset>
                <legend>Configurações</legend>

                <div class="row">

                  <div class="col-md-10">

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="inputName" class="control-label required">Título do e-Mail (subject)</label>
                        <input type="text" class="form-control input-sm" name="DES_ASSUNTO" id="DES_ASSUNTO" maxlength="100" value="<?=$des_assunto?>" required >
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="inputName" class="control-label required">Remetente do e-Mail (from name)</label>
                        <input type="text" class="form-control input-sm" name="DES_REMET" id="DES_REMET" maxlength="100" value="<?=$des_remet?>" required >
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="inputName" class="control-label required">Endereço do remetente</label>
                        <input type="text" class="form-control input-sm" name="END_REMET" id="END_REMET" maxlength="100" value="<?=$end_remet?>" required >
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="inputName" class="control-label required">Email de resposta</label>
                        <input type="text" class="form-control input-sm" name="EMAIL_RESPOSTA" id="EMAIL_RESPOSTA" maxlength="100" value="<?=$email_resposta?>" required>
                      </div>
                      <div class="help-block with-errors"></div>
                    </div>

                  </div>

                  <div class="col-md-2">   
                    <div class="form-group">
                      <label for="inputName" class="control-label">Personalizar Opt-Out</label> 
                      <div class="push5"></div>
                      <label class="switch">
                        <input type="checkbox" name="LOG_OPT" id="LOG_OPT" class="switch" value="S" <?php echo ($log_opt == 'S' ? 'checked' : ''); ?> >
                        <span></span>
                      </label>
                    </div>
                  </div>

                </div>

              </fieldset>

              <div class="push10"></div>

              <fieldset id="optout" style="display: <?php echo ($log_opt == 'S' ? 'block' : 'none'); ?>;">
                <legend>Opt-Out</legend> 

                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">Texto do Opt-Out</label>
                      <input type="text" class="form-control input-sm" name="TXT_OPT" id="TXT_OPT" maxlength="200" value="<?=($txt_opt != '' ? $txt_opt : 'Caso não queira mais receber nossos e-mails')?>" required >
                    </div>
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">Texto do link</label>
                      <input type="text" class="form-control input-sm" name="TXT_LINKOPT" id="TXT_LINKOPT" maxlength="200" value="<?=($txt_linkopt != '' ? $txt_linkopt : 'clique aqui')?>" required >
                    </div>
                    <div class="help-block with-errors"></div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">Tag do Opt-Out</label>
                      <input type="text" class="form-control input-sm" name="TAG_OPT" id="TAG_OPT" maxlength="200" readonly value="<#OPTOUT>" required >
                    </div>
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="inputName" class="control-label required">Tag do link</label>
                      <input type="text" class="form-control input-sm" name="TAG_LINKOPT" id="TAG_LINKOPT" maxlength="200" readonly value="<#LINKOPTOUT>" required >
                    </div>
                    <div class="help-block with-errors"></div>
                  </div>

                </div>

              </fieldset>

              <div class="push20"></div>
              <div class="form-group text-right col-lg-12">
                <button type="submit" class="btn btn-primary" id="salvaTemplate"><i class="fal fa-save"></i>&nbsp; Salvar Template</button>
              </div>
              <div class="push5"></div>
              <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
              <input type="hidden" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?=$cod_template?>">
              <input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?=$cod_campanha?>">
              <input type="hidden" name="html" id="html" value="">
              <input type="hidden" name="tipo" id="tipo" value="<?=$opcao?>">
              <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" /> 
              <input type="hidden" name="hHabilitado" id="hHabilitado" value="S">   
            </form>                

          </div>
        </div>
      </div>
      <!-- fim Portlet -->
    </div>

  </div>

  <!-- modal -->                  
  <div class="modal fade" id="popModalEnvio" tabindex='-1'>
    <div class="modal-dialog" style="">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <form id="envioTeste" action="">
            <fieldset>
              <legend>Dados do envio</legend> 
              
                <div class="row">

                  <div class="col-md-10">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Emails</label>
                      <input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" maxlength="400" required>
                      <div class="help-block with-errors">Separar múltiplos emails com ";"</div>
                    </div>
                  </div>
                  
                  <div class="col-md-2">
                    <div class="push10"></div>
                    <div class="push5"></div>
                    <a href="javascript:void(0)" id="dispararTeste" class="btn btn-primary btn-sm btn-block getBtn" style="margin-top: 2px;"><i class="fal fa-paper-plane" aria-hidden="true"></i>&nbsp; Envio de teste</a>
                  </div>
                            
                  <input type="hidden" name="DES_TEMPLATE_ENVIO" id="DES_TEMPLATE_ENVIO">
                  <input type="hidden" name="DES_ASSUNTO_ENVIO" id="DES_ASSUNTO_ENVIO">

                </div>
                  
            </fieldset>
          </form>
        </div>    
      </div>
    </div>
  </div>  

  <div class="push20"></div> 

  <script>

    var previewOverlay = 0;

    $(function(){

      $('.previewOverlay').click(function(){

        if(previewOverlay == 0){
			HTML2Preview();
          $("#previewOverlay").fadeIn('fast');
          previewOverlay = 1;
        }else{
          $("#previewOverlay").fadeOut('fast');
          previewOverlay = 0;
        }

      });

      $("#enviarTesteSimples").click(function(){

        $("#popModalEnvio").modal();

      });

      $("#dispararTeste").click(function(){
        $("#envioTeste #DES_TEMPLATE_ENVIO").val($("#DES_TEMPLATE").val());
        $("#envioTeste #DES_ASSUNTO_ENVIO").val($("#DES_ASSUNTO").val());
        if($("#DES_EMAILUS").val().trim() != ""){
          if($("#DES_ASSUNTO_ENVIO").val().trim() == ""){
            $.alert({
              title: "Aviso",
              content: "A template não possui assunto definido. Deseja enviar sem?",
              type: 'orange',
              buttons: {
                "ENVIAR": {
                  btnClass: 'btn-blue',
                   action: function(){
                     envioTeste();
                   }
                },
                "DEFINIR ASSUNTO": {
                  btnClass: 'btn-default',
                   action: function(){
                     $("#popModalEnvio").modal('close');
                     $("#DES_ASSUNTO").focus();
                   }
                }
              }
            });
          }else{
            envioTeste();
          }
        }else{
          $.alert({
            title: "Aviso",
            content: "O campo de emails não pode ser vazio",
            type: 'orange',
            buttons: {
              "OK": {
                btnClass: 'btn-blue',
                 action: function(){
                   
                 }
              }
            },
            backgroundDismiss: true
          });
        }
      });

	var drag_count = 0;
	$('.dragTag').on('dragstart', function (event) {
		var tag = $(this).attr('dragTagName');
		event.originalEvent.dataTransfer.setData("text", ' ' + tag + ' ');
		event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
		drag_count++;
		
		if (drag_count <= 1){
			console.log(tag);

			var iframe = document.getElementById('html5editor_ifr');
			iframe.contentWindow.document.addEventListener("drop", function( event ) {
				event.preventDefault();
				var el = event.dataTransfer.getData('text/html');
				var tag = $(el).attr("dragtagname");
				if (tag != undefined && tag != ""){
					$("body").after("<div id='__dragEvent' style='display:none'></div>");
					$("#__dragEvent").html(event.target.innerHTML);
					$("#__dragEvent").find(".dragTag").replaceWith(tag);
					event.target.innerHTML=$("#__dragEvent").html();
					$("#__dragEvent").remove();
				}
			}, false);
		}
	});


    $('#LOG_OPT').change(function () {
      if ($('#LOG_OPT').prop("checked")) {
        $('#optout').fadeIn('fast');
        $('#TXT_OPT').prop('required', true);
      } else {
        $('#optout').fadeOut('fast',function(){
          $('#TXT_OPT').val('').prop('required', false);
          $('#TAG_OPT').val('').prop('required', false);
          $('#TXT_LINKOPT').val('').prop('required', false);
          $('#TAG_LINKOPT').val('').prop('required', false);
        });
      }
    });

    $("#salvaTemplate").click(function(e){
      if(!$(this).hasClass("disabled")){
        e.preventDefault();
    Preview2HTML();
    $('#html').val($('#DES_TEMPLATE').val());
    HTML2Preview()
        $("#blocker").show();
        $.ajax({
          method: 'POST',
          url: 'ajxSalvaTemplateEmail.do?id=<?=fnEncode($cod_empresa)?>',
          data: $("#formulario").serialize(),
          success:function(data){
            if(data == 'erro_tmplt'){
              $.alert({
                title: "ERRO AO SALVAR TEMPLATE",
                content: "Por favor, revise sua template e tente novamente.",
                type: 'red',
                buttons: {
                  "OK": {
                    btnClass: 'btn-blue',
                     action: function(){
                       
                     }
                  }
                },
                backgroundDismiss: true
              });
            }else if(data.trim().substring(0,5) != 'https'){
              $.alert({
                title: "A ferramenta de envio está em manutenção.",
                content: "A template foi salva com <b>sucesso</b>, mas a sincronização falhou. <br/>Por favor, tente novamente <b>mais tarde</b>.",
                type: 'yellow',
                buttons: {
                  "OK": {
                    btnClass: 'btn-blue',
                     action: function(){
                       
                     }
                  }
                }
              });
            }else{
              window.location.replace(data);
            }
            $("#blocker").hide();
            // console.clear();
            console.log(data);
            // alert(data);
          },
          error:function(){
            console.log("erro 500");
          }
        });
      }
    }); 

  });

	function copy2clipboard(txt) {
		setTimeout(() => {
			if ($("#__clipboard").length <= 0) {
				$("body").after("<input style='position:absolute;top:0;left:0' id='__clipboard' type='text'>");
			}

			$("#__clipboard").show();
			$("#__clipboard").val(txt);
			$("#__clipboard").focus();
			$("#__clipboard").select();
			document.execCommand('copy')
			$("#__clipboard").hide();
		}, 100);
	}


    function quickCopy2(index) {
      var dummy = document.createElement("textarea");
      document.body.appendChild(dummy);
      dummy.value = $('#KEY_BANCOVAR_'+index).val();
      dummy.select();
      document.execCommand("copy");
      // alert(dummy.value);
      document.body.removeChild(dummy);
    }

    function envioTeste(){
      $.ajax({
          method: 'POST',
          url: 'ajxEnvioTesteSimples.do?id=<?=fnEncode($cod_empresa)?>',
          data: $("#envioTeste").serialize(),
          beforeSend:function(){
            $("#dispararTeste").html("<center><div class='loading' style='width:50%'></div></center>");
          },
          success:function(data){

            $("#dispararTeste").html("<span class='fas fa-check'></span>&nbsp;Teste enviado")
                               .removeClass("btn-primary")
                               .addClass("btn-success")
                               .attr('disabled',true)
                               .attr('id','disparadoTeste');

            setInterval(function(){
              $("#disparadoTeste").fadeOut('fast')
                                 .html("<span class='fal fa-paper-plane'></span>&nbsp;Envio de teste")
                                 .removeClass("btn-success")
                                 .addClass("btn-primary")
                                 .attr('disabled',false)
                                 .attr('id','dispararTeste')
                                 .fadeIn('fast');
            },15000);

            $.alert({
              title: "Sucesso",
              content: "O seu teste foi enviado! Verifique seu email (essa operação pode levar alguns minutos).",
              type: 'green',
              buttons: {
                "OK": {
                  btnClass: 'btn-blue',
                   action: function(){
                     
                   }
                }
              },
              backgroundDismiss: true
            });

            console.log(data);

          },
          error:function(){

            console.log("erro 500");

          }
        });
    }

  </script>