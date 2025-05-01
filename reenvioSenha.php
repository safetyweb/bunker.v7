<?php

include '_system/_functionsMain.php';
//include '_system/PHPMailer/class.phpmailer.php';

// echo fnDebug('true');

// $arrayCampos = explode(";", $key);


$hashLocal = mt_rand();
$msgRetorno = "";
$msgTipo = "";
// $cod_empresa = 19; 

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
  
    $_SESSION['last_request']  = $request;

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

	    $log_usuario = fnLimpaCampo($_REQUEST['LOG_USUARIO']);

	    $sql = "SELECT NOM_USUARIO, DES_SENHAUS, DES_EMAILUS FROM USUARIOS WHERE LOG_USUARIO = '$log_usuario' AND LOG_ESTATUS = 'S' ORDER BY COD_USUARIO DESC LIMIT 1";
	    // fnEscreve($sql);
	    $arrayQuery = mysqli_query($connAdm->connAdm(),trim($sql));

	    $linhas = mysqli_num_rows($arrayQuery);

	    if($linhas == 1){

	    	$qrSenha = mysqli_fetch_assoc($arrayQuery);
	    	// MONTAGEM DO E-MAIL
	       
	        include 'externo/email/envio_sac.php';

	        $nome = explode(" ",$qrSenha['NOM_USUARIO']);

	    	$texto_envio = "Olá ".ucfirst(strtolower($nome[0])).",<br>Conforme solicitado, estamos enviando abaixo a sua senha de cadastro:<br><b>".fnDecode($qrSenha['DES_SENHAUS'])."</b>";
	    	$emailDestino = array('email5'=>"suporte@markafidelizacao.com.br;$qrSenha[DES_EMAILUS]");
	    	$retorno = fnsacmail(
					$emailDestino,
					'Suporte Marka',
					"<html>".$texto_envio."</html>",
					"Recuperação de senha Bunker",
					'Bunker',
					$connAdm->connAdm(),
					connTemp(3,""),'3');

	        $email_repartido = explode("@", $qrSenha['DES_EMAILUS']);
	        $tam_email = strlen($email_repartido[0]);
	        $tam_email_calculado = round(($tam_email*30)/100);
	        $tam_email_escondido = $tam_email - $tam_email_calculado;
	        $email_apresentado = substr($email_repartido[0],0,-$tam_email_escondido);

	        $pontos = "";
	        for ($i=1; $i <=$tam_email_escondido ; $i++) { 
	           $pontos .= "*";
	        }

	    	$msgRetorno = "Sua senha foi enviada ao seu email de cadastro:<br>".$email_apresentado.$pontos."@".$email_repartido[1];
	    	$msgTipo = "alert-success";
	        
	    }else{

	    	$msgRetorno = "Login não encontrado.";
			$msgTipo = 'alert-warning';

	    } 

	}else{
		$msgRetorno = 'A verificação do "Não sou um robô" <b>falhou</b> ou pode ter <b>expirado</b>. Por favor, tente novamente.';
        $msgTipo = 'alert-danger';
	}

}	

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/full-slider.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
    </script>
	
    <script type="text/javascript" src="js/plugins/trianglify.min.js"></script>	
	
	<script type='text/javascript'>
	window.onload=function(){
	function addTriangleTo(target) {
		var dimensions = target.getClientRects()[0];
		var pattern = Trianglify({
			width: dimensions.width, 
			height: dimensions.height,			
			x_colors: 'Blues'
		});
		target.style['background-image'] = 'url(' + pattern.png() + ')';
	}
	// addTriangleTo(document.getElementById('fullScreen'));
	}//]]> 

	var w = window.innerWidth;
	var h = window.innerHeight;		
	
	</script>	
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


  <style type="text/css">

  	body, .fill{
  		background-color: #2C3E50!important;
  		overflow: auto!important;
  	}
  	
	.carousel-caption {
	  position: absolute;
	  right: 0;
	  top: 20px;
	  left: 0;
	  z-index: 10;
	  padding-top: 20px;
	  padding-bottom: 20px;
	  color: #fff;
	  text-align: center;
	  text-shadow: 0 1px 2px rgba(0, 0, 0, .6);
	}
	
	.colorgraph {
	  height: 1px;
	  border-top: 0;
	  background: #FFF;
	  border-radius: 5px;
	  margin: 5px 0;
	  /*background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
	  background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
	  background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
	  background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);*/
	}

	.shadowTxt{
	text-shadow: 0 3px 3px rgba(0,0,0,.4);
	}
	
	/*.shadow{
	box-shadow: 0 0 5px rgba(255, 255, 255, .4);
	}*/

	.btn span:nth-of-type(1)  {        		
		display: none;
	}
	.btn span:last-child  {            	
		display: block;		
	}

	.btn.active  span:nth-of-type(1)  {            	
		display: block;		
	}
	
	.btn.active span:last-child  {            	
		display: none;			
	}

	.btn-custom { text-align: left;}
	.btn-label {position: relative;left: -12px;display: inline-block;padding: 6px 12px;background: rgba(0,0,0,0.15);border-radius: 3px 0 0 3px;}
	.btn-labeled {padding-top: 0;padding-bottom: 0;}
	/*body {background-color: #cecece;}*/
	.alert-danger {background-color: #E74C3C; color: #fff; text-align: left; font-size: 16px;}

	.text-medium{
		margin-bottom: 2px;
		font-size: 17px;
		color: #FFF;
	}

	.text-small{
		margin-bottom: 2px;
		font-size: 14px;
		color: #FFF;
	}

	.text-smaller{
		margin-bottom: 2px;
		font-size: 11px;
		color: #FFF;
	}

	.push5{
		height: 5px;
		width: 100%;
		clear: both;
	}

	.push10{
		height: 10px;
		width: 100%;
		clear: both;
	}

	.push20{
		height: 20px;
		width: 100%;
		clear: both;
	}

	.push30{
		height: 30px;
		width: 100%;
		clear: both;
	}

	.push50{
		height: 50px;
		width: 100%;
		clear: both;
	}

	.push100{
		height: 100px;
		width: 100%;
		clear: both;
	}
	
  </style>	
	
</head>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<body>

	<div class="container">

		<div class="row">
			<div class="col-xs-12 col-sm-8 col-md-1">
			</div>
			<div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-3">
			
           
               <form id="formulario" role="form" method="post" action="reenvioSenha.do">
			   
					<fieldset>
						<!--<h2><img src="media\clientes\marka_white_big.png" width="80%"></h2>-->
						<br/>
						<br/>
						<div class='text-center'><img src="images/logo_bunker.png"></div>

						<br/>
						<br/>

						<hr class="colorgraph shadow">

						<br/> 

                         <?php
	                       if($msgRetorno != ''){
	                    ?>
	                    	<div class="alert <?=$msgTipo?>" role="alert" id="msgRetorno"><?=$msgRetorno?></div>
	                    <?php                                
	                       }
                        ?>                    
                                                            
						<div class="form-group">
							<input type="text" name="LOG_USUARIO" id="LOG_USUARIO" class="form-control input-lg shadow" placeholder="Usuário">
						</div>

						<br/>

						<div class="form-group">
							<center>
				                <div class="g-recaptcha" data-sitekey="6LecLDUnAAAAABsd8i7O-PkbZBTIzCgvziBKGZMK"></div>
				            </center>
						</div>

						<br/> 
						
						<hr class="colorgraph shadow">

						<br/>
						<br/>			

						<div class="row">
							<div class="col-xs-6 col-sm-6 col-md-6">										
								<a href="index.do" class="btn btn-lg btn-info btn-block shadow">Voltar</a>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6">
							    <input type="submit" class="btn btn-lg btn-success btn-block shadow" id="CAD" value="Reenviar senha">											
							</div>
						</div>
						
					</fieldset>								
				</form>
			</div>
			<div class="push100"></div>
			
		</div>

	</div>
    
    
    <!-- Full Page Image Background Carousel Header -->
    <!-- <header id="myCarousel" class="carousel slide">


        <div class="carousel-inner">
		
            <div class="item active">

                <div class="fill" id="fullScreen"></div>
                <div class="carousel-caption">
							
								
				
                    
                </div>				
            </div>
			
        </div>

    </header> -->

</body>

<script>

	let getVar = "<?=$msgRetorno?>";

	$(function(){

		if(getVar != ""){

		    $.alert({
	            title: "Aviso",
	            content: getVar,
	            buttons: {
						Ok: function () {
								
						}
					}
		    });

		}

		$("#CAD").click(function(e){
	        if($("#CAD").hasClass('clicked')){
	            e.preventDefault();
	        }else{
	            if(grecaptcha.getResponse() != ""){
	                if($("#LOG_USUARIO").val() != "" ){
	                    $("#LOG_USUARIO").attr('readonly');
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

	});
</script>

</html>
<?php

ignore_user_abort (FALSE);
?>