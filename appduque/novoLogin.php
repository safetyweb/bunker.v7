<?php 
include './_system/_functionsMain.php';
$cod_empresa = 19;
//Rotina de logoff
if($_GET['logoff']=='1'){
    //session_destroy();
   // session_unset();
    unset($_SESSION["login"]);

    $server = $_SERVER['SERVER_NAME'];

    $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
    $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");

    if($server == 'adm.bunker.mk'){
      $dominio = "adm.bunker.mk/appduque";
    }else{
      if($iPod || $iPhone || $iPad){
        $dominio = $server."/appduque";
      }else{
        if($server == "adm.bunkerapp.com.br"){
          $dominio = $server."/appduque";
        }else{
          $dominio = $server;
        }
        
      }

    }
    
   //header("Location:https://www.rededuque.com.br/app/");
   header("Location:https://$dominio/"); 
    
}
if($_COOKIE['login'] == 'true'){
?>
  <script type="text/javascript">
    document.cookie = "login=";
    document.cookie = "senha=";
  </script>
<?php 
}

?>		


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
        <link rel="shortcut icon" href="#">
		
        <title>Rede Duque</title>
		
		<?php include "cssLib.php"; ?>		

    </head>

    <style>
      .logo{
        margin-top: 25px;
        margin-bottom: 25px;
      }
      #senha{
        border-radius: 0px; 
      }

      .bgColor {
          background-color: #03204F;
          /*background-image: url(img/bg_intro.jpg);*/
          background-position: center; 
          background-size: cover;
      }

      .btn-primary{
        background: #84B1F5!important;
        border-color: #84B1F5!important
      }

      .text-white{
          color: #fff!important;
      }
      .text-right{
        float: right;
      }

    </style>

    <body class="bgColor" data-gr-c-s-loaded="true">
		<?php 
		// $tituloPagina = "Faça seu login";
		// include "menu.php"; 
		?>
    <div class="row">
      <div class="col-xs-3 text-center">
        <a href="index.php"><i class="fa fa-arrow-left fa-2x text-white" aria-hidden="true"></i></a>
      </div>
    </div>	
		
        <div class="container">

          <div class="push30"></div>

            <div class="text-center pagination-centered">
                <a class="">
                    <img alt="" class="logo img-responsive center-block" src="img/logo_intro_big.png" width="80%">
                </a>
            </div>

            <div class="push10"></div>

            <div class="row">	

              <div class="col-xs-10 col-xs-offset-1">

                <?php

                  $login = $_COOKIE['login'];
                  $senha = $_COOKIE['senha'];

                  if($login == 'true'){
                    $login = "";
                  }

                  if($senha == 'true'){
                    $senha = "";
                  }

                ?>

                <form class="form-signin" method="post">
                    <input type="text" class="form-control text-center" name="email" id="email" value="<?php echo $login;?>" placeholder="Informe seu email ou CPF" required style="border-radius: 0px;">
                    <div class="push10"></div>
                    <input type="password" class="form-control text-center" name="senha" id="senha"  value="<?php echo $senha;?>" placeholder="Sua senha" maxlength="6" autocomplete="new-password" required >
                    <?php if($login != "" && $senha != ""){ ?>
                      <a href="javascript:void(0)" onclick="zeraLogin(this)" class="text-white">Não sou este usuário</a>
                    <?php } ?>
                    <a href="reenvioSenha.do" class="text-white text-right">Recuperar senha</a>
                    <div class="push50"></div>
                    <button type="button" class="btn btn-primary btn-block" name="btLogin" id="btLogin">ENTRAR</button>
                    <div class="push20"></div>
                    <div class="errorLogin" style="background: #fff; color: red; text-align: center; display: none">Email ou senha inválido(s).</div>

                    <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">

                </form>

              </div>

            </div>
			
            <div class="push10"></div>
			
            <!-- <center><a href="reenvioSenha.do" class="btn btn-default">Esqueci a senha</a></center> -->
			
            <div class="push20"></div>

        </div> <!-- /container -->	
		<?php include 'jsLib.php';?>		
    </body>
</html>

<script>
  
  $('#btLogin').click(function() {

    if(!$(this).attr("disabled")){
  
      var pEmail = $('#email').val().trim(),
      pSenha = $('#senha').val().trim(),
      cod_empresa = $('#COD_EMPRESA').val(),
      keep='';
      
      // alert(cod_empresa);
      if($('#keep').attr("type") == "checkbox"){
           if($('#keep').prop("checked")){
              keep = "on";
           }
      }    
      $.ajax({
          type: "POST",                
          url: "ajxLogin.do",
          data: { email:pEmail, senha:pSenha, COD_EMPRESA: cod_empresa, keep:keep },
          beforeSend:function(){
            $("#btLogin").html("<div class='loading' style='width:100%'></div>");
            $("#btLogin").attr("disabled",true);
          },
          success: function(msg) {
              console.log(msg);
              if(msg != 0){
                  console.log(msg);
                  window.location.replace(msg);
              }else{
                  $('.errorLogin').show();
                  $("#btLogin").html("ENTRAR");
                  $("#btLogin").removeAttr("disabled",false);

              }
          }
      });

    }
          
  });

  // $("#email").keydown(function(e){
  //   if (e.keyCode >= 65 && e.keyCode <= 90){
  //     $(this).unmask();
  //     $(this).removeClass('cpfcnpj');
  //   }else if($(this).val() == ""){
  //      $(this).addClass('cpfcnpj');
  //   }
  // });

  if($('.cpfcnpj').val() != undefined){
    mascaraCpfCnpj($('.cpfcnpj'));
  }
   function mascaraCpfCnpj(cpfCnpj){
      var optionsCpfCnpj = {
        onKeyPress: function (cpf, ev, el, op) {
          var masks = ['000.000.000-000', '00.000.000/0000-00'],
            mask = (cpf.length >= 15) ? masks[1] : masks[0];
          cpfCnpj.mask(mask, op);
        }
      } 

      var masks = ['000.000.000-000', '00.000.000/0000-00'];
      mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];
        
      cpfCnpj.mask(mask, optionsCpfCnpj);   
    }

    function zeraLogin(btn){
      $(btn).fadeOut(1,function(){
        $("#email,#senha").val("");
        document.cookie = "login=";
        document.cookie = "senha=";
      });
    }

</script>