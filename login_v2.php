<?php

/*ignore_user_abort (TRUE);
switch (connection_status ()) {
                                case CONNECTION_NORMAL:
                                   $status = 'Normal';
                                   break;
                                case CONNECTION_ABORTED:
                                   $status = 'User Abort';
                                   break;
                                case CONNECTION_TIMEOUT:
                                   $status = 'Max Execution Time exceeded';
                                   break;
                                case (CONNECTION_ABORTED & CONNECTION_TIMEOUT):
                                   $status = 'Aborted and Timed Out';
                                   break;
                                default:
                                   $status = 'Unknown';
                                   break;
}
file_put_contents('test.txt',$status);*/

$arrBackground = array();
$arrBackgroundMob = array();

for ($i=1; $i < 13; $i++) { 
	array_push($arrBackground,"login_$i.jpg");
	array_push($arrBackgroundMob,"login_".$i."_sm.jpg");
}

shuffle($arrBackground);
shuffle($arrBackgroundMob);
// print_r($arrBackgroundMob);
// exit();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

 	<!-- <link rel="apple-touch-icon" sizes="180x180" href="images/favicons/favicon-160x160.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicons/favicon-32x32.png"> -->
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicons/bunker_favicon_2.png">

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
	


<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->


<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
<!--  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet"> -->
<link rel="stylesheet" type="text/css" href="css/fontawesome-pro-5.13.0-web/css/all.min.css" />
<link href="login_v2.css" rel="stylesheet">
<!-- JQUERY-CONFIRM -->
<link href="css/jquery-confirm.min.css" rel="stylesheet"/>
	
	
	
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


	
</head>

<style type="text/css">
	.page-header {
	    
	     margin: 0; 
	}
	.form-control {
		height: 40px;
	}
	.login-page .card-login .logo-container {
     width: 100%;
     height: 30px;
     margin-bottom: 15px;
   }
   .btn-primary{
	   color: #ffffff;
	   background-color: #1A242F;
	   border-color: #1A242F;
   }
   .btn-primary:hover{
    	color: #ffffff;
	   background-color: #2c3e50;
	   border-color: #2c3e50;
   }
   .btn-primary:focus{
    	color: #ffffff;
	   background-color: #2c3e50;
	   border-color: #2c3e50;
   }
   .btn-primary:active{
    	color: #ffffff;
	   background-color: #1A242F;
	   border-color: #1A242F;
   }
   .content{
   	margin-top: 80px!important;
   }

   .login-form{
   	margin-top:2%;
   }

   .navbar-nav{
   	display: none;
   }

   .logo-bunker{
   	margin-left: 20px;
   }

   .small-link{
   	font-size: 8px!important;
   }

   .login-page .card-login .input-group:last-child {
	    margin-bottom: 10px;
	}

	.card-login{
		background-color: rgba(255, 255, 255, .08);  
 		backdrop-filter: blur(5px);
 		padding: 7px!important;
 		max-width: 420px!important;
 		border-radius: 20px!important;
	}

	.logo-div{
		padding-left: 0px;
		padding-right: 0px;
	}

	.logotipo{
		width: 65%!important;
	}

	.logo-container{
		margin-top: 10px!important;
	}

	.jconfirm-box-container{
		margin-left: auto!important;
		margin-right: auto!important;
	}

	.alert-danger{
		color: #fff;
		background-color: #FF5252;
		border-color: #FF5252;
		border-radius: 7px;
	}

	.navbar .navbar-nav .nav-link:not(.btn){
		font-size: 11px!important;
	}

	@media only screen and (max-width: 768px) and (orientation: portrait){
		.page-header-image {
		    background: #24b2d6 url('./images/backgrounds/mobile/<?=$arrBackgroundMob[0]?>')!important;
		    background-size:     cover!important;
		    background-repeat:   no-repeat!important;
		    background-position: center center!important;
		}
	}

	@media only screen and (max-width: 800px) and (orientation: portrait){
		.page-header-image {
		    background: #24b2d6 url('./images/backgrounds/mobile/<?=$arrBackgroundMob[0]?>')!important;
		    background-size:     cover!important;
		    background-repeat:   no-repeat!important;
		    background-position: center center!important;
		}
	}
	@media only screen and (min-device-width: 320px) and (max-device-width: 480px){
		.page-header-image {
		    background: #24b2d6 url('./images/backgrounds/mobile/<?=$arrBackgroundMob[0]?>')!important;
		    background-size:     cover!important;
		    background-repeat:   no-repeat!important;
		    background-position: center center!important;
		}
	}
		



	@media only screen and (min-width: 768px) {
		/* For desktop: */
		.login-form{
	   	margin-top:15%;
	   }

	   .navbar-nav{
	   	display: block;
	   }

	   .logo-bunker{
	   	margin-left: 0px;
	   }

	   .small-link{
	   	font-size: 10px!important;
	   }

	   .card-login{
	 		padding: 40px!important;
		}

		.logotipo{
			width: 100%!important;
		}

		.logo-container{
			margin-top: unset;
		}

	}

