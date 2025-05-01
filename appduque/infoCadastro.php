<?php 
include './_system/_functionsMain.php';	
$msgErro = "";
$cod_empresa = 19;
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); 

	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
			
		@$login = $_REQUEST['login'];
		@$senha = $_REQUEST['senha'];
               
		$sql = "CALL sp_mk_login('$login','$senha')";
              
                
                   
                    try 
                    {
                     $result=mysqli_query($connDUQUE->connDUQUE(),$sql);
                     
                    } 
                    catch (mysqli_sql_exception $e) 
                    {
                       echo  $e;
                       
                      
                    } 

                if(!$result)
                {
                 echo "erro ao efetuar o login";   
                }    
                
                
                $row= mysqli_fetch_assoc($result);
                @$_SESSION["COD_RETORNO"]=$row['retorno'];
                @$cod_retorno=fnEncode($row['retorno']);
		@$_SESSION["LOGIN"]=$login;
             
		if($row['retorno']!='invalido')
                {
                                    
                   //header("Location:https://www.rededuque.com.br/app/home.php?secur=$cod_retorno&log=1");
                   header("Location: https://adm.bunker.mk/appduque/home.php?secur=$cod_retorno&log=1");
                                
                }else{
                                          
                         $msgErro =  'usuario e senha invalido'; 
                      //   header("Location: http://bunker.mk/appduque/login.php");
                }    
                
                
	}
	
    //Rotina de logoff
	if($_GET['logoff']=='1'){
        //session_destroy();
        //session_unset();
        unset($_SESSION["login"]);
       
        
      // header("Location:https://www.rededuque.com.br/app/");
       header("Location:https://adm.bunker.mk/appduque/"); 
        
    }

    $sql = "SELECT COD_CLIENTE,
                   NUM_CGCECPF,
                   NOM_CLIENTE, 
                   DAT_NASCIME,
                   NUM_CELULAR,
                   COD_SEXOPES,
                   DES_EMAILUS,
                   NUM_CEPOZOF
            FROM CLIENTES 
            WHERE COD_EMPRESA = 19 
            AND NUM_CARTAO = ".$_SESSION["COD_RETORNO"];

    $arrayQuery = mysqli_query(connTemp(19,''),$sql);

    $qrCli = mysqli_fetch_assoc($arrayQuery);

    if($qrCli['COD_SEXOPES'] == 1){
      $sexo = "Masculino";
    }else{
      $sexo = "Feminino";
    }

    // echo "<pre>";
    // print_r($_SERVER);
    // echo "</pre>";

