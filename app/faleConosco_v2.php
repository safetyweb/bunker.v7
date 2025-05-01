<?php 
	include 'header.php'; 
	$tituloPagina = "Contato";
	include "navegacao.php";
	$hashLocal = mt_rand();
	$msgRetorno = "";

	if( $_SERVER['REQUEST_METHOD']=='POST' ){

        $g_token = $_POST['g-recaptcha-response'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => [
                                    'secret' => '6LecLDUnAAAAANUs2utDQb9hXEkDMytLsT79P4k0',
                                    'response' => $g_token
                                ]
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $responseArr = json_decode($response,true);

        if($responseArr[success]){

            // var_dump($responseArr);
            // exit();

    		$sql = "SELECT DES_EMAIL FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
    		$qrEmail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

            $sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
            $qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmp));

    		include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
            include '../externo/email/envio_sac.php';

    		$texto='NOME: '.fnLimpaCampo($_REQUEST['NOM_CLIENTE']).
           '<br>EMAIL: '.fnLimpaCampo($_REQUEST['EMAIL']).
           '<br>Celular: '.fnLimpaCampo($_REQUEST['Celular']).        
           '<br>Menssagem :<br>'.fnLimpaCampo($_REQUEST['Soli']);

        // echo $qrEmp[NOM_FANTASI];

    		$email['email1'] = 'ricardoaugusto6693@gmail.com';
        
            $retorno = fnsacmail(
                  $email,
                  'Suporte Marka',
                  "<html>".$texto."</html>",
                  "Fale Conosco - APP",
                  "APP $qrEmp[NOM_FANTASI]",
                  $connAdm->connAdm(),
                  connTemp($cod_empresa,""),$cod_empresa);

            // echo($retorno);
            
        		$msgRetorno = "MENSAGEM ENVIADA COM <b>SUCESSO</b>!";
        		$msgTipo = 'alert-success';

        }else{

            $msgRetorno = 'A verificação do "Não sou um robô" <b>falhou</b> ou pode ter <b>expirado</b>. Por favor, tente novamente.';
            $msgTipo = 'alert-danger';

        }

        unset($_POST);
	}
?>	
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<div class="container">

		<div class="push50"></div>
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

        <form id="formulario" class="form-signin" method="post" action="faleConosco_v2.do?key=<?=fnEncode($cod_empresa)?>">
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control" placeholder="Nome" required="" autofocus="">
            <div class="push10"></div>                 
            <input type="text" name="EMAIL" id="EMAIL" class="form-control" placeholder="E-MAIL" required="" autofocus="">
            <div class="push10"></div>                 
            <input type="tel" name="Celular" id="Celular" class="form-control" placeholder="Celular" required="" autofocus="">
            <div class="push10"></div> 
            <textarea class="form-control" name ="Soli" id='Soli' placeholder="Solicitação"></textarea>
            <div class="push10"></div> 
            <center>
                <div class="g-recaptcha" data-sitekey="6LecLDUnAAAAABsd8i7O-PkbZBTIzCgvziBKGZMK"></div>
            </center>
            <!-- <input type="hidden" name="g_token" id="g_token" value=""> -->
            <div class="push20"></div> 
            <button type="submit" class="btn btn-primary btn-block" id="CAD">ENVIAR MENSAGEM</button>
        </form> 
   
   
    </div> <!-- /container -->

<?php include 'footer.php'; ?>

<script type="text/javascript">

  // function onSubmit(token) {
  //   document.getElementById("demo-form").submit();
  // }

    $("#CAD").click(function(e){
        if($("#CAD").hasClass('clicked')){
            e.preventDefault();
        }else{
            if(grecaptcha.getResponse() != ""){
                if($("#NOM_CLIENTE").val() != "" && $("#Soli").val() != "" && $("#EMAIL").val() != "" && $("#Celular").val() != ""){
                    $("#NOM_CLIENTE").attr('readonly');
                    $("#EMAIL").attr('readonly');
                    $("#Celular").attr('readonly');
                    $("#Soli").attr('readonly');
                    // $("#g_token").val(grecaptcha.getResponse());
                    $(this).html('<div class="loading" style="width:100%"></div>').addClass('clicked').attr('disabled',true);
                    $("#formulario").submit();
                }else{
                    alert('Por favor, preencha todos os dados.');
                }
            }else{
                alert('Selecione a caixa de verificação "Não sou um robô".');
            }
        }
    });
</script>