</style>

<body class="login-page sidebar-collapse">
	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg bg-primary fixed-top navbar-transparent " color-on-scroll="400">
		<div class="container">
			<!-- <div class="dropdown button-dropdown">
				<a href="#pablo" class="dropdown-toggle" id="navbarDropdown" data-toggle="dropdown">
					<span class="button-bar"></span>
					<span class="button-bar"></span>
					<span class="button-bar"></span>
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-header">Dropdown header</a>
					<a class="dropdown-item" href="#">Action</a>
					<a class="dropdown-item" href="#">Another action</a>
					<a class="dropdown-item" href="#">Something else here</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#">Separated link</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#">One more separated link</a>
				</div>
			</div> -->
			<div class="navbar-translate">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="https://marka.mk/">Marka Fidelização</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://mcash.com.br/">Mais.CA$H</a>
					</li>
				</ul>
			</div>
			<div class="collapse navbar-collapse justify-content-end" id="navigation" data-nav-image="../assets/img/blurred-image-1.jpg">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" rel="tooltip" title="Follow us on Twitter" data-placement="bottom" href="https://twitter.com/" target="_blank">
							<i class="fab fa-twitter"></i>
							<p class="d-lg-none d-xl-none">Twitter</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" rel="tooltip" title="Like us on Facebook" data-placement="bottom" href="https://www.facebook.com/" target="_blank">
							<i class="fab fa-facebook-square"></i>
							<p class="d-lg-none d-xl-none">Facebook</p>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" rel="tooltip" title="Follow us on Instagram" data-placement="bottom" href="https://www.instagram.com/" target="_blank">
							<i class="fab fa-instagram"></i>
							<p class="d-lg-none d-xl-none">Instagram</p>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<!-- End Navbar -->
	<div class="page-header">
	<!-- <div class="page-header clear-filter" filter-color="orange"> -->
		<div class="page-header-image" style="background-image:url('./images/backgrounds/desktop/<?=$arrBackground[0]?>?auto=format&fit=crop&w=2378&q=80 2378w')"></div>
		<div class="content">


			<div class="container">
				<div class="col-md-6 col-md-offset-3 col-xs-12 login-form">
					<div class="card card-login card-plain">
						<form class="form" method="post" action="_system/seguranca.php">
							<div class="card-header text-center">
								<div class="logo-container">
										<!-- <img src="./images/logos/bunker_logo.png" alt="">
										<img src="./images/logos/mais_cash_logo.png" alt=""> -->
									<div class="col-md-6 col-xs-12 logo-div logo-bunker">
										<img src="./images/logos/logo_bunker_v1.png" alt="" class="logotipo">
									</div>
									<div class="col-md-6 col-xs-12 logo-div">
										<img src="./images/logos/logo_mc_v1.png" alt="" class="logotipo">
									</div>
								</div>

								<div class="col-md-12" style="margin-bottom: 10px;">
									Plataformas de Cashback, Fidelização, Promoção e CRM
								</div>

							</div>
							<div class="card-body">
								<div class="col-xs-12">
									<?php 

								      if($_GET['msg']=='1')
								      {                                  
								         echo '<div class="alert alert-danger text-left" role="alert" id="msgRetorno">Usuario ou senha invalido!</div>';
								      }  
								      if($_GET['msg']=='2')
								      {                                  
								         echo '<div class="alert alert-danger text-left" role="alert" id="msgRetorno">Empresa bloqueada!</div>';
								      } 
								      if($_GET['msg']=='3')
								      {                                  
								         echo ' <div class="alert alert-danger text-left" role="alert" id="msgRetorno">Usuario Bloqueado!</div>';
								      }
							   
							      ?> 
								</div>
								<div class="input-group no-border input-lg">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fal fa-user"></i>
										</span>
									</div>
									<input type="text" name="login" id="email" placeholder="Usuário" class="form-control">
								</div>
								<div class="input-group no-border input-lg">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fal fa-lock"></i>
										</span>
									</div>
									<input type="password" name="password" id="password" placeholder="Senha" class="form-control" />
								</div>
							</div>
							<div class="card-footer text-center" style="padding: 5px;">
								<div class="col-md-12">
									<button type="submit" class="btn btn-primary btn-round btn-lg btn-block" id="btnLogin">Login</button>
									<!-- <div class="pull-left">
										<h6>
											<a href="#pablo" class="link small-link">Criar conta</a>
										</h6>
									</div> -->
									<div class="pull-right">
										<h6>
											<a href="reenvioSenha_v2.do" class="link small-link">Esqueci minha senha</a>
										</h6>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				Powered by <img src="./images/logos/marka_small.png" alt="">
				<!-- <img src="./images/logos/selo_25_anos.png" alt=""> -->
			</div>
			<div class="col-md-12" style="margin-top: 20px;">
				<img src="./images/logos/selo_marka.png" alt="" width="90px">
			</div>
		</div>
		<footer class="footer">
			<div class="container">
				<!-- <nav>
					<ul>
						<li>
							<a href="https://marka.mk/">
								Marka Fidelização
							</a>
						</li>
						<li>
							<a href="https://mcash.com.br/">
								Mais CA$H
							</a>
						</li>
					</ul>
				</nav> -->
				<div class="copyright" id="copyright">
							
					
					
				</div>
			</div>
		</footer>

	<!--   Core JS Files   -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="js/jquery-confirm.min.js"></script>


</body>

</html>
<?php

ignore_user_abort (FALSE);
?>

<script>

	let getVar = "<?=$_GET[msg]?>",
		 msg = "";

	$(function(){

		// if(getVar != ""){

		// 	if(getVar=='1')
	 //      {                                  
	 //         msg = 'Usuario ou senha invalido!';
	 //      }  
	 //      if(getVar=='2')
	 //      {                                  
	 //         msg = 'Empresa bloqueada!';
	 //      } 
	 //      if(getVar=='3')
	 //      {                                  
	 //         msg = 'Usuario Bloqueado!';
	 //      }

	 //      $.alert({
  //           title: "Aviso",
  //           content: msg,
  //           buttons: {
		// 			Ok: function () {
							
		// 			}
		// 		}
  //        });

		// }

		$("#btnLogin").on('click', function(e){
			// alert();
			if($("#email").val() == "" || $("#password").val() == ""){
				e.preventDefault();
				$.alert({
               title: "Aviso",
               content: "Usuário/Senha vazio(s).",
               type: 'red',
               buttons: {
						Ok: function () {
								
						}
					}
            });
			}
		});

	});
</script>