?>		


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
		
        <title>Rede Duque</title>
		
		<?php include "cssLib.php"; ?>		

    </head>

    <!-- Fire the plugin onDocumentReady -->
        <script type="text/javascript">
           jQuery(document).ready(function( $ ) {
                $("#menu").mmenu();
            });
        </script>

    <body class="bgColor" data-gr-c-s-loaded="true">
		<?php 
		$tituloPagina = "Meu Cadastro";
		include "menu.php"; 
		?>

    <style type="text/css">
      .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -30px;
        position: relative;
        z-index: 2;
      }
    </style>	
		

    <div class="container">

        <div class="push30"></div>

        <div class="row">
          <div class="col-xs-10">
            <h4 style="font-weight: 900!important;">MEUS DADOS</h4>
          </div>
          <!-- <div class="col-xs-2"><span class="fa fa-pencil fa-2x"></span></div> -->
          <div class="col-md-12">
            <hr style="margin:0; border-color: #3c3c3c; width: 100%; max-width: 100%;">
          </div>
        </div>

        <div class="push30"></div>

        <div class="row">   

          <div class="col-xs-12">
            <b><p><?=ucwords(strtolower($qrCli['NOM_CLIENTE']))?></p></b>
          </div>

          <!-- <div class="col-xs-6 text-right">
            <b><p><?=$qrCli['DAT_NASCIME']?></p></b>
          </div> -->

        </div>

        <div class="push10"></div>

        <div class="row">   

          <div class="col-xs-12">
            <b><p><?=$qrCli['NUM_CELULAR']?></p></b>
          </div>

          <!-- <div class="col-xs-6 text-right">
            <b><p><?=$sexo?></p></b>
          </div> -->

        </div>

        <div class="push10"></div>

        <div class="row">   

          <div class="col-xs-6">
            <b><p><?=$qrCli['DES_EMAILUS']?></p></b>
          </div>

          <div class="col-xs-6 text-right">
            <b><p><?=$qrCli['NUM_CEPOZOF']?></p></b>
          </div>

        </div>

        <div class="push10"></div>

        <div class="row">   

          <div class="col-xs-6">
            <a href="javascript:void(0)" id="ALT"><b>Editar senha &nbsp;<span class="fa fa-edit"></span></b></a>
          </div>
          <div class="col-xs-6 text-right">
            <a href="alteraCadastro.do?<?=$_SERVER[QUERY_STRING]?>&idc=<?=fnEncode($qrCli[COD_CLIENTE])?>" class="text-info" id="CAD"><b>Editar Cadastro &nbsp;<span class="fa fa-pencil text-info"></span></b></a>
          </div>

        </div>

        <div class="row">
          
          <div class="push20"></div>
          <div class="push20"></div>
          <div class="col-md-12 text-center">
            <a href="javascript:void(0)" name="EXC" id="EXC" tabindex="5" onclick='ajxDescadastra("<?=fnEncode($qrCli[COD_CLIENTE])?>")' style="font-size: 16px;">Descadastrar-se</a>
          </div>
          <div class="push20"></div>

        </div>

        <div class="push50"></div>

        <div class="row">
          <div class="col-xs-10">
            <h4 style="font-weight: 900!important;">VEÍCULOS CADASTRADOS</h4>
          </div>
          <!-- <div class="col-xs-2"><span class="fa fa-pencil fa-2x"></span></div> -->
          <div class="col-md-12">
            <hr style="margin:0; border-color: #3c3c3c; width: 100%; max-width: 100%;">
          </div>
        </div>

        <div class="push30"></div>

        <div class="row">   

          <div class="col-xs-5 col-xs-offset-1">
            <div class="form-group">
              <input type="text" id="DES_PLACA" name="DES_PLACA" class="form-control input-hg text-center placa" placeholder="Sua placa" data-minlength="7" data-minlength-error="Formato inválido">
              <div class="help-block with-errors"></div>
            </div>
          </div>

          <div class="col-xs-5">
            <a href='javascript:void(0)' name="ADD" id="ADD" class="btn btn-info btn-sm shadow" tabindex="5" disabled><span class="fa fa-plus"></span>&nbsp; Adicionar</a>
          </div>

        </div>

        <div class="push10"></div>

        <div class="row" id="placasConteudo">

          <?php

            $sql = "SELECT COD_VEICULOS, DES_PLACA FROM VEICULOS WHERE COD_CLIENTE_EXT = $qrCli[NUM_CGCECPF] AND COD_EMPRESA = $cod_empresa AND TRIM(DES_PLACA) != '' AND DES_PLACA IS NOT NULL";
            // fnEscreve($sql);
            $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

            while ($qrVeic = mysqli_fetch_assoc($arrayQuery)) {

            ?>

              <div class="col-xs-8" style="font-size: 20px!important;">
                 <div class="col-xs-9">
                  <p class="text-muted"><span class="fa fa-car"></span>&nbsp; <?=$qrVeic['DES_PLACA']?></p>
                 </div>
                 <div class="col-xs-3 text-right">
                  <a href="javascript:void(0)" onclick='excPlaca("<?=fnEncode($qrVeic[COD_VEICULOS])?>")'><span class="fa fa-trash text-danger" style="padding-top: 3.5px;"></span></a>
                 </div>
              </div>

            <?php

            }

          ?>

        </div>        

    </div> <!-- /container -->

    

		<?php include 'jsLib.php';?>		
    </body>
</html>

