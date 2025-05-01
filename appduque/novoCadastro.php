<?php 
include './_system/_functionsMain.php';	
$msgErro = "";
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
        session_unset();
        unset($_SESSION["login"]);
       
        
      // header("Location:https://www.rededuque.com.br/app/");
       header("Location:https://adm.bunker.mk/appduque/"); 
        
    }
?>		


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
		
        <title>Rede Duque</title>
		
		<?php include "cssLib.php"; ?>		

    </head>

    <body class="bgColor" data-gr-c-s-loaded="true">
		<?php 
		$tituloPagina = "Cadastro";
		include "menu.php";
		?>	
		

      <form data-toggle="validator" role="form2" method="post" id="formulario" action="dadosCadastro.do">

        <div class="container">

        <div class="push30"></div>

        <div class="row text-center">
          <h1 style="font-weight: 900!important;">APP DUQUE</h1>
        </div>

        <div class="push50"></div>

        <div class="row text-center">
          <div class="col-xs-12">
            <p><b>Cadastre-se e usufrua dos preços exclusivos que a Rede Duque oferece aos usuários App Duque</b></p>
          </div>
        </div>

        <div class="push30"></div>

        <div class="row">   
          
          <div class="col-xs-8 col-xs-offset-2 text-center">
            <div class="form-group">
              <label for="inputName" class="control-label required"><h4 style="font-weight: 900!important;"><b>INFORME SEU CPF</b></h4></label>
              <div class="push5"></div>
              <input type="tel" id="cpf" name="cpf" class="form-control input-hg cpfcnpj text-center" placeholder="Seu CPF" maxlength="14">
              <div class="help-block with-errors"></div>
            </div>
          </div>

        </div>

        <div class="push50"></div>

        <input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">

        <div class="row">       
          
          <div class="col-xs-10 col-xs-offset-1">
            <button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-block getBtn shadow" tabindex="5">Continuar</button>
          </div>
              
          
        </div><!-- /row -->
            

        </div> <!-- /container -->

    </form>	

		<?php include 'jsLib.php';?>		
    </body>
</html>

<script>

  $(function(){

    $("#CAD").click(function(e){
      e.preventDefault();
      if($("#cpf").val() != ""){
        if(!valida_cpf_cnpj($("#cpf").val())){
          $.alert({
            title: 'Atenção!',
            content: 'CPF digitado é inválido!',
          });     
        }else{
          buscaCPF($("#cpf").val());
        }
      }else{
        $.alert({
          title: "AVISO",
          content: "CPF não informado.",
          type: 'red',
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


  });
  
  if($('.cpfcnpj').val() != undefined){
    mascaraCpfCnpj($('.cpfcnpj'));
  }

  function buscaCPF(cpf){
    $.ajax({
        method: 'POST',
        url: 'ajxBuscaCpf.php',
        data:{COD_EMPRESA:"<?=fnEncode($cod_empresa)?>",CPF:cpf},
        beforeSend:function(){
          $('#cpf').attr('readonly',true);
          $('#CAD').prop('disabled',true);
          $('#CAD').html('<div class="loading" style="width: 100%;"></div>');
        },
        success:function(data){
          console.log(data);
          if(data == 0){
            $("#formulario").submit();
          }else{
            $('#cpf').attr('readonly',false);
            $('#CAD').prop('disabled',false);
            $('#CAD').html('Continuar');
            $.alert({
              title: "ESSE CPF JÁ EXISTE!",
              content: "Para acessar, faça o login ou tente recuperar a senha.",
              type: 'blue',
              backgroundDismiss: true,
              buttons: {
                "LOGIN": {
                   action: function(){
                    window.location.href = "novoLogin.do";
                   }
                },
                "RECUPERAR SENHA": {
                  btnClass: 'btn-blue shadow',
                   action: function(){
                    window.location.href = "reenvioSenha.do";
                   }
                }
              }
            });
          }
        },
        error:function(data){
          console.log(data);
        }
      });
  }

  function valida_cpf_cnpj ( valor ) {

    // Verifica se é CPF ou CNPJ
    // var valida = verifica_cpf_cnpj( valor );
    var valida = 'CPF'

    // Garante que o valor é uma string
    valor = valor.toString();
    
    // Remove caracteres inválidos do valor
    valor = valor.replace(/[^0-9]/g, '');


    // Valida CPF
    if ( valida === 'CPF' ) {
      // Retorna true para cpf válido
      return valida_cpf( valor );
    } 
    
    // Valida CNPJ
    else if ( valida === 'CNPJ' ) {
      // Retorna true para CNPJ válido
      return valida_cnpj( valor );
    } 
    
    // Não retorna nada
    else {
      return false;
    }
  }

  function valida_cpf( valor ) {

    // Garante que o valor é uma string
    valor = valor.toString();
    
    // Remove caracteres inválidos do valor
    valor = valor.replace(/[^0-9]/g, '');


    // Captura os 9 primeiros dígitos do CPF
    // Ex.: 02546288423 = 025462884
    var digitos = valor.substr(0, 9);

    // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
    var novo_cpf = calc_digitos_posicoes( digitos );

    // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
    var novo_cpf = calc_digitos_posicoes( novo_cpf, 11 );

    // Verifica se o novo CPF gerado é idêntico ao CPF enviado
    if ( novo_cpf === valor ) {
      // CPF válido
      return true;
    } else {
      // CPF inválido
      return false;
    }
    
  }

  function calc_digitos_posicoes( digitos, posicoes = 10, soma_digitos = 0 ) {

    // Garante que o valor é uma string
    digitos = digitos.toString();

    // Faz a soma dos dígitos com a posição
    // Ex. para 10 posições:
    //   0    2    5    4    6    2    8    8   4
    // x10   x9   x8   x7   x6   x5   x4   x3  x2
    //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
    for ( var i = 0; i < digitos.length; i++  ) {
      // Preenche a soma com o dígito vezes a posição
      soma_digitos = soma_digitos + ( digitos[i] * posicoes );

      // Subtrai 1 da posição
      posicoes--;

      // Parte específica para CNPJ
      // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
      if ( posicoes < 2 ) {
        // Retorno a posição para 9
        posicoes = 9;
      }
    }

    // Captura o resto da divisão entre soma_digitos dividido por 11
    // Ex.: 196 % 11 = 9
    soma_digitos = soma_digitos % 11;

    // Verifica se soma_digitos é menor que 2
    if ( soma_digitos < 2 ) {
      // soma_digitos agora será zero
      soma_digitos = 0;
    } else {
      // Se for maior que 2, o resultado é 11 menos soma_digitos
      // Ex.: 11 - 9 = 2
      // Nosso dígito procurado é 2
      soma_digitos = 11 - soma_digitos;
    }

    // Concatena mais um dígito aos primeiro nove dígitos
    // Ex.: 025462884 + 2 = 0254628842
    var cpf = digitos + soma_digitos;

    // Retorna
    return cpf;
    
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
  
</script>