<!DOCTYPE html>
<html lang="en">
<head>
	<title>Webbix Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="login_v3/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v3/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v3/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v3/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v3/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="login_v3/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v3/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v3/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="login_v3/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Roboto+Slab:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login_v3/css/util.css">
	<link rel="stylesheet" type="text/css" href="login_v3/css/main.css">
</head>
<body style="background-color: #666666;">
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" autocomplete="off" role="form" method="post" action="_system/seguranca.php">
					<span class="login100-form-title">
						<img src="login_v3/images/logo_login2.png" width="288px">
					</span>

					<?php 
						if($_GET['msg']=='1'){                                  
							echo '<div class="alert alert-danger" role="alert" id="msgRetorno">Usuario ou senha invalido!</div>';
						}  
						if($_GET['msg']=='2'){                                  
							echo '<div class="alert alert-danger" role="alert" id="msgRetorno">Empresa bloqueada!</div>';
						} 
						if($_GET['msg']=='3'){                                  
							echo ' <div class="alert alert-danger" role="alert" id="msgRetorno">Usuario Bloqueado!</div>';
						}
					?>

					<div class="wrap-input100 validate-input" data-validate = "Campo obrigatório">
						<input class="input100" type="text" name="login" id="email" placeholder="Email" autocomplete="off">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Campo obrigatório">
						<input class="input100" type="password" name="password" id="password" placeholder="Senha" autocomplete="off">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit">
							Login
						</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">
							Esqueci
						</span>
						<a class="txt2" href="reenvioSenha.do">
							minha senha
						</a>
					</div>

					<!-- <div class="text-center p-t-136">
						<a class="txt2" href="#">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div> -->
				</form>

				<?php

					$bannerImg = array(
										"login1.jpg",
										"login2.jpg",
										"login3.jpg",
										"login4.jpg",
										"login5.jpg",
										"login6.jpg",
										"login7.jpg",
										"login8.jpg",
										"login9.jpg"
									);

					$randNb = rand(0,8);

				?>

				<div class="login100-more" style="background-image: url('login_v3/images/<?=$bannerImg[$randNb]?>');">
				</div>
			</div>
		</div>
	</div>
	
	

	
	
<!--===============================================================================================-->
	<script src="login_v3/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="login_v3/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="login_v3/vendor/bootstrap/js/popper.js"></script>
	<script src="login_v3/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="login_v3/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="login_v3/vendor/daterangepicker/moment.min.js"></script>
	<script src="login_v3/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="login_v3/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="login_v3/js/main.js"></script>

</body>
</html>