<script>

  $(function(){

    $("#ALT").click(function(e){
      e.preventDefault();

      var form = "<form id='formularioSenha'>"+
                  "<div class='row'>"+
                      "<div class='col-xs-4'><b>Senha atual:</b></div>"+
                      "<div class='col-xs-8'>"+
                        "<div class='form-group'>"+
                          "<input type='password' id='DES_SENHAUS_OLD' name='DES_SENHAUS_OLD' class='form-control input-hg text-center' placeholder='Senha atual' maxlength='6'>"+
                          "<span toggle='#DES_SENHAUS_OLD' class='fa fa-fw fa-eye field-icon toggle-password' onclick='revelaSenha(this)'></span>"+
                          "<div class='help-block with-errors'></div>"+
                        "</div>"+
                      "</div>"+
                    "</div>"+
                    "<div class='row'>"+
                      "<div class='col-xs-4'><b>Nova senha:</b></div>"+
                      "<div class='col-xs-8'>"+
                        "<div class='form-group'>"+
                          "<input type='password' id='DES_SENHAUS' name='DES_SENHAUS' class='form-control input-hg text-center' placeholder='Nova senha' maxlength='6'>"+
                          "<span toggle='#DES_SENHAUS' class='fa fa-fw fa-eye field-icon toggle-password' onclick='revelaSenha(this)'></span>"+
                          "<div class='help-block with-errors'></div>"+
                        "</div>"+
                      "</div>"+
                    "</div>"+
                    "<div class='row'>"+
                      "<div class='col-xs-4'><b>Confirme a senha:</b></div>"+
                      "<div class='col-xs-8'>"+
                        "<div class='form-group'>"+
                          "<input type='password' id='DES_SENHAUS_CONF' name='DES_SENHAUS_CONF' class='form-control input-hg text-center' placeholder='Confirme a senha' maxlength='6'>"+
                          "<div class='help-block with-errors'></div>"+
                        "</div>"+
                      "</div>"+
                    "</div>"+
                  "</form>";

      $.alert({
        title: "EDITAR SENHA",
        content: form,
        type: 'blue',
        backgroundDismiss: true,
        buttons: {
          "CANCELAR":function(){
          
          },
          "ATUALIZAR": {
            btnClass: 'btn-blue shadow',
             action: function(){
              if($("#DES_SENHAUS").val() == $("#DES_SENHAUS_CONF").val()){
                var formulario = $("#formularioSenha");
                var cod_cliente = "<?=fnEncode($qrCli[COD_CLIENTE])?>";
                atualizaSenha(formulario,cod_cliente);
              }else{
                $.alert({
                  title: "AVISO",
                  content: "Senhas diferentes.",
                  buttons: {
                    "OK":function(){
                      $("#ALT").click();
                    }
                  },
                  backgroundDismiss: function(){
                    return "OK";
                  },
                });
              }
             }
          }
        }
      });

    });

    $("body").delegate('input.placa','paste', function(e) {
          $(this).unmask();
      });
    $("body").delegate('input.placa','input', function(e) {
        $('input.placa').mask(MercoSulMaskBehavior, mercoSulOptions);
    });

    $("#DES_PLACA").keyup(function(){
      if(($(this).parent().hasClass('has-error') || $(this).val() == "" || $(this).val().replace('-','').length < 7)){
        // alert("erro");
        $("#ADD").attr("disabled",true);
      }else{
        // alert("ok");
        $("#ADD").removeAttr("disabled",false);
      }
    });

    $("#ADD").click(function(){
      if(!$("#ADD").attr('disabled')){
        $.ajax({
          method: 'POST',
          url: 'ajxCadVeiculo.php',
          data: {COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", DES_PLACA: $("#DES_PLACA").val(), COD_CLIENTE: "<?=fnEncode($qrCli[COD_CLIENTE])?>",NUM_CGCECPF: "<?=fnEncode($qrCli[NUM_CGCECPF])?>"},
          beforeSend:function(){
            $("#placasConteudo").html('<div class="loading" style="width: 100%;"></div>');
            $("#ADD").attr("disabled",true);
          },
          success:function(data){
            $("#placasConteudo").html(data);
            $("#DES_PLACA").val('');
            $("#REFRESH_PLACA").val('S');
          }
        });
      }else{
         
      }
    });

  });


  function atualizaSenha(form,cod_cliente){
    $.ajax({
        type: "POST",                
        url: "ajxAlteraSenha.php?idc="+cod_cliente,
        data: form.serialize(),
        success: function(data) {
            if(data == 1){
              $.alert({
                title: "SENHA ATUALIZADA",
                content: "Sua senha foi alterada com sucesso.",
                type: 'green',
                columnClass: 'col-xs-12',
                buttons: {
                  "OK": {
                    btnClass: 'btn-blue shadow',
                     action: function(){
                     }
                  }
                },
                backgroundDismiss: true
              });
            }else{
              $.alert({
                title: "ERRO AO ALTERAR",
                content: "Sua senha antiga está incorreta. Por favor, tente novamente.",
                type: 'red',
                columnClass: 'col-xs-12',
                buttons: {
                  "OK": {
                    btnClass: 'btn-blue shadow',
                     action: function(){
                        $("#ALT").click();
                     }
                  }
                },
                backgroundDismiss: true
              });
            }
        }
    });
  };

  function revelaSenha(el){
    $(el).toggleClass("fa-eye fa-eye-slash");
      var input = $($(el).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
  }

  function excPlaca(cod_veiculos){
    $.ajax({
      method: 'POST',
      url: 'ajxCadVeiculo.php?opcao=excluir',
      data: {COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", COD_CLIENTE: "<?=fnEncode($qrCli[COD_CLIENTE])?>", NUM_CGCECPF: "<?=fnEncode($qrCli[NUM_CGCECPF])?>", COD_VEICULOS:cod_veiculos},
      beforeSend:function(){
        $("#placasConteudo").html('<div class="loading" style="width: 100%;"></div>');
      },
      success:function(data){
        $("#placasConteudo").html(data);
        $.alert({
          title: "AVISO",
          content: "Veículo excluído.",
          columnClass: 'col-xs-12',
          backgroundDismiss: true,
          buttons: {
            "OK": {
              btnClass: 'btn-blue shadow',
               action: function(){
               }
            }
          }
        });
      }
    });
  }

  var MercoSulMaskBehavior = function (val) {
    var myMask = 'SSS0A00';
    var mercosul = /([A-Za-z]{3}[0-9]{1}[A-Za-z]{1})/;
    var normal = /([A-Za-z]{3}[0-9]{2})/;
    var replaced = val.replace(/[^\w]/g, '');
    if (normal.exec(replaced)) {
        myMask = 'SSS-0000';
    } else if (mercosul.exec(replaced)) {
        myMask = 'SSS0A00';
    }
        return myMask;
  },

  mercoSulOptions = {
      onKeyPress: function(val, e, field, options) {
          field.mask(MercoSulMaskBehavior.apply({}, arguments), options);
      }
  };

  function ajxDescadastra(cod_cliente){

    $.alert({
          title: "Confirmação",
          content: "Deseja excluir seus dados de forma <b>definitiva</b>?",
          type: 'red',
          buttons: {
            "EXCLUIR": {
               btnClass: 'btn-danger',
               action: function(){
                
                    $.alert({
                      title: "Aviso!",
                      content: "<b>Todos</b> os dados serão excluídos <b>permanentemente</b>. Deseja <b>realmente</b> continuar?",
                      type: 'red',
                      buttons: {
                        "EXCLUIR PERMANENTEMENTE": {
                           btnClass: 'btn-danger',
                           action: function(){
                              $.ajax({
                  type: "POST",
                  url: "ajxDescadastro.do?id=<?php echo fnEncode($cod_empresa); ?>",
                  data: { COD_CLIENTE: cod_cliente },
                  beforeSend:function(){
                    $("#blocker").show();
                  },
                  success:function(data){ 
                    window.location.href = "descadastro.do";        
                  },
                  error:function(){
                      console.log('Erro');
                  }
                });
                           }
                        },
                        "CANCELAR": {
                          btnClass: 'btn-default',
                           action: function(){
                            
                           }
                        }
                      },
                      backgroundDismiss: function(){
                          return 'CANCELAR';
                      }
                    });

               }
            },
            "CANCELAR": {
              btnClass: 'btn-default',
               action: function(){
                
               }
            }
          },
          backgroundDismiss: function(){
              return 'CANCELAR';
          }
        });

  }
  
</script>