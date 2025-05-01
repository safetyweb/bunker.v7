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
		window.onload = function() {
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
		} //]]> 

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
		body,
		.fill {
			background-color: #2C3E50 !important;
			overflow: auto !important;
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

		.shadowTxt {
			text-shadow: 0 3px 3px rgba(0, 0, 0, .4);
		}

		/*.shadow{
	box-shadow: 0 0 5px rgba(255, 255, 255, .4);
	}*/

		.btn span:nth-of-type(1) {
			display: none;
		}

		.btn span:last-child {
			display: block;
		}

		.btn.active span:nth-of-type(1) {
			display: block;
		}

		.btn.active span:last-child {
			display: none;
		}

		.btn-custom {
			text-align: left;
		}

		.btn-label {
			position: relative;
			left: -12px;
			display: inline-block;
			padding: 6px 12px;
			background: rgba(0, 0, 0, 0.15);
			border-radius: 3px 0 0 3px;
		}

		.btn-labeled {
			padding-top: 0;
			padding-bottom: 0;
		}

		/*body {background-color: #cecece;}*/
		.alert-danger {
			background-color: #E74C3C;
			color: #fff;
			text-align: left;
			font-size: 16px;
		}

		.text-medium {
			margin-bottom: 2px;
			font-size: 17px;
			color: #FFF;
		}

		.text-small {
			margin-bottom: 2px;
			font-size: 14px;
			color: #FFF;
		}

		.text-smaller {
			margin-bottom: 2px;
			font-size: 11px;
			color: #FFF;
		}

		.push5 {
			height: 5px;
			width: 100%;
			clear: both;
		}

		.push10 {
			height: 10px;
			width: 100%;
			clear: both;
		}

		.push20 {
			height: 20px;
			width: 100%;
			clear: both;
		}

		.push30 {
			height: 30px;
			width: 100%;
			clear: both;
		}

		.push50 {
			height: 50px;
			width: 100%;
			clear: both;
		}

		.push100 {
			height: 100px;
			width: 100%;
			clear: both;
		}
	</style>

</head>

<body>

	<div class="container">

		<div class="row">
			<div class="col-xs-12 col-sm-8 col-md-1">
			</div>
			<div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-3">


				<form role="form" method="post" action="_system/seguranca.php">

					<fieldset>
						<!--<h2><img src="media\clientes\marka_white_big.png" width="80%"></h2>-->
						<br />
						<br />
						<div class='text-center'><img src="images/logo_bunker.png"></div>

						<br />
						<br />

						<hr class="colorgraph shadow">

						<div class="text-center">
							<p class="text-medium">Plataforma de Promoção e Fidelização</p>
							<p class="text-small"><i>Promotion and Loyalty Platform</i></p>
						</div>

						<hr class="colorgraph shadow">

						<br />

						<?php
						if (@$_GET['msg'] == '1') {
							echo '<div class="alert alert-danger" role="alert" id="msgRetorno">Usuario ou senha invalido!</div>';
						}
						if (@$_GET['msg'] == '2') {
							echo '<div class="alert alert-danger" role="alert" id="msgRetorno">Empresa bloqueada!</div>';
						}
						if (@$_GET['msg'] == '3') {
							echo ' <div class="alert alert-danger" role="alert" id="msgRetorno">Usuario Bloqueado!</div>';
						}

						?>

						<div class="form-group">
							<input type="text" name="login" id="email" class="form-control input-lg shadow" placeholder="Usuário">
						</div>
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg shadow" placeholder="Senha">
							<div class="push5"></div>
							<a href="reenvioSenha.do" style="color: #FFF; font-size: 12px;">Esqueci minha senha</a>
						</div>

						<!-- <span class="button-checkbox" style="display:none;">
						<div class="row">
							<div class="col-xs-6 col-sm-6 col-md-6">
                                                                        
								<div class="btn-group" data-toggle="buttons">
									<label class="btn btn-xs btn-default shadow active">				
										<span class="">
											<i class="glyphicon glyphicon-ok"></i> Manter logado
										</span>
										<input type="checkbox" autocomplete="off" checked>
										<span class="">
											<i class="glyphicon glyphicon-ban-circle"></i> Não manter logado
										</span>
									</label>
								</div>										
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6">
								<button type="button" class="btn btn-xs btn-default shadow active" data-color="info"><i class="glyphicon glyphicon-star-empty" aria-hidden="true"></i> Esqueci a senha</button>
							</div>
						</div>
						</span> -->

						<hr class="colorgraph shadow">

						<div class="text-center">
							<p class="text-small"><small>Método exclusivo de Fidelização, Modular, Crescente e Contínua.</small></p>
							<p class="text-smaller"><small><i>Exclusive Method of Loyalty, Modular, Crescent and Continuous.</i></small></p>
						</div>

						<hr class="colorgraph shadow">

						<br />
						<br />

						<div class="row">
							<div class="col-xs-6 col-sm-6 col-md-6">
								<input type="reset" class="btn btn-lg btn-info btn-block shadow" value="Op's, errei">
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6">
								<input type="submit" class="btn btn-lg btn-success btn-block shadow" value="Login">
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

</html>
<?php

ignore_user_abort(FALSE);
?>