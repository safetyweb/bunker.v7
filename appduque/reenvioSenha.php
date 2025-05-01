<?php 
include './_system/_functionsMain.php';
include '../_system/PHPMailer/class.phpmailer.php';
// echo fnDebug('true');

// $arrayCampos = explode(";", $key);


$hashLocal = mt_rand();
$cod_empresa = 19; 

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
  
    $_SESSION['last_request']  = $request;

    $cod_cliente = fnLimpaCampoZero(fnDecode($_REQUEST['COD_CLIENTE']));
    $des_senhaus = fnEncode($_REQUEST['DES_SENHAUS']);

    // fnEscreve(fnDecode($des_senhaus));

    $sql = "UPDATE CLIENTES SET DES_SENHAUS = '$des_senhaus' WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = $cod_empresa";

    // fnescreve($sql);

    mysqli_query(connTemp($cod_empresa,''),$sql);

    $msgRetorno = "SENHA ALTERADA COM <b>SUCESSO</b>!</center><br><a href='novoLogin.do' style='color:#fff!important; text-decoration: underline;'>CLIQUE AQUI</a> PARA FAZER LOGIN";
    $msgTipo = 'alert-success';

    // $cpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['cpf']));         
    // // $email = fnLimpaCampo($_REQUEST['email']);

    // $sql = "SELECT NOM_CLIENTE, DES_SENHAUS, DES_EMAILUS FROM CLIENTES WHERE COD_EMPRESA = 19 AND NUM_CGCECPF = '$cpf'";
    // // fnEscreve($sql);
    // $arrayQuery = mysqli_query(connTemp(19,''),$sql);

    // $linhas = mysqli_num_rows($arrayQuery);

    // if($linhas == 1){
    // 	$qrSenha = mysqli_fetch_assoc($arrayQuery);
    	//MONTAGEM DO E-MAIL
       
  //       include '../externo/email/envio_sac.php';

  //       $nome = explode(" ",$qrSenha['NOM_CLIENTE']);

  //   	$texto_envio = "Olá ".ucfirst(strtolower($nome[0])).",<br>Conforme solicitado, estamos enviando abaixo a sua senha de cadastro do aplicativo:<br><b>".fnDecode($qrSenha['DES_SENHAUS'])."</b>";
  //   	$emailDestino = array('email5'=>"suporte@markafidelizacao.com.br;$qrSenha[DES_EMAILUS]");
  //   	$retorno = fnsacmail(
		// 		$emailDestino,
		// 		'Suporte Marka',
		// 		"<html>".$texto_envio."</html>",
		// 		"Recuperação de senha APP Duque",
		// 		'APP Duque',
		// 		$connAdm->connAdm(),
		// connTemp(19,""),'19');

  //       $email_repartido = explode("@", $qrSenha['DES_EMAILUS']);
  //       $tam_email = strlen($email_repartido[0]);
  //       $tam_email_calculado = round(($tam_email*30)/100);
  //       $tam_email_escondido = $tam_email - $tam_email_calculado;
  //       $email_apresentado = substr($email_repartido[0],0,-$tam_email_escondido);

  //       $pontos = "";
  //       for ($i=1; $i <=$tam_email_escondido ; $i++) { 
  //          $pontos .= "*";
  //       }

    	// $msgRetorno = "Sua senha foi enviada ao seu email de cadastro:<br>".$email_apresentado.$pontos."@".$email_repartido[1];
        
  //   }else{
  //   	$msgRetorno = "CPF não encontrado.";
		// $msgTipo = 'alert-warning';
  //   }
                      
                       
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
        // $msgTipo = 'alert-success';             
      
    }              
    
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

    <style>

        body{
            overflow: hidden;
        }
        
        .bgColor {
            background-color: #03204F;
            /*background-image: url(img/bg_intro.jpg);*/
            background-position: center; 
            background-size: cover;
        }

        .text-white{
            color: #fff!important;
        }

          .field-icon {
            float: right;
            margin-left: -25px;
            margin-top: -30px;
            position: relative;
            z-index: 2;
          }  

    </style>

    <body class="bgColor" data-gr-c-s-loaded="true">
		<?php 
		// $tituloPagina = "Nova Senha";
		// include "menu.php"; 
		?>

        <div class="row">
          <div class="col-xs-3 text-center">
            <a href="index.do"><i class="fa fa-arrow-left fa-2x text-white" aria-hidden="true"></i></a>
          </div>
        </div>  	
		
        <div class="container" id="relatorioConteudo">

            <div class="push20"></div>


            <?php if ($msgRetorno != ""){
            ?>
                <div class="alert <?=$msgTipo?>" role="alert">
                <?php echo $msgRetorno; ?>
                </div>          
            <?php   
            }else{
            ?>
                <div class="push50"></div>
                <div class="push20"></div>
                <div style="height: 3px;"></div>
            <?php
            }
            ?>

	        <div class="row text-center">
	          <div class="col-xs-12">
	            <h4 class="text-white" style="font-weight: 900;">INFORME CORRETAMENTE O CPF DO CADASTRO</h4>
	          </div>
	        </div>

            <div class="push30"></div> 

            <form class="form-signin" method="post" action="reenvioSenha.php">
                <label for="cpf" class="sr-only">CPF</label>
                <input type="text" name="CPF" id="CPF" class="form-control cpfcnpj text-center" placeholder="Seu CPF" maxlength="14" required="" autofocus="">
                <!-- <div class="push10"></div>
                <label for="email" class="sr-only">Email</label>
                <input type="email" name="email" id="email" class="form-control text-center" placeholder="e-Mail" required=""> -->
                <div class="push50"></div>
                <!-- <button class="btn btn-default btn-block" type="submit" id="enviar">BUSCAR</button> -->
                <a href="javascript:void(0)" class="btn btn-default btn-block" data-type="CPF" onclick='trocaSenha($(this).attr("data-type"))'>CONTINUAR</a>
            </form>
			
	   </div> <!-- /container -->	
		<?php include 'jsLib.php';?>		
    </body>
</html>

<script>
	$(function(){

		if($('.cpfcnpj').val() != undefined){
		    mascaraCpfCnpj($('.cpfcnpj'));
		}

        $("#enviar").click(function(){
            $(this).html('<div class="loading" style="width:100%"></div>');
        });

        $(document).keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                event.preventDefault();    
            }
        });

	});

    function trocaSenha(opcao){
        if(!valida_cpf_cnpj($("#CPF").val()) || $("#CPF").val() == "000.000.000-00"){
          $.alert({
            title: 'Atenção!',
            content: 'CPF digitado é inválido!',
          });     
        }else{
            $.ajax({
                method: 'POST',
                url: 'ajxEsqueciSenha.do?opcao='+opcao,
                data: {COD_EMPRESA: '<?=fnEncode($cod_empresa)?>', CAMPO: $("#"+opcao).val()},
                beforeSend:function(){
                    $("#relatorioConteudo").html('<div class="push100"></div><div class="loading" style="width:100%"></div>');
                },
                success:function(data){
                    $("#relatorioConteudo").html(data);
                    console.log(data);
                }
            });
        }
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

</script>